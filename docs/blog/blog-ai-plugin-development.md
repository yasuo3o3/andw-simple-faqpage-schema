# AIと一緒にWordPressプラグインを作ってみた — Claude Codeで「FAQ Schema」プラグインを開発した全記録

## はじめに

「よくある質問」をWordPressの記事に書いたとき、Googleの検索結果にリッチリザルト（質問と回答が展開表示されるアレ）として表示させたい。そのためには **FAQPage Schema**（構造化データ）を記事の `<head>` に埋め込む必要がある。

既存のプラグインもあるが、余計な機能が多かったり、UIが独特だったりと、ちょうどいいものがなかなか見つからない。そこで思い立った。

**「AIに作ってもらおう」**

使うのは **Claude Code**（Anthropic社が提供するCLIベースのAIコーディングツール）。私（管理者）がやりたいことを伝え、AIが設計・実装・コミットまでやってくれる。しかもサブエージェント（探索担当や設計担当のAI）が裏で動いて、コードベースを調べたり設計方針を練ったりしてくれる。

この記事では、その一部始終を物語形式でお伝えする。

---

## 第1章: 準備 — AIに「開発規約」を渡す

### なぜ規約が必要なのか

AIにいきなり「プラグイン作って」と言っても、それなりのものはできる。しかし、**WordPress.org Plugin Directory に申請して審査を通す**レベルのプラグインを作るには、細かいルールがたくさんある。セキュリティ、命名規約、i18n（国際化）、アンインストール処理……。

これらをAIが「知っている」場合もあるが、プロジェクト固有のルールは教えてあげないとわからない。そこで、事前に3つの規約ドキュメントを用意した。

### 用意したドキュメント

**1. WORDPRESS.md（最上位規範）**

WordPress.org の審査で引っかかりやすいポイントをすべて網羅した規約書。

```
禁止事項:
- コア直読み禁止（wp-config.php等の直接require）
- <script>/<style>直書き禁止（wp_enqueue_* を使え）
- php://input丸読み禁止

セキュリティ:
- 入力は sanitize_*、出力は esc_*
- 変更系は nonce + current_user_can() 必須

出荷ゲート:
- Text Domain = スラッグ
- 全エンドポイントで nonce + capability を先頭に実装
- ...
```

こういう「やってはいけないこと」をAIに明示しておくと、最初から審査に通りやすいコードが生成される。

**2. AGENTS.md（AI行動規範）**

AIエージェントの振る舞いを定義するファイル。ワークフロー（計画→スキル確認→検証→実装→テスト→ログ）、出力ルール（日本語、具体的なファイルパスを省略しない）、作業停止条件などを記載。

**3. CONTRIB.md（開発環境情報）**

プロジェクト固有の設定。接頭辞は `andw`、初期バージョンは `0.0.1`、作者は `yasuo3o3` など。

### 実装計画書も用意

`docs/PHASE-PLAN.md` に、作りたいプラグインの仕様を書いた。

- **プラグイン名**: andW Simple FAQPage Schema
- **ブロック名**: `andw/faq-schema`
- **機能**: Gutenbergブロックで FAQ を入力 → フロントで JSON-LD を `<head>` に出力
- **フロント表示**: 通常の見出し + 段落に溶け込むシンプルな見た目
- **設定ページ**: 見出し装飾リセット ON/OFF + カスタム CSS

最初は「画像設定」と「max-image-preview:large」の出力機能も要件に含めていたが、AIとの協議で**プラグインの責務外**として削除した。この「対話で仕様を絞り込む」プロセスも、AI開発の醍醐味のひとつだ。

---

## 第2章: 実装 — AIが一気に22ファイルを生成

### セッション開始

Claude Code を起動し、用意したドキュメントを読み込ませる。CLAUDE.md（プロジェクトの起動指示）を用意してあるので、AIはまず規約ドキュメントを順番に読み、HANDOFF.md（引き継ぎ情報）の有無を確認し、作業に入る。

### サブエージェントが動き出す

Claude Code には**サブエージェント**という仕組みがある。メインのAIが必要に応じて、専門的な役割を持つ別のAIを起動する。

- **Explore エージェント**: コードベースを探索し、既存のパターンや関連ファイルを調査する
- **Plan エージェント**: 実装方針を設計し、アーキテクチャ上のトレードオフを検討する

今回の開発では、まず Explore エージェントがリポジトリの構造を把握し、Plan エージェントが実装計画を立て、メインのAIが実際のコーディングを行う……という分業が自動的に行われた。

### 生成されたファイル群

1つのコミット（`feat: FAQPage Schema プラグイン v0.1.0 初期実装`）で、**22ファイル**が生成された。主要なものを紹介する。

#### メインプラグインファイル（andw-simple-faqpage-schema.php）

プラグインの心臓部。3つの主要関数が定義されている。

