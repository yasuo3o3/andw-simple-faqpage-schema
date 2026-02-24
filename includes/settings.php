<?php
/**
 * 設定ページ（見出しリセット + カスタムCSS）
 *
 * @package AndwSimpleFaqpageSchema
 */

// 直接アクセス禁止
if (!defined('ABSPATH')) {
	exit;
}

/**
 * 設定の登録
 */
function andw_faq_schema_register_settings()
{
	// 設定グループ
	register_setting(
		'andw_faq_schema_settings',
		'andw_faq_schema_reset_heading',
		array(
			'type' => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default' => false,
		)
	);

	register_setting(
		'andw_faq_schema_settings',
		'andw_faq_schema_custom_css',
		array(
			'type' => 'string',
			'sanitize_callback' => 'andw_faq_schema_sanitize_css',
			'default' => '',
		)
	);

	register_setting(
		'andw_faq_schema_settings',
		'andw_faq_schema_disable_jsonld',
		array(
			'type' => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default' => false,
		)
	);

	// セクション: 見出しスタイル設定
	add_settings_section(
		'andw_faq_schema_heading_section',
		esc_html__('見出しスタイル設定', 'andw-simple-faqpage-schema'),
		'andw_faq_schema_heading_section_cb',
		'andw-faq-schema-settings'
	);

	// セクション: 出力設定
	add_settings_section(
		'andw_faq_schema_output_section',
		esc_html__('表示・出力設定', 'andw-simple-faqpage-schema'),
		'andw_faq_schema_output_section_cb',
		'andw-faq-schema-settings'
	);

	// フィールド: 見出し装飾リセット
	add_settings_field(
		'andw_faq_schema_reset_heading',
		esc_html__('見出し装飾リセット', 'andw-simple-faqpage-schema'),
		'andw_faq_schema_reset_heading_cb',
		'andw-faq-schema-settings',
		'andw_faq_schema_heading_section'
	);

	// フィールド: カスタムCSS
	add_settings_field(
		'andw_faq_schema_custom_css',
		esc_html__('カスタムCSS', 'andw-simple-faqpage-schema'),
		'andw_faq_schema_custom_css_cb',
		'andw-faq-schema-settings',
		'andw_faq_schema_heading_section'
	);

	// フィールド: JSON-LDの無効化
	add_settings_field(
		'andw_faq_schema_disable_jsonld',
		esc_html__('JSON-LD無効化', 'andw-simple-faqpage-schema'),
		'andw_faq_schema_disable_jsonld_cb',
		'andw-faq-schema-settings',
		'andw_faq_schema_output_section'
	);
}
add_action('admin_init', 'andw_faq_schema_register_settings');

/**
 * CSSサニタイズ
 *
 * @param string $value 入力値
 * @return string サニタイズ済みCSS
 */
function andw_faq_schema_sanitize_css($value)
{
	// HTMLタグを除去し、テキストとしてサニタイズ
	return wp_strip_all_tags($value);
}

/**
 * セクション説明
 */
function andw_faq_schema_heading_section_cb()
{
	echo '<p>' . esc_html__('FAQ見出し（.andw-faq-question）のスタイルを制御します。', 'andw-simple-faqpage-schema') . '</p>';
}

/**
 * 出力設定セクション説明
 */
function andw_faq_schema_output_section_cb()
{
	echo '<p>' . esc_html__('FAQPageスキーマおよびフロントエンド出力に関する設定です。', 'andw-simple-faqpage-schema') . '</p>';
}

/**
 * 見出し装飾リセットフィールド
 */
function andw_faq_schema_reset_heading_cb()
{
	$value = get_option('andw_faq_schema_reset_heading', false);
	?>
	<label>
		<input type="checkbox" name="andw_faq_schema_reset_heading" value="1" <?php checked($value); ?>>
		<?php echo esc_html__('テーマの見出し装飾をリセットする', 'andw-simple-faqpage-schema'); ?>
	</label>
	<p class="description">
		<?php echo esc_html__('ONにすると、FAQ見出しのフォントサイズ・太字・余白等がリセットされ、通常のテキストと同じ見た目になります。', 'andw-simple-faqpage-schema'); ?>
	</p>
	<?php
}

