<?php

namespace App\Repository;

use PDO;
class PostRepository
{
    public function getAllPost(): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM posts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(array $args): array
    {
        global $pdo;
        $id = $args['id'];
        $stmt = $pdo->prepare("
            SELECT posts.*, users.firstname, users.lastname 
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = :id
        ");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}