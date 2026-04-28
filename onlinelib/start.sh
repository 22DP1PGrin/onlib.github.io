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
chown -R www-data:www-data storage
chmod -R 775 storage
chmod -R 775 public/storage
chmod -R 775 storage bootstrap/cache

# Start Apache server
apache2-foreground
