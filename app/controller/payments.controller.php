<?php

require_once __DIR__ . "/../model/payments.model.php";
require_once __DIR__ . "/../model/bookings.model.php";
require_once __DIR__ . "/auth.controller.php";

class PaymentsController {
    private PDO $pdo;
    private PaymentsModel $paymentModel;
    private BookingsModel $bookingModel;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        $this->paymentModel = new PaymentsModel($pdo);
        $this->bookingModel = new BookingsModel($pdo);
    }

    /**
     * 3.1. showCheckout: Menampilkan halaman tagihan checkout untuk sesi berbayar
     */
    public function showCheckout(): void {
        AuthController::requireAuth();

        $participantId = (int)$_SESSION['user']['id'];
        $bookingId = (int)($_GET['booking_id'] ?? 0);

        $booking = $this->bookingModel->getBookingById($this->pdo, $bookingId);
        if (!$booking || (int)$booking['participant_id'] !== $participantId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Data tagihan tidak ditemukan atau Anda tidak memiliki akses.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        if ($booking['status'] === 'CONFIRMED') {
            $payment = $this->paymentModel->getPaymentByBookingId($this->pdo, $bookingId);
            if ($payment) {
                header("Location: /index.php?page=payment-receipt&id=" . $payment['id']);
                exit;
            }
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        // Cari atau buat record transaksi pembayaran baru
        $payment = $this->paymentModel->getPaymentByBookingId($this->pdo, $bookingId);
        if (!$payment) {
            $ref = 'INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            $paymentId = $this->paymentModel->createPayment($this->pdo, [
                'booking_id'      => $bookingId,
                'amount'          => $booking['total_price'],
                'payment_method'  => 'QRIS',
                'transaction_ref' => $ref,
                'payment_status'  => 'PENDING'
            ]);
            $payment = $this->paymentModel->getPaymentById($this->pdo, $paymentId);
        }

        $pageTitle = "Checkout Pembayaran - PINJAMORANG";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/payments/checkout.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }

    /**
     * 3.2. confirmPayment: Memproses konfirmasi pelunasan transaksi (Atomic)
     */
    public function confirmPayment(): void {
        AuthController::requireAuth();

        $participantId = (int)$_SESSION['user']['id'];
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $method = trim($_POST['payment_method'] ?? 'QRIS');

        $booking = $this->bookingModel->getBookingById($this->pdo, $bookingId);
        if (!$booking || (int)$booking['participant_id'] !== $participantId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Otorisasi pembayaran gagal.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        $payment = $this->paymentModel->getPaymentByBookingId($this->pdo, $bookingId);
        if (!$payment) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Data tagihan pembayaran tidak ditemukan.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        try {
            $this->pdo->beginTransaction();

            // 1. Update status pembayaran menjadi PAID
            $this->paymentModel->markAsPaid($this->pdo, (int)$payment['id']);

            // 2. Sahkan status booking menjadi CONFIRMED
            $this->bookingModel->updateBookingStatus($this->pdo, $bookingId, 'CONFIRMED');

            $this->pdo->commit();

            $_SESSION['flash_message'] = [
                'type' => 'success', 
                'text' => 'Pembayaran berhasil dikonfirmasi! Jadwal sesi Anda kini telah terkonfirmasi aktif.'
            ];
            header("Location: /index.php?page=payment-receipt&id=" . $payment['id']);
            exit;
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Terjadi kesalahan sistem saat memproses pembayaran.'];
            header("Location: /index.php?page=checkout&booking_id=" . $bookingId);
            exit;
        }
    }

    /**
     * 3.3. receipt: Menampilkan bukti transaksi lunas resmi
     */
    public function receipt(): void {
        AuthController::requireAuth();

        $participantId = (int)$_SESSION['user']['id'];
        $paymentId = (int)($_GET['id'] ?? 0);

        $payment = $this->paymentModel->getPaymentById($this->pdo, $paymentId);
        if (!$payment || (int)$payment['participant_id'] !== $participantId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Bukti transaksi tidak ditemukan.'];
            header("Location: /index.php?page=my-bookings");
            exit;
        }

        $pageTitle = "Bukti Pembayaran #" . htmlspecialchars($payment['transaction_ref']) . " - PINJAMORANG";

        require_once __DIR__ . "/../view/layout/header.view.php";
        require_once __DIR__ . "/../view/payments/receipt.view.php";
        require_once __DIR__ . "/../view/layout/footer.view.php";
    }
}
