<?php

namespace App\Repository;

use Exception;
use PDO;
class PostRepository
{
    public function save(array $data): void
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO posts (user_id, category ,title, content, slug, published, createdAt) VALUES (:user_id, :category ,:title, :content, :slug, :published, :createdAt)");

        $stmt->execute([
            ':user_id' => $data['userId'],
            ':category' => $data['categorie'],
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':slug' => $data['slug'],
            ':published' => $data['published'],
            ':createdAt' => $data['createdAt'],
        ]);
    }

    public function getAllPost(): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM posts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPostFormUser($id): array
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT posts.* FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE users.id = :userId
            ORDER BY posts.createdAt DESC"
        );
        $stmt->execute(
            [
                ":userId" => $id
            ]
        );
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

    /**
     * @throws Exception
     */
    public function deletePostFromUser(array $args): void
    {
        global $pdo;
        $id = $args['id'];

        $isTransactionActive = $pdo->inTransaction();

        // Démarrez une transaction seulement s'il n'y en a pas déjà une
        if (!$isTransactionActive) {
            $pdo->beginTransaction();
        }

        try {
            // Supprimez d'abord tous les commentaires liés au post
            $stmt = $pdo->prepare("DELETE FROM posts_comment WHERE post_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Ensuite, supprimez le post lui-même
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Validez la transaction
            $pdo->commit();
        } catch (Exception $e) {
            // En cas d'erreur, annulez la transaction
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e; // Vous pouvez choisir de relancer l'exception ou de la gérer d'une autre manière
        }
    }

    public function updatePost(array $data): void
    {
        global $pdo;
        // Préparation de la requête de mise à jour sans user_id
        $stmt = $pdo->prepare("UPDATE posts SET category = :category, title = :title, content = :content, slug = :slug, published = :published, updatedAt = :updatedAt WHERE id = :id");

        $stmt->execute([
            ':id' => $data['id'],
            ':category' => $data['categorie'], // Assurez-vous que cette clé correspond à votre structure de tableau
            ':title' => $data['title'],
            ':content' => $data['content'],
            ':slug' => $data['slug'],
            ':published' => $data['published'],
            ':updatedAt' => $data['updatedAt'],
        ]);
    }




}