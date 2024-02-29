<?php

global $pdo;
require "db/connDB.php";

$pdo->exec("
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  firstname VARCHAR(255) NOT NULL,
  lastname VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NULL,
  ft_image VARCHAR(255) NULL,
  profile TEXT,
  roles ENUM('ROLE_ADMIN', 'ROLE_USER') DEFAULT 'ROLE_USER',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE INDEX email_UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo ' Tables : USERS, ';