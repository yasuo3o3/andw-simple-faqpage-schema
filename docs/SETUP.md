# SETUP.md — 環境セットアップ手順

このファイルは新しいパソコンで Claude Code 環境をセットアップする際の手順。

## グローバル設定

`~/.claude/settings.json` の `permissions.allow` に以下を追加:

```json
"Bash(git add:*)",
"Bash(git commit:*)"
```

これにより、コード変更時の自動コミットが確認なしで実行される。

## 確認方法

設定後、以下で確認:
```bash
cat ~/.claude/settings.json
```