/**
 * カスタムCSSフィールド
 */
function andw_faq_schema_custom_css_cb()
{
	$value = get_option('andw_faq_schema_custom_css', '');
	?>
	<textarea name="andw_faq_schema_custom_css" rows="8" cols="60"
		class="large-text code"><?php echo esc_textarea($value); ?></textarea>
	<p class="description">
		<?php echo esc_html__('FAQ見出しに適用するカスタムCSSを記述できます。セレクタ .andw-faq-question を使用してください。', 'andw-simple-faqpage-schema'); ?>
	</p>
	<?php
}

/**
 * JSON-LD無効化フィールド
 */
function andw_faq_schema_disable_jsonld_cb()
{
	$value = get_option('andw_faq_schema_disable_jsonld', false);
	?>
	<label>
		<input type="checkbox" name="andw_faq_schema_disable_jsonld" value="1" <?php checked($value); ?>>
		<?php echo esc_html__('JSON-LD スキーマ出力を無効にする（HTMLのみ出力）', 'andw-simple-faqpage-schema'); ?>
	</label>
	<p class="description">
		<?php echo esc_html__('他のSEOプラグインでFAQスキーマを管理する場合はオンにしてください。FAQ の見出し・回答の HTML 表示は維持されます。', 'andw-simple-faqpage-schema'); ?>
	</p>
	<?php
}

/**
 * 設定ページをメニューに追加
 */
function andw_faq_schema_add_settings_page()
{
	add_options_page(
		esc_html__('FAQ Schema 設定', 'andw-simple-faqpage-schema'),
		esc_html__('FAQ Schema', 'andw-simple-faqpage-schema'),
		'manage_options',
		'andw-faq-schema-settings',
		'andw_faq_schema_settings_page'
	);
}
add_action('admin_menu', 'andw_faq_schema_add_settings_page');

/**
 * 設定ページの描画
 */
function andw_faq_schema_settings_page()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	// 競合プラグインのチェック
	include_once ABSPATH . 'wp-admin/includes/plugin.php';
	$conflicting_plugins = array();

	if (is_plugin_active('wordpress-seo/wp-seo.php')) {
		$conflicting_plugins[] = 'Yoast SEO';
	}
	if (class_exists('RankMath')) {
		$conflicting_plugins[] = 'Rank Math SEO';
	}
	// AIOSEO v4系以降を検知対象とする（v3系以前の all_in_one_seo_pack は対象外）
	if (class_exists('AIOSEO\Plugin\AIOSEO')) {
		$conflicting_plugins[] = 'All in One SEO';
	}

	?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>

		<?php if (!empty($conflicting_plugins)): ?>
			<div class="notice notice-warning inline">
				<p>
					<strong><?php echo esc_html__('【注意】FAQスキーマの重複の可能性があります', 'andw-simple-faqpage-schema'); ?></strong><br>
					<?php
					printf(
						/* translators: %s: 検出されたプラグイン名のカンマ区切りリスト */
						esc_html__('%s が有効です。FAQスキーマの重複を避けるため、他プラグイン側のFAQスキーマ出力を無効にするか、本プラグインの設定で「JSON-LD スキーマ出力を無効にする」をオンにしてください。', 'andw-simple-faqpage-schema'),
						esc_html(implode(' / ', $conflicting_plugins))
					);
					?>
				</p>
			</div>
		<?php endif; ?>

		<form action="options.php" method="post">
			<?php
			settings_fields('andw_faq_schema_settings');
			do_settings_sections('andw-faq-schema-settings');
			submit_button();
			?>
		</form>
	</div>
	<?php
}
