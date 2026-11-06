# PRD: listings.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller katalog penawaran waktu (*Human Catalog & Time Listings*) pada sistem **PINJAMORANG**.

---

## 1. Identitas Class & Dependencies
- **Nama File Target:** `app/controller/listings.controller.php`
- **Nama Class:** `ListingsController`
- **Tanggung Jawab:** Menangani etalase katalog publik, pencarian dan filter penawaran, tampilan detail pengalaman, formulir pembuatan waktu baru oleh Host, serta pembatalan penawaran.
- **Dependencies (Ketergantungan):**
  - Objek database: `PDO $pdo`
  - Model: `ListingModel` dari `app/model/listings.model.php`
  - Model User: `UserModel` dari `app/model/users.model.php`

### Kerangka Dasar Class:
```php
class ListingsController {
    private PDO $pdo;
    private ListingModel $listingModel;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
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
| `?page=home` atau default | `GET` | `ListingsController::index()` | Publik |
| `?page=listings` | `GET` | `ListingsController::index()` | Publik |
| `?page=listing-detail&id=X` | `GET` | `ListingsController::detail()` | Publik |
| `?page=create-listing` | `GET` | `ListingsController::create()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=create-listing` | `POST` | `ListingsController::store()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=my-listings` | `GET` | `ListingsController::myListings()` | Wajib Login (`AuthController::requireAuth`) |
| `?page=cancel-listing` | `POST` | `ListingsController::cancel()` | Wajib Login (`AuthController::requireAuth`) |

---

## 3. Spesifikasi Lengkap Fungsi / Method

### 3.1. `public function index(): void`
- **Kegunaan / Untuk Apa:** Menyiapkan data katalog penawaran aktif publik berdasarkan filter pencarian (kata kunci, tipe sesi online/offline, filter gratis), lalu merender halaman katalog utama.
- **Input yang Dibaca:** `$_GET['keyword']`, `$_GET['session_type']`, `$_GET['is_free']`, `$_GET['p']` (halaman pagination).
- **Logika Alur:**
  1. Kumpulkan parameter filter ke dalam array `$filters`.
  2. Panggil Model: `$listings = $this->listingModel->getActiveListings($filters);`
  3. Siapkan judul halaman: `$pageTitle = "Jelajahi Waktu Manusia - PINJAMORANG"`.
  4. Rakit komponen View:
     ```php
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/listings/catalog.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.2. `public function detail(): void`
- **Kegunaan / Untuk Apa:** Menampilkan rincian lengkap sebuah penawaran waktu, informasi host, sisa slot kuota, dan boks batas interaksi (*Boundaries Agreement*).
- **Input yang Dibaca:** `$_GET['id']` (integer ID listing).
- **Logika Alur:**
  1. Ambil `$id = (int)($_GET['id'] ?? 0)`.
  2. Panggil Model: `$listing = $this->listingModel->getListingById($id);`
  3. Jika data tidak ditemukan: Tampilkan halaman error 404 atau alihkan ke katalog dengan flash alert error.
  4. Hitung sisa kapasitas: `$remainingSlots = $this->listingModel->getRemainingCapacity($id);`
  5. Cek apakah user sedang login dan sudah mendaftar di sesi ini:
     `$hasBooked = isset($_SESSION['user']['id']) ? $this->bookingModel->hasUserBookedListing($id, $_SESSION['user']['id']) : false;`
  6. Rakit komponen View:
     ```php
     $extraScript = "/assets/js/booking.js"; // Pasang live price multiplier & boundaries guard
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/listings/detail.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.3. `public function create(): void`
- **Kegunaan / Untuk Apa:** Menampilkan formulir pembuatan penawaran waktu baru bagi Host.
- **Proteksi:** Wajib login. Cek kelayakan Trust Score Host (wajib &ge; 70).
- **Logika Alur:**
  1. Ambil data host: `$host = $this->userModel->getUserById($_SESSION['user']['id']);`
  2. Cek Trust Score: Jika `$host['trust_score'] < 70`, tolak pembuatan penawaran, beri pesan alert bahwa akun dalam masa pembekuan, dan redirect ke dashboard profil.
  3. Siapkan judul: `$pageTitle = "Buat Penawaran Waktu Baru - PINJAMORANG"`.
  4. Rakit komponen View:
     ```php
     $extraScript = "/assets/js/listing-form.js"; // Pasang dynamic venue switcher & auto duration
     require_once __DIR__ . "/../view/layout/header.view.php";
     require_once __DIR__ . "/../view/listings/create.view.php";
     require_once __DIR__ . "/../view/layout/footer.view.php";
     ```

### 3.4. `public function store(): void`
- **Kegunaan / Untuk Apa:** Memvalidasi data formulir penawaran baru yang dikirim oleh Host, mengecek konflik jadwal (anti-overlapping), dan menyimpannya ke tabel `listings`.
- **Input yang Dibaca:** `$_POST['title']`, `$_POST['description']`, `$_POST['session_type']`, `$_POST['listing_date']`, `$_POST['start_time']`, `$_POST['end_time']`, `$_POST['capacity']`, `$_POST['price']`, `$_POST['location']`, `$_POST['approval_mode']`, `$_POST['boundaries']`.
- **Validasi Bisnis:**
  1. Tanggal listing tidak boleh di masa lalu (`listing_date >= today`).
  2. Jam selesai harus lebih besar dari jam mulai (`end_time > start_time`).
  3. Cek jadwal bentrok via Model:
     `$this->listingModel->hasOverlappingListing($hostId, $date, $start, $end)`
     Jika bentrok: kembalikan ke form dengan pesan error "Jadwal bertabrakan dengan penawaran aktif Anda yang lain".
  4. Sesi OFFLINE wajib berlokasi di ruang publik terbuka yang ramai.
  5. Kapasitas minimal 1 orang, harga minimal 0 (jika opsi gratis dicentang, harga otomatis 0.00).
- **Penyimpanan & Redirect:**
  - Panggil `$this->listingModel->createListing(...)`.
  - Pasang flash alert: `$_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Penawaran waktu Anda berhasil diterbitkan!'];`
  - Redirect ke detail listing: `header("Location: /index.php?page=listing-detail&id=" . $newId); exit;`

