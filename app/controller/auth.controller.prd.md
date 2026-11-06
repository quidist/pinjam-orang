# PRD: auth.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller autentikasi pengguna (*User Authentication & Session Management*) pada sistem **PINJAMORANG**.

---

## 1. Identitas Class & Dependencies
- **Nama File Target:** `app/controller/auth.controller.php`
- **Nama Class:** `AuthController`
- **Tanggung Jawab:** Mengelola seluruh alur pendaftaran akun baru, proses masuk (verifikasi password), keluar akun, pengaturan session pengguna, serta fungsi penjaga gerbang (*Auth Guard*).
- **Dependencies (Ketergantungan):**
  - Objek database: `PDO $pdo`
  - Model: `UserModel` dari `app/model/users.model.php`

### Kerangka Dasar Class:
```php
class AuthController {
    private PDO $pdo;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($pdo);
    }
    // ... method-method di bawah ini
}
```

---

## 2. Tabel Pemetaan Routing (`app/index.php`)

| URL Route (`?page=...`) | HTTP Method | Method Controller yang Dipanggil | Proteksi Akses |
|---|---|---|---|
| `?page=login` | `GET` | `AuthController::showLoginForm()` | Tamu Saja (`requireGuest`) |
| `?page=login` | `POST` | `AuthController::handleLogin()` | Tamu Saja (`requireGuest`) |
| `?page=register` | `GET` | `AuthController::showRegisterForm()` | Tamu Saja (`requireGuest`) |
| `?page=register` | `POST` | `AuthController::handleRegister()` | Tamu Saja (`requireGuest`) |
| `?page=logout` | `GET` atau `POST` | `AuthController::handleLogout()` | Wajib Login (`requireAuth`) |

---

## 3. Spesifikasi Lengkap Fungsi / Method

### 3.1. `public function showLoginForm(): void`
- **Kegunaan / Untuk Apa:** Menampilkan halaman antarmuka formulir masuk akun ke layar browser.
- **Logika Alur:**
  1. Cek sesi: Jika user sudah login, alihkan ke `?page=home` (`$this->requireGuest()`).
  2. Siapkan judul halaman: `$pageTitle = "Masuk - PINJAMORANG"`.
  3. Ambil pesan error (jika ada error yang dioper dari redirect sebelumnya).
  4. Rakit komponen View:
     ```php
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/auth/login.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.2. `public function handleLogin(): void`
- **Kegunaan / Untuk Apa:** Memvalidasi kredensial (email & password) yang dikirim oleh form login, menginisialisasi sesi login jika cocok, atau mengembalikan pesan kesalahan jika gagal.
- **Input yang Dibaca:** `$_POST['email']` dan `$_POST['password']`.
- **Logika Alur:**
  1. Bersihkan dan sanitasi input (`trim`).
  2. Validasi: Jika email atau password kosong, set pesan error dan tampilkan kembali form login.
  3. Panggil Model: `$user = $this->userModel->getUserByEmail($email);`
  4. Verifikasi Kata Sandi:
     - Jika `$user` ditemukan dan `password_verify($password, $user['password_hash']) === true`:
       - Jalankan `session_regenerate_id(true)` (keamanan anti-session fixation).
       - Simpan identitas ke session:
         ```php
         $_SESSION['user'] = [
             'id'          => $user['id'],
             'name'        => $user['name'],
             'email'       => $user['email'],
             'trust_score' => $user['trust_score']
         ];
         ```
       - Pasang flash alert: `$_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Selamat datang kembali, ' . $user['name'] . '!'];`
       - Redirect ke: `header("Location: /index.php?page=home"); exit;`
     - Jika tidak cocok:
       - Set pesan error aman: *"Email atau kata sandi tidak sesuai."*
       - Tampilkan kembali form login dengan membawa input email lama.

