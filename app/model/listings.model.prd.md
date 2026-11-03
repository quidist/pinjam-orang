# PRD: listings.model.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi data access layer penawaran waktu (*Time Listing*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola seluruh operasi database untuk tabel `listings`, meliputi pembuatan listing waktu oleh Host, pencarian & filter katalog publik, pengecekan ketersediaan kuota slot, hingga perubahan status penawaran.
- **Letak File Target:** `app/model/listings.model.php`
- **Tabel Basis Data Terkait:** `listings`, berelasi dengan `users` (host), dan `bookings`.
- **Ketergantungan (Dependencies):** PDO instance (`$pdo`) dari `app/config/database.php`.

---

## 2. Struktur Data Tabel `listings`
Berdasarkan `database.sql`:
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `host_id` (BIGINT UNSIGNED, FK -> `users.id`)
- `title` (VARCHAR(255), NOT NULL)
- `description` (TEXT, NOT NULL)
- `session_type` (ENUM('ONLINE', 'OFFLINE'), NOT NULL DEFAULT 'ONLINE')
- `listing_date` (DATE, NOT NULL)
- `start_time` (TIME, NOT NULL)
- `end_time` (TIME, NOT NULL)
- `duration_minutes` (INT, NOT NULL)
- `capacity` (INT, NOT NULL)
- `price` (DECIMAL(12, 2), DEFAULT 0.00)
- `location` (VARCHAR(255), NOT NULL)
- `approval_mode` (ENUM('AUTOMATIC', 'MANUAL'), DEFAULT 'AUTOMATIC')
- `boundaries` (TEXT, NULL)
- `status` (ENUM('DRAFT', 'ACTIVE', 'COMPLETED', 'CANCELLED'), DEFAULT 'ACTIVE')
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `createListing(PDO $pdo, array $data): int|false`
- **Kegunaan:** Menyimpan data penawaran waktu baru yang dibuat oleh Host.
- **Parameter:**
  - `$pdo`: Koneksi PDO aktif.
  - `$data`: Array asosiatif berisi `host_id`, `title`, `description`, `session_type`, `listing_date`, `start_time`, `end_time`, `duration_minutes`, `capacity`, `price`, `location`, `approval_mode`, `boundaries`, `status`.
- **Nilai Kembalian:** ID listing yang berhasil dibuat (int) atau `false`.

### 3.2. `getActiveListings(PDO $pdo, array $filters = [], int $limit = 20, int $offset = 0): array`
- **Kegunaan:** Menampilkan katalog penawaran aktif (`status = 'ACTIVE'`) yang tanggal dan jamnya belum terlewat, mendukung filter pencarian.
- **Parameter:**
  - `$filters`: Array opsional:
    - `session_type`: 'ONLINE' atau 'OFFLINE'
    - `is_free`: bool (jika true: `price = 0`)
    - `date`: tanggal spesifik
    - `keyword`: pencarian substring pada `title` atau `description`
  - `$limit` & `$offset`: Untuk pagination.
- **Nilai Kembalian:** Array daftar listing lengkap beserta data Host (`users.name`, `users.avatar_url`, `users.trust_score`).

### 3.3. `getListingById(PDO $pdo, int $id): array|false`
- **Kegunaan:** Mengambil detail lengkap suatu listing berdasarkan ID-nya, termasuk profil Host pemilik listing.
- **Parameter:** `$id` listing.
- **Nilai Kembalian:** Array asosiatif detail listing dan host, atau `false` jika tidak ditemukan.

### 3.4. `getListingsByHost(PDO $pdo, int $hostId): array`
- **Kegunaan:** Menampilkan seluruh listing milik seorang Host tertentu (untuk keperluan dashboard Host).
- **Parameter:** `$hostId` (user ID).
- **Nilai Kembalian:** Array daftar listing milik host yang bersangkutan.

### 3.5. `updateListing(PDO $pdo, int $id, int $hostId, array $data): bool`
- **Kegunaan:** Memperbarui data listing oleh pemiliknya (hanya jika status masih DRAFT/ACTIVE dan belum ada booking terkonfirmasi).
- **Parameter:** `$id` listing, `$hostId` (untuk memverifikasi hak milik), dan array `$data` perubahan.
- **Nilai Kembalian:** `true` jika berhasil diupdate, `false` jika gagal atau otorisasi tidak sesuai.

### 3.6. `updateListingStatus(PDO $pdo, int $id, string $status): bool`
- **Kegunaan:** Mengubah status listing (`ACTIVE`, `COMPLETED`, `CANCELLED`).
- **Parameter:** `$id` listing, `$status` baru (sesuai ENUM).
- **Nilai Kembalian:** `true` jika berhasil, `false` jika gagal.

### 3.7. `getRemainingCapacity(PDO $pdo, int $listingId): int`
- **Kegunaan:** Menghitung sisa kuota kapasitas slot yang masih tersedia pada listing tersebut.
- **Logika:** `capacity` dikurangi total slot yang sudah dibooking dengan status `CONFIRMED` (atau `PENDING` jika slot ditahan).
- **Nilai Kembalian:** Integer sisa slot (jika penuh maka `0`).

### 3.8. `hasOverlappingListing(PDO $pdo, int $hostId, string $date, string $startTime, string $endTime, ?int $excludeListingId = null): bool`
- **Kegunaan:** Validasi aturan bisnis: Host tidak boleh membuat penawaran dengan jadwal waktu yang bertabrakan dengan penawaran aktif miliknya yang lain.
- **Nilai Kembalian:** `true` jika ada jadwal bentrok, `false` jika jadwal aman.

---

## 4. Checklist Implementasi
- [ ] JOIN dengan tabel `users` untuk mengambil informasi Host (`name`, `avatar_url`, `trust_score`).
- [ ] Sanitasi seluruh filter input untuk mencegah SQL injection (gunakan prepared statements dinamis).
- [ ] Validasi nilai ENUM `session_type` ('ONLINE', 'OFFLINE') dan `approval_mode` ('AUTOMATIC', 'MANUAL').
- [ ] Implementasi validasi kapasitas agar sisa slot tidak pernah negatif.
- [ ] Pengecekan overlap jadwal sebelum listing baru disimpan.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-LST-01 | Happy Path | `createListing` dengan data lengkap dan valid | Mengembalikan ID listing `> 0` |
| TC-LST-02 | Aturan Bisnis | `hasOverlappingListing` ketika Host sudah memiliki listing pada jam 14:00 - 15:00 di tanggal yang sama | Mengembalikan `true` (terdeteksi bentrok) |
| TC-LST-03 | Happy Path | `getActiveListings` dengan filter `session_type = 'ONLINE'` | Hanya mengembalikan listing tipe 'ONLINE' dengan status 'ACTIVE' |
| TC-LST-04 | Happy Path | `getRemainingCapacity` pada listing kapasitas 5 yang sudah terkonfirmasi 3 slot | Mengembalikan integer `2` |
| TC-LST-05 | Edge Case | `getRemainingCapacity` jika kuota sudah habis terisi | Mengembalikan integer `0` |
| TC-LST-06 | Otorisasi | `updateListing` dipanggil oleh user yang bukan pemilik listing (`host_id` beda) | Tidak ada baris yang berubah, mengembalikan `false` |
| TC-LST-07 | Happy Path | `updateListingStatus` mengubah status menjadi 'CANCELLED' | Status di tabel berubah, mengembalikan `true` |

