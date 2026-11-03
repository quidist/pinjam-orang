<?php

class UserModel{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }

    public function createuser(PDO $pdo, array $data): int | false{
        $stmt = $this->pdo->prepare("
        INSERT INTO users (name, email, password_hash) 
        VALUES (:name, :email, :password_hash)");

        $create = $stmt->execute([
            ':nama' => $data['nama'],
            ':email' => $data['email'],
            'password_hash' => $data['password_hash']
        ]);

        if(!create){
            return false;
        }

        return (int) $this->pdo->lastInsertId();
    }

    public function getUserByEmail(PDO $pdo, string $email): array | false{
        $stmt = $this->pdo->prepare("
        SELECT * WHERE
        email = :email");

        $getUser = $stmt->execute([
            ':email' => $email
        ]);

        if(!getUser){
            return false;
        }

        return (array) $this->$getUser->fetch(PDO::FETCH_ASSOC);
    }

}
