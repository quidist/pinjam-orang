# PRD: listings.view.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk antarmuka katalog waktu (*Human Catalog*), halaman rincian penawaran, dan formulir buat penawaran waktu pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan Halaman:** 
  1. Menampilkan etalase waktu manusia (*Human Catalog*) yang bisa difilter oleh pengunjung publik.
  2. Menampilkan rincian lengkap suatu penawaran (profil host, jadwal, aturan interaksi *boundaries*, dan tombol pesan).
  3. Menyediakan formulir pembuatan penawaran baru bagi Host.
- **Letak File Target:**
  - `app/view/listings/catalog.view.php`
  - `app/view/listings/detail.view.php`
  - `app/view/listings/create.view.php`
- **Controller Terkait:** `app/controller/listings.controller.php`
- **Script JS Terkait:** `public/assets/js/listing-form.js`

---

## 2. Struktur Elemen & Tampilan Per Halaman

### A. Katalog Penawaran (`catalog.view.php`)

#### 1. Bar Pencarian & Filter (`<form method="GET" action="/index.php">`)
- `<input type="hidden" name="page" value="listings">`
- **Input Kata Kunci:** `<input type="text" name="keyword" value="<?= htmlspecialchars($filters['keyword'] ?? '') ?>" placeholder="Cari pengalaman (misal: curhat, temani ngopi)...">`
- **Filter Tipe Sesi:**
  - Dropdown `<select name="session_type">`: Opsi `Semua`, `ONLINE (Virtual)`, `OFFLINE (Public Meetup)`.
- **Filter Gratis:**
  - Checkbox `<input type="checkbox" name="is_free" value="1" <?= !empty($filters['is_free']) ? 'checked' : '' ?>>` label *"Hanya yang Gratis"*.
- Tombol: `<button type="submit" class="btn btn-search">Terapkan Filter</button>`

#### 2. Grid Kartu Penawaran (`<div class="grid-listings">`)
- Setiap kartu penawaran (`.listing-card`) memuat:
  - **Badge Tipe Sesi:** Label `ONLINE` (warna biru) atau `OFFLINE` (warna ungu).
  - **Info Host:** Avatar (`<img src="...">`), Nama Host, dan Badge Trust Score (misal: `⭐ 100 Poin`).
  - **Judul Pengalaman:** Teks judul penawaran (`<h3>`).
  - **Jadwal & Durasi:** Tanggal, Jam Mulai - Selesai, dan Durasi (`duration_minutes` menit).
  - **Kapasitas:** Label *"Sisa X slot dari Y"*.
  - **Harga:**
    - Jika `price == 0` &rarr; Badge hijau bertuliskan **"GRATIS"**.
    - Jika `price > 0` &rarr; Teks harga Rupiah `Rp <?= number_format($price, 0, ',', '.') ?>`.
  - **Link Aksi:** Tombol `<a href="/index.php?page=listing-detail&id=<?= $item['id'] ?>" class="btn btn-outline">Lihat Detail</a>`.

#### 3. Kondisi Khusus (Empty State)
- Jika array `$listings` kosong:
  ```php
  <?php if (empty($listings)): ?>
      <div class="empty-state">
          <p>Belum ada penawaran waktu yang cocok dengan pencarian Anda.</p>
          <a href="/index.php?page=listings" class="btn btn-link">Reset Filter</a>
      </div>
  <?php endif; ?>
  ```

---

### B. Halaman Rincian Penawaran (`detail.view.php`)

#### 1. Header & Rincian Pengalaman
- Judul pengalaman, deskripsi lengkap, jenis sesi (Online / Offline Publik).
- Rincian Jadwal: Hari & Tanggal, Jam Selesai, Total Durasi.
- Lokasi:
  - Jika Offline: Menampilkan nama tempat publik (contoh: *"Kopi Kenangan Grand Indonesia"*).
  - Jika Online: Keterangan *"Link Google Meet/Zoom dibagikan privat setelah booking terkonfirmasi"*.

#### 2. Profil Host & Reputasi
- Nama host, bio singkat, status verifikasi, Trust Score, dan rata-rata rating review.

#### 3. Boks Batasan Interaksi (*Interaction Boundaries Box*)
- Daftar checklist ketentuan batas interaksi yang ditetapkan host (misal: *Bebas topik asmara, Tanpa sentuhan fisik, Sesi publik*).
- Catatan batas tambahan khusus dari host (jika ada).

#### 4. Area Formulir Reservasi / Pemesanan (`<form method="POST" action="/index.php?page=book">`)
- `<input type="hidden" name="listing_id" value="<?= $listing['id'] ?>">`
- **Persetujuan Boundaries (Wajib):**
  - Checkbox: `<input type="checkbox" id="chkAgreedBoundaries" name="agreed_to_boundaries" value="1" required>`
  - Label: *"Saya telah membaca dan menyetujui seluruh batasan interaksi di atas."*
