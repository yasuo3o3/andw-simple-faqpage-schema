# SEO・構造化データ監査レポート

調査日: 2026-02-24
対象: andW Simple FAQPage Schema v0.1.0
調査方法: AI サブエージェント（Sonnet）による自動コードレビュー + 構造化データ仕様との照合

---

## 総合評価

JSON-LD の基本構造は Google の FAQPage スキーマ要件を満たしている。ただし、推奨プロパティの欠落、フロントエンド HTML のセマンティクス不足、および 2023年8月の Google リッチリザルト制限に関する告知漏れが確認された。

---

## JSON-LD 出力の品質

### 現在の出力形式

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
        "text": "回答テキスト"
      }
    }
  ]
}
```

### 良好な点

| 項目 | 評価 | 詳細 |
|------|:---:|------|
| @context | ✅ | `https://schema.org` を正しく指定 |
| @type | ✅ | `FAQPage` を正しく指定 |
| mainEntity | ✅ | Question の配列として正しく構成 |
| Question.name | ✅ | 質問テキストが正しく配置 |
| Answer.text | ✅ | 回答テキストが正しく配置 |
| 複数ブロック統合 | ✅ | `andw_faq_schema_collect_faqs()` で再帰的に全ブロックを収集し、1つの FAQPage に統合 |
| 空項目の除外 | ✅ | question/answer が空の場合はスキップ |
| 出力条件 | ✅ | `is_singular()` でのみ出力（アーカイブ・管理画面は除外） |

### 欠落している推奨プロパティ

#### FAQPage レベル

| プロパティ | 状態 | 重要度 | 説明 |
|-----------|:---:|:---:|------|
| `url` | ❌ 欠落 | 高 | ページの正規 URL。`get_permalink()` で取得可能 |
| `name` | ❌ 欠落 | 中 | FAQ ページのタイトル。`get_the_title()` で取得可能 |
| `dateModified` | ❌ 欠落 | 中 | 最終更新日。`get_the_modified_date( 'c' )` で取得可能 |

#### 改善後の JSON-LD 出力案

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "url": "https://example.com/page-slug/",
  "name": "ページタイトル",
  "dateModified": "2026-02-24T12:00:00+09:00",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "質問テキスト",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "回答テキスト"
      }
    }
  ]
}
```

#### 対応コード案

```php
$schema = array(
    '@context'     => 'https://schema.org',
    '@type'        => 'FAQPage',
    'url'          => get_permalink( $post ),
    'name'         => get_the_title( $post ),
    'dateModified' => get_the_modified_date( 'c', $post ),
    'mainEntity'   => $main_entity,
);
```

---

## Answer.text の HTML 問題

### 問題

RichText エディタで入力された回答には HTML タグ（`<strong>`, `<em>`, `<a>`, `<ul>/<li>` 等）が含まれる。これがそのまま JSON-LD の `text` フィールドに入る。

### Google の仕様

Google の [FAQPage 構造化データドキュメント](https://developers.google.com/search/docs/appearance/structured-data/faqpage)では、Answer の text プロパティに HTML を含めることを許容している。ただし、有効な HTML タグは限定されている:

- `<h1>` 〜 `<h6>`, `<br>`, `<ol>`, `<ul>`, `<li>`, `<a>`, `<p>`, `<div>`, `<b>`, `<strong>`, `<i>`, `<em>`

### 対策案

2つのアプローチがある:

**A. HTML を除去する（安全優先）**

```php
$answer = wp_strip_all_tags( trim( $faq['answer'] ) );
```

**B. 許可タグのみ残す（リッチ表現を維持）**

```php
$answer = wp_kses( trim( $faq['answer'] ), array(
    'strong' => array(),
    'em'     => array(),
    'a'      => array( 'href' => array() ),
    'br'     => array(),
    'p'      => array(),
    'ul'     => array(),
    'ol'     => array(),
    'li'     => array(),
) );
```

---

## フロントエンド HTML のセマンティクス

### 現在の出力

```html
<h3 class="andw-faq-question">質問テキスト</h3>
<p>回答テキスト</p>
```

各 FAQ 項目がフラットに並んでおり、ラッパー要素がない。

### 改善提案

#### 1. FAQ ラッパー要素の追加

```html
<div class="andw-faq-block">
  <div class="andw-faq-item" id="faq-1">
    <h3 class="andw-faq-question">質問テキスト</h3>
    <div class="andw-faq-answer">
      <p>回答テキスト</p>
    </div>
  </div>
