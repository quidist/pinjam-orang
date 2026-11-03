# PRD: payments.model.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi data access layer transaksi pembayaran (*Payments*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola rekaman transaksi pembayaran untuk booking berbayar (*Paid Participation - Fase 2 Roadmap*), pencatatan metode pembayaran (QRIS, Bank Transfer, E-Wallet), referensi transaksi, dan verifikasi status pembayaran.
- **Letak File Target:** `app/model/payments.model.php`
- **Tabel Basis Data Terkait:** `payments`, berelasi 1-to-1 dengan tabel `bookings`.
- **Ketergantungan (Dependencies):** PDO instance (`$pdo`) dari `app/config/database.php`.

---

## 2. Struktur Data Tabel `payments`
Berdasarkan `database.sql`:
- `id` (BIGINT UNSIGNED, Primary Key, Auto Increment)
- `booking_id` (BIGINT UNSIGNED, UNIQUE, FK -> `bookings.id`)
- `amount` (DECIMAL(12, 2), NOT NULL)
- `payment_method` (VARCHAR(50), NULL)
- `payment_status` (ENUM('PENDING', 'PAID', 'FAILED', 'EXPIRED', 'REFUNDED'), DEFAULT 'PENDING')
- `transaction_ref` (VARCHAR(100), UNIQUE, NULL)
- `paid_at` (TIMESTAMP, NULL)
- `created_at` (TIMESTAMP)
- `updated_at` (TIMESTAMP)

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `createPayment(PDO $pdo, array $data): int|false`
- **Kegunaan:** Membuat baris transaksi pembayaran baru saat peserta melakukan booking penawaran berbayar (`price > 0`).
- **Parameter:**
  - `$pdo`: Koneksi PDO aktif.
  - `$data`: Array berisi `booking_id`, `amount`, `payment_method`, `transaction_ref`, `payment_status`.
- **Nilai Kembalian:** ID payment yang berhasil dibuat (int) atau `false`.

### 3.2. `getPaymentByBookingId(PDO $pdo, int $bookingId): array|false`
- **Kegunaan:** Mengambil informasi detail pembayaran untuk reservasi tertentu.
- **Parameter:** `$bookingId` (ID booking terkait).
- **Nilai Kembalian:** Array asosiatif pembayaran atau `false`.

### 3.3. `getPaymentByRef(PDO $pdo, string $transactionRef): array|false`
- **Kegunaan:** Mengambil data pembayaran berdasarkan nomor referensi transaksi unik (berguna untuk webhook payment gateway atau pencarian invoice).
- **Nilai Kembalian:** Array asosiatif pembayaran atau `false`.

### 3.4. `markAsPaid(PDO $pdo, int $paymentId, ?string $paidAt = null): bool`
- **Kegunaan:** Memperbarui status pembayaran menjadi `PAID` dan mencatat stempel waktu pembayaran (`paid_at`).
- **Parameter:** `$paymentId`, opsional `$paidAt` (default: `NOW()`).
- **Nilai Kembalian:** `true` jika berhasil, `false` jika gagal.
- **Efek Samping Bisnis:** Status booking terkait harus otomatis diperbarui menjadi `CONFIRMED`.

### 3.5. `updatePaymentStatus(PDO $pdo, int $paymentId, string $status): bool`
- **Kegunaan:** Mengubah status pembayaran (misal: `FAILED`, `EXPIRED`, `REFUNDED`).
- **Parameter:** `$paymentId`, `$status` baru.
- **Nilai Kembalian:** `true` jika berhasil.

---

## 4. Checklist Implementasi
- [ ] Menjaga constraint 1-to-1 antara `payments` dan `bookings` (`booking_id` bersifat UNIQUE).
- [ ] Kolom `amount` dicatat secara akurat sesuai total harga booking (`DECIMAL(12, 2)`).
- [ ] Penggunaan database transaction ketika mengubah status pembayaran dan mengonfirmasi booking.
- [ ] Validasi nilai status pembayaran sesuai ENUM.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-PAY-01 | Happy Path | `createPayment` dengan nilai amount valid dan booking_id terdaftar | Mengembalikan ID payment numerik |
| TC-PAY-02 | Constraint | `createPayment` kedua kalinya untuk booking_id yang sama | Gagal / mengembalikan `false` (constraint UNIQUE) |
| TC-PAY-03 | Happy Path | `markAsPaid` pada payment yang berstatus PENDING | Status berubah menjadi 'PAID' dan `paid_at` terisi timestamp |
| TC-PAY-04 | Happy Path | `getPaymentByRef` dengan nomor referensi unik | Mengembalikan data transaksi yang tepat |
| TC-PAY-05 | Edge Case | `getPaymentByRef` dengan nomor referensi yang tidak terdaftar | Mengembalikan `false` |