- **Catatan untuk Host (Opsional):** `<textarea name="notes" placeholder="Tuliskan catatan singkat untuk Host..."></textarea>`
- **Tombol Pesan:**
  - Jika Kuota Habis: Tombol disabled *"Kapasitas Penuh"*.
  - Jika Sesi Sudah Selesai: Label *"Penawaran Berakhir"*.
  - Jika Masih Tersedia: `<button type="submit" id="btnBookNow" class="btn btn-primary">Pesan Slot Waktu</button>`.

---

### C. Formulir Buat Penawaran (`create.view.php`)

#### 1. Form Attributes
- `<form id="formCreateListing" method="POST" action="/index.php?page=create-listing">`

#### 2. Input Fields
- **Judul:** `<input type="text" name="title" placeholder="Contoh: Diskusi Santai tentang Kopi" required>`
- **Deskripsi:** `<textarea name="description" rows="4" placeholder="Jelaskan apa yang akan kalian lakukan bersama..." required></textarea>`
- **Pilihan Jenis Sesi (Radio Button):**
  - `<input type="radio" name="session_type" value="ONLINE" id="radioOnline" checked>` Label: *Online (Virtual)*
  - `<input type="radio" name="session_type" value="OFFLINE" id="radioOffline">` Label: *Offline (Public Meetup)*
- **Jadwal & Waktu:**
  - Tanggal: `<input type="date" name="listing_date" min="<?= date('Y-m-d') ?>" required>`
  - Jam Mulai: `<input type="time" id="inputStartTime" name="start_time" required>`
  - Jam Selesai: `<input type="time" id="inputEndTime" name="end_time" required>`
  - Durasi Otomatis: `<input type="number" id="inputDuration" name="duration_minutes" readonly> menit`
- **Kapasitas:** `<input type="number" name="capacity" min="1" max="50" value="1" required>`
- **Harga:**
  - Checkbox: `<input type="checkbox" id="chkFreeListing" name="is_free" value="1"> Jadikan Penawaran Gratis (FREE)`
  - Input Nominal: `<input type="number" id="inputPrice" name="price" min="0" step="1000" placeholder="0">`
- **Lokasi:**
  - Label dinamis: `<label id="labelLocation">Link Platform Virtual</label>`
  - Input: `<input type="text" id="inputLocation" name="location" placeholder="Misal: Google Meet / Discord" required>`
  - Catatan keselamatan: *"Peringatan: Untuk sesi offline, pertemuan WAJIB di ruang publik terbuka yang ramai (kafe, taman, perpustakaan umum)."*
- **Checklist Preset Batasan Interaksi:**
  - Checkbox preset: Bebas bahasan asmara, Tanpa kontak fisik, Dilarang merekam tanpa izin.
  - Catatan batasan tambahan: `<textarea name="custom_boundaries" placeholder="Catatan batasan khusus Anda..."></textarea>`
- **Mode Persetujuan Peserta:**
  - Dropdown `<select name="approval_mode">`: `AUTOMATIC (Langsung Terkonfirmasi)` atau `MANUAL (Perlu Persetujuan Host)`.

---

## 3. Checklist Implementasi
- [ ] Validasi tanggal minimal formulir tidak boleh sebelum hari ini (`min="<?= date('Y-m-d') ?>"`).
- [ ] Format mata uang dan tanggal di-format ramah pembaca Indonesia (IDR, tanggal lokal).
- [ ] Atribut elemen ID `#radioOnline`, `#radioOffline`, `#labelLocation`, `#inputLocation`, `#chkFreeListing`, dan `#inputPrice` terpasang untuk dikontrol oleh `listing-form.js`.
- [ ] Penanganan kondisi kuota habis (*disabled state* pada tombol booking).

---

## 4. Daftar Pengujian Fungsional UI (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Tampilan |
|---|---|---|---|
| TC-LST-V01 | Tampilan Katalog | Buka halaman katalog yang memiliki data listing | Kartu listing muncul dengan badge harga/gratis dan tipe sesi yang benar |
| TC-LST-V02 | Empty State | Filter katalog dengan kata kunci acak yang tidak ada datanya | Muncul pesan "Belum ada penawaran waktu yang cocok" |
| TC-LST-V03 | Detail Listing | Buka detail penawaran gratis mode online | Harga menampilkan "GRATIS", lokasi berupa catatan privat link |
| TC-LST-V04 | Kuota Penuh | Buka detail listing dengan kapasitas sisa 0 | Tombol booking berubah menjadi disabled bertuliskan "Kapasitas Penuh" |
| TC-LST-V05 | Form Buat Listing | Buka formulir buat penawaran | Input tanggal otomatis membatasi tanggal minimal hari ini |

