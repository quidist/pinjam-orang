# PRD: users.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller profil pengguna dan reputasi (*User Profile & Trust Dashboard*) pada sistem **PINJAMORANG**.

---

## 1. Identitas Class & Dependencies
- **Nama File Target:** `app/controller/users.controller.php`
- **Nama Class:** `UsersController`
- **Tanggung Jawab:** Menampilkan profil publik pengguna (dengan proteksi privasi nomor kontak), menampilkan dashboard akun pribadi, memproses pembaruan biodata/nama, dan menangani penggantian kata sandi.
- **Dependencies (Ketergantungan):**
  - Objek database: `PDO $pdo`
  - Model: `UserModel` dari `app/model/users.model.php`
  - Model Review: `ReviewModel` dari `app/model/reviews.model.php`

### Kerangka Dasar Class:
```php
class UsersController {
    private PDO $pdo;
    private UserModel $userModel;
    private ReviewModel $reviewModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->userModel = new UserModel($pdo);
        $this->reviewModel = new ReviewModel($pdo);
    }
    // ... method-method di bawah ini
}
```

---

## 2. Tabel Pemetaan Routing (`app/index.php`)

| URL Route (`?page=...`) | HTTP Method | Method Controller yang Dipanggil | Proteksi Akses |
|---|---|---|---|
| `?page=profile&id=X` | `GET` | `UsersController::showProfile()` | Publik |
| `?page=my-profile` | `GET` | `UsersController::showMyProfile()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=update-profile` | `POST` | `UsersController::updateProfile()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=change-password` | `POST` | `UsersController::changePassword()` | Wajib Login (`AuthController::requireAuth`) |

---

## 3. Spesifikasi Lengkap Fungsi / Method

### 3.1. `public function showProfile(): void`
- **Kegunaan / Untuk Apa:** Menampilkan profil reputasi publik seorang pengguna (Host/Peserta) kepada publik, menampilkan meteran Trust Score dan daftar ulasan yang pernah diterima.
- **Input yang Dibaca:** `$_GET['id']` (integer user ID).
- **Logika Alur:**
  1. Ambil `$id = (int)($_GET['id'] ?? 0)`.
  2. Panggil Model dalam mode aman:
     `$user = $this->userModel->getUserById($id, safe: true);`
  3. Jika user tidak ditemukan: Tampilkan halaman 404 Not Found.
  4. Ambil statistik review:
     - `$reviews = $this->reviewModel->getReviewsByUserId($id);`
     - `$avgRating = $this->reviewModel->getUserAverageRating($id);`
  5. **Proteksi Privasi:** Pastikan nomor telepon tidak disertakan ke dalam array data view.
  6. Rakit komponen View:
     ```php
     $pageTitle = "Profil " . htmlspecialchars($user['name']) . " - PINJAMORANG";
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/users/profile.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.2. `public function showMyProfile(): void`
- **Kegunaan / Untuk Apa:** Menampilkan halaman pengaturan akun milik pengguna yang sedang login, berisi formulir edit nama, bio, nomor telepon privat, dan form ganti kata sandi.
- **Logika Alur:**
  1. Ambil ID dari sesi login: `$currentUserId = $_SESSION['user']['id'];`
  2. Panggil Model: `$user = $this->userModel->getUserById($currentUserId, safe: true);`
  3. Siapkan judul halaman: `$pageTitle = "Pengaturan Profil Saya - PINJAMORANG"`.
  4. Rakit komponen View:
     ```php
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/users/my_profile.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.3. `public function updateProfile(): void`
- **Kegunaan / Untuk Apa:** Memvalidasi dan menyimpan pembaruan informasi profil akun sendiri.
- **Input yang Dibaca:** `$_POST['name']`, `$_POST['phone']`, `$_POST['bio']`.
- **Logika Alur:**
  1. Ambil `$currentUserId = $_SESSION['user']['id'];`
  2. Validasi:
     - `name`: Wajib, 3-100 karakter.
     - `bio`: Maksimal 500 karakter.
     - `phone`: Format nomor telepon valid.
  3. Simpan perubahan ke database via `$this->userModel->updateProfile($currentUserId, $data)`.
  4. Perbarui nama di sesi login jika berubah: `$_SESSION['user']['name'] = $name;`
  5. Pasang flash alert sukses: `$_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Profil berhasil diperbarui!'];`
  6. Redirect kembali ke `?page=my-profile`.

### 3.4. `public function changePassword(): void`
- **Kegunaan / Untuk Apa:** Mengubah kata sandi akun secara mandiri dengan memverifikasi kecocokan kata sandi lama terlebih dahulu.
- **Input yang Dibaca:** `$_POST['old_password']`, `$_POST['new_password']`, `$_POST['confirm_new_password']`.
- **Logika Alur:**
  1. Ambil `$currentUserId = $_SESSION['user']['id'];`
  2. Ambil data user lengkap dengan password hash:
     `$user = $this->userModel->getUserById($currentUserId, safe: false);`
  3. Verifikasi Kata Sandi Lama:
     `if (!password_verify($oldPassword, $user['password_hash']))`
     &rarr; Gagal: kembalikan ke form profil dengan flash error *"Kata sandi lama tidak cocok."*
  4. Validasi Kata Sandi Baru:
     - Minimal 6 karakter.
     - Konfirmasi sandi baru harus cocok.
     - Tidak boleh sama dengan sandi lama.
  5. Enkripsi dan Simpan:
     - `$newHash = password_hash($newPassword, PASSWORD_BCRYPT);`
     - Panggil `$this->userModel->updatePassword($currentUserId, $newHash);`
  6. Pasang flash alert: `$_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Kata sandi berhasil diubah!'];`
  7. Redirect kembali ke `?page=my-profile`.

---

## 4. Checklist Implementasi
- [ ] Profil publik tidak pernah mengekspos nomor telepon ke view publik.
- [ ] Pemanggilan `safe: false` pada model hanya dilakukan khusus pada fungsi `changePassword()`.
- [ ] Pengguna hanya memiliki wewenang untuk mengedit profil akun miliknya sendiri.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-UC-01 | Profil Publik | Buka `?page=profile&id=1` | Menampilkan nama, bio, trust score, dan ulasan tanpa nomor telepon |
| TC-UC-02 | Edge Case | Buka `?page=profile&id=99999` (tidak ada) | Menampilkan respon/halaman 404 Not Found |
| TC-UC-03 | Edit Profil | Submit perubahan nama dan bio pada akun sendiri | Data tersimpan, session nama terupdate, flash alert muncul |
| TC-UC-04 | Ganti Password | Submit ganti password dengan kata sandi lama yang salah | Gagal, muncul pesan "Kata sandi lama tidak cocok" |
| TC-UC-05 | Ganti Password | Submit ganti password dengan data valid | Kata sandi terupdate dengan hash baru |
