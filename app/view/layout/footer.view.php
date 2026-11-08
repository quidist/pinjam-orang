    </main>

    <footer>
        <a class="brand footer-brand" href="/index.php?page=home">
            <span class="brand-mark">
                <span>P</span>
            </span>
            <span class="brand-copy">
                <strong>PINJAMORANG</strong>
                <small>Because not every moment needs a reason.</small>
            </span>
        </a>
        <span>&copy; <?= date('Y') ?> PINJAMORANG. Dibuat untuk pertemuan yang lebih manusiawi.</span>
    </footer>
</div>

<!-- Script Utama Global -->
<script src="/assets/js/main.js"></script>

<!-- Skrip Tambahan Spesifik Halaman -->
<?php if (!empty($extraScript)): ?>
    <script src="<?= htmlspecialchars($extraScript) ?>"></script>
<?php endif; ?>
</body>
</html>
