<div class="catalog-section" style="padding-top: 40px; max-width: 480px; margin: 0 auto;">
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 18px; padding: 36px 32px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
        
        <!-- Header Brand -->
        <div style="text-align: center; margin-bottom: 24px;">
            <div class="brand-mark" style="margin: 0 auto 12px; width: 44px; height: 44px; font-size: 24px;">
                <span>P</span>
            </div>
            <h1 style="font-family: var(--font-display); font-size: 28px; color: var(--color-slate-deep); margin: 0 0 6px;">
                Daftar Akun Baru
            </h1>
            <p style="font-size: 13px; color: var(--color-muted); margin: 0;">
                Mulai pinjam waktu, berbagi cerita, dan bangun reputasi Anda.
            </p>
        </div>

        <!-- Ringkasan Error Validasi -->
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" style="margin-bottom: 20px;">
                <ul style="padding-left: 18px; margin: 0; font-size: 12px;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Formulir Pendaftaran -->
        <form id="formRegister" method="POST" action="/index.php?page=register" novalidate>
            <!-- Nama Lengkap -->
            <div style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Nama Lengkap <span style="color: #dc2626;">*</span>
                </label>
                <input 
                    type="text" 
                    id="regName" 
                    name="name" 
                    value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>" 
                    placeholder="Nama lengkap Anda" 
                    minlength="3" 
                    maxlength="100" 
                    required 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px 14px; font-size: 14px;"
                >
            </div>

            <!-- Email -->
            <div style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Alamat Email <span style="color: #dc2626;">*</span>
                </label>
                <input 
                    type="email" 
                    id="regEmail" 
                    name="email" 
                    value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>" 
                    placeholder="contoh@domain.com" 
                    required 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px 14px; font-size: 14px;"
                >
            </div>

            <!-- Password Baru -->
            <div style="margin-bottom: 16px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Kata Sandi <span style="color: #dc2626;">*</span>
                </label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        id="regPassword" 
                        name="password" 
                        placeholder="Minimal 6 karakter" 
                        minlength="6" 
                        required 
                        style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px 42px 11px 14px; font-size: 14px;"
                    >
                    <button 
                        type="button" 
                        class="btn-toggle-password" 
                        data-target="regPassword" 
                        title="Lihat / Sembunyikan Sandi"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 16px; background: none; border: none; cursor: pointer;"
                    >
                        👁️
                    </button>
                </div>
                <!-- Strength Meter -->
                <div id="passwordStrengthMeter" style="height: 4px; background: #e2e8f0; border-radius: 4px; margin-top: 6px; overflow: hidden;">
                    <div id="strengthBar" style="height: 100%; width: 0; transition: width 0.3s ease, background 0.3s ease;"></div>
                </div>
            </div>

            <!-- Konfirmasi Password -->
            <div style="margin-bottom: 24px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Ulangi Kata Sandi <span style="color: #dc2626;">*</span>
                </label>
                <input 
                    type="password" 
                    id="regPasswordConfirm" 
                    name="password_confirm" 
                    placeholder="Ulangi kata sandi" 
                    required 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px 14px; font-size: 14px;"
                >
                <small id="passwordMatchMsg" style="font-size: 11px; display: block; margin-top: 4px;"></small>
            </div>

            <button type="submit" id="btnSubmitRegister" class="button button-primary" style="width: 100%; min-height: 46px; font-size: 14px;">
                Daftar Akun Baru
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--color-muted);">
            Sudah punya akun? 
            <a href="/index.php?page=login" style="color: var(--color-primary); font-weight: 700; text-decoration: none;">
                Masuk di sini
            </a>
        </div>
    </div>
</div>
