# セキュリティ監査レポート

調査日: 2026-02-24
対象: andW Simple FAQPage Schema v0.1.0
調査方法: AI サブエージェント（Sonnet）による自動コードレビュー

---

## 総合評価

**概ね良好** — WordPress のセキュリティベストプラクティスに沿った実装がされている。ただし、いくつかのハードニング（堅牢化）ポイントが確認された。

---

## 良好な点（既に対策済み）

### 1. 直接アクセス防止

全 PHP ファイルの先頭で `ABSPATH` チェックを実施。

```php
// andw-simple-faqpage-schema.php:18-20
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
```

`uninstall.php` では `WP_UNINSTALL_PLUGIN` チェックを使用。

**評価: 合格**

### 2. 設定ページの権限チェック

設定ページの描画時に `manage_options` 権限をチェック。

```php
// includes/settings.php:131
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}
```

メニュー登録時にも `manage_options` を指定。

```php
// includes/settings.php:117-124
add_options_page(
    ...,
    'manage_options',
    ...
);
```

**評価: 合格**

### 3. Nonce・CSRF 対策

`settings_fields()` を使用しており、WordPress 標準の Nonce が自動付与される。

```php
// includes/settings.php:139
settings_fields( 'andw_faq_schema_settings' );
```

**評価: 合格**

### 4. フロントエンド出力のエスケープ

- 質問: `esc_html()` でエスケープ
- 回答: `wp_kses_post()` で許可タグのみ通過
- 見出しタグ名: `esc_attr()` でエスケープ

```php
// includes/render.php:41-42
$output .= '<' . esc_attr( $tag ) . ' class="andw-faq-question">' . esc_html( $question ) . '</' . esc_attr( $tag ) . '>';
$output .= wp_kses_post( $answer );
```

**評価: 合格**

### 5. 見出しレベルの入力バリデーション

`absint()` で整数化し、2〜4 の範囲に制限。

```php
// includes/render.php:21-26
$heading_level = isset( $attributes['headingLevel'] ) ? absint( $attributes['headingLevel'] ) : 3;
if ( $heading_level < 2 || $heading_level > 4 ) {
    $heading_level = 3;
}
```

**評価: 合格**

### 6. カスタム CSS のサニタイズ

保存時に `wp_strip_all_tags()` で HTML タグを除去。出力時にも `wp_strip_all_tags()` を再適用。

```php
// includes/settings.php:72-75 (保存時)
function andw_faq_schema_sanitize_css( $value ) {
    return wp_strip_all_tags( $value );
}

// andw-simple-faqpage-schema.php:154-155 (出力時)
if ( '' !== $custom_css ) {
    $css .= wp_strip_all_tags( $custom_css );
}
```

**評価: 合格** — 二重サニタイズで安全。

### 7. 設定ページの出力エスケープ

`esc_html__()`, `esc_html()`, `esc_textarea()` を適切に使用。

**評価: 合格**

### 8. アンインストール処理

自プラグインのオプションのみを削除しており、安全。

**評価: 合格**

---

## 改善すべき点

### 優先度「高」: JSON-LD Answer.text に HTML が混入する可能性

#### 問題

`andw_faq_schema_collect_faqs()` で回答テキストを取得する際、RichText で入力された HTML タグ（`<strong>`, `<em>`, `<a>` 等）がそのまま JSON-LD の `text` フィールドに含まれる。

```php
// andw-simple-faqpage-schema.php:106
$answer = isset( $faq['answer'] ) ? trim( $faq['answer'] ) : '';
```

Google の FAQPage スキーマは Answer.text に HTML を許容しているが、`wp_json_encode()` 内で HTML タグが含まれると予期しないエスケープの問題が発生する可能性がある。

#### 対策案

JSON-LD 用の回答テキストからは HTML タグを除去する:

```php
$answer = isset( $faq['answer'] ) ? wp_strip_all_tags( trim( $faq['answer'] ) ) : '';
```

