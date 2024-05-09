<?php

global $pdo;
require "connDB.php";

$table = "user";

$pdo->exec("DROP TABLE " . $table);

echo "Database Tables " . $table . "deleted successfuly";