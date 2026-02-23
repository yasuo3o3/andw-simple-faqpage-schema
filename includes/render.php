<?php
/**
 * フロントエンド描画（ブロックのrender_callback）
 *
 * @package AndwSimpleFaqpageSchema
 */

// 直接アクセス禁止
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FAQブロックのフロントエンド描画
 *
 * @param array $attributes ブロック属性
 * @return string HTML出力
 */
function andw_faq_schema_render_block( $attributes ) {
	$faqs          = isset( $attributes['faqs'] ) && is_array( $attributes['faqs'] ) ? $attributes['faqs'] : array();
	$heading_level = isset( $attributes['headingLevel'] ) ? absint( $attributes['headingLevel'] ) : 3;

	// 見出しレベルを2〜4に制限
	if ( $heading_level < 2 || $heading_level > 4 ) {
		$heading_level = 3;
	}

	$tag = 'h' . $heading_level;

	$output = '';

	foreach ( $faqs as $faq ) {
		$question = isset( $faq['question'] ) ? trim( $faq['question'] ) : '';
		$answer   = isset( $faq['answer'] ) ? trim( $faq['answer'] ) : '';

		// 空の項目はスキップ
		if ( '' === $question || '' === $answer ) {
			continue;
		}

		$output .= '<' . esc_attr( $tag ) . ' class="andw-faq-question">' . esc_html( $question ) . '</' . esc_attr( $tag ) . '>';
		$output .= wp_kses_post( $answer );
	}

	return $output;
}
