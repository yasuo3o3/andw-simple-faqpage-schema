# HANDOFF.md — 引き継ぎ情報

## 最終更新: 2026-02-24

## 現在の状態
- **バージョン**: 0.1.0（初回実装完了）
- **ステータス**: 初期実装完了、ローカル実環境テスト待ち
- **ブランチ**: `main`（PR #1 マージ済み）

---

## セッション履歴

| # | ブランチ | 内容 | 状態 |
|---|---------|------|------|
| 1 | `claude/wordpress-faq-schema-plugin-Zh6Wf` | v0.1.0 初期実装 | PR #1 → main マージ済み |
| 2 | `claude/wordpress-faq-schema-plugin-vQSTL` | 実装セッション（補助） | 参照情報 |
| 3 | `claude/wordpress-faq-schema-docs-TXvx1` | ドキュメント整備・ブログ記事作成 | 完了 |
| 4 | - | 競合検知・JSON-LD抑制機能・フィルター追加 | 完了 |

---

## 完了した作業

### 実装（セッション1-2）
1. プラグイン基盤ファイル一式を作成
2. Gutenberg FAQブロック実装（block.json + JSX）
3. フロントエンド描画（h2/h3/h4 + p、スタイルなし）
4. FAQPage Schema JSON-LD 出力（複数ブロック統合、空項目除外）
5. 設定ページ（見出しリセットON/OFF + カスタムCSS）
6. uninstall.php（オプション削除）
7. JSビルド成功（@wordpress/scripts）
8. PHP構文チェック通過
9. v0.1.0 調査レポート4件を追加（セキュリティ監査・SEO構造化データ監査・マーケティング評価・Google FAQリッチリザルト調査）

### 実装（セッション4）
10. 設定画面に他SEOプラグイン（Yoast, Rank Math, AIOSEO）の検知と警告表示を追加
11. 設定画面に「JSON-LD出力を無効にする」オプションを追加
12. 投稿エディタに他プラグイン（Yoast/Rank Math）のFAQブロック競合検知と警告を追加（トップレベルおよびインナーブロックの再帰的走査に対応）
13. `andw_faq_schema_enabled` フィルターフックの追加とJSON-LD出力制御ロジックの適用
14. `uninstall.php` に新規オプションの削除処理を追記

### ドキュメント（セッション3）
9. HANDOFF.md 更新（ローカルIDE引き継ぎ用に詳細化）
10. ブログ記事作成（AI × WordPress プラグイン開発体験記）

---

## ファイル構成と役割

```
andw-simple-faqpage-schema/
├── andw-simple-faqpage-schema.php  # メインプラグインファイル（ブロック登録・JSON-LD出力・スタイル読込）
├── includes/
│   ├── render.php                  # フロントエンド描画（hN + p の HTML 出力）
│   └── settings.php                # 管理画面 設定ページ（見出しリセット・カスタムCSS）
├── src/
│   ├── block.json                  # Gutenbergブロック定義（attributes, supports）
│   ├── index.js                    # ブロック登録エントリポイント
│   ├── edit.js                     # エディタ側UI（FAQ項目の追加・編集・削除）
│   ├── save.js                     # 保存（null を返す＝動的レンダリング）
│   └── editor.css                  # エディタ用スタイル
├── build/                          # @wordpress/scripts ビルド出力（コミット済み）
│   ├── index.js                    # ミニファイ済みJS
│   ├── index.css                   # ミニファイ済みCSS
│   ├── index.asset.php             # 依存関係・バージョンハッシュ
│   └── block.json                  # ブロック定義コピー
├── readme.txt                      # WordPress.org Plugin Directory 用
├── uninstall.php                   # プラグイン削除時のオプション削除
├── package.json                    # npm 設定（@wordpress/scripts）
├── package-lock.json               # npm ロックファイル
├── CHANGELOG.md                    # バージョン履歴
├── CLAUDE.md                       # AI エージェント起動指示
├── LICENSE                         # GPLv2
├── .gitattributes                  # ZIP配布時の除外設定
├── .gitignore                      # Git除外設定
└── docs/
    ├── AGENTS.md                   # AI エージェント共通運用ガイド
    ├── WORDPRESS.md                # WordPress プラグイン開発規約（最上位規範）
    ├── CONTRIB.md                  # ローカル開発環境・命名デフォルト
    ├── PHASE-PLAN.md               # 実装計画（承認済み）
    ├── AI-CODING-PATTERNS.md       # AI コーディング再発防止パターン集
    ├── USER-NOTE.md                # 管理者メモ（プロジェクト横断）
    ├── USER-TASKS.md               # ユーザー向け作業指示
    ├── blog-ai-plugin-development.md  # ブログ記事（AI × WP開発体験記）
    ├── conversation-log/
    │   ├── 2026-02-23.md           # セッション1 会話ログ
    │   └── 2026-02-24.md           # セッション3 会話ログ
    └── external/
        └── agent-skills            # WordPress Agent Skills（git submodule）
```

