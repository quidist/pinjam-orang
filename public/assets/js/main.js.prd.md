# PRD: main.js.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk skrip JavaScript global (*Global Client Script*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Menangani interaksi antarmuka global yang berjalan di seluruh halaman web, seperti toggle menu navigasi mobile (hamburger), penutupan otomatis notifikasi alert flash, dan fungsi bantuan (*helper*) umum.
- **Letak File Target:** `public/assets/js/main.js`
- **Tampilan Terkait:** `app/view/layout/header.view.php` dan `app/view/layout/footer.view.php`
- **Teknologi:** Modern Vanilla JavaScript (ES6+), tanpa library eksternal.

---

## 2. Spesifikasi Fungsi & Event Listeners

### 2.1. `initMobileMenu(): void`
- **Tujuan:** Membuka dan menutup menu navigasi pada layar HP/smartphone.
- **Target Elemen:**
  - Tombol pemicu: `#btnMobileMenu`
  - Kontainer menu: `#navMenuLinks`
- **Logika Interaksi:**
  - Dengarkan event `click` pada `#btnMobileMenu`.
  - Toggle class CSS `.is-active` atau `.show` pada `#navMenuLinks`.
  - Mengubah atribut `aria-expanded` antara `"true"` dan `"false"` untuk aksesibilitas.

### 2.2. `initFlashAlertAutoDismiss(int timeoutMs = 5000): void`
- **Tujuan:** Menghilangkan notifikasi alert flash secara perlahan (*fade out*) setelah beberapa detik agar tidak menghalangi pandangan user.
- **Target Elemen:** `.flash-alert` di dalam `#flashAlertContainer`.
- **Logika Interaksi:**
  - Cari elemen alert dengan class `.flash-alert`.
  - Jika ada, pasang `setTimeout(..., timeoutMs)`.
  - Tambahkan efek transisi CSS `opacity = 0`, lalu hapus elemen dari DOM (`alert.remove()`).
  - Dengarkan juga tombol tutup manual (`.btn-close-alert`) untuk menutup instan jika diklik user.

### 2.3. `initConfirmDialogs(): void`
- **Tujuan:** Mencegah aksi destruktif yang tidak disengaja (misal: tombol logout atau hapus).
- **Target Elemen:** Elemen apa pun yang memiliki atribut `data-confirm="Pesan konfirmasi"`.
- **Logika Interaksi:**
  - Dengarkan event `click` pada elemen bertanda `data-confirm`.
  - Jalankan `confirm(pesan)`. Jika user klik *Cancel*, jalankan `event.preventDefault()`.

---

## 3. Checklist Implementasi
- [ ] Dibungkus di dalam event `document.addEventListener('DOMContentLoaded', ...)` agar script hanya berjalan setelah DOM HTML selesai dimuat.
- [ ] Pengecekan keberadaan elemen (`if (element) { ... }`) sebelum memasang event listener agar tidak melempar error *null pointer* di halaman yang tidak memiliki elemen tersebut.
- [ ] Menggunakan sintaks modern (`const`, `let`, arrow functions).

---

## 4. Daftar Pengujian Fungsional JS (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-JS-MN01 | Mobile Menu | Klik tombol hamburger `#btnMobileMenu` pada layar mobile | Menu `#navMenuLinks` muncul (class `.is-active` bertambah) |
| TC-JS-MN02 | Mobile Menu | Klik tombol hamburger kedua kalinya | Menu `#navMenuLinks` tertutup kembali |
| TC-JS-MN03 | Auto Dismiss | Halaman memuat alert flash sukses | Alert otomatis memudar dan hilang dari layar setelah 5 detik |
| TC-JS-MN04 | Manual Close | Klik tombol silang `&times;` pada alert sebelum 5 detik | Alert langsung hilang seketika |
| TC-JS-MN05 | Confirm Guard | Klik link dengan `data-confirm="Yakin ingin keluar?"` lalu tekan Batal | Halaman tidak berpindah / logout dibatalkan |

