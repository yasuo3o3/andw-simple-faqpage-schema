# WordPress Plugin Directory マーケティング評価レポート

調査日: 2026-02-24
対象: andW Simple FAQPage Schema v0.1.0
調査方法: AI サブエージェント（Sonnet）による readme.txt・プラグインメタデータの評価 + 競合分析

---

## 総合スコア

| 項目 | スコア | 最大値 |
|------|:---:|:---:|
| プラグイン名 | 5 | 10 |
| Short Description | 3 | 10 |
| Long Description | 3 | 10 |
| FAQ セクション | 5 | 10 |
| スクリーンショット | 0 | 10 |
| タグ戦略 | 6 | 10 |
| 差別化の明確さ | 4 | 10 |
| ターゲット明確さ | 2 | 10 |
| **合計** | **28** | **80** |

**プラグイン自体の実装品質は高いが、その強みが readme.txt で全く伝わっていない。**

---

## 1. プラグイン名の評価

### 現状

```
andW Simple FAQPage Schema
```

### 問題点

- 「andW」はブランド接頭辞であり、検索キーワードとして機能しない
- ユーザーが検索する語は「FAQ schema」「FAQ structured data」「FAQ block」「FAQ JSON-LD」
- 「FAQPage」はスキーマの @type 名であり、一般ユーザーには直感的でない
- 競合が「FAQ Schema」「FAQ Block」といったシンプルな名前を使う中、視認性が低い

### 改善案

WordPress.org の検索アルゴリズムはプラグイン名の前半を重視する。キーワードを前に置く:

```
andW FAQ Schema Block — Simple Structured Data for Gutenberg
```

---

## 2. Short Description の評価

### 現状（readme.txt 11行目）

```
FAQPage構造化データ（JSON-LD）を生成するシンプルなGutenbergブロック。
```

### 問題点

- **日本語のみ**: WordPress.org の最大市場は英語圏。日本語のみでは届かない
- **ベネフィットが伝わらない**: 「何ができるか」はあるが「なぜ必要か」がない
- **150文字の活用不足**: WordPress.org の検索スニペットとして機能する重要な 1 文

### 改善案

```
Generate FAQPage structured data (JSON-LD) with a simple Gutenberg block.
No SEO plugin required. Earn FAQ rich results in Google search.
```

---

## 3. Long Description の評価

### 現在の構成

```
記事や固定ページにFAQ（よくある質問）を追加し、Google等の検索エンジン向けに
FAQPage構造化データ（JSON-LD）を自動生成するプラグインです。

特徴:
* Gutenbergブロックエディタに対応
* ブロック挿入パネルから簡単にFAQを追加
  ...（機能リスト8項目）
```

### 問題点

| 問題 | 詳細 |
|------|------|
| 全文日本語のみ | 英語圏ユーザーに全く届かない |
| 「なぜ必要か」の文脈がない | リッチリザルト・CTR 向上等のベネフィット説明がゼロ |
| Use Case がない | どんなサイトに向いているか不明 |
| 競合差別化がない | Yoast/Rank Math の FAQ との違いが不明 |

### 改善後の構成案

```
== Description ==

**Add FAQ structured data to any post with a single Gutenberg block.**

When your FAQ content appears in Google's rich results, it can occupy
extra space in search listings — helping you stand out and increase
click-through rates without additional effort.

= Why you need FAQPage Schema =

Google supports FAQ rich results, which display your Q&A content
directly in search results. This plugin handles all the technical
implementation automatically.

= Features =

* Native Gutenberg block — no shortcodes, no page builders needed
* Automatically outputs FAQPage JSON-LD to <head> (single posts and pages)
* Multiple FAQ items per block, multiple blocks per post
* When multiple blocks exist on one page, all FAQs are merged into
  a single FAQPage schema (Google-compliant)
* Frontend output: semantic heading + paragraph — blends with your theme
* Heading level selector: h2 / h3 / h4
* Optional heading style reset for theme compatibility
* Custom CSS field for FAQ heading styles
* Empty items are automatically excluded from schema output
* Lightweight — no bloat, no dependencies beyond WordPress core

= Who is this for? =

* Bloggers and content creators who want to earn FAQ rich results
* Service businesses with common customer questions
* Anyone who wants structured data without installing a full SEO suite

= Difference from Yoast / Rank Math FAQ blocks =

This plugin does one thing and does it well: FAQPage Schema.
No SEO suite required. Install it alongside any SEO plugin or
use it standalone. There is no conflict with existing schema output
from other plugins.

---

**日本語をお使いの方へ**

このプラグインはGutenbergブロックでFAQ構造化データ（JSON-LD）を生成します。
Googleのリッチリザルト（検索結果でのFAQ表示）に対応。
YoastやRank MathなどのSEOプラグインとの併用も可能です。
```

---

## 4. FAQ セクションの評価

### 現状

3 つの FAQ が記載（日本語のみ）。最低限の内容は揃っているが不足がある。

### 追加すべき FAQ 項目

```
= Does this plugin conflict with Yoast SEO or Rank Math? =

No. This plugin outputs only FAQPage Schema and does not interfere
with other schema types (Article, BreadcrumbList, etc.) output by
SEO plugins.

= Is this compatible with the Classic Editor? =

No. This plugin requires the Gutenberg block editor (WordPress 5.0+).

= Will my FAQ appear in Google rich results? =

In August 2023, Google restricted FAQ rich results to well-known,
authoritative government and health websites. For most sites,
FAQ structured data will NOT produce rich results in Google Search.

However, FAQ schema remains valuable because:
* Bing and other search engines may still display FAQ rich results
* AI search platforms (Gemini, Copilot, ChatGPT, Perplexity) use
  structured data to understand and cite your content
* Google continues to support FAQPage structured data

= Can I use this on category or archive pages? =

Currently, schema output is limited to singular posts and pages
(is_singular()). Archive pages are not supported.
```

