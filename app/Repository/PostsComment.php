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
        $stmt = $pdo->prepare("SELECT * FROM posts_comment WHERE post_id = :postId");
        $stmt->bindParam(':postId', $args['id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}