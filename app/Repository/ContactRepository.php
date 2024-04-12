<?php

namespace App\Repository;

use App\Services\ConnexionService;
use Exception;
use PDO;

class ContactRepository
{
    /**
     * Saves a new contact message to the database.
     * This method inserts a new contact record with provided name, email, subject, and message.
     *
     * @param string $name The name of the person sending the contact message.
     * @param string $email The email address of the person sending the contact message.
     * @param string $subject The subject line of the contact message.
     * @param string $message The content of the contact message.
     */
    public function save(string $name,string $email,string $subject,string $message): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();
        $stmt = $connexionDatabase->prepare("INSERT INTO contact (name,email ,subject ,message) VALUES (:name,:email ,:subject, :message)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':subject' => $subject,
            ':message' => $message,
        ]);
    }

    /**
     * Retrieves all users from the database.
     *
     * @return array An array of all users in associative array format.
     */
    public function getAllContacts(): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $stmt = $connexionDatabase->prepare("SELECT * FROM contact");
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
            $stmt = $connexionDatabase->prepare("DELETE FROM contact WHERE id = :id");
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