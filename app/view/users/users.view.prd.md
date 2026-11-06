# PRD: users.view.prd.md

Dokumen Product Requirement Document (PRD) teknis untuk antarmuka profil publik dan pengaturan akun pengguna (*User Profile & Settings Views*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan Halaman:** 
  1. Halaman **"Profil Publik"** (`profile.view.php`): Menampilkan kartu reputasi pengguna kepada publik, skor karma (Trust Score), status verifikasi, dan ulasan bintang dari orang lain.
  2. Halaman **"Profil Saya / Pengaturan"** (`my_profile.view.php`): Formulir pengelolaan data pribadi oleh pemilik akun (ubah nama, bio, avatar, ganti kata sandi).
- **Letak File Target:**
  - `app/view/users/profile.view.php`
  - `app/view/users/my_profile.view.php`
- **Controller Terkait:** `app/controller/users.controller.php`

---

## 2. Struktur Elemen & Tampilan Per Halaman

### A. Tampilan Profil Publik (`profile.view.php`)

#### 1. Kartu Identitas Pengguna
- **Avatar:** Foto profil pengguna atau inisial nama jika belum unggah avatar.
- **Nama & Status Verifikasi:** Nama lengkap pengguna, di sampingnya ada lencana centang biru jika `is_verified == true`.
- **Bio Diri:** Paragraf deskripsi kepribadian/minat yang ditulis pengguna.
- **Waktu Bergabung:** *"Bergabung sejak: Bulan Tahun"*.

#### 2. Meteran Trust & Karma Score (*Reputation Gauge*)
- Menampilkan skor angka (misal: `100 Poin`).
- Bar meteran reputasi:
  - Hijau (&ge; 90): *"Sangat Terpercaya & Disiplin"*.
  - Kuning (70 - 89): *"Standar"*.
  - Merah (< 70): *"Perhatian: Skor Rendah (Pembatasan Fitur)"*.
- Penjelasan transparansi sistem: *"Skor didapat dari kehadiran tepat waktu dan kepatuhan batasan interaksi."*

#### 3. Proteksi Privasi Kontak (Privacy-First Rule)
- Nomor telepon / WhatsApp **TIDAK DITAMPILKAN** pada profil publik demi mencegah *spamming* dan *stalking*.

#### 4. Daftar Ulasan & Testimoni (`.reviews-section`)
- Menampilkan rata-rata bintang (misal: `⭐ 4.9 dari 5 (12 Ulasan)`).
- Daftar kartu testimoni dari peserta/host lain:
  - Foto & nama pemberi ulasan.
  - Jumlah bintang yang diberikan.
  - Tanggal dan komentar pengalaman bersama.

---

### B. Tampilan Akun Sendiri & Edit Profil (`my_profile.view.php`)

#### 1. Form Edit Informasi Profil
- `<form method="POST" action="/index.php?page=update-profile" enctype="multipart/form-data">`
- **Nama:** `<input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required>`
- **Email:** `<input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly disabled>` *(Email terkunci, tidak bisa diubah sembarangan).*
- **Nomor Telepon:** `<input type="tel" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="08xxxxxxxxxx">`
  - Keterangan privasi: *"Nomor telepon Anda aman dan tidak pernah ditampilkan ke publik."*
- **Bio Profil:** `<textarea name="bio" rows="3" maxlength="500"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>`
- **Tombol Simpan:** `<button type="submit" class="btn btn-primary">Simpan Perubahan</button>`

#### 2. Form Ganti Kata Sandi
- `<form method="POST" action="/index.php?page=change-password">`
- **Kata Sandi Lama:** `<input type="password" name="old_password" required>`
- **Kata Sandi Baru:** `<input type="password" name="new_password" minlength="6" required>`
- **Ulangi Kata Sandi Baru:** `<input type="password" name="confirm_new_password" required>`
- **Tombol Ubah:** `<button type="submit" class="btn btn-secondary">Perbarui Kata Sandi</button>`

---

## 3. Checklist Implementasi
- [ ] Penegakan *Privacy-First*: Nomor telepon tidak boleh dicetak di `profile.view.php`.
- [ ] Nilai Trust Score divisualisasikan dengan kelas CSS warna yang sesuai (hijau/kuning/merah).
- [ ] Atribut `enctype="multipart/form-data"` pada form profil jika mendukung upload avatar.
- [ ] Seluruh data teks bebas (bio, review, nama) di-escape dengan `htmlspecialchars()`.

---

## 4. Daftar Pengujian Fungsional UI (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Tampilan |
|---|---|---|---|
| TC-USR-V01 | Profil Publik | Buka profil publik user lain | Tampil nama, bio, trust score, ulasan; nomor telepon tidak ada |
| TC-USR-V02 | Lencana Verifikasi | Buka profil user dengan `is_verified = true` | Lencana centang biru "Terverifikasi" muncul di samping nama |
| TC-USR-V03 | Trust Score Rendah | Buka profil user dengan Trust Score 65 | Indikator skor berwarna merah dengan label peringatan skor rendah |
| TC-USR-V04 | Edit Profil | Buka halaman `my_profile.view.php` | Input terisi data akun sendiri, input email berstatus readonly |
| TC-USR-V05 | Empty Review | User baru belum memiliki ulasan sama sekali | Muncul tulisan "Belum ada ulasan untuk pengguna ini" |

