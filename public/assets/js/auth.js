/**
 * PINJAMORANG - Authentication Interactive Client Script
 * File: public/assets/js/auth.js
 * Deskripsi: Menangani intip kata sandi, meteran kekuatan kata sandi, dan validasi kecocokan konfirmasi sandi.
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // 1. Toggle Intip Kata Sandi (Show / Hide Password)
    // -------------------------------------------------------------------------
    document.querySelectorAll('.btn-toggle-password').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);

            if (targetInput) {
                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    btn.textContent = '🔒';
                } else {
                    targetInput.type = 'password';
                    btn.textContent = '👁️';
                }
            }
        });
    });

    // -------------------------------------------------------------------------
    // 2. Real-Time Password Strength Meter
    // -------------------------------------------------------------------------
    const regPassword = document.getElementById('regPassword');
    const strengthBar = document.getElementById('strengthBar');

    if (regPassword && strengthBar) {
        regPassword.addEventListener('input', () => {
            const val = regPassword.value;
            let score = 0;

            if (val.length >= 6) score += 25;
            if (val.length >= 10) score += 25;
            if (/[0-9]/.test(val)) score += 25;
            if (/[^A-Za-z0-9]/.test(val)) score += 25;

            strengthBar.style.width = score + '%';

            if (score <= 25) {
                strengthBar.style.background = '#dc2626'; // Lemah
            } else if (score <= 50) {
                strengthBar.style.background = '#d97706'; // Cukup
            } else if (score <= 75) {
                strengthBar.style.background = '#0284c7'; // Kuat
            } else {
                strengthBar.style.background = '#059669'; // Sangat Kuat
            }
        });
    }

    // -------------------------------------------------------------------------
    // 3. Real-Time Password Confirmation Match Validator
    // -------------------------------------------------------------------------
    const regPasswordConfirm = document.getElementById('regPasswordConfirm');
    const passwordMatchMsg = document.getElementById('passwordMatchMsg');

    if (regPassword && regPasswordConfirm && passwordMatchMsg) {
        function checkMatch() {
            if (!regPasswordConfirm.value) {
                passwordMatchMsg.textContent = '';
                return;
            }

            if (regPassword.value === regPasswordConfirm.value) {
                passwordMatchMsg.textContent = '✓ Kata sandi cocok';
                passwordMatchMsg.style.color = '#059669';
            } else {
                passwordMatchMsg.textContent = '✕ Kata sandi belum cocok';
                passwordMatchMsg.style.color = '#dc2626';
            }
        }

        regPassword.addEventListener('input', checkMatch);
        regPasswordConfirm.addEventListener('input', checkMatch);
    }
});
