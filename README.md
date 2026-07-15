# Central Authentication Server (SSO)

![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![PHP 8.3+](https://img.shields.io/badge/PHP-8.3+-777BB4?style=for-the-badge&logo=php)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql)
![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap)

Sistem **Single Sign-On (SSO)** tersentralisasi ini dirancang untuk menjadi gerbang otentikasi tunggal bagi seluruh ekosistem aplikasi di organisasi/perusahaan Anda. Menggunakan arsitektur OAuth2 Authorization Code Flow melalui [Laravel Passport](https://laravel.com/docs/passport), sistem ini memungkinkan pengguna untuk login satu kali dan mendapatkan akses aman ke berbagai aplikasi klien yang telah diotorisasi.

## 🚀 Fitur Utama

- **OAuth2 Authorization Server**: Mengimplementasikan standar industri OAuth2 (Authorization Code Grant) untuk SSO transparan antarsistem.
- **Seamless Login Experience**: Konfigurasi khusus yang melewati (skip) halaman *authorization prompt* berulang kali demi pengalaman SSO yang sejati.
- **Manajemen Klien Terpadu (CRUD)**: Administrator dapat mendaftarkan, memperbarui, dan mencabut akses aplikasi klien serta melakukan *Regenerate Secret* kapan saja.
- **Manajemen Pengguna (CRUD)**: Panel Admin lengkap untuk mengatur data profil dan kredensial pengguna.
- **Audit Log Otomatis**: Seluruh aktivitas CRUD pada level Data (User & Client), beserta proses *Login/Logout*, dilacak secara otomatis menggunakan teknologi *Model Observers* dan disimpan rapi untuk ditinjau oleh Admin.
- **Pemantauan Sesi Aktif**: Administrator memiliki kendali penuh untuk melihat daftar sesi yang sedang aktif dan dapat mencabut akses (*terminate session*) sewaktu-waktu.
- **Desain UI/UX Premium**: Dibangun di atas fondasi Bootstrap 5 dengan modifikasi estetika *Glassmorphism*, font eksklusif *Inter*, animasi mikro (*micro-animations*), dan desain yang 100% responsif di perangkat seluler.

## 💻 Tech Stack

- **Framework**: Laravel 12.x
- **Bahasa**: PHP 8.3+
- **Database**: MySQL (InnoDB, `utf8mb4_unicode_ci`)
- **Autentikasi**: Laravel Passport & Laravel Breeze
- **Frontend**: Blade Template, Vanilla CSS (SCSS), Bootstrap 5

## 📖 Panduan Integrasi (Untuk Aplikasi Klien)

Jika Anda memiliki aplikasi (seperti Aplikasi HRD, Keuangan, dll) yang ingin menggunakan otentikasi dari SSO Server ini, silakan ikuti petunjuk langkah demi langkah yang telah kami sediakan secara terpisah.

👉 **[BACA PANDUAN INTEGRASI KLIEN (SSO_CLIENT_INTEGRATION.md)](./SSO_CLIENT_INTEGRATION.md)**

## ⚙️ Instalasi & Menjalankan Aplikasi Secara Lokal

1. **Clone repository ini:**
   ```bash
   git clone <url-repo-anda>
   cd sso-server
   ```

2. **Instal seluruh dependensi backend:**
   ```bash
   composer install
   ```

3. **Salin file konfigurasi *environment*:**
   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi Database (`.env`):**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_sso
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

6. **Jalankan Migrasi Database:**
   ```bash
   php artisan migrate
   ```

7. **Instalasi Laravel Passport (Generate Encryption Keys):**
   ```bash
   php artisan passport:install
   ```

8. **Tautkan Direktori Storage (Untuk unggah avatar pengguna):**
   ```bash
   php artisan storage:link
   ```

9. **Instal dan Kompilasi Asset Frontend:**
   ```bash
   npm install
   npm run build
   ```

10. **Jalankan Server Lokal:**
    ```bash
    php artisan serve
    ```

## 🔐 Manajemen Akses

Sistem menggunakan middleware berbasis peran (`role:admin`) untuk memproteksi panel administrasi. Secara *default*, sistem tidak menyediakan akun *super-admin* sejak awal. Anda dapat membuat pengguna pertama dan mengubah kolom `role` menjadi `admin` langsung pada basis data untuk mengakses fitur manajemen di `/dashboard`.

---
*Dibangun dengan dedikasi untuk menciptakan arsitektur perangkat lunak yang bersih, aman, dan elegan.*
