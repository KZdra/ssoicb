#!/bin/bash
set -e

echo "==========================================="
echo "   SCRIPT DEPLOYMENT SERVER DEBIAN/UBUNTU  "
echo "==========================================="

# 1. Install Docker & Docker Compose jika belum ada
if ! command -v docker &> /dev/null; then
    echo "[+] Menginstall Docker..."
    sudo apt update
    sudo apt install -y docker.io docker-compose-v2
    sudo systemctl start docker
    sudo systemctl enable docker
else
    echo "[✓] Docker sudah terinstall"
fi

# 2. Fix Error Dockerfile (Membuat file apache config yang hilang)
echo "[+] Memastikan file konfigurasi Apache ada (untuk mencegah error docker build)..."
mkdir -p docker/apache
cat <<EOF > docker/apache/000-default.conf
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
echo "[✓] File docker/apache/000-default.conf berhasil dibuat!"

# 3. Fix Permission agar script entrypoint bisa dieksekusi
if [ -f docker-entrypoint.sh ]; then
    chmod +x docker-entrypoint.sh
fi

# 4. Membangun dan Menjalankan Docker
echo "[+] Mulai proses build dan running (Docker Compose)..."
sudo docker compose up -d --build || sudo docker-compose up -d --build

echo "==========================================="
echo "[✓] DEPLOYMENT SELESAI!"
echo "[✓] Aplikasi berjalan di background."
echo "Untuk melihat log: sudo docker compose logs -f app"
echo "==========================================="
