#!/bin/bash
# =============================================================================
# Docker Entrypoint Script untuk Laravel ssoICB
# =============================================================================
set -e

echo "=================================================="
echo " ssoICB - Docker Entrypoint"
echo " Waktu: $(date +'%Y-%m-%d %H:%M:%S %Z')"
echo "=================================================="

# --- 1. Set Permission (penting jika volume di-mount) ---
echo "[1/7] Mengatur permission storage & bootstrap cache..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
echo "      ✓ Permission diatur."

# --- 2. Pastikan .env ada ---
echo "[2/7] Mengecek file .env..."
if [ ! -f /var/www/html/.env ]; then
    echo "      ! .env tidak ditemukan. Menyalin dari .env.example..."
    cp /var/www/html/.env.example /var/www/html/.env
fi
echo "      ✓ .env siap."

# --- 3. Generate APP_KEY jika belum ada ---
echo "[3/7] Mengecek APP_KEY..."
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env 2>/dev/null; then
    echo "      ! APP_KEY belum diset. Generating..."
    php artisan key:generate --force || true
    echo "      ✓ APP_KEY di-generate."
else
    echo "      ✓ APP_KEY sudah ada."
fi

# --- 4. Tunggu MySQL siap ---
echo "[4/7] Menunggu koneksi database (${DB_HOST}:${DB_PORT:-3306})..."
MAX_TRIES=30
COUNTER=0
until php -r "
    try {
        \$pdo = new PDO(
            'mysql:host=${DB_HOST};port=${DB_PORT:-3306};dbname=${DB_DATABASE}',
            '${DB_USERNAME}',
            '${DB_PASSWORD}'
        );
        echo 'ok';
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null | grep -q "ok"; do
    COUNTER=$((COUNTER + 1))
    if [ $COUNTER -ge $MAX_TRIES ]; then
        echo "      ✗ Gagal terhubung ke database setelah ${MAX_TRIES} percobaan. Melanjutkan..."
        break
    fi
    echo "      ... Mencoba koneksi (${COUNTER}/${MAX_TRIES})..."
    sleep 2
done
echo "      ✓ Database dapat dijangkau."

# --- 5. Jalankan Migrasi ---
echo "[5/7] Menjalankan database migration..."
php artisan migrate --force --no-interaction
echo "      ✓ Migrasi selesai."

# --- 6. Jalankan Passport keys jika belum ada ---
echo "[6/7] Mengecek Passport encryption keys..."
if [ ! -f /var/www/html/storage/oauth-private.key ] || [ ! -f /var/www/html/storage/oauth-public.key ]; then
    echo "      ! Passport keys tidak ditemukan. Generating..."
    php artisan passport:keys --force || true
    # Install client jika belum ada (opsional, hapus jika sudah setup manual)
    # php artisan passport:client --personal --name="ssoICB Personal Access Client" --no-interaction || true
    echo "      ✓ Passport keys di-generate."
else
    echo "      ✓ Passport keys sudah ada."
fi

# --- 7. Optimize Laravel ---
echo "[7/7] Mengoptimasi aplikasi Laravel..."
# Buat symlink storage
php artisan storage:link --force 2>/dev/null || true
# Clear semua cache lama
php artisan optimize:clear
# Rebuild cache untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
echo "      ✓ Optimasi selesai."

echo ""
echo "=================================================="
echo " ✓ ssoICB siap! Memulai Apache..."
echo "=================================================="
echo ""

# Jalankan perintah utama (apache2-foreground)
exec "$@"
