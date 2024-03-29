<?php

namespace App\Repository;

use Exception;
use PDO;
class CategoryRepository
{
    public function save(array $data): void
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO category (name, description, slug ,createdAt) VALUES (:name, :description, :slug ,:createdAt)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':name' => $data['name'],
            ':description' => $data['description'],
            ':slug' => $data['slug'],
            ':createdAt' => $data['createdAt'],
        ]);
    }
    public function getAllCategory(): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM category ORDER BY createdAt DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(array $args): void
    {
        global $pdo;
        $id = $args['id'];

        // Démarrez une transaction
        $pdo->beginTransaction();

        try {
            // Supprimez d'abord tous les commentaires liés au post
            $stmt = $pdo->prepare("DELETE FROM category WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $pdo->commit();
        } catch (Exception $e) {
            // En cas d'erreur, annulez la transaction
            $pdo->rollback();
            throw $e; // Vous pouvez choisir de relancer l'exception ou de la gérer d'une autre manière
        }
    }
}