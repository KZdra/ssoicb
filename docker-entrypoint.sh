#!/bin/bash
set -e

echo "Menyiapkan aplikasi Laravel..."

# Link storage
php artisan storage:link || true

# Generate APP_KEY jika belum ada di .env (Penting untuk deployment baru)
if [ -f .env ] && ! grep -q "^APP_KEY=base64:" .env; then
    echo "APP_KEY belum diset. Men-generate APP_KEY..."
    php artisan key:generate --force
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
