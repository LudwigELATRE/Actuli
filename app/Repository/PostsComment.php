<?php

namespace App\Repository;

use PDO;
class PostsComment
{
    public function getAllComment(array $args): array
    {
        global $pdo;
        // Assurez-vous que l'ID du poste est fourni dans les arguments.
        if (!isset($args['id'])) {
            return []; // Retourne un tableau vide si aucun ID de poste n'est fourni.
        }
        // Préparez la requête avec une condition WHERE pour filtrer les commentaires par ID de poste.
        $stmt = $pdo->prepare("SELECT * FROM posts_comment WHERE post_id = :postId ORDER BY createdAt DESC");
        $stmt->bindParam(':postId', $args['id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(array $data): void
    {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO posts_comment (post_id,  author, content, email, createdAt) VALUES (:post_id, :author, :content, :email, :createdAt)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':post_id' => $data["postId"],
            ':author' => $data["author"],
            ':content' => $data["content"],
            ':email' => $data["email"],
            ':createdAt' => $data["createdAt"],
        ]);
    }

}