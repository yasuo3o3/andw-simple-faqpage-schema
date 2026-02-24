# Google FAQリッチリザルト変更（2023年8月）— 調査レポート

作成日: 2026-02-24

---

## 概要

2023年8月8日、Google は **FAQ（FAQPage）リッチリザルトおよび How-To リッチリザルトの表示を大幅に制限する**ことを公式ブログで発表した。この変更により、ほとんどの一般Webサイトでは FAQ 構造化データを実装しても Google 検索結果上でリッチリザルトとして表示されなくなった。

## 変更内容

### 変更前（〜2023年8月）

- FAQPage 構造化データ（JSON-LD）を正しく実装すれば、どのサイトでも Google 検索結果に FAQ リッチリザルト（質問と回答のアコーディオン）が表示される可能性があった
- 多くの SEO 実務者がこれを利用し、検索結果での占有面積（SERP real estate）を拡大する手法として広く活用していた

### 変更後（2023年8月〜）

- **FAQ リッチリザルトは「よく知られた権威ある政府系・医療系サイト」のみに制限された**
- 一般の企業サイト、ブログ、EC サイト等では FAQ リッチリザルトが表示されなくなった
- How-To リッチリザルトはデスクトップ検索から完全に削除された

### Google 公式ドキュメントの記述

> "FAQ rich results are only available for well-known, authoritative websites that are government-focused or health-focused."
>
> — [Google Search Central: FAQPage structured data](https://developers.google.com/search/docs/appearance/structured-data/faqpage)

## Google が変更した理由

- **FAQ スキーマの濫用が横行していた**: 多くのサイトが本来の FAQ（よくある質問）ではなく、広告・プロモーション目的で FAQ マークアップを使い、検索結果での表示面積を不当に拡大していた
- **検索結果の品質向上**: Google は「よりクリーンで一貫性のある検索体験」を提供するためにこの変更を行ったと説明

## 当プラグインへの影響

### 直接的な影響

`andW Simple FAQPage Schema` は FAQPage JSON-LD を生成するプラグインであるため、この変更の影響を直接受ける。

| 項目 | 影響 |
|------|------|
| JSON-LD の生成機能 | **影響なし** — 技術的に正しい FAQPage JSON-LD は引き続き生成される |
| Google でのリッチリザルト表示 | **大きな影響** — 政府・医療系以外のサイトでは表示されない |
| 構造化データの有効性 | **影響なし** — Google のリッチリザルトテストでは引き続き Valid と判定される |

### readme.txt への影響

現在の readme.txt には、この制限に関する説明が一切記載されていない。ユーザーが「FAQ リッチリザルトを獲得できる」と期待してインストールする可能性があり、期待と実際のギャップが低評価レビューにつながるリスクがある。

## FAQ スキーマは今でも有用か？（2025-2026年の状況）

### 結論: **有用だが、役割が変わった**

Google でのリッチリザルト表示が制限された一方で、FAQ 構造化データの価値は別の方向で高まっている。

### 2025年6月: Google の構造化データ整理で FAQ は存続

2025年6月12日、Google は複数の構造化データタイプのサポートを終了したが、**FAQPage は明示的にサポート対象として維持された**。これは Google が FAQ スキーマ自体を廃止する意図がないことを示している。

### AI 検索における FAQ スキーマの価値

| プラットフォーム | FAQ スキーマの活用状況 |
|----------------|----------------------|
| Google Gemini | 構造化データを参照して回答を生成 |
| Microsoft Bing / Copilot | 「スキーママークアップは LLM のコンテンツ理解を助ける」（Bing Principal PM） |
| ChatGPT | 構造化された Q&A データをソースとして引用 |
| Perplexity | FAQ スキーマを優先的にクロール・引用 |

### Bing でのリッチリザルト

Google とは異なり、**Bing は FAQ リッチリザルトを引き続き表示している**。Bing のシェアは小さいが、Copilot との統合により重要性は増している。

### AI 検索時代の新しい価値

1. **LLM の理解を助ける** — 構造化された Q&A 形式は AI が正確に内容を把握しやすい
2. **ハルシネーション防止** — スキーマにより AI が情報の確信度を高められる
3. **引用されやすくなる** — AI 検索結果でソースとして引用される確率が上がる
4. **音声検索への対応** — 音声アシスタントが FAQ データを回答に利用する

## 当プラグインが取るべき対応

### 必須対応

1. **readme.txt に Google の制限を正直に記載する**
   - 「Google のリッチリザルト表示は政府・医療系サイトに制限されている」ことを FAQ セクションに明記
   - ユーザーの誤解を防ぎ、低評価レビューを回避

2. **AI 検索時代の新しい価値を訴求する**
   - Google リッチリザルトだけがゴールではないことを説明
   - Bing、AI 検索エンジン、音声検索での活用価値をアピール

### readme.txt FAQ セクションへの追記案

```
= Will my FAQ appear as a rich result in Google? =

In August 2023, Google restricted FAQ rich results to well-known,
authoritative government and health websites. For most sites, FAQ
structured data will NOT produce rich results in Google Search.

However, FAQ schema remains valuable because:
* Bing and other search engines may still display FAQ rich results
* AI search platforms (Google Gemini, Bing Copilot, ChatGPT,
  Perplexity) use structured data to understand and cite your content
* Google continues to support FAQPage structured data (confirmed
  in their June 2025 schema update)
* Well-structured FAQ content improves your site's accessibility
  to voice search and AI assistants
```

### readme.txt Description への追記案

```
= Important Note =

Since August 2023, Google shows FAQ rich results only for
authoritative government and health websites. This plugin still
generates valid FAQPage JSON-LD, which benefits AI search engines
(Gemini, Copilot, ChatGPT), Bing rich results, and overall
content structure.
```

## 参考資料

- [Changes to HowTo and FAQ rich results — Google Search Central Blog (2023-08-08)](https://developers.google.com/search/blog/2023/08/howto-faq-changes)
- [FAQ (FAQPage, Question, Answer) structured data — Google Search Central](https://developers.google.com/search/docs/appearance/structured-data/faqpage)
- [The rise and fall of FAQ schema – and what it means for SEO today — Search Engine Land](https://searchengineland.com/faq-schema-rise-fall-seo-today-463993)
- [Google Downgrades Visibility of HowTo and FAQ Rich Results — Search Engine Journal](https://www.searchenginejournal.com/google-downgrades-visibility-of-howto-and-faq-rich-results/493522/)
- [FAQ Schema in 2025: Still a Valuable SEO Asset — Epic Notion](https://www.epicnotion.com/blog/faq-schema-in-2025/)
- [Is Schema Still Important for SEO in 2026? — Mud](https://ournameismud.co.uk/articles/schema-markup-seo-ai-search)
- [FAQ Schema For AI Search 2026 — Digital 6ix](https://www.digital6ix.ca/faq-schema-for-ai-search-toronto-seo/)
- [Schema Markup in 2026: Why It's Now Critical for SERP Visibility — ALM Corp](https://almcorp.com/blog/schema-markup-detailed-guide-2026-serp-visibility/)
