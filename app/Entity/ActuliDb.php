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

$pdo->exec("
CREATE TABLE IF NOT EXISTS category (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) NOT NULL,
  description TEXT NULL,
  createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updatedAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE INDEX name_UNIQUE (name),
  UNIQUE INDEX slug_UNIQUE (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

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
  FOREIGN KEY (`user_id`) REFERENCES users (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$pdo->exec("
CREATE TABLE IF NOT EXISTS posts_comment (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  post_id INT NOT NULL,
  author VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  createdAt DATETIME NULL,
  FOREIGN KEY (`post_id`) REFERENCES posts (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
//pour recuperer faire jointure avec table user

$pdo->exec("
CREATE TABLE IF NOT EXISTS contact (
  id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
  name VARCHAR(255) NOT NULL,
  subject VARCHAR(255) NOT NULL,
  message TEXT NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

echo 'Tables: USERS, POSTS, POSTS_COMMENT, CATEGORY, CONTACT';