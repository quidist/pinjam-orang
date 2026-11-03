# PRD: payments.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller pembayaran transaksi (*Payments & Checkout - Fase 2 Roadmap*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola alur checkout penawaran berbayar, pembuatan tagihan/invoice pembayaran, simulasi verifikasi pembayaran (atau integrasi payment gateway), serta pembaruan otomatis status booking saat pembayaran berhasil dikonfirmasi.
- **Letak File Target:** `app/controller/payments.controller.php`
- **Model Terkait:** `app/model/payments.model.php` dan `app/model/bookings.model.php`.
- **Ketergantungan:** Guard `requireAuth()` dari `auth.controller.php`.

---

## 2. Alur Kerja & Endpoints / Aksi

| Aksi | Method HTTP | Deskripsi | Input Utama | Output / Redirect |
|---|---|---|---|---|
| `showCheckout` | GET | Menampilkan rincian tagihan booking yang perlu dibayar | `booking_id` | Render view `payments/checkout.php` |
| `processPayment` | POST | Memproses pembayaran (pilihan metode pembayaran) | `booking_id`, `payment_method` | Generate data payment, arahkan ke instruksi bayar |
| `confirmPayment` | POST | Konfirmasi penerimaan pembayaran (simulasi/webhook) | `transaction_ref` / `payment_id` | Update status menjadi `PAID`, ubah booking jadi `CONFIRMED` |
| `paymentStatus` | GET | Halaman bukti/status pembayaran | `payment_id` | Render view `payments/receipt.php` |

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `paymentCheckout(PDO $pdo, int $bookingId, int $currentUserId): array`
- **Kegunaan:** Menyiapkan data rincian pembayaran untuk booking berbayar yang sedang menunggu pembayaran.
- **Validasi:**
  - Booking harus berstatus `PENDING` dan total harga `> 0`.
  - User yang mengakses harus merupakan Participant yang memesan (`participant_id`).
- **Nilai Kembalian:** Array data booking, detail listing, dan total tagihan.

### 3.2. `paymentProcess(PDO $pdo, int $bookingId, int $currentUserId, string $paymentMethod): array`
- **Kegunaan:** Membuat baris transaksi pembayaran baru dan membuat kode referensi unik (misal: `INV-20261103-XXXX`).
- **Validasi:** Pastikan belum ada pembayaran aktif untuk booking ini (`getPaymentByBookingId`).
- **Nilai Kembalian:** `['success' => bool, 'payment_id' => int, 'reference' => string]`.

### 3.3. `paymentConfirm(PDO $pdo, int $paymentId, ?string $actor = 'system'): array`
- **Kegunaan:** Mengonfirmasi bahwa dana telah diterima.
- **Efek Transaksi (Database Transaction):**
  1. Ubah status payment menjadi `PAID` via `markAsPaid()`.
  2. Ubah status booking menjadi `CONFIRMED` via `updateBookingStatus()`.
- **Nilai Kembalian:** `['success' => bool, 'message' => string]`.

---

## 4. Checklist Implementasi
- [ ] Integritas transaksi: Selalu gunakan database transaction (`beginTransaction`, `commit`, `rollBack`) saat konfirmasi pembayaran agar status payment dan booking sinkron.
- [ ] Aturan Bisnis #5: Booking berbayar tidak boleh dianggap sah (*CONFIRMED*) sebelum status pembayaran terkonfirmasi *PAID*.
- [ ] Validasi kepemilikan invoice pembayaran.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-PYC-01 | Happy Path | Peserta membuka checkout untuk booking berbayar miliknya | Menampilkan rincian nominal dan instruksi bayar |
| TC-PYC-02 | Otorisasi | User lain mencoba melihat halaman checkout milik orang lain | Akses ditolak (403 Forbidden) |
| TC-PYC-03 | Happy Path | Konfirmasi pembayaran berhasil | Status payment menjadi 'PAID', status booking menjadi 'CONFIRMED' |
| TC-PYC-04 | Edge Case | Konfirmasi pembayaran untuk payment yang sudah berstatus PAID | Mengembalikan respon pembayaran sudah terproses sebelumnya |

