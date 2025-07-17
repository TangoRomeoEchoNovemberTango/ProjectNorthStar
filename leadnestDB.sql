-- MySQL dump 10.13  Distrib 8.0.42, for Win64 (x86_64)
--
-- Host: 50.6.109.204    Database: kgcljxte_copilot
-- ------------------------------------------------------
-- Server version	8.0.42-33

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
-- Table structure for table `calls`
--

DROP TABLE IF EXISTS `calls`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `calls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `contact_id` int NOT NULL,
  `user_id` int NOT NULL,
  `call_time` datetime NOT NULL,
  `duration` int DEFAULT '0',
  `summary` text,
  `outcome` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `contact_id` (`contact_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `calls_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`),
  CONSTRAINT `calls_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `calls`
--

LOCK TABLES `calls` WRITE;
/*!40000 ALTER TABLE `calls` DISABLE KEYS */;
INSERT INTO `calls` VALUES (4,6,1,'2025-07-07 22:02:00',30,'no summary','no answer','2025-07-07 22:02:30'),(5,6,1,'2025-07-09 23:13:00',60,'call was answered','answered','2025-07-09 23:15:21'),(6,16,1,'2025-07-15 23:01:00',0,'','','2025-07-16 01:01:07'),(7,17,1,'2025-07-15 23:51:00',0,'','','2025-07-16 01:51:43'),(8,18,1,'2025-07-15 23:54:00',0,'','','2025-07-16 01:54:37'),(9,19,1,'2025-07-16 00:37:00',0,'','','2025-07-16 02:37:56');
/*!40000 ALTER TABLE `calls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_property`
--

DROP TABLE IF EXISTS `contact_property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_property` (
  `contact_id` int NOT NULL,
  `property_id` int NOT NULL,
  PRIMARY KEY (`contact_id`,`property_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `contact_property_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contact_property_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_property`
--

