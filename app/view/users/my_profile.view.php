<div class="catalog-section" style="padding-top: 40px; max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 28px;">
        <span class="section-kicker">Pengaturan Akun</span>
        <h1 style="font-family: var(--font-display); font-size: 34px; color: var(--color-slate-deep); margin-top: 6px;">
            Profil & Keamanan Saya
        </h1>
        <p style="font-size: 14px; color: var(--color-muted); margin-top: 4px;">
            Kelola data pribadi, reputasi komunitas, dan keamanan kata sandi Anda.
        </p>
    </div>

    <!-- KARTU STATUS REPUTASI (TRUST SCORE) -->
    <?php
        $trust = (int)($user['trust_score'] ?? 100);
        $scoreBg = $trust >= 90 ? '#ecfdf5' : ($trust >= 70 ? '#fffbeb' : '#fef2f2');
        $scoreColor = $trust >= 90 ? '#059669' : ($trust >= 70 ? '#d97706' : '#dc2626');
    ?>
    <div style="background: <?= $scoreBg ?>; border-radius: 16px; padding: 24px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; gap: 20px; flex-wrap: wrap;">
        <div>
            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: <?= $scoreColor ?>; letter-spacing: 0.05em;">
                Reputasi Akun Anda
            </span>
            <div style="font-size: 26px; font-weight: 800; color: <?= $scoreColor ?>; margin: 4px 0;">
                ⭐ <?= $trust ?> Poin Trust Score
            </div>
            <p style="font-size: 12px; color: #475569; margin: 0;">
                Skor dibangun dari kedisiplinan hadir tepat waktu dan saling menghormati batasan interaksi.
            </p>
        </div>
        <a href="/index.php?page=profile&id=<?= (int)$user['id'] ?>" class="button button-outline" style="border-color: <?= $scoreColor ?>; color: <?= $scoreColor ?>;">
            Lihat Tampilan Publik &rarr;
        </a>
    </div>

    <!-- FORM 1: DATA PROFIL -->
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 32px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03); margin-bottom: 30px;">
        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-slate-deep); margin-bottom: 20px;">
            Informasi Pribadi
        </h3>

        <form method="POST" action="/index.php?page=update-profile">
            <!-- Nama -->
            <div style="margin-bottom: 18px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Nama Lengkap <span style="color: #dc2626;">*</span>
                </label>
                <input 
                    type="text" 
                    name="name" 
                    value="<?= htmlspecialchars($user['name']) ?>" 
                    required 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                >
            </div>

            <!-- Email (Readonly) -->
            <div style="margin-bottom: 18px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Alamat Email (Terkunci)
                </label>
                <input 
                    type="email" 
                    value="<?= htmlspecialchars($user['email']) ?>" 
                    readonly 
                    disabled 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px; background: #f8fafc; color: var(--color-muted);"
                >
                <small style="color: var(--color-muted); font-size: 11px; margin-top: 4px; display: block;">Email terikat ke akun dan tidak dapat diubah secara bebas.</small>
            </div>

            <!-- Nomor Telepon -->
            <div style="margin-bottom: 18px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Nomor Telepon / WhatsApp
                </label>
                <input 
                    type="tel" 
                    name="phone" 
                    value="<?= htmlspecialchars($user['phone'] ?? '') ?>" 
                    placeholder="Contoh: 081234567890" 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                >
                <small style="color: #059669; font-size: 11px; margin-top: 4px; display: block;">
                    🛡️ Nomor Anda privat, aman, dan tidak pernah ditampilkan ke publik.
                </small>
            </div>

            <!-- Avatar URL -->
            <div style="margin-bottom: 18px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Tautan Foto Avatar (URL)
                </label>
                <input 
                    type="url" 
                    name="avatar_url" 
                    value="<?= htmlspecialchars($user['avatar_url'] ?? '') ?>" 
                    placeholder="https://..." 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                >
            </div>

            <!-- Bio -->
            <div style="margin-bottom: 24px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Bio & Cerita Singkat Diri
                </label>
                <textarea 
                    name="bio" 
                    rows="3" 
                    maxlength="500" 
                    placeholder="Tuliskan minat, keahlian, topik obrolan favorit, atau apa saja tentang diri Anda..." 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 13px; line-height: 1.6;"
                ><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                <small style="color: var(--color-muted); font-size: 11px; display: block; margin-top: 4px;">Maksimal 500 karakter.</small>
            </div>

            <button type="submit" class="button button-primary">
                Simpan Perubahan Profil
            </button>
        </form>
    </div>

    <!-- FORM 2: GANTI KATA SANDI -->
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 32px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.03);">
        <h3 style="font-size: 18px; font-weight: 700; color: var(--color-slate-deep); margin-bottom: 20px;">
            Keamanan Kata Sandi
        </h3>

        <form method="POST" action="/index.php?page=change-password">
            <div style="margin-bottom: 18px;">
                <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                    Kata Sandi Saat Ini <span style="color: #dc2626;">*</span>
                </label>
                <input 
                    type="password" 
                    name="old_password" 
                    placeholder="Masukkan kata sandi lama Anda" 
                    required 
                    style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                >
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px;">
                <div>
                    <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                        Kata Sandi Baru <span style="color: #dc2626;">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="new_password" 
                        placeholder="Minimal 6 karakter" 
                        minlength="6" 
                        required 
                        style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                    >
                </div>
                <div>
                    <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                        Ulangi Kata Sandi Baru <span style="color: #dc2626;">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="confirm_new_password" 
                        placeholder="Ulangi kata sandi baru" 
                        required 
                        style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 11px; font-size: 14px;"
                    >
                </div>
            </div>

            <button type="submit" class="button button-outline">
                Perbarui Kata Sandi
            </button>
        </form>
    </div>
</div>
