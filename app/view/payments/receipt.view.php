<div class="catalog-section" style="padding-top: 40px; max-width: 640px; margin: 0 auto; text-align: center;">
    <!-- KARTU BUKTI PEMBAYARAN LUNAS -->
    <div style="background: white; border: 1px solid var(--color-border); border-radius: 20px; padding: 40px 32px; box-shadow: 0 15px 35px rgba(15, 23, 42, 0.05);">
        
        <!-- Icon Success -->
        <div style="width: 72px; height: 72px; background: #ecfdf5; border-radius: 50%; color: #059669; font-size: 36px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
            ✓
        </div>

        <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #059669; letter-spacing: 0.08em; background: #ecfdf5; padding: 4px 12px; border-radius: 99px;">
            LUNAS (PAID)
        </span>

        <h1 style="font-family: var(--font-display); font-size: 28px; color: var(--color-slate-deep); margin: 14px 0 6px;">
            Pembayaran Berhasil!
        </h1>
        <p style="font-size: 13px; color: var(--color-muted); margin-bottom: 28px;">
            Terima kasih! Slot waktu Anda telah resmi diamankan dan siap dihadiri.
        </p>

        <!-- Rincian Transaksi -->
        <div style="background: var(--color-canvas); border-radius: 12px; padding: 20px; text-align: left; margin-bottom: 28px;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 12px;">
                <span style="color: var(--color-muted);">No. Referensi:</span>
                <strong style="font-family: monospace; color: var(--color-slate-deep);"><?= htmlspecialchars($payment['transaction_ref']) ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 12px;">
                <span style="color: var(--color-muted);">Waktu Pembayaran:</span>
                <strong style="color: var(--color-slate-deep);"><?= date('d M Y, H:i', strtotime($payment['paid_at'] ?? 'now')) ?> WIB</strong>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 12px;">
                <span style="color: var(--color-muted);">Metode Bayar:</span>
                <strong style="color: var(--color-slate-deep);"><?= htmlspecialchars($payment['payment_method'] ?? 'QRIS') ?></strong>
            </div>
            <div style="display: flex; justify-content: space-between; padding-top: 12px; border-top: 1px dashed var(--color-border); font-size: 14px;">
                <span style="color: var(--color-slate-deep); font-weight: 700;">Total Dibayar:</span>
                <strong style="color: var(--color-primary); font-size: 18px;">Rp <?= number_format((float)$payment['amount'], 0, ',', '.') ?></strong>
            </div>
        </div>

        <!-- Detail Sesi Pengalaman -->
        <div style="text-align: left; margin-bottom: 28px; padding: 0 4px;">
            <div style="font-size: 11px; text-transform: uppercase; color: var(--color-muted); font-weight: 700; margin-bottom: 4px;">
                Sesi yang Dipesan
            </div>
            <div style="font-size: 15px; font-weight: 700; color: var(--color-slate-deep); margin-bottom: 6px;">
                <?= htmlspecialchars($payment['listing_title']) ?>
            </div>
            <div style="font-size: 12px; color: #475569;">
                📅 <?= date('d M Y', strtotime($payment['listing_date'])) ?> • ⏰ <?= htmlspecialchars(substr($payment['start_time'], 0, 5)) ?> WIB • Host: <?= htmlspecialchars($payment['host_name'] ?? 'Host') ?>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <a href="/index.php?page=my-bookings" class="button button-primary" style="width: 100%;">
            Buka Jadwal di Pengalaman Saya &rarr;
        </a>
    </div>
</div>