---

## 次にやるべきこと（ローカルIDE作業）

### 1. 環境セットアップ
```bash
# リポジトリをクローン（まだの場合）
git clone https://github.com/yasuo3o3/andw-simple-faqpage-schema.git
cd andw-simple-faqpage-schema

# node_modules のインストール（初回のみ）
npm install

# ビルド確認（build/ は既にコミット済みだが念のため）
npm run build
```

### 2. WordPressへのインストール
- プラグインフォルダ（`wp-content/plugins/`）にコピーまたはシンボリックリンク
- WordPress管理画面 → プラグイン → 「andW Simple FAQPage Schema」を有効化
- 投稿エディタで「FAQ Schema」ブロックを挿入して動作確認

### 3. 動作確認チェックリスト
- [ ] ブロック挿入パネルに「FAQ Schema」が表示される
- [ ] FAQ項目の追加・編集・削除ができる
- [ ] 見出しレベル（h2/h3/h4）の切り替えが反映される
- [ ] 回答フィールドでRichText（太字・イタリック・リンク・リスト）が使える
- [ ] フロントエンドでFAQ項目が表示される（hN + p）
- [ ] ページソースの `<head>` に FAQPage JSON-LD が出力される
- [ ] 複数FAQブロックを配置した場合、JSON-LDが1つに統合される
- [ ] 空のFAQ項目がJSON-LDから除外される
- [ ] 設定 → FAQ Schema で見出しリセット・カスタムCSSが機能する
- [ ] Google Rich Results Test でスキーマが正しく認識される

### 4. 品質チェック
```bash
# PHP 構文チェック
php -l andw-simple-faqpage-schema.php
php -l includes/render.php
php -l includes/settings.php
php -l uninstall.php

# PHPCS（WordPress Coding Standards）
vendor/bin/phpcs --standard=WordPress andw-simple-faqpage-schema.php includes/

# Plugin Check（WordPress管理画面から実行）
# プラグイン「Plugin Check (PCP)」をインストール → ツール → Plugin Check
```

### 5. Plugin Directory 申請準備
- [ ] readme.txt のスクリーンショットセクションにスクリーンショットを追加
- [ ] Contributors に `yasuo3o3` が含まれていることを確認
- [ ] Stable tag と Version の一致を確認
- [ ] 翻訳ファイル生成の検討（WP 4.6+ で自動ロード対応済み）
- [ ] ZIPファイル作成: `git archive --format=zip --output=../andw-simple-faqpage-schema.zip --prefix=andw-simple-faqpage-schema/ HEAD --worktree-attributes`

---

## 技術メモ

### 識別子一覧
| 項目 | 値 |
|------|-----|
| ブロック名 | `andw/faq-schema` |
| テキストドメイン | `andw-simple-faqpage-schema` |
| 接頭辞 | `andw` |
| 見出しCSSクラス | `andw-faq-question` |
| オプション: 見出しリセット | `andw_faq_schema_reset_heading` |
| オプション: カスタムCSS | `andw_faq_schema_custom_css` |
| オプション: JSON-LD無効化 | `andw_faq_schema_disable_jsonld` |
| 設定グループ | `andw_faq_schema_settings` |
| 設定ページスラッグ | `andw-faq-schema-settings` |

### WordPressフック
| フック | 関数 | 目的 |
|--------|------|------|
| `init` | `andw_faq_schema_register_block` | ブロック登録 |
| `wp_head` | `andw_faq_schema_output_jsonld` | JSON-LD出力 |
| `andw_faq_schema_enabled` | - | （フィルター）JSON-LD出力制御 |
| `wp_enqueue_scripts` | `andw_faq_schema_enqueue_front_styles` | フロントCSS |
| `admin_init` | 設定登録 | Settings API |
| `admin_menu` | 設定ページ追加 | 管理メニュー |

### セキュリティ対策
- 質問テキスト: `esc_html()`
- 回答HTML: `wp_kses_post()`
- JSON-LD: `wp_json_encode()`（`JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES`）
- カスタムCSS: `wp_strip_all_tags()`（保存時）
- 設定変更: nonce + `manage_options` 権限チェック

---

## 注意点
- 回答フィールドはRichText（HTML可）。Schema出力ではHTMLをそのまま含む
- カスタมCSSは `wp_strip_all_tags()` でサニタイズ。CSS構文は破壊しない
- ブロック名は `andw/faq-schema`。`has_block()` での検出と一致を確認済み
- max-image-preview:large と画像機能はオーナー判断で削除済み
- `build/` ディレクトリはコミット済み。`node_modules/` は `.gitignore` で除外
