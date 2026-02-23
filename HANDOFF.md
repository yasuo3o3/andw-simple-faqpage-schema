# HANDOFF.md — 引き継ぎ情報

## 最終更新: 2026-02-23

## 現在の状態
- **バージョン**: 0.1.0（初回実装完了）
- **ステータス**: 初期実装完了、テスト待ち

## 完了した作業
1. プラグイン基盤ファイル一式を作成
2. Gutenberg FAQブロック実装（block.json + JSX）
3. フロントエンド描画（h2/h3/h4 + p、スタイルなし）
4. FAQPage Schema JSON-LD 出力（複数ブロック統合、空項目除外）
5. 設定ページ（見出しリセットON/OFF + カスタムCSS）
6. uninstall.php（オプション削除）
7. JSビルド成功（@wordpress/scripts）
8. PHP構文チェック通過

## 次にやるべきこと
1. **実環境テスト**: WordPressにインストールしてブロックの動作確認
2. **Plugin Check**: PHPCS + Plugin Check の実行
3. **翻訳対応**: 翻訳ファイル生成の検討（WP 4.6+で自動ロード対応済み）
4. **スクリーンショット**: readme.txt用のスクリーンショット作成
5. **Plugin Directory申請**: 準備が整ったら申請

## 注意点
- 回答フィールドはRichText（HTML可）。Schema出力ではHTMLをそのまま含む
- カスタムCSSは `wp_strip_all_tags()` でサニタイズ。CSS構文は破壊しない
- ブロック名は `andw/faq-schema`。has_block() での検出と一致を確認済み
- max-image-preview:large と画像機能はオーナー判断で削除済み
