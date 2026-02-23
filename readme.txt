=== andW Simple FAQPage Schema ===
Contributors: yasuo3o3
Tags: faq, schema, structured-data, json-ld, seo
Requires at least: 6.4
Tested up to: 6.7
Requires PHP: 7.4
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

FAQPage構造化データ（JSON-LD）を生成するシンプルなGutenbergブロック。

== Description ==

記事や固定ページにFAQ（よくある質問）を追加し、Google等の検索エンジン向けにFAQPage構造化データ（JSON-LD）を自動生成するプラグインです。

**特徴:**

* Gutenbergブロックエディタに対応
* ブロック挿入パネルから簡単にFAQを追加
* 質問・回答のペアを複数追加可能
* FAQPage Schema（JSON-LD）を自動で `<head>` に出力
* フロントエンドでは通常の見出し+段落として表示（記事に溶け込むデザイン）
* 見出しレベル（h2/h3/h4）を選択可能
* テーマの見出し装飾をリセットするオプション
* カスタムCSSでFAQ見出しのスタイルを自由に調整

== Installation ==

1. プラグインをWordPressの `/wp-content/plugins/andw-simple-faqpage-schema` ディレクトリにアップロードします。
2. WordPress管理画面の「プラグイン」メニューからプラグインを有効化します。
3. 投稿・固定ページのブロックエディタで「FAQ Schema」ブロックを追加して使用します。

== Frequently Asked Questions ==

= 構造化データはどこに出力されますか？ =

FAQブロックが含まれる投稿・固定ページの `<head>` 内に、JSON-LD形式でFAQPage Schemaが出力されます。

= 1つの投稿に複数のFAQブロックを配置できますか？ =

はい。複数のFAQブロックが同一投稿にある場合、構造化データは1つのFAQPage Schemaに統合されて出力されます。

= 見出しの装飾をリセットするには？ =

「設定」→「FAQ Schema」で「見出し装飾リセット」をONにすると、テーマの見出しスタイルが打ち消されます。さらにカスタムCSSでスタイルを自由に調整できます。

== Changelog ==

= 0.1.0 =
* 初回リリース

== Screenshots ==

== Upgrade Notice ==

= 0.1.0 =
初回リリース
