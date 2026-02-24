# プロンプト: FAQ スキーマ競合検知機能の実装

## 概要
andW Simple FAQPage Schema プラグインに、他プラグインの FAQ スキーマとの競合を検知し、管理画面で警告を表示する機能を追加してください。

## 背景・議論の経緯

オーナーとの議論で以下が合意済みです：

1. **有名 SEO プラグイン**（Yoast SEO, Rank Math, All in One SEO）との FAQ スキーマ重複は、プラグインスラッグや登録ブロック名で検知可能
2. **野良プラグイン**は名前の特定はできないが、「FAQPage スキーマが重複している」という事実は検知可能（フロント HTML をパースする方法）
3. **有名プラグインは FAQ スキーマだけを個別にオフにできる**ので、ユーザーへの案内が成立する
4. 完全網羅は目指さず、**主要ケースをカバーする割り切り**でよい
5. プラグインの「シンプル」という性格を維持すること

## 実装要件

### 1. 設定画面（`includes/settings.php`）に競合検知セクションを追加

管理画面 > 設定 > FAQ Schema に、以下の情報を表示する新しいセクションを追加：

- **検知対象プラグイン**:
  - Yoast SEO（`wordpress-seo/wp-seo.php`）→ FAQ ブロック: `yoast/faq-block`
  - Rank Math（`seo-by-rank-math/rank-math.php`）→ FAQ ブロック: `rank-math/faq-block`
  - All in One SEO（`all-in-one-seo-pack/all_in_one_seo_pack.php`）→ スキーマ設定あり
- 有効なプラグインが検知された場合、**admin notice スタイルの警告**を設定ページ内に表示
- 例: 「Yoast SEO が有効です。FAQ スキーマの重複を避けるため、Yoast 側の FAQ スキーマ出力を無効にするか、本プラグインの JSON-LD 出力を無効にしてください。」

### 2. 投稿編集画面（ブロックエディター）での警告

- 投稿内に `andw/faq-schema` ブロックと同時に `yoast/faq-block` や `rank-math/faq-block` が存在する場合、エディター内に警告バナーを表示
- 実装場所: `src/edit.js` 内で `select('core/block-editor').getBlocks()` を使用
- 警告例: 「この投稿には他プラグインの FAQ ブロックも含まれています。FAQ スキーマが重複する可能性があります。」

### 3. JSON-LD 出力を無効化できるフィルターの追加

`andw-simple-faqpage-schema.php` の `andw_faq_schema_output_jsonld()` に以下のフィルターを追加：

```php
// スキーマ出力の有効/無効を制御するフィルター
if ( ! apply_filters( 'andw_faq_schema_enabled', true, $post ) ) {
    return;
}
```

これにより、他プラグインやテーマから自プラグインの JSON-LD 出力を無効化できるようになる。

### 4. 設定画面に JSON-LD 出力の無効化オプションを追加

- 新しいオプション: `andw_faq_schema_disable_jsonld`（boolean, デフォルト: false）
- チェックボックス: 「JSON-LD スキーマ出力を無効にする（HTMLのみ出力）」
- 説明文: 「他のSEOプラグインでFAQスキーマを管理する場合はオンにしてください。FAQ の見出し・回答の HTML 表示は維持されます。」
- `uninstall.php` にもこのオプションの削除を追加すること

## 現在のファイル構成（変更対象）

```
andw-simple-faqpage-schema.php  ← フィルター追加、JSON-LD出力の条件分岐
includes/settings.php           ← 競合検知セクション、JSON-LD無効化オプション追加
includes/render.php             ← 変更なし
src/edit.js                     ← エディター内の競合警告
src/block.json                  ← 変更なし
uninstall.php                   ← 新オプションの削除追加
```

## 守るべき規約

- `CLAUDE.md` → `docs/AGENTS.md` → `docs/WORDPRESS.md` → `docs/CONTRIB.md` の規約に従うこと
- Text Domain: `andw-simple-faqpage-schema`
- コミットは日本語、1機能1コミットの粒度
- `php -l` で構文チェックを通すこと
- JS を変更した場合は `npm run build` を実行すること
- 既存の admin notice の仕組みは使わず、設定ページ内に限定して表示すること（他のページにグローバル警告を出さない）

## やらないこと（スコープ外）

- フロント HTML をパースして野良プラグインを検知する機能（将来課題）
- Yoast / Rank Math のフィルターに FAQ データを注入する統合機能（将来課題）
- 他プラグインの FAQ スキーマを自動的に無効化する機能（ユーザー判断に委ねる）

## 完了条件

1. 設定画面で Yoast / Rank Math / AIOSEO の有効状態が表示される
2. エディターで他プラグインの FAQ ブロック共存時に警告が出る
3. `andw_faq_schema_enabled` フィルターが機能する
4. 「JSON-LD 出力を無効にする」チェックで JSON-LD のみ停止し、HTML 表示は維持される
5. `php -l` エラーなし、`npm run build` 成功
