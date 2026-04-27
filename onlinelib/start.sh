#!/bin/bash

echo "Starting Laravel app..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

php artisan storage:link || true

echo "=== LAST LARAVEL ERROR ==="
cat storage/logs/laravel.log || true

# Fix permissions
chmod -R 775 storage bootstrap/cache

# Start Apache server
apache2-foreground
