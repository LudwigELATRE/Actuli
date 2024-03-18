<?php

namespace App\Services;

class ConnexionService
{
    const HOST = "127.0.0.1";
    const DATABASE = "actuli";
    const USER = "root";
    const PASSWORD = 'valeur constante';
    const PORT = 'valeur constante';
    const CHARSET = 'valeur constante';
    public function connexionDatabase()
    {
        $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DATABASE . ";port=" . self::PORT . ";charset=" . self::CHARSET;
        $option = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new \PDO($dsn, self::USER, self::PASSWORD, $option);
            echo 'la base de donnee est prete';
        } catch (\PDOException $e) {
            // Log de l'erreur plutôt que d'afficher le message directement
            error_log("Échec de la connexion : " . $e->getMessage());
            // Retourner null ou gérer l'erreur d'une autre manière appropriée
            return null;
        }
        return $pdo;
    }

    public function deleteDatabase()
    {
        $pdo = $this->connexionDatabase();
        $table = "user";

        $pdo->exec("DROP TABLE " . $table);

        echo "Database Tables " . $table . "deleted successfuly";
    }
}