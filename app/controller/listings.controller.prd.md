# PRD: listings.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller katalog penawaran waktu (*Human Catalog & Time Listing*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengatur alur penjelajahan katalog waktu publik (Human Catalog), pencarian dan filter penawaran, pembuatan penawaran baru oleh Host, validasi lokasi ruang publik (untuk sesi Offline), checklist batas interaksi (*Boundaries*), serta pengelolaan status penawaran.
- **Letak File Target:** `app/controller/listings.controller.php`
- **Model Terkait:** `app/model/listings.model.php` dan `app/model/users.model.php`
- **Ketergantungan:** Guard `requireAuth()` dari `auth.controller.php`.

---

## 2. Alur Kerja & Endpoints / Aksi

| Aksi | Method HTTP | Deskripsi | Input Utama | Output / Redirect |
|---|---|---|---|---|
| `index` | GET | Menampilkan katalog penawaran aktif publik | Query params: `keyword`, `session_type`, `is_free`, `date`, `page` | Render view `listings/index.php` |
| `detail` | GET | Menampilkan detail penawaran spesifik | `id` | Render view `listings/detail.php` |
| `createForm` | GET | Menampilkan formulir buat penawaran waktu baru | - | Render view `listings/create.php` (butuh login) |
| `store` | POST | Memvalidasi dan menyimpan penawaran baru | `title`, `description`, `session_type`, `date`, `start_time`, `end_time`, `capacity`, `price`, `location`, `approval_mode`, `boundaries` | Redirect ke detail listing atau dashboard host |
| `myListings` | GET | Menampilkan daftar listing yang dibuat oleh user sendiri | Session user | Render view `listings/my_listings.php` |
| `cancel` | POST | Membatalkan penawaran waktu oleh Host | `id` listing | Update status menjadi `CANCELLED` |

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `listingIndex(PDO $pdo, array $queryParams): array`
- **Kegunaan:** Menyiapkan data katalog penawaran untuk halaman utama/katalog.
- **Logika:**
  - Ekstraksi parameter filter: `keyword`, `session_type` (ONLINE/OFFLINE), `is_free`, `date`, `page`.
  - Panggil `getActiveListings()` dari model.
  - Hitung sisa slot untuk setiap listing menggunakan `getRemainingCapacity()`.
- **Nilai Kembalian:** Array `['listings' => array, 'filters' => array, 'pagination' => array]`.

### 3.2. `listingDetail(PDO $pdo, int $listingId, ?int $currentUserId = null): array`
- **Kegunaan:** Menyiapkan data detail penawaran untuk halaman rincian listing.
- **Logika:**
  - Ambil listing dengan `getListingById()`.
  - Hitung sisa kapasitas slot.
  - Cek apakah user yang sedang login sudah pernah mengajukan booking di listing ini (`hasUserBookedListing`).
- **Nilai Kembalian:** Array detail lengkap atau error 404 jika tidak ditemukan.

### 3.3. `listingCreate(PDO $pdo, int $hostId, array $postData): array`
- **Kegunaan:** Memvalidasi input formulir pembuatan listing dan menyimpannya.
- **Validasi Bisnis Wajib:**
  1. *Trust Score Check:* Cek kelayakan Trust Score Host (wajib &ge; 70).
  2. *Durasi & Waktu:* `listing_date` tidak boleh di masa lalu. `end_time` harus lebih besar dari `start_time`.
  3. *Overlap Check:* Host tidak boleh memiliki penawaran lain yang bertabrakan di tanggal dan jam yang sama (`hasOverlappingListing`).
  4. *Kapasitas & Harga:* Kapasitas minimal 1 orang. Harga tidak boleh negatif (jika 0 berarti FREE).
  5. *Session Type & Location Rule:*
     - Jika `session_type == 'OFFLINE'`, lokasi harus berupa nama ruang publik yang ramai (bukan ruang privat/hotel/rumah).
     - Jika `session_type == 'ONLINE'`, lokasi berupa platform/link meeting.
  6. *Boundaries Checklist:* Host harus menyertakan ketentuan interaksi (preset boundaries + catatan tambahan).
- **Nilai Kembalian:** `['success' => bool, 'listing_id' => ?int, 'errors' => array]`.

### 3.4. `listingCancel(PDO $pdo, int $listingId, int $currentUserId): array`
- **Kegunaan:** Membatalkan listing oleh Host pemiliknya.
- **Validasi:** User yang melakukan aksi wajib pemilik listing (`host_id`). Listing belum berstatus `COMPLETED` atau `CANCELLED`.
- **Nilai Kembalian:** `['success' => bool, 'message' => string]`.

---

## 4. Checklist Implementasi
- [ ] Penegakan aturan keselamatan: Validasi lokasi sesi OFFLINE hanya di ruang publik terbuka.
- [ ] Validasi bentrok jadwal (*anti-overlapping*) untuk Host yang sama.
- [ ] Validasi ketersediaan kapasitas sebelum menampilkan tombol booking.
- [ ] Pengecekan status login menggunakan guard `requireAuth()` pada aksi create, edit, dan cancel.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-LTC-01 | Happy Path | Akses katalog dengan filter `session_type = 'OFFLINE'` | Menampilkan daftar listing offline aktif |
| TC-LTC-02 | Validasi | Buat listing dengan jam selesai sebelum jam mulai | Gagal, error "Waktu selesai harus setelah waktu mulai" |
| TC-LTC-03 | Validasi | Buat listing dengan tanggal kemarin (masa lampau) | Gagal, error "Tanggal listing tidak boleh di masa lalu" |
| TC-LTC-04 | Aturan Bisnis | Host membuat listing yang jamnya bertabrakan dengan listing aktifnya | Gagal, error "Jadwal bertabrakan dengan penawaran Anda yang lain" |
| TC-LTC-05 | Aturan Bisnis | Host dengan Trust Score 60 mencoba membuat listing | Ditolak, error "Skor reputasi di bawah batas minimum" |
| TC-LTC-06 | Otorisasi | User A mencoba membatalkan listing milik User B | Ditolak, error 403 Forbidden / Unauthorized |

