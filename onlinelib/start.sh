#!/bin/bash

echo "Starting Laravel app..."

# Generate app key if not set
php artisan key:generate --force

# Run database migrations
php artisan migrate --force

# Cache configuration for better performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Fix permissions
chmod -R 775 storage bootstrap/cache

# Start Apache server
apache2-foreground