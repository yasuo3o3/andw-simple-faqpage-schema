<?php
/**
 * アンインストール処理
 *
 * プラグイン削除時にのみ実行。
 * 当プラグインが保存したオプションのみを削除する。
 *
 * @package AndwSimpleFaqpageSchema
 */

// WordPress経由の実行でなければ終了
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// 当プラグインが保存したオプションを削除
delete_option( 'andw_faq_schema_reset_heading' );
delete_option( 'andw_faq_schema_custom_css' );
