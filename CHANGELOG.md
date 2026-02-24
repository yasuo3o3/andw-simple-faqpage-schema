# Changelog

## [0.1.0] - 2026-02-23

### 追加
- FAQブロック（Gutenbergブロックエディタ対応）
- FAQ項目の追加・編集・削除機能
- RichText対応の回答フィールド（太字・イタリック・リンク・リスト）
- 見出しレベル選択（h2/h3/h4、デフォルト: h3）
- FAQPage Schema（JSON-LD）の自動出力
- 複数FAQブロックのSchema統合
- 空項目の自動除外
- 設定ページ（見出し装飾リセット + カスタムCSS）
- uninstall.php（プラグイン削除時のクリーンアップ）

## [0.2.0] - 2026-02-24

### 追加
- 設定ページに「表示・出力設定」セクションを新設し、JSON-LD出力制御機能を追加
- 他SEOプラグイン（Yoast, Rank Math, AIOSEO）とのFAQスキーマ競合検知と警告表示（設定画面およびエディター内）
- フィルターフック `andw_faq_schema_enabled` の追加
