<?php

require_once __DIR__ . "/../model/bookings.model.php";
require_once __DIR__ . "/../model/listings.model.php";
require_once __DIR__ . "/../model/users.model.php";
require_once __DIR__ . "/auth.controller.php";

class BookingsController {
    private PDO $pdo;
    private BookingsModel $bookingModel;
    private ListingsModel $listingModel;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->bookingModel = new BookingsModel($pdo);
        $this->listingModel = new ListingsModel($pdo);
        $this->userModel = new UserModel($pdo);
    }

    /**
     * 3.1. book: Memproses reservasi slot waktu baru oleh participant
     */
    public function book(): void {
        AuthController::requireAuth();

        $participantId = (int)$_SESSION['user']['id'];
        $listingId = (int)($_POST['listing_id'] ?? 0);
        $slotCount = max(1, (int)($_POST['slot_count'] ?? 1));
        $agreed = !empty($_POST['agreed_to_boundaries']) && $_POST['agreed_to_boundaries'] == '1';
        $notes = trim($_POST['notes'] ?? '');

        // 1. Cek Batasan Interaksi (Boundaries Agreement)
        if (!$agreed) {
            $_SESSION['flash_message'] = [
                'type' => 'danger', 
                'text' => 'Anda wajib membaca dan menyetujui aturan batasan interaksi sebelum memesan.'
            ];
            header("Location: /index.php?page=listing-detail&id=" . $listingId);
            exit;
        }

        // 2. Cek Trust Score Peserta (Minimal 70)
        $participant = $this->userModel->getUserById($this->pdo, $participantId);
        if ($participant && (int)$participant['trust_score'] < 70) {
            $_SESSION['flash_message'] = [
                'type' => 'danger', 
                'text' => 'Akun Anda dalam pembatasan karena Trust Score di bawah 70. Tidak dapat melakukan reservasi.'
            ];
            header("Location: /index.php?page=my-profile");
            exit;
        }

        // 3. Cek Keberadaan Listing
        $listing = $this->listingModel->getListingById($this->pdo, $listingId);
        if (!$listing || $listing['status'] !== 'ACTIVE') {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Penawaran waktu tidak tersedia atau telah berakhir.'];
            header("Location: /index.php?page=listings");
            exit;
        }

        // 4. Larangan Self-Booking (Pesan listing milik sendiri)
        if ((int)$listing['host_id'] === $participantId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Anda tidak dapat memesan penawaran waktu milik Anda sendiri.'];
            header("Location: /index.php?page=listing-detail&id=" . $listingId);
            exit;
        }

        // 5. Cek Anti-Double-Booking
        if ($this->bookingModel->hasUserBookedListing($this->pdo, $listingId, $participantId)) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Anda sudah memiliki reservasi aktif pada penawaran ini.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        // 6. Cek Sisa Kapasitas Kuota
        $remaining = $this->listingModel->getRemainingCapacity($this->pdo, $listingId);
        if ($remaining < $slotCount) {
            $_SESSION['flash_message'] = [
                'type' => 'danger', 
                'text' => 'Maaf, sisa kuota yang tersedia hanya ' . $remaining . ' slot.'
            ];
            header("Location: /index.php?page=listing-detail&id=" . $listingId);
            exit;
        }

        // 7. Hitung Biaya & Tentukan Status Awal
        $totalPrice = (float)$listing['price'] * $slotCount;
        $isFree = $totalPrice <= 0;

        if ($isFree && $listing['approval_mode'] === 'AUTOMATIC') {
            $status = 'CONFIRMED';
        } else {
            $status = 'PENDING';
        }

        $bookingId = $this->bookingModel->createBooking($this->pdo, [
            'listing_id'           => $listingId,
            'participant_id'       => $participantId,
            'slot_count'           => $slotCount,
            'total_price'          => $totalPrice,
            'status'               => $status,
            'agreed_to_boundaries' => 1,
            'notes'                => $notes
        ]);

        if (!$bookingId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal membuat reservasi. Silakan coba lagi.'];
            header("Location: /index.php?page=listing-detail&id=" . $listingId);
            exit;
        }

        // Jika Sesi Berbayar -> Arahkan ke Checkout Pembayaran
        if (!$isFree) {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Reservasi dibuat! Silakan selesaikan pembayaran.'];
            header("Location: /index.php?page=checkout&booking_id=" . $bookingId);
            exit;
        }

        // Jika Sesi Gratis
        if ($status === 'CONFIRMED') {
            $_SESSION['flash_message'] = [
                'type' => 'success', 
                'text' => 'Reservasi berhasil dikonfirmasi! Akses sesi kini telah dibuka di Pengalaman Saya.'
            ];
        } else {
            $_SESSION['flash_message'] = [
                'type' => 'success', 
                'text' => 'Pengajuan reservasi berhasil dikirim! Menunggu konfirmasi persetujuan dari Host.'
            ];
        }

        header("Location: /index.php?page=my-bookings");
        exit;
    }

    /**
     * 3.2. myBookings: Riwayat reservasi sesi yang diikuti oleh participant
     */
    public function myBookings(): void {
        AuthController::requireAuth();

        $participantId = (int)$_SESSION['user']['id'];
        $statusFilter = !empty($_GET['status']) ? strtoupper(trim($_GET['status'])) : null;

        $myBookings = $this->bookingModel->getBookingsByParticipant($this->pdo, $participantId, $statusFilter);
        $pageTitle = "Pengalaman Saya - PINJAMORANG";
        $extraScript = "/assets/js/booking.js";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/bookings/my_bookings.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.3. hostRequests: Dashboard peninjauan permintaan masuk untuk host
     */
    public function hostRequests(): void {
        AuthController::requireAuth();

        $hostId = (int)$_SESSION['user']['id'];
        $requests = $this->bookingModel->getHostIncomingRequests($this->pdo, $hostId);
        $pageTitle = "Permintaan Masuk Peserta - PINJAMORANG";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/bookings/host_requests.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.4. approve: Host menyetujui pendaftaran peserta
     */
    public function approve(): void {
        AuthController::requireAuth();

        $hostId = (int)$_SESSION['user']['id'];
        $bookingId = (int)($_POST['booking_id'] ?? 0);

        $booking = $this->bookingModel->getBookingById($this->pdo, $bookingId);
        if (!$booking || (int)$booking['host_id'] !== $hostId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Otorisasi gagal. Anda bukan host dari penawaran ini.'];
            header("Location: /index.php?page=host-requests");
            exit;
        }

        if ($booking['status'] !== 'PENDING') {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Permintaan ini tidak berstatus PENDING.'];
            header("Location: /index.php?page=host-requests");
            exit;
        }

        // Validasi Sisa Kuota Sesi sebelum menyetujui (Cegah Overbooking)
        $remaining = $this->listingModel->getRemainingCapacity($this->pdo, (int)$booking['listing_id']);
        $slotCount = (int)$booking['slot_count'];
        if ($remaining < $slotCount) {
            $_SESSION['flash_message'] = [
                'type' => 'danger', 
                'text' => 'Gagal menyetujui permintaan: Sisa kuota penawaran ini sudah tidak mencukupi (Tersisa: ' . $remaining . ' slot, Permintaan: ' . $slotCount . ' slot).'
            ];
            header("Location: /index.php?page=host-requests");
            exit;
        }

        $this->bookingModel->updateBookingStatus($this->pdo, $bookingId, 'CONFIRMED');
        $_SESSION['flash_message'] = [
            'type' => 'success', 
            'text' => 'Pendaftaran peserta berhasil disetujui! Link pertemuan kini terbuka untuk peserta.'
        ];
        header("Location: /index.php?page=host-requests");
        exit;
    }

    /**
     * 3.5. reject: Host menolak pendaftaran peserta
     */
    public function reject(): void {
        AuthController::requireAuth();

        $hostId = (int)$_SESSION['user']['id'];
        $bookingId = (int)($_POST['booking_id'] ?? 0);

        $booking = $this->bookingModel->getBookingById($this->pdo, $bookingId);
        if (!$booking || (int)$booking['host_id'] !== $hostId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Otorisasi gagal.'];
            header("Location: /index.php?page=host-requests");
            exit;
        }

        $this->bookingModel->updateBookingStatus($this->pdo, $bookingId, 'REJECTED');
        $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Permintaan peserta telah ditolak.'];
        header("Location: /index.php?page=host-requests");
        exit;
    }

    /**
     * 3.6. cancel: Membatalkan reservasi dengan aturan anti-ghosting penalty (-25 poin jika < 2 jam)
     */
    public function cancel(): void {
        AuthController::requireAuth();

        $userId = (int)$_SESSION['user']['id'];
        $bookingId = (int)($_POST['booking_id'] ?? 0);

        $booking = $this->bookingModel->getBookingById($this->pdo, $bookingId);
        if (!$booking || ((int)$booking['participant_id'] !== $userId && (int)$booking['host_id'] !== $userId)) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Anda tidak memiliki hak untuk membatalkan reservasi ini.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        if (!in_array($booking['status'], ['PENDING', 'CONFIRMED'], true)) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Reservasi ini tidak dapat dibatalkan.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        // Cek Anti-Ghosting Penalty jika dibatalkan oleh Participant
        $penaltyApplied = false;
        if ((int)$booking['participant_id'] === $userId && $booking['status'] === 'CONFIRMED') {
            $sessionTime = strtotime($booking['listing_date'] . ' ' . $booking['start_time']);
            $diffSeconds = $sessionTime - time();

            // Jika kurang dari 2 jam (7200 detik) sebelum sesi dimulai
            if ($diffSeconds < 7200) {
                $this->userModel->updateTrustScore($this->pdo, $userId, -25);
                $penaltyApplied = true;
            }
        }

        $this->bookingModel->cancelBooking($this->pdo, $bookingId, $userId);

        if ($penaltyApplied) {
            $_SESSION['flash_message'] = [
                'type' => 'danger', 
                'text' => 'Reservasi dibatalkan. Karena pembatalan dilakukan mendadak (< 2 jam sebelum mulai), Trust Score Anda dipotong 25 poin.'
            ];
        } else {
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Reservasi berhasil dibatalkan.'];
        }

        header("Location: /index.php?page=my-bookings");
        exit;
    }
}
