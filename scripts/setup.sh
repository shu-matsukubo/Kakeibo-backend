#!/bin/sh
set -e

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
PROJECT_ROOT=$(cd "$SCRIPT_DIR/.." && pwd)

cd "$PROJECT_ROOT"

echo "=== matsu セットアップ開始 ==="

echo ""
echo "[1/6] Gitフックをセットアップします..."
bash scripts/setup-hooks.sh

echo ""
echo "[2/6] Dockerイメージをビルドします..."
docker compose build --no-cache

echo ""
echo "[3/6] コンテナを起動します..."
docker compose up -d

echo ""
echo "[4/6] composer install を実行します..."
docker compose exec api composer install --no-interaction

echo ""
echo "[5/6] マイグレーションを実行します..."

echo ""
echo "DBの起動を待機中..."
until docker compose exec api-db mysqladmin ping -h localhost -u root -ptest_root_pass --silent 2>/dev/null; do
  printf "."
  sleep 2
done
echo " DB起動完了"

docker compose exec api php artisan migrate --force

echo ""
echo "[6/6] シーダーを実行します..."
docker compose exec api php artisan db:seed

echo ""
echo "=== セットアップ完了 ==="
