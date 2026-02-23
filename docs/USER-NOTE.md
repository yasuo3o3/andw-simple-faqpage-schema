# このドキュメントを読んだAIにAIエージェントへの重要連絡
このドキュメントの閲覧は許可をするが、あくまでいろいろなプロジェクトで使っている管理者のメモに過ぎない様々なプロジェクトの内容が入っているため、ここにあるものは今回のプロジェクトの参考にはしないこと。
ただし、管理者が困っているときにはここにヒントがある可能性があるので、その閲覧・引用して管理者に提示することは許可する。


## ClaudeCode向けプロジェクト開始プロンプト

【プロジェクト開始】

■ 規約の読み込み
・docs/AGENTS.md → docs/WORDPRESS.md → docs/CONTRIB.md の順に参照
・docs/external のエージェントスキルのうち、作業内容に関連するものを参照
・docs/AI-CODING-PATTERNS.md を参照し、既知の落とし穴を回避せよ
  作業中に新たなパターンを発見した場合は同ファイルに追記せよ
・承認ポリシーに従い、確認なしで安全な編集/読取を実行

■ 引き継ぎ確認
・HANDOFF.md（ルート）が存在する場合は最初に読み、現在の状況を把握せよ
・前回のエージェントが残した「次にやるべきこと」を確認し、
  継続作業がある場合はそこから再開せよ

■ 作業中のドキュメント管理
・実装前: docs/PHASE-PLAN.md に実装計画を作成（オーナー承認後に着手）
・実装中: CHANGELOG.md をバージョンごとに更新
・実装後: TESTING.md にテスト計画を作成、
  オーナー担当テストは GitHub Issue で起票（assignee: yasuo3o3）
・毎セッション終了時: HANDOFF.md を更新（現在地・次にやること・注意点）

■ 出力ルール
・作業完了時は短い日本語コミットメッセージを提示
・docs/conversation-log/YYYY-MM-DD.md にユーザー要求と最終回答のみを保存

以上を理解し、次の作業指示をするまで待機せよ


## WordPress.org 審査指摘の是正作業
これから plugin/プラグイン名/ のWordPress.org 審査指摘の是正作業を行う
作業指示のプロンプトを渡すので待機。


## 通常レビュー
/docs/REVIEW.md に従い、レビューを開始

## WPプラグインのレビュー
/docs/REVIEW-WP.md に従い、レビューを開始
レビューは必ず日本語で出力。
コードの修正は不要です

### 修正作業完了時に再度CODEXにチェックを依頼する際
再度レビューを依頼するのではなく、以下のように質問したほうが確実
- 指摘された事項を修正してみました。まだ問題が残っていますか?

### 単体で使用する
では、WORDPRESS.mdの規約に従ってプラグインの制作・修正作業を実行してください

## ZIP
mkdir -p zips
git archive --format=zip --output="zips/$(basename "$PWD")-$(date +%Y%m%d%H%M).zip" --prefix="$(basename "$PWD")/" HEAD --worktree-attributes

git archive --format=zip --output="zips/$(basename "$PWD")-$(date +%Y%m%d%H%M)-$(git rev-parse --short HEAD).zip" --prefix="$(basename "$PWD")/" HEAD --worktree-attributes


## VERSION-UP
/docs/VERSION-UP.mdを参照して、バージョンを0.0.0から0.0.0に変更・更新してください。
今回の主な変更内容は「レビューと修正対応」です。


## ドキュメントへの加筆
### 目的:
- プラグインのリリースに向け、ドキュメントファイルを最新状態に加筆する。

### 対象:
- readme.txt
- README.md
- CHANGELOG.txt
- DEVELOPER.md



### 制約:
- 英語ベースで書く（WordPress.org規約に合わせる）。
- 日本語での説明も後半に掲載する
- コードスタイルや関数名など技術情報は正確に反映。
- 余計な再構成や説明文の追加は不要。必要最低限の差分だけ。



