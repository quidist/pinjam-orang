<?php

class ListingsModel {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * 3.1. createListing: Membuat penawaran waktu baru
     */
    public function createListing(PDO $pdo, array $data): int|false {
        $stmt = $this->pdo->prepare("
            INSERT INTO listings (
                host_id, title, description, session_type, listing_date, 
                start_time, end_time, duration_minutes, capacity, price, 
                location, approval_mode, boundaries, status
            ) VALUES (
                :host_id, :title, :description, :session_type, :listing_date, 
                :start_time, :end_time, :duration_minutes, :capacity, :price, 
                :location, :approval_mode, :boundaries, :status
            )
        ");

        $success = $stmt->execute([
            ':host_id'          => $data['host_id'],
            ':title'            => $data['title'],
            ':description'      => $data['description'],
            ':session_type'     => $data['session_type'],
            ':listing_date'     => $data['listing_date'],
            ':start_time'       => $data['start_time'],
            ':end_time'         => $data['end_time'],
            ':duration_minutes' => $data['duration_minutes'],
            ':capacity'         => $data['capacity'],
            ':price'            => $data['price'],
            ':location'         => $data['location'],
            ':approval_mode'    => $data['approval_mode'] ?? 'AUTOMATIC',
            ':boundaries'       => $data['boundaries'] ?? '',
            ':status'           => $data['status'] ?? 'ACTIVE'
        ]);

        if (!$success) {
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * 3.2. getActiveListings: Mengambil katalog penawaran aktif dengan filter & pagination
     */
    public function getActiveListings(PDO $pdo, array $filters = [], int $limit = 20, int $offset = 0): array {
        $sql = "
            SELECT 
                listings.*, 
                users.name, 
                users.avatar_url, 
                users.trust_score, 
                users.is_verified
            FROM listings 
            JOIN users ON listings.host_id = users.id 
            WHERE listings.status = 'ACTIVE'
        ";

        $params = [];

        if (!empty($filters['keyword'])) {
            $sql .= " AND (listings.title LIKE :keyword OR listings.description LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['session_type'])) {
            $sql .= " AND listings.session_type = :session_type";
            $params[':session_type'] = $filters['session_type'];
        }

        if (!empty($filters['is_free'])) {
            $sql .= " AND listings.price = 0";
        }

        if (!empty($filters['date'])) {
            $sql .= " AND listings.listing_date = :date";
            $params[':date'] = $filters['date'];
        }

        $sql .= " ORDER BY listings.created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3.3. getListingById: Mengambil rincian lengkap listing beserta profil host
     */
    public function getListingById(PDO $pdo, int $id): array|false {
        $stmt = $this->pdo->prepare("
            SELECT
                listings.*,
                users.name AS host_name,
                users.avatar_url AS host_avatar,
                users.bio AS host_bio,
                users.trust_score AS host_trust_score,
                users.is_verified AS host_is_verified
            FROM listings
            JOIN users ON listings.host_id = users.id
            WHERE listings.id = :id
        ");

        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * 3.4. getListingsByHost: Mengambil seluruh listing milik seorang host
     */
    public function getListingByHost(PDO $pdo, int $hostId): array {
        $stmt = $this->pdo->prepare("
            SELECT * 
            FROM listings 
            WHERE host_id = :host_id 
            ORDER BY created_at DESC
        ");

        $stmt->execute([':host_id' => $hostId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * 3.5. updateListing: Memperbarui data listing oleh host pemiliknya
     */
    public function updateListing(PDO $pdo, int $id, int $hostId, array $data): bool {
        $stmt = $this->pdo->prepare("
            UPDATE listings 
            SET 
                title = :title,
                description = :description,
                session_type = :session_type,
                listing_date = :listing_date,
                start_time = :start_time,
                end_time = :end_time,
                duration_minutes = :duration_minutes,
                capacity = :capacity,
                price = :price,
                location = :location,
                approval_mode = :approval_mode,
                boundaries = :boundaries
            WHERE id = :id 
              AND host_id = :host_id
              AND status IN ('DRAFT', 'ACTIVE')
              AND (
                  SELECT COUNT(*) 
                  FROM bookings 
                  WHERE listing_id = :id AND status = 'CONFIRMED'
              ) = 0
        ");

        $success = $stmt->execute([
            ':id'               => $id,
            ':host_id'          => $hostId,
            ':title'            => $data['title'],
            ':description'      => $data['description'],
            ':session_type'     => $data['session_type'],
            ':listing_date'     => $data['listing_date'],
            ':start_time'       => $data['start_time'],
            ':end_time'         => $data['end_time'],
            ':duration_minutes' => $data['duration_minutes'],
            ':capacity'         => $data['capacity'],
            ':price'            => $data['price'],
            ':location'         => $data['location'],
            ':approval_mode'    => $data['approval_mode'] ?? 'AUTOMATIC',
            ':boundaries'       => $data['boundaries'] ?? ''
        ]);

        return $success && $stmt->rowCount() > 0;
    }

    /**
     * 3.6. updateListingStatus: Mengubah status listing
     */
    public function updateListingStatus(PDO $pdo, int $id, string $status): bool {
        $allowed = ['DRAFT', 'ACTIVE', 'COMPLETED', 'CANCELLED'];

        if (!in_array($status, $allowed, true)) {
            return false;
        }

        $stmt = $this->pdo->prepare("
            UPDATE listings
            SET status = :status
            WHERE id = :id
        ");

        $success = $stmt->execute([
            ':id'     => $id,
            ':status' => $status
        ]);

        return $success && $stmt->rowCount() >= 0;
    }

    /**
     * 3.7. getRemainingCapacity: Menghitung sisa kuota kapasitas slot
     */
    public function getRemainingCapacity(PDO $pdo, int $listingId): int {
        $stmt = $this->pdo->prepare("
            SELECT 
                capacity,
                COALESCE(
                    (SELECT SUM(slot_count) 
                     FROM bookings 
                     WHERE listing_id = :listing_id 
                       AND status IN ('CONFIRMED', 'PENDING')
                    ), 0
                ) AS total_booked
            FROM listings
            WHERE id = :listing_id
        ");

        $stmt->execute([':listing_id' => $listingId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return 0;
        }

        $remaining = (int)$result['capacity'] - (int)$result['total_booked'];
        return max(0, $remaining);
    }

    /**
     * 3.8. hasOverlappingListing: Mengecek apakah host memiliki jadwal bentrok
     */
    public function hasOverlappingListing(
        PDO $pdo, 
        int $hostId, 
        string $date, 
        string $startTime, 
        string $endTime, 
        ?int $excludeListingId = null
    ): bool {
        $sql = "
            SELECT COUNT(*) 
            FROM listings 
            WHERE host_id = :host_id 
              AND listing_date = :listing_date 
              AND status IN ('DRAFT', 'ACTIVE')
              AND start_time < :end_time 
              AND end_time > :start_time
        ";

        $params = [
            ':host_id'      => $hostId,
            ':listing_date' => $date,
            ':start_time'   => $startTime,
            ':end_time'     => $endTime
        ];

        if ($excludeListingId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeListingId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (int)$stmt->fetchColumn() > 0;
    }
}