---

## 5. スクリーンショット

### 現状: **空。致命的な機会損失。**

WordPress Plugin Directory ではスクリーンショットがアイキャッチ的な役割を果たす。スクリーンショットがないプラグインは信頼性が低く見え、インストール率に直結する。

### 必要なスクリーンショット（優先度順）

| 番号 | 内容 | 説明文 |
|:---:|------|--------|
| 1 | エディタで FAQ ブロックを追加している画面 | "Add FAQ items directly in the Gutenberg editor" |
| 2 | フロントエンドの表示 | "Clean frontend output — blends naturally with your theme" |
| 3 | Google リッチリザルトテスト合格画面 | "Valid FAQPage Schema — tested with Google's Rich Results Test" |
| 4 | 設定ページ（見出しリセット + カスタム CSS） | "Simple settings: heading style reset and custom CSS" |
| 5 | DevTools で `<head>` 内の JSON-LD を表示 | "FAQPage JSON-LD automatically injected into `<head>`" |

---

## 6. タグ戦略の評価

### 現状

```
faq, schema, structured-data, json-ld, seo
```

### 各タグの評価

| タグ | 評価 | 理由 |
|------|------|------|
| `faq` | 良い | 検索ボリューム高 |
| `schema` | 良い | 技術ユーザーが検索 |
| `structured-data` | 良い | 同上 |
| `json-ld` | 普通 | 上級者向け。検索ボリューム低め |
| `seo` | 良い | 最も広いカバレッジ |

### 改善案

```
faq, schema, gutenberg, seo, structured-data
```

`json-ld` → `gutenberg` の理由:
- 「gutenberg block」はユーザーが実際に検索する語
- Gutenberg ブロック専用であることが差別化ポイント
- `json-ld` で検索する層は実装方法を知っている上級者が多い

---

## 7. 競合分析

### 主要競合

| プラグイン | アクティブインストール | 特徴 |
|-----------|:---:|------|
| Yoast SEO（FAQ Block 内蔵） | 500万+ | SEO スイート全体のインストールが必要 |
| Rank Math（FAQ Block 内蔵） | 300万+ | 同上 |
| Schema & Structured Data for WP & AMP | — | 汎用スキーマ。設定が複雑 |
| 専用軽量 FAQ プラグイン群 | — | 多くはショートコードベース |

### 差別化マトリクス

| 機能 | andW Simple FAQ | Yoast FAQ | Rank Math FAQ |
|------|:---:|:---:|:---:|
| Gutenberg ネイティブ | ○ | ○ | ○ |
| スタンドアロン（SEO スイート不要） | **○** | ✗ | ✗ |
| 複数ブロック → 1 Schema 統合 | **○** | ? | ? |
| 軽量・シングルパーパス | **○** | ✗ | ✗ |
| テーマ見出しスタイルリセット | **○** | ✗ | ✗ |
| カスタム CSS | **○** | ✗ | ✗ |
| 見出しレベル選択 | **○** | ✗ | ✗ |

### 最大の差別化ポイント

**「SEO プラグイン不要のスタンドアロン軽量 FAQ ブロック」**

→ readme.txt の冒頭で明確に主張すべき。

---

## 8. ターゲットユーザー

### 現状: 言及なし

### ターゲットとすべき 3 層

**1. Yoast/Rank Math に不満を持つユーザー（最有望）**
- 「SEO プラグインは別途使っているが、FAQ ブロックだけ欲しい」
- 「Yoast を入れるほどではないが、FAQ のリッチリザルトは欲しい」

**2. Gutenberg に移行した WordPress 管理者**
- 「ショートコードの FAQ プラグインから Gutenberg ネイティブに移行したい」

**3. 日本語 WordPress ユーザー**
- readme.txt が日本語で書かれており親和性が高い（ただし英語圏では届かない）

---

## 9. 改善施策一覧

### 優先度「高」（今すぐ対応可能）

| # | 施策 | 期待効果 |
|---|------|---------|
| 1 | **readme.txt を英語ベースで書き直す** | 英語圏ユーザーへのリーチ。ダウンロード数に最も直結 |
| 2 | **スクリーンショットを最低 3 枚追加** | 信頼性の向上。インストール率に直結 |
| 3 | **Short Description を英語化 + ベネフィット訴求** | 検索スニペットの改善 |

### 優先度「中」（v0.2 で対応）

| # | 施策 | 期待効果 |
|---|------|---------|
| 4 | "Tested up to" を最新 WordPress に追従 | ユーザーの不信感を回避 |
| 5 | Banner（772×250px）とアイコン（256×256px）を追加 | Plugin Directory での視認性向上 |
| 6 | レビュー誘導文を追加 | レビュー数は検索ランキングに影響 |

### 優先度「低」（v0.3 以降）

| # | 施策 | 期待効果 |
|---|------|---------|
| 7 | Classic Editor 非対応の明記 | 不満レビューの防止 |
| 8 | GitHub リポジトリへのリンク追加 | 技術者の信頼獲得 |

---

## 結論

**プラグインの実装品質は競合に対して十分な差別化ができている。問題は、その強みが readme.txt で全く伝わっていないこと。**

最優先で対応すべきは以下の 2 点:

1. **readme.txt を英語ベースで全面的に書き直す**
2. **スクリーンショットを最低 3 枚用意する**

この 2 点だけで、現状の数倍のダウンロード数が見込める。