## プラグインチェック（Plugin Check）のエラー解消
### 以下をCODEXに渡してClaudeCode用のプロンプトを作成させる

以下のエラー出力を整形し、Claude Codeに渡す実行手順書を作成してください。

### 作業指示:
- 目的はWordPress.orgプラグイン審査通過。機能変更禁止。
- 出力はClaude Codeに渡すための実行手順書。コード生成は禁止。
- 同一file×ruleはまとめて作業単位にする。順序は下から上。
- 各単位には rule_id / file / エラーメッセージ要旨 / 該当スニペット±数行 を含める。
- 各単位ごとに「最小差分修正」の作業手順を箇条書きで記述する。
- 変更行上限、禁止事項（関数定義変更禁止・外部依存追加禁止）も明記する。
- 1単位＝1コミット。コミットメッセージは `[rule_id] file: 要約`。
- 新規エラーが出た場合は即Revertする前提で書く。
- エラー出力中に現れる「リンクを編集」「コードエディタで表示」「さらに詳しく」といったUI用の文言は無視する。
- Claude Codeに渡す想定で、実行時の流れをステップごとに出力してください。

''' -- 以下にエラー出力を貼り付ける ---


## ClaudeCodeへの指示
CODEXからの回答を張り付けたのちに追加
- 各ファイル内の修正順序は**下から上**で適用せよ


## 名前の移行

### 作業方針:
- 目的: 旧ブランドから新ブランドへの一括リネームと整合性修正
- ドライランで変更計画を出力し、私の確認後に実行
- バイナリや依存物は除外

### 入力:
- 新ブランド表示名: andW
- 新スラッグ/接頭辞: andw
- 新リポジトリ名: andw-xxxxxx
- 旧ブランド表示名: <旧表示名>
- 旧スラッグ/接頭辞: <旧接頭辞>
- 旧リポジトリ名: <旧repo>

### 対象範囲:
- php, js/ts, css/scss, json, md, pot/po/mo, yml, xml, sh, twig, mustache
- ファイル名・ディレクトリ名・識別子・文字列リテラル

### 除外:
vendor/, node_modules/, dist/, build/, assets/images/, *.min.*, .git/, .github/, coverage/

### 置換ポリシー:
1. 可視名
- プラグイン名やUI表示: 旧表示名 → andW
2. スラッグ/識別子
- 関数・メソッド・変数・定数・フック名: 先頭の <旧接頭辞> → andw
- PHP 名前空間・クラス名のプレフィックス: <旧接頭辞> → Andw の PascalCase に（例: Andw\...）
- JS グローバル/名前空間: <旧接頭辞> → andw
- CSS クラス・data属性・BEM: .<旧接頭辞}- / [data-<旧接頭辞>-] → .andw- / [data-andw-]
- ファイル名・ディレクトリ: 先頭 <旧接頭辞>- → andw-

3. WordPress固有
- プラグインヘッダー: Plugin Name: andW xxxxxx に更新
- Text Domain と Domain Path を andw-xxxxxx に統一
- load_plugin_textdomain の引数を更新
- 翻訳関数のドメイン引数: __( '...', '<旧...>' ) → __( '...', 'andw-xxxxxx' )
- スクリプト/スタイルのハンドル名: <旧接頭辞>-... → andw-...
- REST API namespace: /<旧接頭辞>/v1 → /andw/v1
- Gutenberg ブロック名: <旧接頭辞>/<block> → andw/<block>
- WP-CLI コマンド名: <旧接頭辞> ... → andw ...
- オプション名・トランジェント・cron/nonceキー・cap名の接頭辞を andw に

### メタデータ
- composer.json の "name", "autoload.psr-4" を更新（例 "Andw\\" : "src/"）
- package.json の "name" を andw-xxxxxx に
- README/CHANGELOG/バッジURL/スクショキャプションの名称とスラッグ
- GitHub Actions/CI 設定内のスラッグ

### 大文字小文字規則
- 表示名: andW
- スラッグ/接頭辞/ハンドル/クラス前置き: andw（PHP NS のみ Andw）

### 安全置換
- 単語境界を考慮。部分一致で他語を壊さない
- 正規表現で接頭辞とハイフン区切りを優先
- 変更は1コミットにまとめず、論理単位で分割（ヘッダー/コード/ビルド）

### 出力（ドライラン）:
- 変更予定一覧: 「ファイル移動」「識別子置換」「WP固有修正」「メタデータ更新」に分類
- 破壊的変更の可能性リスト（例: シリアライズ済みキー、DB移行が要る項目）
- 除外ヒット数と未処理候補

### 実行手順:
- 私の「実行OK」後に変更を適用
- composer dump-autoload 相当の提案
- 翻訳: andw-xxxxxx.pot を再生成。po/mo を再配置
- 最後に grep レポートで旧名の残存有無を提示（ゼロなら成功）

### 検証:
- php -l と TypeScript/ESLint を走らせ、エラー要約のみ表示
- WordPress で有効化時の致命的エラー検出手順を案内

### 禁止:
- 除外ディレクトリ内の変更
- 外部API呼び出し


## 2026-02-05 JSON-LDプラグイン TablePress連携不具合対応
### 対象
plugin/andw-tabitamago-price-json-ld

### 問題
ショートコード `[tm_price_json_ld]` を設置してもJSON-LDが出力されず、デバッグログに `Table not found or empty` が記録される。

### 原因調査
1. **Query Monitor** による詳細ログ調査を実施。
2. `tablepress_get_table($id)` の戻り値をダンプした結果、**配列データではなくレンダリング済みのHTML文字列** が返ってきていることが判明。これにより、後続の `empty($table['data'])` チェックが意図せず `true` となりエラー扱いになっていた。
3. また、ACFのツアーコード（例: `A-1`）とTablePress側のコード（例: `A-01`、ソート用ゼロ埋め）の形式不一致も懸念された。

### 対応内容
1. **データ取得ロジックの修正**:
   - `tablepress_get_table()` の使用を中止。
   - TablePressの内部モデルクラス `TablePress::$model_table->load($id)` を使用するように変更し、生データ（配列）を直接取得するよう修正。
2. **ツアーコード照合ロジックの改善**:
   - ACFから取得したコード（`A-1`）で見つからない場合、自動的にゼロ埋め形式（`A-01`）を生成して再検索するリトライロジックを実装。
3. **デバッグ機能の強化とクリーンアップ**:
   - 調査用に詳細なログ出力（Dump等）を追加したが、解決後にこれらを削除し、セキュアな状態に戻した。

### 結果
- TablePressから正常に価格データが取得され、JSON-LD（Product/AggregateOffer）が正しく出力されるようになった。
- A-1 / A-01 の表記揺れがあっても自動対応できるようになった。


## 20260219 memo
1つ相談です。
今回のプロジェクトをコーディングするのは、CloudのSonnet 4.6 Thinkingでも十分に実現できますか?
また、コーディングを行うのが、ジェミニ3Prohighだとどうでしょうか?
私はCloudOps 4.6 Thinkingが好きですが、もし可能であればトークン価格の安いSONNETの方を利用しようと思っています。

Thought for 1s

良い質問です。このプロジェクトの特性を踏まえてお答えします。

結論：Sonnet 4.6で十分に実現可能です
このプロジェクトの技術的な難易度
要素	難易度	理由
Python + Playwright	⭐⭐ 低〜中	ライブラリのAPI呼び出しが中心
WordPress REST API	⭐⭐ 低〜中	定番パターン
WPプラグイン（管理画面）	⭐⭐⭐ 中	テーブル操作・画面実装あり
Chart.jsグラフ	⭐⭐ 低〜中	棒グラフ・折れ線グラフ程度
全体として「中程度」の難易度で、最新のAIモデルであれば品質差はほぼ出ません。

Claude Sonnet 4.6 Thinking
✅ このプロジェクトには十分すぎる性能
✅ WordPress プラグイン開発はやすおさんが何度もSonnetクラスで経験済み
✅ Python + Playwrightも定型的なコード
✅ Opusとの差が出るのは「複雑なアーキテクチャ設計」「大規模リファクタ」「曖昧な要件の解釈」のような場面。今回はドキュメントが明確なので差が出にくい
💰 コスト面で合理的な選択
Gemini 3 Pro High
✅ コーディング能力は十分
⚠️ WordPressプラグイン開発の経験値はClaudeの方がやすおさんの既存ドキュメント（
WORDPRESS.md
 等）との相性が良い可能性がある
⚠️ Geminiはときどき独自の判断でコードを変えることがあるので、既存の規約ドキュメントとの整合性に注意
おすすめ
Sonnet 4.6 Thinking をメインに使うのが最もバランスが良いと思います。理由は：

やすおさんのドキュメント体系（
WORDPRESS.md
、
AGENTS.md
、
CONTRIB.md
）はClaudeでの利用を前提に整備されている
今回のプロジェクトは「明確な仕様書に従って実装する」タスクなので、Sonnetで品質は十分
コスト効率が良い
もし途中で「ここだけは難しい」という箇所が出たら、その部分だけOpusに切り替える運用もアリ
Opusが必要になるケースがあるとすれば、フェーズ2のChart.jsグラフの複雑なインタラクション実装くらいですが、それも仕様が明確なので大丈夫だと思います。


---

## andw-server-speed-monitor 運用メモ（26/02/19）

### 🚀 まず最初にやること（プラグイン初期化）

プラグインを **WordPressの管理画面で一度「無効化 → 有効化」** する。  
→ `sessions` テーブルと `results.session_id` カラムが自動追加される（これをやらないとセッション機能が動かない）

---

### 📂 コレクターの実行方法

| ファイル | 用途 | 動作 |
|---|---|---|
| `run_collector.bat` | **手動実行用** | ウィンドウが開いてログが流れ、Enter で閉じる |
| `run_collector_scheduled.bat` | **タスクスケジューラー用** | ウィンドウなし・ログファイルのみに記録 |

**1回だけ手動で実行する場合：**
```
run_collector.bat をダブルクリック
```

**タスクスケジューラーに登録する場合（コマンド1行）：**
```bat
schtasks /Create /SC MINUTE /MO 30 /TN "andw-speed-monitor" /TR "C:\andW\andw-server-speed-monitor\collector\run_collector_scheduled.bat" /ST 09:00
```
ログは `collector/logs/scheduler_YYYY-MM-DD.log` に保存される。  
計測に10〜15分かかる場合は **30分間隔** が目安。

---

### 📊 フロントエンド ショートコード

WordPressの投稿・固定ページに貼り付けるだけで表示できる：
```
[andw_ssm_table]
[andw_ssm_table metric="ttfb,fcp,lcp"]
[andw_ssm_table metric="ttfb,finish,load_time,fcp,lcp" cache_type="both" days="30" limit="10"]
```

| 属性 | デフォルト | 値 |
|---|---|---|
| `metric` | `ttfb` | `ttfb,finish,load_time,fcp,lcp`（カンマ区切りで複数指定可） |
| `cache_type` | `no-cache` | `no-cache` / `cache` / `both` |
| `days` | `7` | 表示する日数 |
| `limit` | `20` | 最大表示件数 |

---

### 🗂 フェーズC（将来）着手タイミングの目安

以下のどれかに該当したら着手する：

- 同じサーバー会社で **複数プランを比較したい**（Premiumと通常など）
- 試用期間のサーバーが終わって **URLや契約が変わった**
- サーバー数が **10件を超えて管理が煩雑**になってきた
- 「このサーバーのURL変えたいけど過去データも残したい」と思った瞬間

→ その時点でAIに声をかければOK

spec-matome.netを取得せよ
ダミーデータサイトを用意せよ