### 3.3. `public function showRegisterForm(): void`
- **Kegunaan / Untuk Apa:** Menampilkan halaman formulir pendaftaran akun baru.
- **Logika Alur:**
  1. Cek sesi: Jika sudah login, lempar ke home.
  2. Siapkan judul halaman: `$pageTitle = "Daftar Akun Baru - PINJAMORANG"`.
  3. Rakit komponen View:
     ```php
     $extraScript = "/assets/js/auth.js"; // Aktifkan live password match & strength meter
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/auth/register.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.4. `public function handleRegister(): void`
- **Kegunaan / Untuk Apa:** Memproses pendaftaran akun baru, memvalidasi aturan bisnis (email belum pernah terdaftar, password minimal 6 karakter, konfirmasi cocok), mengenkripsi kata sandi, dan menyimpan ke database.
- **Input yang Dibaca:** `$_POST['name']`, `$_POST['email']`, `$_POST['password']`, `$_POST['password_confirm']`.
- **Logika Alur:**
  1. Validasi:
     - Nama: Wajib diisi, minimal 3 karakter, maksimal 100 karakter.
     - Email: Wajib format email valid (`filter_var`), dan belum terdaftar di database via `$this->userModel->isEmailExist($email)`.
     - Password: Wajib minimal 6 karakter.
     - Konfirmasi: Wajib bernilai sama persis dengan password.
  2. Jika ada validasi yang gagal:
     - Simpan array `$errors` dan data `$oldInput`.
     - Tampilkan kembali form register dengan daftar error tersebut.
  3. Jika validasi sukses:
     - Enkripsi password: `$passwordHash = password_hash($password, PASSWORD_BCRYPT);`
     - Panggil Model untuk simpan data:
       ```php
       $newUserId = $this->userModel->createuser([
           'name'          => $name,
           'email'         => $email,
           'password_hash' => $passwordHash
       ]);
       ```
     - Set flash alert sukses: `$_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Akun berhasil dibuat! Silakan masuk.'];`
     - Redirect ke: `header("Location: /index.php?page=login"); exit;`

### 3.5. `public function handleLogout(): void`
- **Kegunaan / Untuk Apa:** Mengakhiri sesi pengguna secara aman saat user mengklik menu "Keluar / Logout".
- **Logika Alur:**
  1. Kosongkan data session: `$_SESSION = [];`
  2. Hancurkan cookie sesi jika ada.
  3. Hancurkan session: `session_destroy();`
  4. Redirect ke halaman login: `header("Location: /index.php?page=login"); exit;`

### 3.6. `public static function requireAuth(): void` (Helper Guard)
- **Kegunaan / Untuk Apa:** Fungsi satpam pemeriksa login. Dipanggil di awal method controller lain yang mewajibkan pengguna login terlebih dahulu.
- **Logika:**
  ```php
  if (!isset($_SESSION['user']['id'])) {
      $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Silakan masuk terlebih dahulu untuk mengakses fitur ini.'];
      header("Location: /index.php?page=login");
      exit;
  }
  ```

### 3.7. `public static function requireGuest(): void` (Helper Guard)
- **Kegunaan / Untuk Apa:** Mencegah pengguna yang sudah login agar tidak membuka kembali halaman `/login` atau `/register`.
- **Logika:**
  ```php
  if (isset($_SESSION['user']['id'])) {
      header("Location: /index.php?page=home");
      exit;
  }
  ```

---

## 4. Checklist Implementasi
- [ ] Class `AuthController` menginjeksi `PDO` dan menginstansiasi `UserModel` di constructor.
- [ ] Kata sandi selalu dienkripsi dengan `password_hash($pass, PASSWORD_BCRYPT)`.
- [ ] Tidak pernah menyimpan teks `password_hash` di dalam array `$_SESSION`.
- [ ] Memanggil `session_regenerate_id(true)` saat login berhasil untuk mencegah *Session Fixation*.
- [ ] Seluruh alur redirect diakhiri dengan perintah `exit;`.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-AC-01 | Tampilan | Buka `?page=login` saat belum login | Merender form login dengan judul "Masuk - PINJAMORANG" |
| TC-AC-02 | Guard | Buka `?page=login` saat sudah login | Dialihkan otomatis ke `?page=home` |
| TC-AC-03 | Happy Path | Submit form register dengan data valid | User tersimpan, dialihkan ke `?page=login` dengan notifikasi sukses |
| TC-AC-04 | Validasi | Submit register dengan email yang sudah ada | Form register muncul kembali dengan pesan "Email sudah terdaftar" |
| TC-AC-05 | Happy Path | Submit login dengan kredensial cocok | Session `user` terisi, dialihkan ke `?page=home` |
| TC-AC-06 | Keamanan | Submit login dengan password salah | Gagal login, session tidak dibuat, muncul pesan error kredensial tidak cocok |
| TC-AC-07 | Happy Path | Mengakses `?page=logout` | Session hancur, dialihkan ke halaman login |
