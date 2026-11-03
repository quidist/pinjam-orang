# PRD: reviews.controller.php

Dokumen Product Requirement Document (PRD) teknis untuk implementasi controller ulasan, rating & karma (*Reviews & Reputation System - Fase 3 Roadmap*) pada sistem **PINJAMORANG**.

---

## 1. Deskripsi & Tanggung Jawab
- **Tujuan File:** Mengelola pemberian review dan rating (1-5 bintang) oleh peserta kepada host setelah sesi pengalaman selesai, penyesuaian Trust Score atas kehadiran, serta tampilan riwayat ulasan pada profil pengguna.
- **Letak File Target:** `app/controller/reviews.controller.php`
- **Model Terkait:** `app/model/reviews.model.php`, `app/model/bookings.model.php`, `app/model/users.model.php`.
- **Ketergantungan:** Guard `requireAuth()` dari `auth.controller.php`.

---

## 2. Alur Kerja & Endpoints / Aksi

| Aksi | Method HTTP | Deskripsi | Input Utama | Output / Redirect |
|---|---|---|---|---|
| `showReviewForm` | GET | Menampilkan formulir review untuk sesi yang telah selesai | `booking_id` | Render view `reviews/create.php` |
| `submitReview` | POST | Memvalidasi dan menyimpan ulasan + rating | `booking_id`, `rating`, `comment` | Simpan review, update Trust Score, redirect ke profil/riwayat |
| `userReviews` | GET | Menampilkan daftar seluruh review yang diterima seorang user | `user_id` | Render view `reviews/user_reviews.php` |

---

## 3. Spesifikasi Fungsi yang Harus Dibuat

### 3.1. `reviewCreate(PDO $pdo, int $bookingId, int $reviewerId, array $postData): array`
- **Kegunaan:** Memvalidasi dan menyimpan ulasan baru dari participant untuk host.
- **Validasi Bisnis:**
  1. *Status Booking Check:* Booking harus berstatus `COMPLETED`.
  2. *Participant Verification:* Reviewer harus merupakan participant dari booking tersebut.
  3. *Single Review Rule:* Booking belum pernah direview sebelumnya (`hasReviewedBooking == false`).
  4. *Rating Range:* Nilai rating wajib berupa bilangan bulat antara 1 sampai 5.
- **Efek Reputasi (Anti-Ghosting & Reward):**
  - Penyelesaian sesi dengan sukses memberikan bonus Trust Score (+5 poin) ke kedua belah pihak atas kepatuhan kehadiran.
- **Nilai Kembalian:** `['success' => bool, 'message' => string, 'errors' => array]`.

### 3.2. `reviewListByUser(PDO $pdo, int $userId): array`
- **Kegunaan:** Menyiapkan data ulasan dan statistik rating untuk ditampilkan pada profil publik pengguna.
- **Nilai Kembalian:** Array `['reviews' => array, 'average_rating' => float, 'total_reviews' => int]`.

---

## 4. Checklist Implementasi
- [ ] Validasi range rating 1 - 5 bintang secara ketat.
- [ ] Mencegah duplikasi review pada 1 booking yang sama.
- [ ] Memastikan sanitasi input teks komentar (`htmlspecialchars` / strip tags) untuk mencegah XSS.
- [ ] Integrasi penambahan Trust Score (+5 poin) setelah sesi berhasil diselesaikan.

---

## 5. Daftar Pengujian (Test Cases)

| ID Tes | Kategori | Skenario Pengujian | Ekspektasi Hasil |
|---|---|---|---|
| TC-RVC-01 | Happy Path | Mengirim review dengan rating 5 dan komentar pada booking COMPLETED | Review tersimpan, Trust Score terupdate |
| TC-RVC-02 | Validasi | Mengirim review dengan rating 0 atau 7 | Ditolak, error "Rating harus bernilai antara 1 sampai 5" |
| TC-RVC-03 | Validasi | Mengirim review pada booking yang statusnya masih PENDING / CONFIRMED | Ditolak, error "Ulasan hanya dapat diberikan setelah sesi selesai" |
| TC-RVC-04 | Otorisasi | User yang tidak berpartisipasi dalam booking mencoba mengirim review | Ditolak (403 Unauthorized) |
| TC-RVC-05 | Constraint | Mengirim review untuk kedua kalinya pada booking yang sama | Ditolak, error "Booking ini sudah pernah diulas" |

