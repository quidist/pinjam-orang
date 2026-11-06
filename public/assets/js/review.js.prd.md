# PRD: review.js.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk skrip interaksi formulir ulasan & rating bintang (*Review & Star Rating Client-Side UX*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola interaksi visual pemilihan bintang 1 sampai 5 (efek hover dan klik) pada formulir ulasan pasca-sesi, serta memperbarui nilai input tersembunyi (*hidden input*) sebelum data dikirim ke Controller.
- **Letak File Target:** `public/assets/js/review.js`
- **Tampilan Terkait:** `app/view/reviews/review_form.view.php`
- **Teknologi:** Modern Vanilla JavaScript (ES6+).

---

## 2. Spesifikasi Fungsi & Event Listeners

### 2.1. `initStarRatingWidget(): void`
- **Tujuan:** Mengatur interaksi pemilihan jumlah bintang secara intuitif.
- **Target Elemen:** 
  - Kontainer bintang: `#starContainer`
  - Elemen bintang-bintang: `.star` (dengan atribut `data-value="1"` s.d. `data-value="5"`)
  - Input tersembunyi: `#inputRatingValue`
  - Label teks feedback: `#ratingLabel`
- **Peta Deskripsi Rating:**
  - 1 Bintang: *"1 Bintang - Sangat Mengecewakan"*
  - 2 Bintang: *"2 Bintang - Kurang Memuaskan"*
  - 3 Bintang: *"3 Bintang - Cukup Baik"*
  - 4 Bintang: *"4 Bintang - Menyenangkan"*
  - 5 Bintang: *"5 Bintang - Pengalaman Luar Biasa!"*
- **Logika Interaksi:**
  - **Saat Hover Mouse (`mouseenter` pada bintang):**
    - Ambil nilai bintang yang disorot (`hoverValue`).
    - Nyalakan bintang 1 sampai `hoverValue` (tambahkan class `.is-hovered`), redupkan bintang setelahnya.
  - **Saat Kursor Keluar (`mouseleave` pada `#starContainer`):**
    - Kembalikan warna bintang sesuai nilai yang sedang terpilih di `#inputRatingValue`.
  - **Saat Bintang Diklik (`click`):**
    - Ambil `selectedValue = parseInt(star.dataset.value)`.
    - Simpan ke input tersembunyi: `inputRatingValue.value = selectedValue`.
    - Beri class `.active` permanen pada bintang 1 s.d. `selectedValue`.
    - Update teks pada `#ratingLabel` sesuai peta deskripsi.

---

## 3. Checklist Implementasi
- [ ] Script hanya dijalankan jika elemen `#starContainer` ditemukan di halaman.
- [ ] Nilai default adalah 5 bintang saat form pertama kali dimuat.
- [ ] Mendukung aksesibilitas keyboard (dapat diubah dengan tombol panah jika dibutuhkan).

---

## 4. Daftar Pengujian Fungsional JS (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-JS-RV01 | Rating Default | Buka halaman form review | Kelima bintang aktif menyala, input hidden bernilai 5 |
| TC-JS-RV02 | Klik Bintang | Klik bintang ke-3 | Bintang 1, 2, dan 3 menyala; bintang 4 dan 5 redup; input hidden bernilai 3 |
| TC-JS-RV03 | Label Dinamis | Klik bintang ke-4 | Label teks di bawahnya otomatis berubah menjadi "4 Bintang - Menyenangkan" |
| TC-JS-RV04 | Hover Effect | Sorot kursor ke bintang ke-1 tanpa klik, lalu geser kursor keluar | Bintang kembali menyala sesuai nilai yang terakhir diklik |

