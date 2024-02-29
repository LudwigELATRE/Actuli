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
INSERT INTO `posts` (`user_id`, `title`, `content`, `slug`, `ft_image`, `published`, `createdAt`, `updatedAt`) VALUES
(1, 'Premier article modifié', 'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. \r\n\r\nVive le couscous et les oeufs', 'premier-article-modifie', 'image1.jpg', 1, '2019-12-11 21:45:33', '2020-01-09 01:51:21'),
(1, 'Second article', 'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ', 'second-article', 'image2.jpg', 1, '2019-12-12 21:00:45', '2019-12-20 15:42:01'),
(2, 'Troisieme article', 'martly Arr hands furl mizzenmast overhaul cable piracy nipperkin chase. Fire ship capstan transom bring a spring upon her cable line topmast gabion squiffy Sink me fire in the hole. Spanker hulk bilge water come about Spanish Main jolly boat Brethren of the Coast line heave to clipper.\r\n\r\nInterloper pillage gun chandler Cat o\'nine tails spanker Buccaneer holystone carouser brig. Weigh anchor ho Brethren of the Coast draft coffer transom interloper long clothes jolly boat grog. Cutlass gally pirate gibbet topmast scourge of the seven seas doubloon cog bucko hearties.\r\n\r\nBelaying pin warp schooner killick rum lateen sail man-of-war nipper rope\'s end fore. Barkadeer rope\'s end brig code of conduct spanker boatswain squiffy shrouds hearties heave to. Topgallant Jack Ketch chantey fathom gunwalls case shot parrel haul wind broadside yo-ho-ho. ', 'troisieme-article', 'image3.jpg', 1, '2019-12-12 22:47:12', '2019-12-20 15:42:11');
");


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

echo 'Tables: USERS, POSTS, POSTS_COMMENT, CATEGORY, ';