# Panduan Lengkap Integrasi SSO ICB ke Aplikasi CBT (CBTICB)

Panduan ini dirancang khusus untuk mengintegrasikan **SSO ICB** ke dalam **Aplikasi CBT** tanpa perlu merusak struktur tabel `users` yang sudah ada di CBT.

---

## 📋 Ringkasan Pemetaan Data (Mapping Attributes)

| Field di CBT (`User`) | Sumber dari SSO ICB (`/api/me`) | Keterangan |
| :--- | :--- | :--- |
| `name` | `fullname` | Nama lengkap |
| `username` | `username` | NIP / Username |
| `email` | `email` | Alamat Email |
| `password` | *Auto-generated Hash* | Password acak aman (login ditangani via SSO) |
| `role` | `role` (`admin` → `admin`, `user` → `teacher`) | Mempertahankan role admin/guru |
| `nip` | `username` (atau tetap jika sudah ada) | Nomor Induk Pegawai |
| `phone` | `phone` | Nomor Telepon / WA |
| `avatar` | `avatar` | URL / path avatar |
| `is_active` | `status == 'active'` (boolean `true`/`false`) | Status aktif user |

---

## 🚀 Langkah 1: Daftarkan Aplikasi CBT di SSO Server

1. Buka Admin Dashboard **SSO ICB** (misal: `http://localhost:8001`).
2. Masuk ke menu **Client Applications** → klik **Add New Client**.
3. Masukkan data:
   - **Name**: `Aplikasi CBT`
   - **Redirect URI**: `http://localhost:8000/auth/callback` *(sesuaikan dengan domain/port CBT Anda)*
   - **Status**: `Active`
4. Simpan, lalu salin **Client ID** dan **Client Secret** yang muncul pada notifikasi hijau.

---

## ⚙️ Langkah 2: Tambahkan Konfigurasi di `.env` Aplikasi CBT

Buka file `.env` di direktori aplikasi **CBT**, tambahkan baris berikut di paling bawah:

```env
# Konfigurasi SSO ICB
SSO_SERVER_URL=http://localhost:8001
SSO_CLIENT_ID=masukkan_client_id_dari_sso
SSO_CLIENT_SECRET=masukkan_client_secret_dari_sso
SSO_REDIRECT_URI=http://localhost:8000/auth/callback
```
> **Catatan**: Jika SSO berjalan di port lain (misal 8000) dan CBT di port 8001, balikkan nilai port di atas sesuai port masing-masing server.

---

## 🛣️ Langkah 3: Tambahkan Route SSO di CBT (`routes/web.php`)

Buka file `routes/web.php` di aplikasi **CBT**, tambahkan rute berikut:

```php
use App\Http\Controllers\SsoController;

// Rute Autentikasi Single Sign-On (SSO)
Route::get('/auth/sso/redirect', [SsoController::class, 'redirect'])->name('sso.redirect');
Route::get('/auth/callback', [SsoController::class, 'callback'])->name('sso.callback');
```

---

## 🎮 Langkah 4: Buat Controller SSO di CBT (`app/Http/Controllers/SsoController.php`)

Jalankan perintah berikut di terminal aplikasi CBT Anda:
```bash
php artisan make:controller SsoController
```

