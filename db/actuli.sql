# ************************************************************
# Antares - SQL Client
# Version 0.7.23
# 
# https://antares-sql.app/
# https://github.com/antares-sql/antares
# 
# Host: 127.0.0.1 (MySQL Community Server - GPL 8.3.0)
# Database: actuli
# Generation time: 2024-04-12T16:08:37+02:00
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table category
# ------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text,
  `createdAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;

INSERT INTO `category` (`id`, `name`, `slug`, `description`) VALUES
	(7, "Programmation et Développement Web", "programmation-et-développement-web", "Tutoriels, astuces, et meilleures pratiques pour différents langages de programmation et cadres de développement."),
	(8, "Sécurité Informatique", "sécurité-informatique", "Conseils sur la sécurisation des systèmes et des réseaux, sensibilisation aux cybermenaces, et meilleures pratiques de cybersécurité."),
	(9, "Intelligence Artificielle et Machine Learning", "intelligence-artificielle-et-machine-learning", "Explorations des dernières avancées, des applications pratiques, et des implications éthiques de l\'IA."),
	(10, "Hardware et Gadgets", "hardware-et-gadgets", "Critiques de matériel informatique, guides d\'achat, et actualités sur les derniers gadgets technologiques."),
	(11, "Systèmes d\'Exploitation et Logiciels", "systèmes-d\'exploitation-et-logiciels", "Astuces, tutoriels, et critiques pour différents systèmes d\'exploitation et logiciels, y compris open source."),
	(12, "Blockchain et Cryptomonnaies", "blockchain-et-cryptomonnaies", "Discussions sur la blockchain, les cryptomonnaies, leur fonctionnement, et leur impact sur l\'industrie."),
	(13, "Réseaux et Infrastructure", "réseaux-et-infrastructure", "Guides sur la configuration de réseaux, l\'optimisation de la performance, et les meilleures pratiques d\'infrastructure."),
	(14, "Projets DIY et Electronique", "projets-diy-et-electronique", "Projets informatiques à faire soi-même, y compris l\'électronique et la robotique, avec des guides étape par étape."),
	(15, "Carrière et Formation en Informatique", "carrière-et-formation-en-informatique", "Conseils sur le développement de carrière, la formation continue, et l\'apprentissage de nouvelles compétences en informatique."),
	(20, "Tendances et Innovations Technologiques", "tendances-et-innovations-technologiques", "test de connexionn service");

/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table contact
# ------------------------------------------------------------

DROP TABLE IF EXISTS `contact`;

CREATE TABLE `contact` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `subject` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `contact` WRITE;
/*!40000 ALTER TABLE `contact` DISABLE KEYS */;

INSERT INTO `contact` (`id`, `name`, `email`, `subject`, `message`) VALUES
	(12, "Ludwig", "ludwig.elatre@outlook.com", "TEST", "sdcdsc"),
	(13, "Ludwig", "poubellepasremplie@gmail.com", "fdgbds", "gdgfd"),
	(15, "gergergsqerg", "poubellepasremplie@gmail.com", "grergergh", "ertherh"),
	(16, "erherh", "ludwig.elatre@outlook.com", "ererherh", "erthreherher");

/*!40000 ALTER TABLE `contact` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table posts
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `published` tinyint(1) NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  `updatedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;

INSERT INTO `posts` (`id`, `user_id`, `category`, `title`, `content`, `slug`, `image`, `published`, `createdAt`, `updatedAt`) VALUES
	(48, 1, "Programmation et Développement Web", "fzefsf", "sefsefsef", "fzefsf", "t-l-chargement-1-.jpg", 1, "2024-04-12 00:00:00", NULL),
	(49, 1, "Tendances et Innovations Technologiques", "test", "rdsghsdgf", "test", "t-l-chargement.jpg", 1, "2024-04-12 00:00:00", NULL);

/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table posts_comment
# ------------------------------------------------------------

DROP TABLE IF EXISTS `posts_comment`;

CREATE TABLE `posts_comment` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `post_id` int NOT NULL,
  `author` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `createdAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `posts_comment_ibfk_2` (`user_id`),
  CONSTRAINT `posts_comment_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`),
  CONSTRAINT `posts_comment_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `posts_comment` WRITE;
/*!40000 ALTER TABLE `posts_comment` DISABLE KEYS */;

INSERT INTO `posts_comment` (`id`, `user_id`, `post_id`, `author`, `email`, `content`, `createdAt`) VALUES
	(28, 1, 48, "LudwigELATRE", "admin@admin.fr", "salut", "2024-04-12 00:00:00"),
	(29, 1, 48, "LudwigELATRE", "admin@admin.fr", "fzefzef", "2024-04-12 00:00:00"),
	(30, 1, 48, "LudwigELATRE", "admin@admin.fr", "zefzefze", "2024-04-12 00:00:00"),
	(31, 1, 48, "LudwigELATRE", "admin@admin.fr", "ezfzefzef", "2024-04-12 00:00:00"),
	(32, 1, 49, "LudwigELATRE", "admin@admin.fr", "zefzef", "2024-04-12 00:00:00"),
	(33, 1, 49, "LudwigELATRE", "admin@admin.fr", "zefze", "2024-04-12 00:00:00"),
	(34, 1, 49, "LudwigELATRE", "admin@admin.fr", "efzef", "2024-04-12 00:00:00"),
	(35, 1, 48, "LudwigELATRE", "admin@admin.fr", "dede", "2024-04-12 00:00:00"),
	(36, 1, 48, "LudwigELATRE", "admin@admin.fr", "iedjenj kjkncfzje dfcjzenfjz encfdjznpaN FJNZKJEFNZkjeufnkjZNECDFKJnefjNEFDCjnfckjNFEC fjecn zFJznefcd ZFNEOEJFJZUJIOEHNFCZJUHNEFCIPzfoiueZFIJCNziuefiUHJFIPziupyvfFDNIPmfiuZ", "2024-04-12 00:00:00"),
	(37, 1, 48, "LudwigELATRE", "admin@admin.fr", "iedjenj kjkncfzje dfcjzenfjz encfdjznpaN FJNZKJEFNZkjeufnkjZNECDFKJnefjNEFDCjnfckjNFEC fjecn zFJznefcd ZFNEOEJFJZUJIOEHNFCZJUHNEFCIPzfoiueZFIJCNziuefiUHJFIPziupyvfFDNIPmfiuZ", "2024-04-12 00:00:00");

/*!40000 ALTER TABLE `posts_comment` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lastname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `mobile` int DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `profile` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `roles` enum('ROLE_ADMIN','ROLE_USER') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'ROLE_USER',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sexe` enum('man','woman','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `address`, `mobile`, `password`, `slug`, `image`, `profile`, `roles`, `sexe`) VALUES
	(1, "Ludwig", "ELATRE", "admin@admin.fr", "27 rue de l\'ermitage 91210 Draveil", 897676562, "$2y$10$23xBj0Kha.ActIkQSZwV8.sdZctSUW0o.tnhBR8AixwLk1PAKRSle", NULL, NULL, "je suis le boss de se truc", "ROLE_ADMIN", "man"),
	(24, "thomas", "caro", "caro@gmail.com", NULL, NULL, "$2y$10$BelipurAPOKwjU.4WQEzpu7jIZF3DW6kQ7t3p8wtRWQOQrgyaWQ8G", "thomas-caro", NULL, NULL, "ROLE_USER", "man");

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of views
# ------------------------------------------------------------

# Creating temporary tables to overcome VIEW dependency errors


/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

# Dump completed on 2024-04-12T16:08:37+02:00
