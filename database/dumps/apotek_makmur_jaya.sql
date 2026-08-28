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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_obat`
--

LOCK TABLES `batch_obat` WRITE;
/*!40000 ALTER TABLE `batch_obat` DISABLE KEYS */;
INSERT INTO `batch_obat` VALUES (1,1,80,80,'2026-06-28','2027-10-28','2026-08-28 15:21:39','2026-08-28 15:21:39'),(2,2,40,40,'2026-07-28','2027-04-28','2026-08-28 15:21:39','2026-08-28 15:21:39'),(3,3,35,35,'2026-07-19','2027-06-28','2026-08-28 15:21:39','2026-08-28 15:21:39'),(4,4,8,8,'2026-05-28','2027-02-28','2026-08-28 15:21:39','2026-08-28 15:21:39'),(5,5,50,50,'2026-03-28','2026-09-22','2026-08-28 15:21:39','2026-08-28 15:21:39'),(6,6,22,22,'2026-08-18','2028-02-28','2026-08-28 15:21:39','2026-08-28 15:21:39'),(7,1,30,30,'2026-08-28','2027-08-28','2026-08-28 15:21:39','2026-08-28 15:21:39');
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
-- Table structure for table `item_transaksi`
--

DROP TABLE IF EXISTS `item_transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `item_transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint(20) unsigned NOT NULL,
  `obat_id` bigint(20) unsigned NOT NULL,
  `jumlah` int(10) unsigned NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL,
  `subtotal` decimal(14,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_pesanan_pesanan_id_foreign` (`transaksi_id`),
  KEY `item_pesanan_obat_id_foreign` (`obat_id`),
  CONSTRAINT `item_pesanan_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`),
  CONSTRAINT `item_pesanan_pesanan_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `item_transaksi`
--

LOCK TABLES `item_transaksi` WRITE;
/*!40000 ALTER TABLE `item_transaksi` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_transaksi` ENABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  `slot` smallint(5) unsigned NOT NULL DEFAULT 0,
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori_obat_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori_obat`
--

LOCK TABLES `kategori_obat` WRITE;
/*!40000 ALTER TABLE `kategori_obat` DISABLE KEYS */;
INSERT INTO `kategori_obat` VALUES (1,'Analgesik','analgesik',1,'Obat pereda nyeri',1,'analgesik@makmurjaya.test','2026-08-28 15:21:39','2026-08-28 15:21:39'),(2,'Antibiotik','antibiotik',2,'Obat anti infeksi (resep)',1,'antibiotik@makmurjaya.test','2026-08-28 15:21:39','2026-08-28 15:21:39'),(3,'Vitamin','vitamin',3,'Suplemen vitamin',1,'vitamin@makmurjaya.test','2026-08-28 15:21:39','2026-08-28 15:21:39');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_audit`
--

