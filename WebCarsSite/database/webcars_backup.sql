-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: webcars
-- ------------------------------------------------------
-- Server version	8.4.7

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `car_images`
--

DROP TABLE IF EXISTS `car_images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `car_id` int NOT NULL,
  `filename` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `car_id` (`car_id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_images`
--

LOCK TABLES `car_images` WRITE;
/*!40000 ALTER TABLE `car_images` DISABLE KEYS */;
INSERT INTO `car_images` VALUES (5,1,'corolla_front.jpg','Front view','2026-01-07 18:15:18'),(6,1,'corolla_interior.jpg','Interior','2026-01-07 18:15:18'),(7,2,'civic_side.jpg','Side view','2026-01-07 18:15:18'),(8,2,'civic_dashboard.jpg','Dashboard','2026-01-07 18:15:18'),(9,3,'golf_front.jpg','Front view','2026-01-07 18:15:18'),(10,3,'golf_back.jpg','Back view','2026-01-07 18:15:18'),(11,4,'focus_front.jpg','Front view','2026-01-07 18:15:18'),(12,4,'focus_interior.jpg','Interior','2026-01-07 18:15:18'),(13,5,'bmw_front.jpg','Front view with LED lights','2026-01-07 18:15:18'),(14,5,'bmw_interior.jpg','Luxury interior','2026-01-07 18:15:18'),(15,6,'mercedes_front.jpg','Front view','2026-01-07 18:15:18'),(16,6,'mercedes_back.jpg','Back view','2026-01-07 18:15:18'),(17,7,'audi_front.jpg','Front view','2026-01-07 18:15:18'),(18,7,'audi_interior.jpg','Interior with technology','2026-01-07 18:15:18'),(19,8,'yaris_front.jpg','Front view','2026-01-07 18:15:18'),(20,8,'yaris_interior.jpg','Compact interior','2026-01-07 18:15:18'),(21,9,'qashqai_front.jpg','SUV front view','2026-01-07 18:15:18'),(22,9,'qashqai_back.jpg','Spacious back','2026-01-07 18:15:18');
/*!40000 ALTER TABLE `car_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cars` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `brand` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `model` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `body_type` enum('mini','hatchback','sedan','SUV') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `engine_cc` int DEFAULT NULL,
  `fuel_type` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kilometers` int DEFAULT NULL,
  `first_registration` date DEFAULT NULL,
  `has_turbo` tinyint(1) DEFAULT '0',
  `is_hybrid` tinyint(1) DEFAULT '0',
  `needs_repair` tinyint(1) DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES (1,1,'Toyota','Corolla','sedan',1600,'Petrol',120000,'2018-05-15',0,0,0,12500.00,'2026-01-07 17:27:20'),(2,2,'Honda','Civic','hatchback',1800,'Petrol',80000,'2019-08-22',1,0,0,14500.00,'2026-01-07 17:27:20'),(3,3,'Volkswagen','Golf','hatchback',1400,'Diesel',95000,'2017-11-10',1,0,1,11000.00,'2026-01-07 17:27:20'),(4,4,'Ford','Focus','sedan',1600,'Petrol',150000,'2016-03-25',0,0,0,8500.00,'2026-01-07 17:27:20'),(5,5,'BMW','320i','sedan',2000,'Petrol',60000,'2020-01-18',1,0,0,22500.00,'2026-01-07 17:27:20'),(6,6,'Mercedes','A180','hatchback',1600,'Diesel',70000,'2019-07-12',0,1,0,19500.00,'2026-01-07 17:27:20'),(7,7,'Audi','A3','hatchback',1400,'Petrol',55000,'2020-09-05',1,0,0,18500.00,'2026-01-07 17:27:20'),(8,8,'Toyota','Yaris','mini',1200,'Petrol',30000,'2021-02-28',0,1,0,13500.00,'2026-01-07 17:27:20'),(9,9,'Nissan','Qashqai','SUV',1500,'Diesel',90000,'2018-12-15',1,0,0,16500.00,'2026-01-07 17:27:20'),(10,10,'Opel','Corsa','hatchback',1200,'Petrol',110000,'2017-06-20',0,0,1,7500.00,'2026-01-07 17:27:20');
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `activation_code` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `style_pref` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'light',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'john_doe','9cba73c31ac15d21512382ce6b21e83f8b9fddd31196ff4f54559a8e29add1e3bc4038c86c9bee7512d0d8ea72ec9480580dc677a9f172b46366ecb5198615cc','John','Doe','john@example.com','2101234567','12345',1,'light','2026-01-07 17:27:20'),(2,'maria_smith','419886747620b6e20773e5d0c128ce9ed3689bd5a0a634f03b1189bf1d8da07a608d43223cd6ff30b17198a481b47b6be2a4d6646b0c431e81b5de5e24b3e0bd','Maria','Smith','maria@example.com','2102345678','23456',1,'light','2026-01-07 17:27:20'),(3,'george_brown','339742d3f04363a65efa2f3fb779f65edbfb352ca2a77c73e22da320340a3cd6e76a50f3f166548692f31eb414ae69cd79a78bb50ca4843801ebb17f119af82f','George','Brown','george@example.com','2103456789','34567',1,'light','2026-01-07 17:27:20'),(4,'anna_wilson','7f5dfac86bbaa44eb70dbfef3ba4c2fc599ee1f99efe1c6c1c41912d52f4e42782ba50e81fd6762582aae51fe7b0966bf1b8211181c59cc45f61f8522c9b7758','Anna','Wilson','anna@example.com','2104567890','45678',1,'light','2026-01-07 17:27:20'),(5,'peter_taylor','42c3ba0410c7ac2452e94a876baeb843a2be90b1c95eba9f7b48c02e6c4664132df50a88d4e86c699d594ad46ecdb8a559c39f0457ed1726fde1b6e9434f0eb6','Peter','Taylor','peter@example.com','2105678901','56789',1,'light','2026-01-07 17:27:20'),(6,'sophia_clark','e42d147651ebb52d12559d85bdb0ef5f545e0b7cfa9cf9ee66fcf3e55005c278d5b97ae0270afbaab568eff223b662153a17d4303e1fc2c563289538296097a1','Sophia','Clark','sophia@example.com','2106789012','67890',1,'light','2026-01-07 17:27:20'),(7,'michael_lee','6ff0a0481fa31b48fc58580ffa183b31547201134b82e9b248d4366f07c275882c7e8578cf12ea511f0bb60d30ed3961b7654b962f9ec9831d13f391a73942d2','Michael','Lee','michael@example.com','2107890123','78901',1,'light','2026-01-07 17:27:20'),(8,'elena_martin','931f5571e159c67cca68d9869cddb5e91c81fcfcd4674013684318e9a29cc572b288145cf167cfce2d1ab8b018f66bd62d20112a9cb3c23f2a66ab6e1a47e110','Elena','Martin','elena@example.com','2108901234','89012',1,'light','2026-01-07 17:27:20'),(9,'david_white','9dafc199be5a840a815b0b692e1568ae3e3bd91475c5ad1905bb576dd43295364b0e346ddb401e2caac57688d699a1cc17aae319dd0fa7769f2f1201d7e27eda','David','White','david@example.com','2109012345','90123',1,'light','2026-01-07 17:27:20'),(10,'lisa_hall','b3fd82ca55daa7b821f51587729a80533069ad56a050b75c43d5780db3b0b6ae39f59b0ebb1c2d9d1b73a8a2c1d5728a06ddc1a9ceb349528ac4743a970c7d0a','Lisa','Hall','lisa@example.com','2100123456','01234',1,'light','2026-01-07 17:27:20');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-07 20:34:09