Kemudian buka file `app/Http/Controllers/SsoController.php` dan masukkan kode berikut:

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    /**
     * Mengarahkan user ke SSO Server untuk login
     */
    public function redirect(Request $request)
    {
        $request->session()->put('sso_state', $state = Str::random(40));

        $query = http_build_query([
            'client_id'     => env('SSO_CLIENT_ID'),
            'redirect_uri'  => env('SSO_REDIRECT_URI'),
            'response_type' => 'code',
            'scope'         => '',
            'state'         => $state,
        ]);

        return redirect(rtrim(env('SSO_SERVER_URL'), '/') . '/oauth/authorize?' . $query);
    }

    /**
     * Menangani callback dari SSO Server, menukar authorization code dengan token,
     * lalu menyinkronkan user ke tabel users CBT.
     */
    public function callback(Request $request)
    {
        $sessionState = $request->session()->pull('sso_state');

        // 1. Validasi CSRF state
        if (empty($sessionState) || $sessionState !== $request->state) {
            return redirect('/login')->with('error', 'Validasi sesi SSO gagal (State mismatch). Silakan coba lagi.');
        }

        // Cek jika user membatalkan otorisasi
        if ($request->has('error')) {
            return redirect('/login')->with('error', 'Otorisasi login dibatalkan oleh pengguna.');
        }

        // 2. Tukar Authorization Code dengan Access Token
        $response = Http::asForm()->post(rtrim(env('SSO_SERVER_URL'), '/') . '/oauth/token', [
            'grant_type'    => 'authorization_code',
            'client_id'     => env('SSO_CLIENT_ID'),
            'client_secret' => env('SSO_CLIENT_SECRET'),
            'redirect_uri'  => env('SSO_REDIRECT_URI'),
            'code'          => $request->code,
        ]);

        if ($response->failed()) {
            return redirect('/login')->with('error', 'Gagal mendapatkan token autentikasi dari SSO Server.');
        }

        $tokenData = $response->json();
        $accessToken = $tokenData['access_token'] ?? null;

        if (!$accessToken) {
            return redirect('/login')->with('error', 'Access Token tidak ditemukan dari SSO Server.');
        }

        // 3. Ambil data profil user dari SSO Server
        $userResponse = Http::withHeaders([
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer ' . $accessToken,
        ])->get(rtrim(env('SSO_SERVER_URL'), '/') . '/api/me');

        if ($userResponse->failed()) {
            return redirect('/login')->with('error', 'Gagal mengambil data profil dari SSO Server.');
        }

        $ssoUser = $userResponse->json();

        // 4. Sinkronisasi dengan Tabel Users di CBT
        $username = $ssoUser['username'] ?? null;
        $email    = $ssoUser['email'] ?? null;
        $isActive = ($ssoUser['status'] ?? 'active') === 'active';

        // Cek apakah akun aktif
        if (!$isActive) {
            return redirect('/login')->with('error', 'Akun SSO Anda dalam status nonaktif. Hubungi administrator.');
        }

        // Cari user berdasarkan username atau email
        $user = User::where(function ($query) use ($username, $email) {
            if ($username) {
                $query->where('username', $username);
            }
            if ($email) {
                $query->orWhere('email', $email);
            }
        })->first();

        // Mapping role: jika di SSO admin -> admin, selain itu -> teacher
        $mappedRole = ($ssoUser['role'] === 'admin') ? 'admin' : 'teacher';

        if ($user) {
            // User sudah terdaftar: perbarui profilnya
            $user->update([
                'name'      => $ssoUser['fullname'] ?? $user->name,
                'email'     => $email ?? $user->email,
                'phone'     => $ssoUser['phone'] ?? $user->phone,
                'avatar'    => $ssoUser['avatar'] ?? $user->avatar,
                'is_active' => true,
                // Jika role belum diset atau ingin sinkronkan:
                'role'      => $user->role ?: $mappedRole,
            ]);
        } else {
            // User baru: buat akun baru di database CBT
            $user = User::create([
                'name'      => $ssoUser['fullname'] ?? $username,
                'username'  => $username,
                'email'     => $email,
                'password'  => Hash::make(Str::random(32)), // Password random acak aman
                'role'      => $mappedRole,
                'nip'       => is_numeric($username) ? $username : null,
                'phone'     => $ssoUser['phone'] ?? null,
                'avatar'    => $ssoUser['avatar'] ?? null,
                'is_active' => true,
            ]);
        }

        // 5. Login pengguna ke sesi CBT
        Auth::login($user, true);

        // 6. Redirect sesuai role
        if ($user->isAdmin()) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/dashboard');
    }
}
```

---

## 🎨 Langkah 5: Tambahkan Tombol "Login dengan SSO ICB" di Halaman Login CBT

Buka file tampilan login CBT (misalnya: `resources/views/auth/login.blade.php`):

Tempatkan kode tombol ini di bawah form login atau di atas tombol submit:

```html
<!-- Tombol SSO ICB -->
<div class="mt-4">
    <div class="relative flex py-2 items-center">
        <div class="flex-grow border-t border-gray-300"></div>
        <span class="flex-shrink mx-4 text-gray-400 text-xs font-semibold uppercase">atau</span>
        <div class="flex-grow border-t border-gray-300"></div>
    </div>

    <a href="{{ route('sso.redirect') }}" 
       class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm rounded-lg shadow-sm transition duration-150">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
            <polyline points="10 17 15 12 10 7"/>
            <line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
        <span>Masuk dengan Akun SSO ICB</span>
    </a>
</div>
```

*(Jika CBT menggunakan Bootstrap, cukup sesuaikan class-nya)*:
```html
<a href="{{ route('sso.redirect') }}" class="btn btn-primary w-100 mt-3 d-flex align-items-center justify-content-center gap-2">
    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
    Login dengan SSO ICB
</a>
```
