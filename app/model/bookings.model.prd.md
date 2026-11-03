# PRD: bookings.model.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi data access layer reservasi slot waktu (*Time Booking*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola reservasi waktu antara Participant dan Listing milik Host, mencakup pembuatan booking, validasi kapasitas, konfirmasi persetujuan batasan (*boundaries*), pengecekan anti-double-booking, dan riwayat status booking.
- **Letak File Target:** `app/model/bookings.model.php`
- **Tabel Basis Data Terkait:** `bookings`, berelasi dengan `listings` dan `users`.
- **Ketergantungan (Dependencies):** PDO instance (`$pdo`) dari `app/config/database.php`.

---

## 2. Struktur Data Tabel `bookings`
Berdasarkan `database.sql`:
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `listing_id` (BIGINT UNSIGNED, FK -> `listings.id`)
- `participant_id` (BIGINT UNSIGNED, FK -> `users.id`)
- `slot_count` (INT, DEFAULT 1)
- `total_price` (DECIMAL(12, 2), DEFAULT 0.00)
- `status` (ENUM('PENDING', 'CONFIRMED', 'REJECTED', 'CANCELLED', 'COMPLETED'), DEFAULT 'PENDING')
- `agreed_to_boundaries` (BOOLEAN, DEFAULT TRUE)
- `notes` (TEXT, NULL)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)
- **Constraint Unik:** `(listing_id, participant_id)` &rarr; 1 participant tidak boleh mendaftar ganda di listing yang sama.

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `createBooking(PDO $pdo, array $data): int|false`
- **Kegunaan:** Membuat data reservasi baru saat Participant mengajukan booking slot.
- **Parameter:**
  - `$pdo`: Koneksi PDO aktif.
  - `$data`: Array berisi `listing_id`, `participant_id`, `slot_count`, `total_price`, `status`, `agreed_to_boundaries`, `notes`.
- **Nilai Kembalian:** ID booking (int) atau `false`.
- **Aturan Bisnis:** Nilai `agreed_to_boundaries` harus `true`. Jika approval listing 'AUTOMATIC' dan penawaran FREE, status langsung `CONFIRMED`. Jika 'MANUAL', status awal `PENDING`.

### 3.2. `getBookingById(PDO $pdo, int $id): array|false`
- **Kegunaan:** Mengambil data spesifik booking berdasarkan ID, di-JOIN dengan data `listings` dan `users` (host & participant).
- **Nilai Kembalian:** Array asosiatif lengkap informasi reservasi, atau `false` jika tidak ditemukan.

### 3.3. `hasUserBookedListing(PDO $pdo, int $listingId, int $participantId): bool`
- **Kegunaan:** Memeriksa apakah user sudah pernah mengajukan booking pada listing tersebut (mencegah duplikasi).
- **Nilai Kembalian:** `true` jika sudah pernah booking (dengan status aktif), `false` jika belum.

### 3.4. `getBookingsByParticipant(PDO $pdo, int $participantId): array`
- **Kegunaan:** Mengambil daftar riwayat booking yang diajukan oleh Participant (untuk halaman "Pengalaman Saya / My Bookings").
- **Parameter:** `$participantId` (user ID).
- **Nilai Kembalian:** Array daftar booking disertai detail judul listing, tanggal, jam, tipe sesi, dan lokasi.

### 3.5. `getBookingsByListing(PDO $pdo, int $listingId): array`
- **Kegunaan:** Menampilkan seluruh pendaftar/peserta pada suatu listing (untuk ditinjau oleh Host).
- **Nilai Kembalian:** Array pendaftar disertai profil participant (`name`, `avatar_url`, `trust_score`, `notes`).

### 3.6. `updateBookingStatus(PDO $pdo, int $id, string $newStatus): bool`
- **Kegunaan:** Mengubah status booking (`CONFIRMED`, `REJECTED`, `CANCELLED`, `COMPLETED`).
- **Parameter:** `$id` booking, `$newStatus` (sesuai ENUM).
- **Nilai Kembalian:** `true` jika status berhasil diubah.

### 3.7. `cancelBooking(PDO $pdo, int $id, int $userId): bool`
- **Kegunaan:** Membatalkan booking oleh Participant atau Host.
- **Parameter:** `$id` booking dan `$userId` yang meminta pembatalan (untuk otorisasi).
- **Aturan Bisnis:** Pembatalan hanya bisa dilakukan jika status masih `PENDING` atau `CONFIRMED`.

---

## 4. Checklist Implementasi
- [ ] Validasi persetujuan batas interaksi (`agreed_to_boundaries` wajib `true`).
- [ ] Pengecekan constraint unik `(listing_id, participant_id)` untuk mencegah *double booking*.
- [ ] Memastikan integritas data menggunakan transaksi database (`beginTransaction`, `commit`, `rollBack`) saat membuat booking dan mengurangi kuota.
- [ ] Penggunaan prepared statement pada seluruh query.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-BOK-01 | Happy Path | `createBooking` pada listing kuota tersedia dan boundaries disetujui | Record tersimpan, mengembalikan ID booking |
| TC-BOK-02 | Validasi | `createBooking` dengan `agreed_to_boundaries = false` | Ditolak oleh validasi, tidak tersimpan |
| TC-BOK-03 | Constraint | `createBooking` untuk user yang sudah mendaftar di listing yang sama | Mengembalikan `false` / duplicate constraint error |
| TC-BOK-04 | Happy Path | `getBookingsByParticipant` mengambil riwayat peserta | Mengembalikan array booking beserta detail listing |
| TC-BOK-05 | Happy Path | `updateBookingStatus` mengubah PENDING menjadi CONFIRMED oleh Host | Status di database menjadi 'CONFIRMED' |
| TC-BOK-06 | Otorisasi | `cancelBooking` dipanggil oleh user yang bukan participant maupun host | Mengembalikan `false`, status tidak berubah |

