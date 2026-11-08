<?php

class BookingsModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * 3.1. createBooking: Membuat reservasi slot waktu baru
     */
    public function createBooking(PDO $pdo, array $data): int|false {
        $stmt = $this->pdo->prepare("
            INSERT INTO bookings (
                listing_id, participant_id, slot_count, total_price, 
                status, agreed_to_boundaries, notes
            ) VALUES (
                :listing_id, :participant_id, :slot_count, :total_price, 
                :status, :agreed_to_boundaries, :notes
            )
        ");

        $success = $stmt->execute([
            ':listing_id'           => $data['listing_id'],
            ':participant_id'       => $data['participant_id'],
            ':slot_count'           => $data['slot_count'] ?? 1,
            ':total_price'          => $data['total_price'] ?? 0.00,
            ':status'               => $data['status'] ?? 'PENDING',
            ':agreed_to_boundaries' => !empty($data['agreed_to_boundaries']) ? 1 : 0,
            ':notes'                => $data['notes'] ?? null
        ]);

        if (!$success) {
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * 3.2. getBookingById: Mengambil data booking spesifik dengan JOIN listing & users
     */
    public function getBookingById(PDO $pdo, int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT 
                b.*,
                l.title AS listing_title,
                l.description AS listing_description,
                l.session_type,
                l.listing_date,
                l.start_time,
                l.end_time,
                l.duration_minutes,
                l.location,
                l.host_id,
                l.price AS unit_price,
                host.name AS host_name,
                host.avatar_url AS host_avatar,
                host.trust_score AS host_trust_score,
                part.name AS participant_name,
                part.avatar_url AS participant_avatar,
                part.trust_score AS participant_trust_score
            FROM bookings b
            JOIN listings l ON b.listing_id = l.id
            JOIN users host ON l.host_id = host.id
            JOIN users part ON b.participant_id = part.id
            WHERE b.id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3.3. hasUserBookedListing: Cek apakah user sudah pernah mendaftar aktif di listing ini
     */
    public function hasUserBookedListing(PDO $pdo, int $listingId, int $participantId): bool {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM bookings 
            WHERE listing_id = :listing_id 
              AND participant_id = :participant_id
              AND status NOT IN ('REJECTED', 'CANCELLED')
        ");

        $stmt->execute([
            ':listing_id'     => $listingId,
            ':participant_id' => $participantId
        ]);

        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * 3.4. getBookingsByParticipant: Riwayat sesi yang dipesan oleh seorang peserta
     */
    public function getBookingsByParticipant(PDO $pdo, int $participantId, ?string $status = null): array {
        $sql = "
            SELECT 
                b.*,
                l.title AS listing_title,
                l.session_type,
                l.listing_date,
                l.start_time,
                l.end_time,
                l.duration_minutes,
                l.location,
                l.host_id,
                host.name AS host_name,
                host.avatar_url AS host_avatar,
                host.trust_score AS host_trust_score
            FROM bookings b
            JOIN listings l ON b.listing_id = l.id
            JOIN users host ON l.host_id = host.id
            WHERE b.participant_id = :participant_id
        ";

        $params = [':participant_id' => $participantId];

        if (!empty($status)) {
            $sql .= " AND b.status = :status";
            $params[':status'] = $status;
        }

        $sql .= " ORDER BY l.listing_date DESC, l.start_time DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3.5. getBookingsByListing: Daftar peserta pendaftar pada suatu listing
     */
    public function getBookingsByListing(PDO $pdo, int $listingId): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                b.*,
                part.name AS participant_name,
                part.avatar_url AS participant_avatar,
                part.trust_score AS participant_trust_score,
                part.is_verified AS participant_is_verified
            FROM bookings b
            JOIN users part ON b.participant_id = part.id
            WHERE b.listing_id = :listing_id
            ORDER BY b.created_at DESC
        ");

        $stmt->execute([':listing_id' => $listingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * getHostIncomingRequests: Mengambil semua pendaftaran yang masuk untuk seluruh listing milik host
     */
    public function getHostIncomingRequests(PDO $pdo, int $hostId): array {
        $stmt = $this->pdo->prepare("
            SELECT 
                b.*,
                l.title AS listing_title,
                l.listing_date,
                l.start_time,
                l.end_time,
                l.session_type,
                part.name AS participant_name,
                part.avatar_url AS participant_avatar,
                part.trust_score AS participant_trust_score,
                part.is_verified AS participant_is_verified
            FROM bookings b
            JOIN listings l ON b.listing_id = l.id
            JOIN users part ON b.participant_id = part.id
            WHERE l.host_id = :host_id
            ORDER BY b.created_at DESC
        ");

        $stmt->execute([':host_id' => $hostId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3.6. updateBookingStatus: Mengubah status booking
     */
    public function updateBookingStatus(PDO $pdo, int $id, string $newStatus): bool {
        $allowed = ['PENDING', 'CONFIRMED', 'REJECTED', 'CANCELLED', 'COMPLETED'];
        if (!in_array($newStatus, $allowed, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE bookings 
            SET status = :status 
            WHERE id = :id
        ");

        return $stmt->execute([
            ':id'     => $id,
            ':status' => $newStatus
        ]);
    }

    /**
     * 3.7. cancelBooking: Membatalkan booking dengan verifikasi hak milik
     */
    public function cancelBooking(PDO $pdo, int $id, int $userId): bool {
        $stmt = $this->pdo->prepare("
            UPDATE bookings b
            JOIN listings l ON b.listing_id = l.id
            SET b.status = 'CANCELLED'
            WHERE b.id = :id 
              AND (b.participant_id = :user_id OR l.host_id = :user_id)
              AND b.status IN ('PENDING', 'CONFIRMED')
        ");

        $success = $stmt->execute([
            ':id'      => $id,
            ':user_id' => $userId
        ]);

        return $success && $stmt->rowCount() > 0;
    }
}
