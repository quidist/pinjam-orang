# PRD: users.model.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi data access layer pengguna (`users`) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola seluruh operasi database (CRUD) yang berkaitan dengan tabel `users`, termasuk autentikasi data, profil pengguna, skor reputasi (*Trust & Karma Score*), serta status verifikasi.
- **Letak File Target:** `app/model/users.model.php`
- **Tabel Basis Data Terkait:** `users`
- **Ketergantungan (Dependencies):** PDO instance (`$pdo`) dari `app/config/database.php`.

---

## 2. Struktur Data Tabel `users`
Berdasarkan `database.sql`:
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `name` (VARCHAR(100), NOT NULL)
- `email` (VARCHAR(150), NOT NULL, UNIQUE)
- `password_hash` (VARCHAR(255), NOT NULL)
- `phone` (VARCHAR(30), NULL)
- `bio` (TEXT, NULL)
- `avatar_url` (VARCHAR(255), NULL)
- `trust_score` (INT, DEFAULT 100)
- `is_verified` (BOOLEAN, DEFAULT FALSE)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `createUser(PDO $pdo, array $data): int|false`
- **Kegunaan:** Menyimpan data pengguna baru saat registrasi akun.
- **Parameter:**
  - `$pdo`: Koneksi database aktif.
  - `$data`: Array asosiatif berisi `name`, `email`, `password_hash`, opsional: `phone`, `bio`.
- **Nilai Kembalian:** `id` pengguna yang baru dibuat (integer) jika berhasil, atau `false` jika gagal.
- **Aturan Bisnis:** Nilai `trust_score` default 100, `is_verified` default false.

### 3.2. `getUserByEmail(PDO $pdo, string $email): array|false`
- **Kegunaan:** Mengambil data pengguna berdasarkan alamat email, digunakan terutama pada proses login/autentikasi.
- **Parameter:** `$email` (string email yang sudah disanitasi).
- **Nilai Kembalian:** Array data pengguna lengkap (termasuk `password_hash`) jika ditemukan, atau `false` jika tidak ditemukan.

### 3.3. `getUserById(PDO $pdo, int $id, bool $safe = true): array|false`
- **Kegunaan:** Mengambil data profil pengguna berdasarkan user ID.
- **Parameter:**
  - `$id`: ID pengguna.
  - `$safe`: Jika `true`, kolom sensitif seperti `password_hash` tidak diikutsertakan dalam hasil query (`SELECT id, name, email, phone, bio, avatar_url, trust_score, is_verified, created_at`).
- **Nilai Kembalian:** Array asosiatif profil pengguna atau `false`.

### 3.4. `updateProfile(PDO $pdo, int $id, array $data): bool`
- **Kegunaan:** Memperbarui informasi profil pengguna seperti nama, bio, avatar, dan nomor telepon.
- **Parameter:**
  - `$id`: ID pengguna yang login.
  - `$data`: Array kolom yang diperbolehkan diubah (`name`, `phone`, `bio`, `avatar_url`).
- **Nilai Kembalian:** `true` jika berhasil diupdate, `false` jika gagal.

### 3.5. `updatePassword(PDO $pdo, int $id, string $newPasswordHash): bool`
- **Kegunaan:** Mengubah hash password pengguna (fitur ganti password).
- **Parameter:** `$id` pengguna dan string hash password baru.
- **Nilai Kembalian:** `true` jika berhasil diupdate, `false` jika gagal.

### 3.6. `updateTrustScore(PDO $pdo, int $id, int $pointDelta): bool`
- **Kegunaan:** Menambah atau mengurangi poin karma/kepercayaan pengguna berdasarkan perilaku sesi (anti-ghosting).
  - Tepat waktu: `+5`
  - No-show / ghosting: `-25`
- **Parameter:**
  - `$id`: ID pengguna.
  - `$pointDelta`: Perubahan poin (positif atau negatif).
- **Nilai Kembalian:** `true` jika berhasil.
- **Aturan Bisnis:** Pastikan nilai `trust_score` tidak melebihi batas wajar (misal max 100 atau tanpa batas atas, minimum 0).

### 3.7. `isEmailExists(PDO $pdo, string $email, ?int $excludeUserId = null): bool`
- **Kegunaan:** Memeriksa apakah email sudah terdaftar sebelumnya untuk mencegah duplikasi akun saat registrasi atau pembaruan email.
- **Parameter:** `$email`, dan opsional `$excludeUserId` (jika user sedang update profil sendiri).
- **Nilai Kembalian:** `true` jika email sudah ada, `false` jika belum.

---

## 4. Checklist Implementasi
- [ ] Menggunakan Prepared Statement (`$pdo->prepare()`) untuk seluruh query SQL (mencegah SQL Injection).
- [ ] Kolom sensitif (`password_hash`) tidak pernah diekspos sembarangan ke view/client publik.
- [ ] Penanganan error PDO menggunakan try-catch atau membiarkan exception ditangkap handler terpusat.
- [ ] Validasi tipe data parameter secara ketat (type-hinting PHP 8+).
- [ ] Mengembalikan nilai boolean atau array yang konsisten.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-USR-01 | Happy Path | `createUser` dengan data valid (nama, email unik, hash password) | Mengembalikan ID numerik `> 0`, record tersimpan di tabel |
| TC-USR-02 | Boundary | `createUser` dengan email yang sudah ada (duplikat) | Mengembalikan `false` atau throw PDOException duplicate entry |
| TC-USR-03 | Happy Path | `getUserByEmail` dengan email yang terdaftar | Mengembalikan array data pengguna sesuai |
| TC-USR-04 | Edge Case | `getUserByEmail` dengan format email valid tapi belum terdaftar | Mengembalikan `false` |
| TC-USR-05 | Keamanan | `getUserById` dengan `$safe = true` | Kolom `password_hash` tidak ada di array output |
| TC-USR-06 | Happy Path | `updateProfile` memperbarui nama dan bio | Data di database terupdate, mengembalikan `true` |
| TC-USR-07 | Happy Path | `updateTrustScore` penalti ghosting (`-25`) | Nilai `trust_score` berkurang 25 poin dari nilai sebelumnya |
| TC-USR-08 | Boundary | `updateTrustScore` jika skor awal 10 dikurangi 25 | Nilai skor tidak boleh minus (mentok di 0) |

