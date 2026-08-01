#!/bin/sh
set -e

SCRIPT_DIR=$(cd "$(dirname "$0")" && pwd)
PROJECT_ROOT=$(cd "$SCRIPT_DIR/.." && pwd)

cd "$PROJECT_ROOT"

echo "=== matsu アップデート開始 ==="

echo ""
echo "[1/5] コンテナを起動・更新します..."
docker compose up -d

echo ""
echo "[2/5] composer install を実行します..."
docker compose exec web composer install --no-interaction

echo ""
echo "[3/5] Laravelの設定キャッシュをクリアします..."
docker compose exec web php artisan config:clear

echo ""
echo "[4/5] マイグレーションを実行します..."
docker compose exec web php artisan migrate --force

echo ""
echo "[5/5] シーダーを実行します..."
docker compose exec web php artisan db:seed

echo ""
echo "=== アップデート完了 ==="
