# PRD: reviews.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller ulasan, rating & karma (*Reviews & Reputation System - Fase 3 Roadmap*) pada sistem **PINJAMORANG**.

---

## 1. Identitas Class & Dependencies
- **Nama File Target:** `app/controller/reviews.controller.php`
- **Nama Class:** `ReviewsController`
- **Tanggung Jawab:** Mengelola formulir penilaian pengalaman, pemberian rating (skala 1 sampai 5 bintang), penyimpanan testimoni komentar, dan pemberian reward karma Trust Score (+5 poin) kepada pihak yang hadir tepat waktu.
- **Dependencies (Ketergantungan):**
  - Objek database: `PDO $pdo`
  - Model: `ReviewModel` dari `app/model/reviews.model.php`
  - Model Booking: `BookingModel` dari `app/model/bookings.model.php`
  - Model User: `UserModel` dari `app/model/users.model.php`

### Kerangka Dasar Class:
```php
class ReviewsController {
    private PDO $pdo;
    private ReviewModel $reviewModel;
    private BookingModel $bookingModel;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->reviewModel = new ReviewModel($pdo);
        $this->bookingModel = new BookingModel($pdo);
        $this->userModel = new UserModel($pdo);
    }
    // ... method-method di bawah ini
}
```

---

## 2. Tabel Pemetaan Routing (`app/index.php`)

| URL Route (`?page=...`) | HTTP Method | Method Controller yang Dipanggil | Proteksi Akses |
|---|---|---|---|
| `?page=review&booking_id=X` | `GET` | `ReviewsController::showReviewForm()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=submit-review` | `POST` | `ReviewsController::submitReview()` | Wajib Login (`AuthController::requireAuth`) |

---

## 3. Spesifikasi Lengkap Fungsi / Method

### 3.1. `public function showReviewForm(): void`
- **Kegunaan / Untuk Apa:** Menampilkan formulir penilaian ulasan dan bintang interaktif setelah sesi pengalaman selesai diselenggarakan.
- **Input yang Dibaca:** `$_GET['booking_id']`.
- **Validasi Bisnis:**
  1. Booking harus ada dan berstatus `COMPLETED`.
  2. Pengguna yang login wajib merupakan Participant yang mengikuti sesi tersebut (`participant_id`).
  3. Sesi belum pernah diulas sebelumnya via `$this->reviewModel->hasReviewedBooking($bookingId)`.
- **Logika Alur:**
  1. Siapkan judul halaman: `$pageTitle = "Beri Ulasan Pengalaman - PINJAMORANG"`.
  2. Rakit komponen View:
     ```php
     $extraScript = "/assets/js/review.js"; // Widget interaktif bintang 1-5
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/reviews/review_form.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.2. `public function submitReview(): void`
- **Kegunaan / Untuk Apa:** Memvalidasi input rating & komentar, menyimpan record ulasan baru ke database, dan memberikan reward penambahan skor reputasi (+5 poin Trust Score) atas kedisiplinan kehadiran.
- **Input yang Dibaca:** `$_POST['booking_id']`, `$_POST['rating']`, `$_POST['comment']`.
- **Validasi Input:**
  - `rating`: Wajib bilangan bulat antara 1 sampai 5.
  - `comment`: Teks opsional, maksimal 1000 karakter.
- **Logika Alur & Reward Kehadiran:**
  1. Mulai transaksi database: `$this->pdo->beginTransaction();`
  2. Simpan ulasan baru via:
     ```php
     $this->reviewModel->createReview([
         'booking_id'       => $bookingId,
         'reviewer_id'      => $_SESSION['user']['id'],
         'reviewed_user_id' => $booking['host_id'],
         'rating'           => $rating,
         'comment'          => $comment
     ]);
     ```
  3. **Reward Trust & Karma Score (+5 Poin):**
     - Berikan bonus kehadiran kepada Host:
       `$this->userModel->updateTrustScore($booking['host_id'], 5);`
     - Berikan bonus kehadiran kepada Participant:
       `$this->userModel->updateTrustScore($_SESSION['user']['id'], 5);`
  4. Selesaikan transaksi: `$this->pdo->commit();`
  5. Pasang flash alert: `$_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Terima kasih atas ulasan Anda! Skor reputasi Anda dan Host bertambah +5 poin.'];`
  6. Redirect ke halaman riwayat booking: `header("Location: /index.php?page=my-bookings"); exit;`

---

## 4. Checklist Implementasi
- [ ] Validasi range rating angka 1 s.d. 5 secara ketat di sisi server.
- [ ] Aturan 1 booking hanya dapat diulas 1 kali.
- [ ] Penambahan skor karma (+5 poin) diaplikasikan secara otomatis ke kedua belah pihak pasca-review.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-RC-01 | Tampilan Form | Buka form review untuk booking COMPLETED | Menampilkan formulir rating dan widget 5 bintang aktif |
| TC-RC-02 | Proteksi Status | Buka form review untuk booking yang masih PENDING | Ditolak, pesan "Ulasan hanya dapat diberikan setelah sesi selesai" |
| TC-RC-03 | Happy Path | Submit review dengan rating 5 dan komentar | Ulasan tersimpan, Trust Score user & host bertambah +5 poin |
| TC-RC-04 | Validasi | Submit review dengan rating 0 atau 6 | Ditolak oleh validasi Controller |
| TC-RC-05 | Duplikasi | Mengirim review untuk kedua kalinya pada booking yang sama | Ditolak, pesan "Booking ini sudah pernah diulas" |
