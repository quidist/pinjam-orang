# PRD: payments.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller pembayaran transaksi (*Payments & Checkout - Fase 2 Roadmap*) pada sistem **PINJAMORANG**.

---

## 1. Identitas Class & Dependencies
- **Nama File Target:** `app/controller/payments.controller.php`
- **Nama Class:** `PaymentsController`
- **Tanggung Jawab:** Menangani alur checkout reservasi slot berbayar, penerbitan tagihan/invoice dengan nomor referensi unik, verifikasi konfirmasi pembayaran, dan sinkronisasi status booking menjadi `CONFIRMED`.
- **Dependencies (Ketergantungan):**
  - Objek database: `PDO $pdo`
  - Model: `PaymentModel` dari `app/model/payments.model.php`
  - Model Booking: `BookingModel` dari `app/model/bookings.model.php`

### Kerangka Dasar Class:
```php
class PaymentsController {
    private PDO $pdo;
    private PaymentModel $paymentModel;
    private BookingModel $bookingModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->paymentModel = new PaymentModel($pdo);
        $this->bookingModel = new BookingModel($pdo);
    }
    // ... method-method di bawah ini
}
```

---

## 2. Tabel Pemetaan Routing (`app/index.php`)

| URL Route (`?page=...`) | HTTP Method | Method Controller yang Dipanggil | Proteksi Akses |
|---|---|---|---|
| `?page=checkout&booking_id=X` | `GET` | `PaymentsController::showCheckout()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=confirm-payment` | `POST` | `PaymentsController::confirmPayment()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=payment-receipt&id=X` | `GET` | `PaymentsController::receipt()` | Wajib Login (`AuthController::requireAuth`) |

---

## 3. Spesifikasi Lengkap Fungsi / Method

### 3.1. `public function showCheckout(): void`
- **Kegunaan / Untuk Apa:** Menampilkan halaman invoice rincian tagihan reservasi berbayar yang harus diselesaikan oleh peserta.
- **Input yang Dibaca:** `$_GET['booking_id']`.
- **Logika Alur:**
  1. Ambil `$bookingId = (int)($_GET['booking_id'] ?? 0);`
  2. Ambil data booking dari model.
  3. Validasi Otorisasi & Status:
     - User yang membuka wajib participant pemilik booking (`$booking['participant_id'] == $_SESSION['user']['id']`).
     - Status booking harus `PENDING` dan total harga `> 0`.
  4. Cek apakah baris transaksi `payments` sudah ada:
     - Jika belum ada, buat baris pembayaran awal berstatus `PENDING` dengan kode transaksi unik via `$this->paymentModel->createPayment(...)`.
  5. Siapkan judul halaman: `$pageTitle = "Checkout Pembayaran - PINJAMORANG"`.
  6. Rakit komponen View:
     ```php
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/payments/checkout.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.2. `public function confirmPayment(): void`
- **Kegunaan / Untuk Apa:** Memproses konfirmasi pelunasan pembayaran (simulasi pembayaran atau integrasi gateway) dan secara otomatis mengesahkan status booking menjadi `CONFIRMED`.
- **Input yang Dibaca:** `$_POST['booking_id']`, `$_POST['payment_method']`.
- **Aturan Bisnis #5:** Booking berbayar tidak boleh dianggap sah (*CONFIRMED*) sebelum status pembayaran terkonfirmasi *PAID*.
- **Logika Transaksi Database (Atomic):**
  1. Mulai transaksi database: `$this->pdo->beginTransaction();`
  2. Ambil record pembayaran untuk booking tersebut.
  3. Update status pembayaran menjadi `PAID` dan isi stempel waktu pembayaran via `$this->paymentModel->markAsPaid($paymentId)`.
  4. Update status booking terkait menjadi `CONFIRMED` via `$this->bookingModel->updateBookingStatus($bookingId, 'CONFIRMED')`.
  5. Selesaikan transaksi: `$this->pdo->commit();`
  6. Redirect ke halaman bukti lunas: `header("Location: /index.php?page=payment-receipt&id=" . $paymentId); exit;`

### 3.3. `public function receipt(): void`
- **Kegunaan / Untuk Apa:** Menampilkan bukti lunas (*Payment Receipt*) resmi setelah transaksi terkonfirmasi.
- **Input yang Dibaca:** `$_GET['id']` (integer payment ID).
- **Logika Alur:**
  1. Ambil data pembayaran dan detail booking terkait.
  2. Pastikan status pembayaran adalah `PAID`.
  3. Rakit komponen View `app/view/payments/receipt.view.php`.

---

## 4. Checklist Implementasi
- [ ] Penggunaan transaksi database (`beginTransaction`, `commit`, `rollBack`) pada proses konfirmasi pembayaran.
- [ ] Hanya pemilik booking yang diizinkan mengakses halaman checkout dan receipt miliknya sendiri.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-PC-01 | Tampilan Checkout | Peserta membuka checkout untuk booking miliknya | Tampil rincian nominal harga dan pilihan metode bayar |
| TC-PC-02 | Otorisasi | User lain mencoba mengakses URL checkout milik orang lain | Akses ditolak (403 Forbidden) |
| TC-PC-03 | Konfirmasi Bayar | Submit konfirmasi pembayaran | Status payment berubah jadi PAID, status booking jadi CONFIRMED |
| TC-PC-04 | Bukti Lunas | Membuka halaman receipt setelah pembayaran berhasil | Menampilkan lencana hijau LUNAS dan link menuju jadwal sesi |