```php
// 1. ブロック登録
function andw_faq_schema_register_block() {
    register_block_type( __DIR__ . '/build', array(
        'render_callback' => 'andw_faq_schema_render_block',
    ) );
}
add_action( 'init', 'andw_faq_schema_register_block' );

// 2. JSON-LD 出力（wp_head フック）
function andw_faq_schema_output_jsonld() {
    if ( is_admin() || ! is_singular() ) {
        return; // 管理画面や一覧ページでは出力しない
    }
    $post = get_post();
    if ( ! has_block( 'andw/faq-schema', $post ) ) {
        return; // FAQブロックがなければ何もしない
    }
    // 全FAQブロックからQ&Aを収集し、1つのJSON-LDにまとめて出力
    $faqs = andw_faq_schema_collect_faqs( parse_blocks( $post->post_content ) );
    // ... JSON-LD を <script type="application/ld+json"> で出力
}
add_action( 'wp_head', 'andw_faq_schema_output_jsonld' );
```

ポイントは、**複数のFAQブロックがあっても1つのスキーマに統合**されること。再帰的にブロックを走査し、空の質問・回答は自動除外する。

#### Gutenbergブロック（src/edit.js）

エディタ側のUIを担当するReactコンポーネント。

```jsx
// FAQ項目の追加
const addFaq = () => {
    setAttributes({
        faqs: [ ...faqs, { question: '', answer: '' } ],
    });
};

// エディタ内でQ&Aを直感的に入力できるUI
// - 各項目に「Q1」「Q2」のラベル
// - 質問はプレーンテキスト、回答はRichText（太字・リンク等対応）
// - 削除ボタンで個別に削除可能
```

回答フィールドには WordPress の `RichText` コンポーネントを使い、太字・イタリック・リンク・リストに対応。当初はプレーンテキストだったが、AIとの協議で拡張した。

#### JSON-LD の出力例

プラグインが生成する構造化データはこのような形になる。

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "このプラグインは何をしますか？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "FAQPage構造化データ（JSON-LD）を自動生成します。"
      }
    },
    {
      "@type": "Question",
      "name": "複数のFAQブロックを使えますか？",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "はい。同じ投稿内に複数配置しても、<strong>1つのスキーマ</strong>に統合されます。"
      }
    }
  ]
}
```

この JSON-LD が記事の `<head>` セクションに自動挿入され、Google のクローラーが読み取ることで、検索結果にリッチリザルトとして表示される可能性が生まれる。

---

## 第3章: 仕様の磨き込み — 人間とAIの対話

### 機能の取捨選択

開発はただ「AIにお任せ」ではない。要所要所で人間が判断を下す場面がある。

**削除した機能:**
- 画像設定機能 → プラグインの責務を超えるため削除
- max-image-preview:large メタタグ → 画像機能削除に伴い不要に

**追加した機能:**
- 回答フィールドの RichText 対応（太字・リンク・リスト）
- 複数FAQブロックのスキーマ統合
- 空項目の自動除外

こうした判断は、AIが「こうした方がいいのでは？」と提案することもあれば、管理者が「これは要らない」と削ることもある。AI開発における**人間の役割は「方向性の決定」**だ。

### セキュリティへの配慮

WordPress プラグインでは、セキュリティが審査の最重要ポイント。AIは WORDPRESS.md の規約に従い、最初から安全なコードを書いてくれた。

| 対象 | 対策 |
|------|------|
| 質問テキスト出力 | `esc_html()` でHTMLエスケープ |
| 回答HTML出力 | `wp_kses_post()` で安全なHTMLタグのみ許可 |
| JSON-LD出力 | `wp_json_encode()` でエンコード |
| カスタムCSS保存 | `wp_strip_all_tags()` でHTMLを除去 |
| 設定変更 | nonce + `manage_options` 権限チェック |

事前に規約を渡しておくことで、「あとからセキュリティ修正」ではなく「最初から安全」なコードが生成される。これはAI開発における**ドキュメント整備の大きなメリット**だ。

---

## 第4章: PR作成とマージ — AIのワークフロー

### Pull Request #1

AIは実装が完了すると、Git ブランチ（`claude/wordpress-faq-schema-plugin-Zh6Wf`）にコミットし、Pull Request を作成した。

**コミットメッセージ:**
```
feat: FAQPage Schema プラグイン v0.1.0 初期実装

