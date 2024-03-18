<?php

namespace App\Repository;

use Exception;
use PDO;
class UserRepository
{
    public function save(array $data){
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password, roles) VALUES (:firstname, :lastname, :email, :password, :roles)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':firstname' => $data['firstname'],
            ':lastname' => $data['lastname'],
            ':email' => $data['email'],
            ':password' => $data['password'],
            ':roles' => $data['roles']
        ]);
    }
    public function getAllUsers(): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUser($id)
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute([
            ':id' => $id
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findBySomeField(array $data): array
    {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $data['email']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result : [];
    }

    public function update(array $data)
    {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE users SET firstname = :firstname, lastname = :lastname, profile = :profile, mobile = :mobile, address = :address WHERE id = :id");

        $stmt->execute([
            ':id' => $data['id'],
            ':firstname' => $data['firstname'],
            ':lastname' => $data['lastname'],
            ':profile' => $data['profile'],
            ':mobile' => $data['mobile'],
            ':address' => $data['address'],
        ]);
    }

    public function deleteUserWithPostsAndComments(array $args): void
    {
        global $pdo; // Considérez d'injecter $pdo via le constructeur pour une meilleure pratique.
        $userId = $args['id']; // Cet ID doit être celui de l'utilisateur à supprimer.

        // Vérifiez si une transaction est déjà en cours.
        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
        }

        try {
            // Supprimez d'abord tous les commentaires des posts de l'utilisateur.
            $stmt = $pdo->prepare("DELETE FROM posts_comment WHERE post_id IN (SELECT id FROM posts WHERE user_id = :userId)");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Ensuite, supprimez tous les posts de l'utilisateur.
            $stmt = $pdo->prepare("DELETE FROM posts WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Enfin, supprimez l'utilisateur lui-même.
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();

            // Si tout s'est bien passé, validez la transaction.
            $pdo->commit();
        } catch (Exception $e) {
            // En cas d'erreur, annulez la transaction.
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e; // Vous pouvez choisir de relancer l'exception ou de la gérer d'une autre manière.
        }
    }



}