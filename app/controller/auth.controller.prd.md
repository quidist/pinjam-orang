# PRD: auth.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller autentikasi & sesi pengguna (*Authentication & Session*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengatur alur registrasi akun baru, proses login (verifikasi kata sandi), logout, manajemen session pengguna, serta fungsi proteksi rute (auth guard / middleware sederhana).
- **Letak File Target:** `app/controller/auth.controller.php`
- **Model Terkait:** `app/model/users.model.php`
- **Ketergantungan:** Session PHP (`session_start()`), helper redirect, enkripsi password PHP native (`password_hash`, `password_verify`).

---

## 2. Alur Kerja & Endpoints / Aksi

| Aksi | Method HTTP | Deskripsi | Input Utama | Output / Redirect |
|---|---|---|---|---|
| `showRegisterForm` | GET | Menampilkan halaman form pendaftaran | - | Render view `register.php` |
| `handleRegister` | POST | Memproses pendaftaran user baru | `name`, `email`, `password`, `password_confirm` | Redirect ke `/login` dengan notifikasi sukses, atau reload jika error |
| `showLoginForm` | GET | Menampilkan halaman form login | - | Render view `login.php` |
| `handleLogin` | POST | Memvalidasi kredensial dan set session | `email`, `password` | Set `$_SESSION['user_id']`, redirect ke dashboard/katalog |
| `handleLogout` | POST/GET | Menghapus session dan logout | - | Hancurkan session, redirect ke `/login` atau homepage |
| `requireAuth` | Helper / Guard | Memastikan pengguna sudah login sebelum mengakses fitur privat | Session check | Lanjut eksekusi atau redirect ke `/login` |

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `authRegister(PDO $pdo, array $postData): array`
- **Kegunaan:** Menangani logika pendaftaran pengguna baru.
- **Validasi:**
  - `name`: Wajib, minimal 3 karakter, maksimal 100 karakter.
  - `email`: Wajib, format email valid (`filter_var`), belum pernah terdaftar (`isEmailExists`).
  - `password`: Wajib, minimal 6 karakter.
  - `password_confirm`: Harus persis sama dengan `password`.
- **Proses:** Lakukan `password_hash($password, PASSWORD_BCRYPT)`. Panggil `createUser()` di model.
- **Nilai Kembalian:** Array status `['success' => bool, 'message' => string, 'errors' => array]`.

### 3.2. `authLogin(PDO $pdo, array $postData): array`
- **Kegunaan:** Menangani proses login dan inisialisasi session.
- **Validasi:** Email dan password tidak boleh kosong.
- **Proses:** 
  - Cari pengguna dengan `getUserByEmail()`.
  - Cek kecocokan password menggunakan `password_verify($password, $user['password_hash'])`.
  - Jika cocok: set `$_SESSION['user'] = ['id' => $user['id'], 'name' => $user['name'], 'email' => $user['email']]`.
- **Nilai Kembalian:** Array status `['success' => bool, 'message' => string]`.

### 3.3. `authLogout(): void`
- **Kegunaan:** Mengakhiri sesi pengguna secara aman.
- **Proses:** Bersihkan array `$_SESSION`, hapus cookie sesi jika ada, jalankan `session_destroy()`, lalu redirect ke halaman utama/login.

### 3.4. `isAuthenticated(): bool`
- **Kegunaan:** Helper untuk memeriksa apakah ada user yang sedang login aktif dalam sesi saat ini.
- **Nilai Kembalian:** `true` jika `isset($_SESSION['user']['id'])`, sebaliknya `false`.

### 3.5. `getCurrentUser(PDO $pdo): ?array`
- **Kegunaan:** Mengambil data profil lengkap dari user yang sedang login saat ini.
- **Nilai Kembalian:** Array profil user atau `null` jika belum login.

### 3.6. `requireAuth(): void`
- **Kegunaan:** Guard / pencegah akses: jika user belum login, simpan return URL dan alihkan seketika ke halaman login.

---

## 4. Checklist Implementasi
- [ ] Penggunaan `password_hash()` dengan algoritma aman (`PASSWORD_BCRYPT` atau `PASSWORD_DEFAULT`).
- [ ] Mencegah Session Fixation dengan `session_regenerate_id(true)` saat login berhasil.
- [ ] Sanitasi seluruh input `POST` (`trim`, `htmlspecialchars` pada tampilan).
- [ ] Menyediakan pesan error yang informatif namun aman (hindari membocorkan secara detail apakah email atau password yang salah pada form login demi keamanan).

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-ATH-01 | Happy Path | Registrasi dengan data lengkap dan valid | User tersimpan, return `success => true` |
| TC-ATH-02 | Validasi | Registrasi dengan konfirmasi password tidak cocok | Gagal, error "Konfirmasi kata sandi tidak cocok" |
| TC-ATH-03 | Validasi | Registrasi dengan email yang sudah digunakan | Gagal, error "Email sudah terdaftar" |
| TC-ATH-04 | Happy Path | Login dengan email dan password yang benar | Session `user` terisi, redirect ke halaman utama |
| TC-ATH-05 | Keamanan | Login dengan password salah | Gagal, session tidak terbuat, error kredensial tidak valid |
| TC-ATH-06 | Happy Path | Pemanggilan `authLogout` | Session terhapus, `isAuthenticated()` bernilai `false` |
| TC-ATH-07 | Guard | Mengakses fitur yang butuh login tanpa session aktif | Diarahkan otomatis ke halaman login |

