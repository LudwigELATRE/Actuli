<?php

global $pdo;
require "db/connDB.php";

$pdo->exec("
CREATE TABLE IF NOT EXISTS posts_comment (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  post_id INT NOT NULL,
  author VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  createdAt DATETIME NULL,
  FOREIGN KEY (`post_id`)  REFERENCES posts (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo ' Tables : POSTS_COMMENT, ';