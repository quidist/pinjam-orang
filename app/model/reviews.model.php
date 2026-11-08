<?php

class ReviewsModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * 3.1. createReview: Menyimpan review baru pasca-sesi
     */
    public function createReview(PDO $pdo, array $data): int|false {
        $stmt = $this->pdo->prepare("
            INSERT INTO reviews (
                booking_id, reviewer_id, reviewed_user_id, rating, comment
            ) VALUES (
                :booking_id, :reviewer_id, :reviewed_user_id, :rating, :comment
            )
        ");

        $success = $stmt->execute([
            ':booking_id'        => $data['booking_id'],
            ':reviewer_id'       => $data['reviewer_id'],
            ':reviewed_user_id'  => $data['reviewed_user_id'],
            ':rating'            => max(1, min(5, (int)$data['rating'])),
            ':comment'           => $data['comment'] ?? null
        ]);

        if (!$success) {
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * 3.2. getReviewsByUserId: Mengambil ulasan yang diterima oleh seorang user
     */
    public function getReviewsByUserId(PDO $pdo, int $userId): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                r.*,
                reviewer.name AS reviewer_name,
                reviewer.avatar_url AS reviewer_avatar,
                reviewer.trust_score AS reviewer_trust_score
            FROM reviews r
            JOIN users reviewer ON r.reviewer_id = reviewer.id
            WHERE r.reviewed_user_id = :user_id
            ORDER BY r.created_at DESC
        ");

        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3.3. getUserAverageRating: Menghitung rata-rata bintang pengguna
     */
    public function getUserAverageRating(PDO $pdo, int $userId): float {
        $stmt = $this->pdo->prepare("
            SELECT AVG(rating) AS avg_rating
            FROM reviews
            WHERE reviewed_user_id = :user_id
        ");

        $stmt->execute([':user_id' => $userId]);
        $val = $stmt->fetchColumn();
        return $val !== false && $val !== null ? round((float)$val, 1) : 0.0;
    }

    /**
     * 3.4. hasReviewedBooking: Cek apakah booking sudah pernah direview
     */
    public function hasReviewedBooking(PDO $pdo, int $bookingId): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM reviews 
            WHERE booking_id = :booking_id
        ");

        $stmt->execute([':booking_id' => $bookingId]);
        return (int)$stmt->fetchColumn() > 0;
    }
}
