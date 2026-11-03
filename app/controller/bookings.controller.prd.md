# PRD: bookings.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller reservasi waktu (*Time Booking & Capacity Management*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola alur pemesanan waktu oleh Participant, verifikasi sisa kuota kapasitas, penegakan persetujuan batas interaksi (*Boundaries Agreement*), sistem persetujuan oleh Host (*Host Approval System*), pembatalan reservasi, dan daftar jadwal pengalaman pengguna.
- **Letak File Target:** `app/controller/bookings.controller.php`
- **Model Terkait:** `app/model/bookings.model.php`, `app/model/listings.model.php`, `app/model/users.model.php`, `app/model/payments.model.php`.
- **Ketergantungan:** Guard `requireAuth()` dari `auth.controller.php`.

---

## 2. Alur Kerja & Endpoints / Aksi

| Aksi | Method HTTP | Deskripsi | Input Utama | Output / Redirect |
|---|---|---|---|---|
| `book` | POST | Mengajukan booking slot pada listing tertentu | `listing_id`, `agreed_to_boundaries`, `notes` | Redirect ke invoice (jika berbayar) atau konfirmasi (jika free) |
| `myBookings` | GET | Menampilkan riwayat pengalaman yang dipesan participant | Session user | Render view `bookings/my_bookings.php` |
| `hostRequests` | GET | Menampilkan daftar permintaan booking masuk untuk Host | `listing_id` atau Host ID | Render view `bookings/host_requests.php` |
| `approve` | POST | Host menyetujui permintaan booking masuk | `booking_id` | Update status menjadi `CONFIRMED` |
| `reject` | POST | Host menolak permintaan booking masuk | `booking_id`, alasan | Update status menjadi `REJECTED` |
| `cancel` | POST | Membatalkan reservasi oleh Participant | `booking_id` | Update status menjadi `CANCELLED` |

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `bookingCreate(PDO $pdo, int $participantId, array $postData): array`
- **Kegunaan:** Memvalidasi pengajuan reservasi dan menyimpannya ke database.
- **Validasi Bisnis Wajib:**
  1. *Self-Booking Prevention:* Host tidak boleh memesan listing miliknya sendiri (`host_id != participantId`).
  2. *Trust Score Check:* Participant harus memiliki Trust Score &ge; 70.
  3. *Listing Status & Schedule Check:* Listing harus berstatus `ACTIVE` dan belum dimulai/kedaluwarsa.
  4. *Capacity Check:* Sisa kapasitas harus &ge; 1 (`getRemainingCapacity() > 0`).
  5. *Anti-Double-Booking:* Participant belum pernah mendaftar di listing ini (`hasUserBookedListing == false`).
  6. *Boundaries Agreement:* Kolom `agreed_to_boundaries` **wajib dicentang** (`true`). Booking ditolak jika participant tidak menyetujui batasan.
- **Penetapan Status:**
  - Jika listing `price == 0` (FREE):
    - Jika `approval_mode == 'AUTOMATIC'` &rarr; status langsung `CONFIRMED`.
    - Jika `approval_mode == 'MANUAL'` &rarr; status `PENDING`.
  - Jika listing `price > 0` (PAID) &rarr; status `PENDING` (menunggu pembayaran).
- **Nilai Kembalian:** `['success' => bool, 'booking_id' => ?int, 'requires_payment' => bool, 'errors' => array]`.

### 3.2. `bookingApprove(PDO $pdo, int $bookingId, int $currentUserId): array`
- **Kegunaan:** Menyetujui pendaftaran peserta oleh Host (untuk listing mode persetujuan manual).
- **Validasi:** User yang melakukan aksi wajib pemilik listing yang bersangkutan. Status booking saat ini harus `PENDING`.
- **Nilai Kembalian:** `['success' => bool, 'message' => string]`.

### 3.3. `bookingReject(PDO $pdo, int $bookingId, int $currentUserId): array`
- **Kegunaan:** Menolak pendaftaran peserta oleh Host.
- **Validasi:** User yang melakukan aksi wajib pemilik listing. Status booking harus `PENDING`.
- **Nilai Kembalian:** `['success' => bool, 'message' => string]`.

### 3.4. `bookingCancel(PDO $pdo, int $bookingId, int $currentUserId): array`
- **Kegunaan:** Membatalkan reservasi oleh peserta.
- **Aturan Bisnis Anti-Ghosting:**
  - Pembatalan > 2 jam sebelum sesi: Normal, tidak ada penalti skor.
  - Pembatalan mendadak (< 2 jam sebelum sesi) atau mangkir (*No-Show*): Mengurangi Trust Score (-25 poin) untuk mencegah ghosting.
- **Nilai Kembalian:** `['success' => bool, 'message' => string]`.

---

## 4. Checklist Implementasi
- [ ] Validasi wajib persetujuan *boundaries* sebelum data booking diproses.
- [ ] Larangan memesan slot pada penawaran milik diri sendiri.
- [ ] Pengurangan kapasitas dan penanganan *race condition* (kapasitas tidak boleh terlampaui).
- [ ] Penegakan aturan Trust & Karma Score pada pembatalan mendadak.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-BKC-01 | Happy Path | Booking listing gratis mode AUTOMATIC dengan boundaries disetujui | Sukses, status booking langsung 'CONFIRMED' |
| TC-BKC-02 | Validasi | Booking tanpa mencentang persetujuan boundaries | Ditolak, error "Anda wajib menyetujui batasan interaksi" |
| TC-BKC-03 | Validasi | Host mencoba memesan penawaran miliknya sendiri | Ditolak, error "Anda tidak dapat memesan penawaran Anda sendiri" |
| TC-BKC-04 | Boundary | Booking pada listing yang kapasitasnya sudah 0 (penuh) | Ditolak, error "Kapasitas penawaran sudah penuh" |
| TC-BKC-05 | Constraint | Booking ulang pada listing yang sama oleh user yang sama | Ditolak, error "Anda sudah terdaftar pada penawaran ini" |
| TC-BKC-06 | Otorisasi | Host menyetujui (approve) booking peserta | Status booking berubah menjadi 'CONFIRMED' |
| TC-BKC-07 | Aturan Bisnis | Participant dengan Trust Score 50 mencoba mengajukan booking | Ditolak, error akun dalam masa pembekuan / skor rendah |

