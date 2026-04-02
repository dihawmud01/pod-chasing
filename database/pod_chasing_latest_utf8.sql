-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: pod_chasing
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_02_24_000001_create_vessels_table',1),(5,'2026_03_10_000001_create_prospects_table',2),(6,'2026_03_11_000001_add_prospect_date_to_prospects_table',3),(7,'2026_03_11_000002_update_prospects_eta_to_datetime',4),(8,'2026_03_11_000003_add_section_to_prospects_table',5),(9,'2026_03_12_000001_update_prospects_delivery_date_to_datetime',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prospects`
--

DROP TABLE IF EXISTS `prospects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prospects` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `prospect_date` date DEFAULT NULL,
  `section` enum('nl_be','eu_gb') NOT NULL DEFAULT 'nl_be',
  `vessel_name` varchar(255) NOT NULL,
  `port` varchar(255) DEFAULT NULL,
  `eta` datetime DEFAULT NULL,
  `etb` datetime DEFAULT NULL,
  `etd` datetime DEFAULT NULL,
  `destination_country` varchar(255) DEFAULT NULL,
  `forwarder` varchar(255) DEFAULT NULL,
  `delivery_date` datetime DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'planning',
  `customs_note` varchar(500) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospects`
--

LOCK TABLES `prospects` WRITE;
/*!40000 ALTER TABLE `prospects` DISABLE KEYS */;
INSERT INTO `prospects` VALUES (1,'2026-03-10','nl_be','Example','PORT: ROTTERDAM','2026-03-12 00:00:00','2026-03-14 00:00:00','2026-03-16 00:00:00','NETHERLANDS','AGENT','2026-03-14 00:00:00','completed',NULL,'NOTES','2026-03-10 09:45:05','2026-03-10 09:45:28'),(2,'2026-03-10','nl_be','PODLASIE','PORT: LISBON','2026-03-12 00:00:00','2026-03-12 00:00:00','2026-03-13 00:00:00','PORTUGAL','TD ELKEYT','2026-03-12 00:00:00','completed',NULL,'CONFIRMED','2026-03-10 09:49:02','2026-03-10 09:49:10'),(3,'2026-03-12','nl_be','PODLASIE','PORT: LISBON','2026-04-17 00:00:00','2026-03-18 00:00:00','2026-03-24 00:00:00','PORTUGAL','AGENT','2026-03-25 00:00:00','delayed',NULL,NULL,'2026-03-11 06:41:21','2026-03-11 06:52:58');
/*!40000 ALTER TABLE `prospects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vessels`
--

DROP TABLE IF EXISTS `vessels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vessels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `vessel_name` varchar(255) NOT NULL,
  `driver` varchar(255) DEFAULT NULL,
  `delivery_address` varchar(255) DEFAULT NULL,
  `information` text DEFAULT NULL,
  `so_numbers` text DEFAULT NULL,
  `customs_doc` tinyint(1) NOT NULL DEFAULT 0,
  `print_status` tinyint(1) NOT NULL DEFAULT 0,
  `pod_status` tinyint(1) NOT NULL DEFAULT 0,
  `delivered` tinyint(1) NOT NULL DEFAULT 0,
  `pod_file` varchar(255) DEFAULT NULL,
  `report_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vessels`
--

