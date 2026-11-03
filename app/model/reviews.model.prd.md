# PRD: reviews.model.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi data access layer ulasan & reputasi (*Reviews & Reputation - Fase 3 Roadmap*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola feedback, rating (skala 1-5), dan komentar pasca-pengalaman yang diselesaikan, yang berkontribusi langsung pada perhitungan reputasi dan Trust Score pengguna.
- **Letak File Target:** `app/model/reviews.model.php`
- **Tabel Basis Data Terkait:** `reviews`, berelasi dengan `bookings` dan `users`.
- **Ketergantungan (Dependencies):** PDO instance (`$pdo`) dari `app/config/database.php`.

---

## 2. Struktur Data Tabel `reviews`
Berdasarkan `database.sql`:
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `booking_id` (BIGINT UNSIGNED, UNIQUE, FK -> `bookings.id`)
- `reviewer_id` (BIGINT UNSIGNED, FK -> `users.id`)
- `reviewed_user_id` (BIGINT UNSIGNED, FK -> `users.id`)
- `rating` (TINYINT, CHECK (rating >= 1 AND rating <= 5))
- `comment` (TEXT, NULL)
- `created_at` (TIMESTAMP)

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `createReview(PDO $pdo, array $data): int|false`
- **Kegunaan:** Menyimpan review baru setelah sesi booking dinyatakan `COMPLETED`.
- **Parameter:**
  - `$pdo`: Koneksi PDO aktif.
  - `$data`: Array berisi `booking_id`, `reviewer_id`, `reviewed_user_id`, `rating`, `comment`.
- **Nilai Kembalian:** ID review (int) atau `false`.
- **Aturan Bisnis:** 
  - Rating harus bernilai antara 1 sampai 5.
  - Booking harus berstatus `COMPLETED`.
  - Hanya boleh ada 1 review per booking (`booking_id` UNIQUE).

### 3.2. `getReviewsByUserId(PDO $pdo, int $userId): array`
- **Kegunaan:** Menampilkan seluruh review yang diterima oleh seorang pengguna (baik saat berperan sebagai Host maupun Participant).
- **Nilai Kembalian:** Array ulasan disertai profil reviewer (`users.name`, `users.avatar_url`).

### 3.3. `getUserAverageRating(PDO $pdo, int $userId): float`
- **Kegunaan:** Menghitung rata-rata bintang/rating yang didapatkan pengguna.
- **Nilai Kembalian:** Float rata-rata rating (misal `4.85`) atau `0.0` jika belum ada review.

### 3.4. `hasReviewedBooking(PDO $pdo, int $bookingId): bool`
- **Kegunaan:** Memeriksa apakah suatu booking sudah diberikan review atau belum.
- **Nilai Kembalian:** `true` jika sudah direview, `false` jika belum.

---

## 4. Checklist Implementasi
- [ ] Validasi batasan rating 1 hingga 5 (range integer).
- [ ] Menjaga constraint 1 ulasan per booking (`booking_id` UNIQUE).
- [ ] Menjaga privasi: Review hanya bisa dibuat oleh pihak yang terlibat di dalam booking tersebut.
- [ ] Sanitasi teks komentar untuk mencegah XSS / script injection.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-REV-01 | Happy Path | `createReview` dengan rating 5 dan komentar valid pada booking COMPLETED | Mengembalikan ID review numerik |
| TC-REV-02 | Boundary | `createReview` dengan rating 0 atau 6 (di luar range 1-5) | Gagal / validasi melempar error |
| TC-REV-03 | Constraint | `createReview` kedua kali untuk booking yang sama | Gagal / mengembalikan `false` (constraint UNIQUE) |
| TC-REV-04 | Happy Path | `getUserAverageRating` untuk user dengan dua review (rating 4 dan 5) | Mengembalikan nilai float `4.5` |
| TC-REV-05 | Edge Case | `getUserAverageRating` untuk user baru tanpa review | Mengembalikan float `0.0` |

