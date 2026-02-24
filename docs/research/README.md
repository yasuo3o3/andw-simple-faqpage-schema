# v0.1.0 調査レポート一覧

調査日: 2026-02-24
対象: andW Simple FAQPage Schema v0.1.0

---

## レポート一覧

| # | ファイル | 内容 | 調査観点 |
|---|---------|------|---------|
| 01 | [01-security-audit.md](./01-security-audit.md) | セキュリティ監査 | XSS対策、入力バリデーション、権限チェック、サニタイズ |
| 02 | [02-seo-structured-data-audit.md](./02-seo-structured-data-audit.md) | SEO・構造化データ監査 | JSON-LD 品質、HTML セマンティクス、Google 仕様準拠 |
| 03 | [03-marketing-evaluation.md](./03-marketing-evaluation.md) | マーケティング評価 | readme.txt、競合分析、差別化、ダウンロード数向上施策 |
| 04 | [04-google-faq-rich-results-2023.md](./04-google-faq-rich-results-2023.md) | Google FAQ リッチリザルト変更 | 2023年8月の制限、AI 検索時代の FAQ スキーマの価値 |

## 横断的な改善優先度

| 優先度 | 改善内容 | レポート |
|--------|---------|---------|
| **最高** | readme.txt を英語化 + 差別化訴求 | #03 |
| **最高** | スクリーンショット追加 | #03 |
| **高** | JSON-LD Answer.text から HTML 適切処理 | #01, #02 |
| **高** | FAQPage に url/name/dateModified 追加 | #02 |
| **高** | JSON-LD に JSON_HEX_TAG フラグ追加 | #01 |
| **高** | Google リッチリザルト制限を readme に記載 | #02, #04 |
| **中** | フロントHTML にラッパー + id 属性追加 | #02 |
| **中** | block.json faqs に items スキーマ追加 | #01 |
| **中** | タグ `json-ld` → `gutenberg` 変更 | #02, #03 |
