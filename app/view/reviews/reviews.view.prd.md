# PRD: reviews.view.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk antarmuka formulir ulasan & rating bintang (*Post-Session Review Form - Fase 3 Roadmap*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan Halaman:** Menyediakan formulir bagi peserta yang telah menyelesaikan sesi pengalaman untuk memberikan ulasan feedback dan penilaian bintang (1 sampai 5) kepada Host, yang secara langsung memengaruhi Trust Score dan reputasi publik.
- **Letak File Target:** `app/view/reviews/review_form.view.php`
- **Controller Terkait:** `app/controller/reviews.controller.php`
- **Script JS Terkait:** `public/assets/js/review.js`

---

## 2. Struktur Elemen & Form Spesifikasi

#### 1. Header Pengalaman yang Ditinjau
- Menampilkan judul sesi pengalaman yang baru saja diselesaikan.
- Nama & foto Host yang dipinjam waktunya.
- Waktu dan tanggal pelaksanaan sesi.

#### 2. Formulir Ulasan (`<form id="formReview" method="POST" action="/index.php?page=submit-review">`)
- `<input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">`
- Input tersembunyi nilai rating angka:
  `<input type="hidden" id="inputRatingValue" name="rating" value="5" required>`

#### 3. Komponen Bintang Interaktif (`.star-rating-widget`)
- 5 ikon bintang yang bisa diklik atau di-hover (`data-value="1"` sampai `data-value="5"`):
  ```html
  <div class="star-rating" id="starContainer">
      <span class="star active" data-value="1">★</span>
      <span class="star active" data-value="2">★</span>
      <span class="star active" data-value="3">★</span>
      <span class="star active" data-value="4">★</span>
      <span class="star active" data-value="5">★</span>
  </div>
  <p id="ratingLabel" class="rating-label">5 Bintang - Pengalaman Luar Biasa!</p>
  ```

#### 4. Komentar Pengalaman
- Label: *"Ceritakan bagaimana momen yang kalian habiskan bersama"*
- Textarea: `<textarea name="comment" rows="4" placeholder="Apakah Host ramah? Bagaimana cerita yang kalian dapatkan? Tuliskan dengan jujur..." maxlength="1000"></textarea>`

#### 5. Banner Penghargaan Kehadiran (*Anti-Ghosting Reward*)
- Kotak informasi:
  *"Terima kasih telah hadir tepat waktu! Pengiriman ulasan ini akan menambahkan +5 poin Trust Score ke akun Anda dan Host."*

#### 6. Tombol Aksi
- Tombol Submit: `<button type="submit" id="btnSubmitReview" class="btn btn-primary">Kirim Ulasan & Tambah Karma</button>`
- Tombol Batal / Lewati: `<a href="/index.php?page=my-bookings" class="btn btn-link">Nanti Saja</a>`

---

## 3. Checklist Implementasi
- [ ] Formulir hanya dapat dibuka jika status booking sudah `COMPLETED`.
- [ ] Atribut input rating tersembunyi terhubung dengan interaksi bintang pada `review.js`.
- [ ] Validasi batasan karakter komentar maksimal 1000 karakter.
- [ ] Perlindungan XSS pada teks yang ditampilkan kembali.

---

## 4. Daftar Pengujian Fungsional UI (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Tampilan |
|---|---|---|---|
| TC-REV-V01 | Tampilan Form | Buka halaman review untuk booking COMPLETED | Menampilkan judul sesi, nama host, 5 bintang, dan textarea komentar |
| TC-REV-V02 | Proteksi Status | Buka halaman review untuk booking yang masih PENDING | Ditolak oleh Controller, dialihkan dengan pesan error |
| TC-REV-V03 | Rating Default | Buka form review pertama kali | Rating default terisi 5 bintang aktif dan label "Pengalaman Luar Biasa" |