### 3.5. `public function myListings(): void`
- **Kegunaan / Untuk Apa:** Menampilkan seluruh penawaran waktu yang pernah dibuat oleh user yang sedang login (Host Dashboard).
- **Logika Alur:**
  1. Ambil `$hostId = $_SESSION['user']['id'];`
  2. Panggil Model: `$myListings = $this->listingModel->getListingsByHost($hostId);`
  3. Rakit komponen View `app/view/listings/my_listings.view.php`.

### 3.6. `public function cancel(): void`
- **Kegunaan / Untuk Apa:** Membatalkan penawaran waktu oleh Host pemiliknya.
- **Input yang Dibaca:** `$_POST['listing_id']`.
- **Validasi Otorisasi:** Pastikan `host_id` pada listing sesuai dengan `$_SESSION['user']['id']`. Listing belum berstatus `COMPLETED` atau `CANCELLED`.
- **Logika:**
  - Panggil `$this->listingModel->updateListingStatus($listingId, 'CANCELLED')`.
  - Redirect kembali ke halaman `my-listings` dengan flash message sukses.

---

## 4. Checklist Implementasi
- [ ] Validasi keamanan jadwal bentrok (*anti-overlapping*) dijalankan sebelum data disimpan ke database.
- [ ] Aturan keselamatan sesi tatap muka (Offline) hanya diizinkan di ruang publik.
- [ ] Pengecekan ambang batas Trust Score (&ge; 70) pada fungsi `create()` dan `store()`.
- [ ] Menggunakan prepared statements secara konsisten melalui Model.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-LC-01 | Tampilan | Membuka `?page=listings` tanpa filter | Menampilkan daftar seluruh listing aktif publik |
| TC-LC-02 | Filter | Filter dengan `session_type=ONLINE` | Hanya menampilkan data dengan jenis sesi online |
| TC-LC-03 | Validasi Jadwal | Buat listing dengan jam mulai 15:00 dan jam selesai 14:00 | Gagal validasi, pesan "Jam selesai harus setelah jam mulai" |
| TC-LC-04 | Overlap Guard | Host membuat listing pada jam yang sama dengan listing aktifnya | Gagal validasi, pesan "Jadwal bertabrakan" |
| TC-LC-05 | Trust Guard | Host dengan Trust Score 60 mencoba membuat listing | Ditolak, diarahkan ke dashboard dengan notifikasi pembekuan akun |
| TC-LC-06 | Otorisasi Batal | User A mencoba membatalkan listing milik User B | Ditolak (403 Unauthorized) |
