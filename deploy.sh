#!/bin/bash
set -e

start_time=$(date +%s)

echo "🚀 Starting deployment process..."

echo "👷 Enabling maintenance mode..."
php artisan down || true

# echo "💾 Creating database backup..."
# php artisan backup:run --only-db || echo "⚠️ Database backup failed, continuing deployment..."

echo "📥 Updating codebase..."
git reset --hard
git pull origin main

echo "📦 Installing PHP dependencies..."
composer update --no-dev --no-interaction --optimize-autoloader

echo "🎨 Installing and building frontend assets..."
bun install --production
bun run build

echo "🔄 Running database migrations..."
php artisan migrate --force

echo "🧹 Optimizing application..."
php artisan optimize:clear
php artisan optimize

# echo "👷 Restarting queue workers..."
# php artisan queue:restart

echo "✅ Bringing application back online..."
php artisan up

end_time=$(date +%s)
duration=$((end_time - start_time))
echo "✨ Deployment completed in ${duration} seconds"
