-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: 127.0.0.1    Database: apotek_makmur_jaya
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
-- Current Database: `apotek_makmur_jaya`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `apotek_makmur_jaya` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `apotek_makmur_jaya`;

--
-- Table structure for table `batch_obat`
--

DROP TABLE IF EXISTS `batch_obat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `batch_obat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `obat_id` bigint(20) unsigned NOT NULL,
  `jumlah_masuk` int(10) unsigned NOT NULL,
  `sisa` int(10) unsigned NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `tanggal_kedaluwarsa` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `batch_obat_obat_id_tanggal_masuk_index` (`obat_id`,`tanggal_masuk`),
  KEY `batch_obat_obat_id_tanggal_kedaluwarsa_index` (`obat_id`,`tanggal_kedaluwarsa`),
  CONSTRAINT `batch_obat_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=88 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_obat`
--

LOCK TABLES `batch_obat` WRITE;
/*!40000 ALTER TABLE `batch_obat` DISABLE KEYS */;
INSERT INTO `batch_obat` VALUES (1,1,80,77,'2026-06-27','2027-10-27','2026-08-27 02:52:23','2026-08-27 03:41:33'),(2,2,40,40,'2026-07-27','2027-04-27','2026-08-27 02:52:23','2026-08-27 02:52:23'),(3,3,35,34,'2026-07-18','2027-06-27','2026-08-27 02:52:23','2026-08-27 03:41:33'),(4,4,8,8,'2026-05-27','2027-02-27','2026-08-27 02:52:23','2026-08-27 02:52:23'),(5,5,50,50,'2026-03-27','2026-09-21','2026-08-27 02:52:23','2026-08-27 02:52:23'),(6,6,22,22,'2026-08-17','2028-02-27','2026-08-27 02:52:23','2026-08-27 02:52:23'),(7,1,30,30,'2026-08-27','2027-08-27','2026-08-27 02:52:23','2026-08-27 02:52:23'),(8,7,40,40,'2026-08-27','2027-03-01','2026-08-27 03:04:10','2026-08-27 03:04:10'),(9,8,35,35,'2026-08-27','2027-05-12','2026-08-27 03:04:10','2026-08-27 03:04:10'),(10,9,28,27,'2026-08-27','2026-12-15','2026-08-27 03:04:10','2026-08-27 03:34:11'),(11,10,12,12,'2026-08-27','2027-01-20','2026-08-27 03:04:10','2026-08-27 03:04:10'),(12,11,60,60,'2026-08-27','2027-08-20','2026-08-27 03:04:10','2026-08-27 03:04:10'),(13,12,45,45,'2026-08-27','2027-06-30','2026-08-27 03:04:10','2026-08-27 03:04:10'),(14,13,80,80,'2026-08-27','2027-04-18','2026-08-27 03:04:10','2026-08-27 03:04:10'),(15,14,100,100,'2026-08-27','2027-09-01','2026-08-27 03:04:10','2026-08-27 03:04:10'),(16,15,50,50,'2026-08-27','2026-11-10','2026-08-27 03:04:10','2026-08-27 03:04:10'),(17,16,18,16,'2026-08-27','2026-10-05','2026-08-27 03:04:10','2026-08-27 03:42:52'),(18,17,30,30,'2026-08-27','2027-02-28','2026-08-27 03:04:10','2026-08-27 03:04:10'),(19,18,55,55,'2026-08-27','2027-07-15','2026-08-27 03:04:10','2026-08-27 03:04:10'),(20,19,42,42,'2026-08-27','2027-03-22','2026-08-27 03:04:11','2026-08-27 03:04:11'),(21,20,20,20,'2026-08-27','2028-01-01','2026-08-27 03:04:11','2026-08-27 03:04:11'),(22,21,25,25,'2026-08-27','2027-12-12','2026-08-27 03:04:11','2026-08-27 03:04:11'),(23,22,120,120,'2026-08-27','2027-05-01','2026-08-27 03:04:11','2026-08-27 03:04:11'),(24,23,70,70,'2026-08-27','2027-08-08','2026-08-27 03:04:11','2026-08-27 03:04:11'),(25,24,90,90,'2026-08-27','2026-09-30','2026-08-27 03:04:11','2026-08-27 03:04:11'),(26,25,65,64,'2026-08-27','2027-01-15','2026-08-27 03:04:11','2026-08-27 03:24:40'),(27,26,48,48,'2026-08-27','2027-04-04','2026-08-27 03:04:11','2026-08-27 03:04:11'),(28,27,55,55,'2026-08-27','2027-06-01','2026-08-27 03:04:11','2026-08-27 03:04:11'),(29,28,33,33,'2026-08-27','2027-02-14','2026-08-27 03:04:11','2026-08-27 03:04:11'),(30,29,40,40,'2026-08-27','2028-03-01','2026-08-27 03:04:11','2026-08-27 03:04:11'),(31,30,22,22,'2026-08-27','2027-11-20','2026-08-27 03:04:11','2026-08-27 03:04:11'),(32,31,200,200,'2026-08-27','2029-01-01','2026-08-27 03:04:11','2026-08-27 03:04:11'),(33,32,75,73,'2026-08-27','2028-06-15','2026-08-27 03:04:11','2026-08-27 03:32:49'),(34,33,90,90,'2026-08-27','2028-12-31','2026-08-27 03:04:11','2026-08-27 03:04:11'),(35,34,35,35,'2026-08-27','2027-09-09','2026-08-27 03:04:11','2026-08-27 03:04:11'),(36,35,25,25,'2026-08-27','2027-07-07','2026-08-27 03:04:11','2026-08-27 03:04:11'),(37,36,60,60,'2026-08-27','2027-10-10','2026-08-27 03:04:11','2026-08-27 03:04:11'),(38,7,40,40,'2026-08-27','2027-03-01','2026-08-27 03:05:03','2026-08-27 03:05:03'),(39,8,35,35,'2026-08-27','2027-05-12','2026-08-27 03:05:03','2026-08-27 03:05:03'),(40,9,28,28,'2026-08-27','2026-12-15','2026-08-27 03:05:03','2026-08-27 03:05:03'),(41,10,12,12,'2026-08-27','2027-01-20','2026-08-27 03:05:03','2026-08-27 03:05:03'),(42,11,60,60,'2026-08-27','2027-08-20','2026-08-27 03:05:03','2026-08-27 03:05:03'),(43,12,45,45,'2026-08-27','2027-06-30','2026-08-27 03:05:03','2026-08-27 03:05:03'),(44,13,80,80,'2026-08-27','2027-04-18','2026-08-27 03:05:03','2026-08-27 03:05:03'),(45,14,100,100,'2026-08-27','2027-09-01','2026-08-27 03:05:03','2026-08-27 03:05:03'),(46,15,50,50,'2026-08-27','2026-11-10','2026-08-27 03:05:03','2026-08-27 03:05:03'),(47,16,18,18,'2026-08-27','2026-10-05','2026-08-27 03:05:03','2026-08-27 03:05:03'),(48,17,30,30,'2026-08-27','2027-02-28','2026-08-27 03:05:03','2026-08-27 03:05:03'),(49,18,55,55,'2026-08-27','2027-07-15','2026-08-27 03:05:03','2026-08-27 03:05:03'),(50,19,42,42,'2026-08-27','2027-03-22','2026-08-27 03:05:03','2026-08-27 03:05:03'),(51,20,20,20,'2026-08-27','2028-01-01','2026-08-27 03:05:03','2026-08-27 03:05:03'),(52,21,25,25,'2026-08-27','2027-12-12','2026-08-27 03:05:03','2026-08-27 03:05:03'),(53,22,120,120,'2026-08-27','2027-05-01','2026-08-27 03:05:04','2026-08-27 03:05:04'),(54,23,70,70,'2026-08-27','2027-08-08','2026-08-27 03:05:04','2026-08-27 03:05:04'),(55,24,90,90,'2026-08-27','2026-09-30','2026-08-27 03:05:04','2026-08-27 03:05:04'),(56,25,65,65,'2026-08-27','2027-01-15','2026-08-27 03:05:04','2026-08-27 03:05:04'),(57,26,48,48,'2026-08-27','2027-04-04','2026-08-27 03:05:04','2026-08-27 03:05:04'),(58,27,55,55,'2026-08-27','2027-06-01','2026-08-27 03:05:04','2026-08-27 03:05:04'),(59,28,33,33,'2026-08-27','2027-02-14','2026-08-27 03:05:04','2026-08-27 03:05:04'),(60,29,40,40,'2026-08-27','2028-03-01','2026-08-27 03:05:04','2026-08-27 03:05:04'),(61,30,22,22,'2026-08-27','2027-11-20','2026-08-27 03:05:04','2026-08-27 03:05:04'),(62,31,200,200,'2026-08-27','2029-01-01','2026-08-27 03:05:04','2026-08-27 03:05:04'),(63,32,75,75,'2026-08-27','2028-06-15','2026-08-27 03:05:04','2026-08-27 03:05:04'),(64,33,90,90,'2026-08-27','2028-12-31','2026-08-27 03:05:04','2026-08-27 03:05:04'),(65,34,35,35,'2026-08-27','2027-09-09','2026-08-27 03:05:04','2026-08-27 03:05:04'),(66,35,25,25,'2026-08-27','2027-07-07','2026-08-27 03:05:04','2026-08-27 03:05:04'),(67,36,60,60,'2026-08-27','2027-10-10','2026-08-27 03:05:04','2026-08-27 03:05:04'),(68,37,18,17,'2026-08-27','2026-07-15','2026-08-27 03:13:46','2026-08-27 03:42:52'),(69,38,9,9,'2026-08-27','2026-06-20','2026-08-27 03:13:47','2026-08-27 03:13:47'),(70,39,12,8,'2026-08-27','2026-08-01','2026-08-27 03:13:47','2026-08-27 03:34:11'),(71,40,25,25,'2026-08-27','2025-12-31','2026-08-27 03:13:47','2026-08-27 03:13:47'),(72,41,7,7,'2026-08-27','2026-05-10','2026-08-27 03:13:47','2026-08-27 03:13:47'),(73,42,30,30,'2026-08-27','2026-09-10','2026-08-27 03:13:47','2026-08-27 03:13:47'),(74,43,22,22,'2026-08-27','2026-09-15','2026-08-27 03:13:47','2026-08-27 03:13:47'),(75,44,40,40,'2026-08-27','2026-09-20','2026-08-27 03:13:47','2026-08-27 03:13:47'),(76,45,14,14,'2026-08-27','2026-09-05','2026-08-27 03:13:47','2026-08-27 03:13:47'),(77,46,28,28,'2026-08-27','2026-10-12','2026-08-27 03:13:47','2026-08-27 03:13:47'),(78,47,16,16,'2026-08-27','2026-09-25','2026-08-27 03:13:47','2026-08-27 03:13:47'),(79,48,35,35,'2026-08-27','2026-10-20','2026-08-27 03:13:47','2026-08-27 03:13:47'),(80,49,20,20,'2026-08-27','2026-10-08','2026-08-27 03:13:47','2026-08-27 03:13:47'),(81,50,45,45,'2026-08-27','2026-10-28','2026-08-27 03:13:47','2026-08-27 03:13:47'),(82,51,33,32,'2026-08-27','2026-10-15','2026-08-27 03:13:47','2026-08-27 03:34:11'),(83,52,38,38,'2026-08-27','2026-11-10','2026-08-27 03:13:47','2026-08-27 03:13:47'),(84,53,50,49,'2026-08-27','2026-11-18','2026-08-27 03:13:47','2026-08-27 03:34:11'),(85,54,18,18,'2026-08-27','2026-11-05','2026-08-27 03:13:47','2026-08-27 03:13:47'),(86,55,27,27,'2026-08-27','2026-11-22','2026-08-27 03:13:47','2026-08-27 03:13:47'),(87,56,24,24,'2026-08-27','2026-11-28','2026-08-27 03:13:47','2026-08-27 03:13:47');
/*!40000 ALTER TABLE `batch_obat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_pesanan`
--

DROP TABLE IF EXISTS `item_pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_pesanan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint(20) unsigned NOT NULL,
  `obat_id` bigint(20) unsigned NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  `harga_satuan` int(10) unsigned NOT NULL,
  `subtotal` int(10) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_pesanan_pesanan_id_foreign` (`pesanan_id`),
  KEY `item_pesanan_obat_id_foreign` (`obat_id`),
  CONSTRAINT `item_pesanan_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`),
  CONSTRAINT `item_pesanan_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_pesanan`
--

LOCK TABLES `item_pesanan` WRITE;
/*!40000 ALTER TABLE `item_pesanan` DISABLE KEYS */;
INSERT INTO `item_pesanan` VALUES (1,1,1,1,3000,3000,'2026-08-27 03:24:17','2026-08-27 03:24:17'),(2,2,39,2,9800,19600,'2026-08-27 03:24:40','2026-08-27 03:24:40'),(3,2,25,1,4000,4000,'2026-08-27 03:24:40','2026-08-27 03:24:40'),(4,3,32,1,9000,9000,'2026-08-27 03:26:20','2026-08-27 03:26:20'),(5,4,39,1,9800,9800,'2026-08-27 03:26:52','2026-08-27 03:26:52'),(6,5,1,1,3000,3000,'2026-08-27 03:27:28','2026-08-27 03:27:28'),(7,5,16,1,22000,22000,'2026-08-27 03:27:28','2026-08-27 03:27:28'),(8,5,32,1,9000,9000,'2026-08-27 03:27:28','2026-08-27 03:27:28'),(9,6,39,1,9800,9800,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(10,6,51,1,5500,5500,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(11,6,9,1,6500,6500,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(12,6,53,1,9000,9000,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(13,7,1,1,3000,3000,'2026-08-27 03:37:21','2026-08-27 03:37:21'),(14,7,3,1,8000,8000,'2026-08-27 03:37:21','2026-08-27 03:37:21'),(15,8,37,1,4500,4500,'2026-08-27 03:41:07','2026-08-27 03:41:07'),(16,8,16,1,22000,22000,'2026-08-27 03:41:07','2026-08-27 03:41:07'),(17,9,3,1,8000,8000,'2026-08-27 03:50:41','2026-08-27 03:50:41');
/*!40000 ALTER TABLE `item_pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
INSERT INTO `job_batches` VALUES ('a29a0b5f-74f4-4394-9cde-b29e2aa813ca','impor-obat-MIG-20260827100314-vjmo',30,0,0,'[]','a:0:{}',NULL,1787824994,1787825051),('a29a0c03-8dbe-4964-adbd-69b515887341','impor-obat-MIG-20260827100502-kupj',30,0,0,'[]','a:0:{}',NULL,1787825102,1787825104),('a29a0f22-951e-416f-b4f1-aeb15acf593e','impor-obat-MIG-20260827101345-xqiv',20,0,0,'[]','a:0:{}',NULL,1787825625,1787825627);
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB AUTO_INCREMENT=93 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori_obat`
--

DROP TABLE IF EXISTS `kategori_obat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategori_obat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_obat_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_obat`
--

LOCK TABLES `kategori_obat` WRITE;
/*!40000 ALTER TABLE `kategori_obat` DISABLE KEYS */;
INSERT INTO `kategori_obat` VALUES (1,'Analgesik','analgesik','2026-08-27 02:52:22','2026-08-27 02:52:22'),(2,'Antibiotik','antibiotik','2026-08-27 02:52:22','2026-08-27 02:52:22'),(3,'Vitamin','vitamin','2026-08-27 02:52:22','2026-08-27 02:52:22'),(4,'Batuk','batuk','2026-08-27 03:04:10','2026-08-27 03:04:10'),(5,'Asma','asma','2026-08-27 03:04:10','2026-08-27 03:04:10'),(6,'Lambung','lambung','2026-08-27 03:04:10','2026-08-27 03:04:10'),(7,'Antihistamin','antihistamin','2026-08-27 03:04:10','2026-08-27 03:04:10'),(8,'Antiseptik','antiseptik','2026-08-27 03:04:11','2026-08-27 03:04:11'),(9,'Diare','diare','2026-08-27 03:04:11','2026-08-27 03:04:11'),(10,'Metabolisme','metabolisme','2026-08-27 03:04:11','2026-08-27 03:04:11'),(11,'Kardiovaskular','kardiovaskular','2026-08-27 03:04:11','2026-08-27 03:04:11'),(12,'Topikal','topikal','2026-08-27 03:04:11','2026-08-27 03:04:11'),(13,'Alkes','alkes','2026-08-27 03:04:11','2026-08-27 03:04:11'),(14,'Steroid','steroid','2026-08-27 03:13:47','2026-08-27 03:13:47');
/*!40000 ALTER TABLE `kategori_obat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_audit`
--

DROP TABLE IF EXISTS `log_audit`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_audit` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `aksi` varchar(60) NOT NULL,
  `objek_tipe` varchar(60) DEFAULT NULL,
  `objek_id` bigint(20) unsigned DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `ip` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_audit_user_id_foreign` (`user_id`),
  KEY `log_audit_objek_tipe_objek_id_index` (`objek_tipe`,`objek_id`),
  CONSTRAINT `log_audit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_audit`
--

LOCK TABLES `log_audit` WRITE;
/*!40000 ALTER TABLE `log_audit` DISABLE KEYS */;
INSERT INTO `log_audit` VALUES (1,1,'masuk','User',1,NULL,'127.0.0.1','2026-08-27 02:55:04','2026-08-27 02:55:04'),(2,1,'migrasi.impor',NULL,NULL,'MIG-20260827100314-vjmo','127.0.0.1','2026-08-27 03:03:14','2026-08-27 03:03:14'),(3,1,'migrasi.impor',NULL,NULL,'MIG-20260827100502-kupj','127.0.0.1','2026-08-27 03:05:02','2026-08-27 03:05:02'),(4,1,'migrasi.impor',NULL,NULL,'MIG-20260827101345-xqiv','127.0.0.1','2026-08-27 03:13:45','2026-08-27 03:13:45'),(5,1,'keluar','User',1,NULL,'127.0.0.1','2026-08-27 03:22:36','2026-08-27 03:22:36'),(6,2,'masuk','User',2,NULL,'127.0.0.1','2026-08-27 03:23:23','2026-08-27 03:23:23'),(7,2,'keluar','User',2,NULL,'127.0.0.1','2026-08-27 03:23:41','2026-08-27 03:23:41'),(8,3,'masuk','User',3,NULL,'127.0.0.1','2026-08-27 03:24:03','2026-08-27 03:24:03'),(9,3,'kasir.jual','Pesanan',1,'PSN-20260827-0001','127.0.0.1','2026-08-27 03:24:17','2026-08-27 03:24:17'),(10,3,'kasir.jual','Pesanan',2,'PSN-20260827-0002','127.0.0.1','2026-08-27 03:24:40','2026-08-27 03:24:40'),(11,3,'kasir.jual','Pesanan',3,'PSN-20260827-0003','127.0.0.1','2026-08-27 03:26:20','2026-08-27 03:26:20'),(12,3,'kasir.jual','Pesanan',4,'PSN-20260827-0004','127.0.0.1','2026-08-27 03:26:52','2026-08-27 03:26:52'),(13,3,'pesanan.buat','Pesanan',5,'PSN-20260827-0005','127.0.0.1','2026-08-27 03:27:28','2026-08-27 03:27:28'),(14,3,'pembayaran.dispatch','Pesanan',5,NULL,'127.0.0.1','2026-08-27 03:27:32','2026-08-27 03:27:32'),(15,3,'pembayaran.dispatch','Pesanan',5,NULL,'127.0.0.1','2026-08-27 03:27:59','2026-08-27 03:27:59'),(16,3,'pembayaran.dispatch','Pesanan',5,NULL,'127.0.0.1','2026-08-27 03:28:16','2026-08-27 03:28:16'),(17,3,'pembayaran.dispatch','Pesanan',5,NULL,'127.0.0.1','2026-08-27 03:29:34','2026-08-27 03:29:34'),(18,NULL,'pembayaran.sukses','Pesanan',5,'PSN-20260827-0005','127.0.0.1','2026-08-27 03:31:24','2026-08-27 03:31:24'),(19,3,'resep.unggah','Pesanan',5,NULL,'127.0.0.1','2026-08-27 03:32:01','2026-08-27 03:32:01'),(20,3,'keluar','User',3,NULL,'127.0.0.1','2026-08-27 03:32:14','2026-08-27 03:32:14'),(21,2,'masuk','User',2,NULL,'127.0.0.1','2026-08-27 03:32:22','2026-08-27 03:32:22'),(22,2,'resep.disetujui','Pesanan',5,NULL,'127.0.0.1','2026-08-27 03:32:47','2026-08-27 03:32:47'),(23,NULL,'stok.fifo','Pesanan',5,'Stok dipotong FIFO untuk PSN-20260827-0005','127.0.0.1','2026-08-27 03:32:49','2026-08-27 03:32:49'),(24,2,'keluar','User',2,NULL,'127.0.0.1','2026-08-27 03:33:01','2026-08-27 03:33:01'),(25,3,'masuk','User',3,NULL,'127.0.0.1','2026-08-27 03:33:08','2026-08-27 03:33:08'),(26,3,'kasir.jual','Pesanan',6,'PSN-20260827-0006','127.0.0.1','2026-08-27 03:34:11','2026-08-27 03:34:11'),(27,3,'keluar','User',3,NULL,'127.0.0.1','2026-08-27 03:35:55','2026-08-27 03:35:55'),(28,4,'masuk','User',4,NULL,'127.0.0.1','2026-08-27 03:36:24','2026-08-27 03:36:24'),(29,4,'pesanan.buat','Pesanan',7,'PSN-20260827-0007','127.0.0.1','2026-08-27 03:37:21','2026-08-27 03:37:21'),(30,4,'pembayaran.dispatch','Pesanan',7,NULL,'127.0.0.1','2026-08-27 03:37:24','2026-08-27 03:37:24'),(31,NULL,'pembayaran.sukses','Pesanan',7,'PSN-20260827-0007','127.0.0.1','2026-08-27 03:37:26','2026-08-27 03:37:26'),(32,4,'resep.unggah','Pesanan',7,NULL,'127.0.0.1','2026-08-27 03:39:53','2026-08-27 03:39:53'),(33,4,'keluar','User',4,NULL,'127.0.0.1','2026-08-27 03:39:59','2026-08-27 03:39:59'),(34,2,'masuk','User',2,NULL,'127.0.0.1','2026-08-27 03:40:07','2026-08-27 03:40:07'),(35,2,'resep.disetujui','Pesanan',7,NULL,'127.0.0.1','2026-08-27 03:40:10','2026-08-27 03:40:10'),(36,2,'keluar','User',2,NULL,'127.0.0.1','2026-08-27 03:40:16','2026-08-27 03:40:16'),(37,4,'masuk','User',4,NULL,'127.0.0.1','2026-08-27 03:40:23','2026-08-27 03:40:23'),(38,4,'pesanan.buat','Pesanan',8,'PSN-20260827-0008','127.0.0.1','2026-08-27 03:41:07','2026-08-27 03:41:07'),(39,4,'pembayaran.dispatch','Pesanan',8,NULL,'127.0.0.1','2026-08-27 03:41:10','2026-08-27 03:41:10'),(40,4,'pembayaran.dispatch','Pesanan',8,NULL,'127.0.0.1','2026-08-27 03:41:17','2026-08-27 03:41:17'),(41,4,'pembayaran.dispatch','Pesanan',8,NULL,'127.0.0.1','2026-08-27 03:41:22','2026-08-27 03:41:22'),(42,NULL,'pembayaran.sukses','Pesanan',8,'PSN-20260827-0008','127.0.0.1','2026-08-27 03:41:33','2026-08-27 03:41:33'),(43,NULL,'stok.fifo','Pesanan',7,'Stok dipotong FIFO untuk PSN-20260827-0007','127.0.0.1','2026-08-27 03:41:33','2026-08-27 03:41:33'),(44,4,'resep.unggah','Pesanan',8,NULL,'127.0.0.1','2026-08-27 03:42:18','2026-08-27 03:42:18'),(45,4,'keluar','User',4,NULL,'127.0.0.1','2026-08-27 03:42:22','2026-08-27 03:42:22'),(46,2,'masuk','User',2,NULL,'127.0.0.1','2026-08-27 03:42:45','2026-08-27 03:42:45'),(47,2,'resep.disetujui','Pesanan',8,NULL,'127.0.0.1','2026-08-27 03:42:49','2026-08-27 03:42:49'),(48,NULL,'stok.fifo','Pesanan',8,'Stok dipotong FIFO untuk PSN-20260827-0008','127.0.0.1','2026-08-27 03:42:52','2026-08-27 03:42:52'),(49,2,'keluar','User',2,NULL,'127.0.0.1','2026-08-27 03:43:05','2026-08-27 03:43:05'),(50,1,'masuk','User',1,NULL,'127.0.0.1','2026-08-27 03:43:25','2026-08-27 03:43:25'),(51,1,'keluar','User',1,NULL,'127.0.0.1','2026-08-27 03:50:01','2026-08-27 03:50:01'),(52,4,'masuk','User',4,NULL,'127.0.0.1','2026-08-27 03:50:25','2026-08-27 03:50:25'),(53,4,'pesanan.buat','Pesanan',9,'PSN-20260827-0009','127.0.0.1','2026-08-27 03:50:41','2026-08-27 03:50:41'),(54,4,'pembayaran.dispatch','Pesanan',9,NULL,'127.0.0.1','2026-08-27 03:50:43','2026-08-27 03:50:43'),(55,NULL,'pembayaran.sukses','Pesanan',9,'PSN-20260827-0009','127.0.0.1','2026-08-27 03:50:44','2026-08-27 03:50:44');
/*!40000 ALTER TABLE `log_audit` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_kesalahan`
--

DROP TABLE IF EXISTS `log_kesalahan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_kesalahan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tingkat` varchar(20) NOT NULL,
  `pesan` varchar(255) NOT NULL,
  `jejak` text DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_kesalahan_tingkat_index` (`tingkat`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_kesalahan`
--

LOCK TABLES `log_kesalahan` WRITE;
/*!40000 ALTER TABLE `log_kesalahan` DISABLE KEYS */;
INSERT INTO `log_kesalahan` VALUES (1,'kritis','The \"--columns\" option does not exist.','#0 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Input\\ArgvInput.php(153): Symfony\\Component\\Console\\Input\\ArgvInput->addLongOption(\'columns\', \'method,uri,name\')\n#1 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Input\\ArgvInput.php(88): Symfony\\Component\\Console\\Input\\ArgvInput->parseLongOption(\'--columns=metho...\')\n#2 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Input\\ArgvInput.php(77): Symfony\\Component\\Console\\Input\\ArgvInput->parseToken(\'--columns=metho...\', true)\n#3 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Input\\Input.php(53): Symfony\\Component\\Console\\Input\\ArgvInput->parse()\n#4 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Command\\Command.php(295): Symfony\\Component\\Console\\Input\\Input->bind(Object(Symfony\\Component\\Console\\Input\\InputDefinition))\n#5 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#6 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#7 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Foundation\\Console\\RouteListCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#8 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#9 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#10 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#11 D:\\Documents\\superproof\\azmi\\assesmen\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#12 {main}','http://localhost:8000','2026-08-27 02:52:40','2026-08-27 02:52:40'),(2,'kritis','PHP Parse error: Syntax error, unexpected \'=\' on line 1','#0 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\psy\\psysh\\src\\CodeCleaner.php(657): Psy\\Exception\\ParseErrorException::fromParseError(Object(PhpParser\\Error))\n#1 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\psy\\psysh\\src\\CodeCleaner.php(273): Psy\\CodeCleaner->parse(\'<?php =App\\\\Mode...\', false)\n#2 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\psy\\psysh\\src\\Shell.php(1399): Psy\\CodeCleaner->clean(Array, false)\n#3 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\psy\\psysh\\src\\Shell.php(1506): Psy\\Shell->appendCode(\'=App\\\\Models\\\\Pes...\', true, false)\n#4 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\psy\\psysh\\src\\Shell.php(2215): Psy\\Shell->setCode(\'=App\\\\Models\\\\Pes...\', true)\n#5 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\tinker\\src\\Console\\TinkerCommand.php(77): Psy\\Shell->execute(\'=App\\\\Models\\\\Pes...\')\n#6 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(36): Laravel\\Tinker\\Console\\TinkerCommand->handle()\n#7 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Util.php(43): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#8 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(96): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#9 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\BoundMethod.php(35): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#10 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Container\\Container.php(799): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#11 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(211): Illuminate\\Container\\Container->call(Array)\n#12 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Command\\Command.php(341): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#13 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Console\\Command.php(180): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#14 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Application.php(1117): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#15 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Application.php(356): Symfony\\Component\\Console\\Application->doRunCommand(Object(Laravel\\Tinker\\Console\\TinkerCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#16 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\symfony\\console\\Application.php(195): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#17 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Console\\Kernel.php(198): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#18 D:\\Documents\\superproof\\azmi\\assesmen\\vendor\\laravel\\framework\\src\\Illuminate\\Foundation\\Application.php(1235): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#19 D:\\Documents\\superproof\\azmi\\assesmen\\artisan(16): Illuminate\\Foundation\\Application->handleCommand(Object(Symfony\\Component\\Console\\Input\\ArgvInput))\n#20 {main}','http://localhost:8000','2026-08-27 03:29:22','2026-08-27 03:29:22');
/*!40000 ALTER TABLE `log_kesalahan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_migrasi`
--

DROP TABLE IF EXISTS `log_migrasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_migrasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `batch_migrasi_id` varchar(40) NOT NULL,
  `baris_ke` int(10) unsigned NOT NULL,
  `kode_obat` varchar(255) DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `pesan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_migrasi_batch_migrasi_id_index` (`batch_migrasi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_migrasi`
--

LOCK TABLES `log_migrasi` WRITE;
/*!40000 ALTER TABLE `log_migrasi` DISABLE KEYS */;
INSERT INTO `log_migrasi` VALUES (1,'MIG-20260827100314-vjmo',2,'XL-001','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(2,'MIG-20260827100314-vjmo',3,'XL-002','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(3,'MIG-20260827100314-vjmo',4,'XL-003','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(4,'MIG-20260827100314-vjmo',5,'XL-004','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(5,'MIG-20260827100314-vjmo',6,'XL-005','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(6,'MIG-20260827100314-vjmo',7,'XL-006','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(7,'MIG-20260827100314-vjmo',8,'XL-007','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(8,'MIG-20260827100314-vjmo',9,'XL-008','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(9,'MIG-20260827100314-vjmo',10,'XL-009','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(10,'MIG-20260827100314-vjmo',11,'XL-010','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(11,'MIG-20260827100314-vjmo',12,'XL-011','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(12,'MIG-20260827100314-vjmo',13,'XL-012','sukses','Impor berhasil','2026-08-27 03:04:10','2026-08-27 03:04:10'),(13,'MIG-20260827100314-vjmo',14,'XL-013','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(14,'MIG-20260827100314-vjmo',15,'XL-014','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(15,'MIG-20260827100314-vjmo',16,'XL-015','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(16,'MIG-20260827100314-vjmo',17,'XL-016','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(17,'MIG-20260827100314-vjmo',18,'XL-017','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(18,'MIG-20260827100314-vjmo',19,'XL-018','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(19,'MIG-20260827100314-vjmo',20,'XL-019','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(20,'MIG-20260827100314-vjmo',21,'XL-020','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(21,'MIG-20260827100314-vjmo',22,'XL-021','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(22,'MIG-20260827100314-vjmo',23,'XL-022','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(23,'MIG-20260827100314-vjmo',24,'XL-023','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(24,'MIG-20260827100314-vjmo',25,'XL-024','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(25,'MIG-20260827100314-vjmo',26,'XL-025','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(26,'MIG-20260827100314-vjmo',27,'XL-026','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(27,'MIG-20260827100314-vjmo',28,'XL-027','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(28,'MIG-20260827100314-vjmo',29,'XL-028','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(29,'MIG-20260827100314-vjmo',30,'XL-029','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(30,'MIG-20260827100314-vjmo',31,'XL-030','sukses','Impor berhasil','2026-08-27 03:04:11','2026-08-27 03:04:11'),(31,'MIG-20260827100502-kupj',2,'XL-001','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(32,'MIG-20260827100502-kupj',3,'XL-002','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(33,'MIG-20260827100502-kupj',4,'XL-003','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(34,'MIG-20260827100502-kupj',5,'XL-004','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(35,'MIG-20260827100502-kupj',6,'XL-005','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(36,'MIG-20260827100502-kupj',7,'XL-006','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(37,'MIG-20260827100502-kupj',8,'XL-007','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(38,'MIG-20260827100502-kupj',9,'XL-008','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(39,'MIG-20260827100502-kupj',10,'XL-009','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(40,'MIG-20260827100502-kupj',11,'XL-010','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(41,'MIG-20260827100502-kupj',12,'XL-011','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(42,'MIG-20260827100502-kupj',13,'XL-012','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(43,'MIG-20260827100502-kupj',14,'XL-013','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(44,'MIG-20260827100502-kupj',15,'XL-014','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(45,'MIG-20260827100502-kupj',16,'XL-015','sukses','Impor berhasil','2026-08-27 03:05:03','2026-08-27 03:05:03'),(46,'MIG-20260827100502-kupj',17,'XL-016','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(47,'MIG-20260827100502-kupj',18,'XL-017','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(48,'MIG-20260827100502-kupj',19,'XL-018','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(49,'MIG-20260827100502-kupj',20,'XL-019','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(50,'MIG-20260827100502-kupj',21,'XL-020','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(51,'MIG-20260827100502-kupj',22,'XL-021','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(52,'MIG-20260827100502-kupj',23,'XL-022','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(53,'MIG-20260827100502-kupj',24,'XL-023','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(54,'MIG-20260827100502-kupj',25,'XL-024','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(55,'MIG-20260827100502-kupj',26,'XL-025','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(56,'MIG-20260827100502-kupj',27,'XL-026','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(57,'MIG-20260827100502-kupj',28,'XL-027','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(58,'MIG-20260827100502-kupj',29,'XL-028','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(59,'MIG-20260827100502-kupj',30,'XL-029','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(60,'MIG-20260827100502-kupj',31,'XL-030','sukses','Impor berhasil','2026-08-27 03:05:04','2026-08-27 03:05:04'),(61,'MIG-20260827101345-xqiv',2,'KD-101','sukses','Impor berhasil','2026-08-27 03:13:46','2026-08-27 03:13:46'),(62,'MIG-20260827101345-xqiv',4,'KD-103','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(63,'MIG-20260827101345-xqiv',3,'KD-102','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(64,'MIG-20260827101345-xqiv',5,'KD-104','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(65,'MIG-20260827101345-xqiv',6,'KD-105','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(66,'MIG-20260827101345-xqiv',7,'KD-201','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(67,'MIG-20260827101345-xqiv',8,'KD-202','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(68,'MIG-20260827101345-xqiv',9,'KD-203','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(69,'MIG-20260827101345-xqiv',10,'KD-204','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(70,'MIG-20260827101345-xqiv',12,'KD-301','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(71,'MIG-20260827101345-xqiv',11,'KD-205','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(72,'MIG-20260827101345-xqiv',13,'KD-302','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(73,'MIG-20260827101345-xqiv',14,'KD-303','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(74,'MIG-20260827101345-xqiv',15,'KD-304','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(75,'MIG-20260827101345-xqiv',16,'KD-305','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(76,'MIG-20260827101345-xqiv',17,'KD-401','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(77,'MIG-20260827101345-xqiv',18,'KD-402','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(78,'MIG-20260827101345-xqiv',19,'KD-403','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(79,'MIG-20260827101345-xqiv',20,'KD-404','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47'),(80,'MIG-20260827101345-xqiv',21,'KD-405','sukses','Impor berhasil','2026-08-27 03:13:47','2026-08-27 03:13:47');
/*!40000 ALTER TABLE `log_migrasi` ENABLE KEYS */;
UNLOCK TABLES;

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_27_100000_create_master_obat_tables',1),(5,'2026_08_27_100100_create_pesanan_tables',1),(6,'2026_08_27_100200_create_pengawasan_tables',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mutasi_stok`
--

DROP TABLE IF EXISTS `mutasi_stok`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mutasi_stok` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `obat_id` bigint(20) unsigned NOT NULL,
  `batch_obat_id` bigint(20) unsigned NOT NULL,
  `jenis` varchar(10) NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  `sumber` varchar(40) NOT NULL,
  `pesanan_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_stok_obat_id_foreign` (`obat_id`),
  KEY `mutasi_stok_batch_obat_id_foreign` (`batch_obat_id`),
  KEY `mutasi_stok_pesanan_id_index` (`pesanan_id`),
  CONSTRAINT `mutasi_stok_batch_obat_id_foreign` FOREIGN KEY (`batch_obat_id`) REFERENCES `batch_obat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mutasi_stok_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mutasi_stok_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=104 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mutasi_stok`
--

LOCK TABLES `mutasi_stok` WRITE;
/*!40000 ALTER TABLE `mutasi_stok` DISABLE KEYS */;
INSERT INTO `mutasi_stok` VALUES (1,1,1,'masuk',80,'seeder',NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(2,2,2,'masuk',40,'seeder',NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(3,3,3,'masuk',35,'seeder',NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(4,4,4,'masuk',8,'seeder',NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(5,5,5,'masuk',50,'seeder',NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(6,6,6,'masuk',22,'seeder',NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(7,1,7,'masuk',30,'seeder',NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(8,7,8,'masuk',40,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(9,8,9,'masuk',35,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(10,9,10,'masuk',28,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(11,10,11,'masuk',12,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(12,11,12,'masuk',60,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(13,12,13,'masuk',45,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(14,13,14,'masuk',80,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(15,14,15,'masuk',100,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(16,15,16,'masuk',50,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(17,16,17,'masuk',18,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(18,17,18,'masuk',30,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(19,18,19,'masuk',55,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(20,19,20,'masuk',42,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(21,20,21,'masuk',20,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(22,21,22,'masuk',25,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(23,22,23,'masuk',120,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(24,23,24,'masuk',70,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(25,24,25,'masuk',90,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(26,25,26,'masuk',65,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(27,26,27,'masuk',48,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(28,27,28,'masuk',55,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(29,28,29,'masuk',33,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(30,29,30,'masuk',40,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(31,30,31,'masuk',22,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(32,31,32,'masuk',200,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(33,32,33,'masuk',75,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(34,33,34,'masuk',90,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(35,34,35,'masuk',35,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(36,35,36,'masuk',25,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(37,36,37,'masuk',60,'migrasi:MIG-20260827100314-vjmo',NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(38,7,38,'masuk',40,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(39,8,39,'masuk',35,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(40,9,40,'masuk',28,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(41,10,41,'masuk',12,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(42,11,42,'masuk',60,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(43,12,43,'masuk',45,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(44,13,44,'masuk',80,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(45,14,45,'masuk',100,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(46,15,46,'masuk',50,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(47,16,47,'masuk',18,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(48,17,48,'masuk',30,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(49,18,49,'masuk',55,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(50,19,50,'masuk',42,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(51,20,51,'masuk',20,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(52,21,52,'masuk',25,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:03','2026-08-27 03:05:03'),(53,22,53,'masuk',120,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(54,23,54,'masuk',70,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(55,24,55,'masuk',90,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(56,25,56,'masuk',65,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(57,26,57,'masuk',48,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(58,27,58,'masuk',55,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(59,28,59,'masuk',33,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(60,29,60,'masuk',40,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(61,30,61,'masuk',22,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(62,31,62,'masuk',200,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(63,32,63,'masuk',75,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(64,33,64,'masuk',90,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(65,34,65,'masuk',35,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(66,35,66,'masuk',25,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(67,36,67,'masuk',60,'migrasi:MIG-20260827100502-kupj',NULL,'2026-08-27 03:05:04','2026-08-27 03:05:04'),(68,37,68,'masuk',18,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:46','2026-08-27 03:13:46'),(69,38,69,'masuk',9,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(70,39,70,'masuk',12,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(71,40,71,'masuk',25,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(72,41,72,'masuk',7,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(73,42,73,'masuk',30,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(74,43,74,'masuk',22,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(75,44,75,'masuk',40,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(76,45,76,'masuk',14,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(77,46,77,'masuk',28,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(78,47,78,'masuk',16,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(79,48,79,'masuk',35,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(80,49,80,'masuk',20,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(81,50,81,'masuk',45,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(82,51,82,'masuk',33,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(83,52,83,'masuk',38,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(84,53,84,'masuk',50,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(85,54,85,'masuk',18,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(86,55,86,'masuk',27,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(87,56,87,'masuk',24,'migrasi:MIG-20260827101345-xqiv',NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(88,1,1,'keluar',1,'kasir',1,'2026-08-27 03:24:17','2026-08-27 03:24:17'),(89,39,70,'keluar',2,'kasir',2,'2026-08-27 03:24:40','2026-08-27 03:24:40'),(90,25,26,'keluar',1,'kasir',2,'2026-08-27 03:24:40','2026-08-27 03:24:40'),(91,32,33,'keluar',1,'kasir',3,'2026-08-27 03:26:20','2026-08-27 03:26:20'),(92,39,70,'keluar',1,'kasir',4,'2026-08-27 03:26:52','2026-08-27 03:26:52'),(93,1,1,'keluar',1,'pesanan-online',5,'2026-08-27 03:32:49','2026-08-27 03:32:49'),(94,16,17,'keluar',1,'pesanan-online',5,'2026-08-27 03:32:49','2026-08-27 03:32:49'),(95,32,33,'keluar',1,'pesanan-online',5,'2026-08-27 03:32:49','2026-08-27 03:32:49'),(96,39,70,'keluar',1,'kasir',6,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(97,51,82,'keluar',1,'kasir',6,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(98,9,10,'keluar',1,'kasir',6,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(99,53,84,'keluar',1,'kasir',6,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(100,1,1,'keluar',1,'pesanan-online',7,'2026-08-27 03:41:33','2026-08-27 03:41:33'),(101,3,3,'keluar',1,'pesanan-online',7,'2026-08-27 03:41:33','2026-08-27 03:41:33'),(102,37,68,'keluar',1,'pesanan-online',8,'2026-08-27 03:42:52','2026-08-27 03:42:52'),(103,16,17,'keluar',1,'pesanan-online',8,'2026-08-27 03:42:52','2026-08-27 03:42:52');
/*!40000 ALTER TABLE `mutasi_stok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `obat`
--

DROP TABLE IF EXISTS `obat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `obat` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(40) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kategori_obat_id` bigint(20) unsigned NOT NULL,
  `pemasok_id` bigint(20) unsigned DEFAULT NULL,
  `harga` int(10) unsigned NOT NULL,
  `stok_minimum` int(10) unsigned NOT NULL DEFAULT 10,
  `butuh_resep` tinyint(1) NOT NULL DEFAULT 0,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `obat_kode_unique` (`kode`),
  KEY `obat_pemasok_id_foreign` (`pemasok_id`),
  KEY `obat_kategori_obat_id_nama_index` (`kategori_obat_id`,`nama`),
  CONSTRAINT `obat_kategori_obat_id_foreign` FOREIGN KEY (`kategori_obat_id`) REFERENCES `kategori_obat` (`id`),
  CONSTRAINT `obat_pemasok_id_foreign` FOREIGN KEY (`pemasok_id`) REFERENCES `pemasok` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obat`
--

LOCK TABLES `obat` WRITE;
/*!40000 ALTER TABLE `obat` DISABLE KEYS */;
INSERT INTO `obat` VALUES (1,'PCT500','Paracetamol 500mg',1,1,3000,20,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-27 02:52:23','2026-08-27 02:52:23'),(2,'IBU400','Ibuprofen 400mg',1,1,4500,15,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-27 02:52:23','2026-08-27 02:52:23'),(3,'AMX500','Amoxicillin 500mg',2,1,8000,10,1,NULL,'Data demo Klinik Makmur Jaya.','2026-08-27 02:52:23','2026-08-27 02:52:23'),(4,'CTM4','CTM 4mg',1,1,2000,25,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-27 02:52:23','2026-08-27 02:52:23'),(5,'VITC','Vitamin C 500mg',3,1,5000,12,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-27 02:52:23','2026-08-27 02:52:23'),(6,'VITB','Vitamin B Complex',3,1,7000,10,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-27 02:52:23','2026-08-27 02:52:23'),(7,'XL-001','OBH Mix Anak 60ml',4,1,12500,10,0,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(8,'XL-002','OBH Combi Batuk Berdahak',4,1,15000,10,0,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(9,'XL-003','Salbutamol 2mg Tablet',5,2,6500,8,1,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(10,'XL-004','Salbutamol Inhaler 100mcg',5,2,85000,5,1,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(11,'XL-005','Zinc Sulfat 20mg',3,3,4500,12,0,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(12,'XL-006','Vitamin C 1000mg Effervescent',3,4,18000,15,0,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(13,'XL-007','Asam Mefenamat 500mg',1,5,3500,20,0,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(14,'XL-008','Antasida DOEN Tablet',6,6,2500,25,0,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(15,'XL-009','Omeprazole 20mg Kapsul',6,6,7000,15,1,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(16,'XL-010','Amoxicillin Sirup 125mg/5ml',2,7,22000,8,1,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(17,'XL-011','Cefadroxil 500mg Kapsul',2,7,9500,10,1,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(18,'XL-012','Cetirizine 10mg',7,8,4000,15,0,NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(19,'XL-013','Loratadine 10mg',7,8,5500,12,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(20,'XL-014','Betadine Gargle 100ml',8,9,28000,8,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(21,'XL-015','Povidone Iodine 10% 30ml',8,9,16000,10,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(22,'XL-016','Oralit 200ml Sachet',9,2,3000,30,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(23,'XL-017','Loperamide 2mg',9,5,2500,20,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(24,'XL-018','Metformin 500mg',10,4,3500,25,1,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(25,'XL-019','Amlodipine 5mg',11,5,4000,20,1,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(26,'XL-020','Simvastatin 20mg',11,4,6000,15,1,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(27,'XL-021','Paracetamol Sirup Anak 120mg/5ml',1,1,11000,15,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(28,'XL-022','Ibuprofen Sirup Anak 100mg/5ml',1,1,14000,10,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(29,'XL-023','Minyak Kayu Putih 60ml',12,10,18000,10,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(30,'XL-024','Hot In Cream 60g',12,10,22000,8,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(31,'XL-025','Kassa Steril 16x16',13,11,8000,50,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(32,'XL-026','Alkohol 70% 100ml',13,11,9000,20,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(33,'XL-027','Masker Medis 3 Ply (isi 50)',13,11,35000,20,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(34,'XL-028','Hand Sanitizer 500ml',8,3,27000,10,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(35,'XL-029','Multivitamin Anak Sirup',3,4,32000,8,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(36,'XL-030','Calcium Carbonat 500mg',3,6,5000,15,0,NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(37,'KD-101','Paracetamol Forte 650mg Batch Lama',1,12,4500,10,0,NULL,NULL,'2026-08-27 03:13:46','2026-08-27 03:13:46'),(38,'KD-103','Antasida Cair 100ml Expired',6,13,7500,5,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(39,'KD-102','Ambroxol Sirup 15mg/5ml Expired',4,12,9800,8,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(40,'KD-104','CTM 4mg Strip Expired',7,13,2200,10,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(41,'KD-105','Povidone Iodine 60ml Expired',8,14,14500,5,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(42,'KD-201','Vitamin B1 100mg Near Exp 30',3,15,5500,12,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(43,'KD-202','Ibuprofen 200mg Near Exp 30',1,16,3800,10,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(44,'KD-203','Oralit Rasa Jeruk Near Exp 30',9,17,3200,15,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(45,'KD-204','Salbutamol 4mg Near Exp 30',5,17,7000,8,1,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(46,'KD-301','Cetirizine Sirup Near Exp 60',7,18,12000,10,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(47,'KD-205','Omeprazole 40mg Near Exp 30',6,19,8500,8,1,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(48,'KD-302','Zinc Anak 10mg Near Exp 60',3,18,6000,12,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(49,'KD-303','Cefixime 100mg Near Exp 60',2,20,15000,8,1,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(50,'KD-304','Metformin 850mg Near Exp 60',10,15,4200,15,1,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(51,'KD-305','Amlodipine 10mg Near Exp 60',11,16,5500,12,1,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(52,'KD-401','Simvastatin 10mg Near Exp 90',11,15,4800,12,1,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(53,'KD-402','Vitamin C Kunyah 100mg Near Exp 90',3,12,9000,15,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(54,'KD-403','Betadine Ointment 15g Near Exp 90',8,21,24000,8,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(55,'KD-404','Loperamide Anak Near Exp 90',9,16,3500,10,0,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(56,'KD-405','Dexamethasone 0.75mg Near Exp 90',14,17,3100,10,1,NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47');
/*!40000 ALTER TABLE `obat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pemasok`
--

DROP TABLE IF EXISTS `pemasok`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pemasok` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `telepon` varchar(30) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemasok`
--

LOCK TABLES `pemasok` WRITE;
/*!40000 ALTER TABLE `pemasok` DISABLE KEYS */;
INSERT INTO `pemasok` VALUES (1,'PT Sinar Farma','021555000','Jakarta','2026-08-27 02:52:22','2026-08-27 02:52:22'),(2,'PT Kimia Farma',NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(3,'PT Nusantara Medika',NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(4,'PT Kalbe Farma',NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(5,'PT Dexa Medica',NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(6,'PT Phapros',NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(7,'PT Sanbe Farma',NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(8,'PT Tempo Scan',NULL,NULL,'2026-08-27 03:04:10','2026-08-27 03:04:10'),(9,'Mundipharma',NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(10,'PT Cap Lang',NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(11,'PT OneMed',NULL,NULL,'2026-08-27 03:04:11','2026-08-27 03:04:11'),(12,'PT Farma Nusantara',NULL,NULL,'2026-08-27 03:13:46','2026-08-27 03:13:46'),(13,'PT Medika Sejahtera',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(14,'PT OneMed Jaya',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(15,'PT Kalbe Sehat',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(16,'PT Dexa Sehat',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(17,'PT Kimia Sehat',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(18,'PT Tempo Sehat',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(19,'PT Phapros Sehat',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(20,'PT Sanbe Sehat',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47'),(21,'PT Mundi Sehat',NULL,NULL,'2026-08-27 03:13:47','2026-08-27 03:13:47');
/*!40000 ALTER TABLE `pemasok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `peringatan`
--

DROP TABLE IF EXISTS `peringatan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `peringatan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jenis` varchar(30) NOT NULL,
  `tingkat` varchar(20) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `obat_id` bigint(20) unsigned DEFAULT NULL,
  `pesanan_id` bigint(20) unsigned DEFAULT NULL,
  `dibaca_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peringatan_obat_id_foreign` (`obat_id`),
  KEY `peringatan_pesanan_id_foreign` (`pesanan_id`),
  KEY `peringatan_jenis_index` (`jenis`),
  KEY `peringatan_tingkat_index` (`tingkat`),
  CONSTRAINT `peringatan_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE SET NULL,
  CONSTRAINT `peringatan_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peringatan`
--

LOCK TABLES `peringatan` WRITE;
/*!40000 ALTER TABLE `peringatan` DISABLE KEYS */;
INSERT INTO `peringatan` VALUES (1,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Vitamin C 500mg','batch #5 kedaluwarsa 2026-09-21 (25 hari).',5,NULL,NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(2,'stok_kritis','peringatan','Stok kritis: CTM 4mg','Sisa 8 unit, minimum 25.',4,NULL,NULL,'2026-08-27 02:52:23','2026-08-27 02:52:23'),(3,'kesalahan','kritis','Kesalahan sistem','The \"--columns\" option does not exist.',NULL,NULL,NULL,'2026-08-27 02:52:40','2026-08-27 02:52:40'),(4,'kedaluwarsa','peringatan','Kedaluwarsa ≤90 hari: Omeprazole 20mg Kapsul','batch #16 kedaluwarsa 2026-11-10 (75 hari).',15,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(5,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Amoxicillin Sirup 125mg/5ml','batch #17 kedaluwarsa 2026-10-05 (39 hari).',16,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(6,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Metformin 500mg','batch #25 kedaluwarsa 2026-09-30 (34 hari).',24,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(7,'kedaluwarsa','peringatan','Kedaluwarsa ≤90 hari: Omeprazole 20mg Kapsul','batch #46 kedaluwarsa 2026-11-10 (75 hari).',15,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(8,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Amoxicillin Sirup 125mg/5ml','batch #47 kedaluwarsa 2026-10-05 (39 hari).',16,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(9,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Metformin 500mg','batch #55 kedaluwarsa 2026-09-30 (34 hari).',24,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(10,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Paracetamol Forte 650mg Batch Lama','batch #68 kedaluwarsa 2026-07-15 (-43 hari).',37,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(11,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Antasida Cair 100ml Expired','batch #69 kedaluwarsa 2026-06-20 (-68 hari).',38,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(12,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Ambroxol Sirup 15mg/5ml Expired','batch #70 kedaluwarsa 2026-08-01 (-26 hari).',39,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(13,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: CTM 4mg Strip Expired','batch #71 kedaluwarsa 2025-12-31 (-239 hari).',40,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(14,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Povidone Iodine 60ml Expired','batch #72 kedaluwarsa 2026-05-10 (-109 hari).',41,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(15,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Vitamin B1 100mg Near Exp 30','batch #73 kedaluwarsa 2026-09-10 (14 hari).',42,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(16,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Ibuprofen 200mg Near Exp 30','batch #74 kedaluwarsa 2026-09-15 (19 hari).',43,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(17,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Oralit Rasa Jeruk Near Exp 30','batch #75 kedaluwarsa 2026-09-20 (24 hari).',44,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(18,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Salbutamol 4mg Near Exp 30','batch #76 kedaluwarsa 2026-09-05 (9 hari).',45,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(19,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Cetirizine Sirup Near Exp 60','batch #77 kedaluwarsa 2026-10-12 (46 hari).',46,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(20,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Omeprazole 40mg Near Exp 30','batch #78 kedaluwarsa 2026-09-25 (29 hari).',47,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(21,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Zinc Anak 10mg Near Exp 60','batch #79 kedaluwarsa 2026-10-20 (54 hari).',48,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(22,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Cefixime 100mg Near Exp 60','batch #80 kedaluwarsa 2026-10-08 (42 hari).',49,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(23,'kedaluwarsa','peringatan','Kedaluwarsa ≤90 hari: Metformin 850mg Near Exp 60','batch #81 kedaluwarsa 2026-10-28 (62 hari).',50,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(24,'kedaluwarsa','peringatan','Kedaluwarsa ≤60 hari: Amlodipine 10mg Near Exp 60','batch #82 kedaluwarsa 2026-10-15 (49 hari).',51,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(25,'kedaluwarsa','peringatan','Kedaluwarsa ≤90 hari: Simvastatin 10mg Near Exp 90','batch #83 kedaluwarsa 2026-11-10 (75 hari).',52,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(26,'kedaluwarsa','peringatan','Kedaluwarsa ≤90 hari: Vitamin C Kunyah 100mg Near Exp 90','batch #84 kedaluwarsa 2026-11-18 (83 hari).',53,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(27,'kedaluwarsa','peringatan','Kedaluwarsa ≤90 hari: Betadine Ointment 15g Near Exp 90','batch #85 kedaluwarsa 2026-11-05 (70 hari).',54,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(28,'kedaluwarsa','peringatan','Kedaluwarsa ≤90 hari: Loperamide Anak Near Exp 90','batch #86 kedaluwarsa 2026-11-22 (87 hari).',55,NULL,NULL,'2026-08-27 03:17:30','2026-08-27 03:17:30'),(29,'kesalahan','kritis','Kesalahan sistem','PHP Parse error: Syntax error, unexpected \'=\' on line 1',NULL,NULL,NULL,'2026-08-27 03:29:22','2026-08-27 03:29:22'),(30,'pesanan_baru','info','Pesanan baru PSN-20260827-0005','Total Rp 34.000',NULL,5,NULL,'2026-08-27 03:31:24','2026-08-27 03:31:24'),(31,'stok_kritis','peringatan','Stok kritis: Ambroxol Sirup 15mg/5ml Expired','Sisa 8 unit, minimum 8.',39,NULL,NULL,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(32,'pesanan_baru','info','Pesanan baru PSN-20260827-0007','Total Rp 11.000',NULL,7,NULL,'2026-08-27 03:37:26','2026-08-27 03:37:26'),(33,'pesanan_baru','info','Pesanan baru PSN-20260827-0008','Total Rp 26.500',NULL,8,NULL,'2026-08-27 03:41:33','2026-08-27 03:41:33'),(34,'pesanan_baru','info','Pesanan baru PSN-20260827-0009','Total Rp 8.000',NULL,9,NULL,'2026-08-27 03:50:44','2026-08-27 03:50:44');
/*!40000 ALTER TABLE `peringatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pesanan`
--

DROP TABLE IF EXISTS `pesanan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pesanan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomor` varchar(30) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` varchar(30) NOT NULL,
  `sumber` varchar(20) NOT NULL DEFAULT 'online',
  `total` int(10) unsigned NOT NULL,
  `dibayar_pada` timestamp NULL DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pesanan_nomor_unique` (`nomor`),
  KEY `pesanan_user_id_foreign` (`user_id`),
  KEY `pesanan_status_index` (`status`),
  CONSTRAINT `pesanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pesanan`
--

LOCK TABLES `pesanan` WRITE;
/*!40000 ALTER TABLE `pesanan` DISABLE KEYS */;
INSERT INTO `pesanan` VALUES (1,'PSN-20260827-0001',3,'selesai','kasir',3000,'2026-08-27 03:24:17',NULL,'2026-08-27 03:24:17','2026-08-27 03:24:17'),(2,'PSN-20260827-0002',3,'selesai','kasir',23600,'2026-08-27 03:24:40',NULL,'2026-08-27 03:24:40','2026-08-27 03:24:40'),(3,'PSN-20260827-0003',3,'selesai','kasir',9000,'2026-08-27 03:26:20',NULL,'2026-08-27 03:26:20','2026-08-27 03:26:20'),(4,'PSN-20260827-0004',3,'selesai','kasir',9800,'2026-08-27 03:26:52',NULL,'2026-08-27 03:26:52','2026-08-27 03:26:52'),(5,'PSN-20260827-0005',3,'selesai','online',34000,'2026-08-27 03:31:24',NULL,'2026-08-27 03:27:28','2026-08-27 03:32:49'),(6,'PSN-20260827-0006',3,'selesai','kasir',30800,'2026-08-27 03:34:11',NULL,'2026-08-27 03:34:11','2026-08-27 03:34:11'),(7,'PSN-20260827-0007',4,'selesai','online',11000,'2026-08-27 03:37:26',NULL,'2026-08-27 03:37:21','2026-08-27 03:41:33'),(8,'PSN-20260827-0008',4,'selesai','online',26500,'2026-08-27 03:41:33',NULL,'2026-08-27 03:41:07','2026-08-27 03:42:52'),(9,'PSN-20260827-0009',4,'menunggu_resep','online',8000,'2026-08-27 03:50:44',NULL,'2026-08-27 03:50:41','2026-08-27 03:50:44');
/*!40000 ALTER TABLE `pesanan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resep`
--

DROP TABLE IF EXISTS `resep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resep` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_id` bigint(20) unsigned NOT NULL,
  `berkas_gambar` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'menunggu',
  `diverifikasi_oleh` bigint(20) unsigned DEFAULT NULL,
  `catatan_apoteker` text DEFAULT NULL,
  `diverifikasi_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resep_pesanan_id_foreign` (`pesanan_id`),
  KEY `resep_diverifikasi_oleh_foreign` (`diverifikasi_oleh`),
  KEY `resep_status_index` (`status`),
  CONSTRAINT `resep_diverifikasi_oleh_foreign` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `resep_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resep`
--

LOCK TABLES `resep` WRITE;
/*!40000 ALTER TABLE `resep` DISABLE KEYS */;
INSERT INTO `resep` VALUES (1,5,'resep/mCTyGkR9K5CE0QE9LFpoF4nHHDUvQCzMA6sCgl7G.jpg','disetujui',2,NULL,'2026-08-27 03:32:47','2026-08-27 03:32:01','2026-08-27 03:32:47'),(2,7,'resep/RCfXKlUuQiMF9YgD2qn0h6ogpJ86ZrFeQ1YicPQ7.jpg','disetujui',2,NULL,'2026-08-27 03:40:10','2026-08-27 03:39:53','2026-08-27 03:40:10'),(3,8,'resep/wjoYuzEEaaV1Xqud4DHMFjsZBg1yr7hP6N7K6iJo.jpg','disetujui',2,NULL,'2026-08-27 03:42:49','2026-08-27 03:42:18','2026-08-27 03:42:49');
/*!40000 ALTER TABLE `resep` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('IGy6fiqHNfev9CrO3bB2uPK7rtYrZ3P5w0ttdlM5',4,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:154.0) Gecko/20100101 Firefox/154.0','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNGdUd3N3SUczYkZoTTgzMVVVU2FOVWVWT2dyMlFWMGJzd3A4TzltbyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9wZXNhbmFuIjtzOjU6InJvdXRlIjtzOjEzOiJwZXNhbmFuLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9',1787827847);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `peran` varchar(20) NOT NULL DEFAULT 'pasien',
  `telepon` varchar(30) DEFAULT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_peran_index` (`peran`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin Klinik','admin@makmurjaya.test','2026-08-27 02:52:21','$2y$12$hqoS6eOMaD/yT1392zN9cez13xeBv1CZEp/ZZWxFslbO.wQB6Odeu','admin','08123456789','Jl. Makmur No. 12',NULL,'2026-08-27 02:52:21','2026-08-27 02:52:21'),(2,'Apt. Sari','apoteker@makmurjaya.test','2026-08-27 02:52:21','$2y$12$zxyzz7kmM.16Gd8/jjZQ9uhdeBo4fvHSA.V2TFcq8uh3qSnDOSUC.','apoteker','08123456789','Jl. Makmur No. 12',NULL,'2026-08-27 02:52:22','2026-08-27 02:52:22'),(3,'Kasir Budi','kasir@makmurjaya.test','2026-08-27 02:52:22','$2y$12$FwHgVWlZ1NYCrT9SogQfTORjUE8XAqYo/H2f9H.cjOSLdU0/MueKi','kasir','08123456789','Jl. Makmur No. 12',NULL,'2026-08-27 02:52:22','2026-08-27 02:52:22'),(4,'Pasien Rina','pasien@makmurjaya.test','2026-08-27 02:52:22','$2y$12$YX1muUYBPWKtwb2wTtg0F.A0DvRyot8Cn9tlhUPnw0.EAA2ApRHe6','pasien','08123456789','Jl. Makmur No. 12',NULL,'2026-08-27 02:52:22','2026-08-27 02:52:22');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'apotek_makmur_jaya'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-08-27 18:47:35
