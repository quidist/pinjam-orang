<div class="catalog-section" style="padding-top: 50px; max-width: 460px; margin: 0 auto;">
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 18px; padding: 36px 32px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
        
        <!-- Header Brand -->
        <div style="text-align: center; margin-bottom: 28px;">
            <div class="brand-mark" style="margin: 0 auto 12px; width: 44px; height: 44px; font-size: 24px;">
                <span>P</span>
            </div>
            <h1 style="font-family: var(--font-display); font-size: 28px; color: var(--color-slate-deep); margin: 0 0 6px;">
                Masuk ke Akun
            </h1>
            <p style="font-size: 13px; color: var(--color-muted); margin: 0;">
                Selamat datang kembali di komunitas PINJAMORANG.
            </p>
        </div>

        <!-- Banner Error Login -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert" style="margin-bottom: 20px;">
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <!-- Formulir Login -->
        <form id="formLogin" method="POST" action="/index.php?page=login" novalidate>
            <!-- Email -->
            <div style="margin-bottom: 18px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Alamat Email <span style="color: #dc2626;">*</span>
                </label>
                <input 
                    type="email" 
                    id="loginEmail" 
                    name="email" 
                    value="<?= htmlspecialchars($oldInput['email'] ?? '') ?>" 
                    placeholder="nama@email.com" 
                    required 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 12px 14px; font-size: 14px;"
                >
            </div>

            <!-- Password -->
            <div style="margin-bottom: 24px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Kata Sandi <span style="color: #dc2626;">*</span>
                </label>
                <div style="position: relative;">
                    <input 
                        type="password" 
                        id="loginPassword" 
                        name="password" 
                        placeholder="Masukkan kata sandi Anda" 
                        required 
                        style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 12px 42px 12px 14px; font-size: 14px;"
                    >
                    <button 
                        type="button" 
                        class="btn-toggle-password" 
                        data-target="loginPassword" 
                        title="Lihat / Sembunyikan Sandi"
                        style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 16px; background: none; border: none; cursor: pointer;"
                    >
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" id="btnSubmitLogin" class="button button-primary" style="width: 100%; min-height: 46px; font-size: 14px;">
                Masuk ke Akun
            </button>
        </form>

        <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--color-muted);">
            Belum punya akun? 
            <a href="/index.php?page=register" style="color: var(--color-primary); font-weight: 700; text-decoration: none;">
                Daftar Sekarang
            </a>
        </div>
    </div>
</div>
