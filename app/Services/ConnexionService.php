<?php

namespace App\Services;

use PDO;

class ConnexionService
{
    const HOST = "127.0.0.1";
    const DATABASE = "actuli";
    const USER = "root";
    const PASSWORD = "root";
    const PORT = "3306:3306";
    const CHARSET = "utf8mb4";

    /**
     * Creates a new PDO connection using predefined connection parameters.
     * Sets specific PDO options to enhance error reporting and result fetching.
     *
     * @return PDO|null Returns a PDO connection object or null if connection fails.
     */
    public function connexionDatabase(): PDO|null
    {
        $dsn = "mysql:host=" . self::HOST . ";dbname=" . self::DATABASE . ";port=" . self::PORT . ";charset=" . self::CHARSET;
        $option = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $pdo = new PDO($dsn, self::USER, self::PASSWORD, $option);
        } catch (\PDOException $e) {
            error_log("Échec de la connexion : " . $e->getMessage());
            return null;
        }
        return $pdo;
    }

    /**
     * Deletes a specific database table, specified in the method.
     * Note: This method should be used with extreme caution as it will permanently delete data.
     */
    public function deleteDatabase()
    {
        $pdo = $this->connexionDatabase();
        $table = "user";

        $pdo->exec("DROP TABLE " . $table);

        echo "Database Tables " . $table . "deleted successfuly";
    }
}