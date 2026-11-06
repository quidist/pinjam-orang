# PRD: bookings.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller reservasi waktu (*Time Booking & Capacity Management*) pada sistem **PINJAMORANG**.

---

## 1. Identitas Class & Dependencies
- **Nama File Target:** `app/controller/bookings.controller.php`
- **Nama Class:** `BookingsController`
- **Tanggung Jawab:** Mengatur alur pendaftaran slot waktu oleh Participant, penegakan persetujuan batasan interaksi (*Boundaries Agreement*), dashboard persetujuan oleh Host (*Host Approval System*), pembatalan reservasi, dan penegakan aturan anti-ghosting.
- **Dependencies (Ketergantungan):**
  - Objek database: `PDO $pdo`
  - Model: `BookingModel` dari `app/model/bookings.model.php`
  - Model Listing: `ListingModel` dari `app/model/listings.model.php`
  - Model User: `UserModel` dari `app/model/users.model.php`

### Kerangka Dasar Class:
```php
class BookingsController {
    private PDO $pdo;
    private BookingModel $bookingModel;
    private ListingModel $listingModel;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->bookingModel = new BookingModel($pdo);
        $this->listingModel = new ListingModel($pdo);
        $this->userModel = new UserModel($pdo);
    }
    // ... method-method di bawah ini
}
```

---

## 2. Tabel Pemetaan Routing (`app/index.php`)

| URL Route (`?page=...`) | HTTP Method | Method Controller yang Dipanggil | Proteksi Akses |
|---|---|---|---|
| `?page=book` | `POST` | `BookingsController::book()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=my-bookings` | `GET` | `BookingsController::myBookings()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=host-requests` | `GET` | `BookingsController::hostRequests()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=approve-booking` | `POST` | `BookingsController::approve()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=reject-booking` | `POST` | `BookingsController::reject()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=cancel-booking` | `POST` | `BookingsController::cancel()` | Wajib Login (`AuthController::requireAuth`) |

---

## 3. Spesifikasi Lengkap Fungsi / Method

### 3.1. `public function book(): void`
- **Kegunaan / Untuk Apa:** Memvalidasi dan memproses pengajuan pemesanan slot waktu yang dikirim oleh peserta dari halaman detail penawaran.
- **Input yang Dibaca:** `$_POST['listing_id']`, `$_POST['slot_count']`, `$_POST['agreed_to_boundaries']`, `$_POST['notes']`.
- **Logika Alur & Validasi Bisnis:**
  1. Ambil `$participantId = $_SESSION['user']['id'];`
  2. Cek Trust Score Peserta: Jika `$participant['trust_score'] < 70`, tolak pemesanan dan kembalikan dengan pesan bahwa akun dalam masa pembekuan skor.
  3. Cek Listing: Pastikan listing ada dan berstatus `ACTIVE`.
  4. Cek Self-Booking: Host dilarang memesan penawaran miliknya sendiri (`$listing['host_id'] !== $participantId`).
  5. Cek Boundaries Agreement: Peserta **wajib mencentang** persetujuan batasan (`isset($_POST['agreed_to_boundaries']) && $_POST['agreed_to_boundaries'] == '1'`). Jika tidak dicentang, tolak pemesanan.
  6. Cek Anti-Double-Booking: Peserta belum pernah mendaftar di sesi ini via `$this->bookingModel->hasUserBookedListing($listingId, $participantId)`.
  7. Cek Sisa Kapasitas: Kuota yang diminta (`slot_count`) tidak boleh melebihi sisa kapasitas yang tersedia.
  8. **Penentuan Status & Transaksi:**
     - Hitung total harga: `$totalPrice = $listing['price'] * $slotCount;`
     - Jika listing **GRATIS (`price == 0`)**:
       - Jika `approval_mode == 'AUTOMATIC'` &rarr; status langsung `CONFIRMED`.
       - Jika `approval_mode == 'MANUAL'` &rarr; status `PENDING` (menunggu persetujuan host).
       - Simpan ke database via `$this->bookingModel->createBooking(...)`.
       - Pasang flash alert sukses, lalu redirect ke `?page=my-bookings`.
     - Jika listing **BERBAYAR (`price > 0`)**:
       - Status awal `PENDING`.
       - Simpan booking, lalu redirect langsung ke halaman checkout: `header("Location: /index.php?page=checkout&booking_id=" . $bookingId); exit;`

