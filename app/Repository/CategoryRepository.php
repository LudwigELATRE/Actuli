<?php

namespace App\Repository;

use PDO;
class CategoryRepository
{
    public function getAllCategory(): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM category");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}