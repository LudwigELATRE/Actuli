<?php

namespace App\Repository;

use App\Entity\PostComment;
use App\Services\ConnexionService;
use Exception;
use PDO;
class PostsCommentRepository
{

    /**
     * Retrieves all comments associated with a specific post.
     *
     * @param array $args Should contain 'id' key specifying the post ID.
     * @return array An array of comments in associative array format or an empty array if no ID provided.
     */
    public function getAllCommentPostId(array $args): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        // Assurez-vous que l'ID du poste est fourni dans les arguments.
        if (!isset($args['id'])) {
            return []; // Retourne un tableau vide si aucun ID de poste n'est fourni.
        }
        // Préparez la requête avec une condition WHERE pour filtrer les commentaires par ID de poste.
        $stmt = $connexionDatabase->prepare("SELECT * FROM posts_comment WHERE post_id = :postId ORDER BY createdAt DESC");
        $stmt->bindParam(':postId', $args['id'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Saves a new comment to the database.
     * Inserts the comment details into the posts_comment table.
     *
     * @param PostComment $postComment An instance of PostComment containing comment data.
     */
    public function save(PostComment $postComment): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $stmt = $connexionDatabase->prepare("INSERT INTO posts_comment (user_id, post_id,  author, content, email, createdAt) VALUES (:user_id, :post_id, :author, :content, :email, :createdAt)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':user_id' => $postComment->getUserId(),
            ':post_id' => $postComment->getPostId(),
            ':author' => $postComment->getAuthor(),
            ':content' => $postComment->getContent(),
            ':email' => $postComment->getEmail(),
            ':createdAt' => $postComment->getCreatedAt(),
        ]);
    }

    /**
     * Retrieves all comments associated with a specific post.
     *
     * @return array An array of comments in associative array format or an empty array if no ID provided.
     */
    public function getAllComments(): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        //$stmt = $connexionDatabase->prepare("SELECT * FROM posts_comment ORDER BY createdAt DESC");
        $stmt = $connexionDatabase->prepare("
        SELECT pc.*, p.title AS post_title 
        FROM posts_comment pc
        JOIN posts p ON pc.post_id = p.id
        ORDER BY pc.createdAt DESC
    ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Deletes a category based on the provided ID.
     * Handles deletion within a transaction to ensure integrity.
     *
     * @param array $args Must contain the 'id' of the category to be deleted.
     * @throws Exception If there is an error during the transaction, including rollback scenarios.
     */
    public function delete(array $args): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $id = $args['id'];

        // Démarrez une transaction
        $connexionDatabase->beginTransaction();

        try {
            $stmt = $connexionDatabase->prepare("DELETE FROM posts_comment WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $connexionDatabase->commit();
        } catch (Exception $e) {
            // En cas d'erreur, annulez la transaction
            $connexionDatabase->rollback();
            throw $e; // Vous pouvez choisir de relancer l'exception ou de la gérer d'une autre manière
        }
    }
}