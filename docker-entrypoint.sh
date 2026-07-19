#!/bin/bash
set -e

echo "Menyiapkan aplikasi Laravel..."

# Link storage (hides error if exists)
php artisan storage:link -q || true

# Check jika APP_KEY kosong dan .env writable
if [ -f .env ] && [ -w .env ] && ! grep -q "APP_KEY=base64:" .env; then
    echo "APP_KEY belum diset. Men-generate APP_KEY..."
    php artisan key:generate --force || true
fi

# Clear/Cache configuration

php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Menjalankan migrasi database
echo "Menjalankan migrasi database..."
php artisan migrate --force --seed

# Cek apakah oauth keys belum ada, jika belum maka generate (untuk Laravel Passport)
if [ ! -f storage/oauth-private.key ]; then
    echo "Generate kunci Laravel Passport..."
    php artisan passport:keys
fi

echo "Memulai server Apache..."
exec apache2-foreground