### 3.2. `public function myBookings(): void`
- **Kegunaan / Untuk Apa:** Menampilkan halaman "Pengalaman Saya" bagi peserta, berisi daftar seluruh sesi yang pernah dan akan diikuti beserta statusnya.
- **Logika Alur:**
  1. Ambil data booking peserta: `$myBookings = $this->bookingModel->getBookingsByParticipant($_SESSION['user']['id']);`
  2. Siapkan judul halaman: `$pageTitle = "Pengalaman Saya - PINJAMORANG"`.
  3. Rakit komponen View:
     ```php
     $extraScript = "/assets/js/booking.js"; // Dialog modal konfirmasi pembatalan
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/bookings/my_bookings.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.3. `public function hostRequests(): void`
- **Kegunaan / Untuk Apa:** Dashboard bagi Host untuk melihat daftar calon peserta yang mendaftar pada listing miliknya yang memerlukan persetujuan manual.
- **Input yang Dibaca:** `$_GET['listing_id']` (opsional jika ingin filter per listing).
- **Logika Alur:**
  1. Ambil seluruh permintaan masuk untuk penawaran milik Host yang sedang login.
  2. Rakit komponen View `app/view/bookings/host_requests.view.php`.

### 3.4. `public function approve(): void`
- **Kegunaan / Untuk Apa:** Host menyetujui pendaftaran calon peserta yang berstatus `PENDING`.
- **Input yang Dibaca:** `$_POST['booking_id']`.
- **Validasi Otorisasi:** Pastikan user yang menekan tombol setuju adalah Host pemilik listing tersebut.
- **Logika Alur:**
  1. Ubah status booking menjadi `CONFIRMED` via `$this->bookingModel->updateBookingStatus($bookingId, 'CONFIRMED')`.
  2. Pasang flash alert: `$_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Peserta berhasil disetujui! Link pertemuan kini telah dibagikan.'];`
  3. Redirect kembali ke `?page=host-requests`.

### 3.5. `public function reject(): void`
- **Kegunaan / Untuk Apa:** Host menolak pendaftaran calon peserta.
- **Input yang Dibaca:** `$_POST['booking_id']`.
- **Logika Alur:**
  1. Verifikasi kepemilikan Host.
  2. Ubah status booking menjadi `REJECTED` via `$this->bookingModel->updateBookingStatus($bookingId, 'REJECTED')`.
  3. Pasang flash alert informasi, lalu redirect kembali ke `?page=host-requests`.

### 3.6. `public function cancel(): void`
- **Kegunaan / Untuk Apa:** Membatalkan reservasi oleh peserta, dengan penegakan aturan Trust Score (anti-ghosting).
- **Input yang Dibaca:** `$_POST['booking_id']`.
- **Aturan Bisnis Anti-Ghosting:**
  1. Ambil jadwal mulai listing dari booking tersebut.
  2. Hitung selisih jam antara waktu saat ini dengan jam mulai sesi.
  3. **Jika pembatalan mendadak (< 2 jam sebelum sesi mulai):**
     - Berikan penalti pengurangan Trust Score (-25 poin):
       `$this->userModel->updateTrustScore($_SESSION['user']['id'], -25);`
     - Beri notifikasi peringatan: *"Reservasi dibatalkan. Karena pembatalan kurang dari 2 jam, skor reputasi Anda berkurang 25 poin."*
  4. **Jika pembatalan normal (> 2 jam sebelum sesi mulai):**
     - Tidak ada penalti skor.
  5. Ubah status booking menjadi `CANCELLED` via `$this->bookingModel->cancelBooking($bookingId, $_SESSION['user']['id'])`.
  6. Redirect kembali ke `?page=my-bookings`.

---

## 4. Checklist Implementasi
- [ ] Validasi wajib persetujuan batasan interaksi (`agreed_to_boundaries == 1`).
- [ ] Larangan memesan penawaran milik diri sendiri (*self-booking prevention*).
- [ ] Penegakan aturan penalti Trust Score (-25 poin) saat pembatalan mendadak (< 2 jam).
- [ ] Link sesi privat hanya dibagikan di halaman View setelah status `CONFIRMED`.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-BC-01 | Happy Path | Booking listing gratis mode AUTOMATIC dengan batasan disetujui | Status langsung CONFIRMED, dialihkan ke my-bookings |
| TC-BC-02 | Validasi | Booking tanpa menyetujui batasan interaksi | Ditolak, pesan "Wajib menyetujui batasan interaksi" |
| TC-BC-03 | Self-Booking | Host mencoba memesan slot di penawaran miliknya | Ditolak, pesan "Tidak dapat memesan penawaran sendiri" |
| TC-BC-04 | Host Approval | Host menyetujui pendaftaran peserta | Status booking berubah jadi CONFIRMED |
| TC-BC-05 | Anti-Ghosting | Peserta membatalkan sesi 30 menit sebelum acara | Booking dibatalkan, Trust Score peserta berkurang 25 poin |
| TC-BC-06 | Pembatalan Normal | Peserta membatalkan sesi 1 hari sebelum acara | Booking dibatalkan tanpa penalti Trust Score |
