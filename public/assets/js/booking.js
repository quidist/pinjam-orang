/**
 * PINJAMORANG - Booking & Reservation Interactive Client Script
 * File: public/assets/js/booking.js
 * Deskripsi: Menangani modal peringatan pembatalan anti-ghosting dan validasi persetujuan batasan.
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // 1. Modal Pembatalan Reservasi (Peringatan Penalti Trust Score)
    // -------------------------------------------------------------------------
    const modal = document.getElementById('modalCancelBooking');
    const modalBookingId = document.getElementById('modalBookingId');
    const modalBookingTitle = document.getElementById('modalBookingTitle');
    const btnDismiss = document.getElementById('btnDismissCancelModal');

    document.querySelectorAll('.btn-open-cancel-modal').forEach((btn) => {
        btn.addEventListener('click', () => {
            const bookingId = btn.getAttribute('data-id');
            const title = btn.getAttribute('data-title');

            if (modalBookingId) modalBookingId.value = bookingId;
            if (modalBookingTitle) modalBookingTitle.textContent = `"${title}"`;
            if (modal) modal.style.display = 'flex';
        });
    });

    if (btnDismiss && modal) {
        btnDismiss.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    }

    // -------------------------------------------------------------------------
    // 2. Validasi Formulir Pemesanan di Listing Detail
    // -------------------------------------------------------------------------
    const formBooking = document.getElementById('formBooking');
    const chkBoundaries = document.getElementById('chkAgreedBoundaries');

    if (formBooking) {
        formBooking.addEventListener('submit', (e) => {
            if (chkBoundaries && !chkBoundaries.checked) {
                e.preventDefault();
                alert('Anda wajib membaca dan menyetujui aturan batasan interaksi sebelum memesan!');
                chkBoundaries.focus();
            }
        });
    }
});