</div>
```

**メリット:**

- CSS スタイリングが容易になる
- `id` 属性によりページ内リンク（アンカーリンク）が可能に
- FAQ 項目のグルーピングが明確になり、アクセシビリティが向上
- 将来的なアコーディオン UI への拡張が容易

#### 2. itemscope / itemprop の追加（オプション）

JSON-LD で構造化データを出力しているため、Microdata は不要。ただし、二重マークアップを行うサイトもある。**JSON-LD のみで十分**。

---

## 複数ブロック統合の検証

### 動作確認

`andw_faq_schema_collect_faqs()` は `parse_blocks()` の結果を再帰的に処理し、ネストされたブロック（カラム内配置等）にも対応している。

```php
// andw-simple-faqpage-schema.php:98-128
function andw_faq_schema_collect_faqs( $blocks ) {
    $faqs = array();
    foreach ( $blocks as $block ) {
        if ( 'andw/faq-schema' === $block['blockName'] ) {
            // FAQ 項目を収集
        }
        // インナーブロックの再帰処理
        if ( ! empty( $block['innerBlocks'] ) ) {
            $faqs = array_merge( $faqs, andw_faq_schema_collect_faqs( $block['innerBlocks'] ) );
        }
    }
    return $faqs;
}
```

**評価: 合格** — Google が推奨する「1ページ1つの FAQPage スキーマ」に準拠している。

### 注意点

重複する質問（同じテキストの Q&A）が複数ブロックに存在した場合、重複したまま JSON-LD に含まれる。これは Google のバリデーションでエラーにはならないが、理想的には重複排除があると良い。

**優先度: 低** — ユーザーが同じ質問を意図的に重複させるケースは稀。

---

## Google FAQリッチリザルト制限（2023年8月）

### 概要

2023年8月8日、Google は FAQ リッチリザルトの表示を**権威ある政府系・医療系サイトのみ**に制限した。

> "FAQ rich results are only available for well-known, authoritative websites that are government-focused or health-focused."
> — Google Search Central

### 当プラグインへの影響

- JSON-LD の技術的な有効性に影響なし
- Google 検索でのリッチリザルト表示は一般サイトでは期待できない
- **readme.txt にこの事実が記載されていない** — ユーザーの誤解を招く可能性がある

### FAQ スキーマの現在の価値（2025-2026年）

Google リッチリザルト以外での価値は依然として高い:

- **Bing**: FAQ リッチリザルトを引き続き表示
- **AI 検索**（Gemini, Copilot, ChatGPT, Perplexity）: 構造化データを参照・引用
- **音声検索**: FAQ データをアシスタントが利用
- **Google 自身**: 2025年6月の構造化データ整理で FAQPage を明示的に存続させた

詳細は `docs/research/2023-08-google-faq-rich-results-change.md` を参照。

---

## タグ戦略（readme.txt）

### 現在

```
faq, schema, structured-data, json-ld, seo
```

### 改善提案

```
faq, schema, gutenberg, seo, structured-data
```

| 変更 | 理由 |
|------|------|
| `json-ld` → `gutenberg` | 「gutenberg block」はユーザーが実際に検索する語。Gutenberg ネイティブであることが差別化ポイント。`json-ld` で検索する層は自前実装できる上級者が多い |

---

## 改善優先度まとめ

| 優先度 | 項目 | カテゴリ |
|--------|------|---------|
| **高** | FAQPage に `url` / `name` / `dateModified` を追加 | JSON-LD |
| **高** | Answer.text から HTML タグを適切に処理 | JSON-LD |
| **高** | readme.txt に Google リッチリザルト制限を記載 | ドキュメント |
| **中** | フロントエンド HTML にラッパー + `id` 属性を追加 | HTML |
| **中** | タグを `json-ld` → `gutenberg` に変更 | readme.txt |
| **低** | 重複 FAQ の排除 | JSON-LD |
| **不要** | Microdata の追加 | HTML |

---

## 参考資料

- [Google FAQPage 構造化データ仕様](https://developers.google.com/search/docs/appearance/structured-data/faqpage)
- [Schema.org FAQPage](https://schema.org/FAQPage)
- [Google リッチリザルトテスト](https://search.google.com/test/rich-results)
- [Changes to HowTo and FAQ rich results (2023-08)](https://developers.google.com/search/blog/2023/08/howto-faq-changes)
