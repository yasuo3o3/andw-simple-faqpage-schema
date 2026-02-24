# CLAUDE.md — プロジェクト起動指示

このファイルは Claude Code がチャット開始時に自動で読み込む。

## 規約の読み込み
- `docs/AGENTS.md` → `docs/WORDPRESS.md` → `docs/CONTRIB.md` の順に参照
- `docs/external` のエージェントスキルのうち、作業内容に関連するものを参照
- `docs/AI-CODING-PATTERNS.md` を参照し、既知の落とし穴を回避せよ
  - 作業中に新たなパターンを発見した場合は同ファイルに追記せよ

## 引き継ぎ確認
- `HANDOFF.md`（ルート）が存在する場合は最初に読み、現在の状況を把握せよ
- 前回のエージェントが残した「次にやるべきこと」を確認し、継続作業がある場合はそこから再開せよ

## 作業中のドキュメント管理
- 実装前: `docs/PHASE-PLAN.md` に実装計画を作成（オーナー承認後に着手）
- 実装中: `CHANGELOG.md` をバージョンごとに更新
- 実装後: `TESTING.md` にテスト計画を作成、オーナー担当テストは GitHub Issue で起票（assignee: yasuo3o3）
- コンテキスト80%超過時: `/blog-log` を実行してログを保存してから `/compact` を提案
- 毎セッション終了時: `HANDOFF.md` を更新（現在地・次にやること・注意点）

## 出力ルール
- コード変更時は細かい粒度でコミット（1ファイルまたは1機能単位、短い日本語メッセージ）
- `docs/conversation-log/YYYY-MM-DD.md` にユーザー要求と最終回答のみを保存

## テンプレート同期
- マスターテンプレート: `c:\andW\andw-template`
- 同期対象: `CLAUDE.md`, `docs/AGENTS.md`, `docs/AI-CODING-PATTERNS.md`, `docs/CONTRIB.md`, `docs/WORDPRESS.md`, `docs/SETUP.md`
- 同期時は差分を見せて確認してから反映すること
