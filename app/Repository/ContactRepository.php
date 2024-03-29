<?php

namespace App\Repository;

use App\Services\ConnexionService;
use PDO;
class ContactRepository
{
    public function save(array $data){
        // Préparation de la requête
        //$connexionDatabase = new ConnexionService();
        //$pdo = $connexionDatabase->connexionDatabase();
        //dd($pdo);
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO contact (name, subject, message) VALUES (:name, :subject, :message)");

        // Exécution de la requête avec les valeurs
        $stmt->execute([
            ':name' => $data['name'],
            ':subject' => $data['subject'],
            ':message' => $data['message'],
        ]);
    }

}