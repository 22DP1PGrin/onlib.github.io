#!/bin/bash

echo "Starting Laravel app..."

# Run database migrations
php artisan migrate --force

echo "=== LAST LARAVEL ERROR ==="
cat storage/logs/laravel.log || true

# Fix permissions
chmod -R 775 storage bootstrap/cache

# Start Apache server
apache2-foreground