LOCK TABLES `log_audit` WRITE;
/*!40000 ALTER TABLE `log_audit` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_kesalahan`
--

LOCK TABLES `log_kesalahan` WRITE;
/*!40000 ALTER TABLE `log_kesalahan` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_migrasi`
--

LOCK TABLES `log_migrasi` WRITE;
/*!40000 ALTER TABLE `log_migrasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_migrasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `log_tautan_verifikasi`
--

DROP TABLE IF EXISTS `log_tautan_verifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `log_tautan_verifikasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `email` varchar(255) NOT NULL,
  `tautan` text NOT NULL,
  `kadaluarsa_pada` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `dipakai_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `log_tautan_verifikasi_user_id_dipakai_pada_index` (`user_id`,`dipakai_pada`),
  CONSTRAINT `log_tautan_verifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `log_tautan_verifikasi`
--

LOCK TABLES `log_tautan_verifikasi` WRITE;
/*!40000 ALTER TABLE `log_tautan_verifikasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `log_tautan_verifikasi` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_08_27_100000_create_master_obat_tables',1),(5,'2026_08_27_100100_create_pesanan_tables',1),(6,'2026_08_27_100200_create_pengawasan_tables',1),(7,'2026_08_28_000000_create_log_tautan_verifikasi_table',1),(8,'2026_08_29_100000_revisi_domain_transaksi',1);
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
  `transaksi_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `mutasi_stok_obat_id_foreign` (`obat_id`),
  KEY `mutasi_stok_batch_obat_id_foreign` (`batch_obat_id`),
  KEY `mutasi_stok_pesanan_id_index` (`transaksi_id`),
  CONSTRAINT `mutasi_stok_batch_obat_id_foreign` FOREIGN KEY (`batch_obat_id`) REFERENCES `batch_obat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mutasi_stok_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE CASCADE,
  CONSTRAINT `mutasi_stok_pesanan_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mutasi_stok`
--

LOCK TABLES `mutasi_stok` WRITE;
/*!40000 ALTER TABLE `mutasi_stok` DISABLE KEYS */;
INSERT INTO `mutasi_stok` VALUES (1,1,1,'masuk',80,'seeder',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39'),(2,2,2,'masuk',40,'seeder',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39'),(3,3,3,'masuk',35,'seeder',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39'),(4,4,4,'masuk',8,'seeder',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39'),(5,5,5,'masuk',50,'seeder',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39'),(6,6,6,'masuk',22,'seeder',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39'),(7,1,7,'masuk',30,'seeder',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39');
/*!40000 ALTER TABLE `mutasi_stok` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifikasi`
--

DROP TABLE IF EXISTS `notifikasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifikasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `transaksi_id` bigint(20) unsigned DEFAULT NULL,
  `jenis` varchar(40) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pesan` text NOT NULL,
  `dibaca_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifikasi_user_id_foreign` (`user_id`),
  KEY `notifikasi_transaksi_id_foreign` (`transaksi_id`),
  KEY `notifikasi_jenis_index` (`jenis`),
  CONSTRAINT `notifikasi_transaksi_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE SET NULL,
  CONSTRAINT `notifikasi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifikasi`
--

LOCK TABLES `notifikasi` WRITE;
/*!40000 ALTER TABLE `notifikasi` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifikasi` ENABLE KEYS */;
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
  `harga` decimal(12,2) NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `obat`
--

LOCK TABLES `obat` WRITE;
/*!40000 ALTER TABLE `obat` DISABLE KEYS */;
INSERT INTO `obat` VALUES (1,'PCT500','Paracetamol 500mg',1,1,3300.50,20,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-28 15:21:39','2026-08-28 15:21:39'),(2,'IBU400','Ibuprofen 400mg',1,1,4950.75,15,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-28 15:21:39','2026-08-28 15:21:39'),(3,'AMX500','Amoxicillin 500mg',2,1,8800.00,10,1,NULL,'Data demo Klinik Makmur Jaya.','2026-08-28 15:21:39','2026-08-28 15:21:39'),(4,'CTM4','CTM 4mg',1,1,2200.25,25,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-28 15:21:39','2026-08-28 15:21:39'),(5,'VITC','Vitamin C 500mg',3,1,5500.00,12,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-28 15:21:39','2026-08-28 15:21:39'),(6,'VITB','Vitamin B Complex',3,1,7700.99,10,0,NULL,'Data demo Klinik Makmur Jaya.','2026-08-28 15:21:39','2026-08-28 15:21:39');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pemasok`
--

LOCK TABLES `pemasok` WRITE;
/*!40000 ALTER TABLE `pemasok` DISABLE KEYS */;
INSERT INTO `pemasok` VALUES (1,'PT Sinar Farma','021555000','Jakarta','2026-08-28 15:21:39','2026-08-28 15:21:39');
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
  `transaksi_id` bigint(20) unsigned DEFAULT NULL,
  `dibaca_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `peringatan_obat_id_foreign` (`obat_id`),
  KEY `peringatan_pesanan_id_foreign` (`transaksi_id`),
  KEY `peringatan_jenis_index` (`jenis`),
  KEY `peringatan_tingkat_index` (`tingkat`),
  CONSTRAINT `peringatan_obat_id_foreign` FOREIGN KEY (`obat_id`) REFERENCES `obat` (`id`) ON DELETE SET NULL,
  CONSTRAINT `peringatan_pesanan_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `peringatan`
--

LOCK TABLES `peringatan` WRITE;
/*!40000 ALTER TABLE `peringatan` DISABLE KEYS */;
INSERT INTO `peringatan` VALUES (1,'kedaluwarsa','kritis','Kedaluwarsa ≤30 hari: Vitamin C 500mg','batch #5 kedaluwarsa 2026-09-22 (25 hari).',5,NULL,NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39'),(2,'stok_kritis','peringatan','Stok kritis: CTM 4mg','Sisa 8 unit, minimum 25.',4,NULL,NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39');
/*!40000 ALTER TABLE `peringatan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resep`
--

DROP TABLE IF EXISTS `resep`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resep` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `transaksi_id` bigint(20) unsigned NOT NULL,
  `berkas_gambar` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'menunggu',
  `diverifikasi_oleh` bigint(20) unsigned DEFAULT NULL,
  `catatan_verifikasi` text DEFAULT NULL,
  `diverifikasi_pada` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `resep_pesanan_id_foreign` (`transaksi_id`),
  KEY `resep_diverifikasi_oleh_foreign` (`diverifikasi_oleh`),
  KEY `resep_status_index` (`status`),
  CONSTRAINT `resep_diverifikasi_oleh_foreign` FOREIGN KEY (`diverifikasi_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `resep_pesanan_id_foreign` FOREIGN KEY (`transaksi_id`) REFERENCES `transaksi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resep`
--

LOCK TABLES `resep` WRITE;
/*!40000 ALTER TABLE `resep` DISABLE KEYS */;
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
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaksi`
--

DROP TABLE IF EXISTS `transaksi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transaksi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kode_transaksi` varchar(30) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `status` varchar(30) NOT NULL,
  `sumber` varchar(20) NOT NULL DEFAULT 'online',
  `total` decimal(14,2) NOT NULL,
  `metode_pembayaran` varchar(60) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `alamat_pengiriman` text DEFAULT NULL,
  `dibayar_pada` timestamp NULL DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pesanan_nomor_unique` (`kode_transaksi`),
  KEY `pesanan_user_id_foreign` (`user_id`),
  KEY `pesanan_status_index` (`status`),
  CONSTRAINT `pesanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaksi`
--

LOCK TABLES `transaksi` WRITE;
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;
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
INSERT INTO `users` VALUES (1,'Admin Klinik','admin@makmurjaya.test','2026-08-28 15:21:37','$2y$12$s/aVEXPxtPHZTGMcN5UCMO3Ie4MEYJCECE2WZdio0KwvxkUS86E9q','admin','08123456789','Jl. Makmur No. 12',NULL,'2026-08-28 15:21:38','2026-08-28 15:21:38'),(2,'Apt. Sari','apoteker@makmurjaya.test','2026-08-28 15:21:38','$2y$12$elHIJD59tKkbSaRQdWah5e98ruF6u0yVssdm1bDeoHC9JzUE3IYji','apoteker','08123456789','Jl. Makmur No. 12',NULL,'2026-08-28 15:21:38','2026-08-28 15:21:38'),(3,'Kasir Budi','kasir@makmurjaya.test','2026-08-28 15:21:38','$2y$12$8ZwWoVrnVDwsbQ3BOXA2V.iymFucEJxnlR5BUqY614ThL/CXx626C','kasir','08123456789','Jl. Makmur No. 12',NULL,'2026-08-28 15:21:38','2026-08-28 15:21:38'),(4,'Pasien Rina','pasien@makmurjaya.test','2026-08-28 15:21:39','$2y$12$b6tO9.wYITHy2D4TAf9KteJgrU6e1Aa9lJBdjkW2BPepUUq5cBimu','pasien','08123456789','Jl. Makmur No. 12',NULL,'2026-08-28 15:21:39','2026-08-28 15:21:39');
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

-- Dump completed on 2026-08-29  5:21:44