- Gutenberg FAQブロック（ブロック挿入パネル対応）
- FAQ項目の追加・編集・削除（RichText回答対応）
- FAQPage Schema JSON-LD 出力（複数ブロック統合・空項目除外）
- 見出しレベル選択（h2/h3/h4）+ 専用クラス andw-faq-question
- 設定ページ（見出し装飾リセット + カスタムCSS）
- uninstall.php / readme.txt / CHANGELOG.md 整備
```

22ファイル、22,610行の変更。管理者（私）がレビューし、問題なしと判断して main ブランチにマージした。

### ドキュメントの自動管理

AIは実装だけでなく、プロジェクト管理のドキュメントも自動的に作成・更新した。

- **CHANGELOG.md**: バージョン 0.1.0 の変更内容を記載
- **HANDOFF.md**: 次のセッション（または人間）への引き継ぎ情報
- **会話ログ**: `docs/conversation-log/2026-02-23.md` にセッションの概要を記録

これらは CLAUDE.md（プロジェクト起動指示）で「セッション終了時に更新すること」と指示してあるので、AIが自律的に対応してくれる。

---

## 第5章: 落とし穴との戦い — AI-CODING-PATTERNS.md

### AIが間違えやすいパターン

AI にコーディングを任せていると、特定のパターンで繰り返しバグが発生することがある。私たちのプロジェクトでは、そうした「落とし穴」を **AI-CODING-PATTERNS.md** というファイルに記録している。

**例: admin_enqueue_scripts の hook suffix 問題**

WordPress の管理画面で JS/CSS を読み込む際、`admin_enqueue_scripts` の `$hook_suffix` をハードコードすると、プラグインの構成によっては動かないことがある。AIは学習データの一般例から推測してハードコードしがちだ。

```php
// 間違いパターン（AIが書きがち）
function my_enqueue( $hook_suffix ) {
    if ( 'settings_page_my-plugin' !== $hook_suffix ) {
        return; // ← この文字列が環境によって変わる
    }
}

// 正しいパターン
function my_enqueue( $hook_suffix ) {
    $page = isset( $_GET['page'] )
        ? sanitize_text_field( wp_unslash( $_GET['page'] ) )
        : '';
    if ( 'my-plugin-page' !== $page ) {
        return; // ← $_GET['page'] で判定すれば確実
    }
}
```

こうした知見をドキュメントに蓄積し、AIが読み込むことで、同じ間違いを繰り返さなくなる。**AIの「組織的学習」**と言えるかもしれない。

---

## 第6章: 振り返りと学び

### AIにプラグインを作らせるコツ

この開発を通じて得た知見をまとめる。

**1. ドキュメント整備が9割**

AIのコード品質は、渡す情報の質に比例する。WORDPRESS.md のような詳細な規約書を用意しておくと、最初から審査レベルのコードが出てくる。「AIに任せるから楽」ではなく、「AIに正しく任せるための準備に力を入れる」のが正解。

**2. 対話で仕様を磨く**

最初の要件がそのまま最終仕様になることはない。AIと対話しながら「これは本当に必要か？」「こうした方がシンプルでは？」と議論することで、よりよい設計に落ち着く。

**3. 落とし穴を記録する**

AI-CODING-PATTERNS.md のような「AIが間違えやすいパターン集」を蓄積することで、プロジェクトが進むほどコード品質が上がる。

**4. 引き継ぎを仕組み化する**

HANDOFF.md でセッション間の引き継ぎを仕組み化することで、異なるセッション（あるいは異なるAIモデル）でも開発を継続できる。

### 今後のステップ

プラグインの実装は完了したが、リリースまでにはまだ作業がある。

1. **実環境テスト**: WordPressにインストールしてブロックの動作を確認
2. **品質チェック**: PHPCS（WordPress Coding Standards）と Plugin Check の実行
3. **スクリーンショット**: readme.txt 用のスクリーンショット撮影
4. **Plugin Directory 申請**: WordPress.org への提出

これらはローカルのWordPress環境で行う作業なので、ここからは人間の出番だ。

---

## おわりに

AIと一緒にプラグインを作る体験は、「プログラミングの民主化」の一端を感じさせるものだった。

もちろん、AIが万能というわけではない。WordPress 固有のフック名を間違えたり、環境依存で動かないコードを書いたりすることもある。しかし、適切なドキュメントを整備し、対話を通じて仕様を磨き、落とし穴を記録して共有する——こうした「人間側の工夫」によって、AIは驚くほど強力なパートナーになる。

今回作ったプラグインのコードは GitHub で公開している。FAQ Schema の構造化データを WordPress で扱いたい方、あるいは AI × 開発に興味がある方は、ぜひ参考にしてほしい。

---

## 技術情報

| 項目 | 値 |
|------|-----|
| プラグイン名 | andW Simple FAQPage Schema |
| バージョン | 0.1.0 |
| 対応WordPress | 6.4以上 |
| 対応PHP | 7.4以上 |
| 使用AIツール | Claude Code (Opus 4.6) |
| 開発期間 | 2026年2月23日〜24日（2セッション + ドキュメント整備1セッション） |
| 生成ファイル数 | 22ファイル |
| 主要技術 | Gutenberg Block API, JSON-LD, React (JSX), WordPress Settings API |