LOCK TABLES `contact_property` WRITE;
/*!40000 ALTER TABLE `contact_property` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `street_address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` char(2) DEFAULT NULL,
  `zip` varchar(10) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `notes` text,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `type` enum('seller','buyer','buyers_agent','sellers_agent','cash_buyer','title_company','municipality','other') NOT NULL DEFAULT 'other',
  `property_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_contacts_property` (`property_id`),
  KEY `fk_contacts_user` (`user_id`),
  CONSTRAINT `fk_contacts_property` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_contacts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (3,1,'Butt','My','Fuck','123 main st','phoenix','AZ','85383','Buyer','555-555-5555','test@test.com','no notes','2025-07-07 19:08:31','other',NULL),(5,1,'Eat','My','Ass','123 main st','phoenix','AZ','85383','Buyer','555-555-5555','test@test.com','no notes','2025-07-07 21:49:16','seller',NULL),(6,1,'Trent','','Herring','742 Evergreen Terrace','Springfield','IL','62701','Buyer','6028459083','tre2020067@gmail.com','no notes','2025-07-07 21:49:42','other',NULL),(7,1,'Eat','My','Ass','122 fake st','Peoria','IL','85383','Buyer','6028459083','fuckoff@gmail.com','','2025-07-08 17:21:21','other',NULL),(8,1,'Trent','','Herring','742 Evergreen Terrace','Springfield','IL','62701','Buyer','6028459083','tre2020067@gmail.com','this is a new contact','2025-07-09 23:15:41','other',NULL),(9,1,'Shit',NULL,'Stick',NULL,NULL,NULL,NULL,NULL,'555-555-1234','dingleberry@asshole.com',NULL,'2025-07-13 21:34:20','other',NULL),(10,1,'Gaping',NULL,'Asshole',NULL,NULL,NULL,NULL,NULL,'4444444444','ouch@butt.com',NULL,'2025-07-13 23:19:11','other',NULL),(12,1,'Trent','','Herring',NULL,NULL,NULL,NULL,NULL,'6028459083','tre2020067@gmail.com','','2025-07-15 04:34:27','seller',NULL),(13,1,'i','hate','my life',NULL,NULL,NULL,NULL,NULL,'7704557141','damn@fuck.com','none','2025-07-15 04:38:55','seller',NULL),(14,1,'Trent','','Herring',NULL,NULL,NULL,NULL,NULL,'6028459083','tre2020067@gmail.com','','2025-07-15 19:19:10','seller',NULL),(15,1,'Trent','','Herring',NULL,NULL,NULL,NULL,NULL,'6028459083','tre2020067@gmail.com','','2025-07-15 20:27:49','seller',NULL),(16,1,'Trent','','Herring',NULL,NULL,NULL,NULL,NULL,'6028459083','tre2020067@gmail.com','','2025-07-16 01:00:51','seller',NULL),(17,1,'Trent','','Herring',NULL,NULL,NULL,NULL,NULL,'6028459083','tre2020067@gmail.com','','2025-07-16 01:50:43','seller',NULL),(18,1,'T-rent','','Money',NULL,NULL,NULL,NULL,NULL,'6028459083','money@now.com','','2025-07-16 01:53:54','seller',NULL),(19,1,'Trent1','','Herring1',NULL,NULL,NULL,NULL,NULL,'6028459081','1tre2020067@gmail.com','','2025-07-16 02:37:22','seller',NULL);
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `deals`
--

DROP TABLE IF EXISTS `deals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_id` int NOT NULL,
  `property_id` int NOT NULL,
  `stage` enum('lead','offer_made','under_contract','due_diligence','closed','lost') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'lead',
  `amount` decimal(12,2) DEFAULT NULL,
  `offer_date` date DEFAULT NULL,
  `close_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `contact_id` (`contact_id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `deals_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `deals_ibfk_2` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `deals`
--

LOCK TABLES `deals` WRITE;
/*!40000 ALTER TABLE `deals` DISABLE KEYS */;
/*!40000 ALTER TABLE `deals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `file_name` varchar(255) NOT NULL,
  `file_type` varchar(100) NOT NULL,
  `file_size` int NOT NULL,
  `file_data` longblob NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_assignments`
--

DROP TABLE IF EXISTS `email_assignments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_assignments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `campaign_id` int NOT NULL,
  `contact_id` int NOT NULL,
  `tag_filter` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  CONSTRAINT `email_assignments_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `email_campaigns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_assignments`
--

LOCK TABLES `email_assignments` WRITE;
/*!40000 ALTER TABLE `email_assignments` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_assignments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_campaigns`
--

DROP TABLE IF EXISTS `email_campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_campaigns` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `campaign_type` enum('blast','drip') DEFAULT 'blast',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_campaigns`
--

LOCK TABLES `email_campaigns` WRITE;
/*!40000 ALTER TABLE `email_campaigns` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_messages`
--

DROP TABLE IF EXISTS `email_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `campaign_id` int NOT NULL,
  `subject_line` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `delay_days` int DEFAULT '0',
  `position` int DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `campaign_id` (`campaign_id`),
  CONSTRAINT `email_messages_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `email_campaigns` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_messages`
--

LOCK TABLES `email_messages` WRITE;
/*!40000 ALTER TABLE `email_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_queue`
--

DROP TABLE IF EXISTS `email_queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_queue` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `contact_id` int NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `sent_at` datetime DEFAULT NULL,
  `status` enum('queued','sent','failed') DEFAULT 'queued',
  PRIMARY KEY (`id`),
  KEY `message_id` (`message_id`),
  CONSTRAINT `email_queue_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `email_messages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_queue`
--

LOCK TABLES `email_queue` WRITE;
/*!40000 ALTER TABLE `email_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_id` int NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `motivation` text COLLATE utf8mb4_unicode_ci,
  `timeline` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `condition` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `arv` decimal(12,2) NOT NULL DEFAULT '0.00',
  `mao` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`),
  KEY `contact_id` (`contact_id`),
  CONSTRAINT `properties_ibfk_1` FOREIGN KEY (`contact_id`) REFERENCES `contacts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` VALUES (1,'28e13fa86cdb605d0867f9bcc2d8bc62',16,'9026 W Villa Lindo Dr, Peoria, AZ 85383','','','','',0.00,0.00,0.00,'2025-07-16 00:58:24'),(2,'11be95b07181a764ee8429c51aebb433',18,'917 Harold Dr, Incline Village, NV 89451','','','','',0.00,0.00,0.00,'2025-07-16 01:54:07'),(3,'135576db8f060ed1027f4cba818938a6',18,'2172 Polar Rock Terrace SW, Atlanta, GA 30315','','','','',1.00,1.00,0.00,'2025-07-16 02:36:16'),(4,'c0d48de65a3145e3446346510e1880cf',19,'930 Cavanaugh Dr, Reno, NV 89509','','','','',0.00,0.00,0.00,'2025-07-16 02:37:34');
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property_documents`
--

DROP TABLE IF EXISTS `property_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `property_documents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `property_id` int NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_data` longblob NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `property_id` (`property_id`),
  CONSTRAINT `property_documents_ibfk_1` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property_documents`
--

LOCK TABLES `property_documents` WRITE;
/*!40000 ALTER TABLE `property_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `property_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_posts`
--

DROP TABLE IF EXISTS `social_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_posts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `uuid` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scheduled_at` datetime NOT NULL,
  `status` enum('pending','sent','error') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `result` text COLLATE utf8mb4_unicode_ci,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_posts`
--

LOCK TABLES `social_posts` WRITE;
/*!40000 ALTER TABLE `social_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','dummyhash',NULL,'2025-07-07 21:56:23');
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

-- Dump completed on 2025-07-16 17:38:28
