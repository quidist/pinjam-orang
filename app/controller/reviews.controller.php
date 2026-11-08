<?php

require_once __DIR__ . "/../model/reviews.model.php";
require_once __DIR__ . "/../model/bookings.model.php";
require_once __DIR__ . "/../model/users.model.php";
require_once __DIR__ . "/auth.controller.php";

class ReviewsController {
    private PDO $pdo;
    private ReviewsModel $reviewModel;
    private BookingsModel $bookingModel;
    private UserModel $userModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->reviewModel = new ReviewsModel($pdo);
        $this->bookingModel = new BookingsModel($pdo);
        $this->userModel = new UserModel($pdo);
    }

    /**
     * 3.1. showReviewForm: Menampilkan formulir ulasan pasca-sesi
     */
    public function showReviewForm(): void {
        AuthController::requireAuth();

        $participantId = (int)$_SESSION['user']['id'];
        $bookingId = (int)($_GET['booking_id'] ?? 0);

        $booking = $this->bookingModel->getBookingById($this->pdo, $bookingId);
        if (!$booking || (int)$booking['participant_id'] !== $participantId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Data reservasi tidak ditemukan atau Anda tidak berwenang.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        if ($booking['status'] !== 'COMPLETED') {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Ulasan hanya dapat diberikan setelah sesi berstatus COMPLETED (selesai).'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        if ($this->reviewModel->hasReviewedBooking($this->pdo, $bookingId)) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Anda telah memberikan ulasan untuk sesi ini sebelumnya.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        $pageTitle = "Beri Ulasan Pengalaman - PINJAMORANG";
        $extraScript = "/assets/js/review.js";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/reviews/review_form.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.2. submitReview: Menyimpan ulasan & memberikan reward +5 Trust Score ke Host & Participant
     */
    public function submitReview(): void {
        AuthController::requireAuth();

        $participantId = (int)$_SESSION['user']['id'];
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');

        if ($rating < 1 || $rating > 5) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Rating bintang harus antara 1 sampai 5.'];
            header("Location: /index.php?page=review&booking_id=" . $bookingId);
            exit;
        }

        if (strlen($comment) > 1000) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Komentar ulasan maksimal 1000 karakter.'];
            header("Location: /index.php?page=review&booking_id=" . $bookingId);
            exit;
        }

        $booking = $this->bookingModel->getBookingById($this->pdo, $bookingId);
        if (!$booking || (int)$booking['participant_id'] !== $participantId || $booking['status'] !== 'COMPLETED') {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Sesi tidak valid untuk diulas.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        if ($this->reviewModel->hasReviewedBooking($this->pdo, $bookingId)) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Anda telah memberikan ulasan sebelumnya.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // 1. Simpan Ulasan
            $this->reviewModel->createReview($this->pdo, [
                'booking_id'       => $bookingId,
                'reviewer_id'      => $participantId,
                'reviewed_user_id' => (int)$booking['host_id'],
                'rating'           => $rating,
                'comment'          => !empty($comment) ? $comment : null
            ]);

            // 2. Beri Reward Kehadiran (+5 Trust Score) ke Host & Peserta
            $this->userModel->updateTrustScore($this->pdo, (int)$booking['host_id'], 5);
            $this->userModel->updateTrustScore($this->pdo, $participantId, 5);

            $this->pdo->commit();

            $_SESSION['flash_message'] = [
                'type' => 'success', 
                'text' => 'Ulasan berhasil dikirim! Anda dan Host masing-masing mendapatkan +5 poin Trust Score.'
            ];
            header("Location: /index.php?page=my-bookings");
            exit;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Gagal menyimpan ulasan. Silakan coba kembali.'];
            header("Location: /index.php?page=review&booking_id=" . $bookingId);
            exit;
        }
    }
}
