<?php
/**
 * Plugin Name: andW Simple FAQPage Schema
 * Description: FAQPage構造化データ（JSON-LD）を生成するシンプルなGutenbergブロック
 * Version: 0.1.0
 * Author: yasuo3o3
 * Author URI: https://yasuo-o.xyz/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: andw-simple-faqpage-schema
 * Requires at least: 6.4
 * Requires PHP: 7.4
 *
 * @package AndwSimpleFaqpageSchema
 */

// 直接アクセス禁止
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// プラグイン定数
define( 'ANDW_FAQ_SCHEMA_VERSION', '0.1.0' );
define( 'ANDW_FAQ_SCHEMA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ANDW_FAQ_SCHEMA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// 設定ページの読み込み
require_once ANDW_FAQ_SCHEMA_PLUGIN_DIR . 'includes/settings.php';

// フロントエンド描画・Schema出力の読み込み
require_once ANDW_FAQ_SCHEMA_PLUGIN_DIR . 'includes/render.php';

/**
 * ブロックの登録
 */
function andw_faq_schema_register_block() {
	register_block_type(
		ANDW_FAQ_SCHEMA_PLUGIN_DIR . 'build',
		array(
			'render_callback' => 'andw_faq_schema_render_block',
		)
	);
}
add_action( 'init', 'andw_faq_schema_register_block' );

/**
 * FAQPage JSON-LD Schema を wp_head で出力
 */
function andw_faq_schema_output_jsonld() {
	// フロントエンドの単一投稿・固定ページのみ
	if ( is_admin() || ! is_singular() ) {
		return;
	}

	$post = get_post();
	if ( ! $post || ! has_block( 'andw/faq-schema', $post ) ) {
		return;
	}

	// 投稿内の全FAQブロックからFAQ項目を収集
	$blocks   = parse_blocks( $post->post_content );
	$all_faqs = andw_faq_schema_collect_faqs( $blocks );

	if ( empty( $all_faqs ) ) {
		return;
	}

	// Schema組み立て
	$main_entity = array();
	foreach ( $all_faqs as $faq ) {
		$main_entity[] = array(
			'@type'          => 'Question',
			'name'           => $faq['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => $faq['answer'],
			),
		);
	}

	$schema = array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $main_entity,
	);

	// JSON-LD出力（wp_json_encodeでXSS対策）
	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'andw_faq_schema_output_jsonld' );

/**
 * ブロック配列から全FAQブロックのFAQ項目を再帰的に収集
 *
 * @param array $blocks パースされたブロック配列
 * @return array FAQ項目の配列
 */
function andw_faq_schema_collect_faqs( $blocks ) {
	$faqs = array();

	foreach ( $blocks as $block ) {
		if ( 'andw/faq-schema' === $block['blockName'] ) {
			if ( ! empty( $block['attrs']['faqs'] ) && is_array( $block['attrs']['faqs'] ) ) {
				foreach ( $block['attrs']['faqs'] as $faq ) {
					$question = isset( $faq['question'] ) ? trim( $faq['question'] ) : '';
					$answer   = isset( $faq['answer'] ) ? trim( $faq['answer'] ) : '';

					// 空の項目はスキップ
					if ( '' === $question || '' === $answer ) {
						continue;
					}

					$faqs[] = array(
						'question' => $question,
						'answer'   => $answer,
					);
				}
			}
		}

		// インナーブロックの再帰処理
		if ( ! empty( $block['innerBlocks'] ) ) {
			$faqs = array_merge( $faqs, andw_faq_schema_collect_faqs( $block['innerBlocks'] ) );
		}
	}

	return $faqs;
}

/**
 * 見出しリセットCSS・カスタムCSSのフロント出力
 */
function andw_faq_schema_enqueue_front_styles() {
	if ( is_admin() || ! is_singular() ) {
		return;
	}

	$post = get_post();
	if ( ! $post || ! has_block( 'andw/faq-schema', $post ) ) {
		return;
	}

	$reset_heading = get_option( 'andw_faq_schema_reset_heading', false );
	$custom_css    = get_option( 'andw_faq_schema_custom_css', '' );

	$css = '';

	// 見出し装飾リセット
	if ( $reset_heading ) {
		$css .= '.andw-faq-question{font-size:inherit;font-weight:normal;margin:0;padding:0;border:none;line-height:inherit;letter-spacing:normal;text-transform:none;color:inherit;background:none;}';
	}

	// カスタムCSS
	if ( '' !== $custom_css ) {
		$css .= wp_strip_all_tags( $custom_css );
	}

	if ( '' !== $css ) {
		wp_register_style( 'andw-faq-schema-front', false, array(), ANDW_FAQ_SCHEMA_VERSION );
		wp_enqueue_style( 'andw-faq-schema-front' );
		wp_add_inline_style( 'andw-faq-schema-front', $css );
	}
}
add_action( 'wp_enqueue_scripts', 'andw_faq_schema_enqueue_front_styles' );
