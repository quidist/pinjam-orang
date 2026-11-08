<?php

class PaymentsModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * 3.1. createPayment: Membuat baris transaksi pembayaran baru
     */
    public function createPayment(PDO $pdo, array $data): int|false {
        $stmt = $this->pdo->prepare("
            INSERT INTO payments (
                booking_id, amount, payment_method, transaction_ref, payment_status
            ) VALUES (
                :booking_id, :amount, :payment_method, :transaction_ref, :payment_status
            )
        ");

        $success = $stmt->execute([
            ':booking_id'       => $data['booking_id'],
            ':amount'           => $data['amount'],
            ':payment_method'   => $data['payment_method'] ?? 'QRIS',
            ':transaction_ref'  => $data['transaction_ref'] ?? ('INV-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6))),
            ':payment_status'   => $data['payment_status'] ?? 'PENDING'
        ]);

        if (!$success) {
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * 3.2. getPaymentByBookingId: Mengambil data pembayaran berdasarkan booking_id
     */
    public function getPaymentByBookingId(PDO $pdo, int $bookingId): array|false {
        $stmt = $this->pdo->prepare("
            SELECT * FROM payments 
            WHERE booking_id = :booking_id
        ");

        $stmt->execute([':booking_id' => $bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * getPaymentById: Mengambil data pembayaran berdasarkan ID
     */
    public function getPaymentById(PDO $pdo, int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT 
                p.*,
                b.listing_id,
                b.participant_id,
                b.slot_count,
                l.title AS listing_title,
                l.listing_date,
                l.start_time,
                l.end_time,
                l.session_type,
                l.location,
                host.name AS host_name
            FROM payments p
            JOIN bookings b ON p.booking_id = b.id
            JOIN listings l ON b.listing_id = l.id
            JOIN users host ON l.host_id = host.id
            WHERE p.id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3.3. getPaymentByRef: Mengambil data pembayaran berdasarkan transaction_ref
     */
    public function getPaymentByRef(PDO $pdo, string $transactionRef): array|false {
        $stmt = $this->pdo->prepare("
            SELECT * FROM payments 
            WHERE transaction_ref = :ref
        ");

        $stmt->execute([':ref' => $transactionRef]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3.4. markAsPaid: Mengubah status pembayaran menjadi PAID dan mengisi paid_at
     */
    public function markAsPaid(PDO $pdo, int $paymentId, ?string $paidAt = null): bool {
        $stmt = $this->pdo->prepare("
            UPDATE payments 
            SET payment_status = 'PAID',
                paid_at = :paid_at
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'      => $paymentId,
            ':paid_at' => $paidAt ?? date('Y-m-d H:i:s')
        ]);
    }

    /**
     * 3.5. updatePaymentStatus: Mengubah status pembayaran umum
     */
    public function updatePaymentStatus(PDO $pdo, int $paymentId, string $status): bool {
        $allowed = ['PENDING', 'PAID', 'FAILED', 'EXPIRED', 'REFUNDED'];
        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE payments 
            SET payment_status = :status 
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'     => $paymentId,
            ':status' => $status
        ]);
    }
}
