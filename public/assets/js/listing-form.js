/**
 * PINJAMORANG - Listing Form Interactive Client Script
 * File: public/assets/js/listing-form.js
 * Deskripsi: Menangani dynamic venue switcher, auto-duration calculation, dan toggle gratis.
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // 1. Dynamic Venue Switcher (Online vs Offline)
    // -------------------------------------------------------------------------
    const radioOnline = document.getElementById('radioOnline');
    const radioOffline = document.getElementById('radioOffline');
    const offlineWrapper = document.getElementById('offlineLocationWrapper');
    const inputLocation = document.getElementById('inputLocation');

    function toggleVenue() {
        if (!offlineWrapper) return;
        if (radioOffline && radioOffline.checked) {
            offlineWrapper.style.display = 'block';
            if (inputLocation) inputLocation.required = true;
        } else {
            offlineWrapper.style.display = 'none';
            if (inputLocation) inputLocation.required = false;
        }
    }

    if (radioOnline && radioOffline) {
        radioOnline.addEventListener('change', toggleVenue);
        radioOffline.addEventListener('change', toggleVenue);
        toggleVenue(); // Inisialisasi awal saat load
    }

    // -------------------------------------------------------------------------
    // 2. Auto Duration Calculation
    // -------------------------------------------------------------------------
    const inputStartTime = document.getElementById('inputStartTime');
    const inputEndTime = document.getElementById('inputEndTime');
    const durationBadge = document.getElementById('durationBadge');

    function updateDuration() {
        if (!inputStartTime || !inputEndTime || !durationBadge) return;

        const start = inputStartTime.value;
        const end = inputEndTime.value;

        if (!start || !end) {
            durationBadge.textContent = '';
            return;
        }

        const [startH, startM] = start.split(':').map(Number);
        const [endH, endM] = end.split(':').map(Number);

        const startMinutes = startH * 60 + startM;
        const endMinutes = endH * 60 + endM;

        const diff = endMinutes - startMinutes;

        if (diff <= 0) {
            durationBadge.textContent = '⚠️ Jam selesai harus lebih besar dari jam mulai!';
            durationBadge.style.color = '#dc2626';
        } else {
            const hours = Math.floor(diff / 60);
            const mins = diff % 60;
            let durationText = `${diff} menit`;
            if (hours > 0 && mins > 0) {
                durationText = `${hours} jam ${mins} menit (${diff} menit)`;
            } else if (hours > 0 && mins === 0) {
                durationText = `${hours} jam (${diff} menit)`;
            }
            durationBadge.textContent = `⏱️ Perkiraan durasi: ${durationText}`;
            durationBadge.style.color = 'var(--color-primary-dark)';
        }
    }

    if (inputStartTime && inputEndTime) {
        inputStartTime.addEventListener('change', updateDuration);
        inputEndTime.addEventListener('change', updateDuration);
        updateDuration();
    }

    // -------------------------------------------------------------------------
    // 3. Toggle Biaya Gratis
    // -------------------------------------------------------------------------
    const chkIsFree = document.getElementById('chkIsFree');
    const inputPrice = document.getElementById('inputPrice');

    function toggleFreePrice() {
        if (!chkIsFree || !inputPrice) return;
        if (chkIsFree.checked) {
            inputPrice.value = 0;
            inputPrice.readOnly = true;
            inputPrice.style.background = '#f1f5f9';
        } else {
            inputPrice.readOnly = false;
            inputPrice.style.background = 'white';
            if (Number(inputPrice.value) === 0) {
                inputPrice.value = '';
                inputPrice.focus();
            }
        }
    }

    if (chkIsFree && inputPrice) {
        chkIsFree.addEventListener('change', toggleFreePrice);
        toggleFreePrice();
    }
});
