php artisan down || true

git reset --hard
git pull origin main

composer install --no-dev --no-interaction --optimize-autoloader

bun install --production
bun run build

php artisan migrate --force
php artisan optimize
php artisan up