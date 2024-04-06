<?php

namespace App\Repository;

use App\Services\ConnexionService;
use Exception;
use PDO;
class PostRepository
{
    /**
     * Saves a new post to the database.
     * Inserts a new post using the provided parameters.
     *
     * @param int $user_id The ID of the user who created the post.
     * @param string $category The category of the post.
     * @param string $title The title of the post.
     * @param string $content The content of the post.
     * @param string $slug The SEO-friendly URL slug.
     * @param string $image The path to the post's image.
     * @param bool $published Whether the post is published or not.
     * @param string $createdAt The creation date of the post.
     */
    public function save(int $user_id,string $category,string $title,string $content,string $slug,string $image,bool $published, string $createdAt): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $stmt = $connexionDatabase->prepare("INSERT INTO posts (user_id, category ,title, content, slug, image, published, createdAt) VALUES (:user_id, :category ,:title, :content, :slug, :image, :published, :createdAt)");

        $stmt->execute([
            ':user_id' => $user_id,
            ':category' => $category,
            ':title' => $title,
            ':content' => $content,
            ':slug' => $slug,
            ':image' => $image,
            ':published' => $published,
            ':createdAt' => $createdAt,
        ]);
    }

    /**
     * Retrieves all posts from the database.
     *
     * @return array An array of all posts in associative array format.
     */
    public function getAllPost(): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $stmt = $connexionDatabase->prepare("SELECT * FROM posts");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all posts from a specific user.
     *
     * @param int $id The ID of the user.
     * @return array An array of all posts by the specified user in associative array format.
     */
    public function getPostFormUser($id): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $stmt = $connexionDatabase->prepare("
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

    /**
     * Finds a post by its ID and includes author information.
     *
     * @param array $args Contains 'id' of the post to find.
     * @return array|null The post data with author details or null if no post found.
     */
    public function findById(array $args): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $id = $args['id'];
        $stmt = $connexionDatabase->prepare("
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
     * Deletes a post and its associated comments.
     *
     * @param array $args Contains 'id' of the post to delete.
     * @throws Exception If the transaction fails.
     */
    public function deletePostFromUser(array $args): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $id = $args['id'];

        $isTransactionActive = $connexionDatabase->inTransaction();

        // Démarrez une transaction seulement s'il n'y en a pas déjà une
        if (!$isTransactionActive) {
            $connexionDatabase->beginTransaction();
        }

        try {
            // Supprimez d'abord tous les commentaires liés au post
            $stmt = $connexionDatabase->prepare("DELETE FROM posts_comment WHERE post_id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Ensuite, supprimez le post lui-même
            $stmt = $connexionDatabase->prepare("DELETE FROM posts WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // Validez la transaction
            $connexionDatabase->commit();
        } catch (Exception $e) {
            // En cas d'erreur, annulez la transaction
            if ($connexionDatabase->inTransaction()) {
                $connexionDatabase->rollBack();
            }
            throw $e; // Vous pouvez choisir de relancer l'exception ou de la gérer d'une autre manière
        }
    }

    /**
     * Updates a post with new data.
     *
     * @param int $id The ID of the post to update.
     * @param string $category The new category of the post.
     * @param string $title The new title of the post.
     * @param string $content The new content of the post.
     * @param string $slug The new slug of the post.
     * @param string $image The new image path for the post.
     * @param bool $published Whether the post is now published or not.
     * @param string $createdAt The date the post was updated.
     */
    public function updatePost(int $id,string $category,string $title,string $content,string $slug,string $image,bool $published, string $createdAt): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        // Préparation de la requête de mise à jour sans user_id
        $stmt = $connexionDatabase->prepare("UPDATE posts SET category = :category, title = :title, content = :content, slug = :slug, image = :image, published = :published, updatedAt = :updatedAt WHERE id = :id");

        $stmt->execute([
            ':id' => $id,
            ':category' => $category,
            ':title' => $title,
            ':content' => $content,
            ':slug' => $slug,
            ':image' => $image,
            ':published' => $published,
            ':updatedAt' => $createdAt,
        ]);
    }
}