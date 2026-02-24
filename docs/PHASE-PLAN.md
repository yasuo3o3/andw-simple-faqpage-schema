# PHASE-PLAN.md — andw-simple-faqpage-schema 実装計画（承認済み）

## 概要
FAQPage Schema（JSON-LD）を生成するシンプルなWordPressプラグイン。
Gutenbergブロックとして実装し、WordPress.org Plugin Directory への申請を前提とする。

## 確定要件
- **スラッグ**: `andw-simple-faqpage-schema`
- **Text Domain**: `andw-simple-faqpage-schema`
- **接頭辞**: `andw`（関数/クラス/定数/CSS/JS変数）
- **ブロック名**: `andw/faq-schema`

### 機能
1. **FAQブロック**: エディタの「ブロック挿入パネル」に表示される Gutenberg カスタムブロック
2. **FAQ項目管理**: ブロック内で質問・回答のペアを複数追加/削除可能
3. **FAQPage Schema**: JSON-LD で `<head>` に出力（フロントのみ）
4. **フロントエンド**: h2/h3/h4 + p で出力。訪問者には通常の本文と見分けがつかない
5. **エディタプレビュー**: ブロック内で見た目の確認ができる（将来廃止の可能性あり）
6. **設定ページ**: 管理画面「設定」配下に設定ページを追加
   - 見出し装飾リセット ON/OFF（デフォルト: OFF）
   - カスタムCSS テキストエリア（FAQ見出しに適用するCSS）
7. **複数ブロック統合**: 同一投稿に複数FAQブロックがある場合、Schema出力は1つに統合
8. **空項目除外**: 質問・回答が空のFAQ項目はSchema出力から自動除外

### 削除した機能
- ~~画像設定~~: プラグインの責務外として削除
- ~~max-image-preview:large~~: 画像機能削除に伴い不要

### 見出しレベル
ブロック設定（InspectorControls）で h2 / h3 / h4 を選択可能。デフォルトは h3。

### 見出しスタイル制御
- FAQ見出しには専用クラス `andw-faq-question` を付与
- 設定ページで「見出し装飾リセット」をONにすると、テーマの見出しスタイルを打ち消すCSSをフロントに出力
  - リセットCSS例: `font-size: inherit; font-weight: normal; margin: 0; padding: 0; border: none; line-height: inherit;`
- カスタムCSS欄: `.andw-faq-question` に対する追加スタイルをユーザーが自由に記述可能

### 回答フィールド
- **RichText** コンポーネントを使用（太字・イタリック・リンク・リスト対応）
- Schema出力: Answer.text にHTML含む形式で出力
- フロント出力: wp_kses_post() でサニタイズ後にHTML出力

## ファイル構成
```
andw-simple-faqpage-schema/
├── andw-simple-faqpage-schema.php   # メインプラグインファイル
├── includes/
│   ├── render.php                   # フロントエンド描画 + Schema出力
│   └── settings.php                 # 設定ページ（見出しリセット + カスタムCSS）
├── src/
│   ├── block.json                   # ブロック定義
│   ├── index.js                     # ブロック登録エントリ
│   ├── edit.js                      # エディタ側UI
│   ├── save.js                      # 保存（null = 動的レンダリング）
│   └── editor.css                   # エディタ用スタイル（最小限）
├── build/                           # @wordpress/scripts ビルド出力
├── languages/                       # 翻訳ファイル
├── readme.txt                       # WordPress.org 用
├── uninstall.php                    # アンインストール処理
├── CHANGELOG.md
├── HANDOFF.md
├── package.json
└── docs/
    └── ...
```

## 技術設計

### block.json attributes
```json
{
  "headingLevel": { "type": "number", "default": 3 },
  "faqs": {
    "type": "array",
    "default": [],
    "items": {
      "type": "object",
      "properties": {
        "question": { "type": "string" },
        "answer": { "type": "string" }
      }
    }
  }
}
```

### フロントエンド出力（render.php）
- FAQ各項目: `<hN class="andw-faq-question">質問</hN><p>回答</p>` として出力
- JSON-LD: `wp_head` アクションで FAQPage スキーマを出力（同一投稿の全FAQブロックを統合）
- 空の質問・回答はスキップ

### エディタUI（edit.js）
- InspectorControls: 見出しレベル選択（h2/h3/h4）
- ブロック本体: FAQ項目の追加/編集/削除UI + リアルタイムプレビュー
- 質問: TextControl
- 回答: RichText（太字・リンク・リスト対応）
- 追加ボタン: 新しいFAQ項目を末尾に追加

### 設定ページ（settings.php）
- オプション名: `andw_faq_schema_reset_heading`（boolean）
- オプション名: `andw_faq_schema_custom_css`（string）
- 設定グループ: `andw_faq_schema_settings`
- ページスラッグ: `andw-faq-schema-settings`

### Schema出力例
```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "質問テキスト",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "回答テキスト（HTML可）"
      }
    }
  ]
}
```

## セキュリティ
- 出力: 質問テキストを `esc_html()` でエスケープ
- 回答HTML: `wp_kses_post()` でサニタイズ
- JSON-LD: `wp_json_encode()` でエンコード（XSS対策）
- カスタムCSS: 保存時 `sanitize_textarea_field()` + `wp_strip_all_tags()`、出力時 `wp_kses()` で検証
- 設定変更: nonce + `manage_options` 権限チェック

## 対象バージョン
- WordPress: 6.4+
- PHP: 7.4+
