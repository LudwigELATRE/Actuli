<?php

namespace App\Repository;

use App\Services\ConnexionService;
use Exception;
use PDO;
class CategoryRepository
{
    /**
     * Saves a new category to the database.
     * Inserts a category using the provided name, description, slug, and creation date.
     *
     * @param string $name The name of the category.
     * @param string $description The description of the category.
     * @param string $slug The slug for the category, typically URL-friendly.
     * @param string $createdAt The creation date of the category.
     * @throws Exception If there is a database connection or execution problem.
     */
    public function save(string $name,string $description,string $slug, string $createdAt): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $stmt = $connexionDatabase->prepare("INSERT INTO category (name, description, slug ,createdAt) VALUES (:name, :description, :slug ,:createdAt)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':slug' => $slug,
            ':createdAt' => $createdAt,
        ]);
    }

    /**
     * Retrieves all categories from the database.
     * Fetches all categories and sorts them by their creation date in descending order.
     *
     * @return array An array of all categories in associative array format.
     * @throws Exception If there is a database connection or execution problem.
     */
    public function getAllCategory(): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $stmt = $connexionDatabase->prepare("SELECT * FROM category ORDER BY createdAt DESC");
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
            // Supprimez d'abord tous les commentaires liés au post
            $stmt = $connexionDatabase->prepare("DELETE FROM category WHERE id = :id");
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