LOCK TABLES `vessels` WRITE;
/*!40000 ALTER TABLE `vessels` DISABLE KEYS */;
INSERT INTO `vessels` VALUES (1,'HORIZON ARCTIC','TML HOLLAND','PORT LA NOUVELLE','Follow up delivery status',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:10:05'),(2,'TERVEL','TD Elkey sp. z o.o','SKAGEN','Follow up delivery status',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 16:55:54'),(3,'BULK FINLAND','Logidix GTM','GIJON','Follow up delivery status',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 16:56:04'),(4,'ALBERTO TOPIC','Kelly European Freight','BELFAST','Follow up delivery status',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(5,'LUZON SPIRIT','LIMANi Export Warehouse Algeciras','GIBRALTAR','Follow up with Mariano',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(6,'HOEGH TROVE','ELK TRANSPORT INTERNATIONAL','ANTWERP','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(7,'GIANNA','Zargood','AMSTERDAM','Follow up delivery status',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(8,'BANGLAR ARJAN','TD Elkey sp. z o.o','SKAGEN','Follow up delivery status',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(9,'AOM SVEVA','Newflow Logistics Sp. z o.o.','HAMBURG','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(10,'MANISA CAMILLA','TML HOLLAND','SETE','DELIVERED and waiting for POD',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(11,'SUMINISTROS DE LA NAVE','LIMANi Export Warehouse Algeciras','Algeciras Logistic Solutions ALS','Follow up with Mariano',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(12,'HAV Marlin','Van der Mark Transport','MOERDIJK','Follow up delivery status',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(13,'AURORA ONE','Tymczyj Logistics','BAYONNE','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(14,'AMBER SPIRIT','Chandler Consolidated Services','ROTTERDAM','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(15,'GENCO AQUITAINE','LIMANi Export Warehouse Algeciras','GIBRALTAR','Follow up with Mariano',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(16,'PASCO BERCEM','TML HOLLAND','LAVERA','DELIVERED and waiting for POD',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(17,'BRASSIANA','Chandler Consolidated Services','BARENDRECHT','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(18,'KIVALLIQ W.','Chandler Consolidated Services','HOOFDDORP','Waiting in customs email',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(19,'BOS TETHYS','TIPSA-LOGISTICA COSTA DORADA 2014 SL','VINAROS','Follow up delivery status',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(20,'COASTALWATER','ELK TRANSPORT INTERNATIONAL','GHENT','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(21,'LEALE','TOUR MENSAJEROS EXPRESS SL','ALGECIRAS','DELIVERED and waiting for POD',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(22,'SEAWAY VENTUS','COURIER','ROZENBURG','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(23,'KNUD','TD Elkey sp. z o.o','SKAGEN','Follow up delivery status',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(24,'PATAGONMAN','Chandler Consolidated Services','Spijkenisse','DELIVERED',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(25,'KAUNAS','LIMANi Export Warehouse Algeciras','ALGECIRAS','Follow up with Mariano',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(26,'BALTIC JASMINE','BALTIC JASMINE','SKAGEN','Follow up delivery status',NULL,0,0,0,0,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(27,'DUBAI ATTRACTION','Tymczyj Logistics','HUELVA','DELIVERED and waiting for POD',NULL,0,0,0,1,NULL,'2026-02-21','2026-02-24 17:47:03','2026-02-24 17:47:03'),(28,'HORIZON ARCTIC','TD ELKEY','ROTTERDAM','DELIVERED',NULL,1,1,1,1,NULL,'2026-02-24','2026-02-24 16:55:17','2026-02-24 17:10:41'),(29,'TERVEL','TD Elkey','L&N Supply Ships','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:53:00','2026-02-25 13:20:45'),(30,'MED NORDIC','Chandler Consolidate Service','SSL ROTTERDAM','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:53:45','2026-02-25 15:14:25'),(31,'GIANNA','Chandler Consolidate Service','SSL AMSTERDAM','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:54:18','2026-02-25 16:00:04'),(32,'OCEAN EXPLORER','Chandler Consolidate Service','c/o Neele-vat','Waiting for the POD on Customs mail',NULL,1,1,0,0,NULL,'2026-02-25','2026-02-25 12:55:16','2026-02-25 12:55:16'),(33,'INEOS INSPIRATION','Chandler Consolidate Service','C/O Loyal Cargo Services','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:55:46','2026-02-25 15:57:36'),(34,'TOMINI MISTRAL','Chandler Consolidate Service','C/O PAC OCEAN','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:56:10','2026-02-25 15:58:13'),(35,'VEGA CLARA','Ritmeester B.V','c/o Hellmann Worldwide','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:56:58','2026-02-25 17:39:26'),(36,'VEGA CHRISTINA','Ritmeester B.V','c/o Hellmann Worldwide','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:57:17','2026-02-25 18:01:18'),(37,'ALSTERDEIP','SERMACO VALENCIA','PORT','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 12:59:40','2026-02-25 16:26:35'),(38,'LANGENESS','SERMACO VALENCIA','PORT: VALENCIA','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 13:00:46','2026-02-25 13:19:06'),(39,'CONTSHIP IVY','SERMACO VALENCIA','PORT: VALENCIA','Delivered, waiting for POD',NULL,1,1,0,1,NULL,'2026-02-25','2026-02-25 13:01:16','2026-02-25 18:03:08'),(40,'BULK FINLAND','TIPSA','PORT: GIJON','Delivered & POD Received',NULL,1,1,0,1,NULL,'2026-02-25','2026-02-25 13:01:54','2026-02-25 18:05:29'),(41,'FT QUINTO','TOUR MENSAJEROS EXPRESS SL','PORT: HUELVA','Delivered, waiting for POD',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 13:02:22','2026-02-25 18:05:26'),(42,'JERA','TOUR MENSAJEROS EXPRESS SL','PORT: GARRUCHA','Delivered, waiting for POD',NULL,1,1,0,1,NULL,'2026-02-25','2026-02-25 13:02:41','2026-02-25 18:03:03'),(43,'CAPE ANN','TIPSA','PORT: TARRAGONA','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 13:02:59','2026-02-25 18:02:34'),(44,'NEW PRESTIGE','MARINE SERVICE MEDITERRANEAN S.L.','PORT: TARRAGONA','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 13:03:23','2026-02-25 18:03:26'),(45,'SIDER LIU','TOUR MENSAJEROS EXPRESS SL','PORT: ALMERIA','Delivered, waiting for POD',NULL,1,1,0,1,NULL,'2026-02-25','2026-02-25 13:03:48','2026-02-25 16:11:06'),(46,'VERONICA B','TOUR MENSAJEROS EXPRESS SL','PORT: SEVILLA','Delivered & POD Received',NULL,1,1,1,1,NULL,'2026-02-25','2026-02-25 13:04:05','2026-02-25 16:11:08'),(47,'ATLANTIC SPIRIT II','BASE LOGISTICS','CROSS FREIGHT','Delivered, waiting for POD',NULL,0,0,0,1,NULL,'2026-02-25','2026-02-25 15:17:15','2026-02-25 15:17:15'),(48,'OSLO BULK 2','BASE LOGISTICS','Central Dispatch, Inc.','Expected Delivered tomorrow',NULL,0,0,0,1,NULL,'2026-02-25','2026-02-25 15:20:17','2026-02-25 18:03:18'),(49,'EXPEDITION','Chandler Consolidate Service','c/o Neele-vat','Delivered & POD Received',NULL,0,0,1,1,NULL,'2026-02-25','2026-02-25 15:22:17','2026-02-25 16:11:12'),(50,'ALSTERDEIP','SERMACO VALENCIA','PORT: VALENCIA','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-26','2026-02-26 12:45:22','2026-02-27 13:33:05'),(51,'BORDEAUX','ALGECIRAS LOGISTIC SOLUTIONS ALS','PORT: ALGECIRAS','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-26','2026-02-26 12:46:05','2026-02-26 15:42:23'),(52,'ATLANTA','TD Elkey','PORT: RAVENNA','Delivered at Place, ready to unload',NULL,0,0,0,1,NULL,'2026-02-26','2026-02-26 12:49:37','2026-02-26 15:42:22'),(53,'BALTIC SPRING','Tymczyj Logistics','L&N Supply Ships','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-26','2026-02-26 12:52:16','2026-02-26 15:42:19'),(54,'GATTACA','Tymczyj Logistics','L&N Supply Ships','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-26','2026-02-26 12:53:28','2026-02-26 15:42:19'),(55,'NEPHIRA','Tymczyj Logistics','L&N Supply Ships','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-26','2026-02-26 12:53:45','2026-02-26 15:42:17'),(56,'BULK SWEDEN','Ritmeester B.V','PORT: HAMBURG','Followed up, waiting next information',NULL,0,0,0,0,NULL,'2026-02-26','2026-02-26 12:56:03','2026-02-26 12:56:03'),(57,'OCEAN EXPLORER','Chandler Consolidate Service','PORT: ANTWERP','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-26','2026-02-26 13:02:19','2026-02-26 15:56:01'),(58,'MAERSK IOWA','Chandler Consolidate Service','PORT: ANTWERP','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-26','2026-02-26 13:02:44','2026-02-26 15:56:00'),(59,'MANTA MELEK','Chandler Consolidate Service','PORT: ANTWERP','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-26','2026-02-26 13:03:00','2026-02-26 15:56:00'),(60,'ETHRA GOLD','Ritmeester B.V','PORT: ANTWERP','Loading today, expected to deliver tomorrow',NULL,0,0,0,0,NULL,'2026-02-26','2026-02-26 13:03:37','2026-02-26 15:42:15'),(61,'HAFNIA AMETRINE','Ritmeester B.V','SSL ROTTERDAM','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-26','2026-02-26 13:04:10','2026-02-26 15:42:27'),(62,'CONSTANCE','Chandler Consolidate Service','PORT: ZEEBRUGGE','Vessel already sailed, the shipment is returned to WH',NULL,0,0,0,0,NULL,'2026-02-26','2026-02-26 13:06:10','2026-02-26 14:13:19'),(63,'MONJASA THUNDER','RELEASE','SEALOGIC B.V.','ALREADY PICKED UP BY BTS LOGISTICS',NULL,0,0,0,0,NULL,'2026-02-26','2026-02-26 13:10:39','2026-02-26 13:10:39'),(64,'ETHRA GOLD','Ritmeester B.V','PORT: ANTWERP','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 12:54:21','2026-02-27 14:24:46'),(65,'PAN QUEST','Chandler Consolidated Services','EUCO INTERNATIONAL','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 13:22:20','2026-02-27 13:22:20'),(66,'CORRIB FISHER','Chandler Consolidated Services','Engine Assist B.V','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 13:22:48','2026-02-27 13:22:48'),(67,'BALTIC ECLIPSE','Ritmeester B.V','C/O EMDER','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:27:10','2026-02-27 14:50:15'),(68,'FIONA B','Ritmeester B.V','ELB LOGISTICS','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:29:11','2026-02-27 14:50:58'),(69,'VEGA LUISE','Ritmeester B.V','NORDERSTEDT','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:30:17','2026-02-27 14:50:39'),(70,'VEGA PHILIPPA','Ritmeester B.V','NORDERSTEDT','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:30:33','2026-02-27 14:51:08'),(71,'ECHO ELISA','Ritmeester B.V','NORDERSTEDT','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:30:48','2026-02-27 14:51:15'),(72,'HAV ECOTRADER 1','Ritmeester B.V','NORDERSTEDT','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:31:10','2026-02-27 14:51:23'),(73,'HAV ECOTRADER 3','Ritmeester B.V','NORDERSTEDT','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:31:25','2026-02-27 14:51:30'),(74,'HAV ECOTRADER 4','Ritmeester B.V','NORDERSTEDT','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:31:38','2026-02-27 14:51:38'),(75,'PUFFIN ARROW','TFT LOGISTICS','PORT: MONFALCONE','Followed up, waiting next information',NULL,0,0,0,0,NULL,'2026-02-27','2026-02-27 14:32:54','2026-02-27 14:32:54'),(76,'JAY','Ritmeester B.V','PORT: ANTWERP','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:33:28','2026-02-27 14:33:28'),(77,'DETROIT EXPRESS','Ritmeester B.V','PORT: ANTWERP','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:34:12','2026-02-27 14:34:12'),(78,'SM BLUE BIRD','Chandler Consolidated Services','FUJI TRANSPORT','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 14:43:58','2026-02-27 14:43:58'),(79,'UHL FABLE','WEN TRANSPORT','PORT: CHERBOURG','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 14:44:57','2026-02-27 14:44:57'),(80,'UHL FREEDOM','JP TRANSPORT B.V.','PORT: ROTTERDAM','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 14:45:47','2026-02-27 14:45:47'),(81,'YASA CANARY','Tymczyj Logistics','PORT: SZCZECIN','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 14:48:41','2026-03-02 07:49:44'),(82,'AFINA I','BIREXPRESS SRL','PORT: AVEIRO','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 15:02:07','2026-03-02 07:38:03'),(83,'AZAMARA JOURNEY','LIMANi Export Warehouse Algeciras','PORT: SEVILLA','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 15:02:37','2026-02-27 15:02:37'),(84,'BLUE LAGOON','GRUPAJES NOROESTE','PORT: FERROL','Deliver on Monday',NULL,0,0,0,0,NULL,'2026-02-27','2026-02-27 15:04:22','2026-02-27 16:57:17'),(85,'ALISIOS LNG','TIPSA-LOGISTICA COSTA DORADA 2014 SL','PORT: HUELVA','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-02-27','2026-02-27 15:04:50','2026-02-27 16:58:14'),(86,'PINNACLE SPIRIT','TRANSPORTES SEBASTIAN DE CASTRO','PORT: CARTAGENA','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-02-27','2026-02-27 15:07:01','2026-02-27 15:07:01'),(87,'RDO CONCORD','SERMACO VALENCIA','PORT: VALENCIA','Will deliver tomorrow',NULL,0,0,0,0,NULL,'2026-02-27','2026-02-27 15:08:34','2026-02-27 16:59:16'),(88,'RDO CONCORD','TIPSA-LOGISTICA COSTA DORADA 2014 SL','PORT: VALENCIA','Followed up, waiting next information',NULL,0,0,0,0,NULL,'2026-02-27','2026-02-27 15:08:48','2026-02-27 15:08:48'),(89,'BALTIC SPRING','Tymczyj Logistics','PORT: SKAGEN','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:33:24','2026-03-02 13:35:55'),(90,'BEAUMAGIC','Tymczyj Logistics','PORT: PLYMOUTH','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-03-02','2026-03-02 13:34:44','2026-03-02 16:00:41'),(91,'BBC VESUVIUS','Tymczyj Logistics','PORT: ESBJERG','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:35:46','2026-03-02 13:35:46'),(92,'PCT ARTEMIS','Chandler Consolidated Services','SHIP SPARES LOGISTICS','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:36:28','2026-03-02 13:36:28'),(93,'HAV ZANDER','JP TRANSPORT B.V.','PORT: VLISSINGEN','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:37:22','2026-03-02 13:50:19'),(94,'OCEAN EXPLORER','Chandler Consolidated Services','PORT: ANTWERP','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:39:57','2026-03-02 14:13:52'),(95,'MERBABU','WEN TRANSPORT','PORT: FOS SUR MER','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:40:56','2026-03-02 13:40:56'),(96,'NACC INDIAN','BEEDEE EXPRESS','PORT: ELLESMERE','Will deliver tomorrow',NULL,0,0,0,0,NULL,'2026-03-02','2026-03-02 13:42:21','2026-03-02 13:42:21'),(97,'ONE OLYMPUS','Chandler Consolidated Services','PORT: ROTTERDAM','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:42:58','2026-03-03 10:42:47'),(98,'BALTIC SUMMER','Chandler Consolidated Services','DIESEL INJECTION VLIEGENTHART BV','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:43:55','2026-03-02 14:11:56'),(99,'PASCO BERCEM','WEN TRANSPORT','PORT: LAVERA','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:44:31','2026-03-02 13:44:31'),(100,'POL STELLA','Zargood','PORT: GENOA','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:45:34','2026-03-02 16:01:09'),(101,'SANTA VENERA','Ritmeester B.V','VERENIGDE BOOTLIEDEN BV','Delivered, Waiting for POD',NULL,0,0,0,1,NULL,'2026-03-02','2026-03-02 13:47:57','2026-03-02 14:15:32'),(102,'SIRIOS CEMENT I TBR GRIS CEMENT I','Zargood','PORT: PORTO MARGHERA','Followed up, waiting next information',NULL,0,0,0,0,NULL,'2026-03-02','2026-03-02 13:48:35','2026-03-02 13:48:35'),(103,'HAV ECOTRADER 2','Ritmeester B.V','C/O HELLMANN WORLDWIDE LOGISTICS GERMANY GMBH & CO. KG','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:51:26','2026-03-02 13:51:26'),(104,'ARTVIN','TIPSA-LOGISTICA COSTA DORADA 2014 SL','PORT: ALICANTE','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:52:20','2026-03-02 13:52:20'),(105,'AVIONA','LIMANi Export Warehouse Algeciras','PORT: HUELVA','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 13:56:36','2026-03-02 13:56:36'),(106,'SIDER HARMONY','TIPSA-LOGISTICA COSTA DORADA 2014 SL','PORT: CASTELLON','Followed up, waiting next information',NULL,0,0,0,0,NULL,'2026-03-02','2026-03-02 13:59:55','2026-03-02 13:59:55'),(107,'PODLASIE','GRUPO V3 SOLUTION SL','PORT: LISBON','Delivered, POD Received',NULL,0,0,1,1,NULL,'2026-03-02','2026-03-02 14:00:57','2026-03-03 10:42:32'),(108,'Example','AGENT','NETHERLANDS',NULL,NULL,0,0,0,0,NULL,'2026-03-14','2026-03-10 09:45:28','2026-03-10 09:45:28'),(109,'PODLASIE','TD ELKEYT','PORTUGAL',NULL,NULL,0,0,0,0,NULL,'2026-03-12','2026-03-10 09:49:10','2026-03-10 09:49:10');
/*!40000 ALTER TABLE `vessels` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-12  8:53:38
