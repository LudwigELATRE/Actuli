<?php

global $pdo;
require "db/connDB.php";

$pdo->exec("
CREATE TABLE IF NOT EXISTS posts (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  user_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  slug VARCHAR(255) NOT NULL,
  ft_image VARCHAR(255) NOT NULL,
  published TINYINT(1) NOT NULL,
  createdAt DATETIME NULL,
  updatedAt DATETIME NULL,
  FOREIGN KEY (`user_id`)  REFERENCES users (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo ' Tables : POSTS, ';