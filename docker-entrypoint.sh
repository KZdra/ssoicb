#!/bin/bash
set -e

echo "Menyiapkan aplikasi Laravel..."

# Link storage
php artisan storage:link || true

# Clear/Cache configuration
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Menjalankan migrasi database
echo "Menjalankan migrasi database..."
php artisan migrate --force --seed

echo "Memulai server Apache..."
exec apache2-foreground
