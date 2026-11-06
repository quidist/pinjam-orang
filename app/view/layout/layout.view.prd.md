# PRD: layout.view.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk komponen kerangka tata letak global (*Global Layout: Header & Footer*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan Komponen:** Menyediakan pembungkus standar HTML5 (`<head>`, CSS, navigasi atas, penampung notifikasi kilat/flash message, dan penutup dokumen beserta skrip JS) agar tidak terjadi duplikasi kode di setiap halaman aplikasi.
- **Letak File Target:**
  - `app/view/layout/header.view.php`
  - `app/view/layout/footer.view.php`
- **Model / Data Terkait:** Session PHP `$_SESSION['user']` dan `$_SESSION['flash_message']`.

---

## 2. Struktur Elemen & Data Binding

### A. Komponen `header.view.php`

#### 1. Metadata Dokumen (`<head>`)
- Parameter judul halaman dinamis: `$pageTitle ?? 'PINJAMORANG - Borrow a Human'`
- Link stylesheet: `<link rel="stylesheet" href="/assets/css/style.css">`
- Viewport tag untuk responsivitas mobile: `<meta name="viewport" content="width=device-width, initial-scale=1.0">`

#### 2. Navigasi Global (`<nav class="navbar">`)
- **Brand / Logo:** Link ke `/index.php?page=home` dengan teks "PINJAMORANG" dan tagline kecil *"Borrow a human. Return with a story."*
- **Menu Publik:**
  - Link "Jelajahi Katalog" (`/index.php?page=listings`)
- **Kondisi Sesi Pengguna (User State):**
  - **Jika Belum Login (`!isset($_SESSION['user'])`):**
    - Tombol "Masuk" (`/index.php?page=login`)
    - Tombol "Daftar" (`/index.php?page=register`)
  - **Jika Sudah Login (`isset($_SESSION['user'])`):**
    - Tombol "+ Tawarkan Waktu" (`/index.php?page=create-listing`)
    - Link "Pengalaman Saya" (`/index.php?page=my-bookings`)
    - Menu Profil Akun:
      - Menampilkan nama user: `<?= htmlspecialchars($_SESSION['user']['name']) ?>`
      - Link "Profil Saya" (`/index.php?page=my-profile`)
      - Link "Keluar / Logout" (`/index.php?page=logout`)
- **Tombol Navigasi Mobile (Hamburger Button):**
  - Elemen `<button id="btnMobileMenu" class="mobile-toggle">` untuk toggle menu pada layar kecil.

#### 3. Penampung Notifikasi Flash (`#flashAlertContainer`)
- Wadah untuk menampilkan pesan kilat sekali pakai dari session (`$_SESSION['flash_message']`):
  - Tipe `success` &rarr; Banner warna hijau (misal: "Login berhasil!", "Penawaran waktu diterbitkan").
  - Tipe `error` &rarr; Banner warna merah (misal: "Gagal login", "Sesi telah berakhir").
- Tombol *close* (`&times;`) dan otomatis hilang via JS setelah 5 detik.
- Hapus session setelah ditampilkan: `unset($_SESSION['flash_message'])`.

#### 4. Wadah Utama Konten
- Tag pembuka pembungkus konten halaman: `<main class="main-container">`.

---

### B. Komponen `footer.view.php`

#### 1. Wadah Penutup Konten
- Tag penutup: `</main>`.

#### 2. Informasi Kaki Halaman (`<footer>`)
- Teks hak cipta: `&copy; <?= date('Y') ?> PINJAMORANG. All rights reserved.`
- Tagline filosofi: *"Because not every moment needs a reason."*

#### 3. Pemuatan Skrip JavaScript (`<script>`)
- Skrip Global: `<script src="/assets/js/main.js"></script>`
- Pemuatan Skrip Tambahan Spesifik Halaman (jika ada variabel `$extraScript` yang dikirim controller):
  ```php
  <?php if (!empty($extraScript)): ?>
      <script src="<?= htmlspecialchars($extraScript) ?>"></script>
  <?php endif; ?>
  ```

---

## 3. Checklist Implementasi
- [ ] Tag HTML5 valid, diawali `<!DOCTYPE html>` dan diakhiri `</html>`.
- [ ] Seluruh data dinamis di-escape dengan `htmlspecialchars()` untuk mencegah XSS.
- [ ] Penanganan sesi flash message otomatis dibersihkan (`unset`) setelah dirender.
- [ ] Id elemen `#btnMobileMenu` dan `#navMenuLinks` tersedia untuk dihubungkan dengan `main.js`.
- [ ] Atribut responsif viewport disertakan.

---

## 4. Daftar Pengujian Fungsional UI (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Tampilan |
|---|---|---|---|
| TC-LAY-01 | State Tamu | Membuka halaman saat `$_SESSION['user']` kosong | Navbar menampilkan tombol "Masuk" dan "Daftar", tombol logout tidak muncul |
| TC-LAY-02 | State Login | Membuka halaman saat session login aktif | Navbar menampilkan nama user, link "+ Tawarkan Waktu", dan link "Logout" |
| TC-LAY-03 | Flash Alert | Mengakses halaman setelah `$_SESSION['flash_message']` diset | Banner alert muncul di bawah navbar dengan warna dan teks yang sesuai |
| TC-LAY-04 | Flash Cleanup | Me-refresh halaman setelah flash alert muncul | Banner alert hilang (tidak muncul lagi) |
| TC-LAY-05 | Skrip Ekstra | Controller mengirim `$extraScript = '/assets/js/booking.js'` | Tag `<script src="/assets/js/booking.js">` ter-render di footer |

