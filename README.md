# matsu-api

`matsu-api` は、matsu ワークスペースの家計簿ドメインを提供する Laravel API です。BFF から呼び出され、`matsu-auth` が発行したアクセストークンを検証します。

Laravel アプリは `src/www` にあります。サービス境界、API 契約、認証方式の詳細は末尾の設計文書を参照してください。

## 必要条件

- Docker Desktop または Docker Engine
- Docker Compose
- Git Bash など、`sh` スクリプトを実行できる環境

ローカルでは API が `http://localhost:18080/api`、MySQL が `localhost:13306` で公開されます。

## 初回セットアップ

リポジトリルートで次を実行します。

```bash
sh scripts/setup.sh
```

このスクリプトは Git hook の配置、Docker イメージのビルド、コンテナ起動、Composer 依存関係のインストール、マイグレーション、シードを順に行います。

ローカル開発用のアプリ・DB・認証設定は `src/www/.env.local` を Compose が読み込みます。これはローカル専用であり、本番の認証情報を記載しないでください。

## 起動と停止

```bash
docker compose up -d api
docker compose down
```

状態とログは次のコマンドで確認できます。

```bash
docker compose ps
docker compose logs -f api
```

通常の停止では named volume を削除しません。DB を含むローカルデータを消す操作は、必要性を確認してから行ってください。

## 更新と開発

`develop` の更新を取り込んだ後は、リポジトリルートで次を実行します。

```bash
sh scripts/update.sh
```

コンテナの更新、Composer 依存関係の同期、設定キャッシュのクリア、マイグレーション、シードが実行されます。

開発時は `develop` から作業ブランチを作成し、変更と検証を完了してから `develop` 向け Pull Request を作成します。リリースは GitHub 上で `develop` から `main` へマージします。

設定変更が反映されない場合は、次のコマンドで Laravel の設定キャッシュを削除します。

```bash
docker compose exec api php artisan config:clear
```

## 主な設定ファイル

- `docker-compose.yml`: ローカルの API・MySQL サービス、ポート、volume
- `src/www/.env.local`: ローカル開発用のアプリ・DB・認証設定
- `src/www/.env.testing`: CI とテスト用の設定
- `src/www/composer.json`: PHP 依存関係と品質ゲート

## 品質ゲート

API コンテナを起動した状態で、リポジトリルートから実行します。

```bash
docker compose exec api composer pint:test
docker compose exec api composer analyse
docker compose exec api composer test
```

コードを整形する場合は `docker compose exec api composer pint`、coverage を確認する場合は `docker compose exec api composer test:coverage` を実行します。

Git hook は初回セットアップに含まれます。単独で再配置する場合は次を実行します。

```bash
sh scripts/setup-hooks.sh
```

- `pre-commit`: ステージ済み PHP を Pint で整形し、変更を再ステージします。
- `pre-push`: push 対象の PHP に Pint と PHPStan を実行し、修正またはエラーがあれば push を停止します。

hook の実行には API コンテナが必要です。

GitHub Actions は `develop` または `main` 向け Pull Request で、依存関係のインストール、マイグレーション、シード、Pint、PHPStan、PHPUnit を実行します。workflow の正本は `.github/workflows/ci.yml` です。

## 設計文書

- [API コンポーネント](https://github.com/shu-matsukubo/matsu-docs/blob/main/docs/components/api.md)
- [API 契約](https://github.com/shu-matsukubo/matsu-docs/blob/main/docs/architecture/api-contracts.md)
- [認証とセッション](https://github.com/shu-matsukubo/matsu-docs/blob/main/docs/architecture/authentication.md)
- [品質ゲート](https://github.com/shu-matsukubo/matsu-docs/blob/main/docs/architecture/quality-gates.md)
