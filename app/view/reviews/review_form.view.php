<div class="catalog-section" style="padding-top: 40px; max-width: 680px; margin: 0 auto;">
    <div style="font-size: 12px; color: var(--color-muted); margin-bottom: 20px;">
        <a href="/index.php?page=my-bookings" style="color: var(--color-primary); font-weight: 600;">Pengalaman Saya</a>
        <span style="margin: 0 8px;">/</span>
        <span>Beri Ulasan</span>
    </div>

    <div style="margin-bottom: 28px;">
        <span class="section-kicker">Feedback & Reputasi</span>
        <h1 style="font-family: var(--font-display); font-size: 34px; color: var(--color-slate-deep); margin-top: 6px;">
            Bagaimana Momen Kebersamaan Kalian?
        </h1>
        <p style="font-size: 14px; color: var(--color-muted); margin-top: 4px;">
            Ceritakan pengalaman Anda bersama Host untuk membantu anggota komunitas lainnya.
        </p>
    </div>

    <!-- KARTU INFORMASI SESI -->
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 22px; display: flex; gap: 16px; align-items: center; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.03);">
        <img 
            src="<?= !empty($booking['host_avatar']) ? htmlspecialchars($booking['host_avatar']) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?crop=faces&fit=crop&w=100&h=100&q=80' ?>" 
            alt="Foto Host" 
            style="width: 52px; height: 52px; border-radius: 50%; object-fit: cover;"
        >
        <div>
            <span style="font-size: 11px; text-transform: uppercase; color: var(--color-muted); font-weight: 700;">Host Sesi</span>
            <div style="font-size: 16px; font-weight: 700; color: var(--color-slate-deep);">
                <?= htmlspecialchars($booking['host_name'] ?? 'Host') ?>
            </div>
            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">
                <?= htmlspecialchars($booking['listing_title']) ?>
            </div>
        </div>
    </div>

    <!-- FORMULIR ULASAN -->
    <form id="formReview" method="POST" action="/index.php?page=submit-review" style="background: white; border: 1px solid var(--color-border); border-radius: 16px; padding: 32px; box-shadow: 0 10px 30px rgba(15, 23, 42, 0.04);">
        <input type="hidden" name="booking_id" value="<?= (int)$booking['id'] ?>">
        <input type="hidden" id="inputRatingValue" name="rating" value="5">

        <!-- 1. Bintang Interaktif -->
        <div style="text-align: center; margin-bottom: 28px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 12px;">
                Beri Penilaian Bintang
            </label>
            <div id="starContainer" style="display: flex; justify-content: center; gap: 8px; font-size: 40px; cursor: pointer; user-select: none;">
                <span class="star-item" data-value="1" style="color: #f59e0b; transition: transform 0.1s ease;">★</span>
                <span class="star-item" data-value="2" style="color: #f59e0b; transition: transform 0.1s ease;">★</span>
                <span class="star-item" data-value="3" style="color: #f59e0b; transition: transform 0.1s ease;">★</span>
                <span class="star-item" data-value="4" style="color: #f59e0b; transition: transform 0.1s ease;">★</span>
                <span class="star-item" data-value="5" style="color: #f59e0b; transition: transform 0.1s ease;">★</span>
            </div>
            <p id="ratingLabel" style="font-size: 13px; font-weight: 700; color: #b45309; margin-top: 8px;">
                5 Bintang - Pengalaman Luar Biasa!
            </p>
        </div>

        <!-- 2. Komentar Pengalaman -->
        <div style="margin-bottom: 24px;">
            <label style="font-size: 13px; font-weight: 700; color: var(--color-slate-deep); display: block; margin-bottom: 6px;">
                Cerita & Ulasan Pengalaman Anda
            </label>
            <textarea 
                name="comment" 
                rows="4" 
                maxlength="1000" 
                placeholder="Apakah Host ramah dan tepat waktu? Bagaimana jalannya obrolan kalian? Tuliskan ulasan yang jujur..." 
                style="width: 100%; border: 1px solid var(--color-border); border-radius: 8px; padding: 12px; font-size: 13px; line-height: 1.6;"
            ></textarea>
            <small style="color: var(--color-muted); font-size: 11px; display: block; margin-top: 4px;">Maksimal 1000 karakter.</small>
        </div>

        <!-- 3. Banner Anti-Ghosting Reward -->
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 12px; padding: 16px; margin-bottom: 28px; display: flex; gap: 12px; align-items: start;">
            <span style="font-size: 20px;">🎉</span>
            <div style="font-size: 12px; color: #065f46; line-height: 1.6;">
                <strong>Penghargaan Kehadiran Tepat Waktu:</strong>
                <br>
                Terima kasih telah berkomitmen hadir! Pengiriman ulasan ini akan menambahkan <strong>+5 Poin Trust Score</strong> secara otomatis ke akun Anda dan Host.
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div style="display: flex; justify-content: flex-end; gap: 12px; align-items: center;">
            <a href="/index.php?page=my-bookings" class="button button-outline">
                Nanti Saja
            </a>
            <button type="submit" id="btnSubmitReview" class="button button-primary" style="min-width: 200px;">
                Kirim Ulasan & Tambah Karma
            </button>
        </div>
    </form>
</div>