または、Google が [HTML を許容している](https://developers.google.com/search/docs/appearance/structured-data/faqpage)ことを活かし、許可タグのみを残す方法もある:

```php
$answer = isset( $faq['answer'] ) ? wp_kses_post( trim( $faq['answer'] ) ) : '';
```

**注:** フロントエンド表示用の回答はそのまま HTML を保持し、JSON-LD 用のみ処理を変える必要がある。

---

### 優先度「中」: JSON-LD 出力に JSON_HEX_TAG フラグがない

#### 問題

現在の JSON-LD 出力:

```php
// andw-simple-faqpage-schema.php:88
echo '<script type="application/ld+json">'
    . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES )
    . '</script>' . "\n";
```

FAQ の質問や回答に `</script>` という文字列が含まれた場合、`<script>` タグが閉じられてしまい XSS の可能性がある。`wp_json_encode()` は `json_encode()` のラッパーであり、`</script>` を自動的にエスケープしない。

#### 対策案

`JSON_HEX_TAG` フラグを追加して `<` と `>` をユニコードエスケープする:

```php
echo '<script type="application/ld+json">'
    . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG )
    . '</script>' . "\n";
```

---

### 優先度「中」: block.json の faqs 属性に items スキーマがない

#### 問題

```json
"faqs": {
    "type": "array",
    "default": []
}
```

`items` プロパティが未定義のため、ブロックエディタ側でのバリデーションが緩い。不正な構造のデータが `faqs` に入り得る。

#### 対策案

```json
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
```

---

### 優先度「低」: カスタム CSS インジェクションの理論的リスク

#### 状況

`wp_strip_all_tags()` は HTML タグを除去するが、CSS 固有の攻撃ベクターに対しては防御が完全ではない。

- `expression()` — 古い IE のみ（現代ブラウザでは無効）
- `url()` — 外部リソースの読み込み
- `@import` — 外部 CSS の読み込み

#### 評価

WordPress コアの「カスタム CSS」機能（wp_filter_nohtml_kses を使用する Additional CSS）と同程度のセキュリティレベルであり、`manage_options` 権限者のみが編集できることから、**現時点では許容可能**。

将来的に権限レベルを下げる場合は、CSS パーサーによるサニタイズ（safecss_filter_attr 等）を検討する。

---

## PHPヘッダーと readme.txt の整合性

| 項目 | PHP ヘッダー | readme.txt | 一致 |
|------|------------|-----------|:---:|
| Plugin Name | andW Simple FAQPage Schema | andW Simple FAQPage Schema | ✓ |
| Version | 0.1.0 | 0.1.0 | ✓ |
| Requires at least | 6.4 | 6.4 | ✓ |
| Requires PHP | 7.4 | 7.4 | ✓ |
| License | GPLv2 or later | GPLv2 or later | ✓ |
| Text Domain | andw-simple-faqpage-schema | — | — |
| Description | 日本語 | 日本語 | ✓ |
| Author | yasuo3o3 | yasuo3o3 (Contributors) | ✓ |

**評価: 完全一致。問題なし。**

---

## まとめ

| 項目 | 状態 |
|------|------|
| 直接アクセス防止 | ✅ 対策済み |
| 権限チェック | ✅ 対策済み |
| Nonce / CSRF | ✅ 対策済み |
| フロントエンド XSS 対策 | ✅ 対策済み |
| 入力バリデーション | ✅ 対策済み |
| CSS サニタイズ | ✅ 対策済み |
| エスケープ | ✅ 対策済み |
| アンインストール | ✅ 安全 |
| JSON-LD Answer HTML 混入 | ⚠️ 要改善（高） |
| JSON-LD `</script>` 対策 | ⚠️ 要改善（中） |
| block.json items スキーマ | ⚠️ 要改善（中） |
| CSS インジェクション | ℹ️ 現状許容可（低） |
