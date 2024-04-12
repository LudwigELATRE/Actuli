<?php

namespace App\Repository;

use App\Entity\User;
use App\Services\ConnexionService;
use Exception;
use PDO;
class UserRepository
{
    /**
     * Inserts a new user into the database.
     *
     * @param User $user The user entity to be saved.
     */
    public function save(User $user){
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $stmt = $connexionDatabase->prepare("INSERT INTO users (firstname, lastname, email, password, roles, slug) VALUES (:firstname, :lastname, :email, :password, :roles, :slug)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':firstname' => $user->getFirstname(),
            ':lastname' => $user->getLastname(),
            ':email' => $user->getEmail(),
            ':password' => $user->getPassword(),
            ':roles' => $user->getRoles(),
            ':slug' => $user->getSlug(),
        ]);
    }

    /**
     * Retrieves all users from the database.
     *
     * @return array An array of all users in associative array format.
     */
    public function getAllUsers(): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $stmt = $connexionDatabase->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a user by their ID.
     *
     * @param mixed $id The ID of the user to retrieve.
     * @return array An array containing the user's data or empty if not found.
     */
    public function getUser($id)
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $stmt = $connexionDatabase->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Finds a user based on specific criteria, typically used for authentication.
     *
     * @param array $data Criteria used for finding the user, e.g., email.
     * @return array An associative array containing the user's data or empty if not found.
     */
    public function findBySomeField(array $data): array
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $stmt = $connexionDatabase->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $data['email']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }

    /**
     * Updates user information in the database.
     *
     * @param array $data An associative array containing data to update.
     */
    public function update(array $data)
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $stmt = $connexionDatabase->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, profile = :profile, mobile = :mobile, address = :address WHERE id = :id");

        $stmt->execute([
            ':id' => $data['id'],
            ':firstname' => $data['firstname'],
            ':lastname' => $data['lastname'],
            ':profile' => $data['profile'],
            ':mobile' => $data['mobile'],
            ':address' => $data['address'],
        ]);
    }

    /**
     * Deletes a user along with their associated posts and comments from the database.
     *
     * @param array $args An array containing 'id' of the user to delete.
     * @throws Exception If the transaction fails.
     */
    public function deleteUserWithPostsAndComments(array $args): void
    {
        $connexionService = new ConnexionService();
        $connexionDatabase = $connexionService->connexionDatabase();

        $userId = $args['id'];

        // Vérifiez si une transaction est déjà en cours.
        if (!$connexionDatabase->inTransaction()) {
            $connexionDatabase->beginTransaction();
        }

        try {
            // Supprimez les commentaires de l'utilisateur.
            $stmt = $connexionDatabase->prepare("DELETE FROM posts_comment WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Supprimez d'abord tous les commentaires des posts de l'utilisateur.
            $stmt = $connexionDatabase->prepare("DELETE FROM posts_comment WHERE post_id IN (SELECT id FROM posts WHERE user_id = :userId)");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Ensuite, supprimez tous les posts de l'utilisateur.
            $stmt = $connexionDatabase->prepare("DELETE FROM posts WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Enfin, supprimez l'utilisateur lui-même.
            $stmt = $connexionDatabase->prepare("DELETE FROM users WHERE id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Si tout s'est bien passé, validez la transaction.
            $connexionDatabase->commit();
        } catch (Exception $e) {
            // En cas d'erreur, annulez la transaction.
            if ($connexionDatabase->inTransaction()) {
                $connexionDatabase->rollBack();
            }
            throw $e; // Vous pouvez choisir de relancer l'exception ou de la gérer d'une autre manière.
        }
    }



}