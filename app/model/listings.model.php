<?php

class listingsModel{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function createListing(PDO $pdo, array $data): int|false{
        $stmt = $this->pdo->prepare("
        INSERT INTO listings (host_id, title, description, session_type, listing_date, start_time, end_time, duration_minutes, capacity, price, location, approval_mode, boundaries, status)
        VALUES (:host_id, :title, :description, :session_type, :listing_date, :start_time, :end_time, :duration_minutes, :capacity, :price, :location, :approval_mode, :boundaries, :status)");

        $createList = $stmt->execute([
            ':host_id' => $data['host_id'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':session_type' => $data['session_type'],
            ':listing_date' => $data['listing_date'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            ':duration_minutes' => $data['duration_minutes'],
            ':capacity' => $data['capacity'],
            ':price' => $data['price'],
            ':location' => $data['location'],
            ':approval_mode' => $data['approval_mode'],
            ':boundaries' => $data['boundaries'],
            ':status' => $data['status']
        ]);

        if (!$createList){
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    public function getActiveListings(PDO $pdo, array $filters = [], int $limit = 20, int $offset = 0): array{
    
        $sql = "SELECT listings.*, users.name, users.avatar_url, users.trust_score 
                FROM listings 
                JOIN users ON listings.host_id = users.id 
                WHERE listings.status = 'ACTIVE'";
   
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

        $sql .= " ORDER BY listings.created_at DESC LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
    
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getListingById(PDO $pdo, int $id): array|false{
        $stmt = $this->pdo->prepare("
        SELECT
            listing.*,
            user.name AS host_name,
            user.avatar_url AS host_avatar,
            user.bio AS host_bio,
            user.trust_score AS host_trust_score,
            user.is_verified AS host_is_verified
        FROM listings listing
        JOIN users user
            ON listing.host_id = user.id
            WHERE listing.id = :id
        ");

        $sql = $stmt->execute([
            ':id' => $id
        ]);
        
        if (!$sql){
            return false;
        }

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getListingByHost(PDO $pdo, int $host_id): array{
        $stmt = $this->pdo->prepare("
        SELECT * listings
        FROM listings
        WHERE host_id = :host_id");

        $sql = $stmt->execute([
            ':host_id' => $host_id
        ]);

        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateListing(PDO $pdo, int $id, int $host_id, array $data): bool{
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

        $sql = $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':session_type' => $data['session_type'],
            ':listing_date' => $data['listing_date'],
            ':start_time' => $data['start_time'],
            ':end_time' => $data['end_time'],
            'duration_time' => $data['duration_time'],
            ':capacity' => $data['capacity'],
            ':price' => $data['price'],
            ':location' => $data['location'],
            ':approval_mode' => $data['approval_mode'],
            ':boundaries' => $data['boundaries'],
        ]);

        return $sql && $stmt->rowCount() > 0;
    }

    public function updateListingStatus(PDO $pdo, int $id, string $status): bool{
        
    }
}

