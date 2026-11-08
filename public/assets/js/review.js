/**
 * PINJAMORANG - Review & Star Rating Interactive Script
 * File: public/assets/js/review.js
 * Deskripsi: Menangani hover, klik, dan update label rating bintang secara real-time.
 */

document.addEventListener('DOMContentLoaded', () => {
    const starItems = document.querySelectorAll('#starContainer .star-item');
    const inputRating = document.getElementById('inputRatingValue');
    const ratingLabel = document.getElementById('ratingLabel');

    if (!starItems.length || !inputRating || !ratingLabel) return;

    let selectedRating = parseInt(inputRating.value, 10) || 5;

    const labels = {
        1: '1 Bintang - Sangat Mengecewakan',
        2: '2 Bintang - Kurang Memuaskan',
        3: '3 Bintang - Cukup Baik',
        4: '4 Bintang - Sesi Sangat Menyenangkan',
        5: '5 Bintang - Pengalaman Luar Biasa!'
    };

    function renderStars(rating) {
        starItems.forEach((star) => {
            const val = parseInt(star.getAttribute('data-value'), 10);
            if (val <= rating) {
                star.style.color = '#f59e0b';
                star.textContent = '★';
            } else {
                star.style.color = '#cbd5e1';
                star.textContent = '☆';
            }
        });
        ratingLabel.textContent = labels[rating] || `${rating} Bintang`;
    }

    starItems.forEach((star) => {
        const val = parseInt(star.getAttribute('data-value'), 10);

        star.addEventListener('mouseenter', () => {
            renderStars(val);
        });

        star.addEventListener('click', () => {
            selectedRating = val;
            inputRating.value = val;
            renderStars(selectedRating);
        });
    });

    const starContainer = document.getElementById('starContainer');
    if (starContainer) {
        starContainer.addEventListener('mouseleave', () => {
            renderStars(selectedRating);
        });
    }

    // Inisialisasi awal (default 5)
    renderStars(selectedRating);
});
