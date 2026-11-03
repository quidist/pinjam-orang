# PRD: users.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller manajemen profil pengguna (*User Profile & Trust Dashboard*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola tampilan profil pengguna, formulir edit profil (nama, bio, foto avatar, nomor kontak), tampilan Trust & Karma Score, serta status verifikasi akun.
- **Letak File Target:** `app/controller/users.controller.php`
- **Model Terkait:** `app/model/users.model.php` dan `app/model/reviews.model.php`
- **Ketergantungan:** Helper otentikasi dari `auth.controller.php`.

---

## 2. Alur Kerja & Endpoints / Aksi

| Aksi | Method HTTP | Deskripsi | Input Utama | Output / Redirect |
|---|---|---|---|---|
| `showProfile` | GET | Menampilkan halaman profil publik seorang user | `id` (via query param / path) | Render view `users/profile.php` |
| `showMyProfile` | GET | Menampilkan dashboard profil akun sendiri | Session user | Render view `users/my_profile.php` |
| `editProfile` | POST | Memproses pembaruan data profil akun | `name`, `bio`, `phone`, file avatar | Update database, redirect dengan flash message |
| `changePassword` | POST | Memproses perubahan password akun | `old_password`, `new_password`, `confirm` | Validasi password lama, simpan hash baru |
| `showTrustScore` | GET | Halaman edukasi & rincian skor karma user | Session user | Menampilkan skor, tier reputasi, & log kepatuhan |

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `userShowProfile(PDO $pdo, int $targetUserId): array`
- **Kegunaan:** Menyiapkan data profil publik user tertentu agar dapat dilihat oleh pengguna lain sebelum memutuskan meminjam waktu.
- **Logika:**
  - Ambil data profil dengan `getUserById($pdo, $targetUserId, safe: true)`.
  - Ambil rata-rata rating review dengan `getUserAverageRating()`.
  - Ambil ulasan terbaru yang diterima oleh pengguna tersebut.
- **Proteksi Privasi:** Nomor telepon tidak boleh disertakan pada tampilan publik (sesuai *Privacy-First Communication rule*).
- **Nilai Kembalian:** Array asosiatif data profil publik atau pesan 404 jika user tidak ditemukan.

### 3.2. `userUpdateProfile(PDO $pdo, int $currentUserId, array $postData, ?array $fileData): array`
- **Kegunaan:** Memvalidasi dan menyimpan perubahan data diri pengguna.
- **Validasi:**
  - `name`: Wajib, 3-100 karakter.
  - `bio`: Opsional, maksimal 500 karakter.
  - `phone`: Format nomor telepon valid.
  - Avatar: Jika ada unggahan file, validasi ekstensi (`jpg`, `jpeg`, `png`, `webp`) dan ukuran maksimum (misal 2MB).
- **Nilai Kembalian:** `['success' => bool, 'message' => string]`.

### 3.3. `userChangePassword(PDO $pdo, int $currentUserId, array $postData): array`
- **Kegunaan:** Mengubah kata sandi user secara mandiri.
- **Validasi:**
  - Memverifikasi kata sandi lama cocok dengan yang tersimpan di database.
  - Kata sandi baru minimal 6 karakter dan tidak sama dengan kata sandi lama.
  - Konfirmasi kata sandi baru harus cocok.
- **Nilai Kembalian:** `['success' => bool, 'message' => string]`.

### 3.4. `checkUserTrustEligibility(PDO $pdo, int $userId): array`
- **Kegunaan:** Memeriksa apakah Trust Score pengguna memenuhi syarat minimum untuk membuat penawaran atau booking (sesuai *Business Rule #9*: Trust Score < 70 terkena pembekuan sementara / cooldown).
- **Nilai Kembalian:** `['eligible' => bool, 'score' => int, 'reason' => ?string]`.

---

## 4. Checklist Implementasi
- [ ] Otorisasi ketat: User hanya boleh mengedit profil dan password miliknya sendiri (`$currentUserId == $_SESSION['user']['id']`).
- [ ] *Privacy Rule*: Nomor kontak pribadi tidak diekspos pada profil publik sebelum sesi terkonfirmasi.
- [ ] Validasi keamanan unggahan file avatar (tipe MIME, ekstensi, batas ukuran) jika fitur upload diaktifkan.
- [ ] Menerapkan aturan batasan Trust Score (< 70 status *Restricted*).

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-UPR-01 | Happy Path | Melihat profil publik user terdaftar | Profil tampil lengkap tanpa nomor telepon sensitif |
| TC-UPR-02 | Edge Case | Melihat profil publik user yang tidak ada (ID salah) | Menampilkan respon/halaman 404 Not Found |
| TC-UPR-03 | Happy Path | Update profil diri dengan data baru | Data tersimpan, flash message "Profil berhasil diperbarui" |
| TC-UPR-04 | Otorisasi | Mencoba mengubah password tanpa mengetahui password lama yang benar | Ditolak, error "Kata sandi lama tidak cocok" |
| TC-UPR-05 | Aturan Bisnis | Cek kelayakan user dengan Trust Score 65 | `eligible` bernilai `false` (dibekukan karena skor < 70) |
| TC-UPR-06 | Aturan Bisnis | Cek kelayakan user dengan Trust Score 95 | `eligible` bernilai `true` (memenuhi syarat) |

