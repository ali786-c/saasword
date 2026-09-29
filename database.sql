-- MySQL dump 10.13  Distrib 8.4.4, for macos15 (arm64)
--
-- Host: 127.0.0.1    Database: stories
-- ------------------------------------------------------
-- Server version	8.4.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activations_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activations`
--

LOCK TABLES `activations` WRITE;
/*!40000 ALTER TABLE `activations` DISABLE KEYS */;
INSERT INTO `activations` VALUES (1,1,'X7MCY2uFrWhnnagGzg9GwxqSjo9wSEwg',1,'2025-12-21 19:14:31','2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `activations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permission` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` datetime DEFAULT NULL,
  `location` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clicked` bigint NOT NULL DEFAULT '0',
  `order` int DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT '1',
  `tablet_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ads_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_adsense_slot_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ads_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
INSERT INTO `ads` VALUES (1,'Panel Ads','2030-12-22 00:00:00','panel-ads','LLEWUB5BHFEH','banners/1.jpg','https://botble.com',0,1,'published','2025-12-21 19:14:32','2025-12-21 19:14:32',1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads_translations`
--

DROP TABLE IF EXISTS `ads_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ads_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tablet_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ads_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads_translations`
--

LOCK TABLES `ads_translations` WRITE;
/*!40000 ALTER TABLE `ads_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `has_action` tinyint(1) NOT NULL DEFAULT '0',
  `action_label` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_open_new_tab` tinyint(1) NOT NULL DEFAULT '0',
  `dismissible` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
INSERT INTO `announcements` VALUES (1,'Announcement 1','Cyber Monday: Save big on the Creative Cloud All Apps plan for individuals through 2 Dec',0,NULL,NULL,0,1,'2025-12-22 02:14:32',NULL,1,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(2,'Announcement 2','Students and teachers save a massive 71% on Creative Cloud All Apps',0,NULL,NULL,0,1,'2025-12-22 02:14:32',NULL,1,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(3,'Announcement 3','Black Friday and Cyber Monday 2023 Deals for Motion Designers, grab it now!',0,NULL,NULL,0,1,'2025-12-22 02:14:32',NULL,1,'2025-12-21 19:14:32','2025-12-21 19:14:32');
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `announcements_translations`
--

DROP TABLE IF EXISTS `announcements_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `announcements_id` bigint unsigned NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci,
  `action_label` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`announcements_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements_translations`
--

LOCK TABLES `announcements_translations` WRITE;
/*!40000 ALTER TABLE `announcements_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_histories`
--

DROP TABLE IF EXISTS `audit_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'Botble\\ACL\\Models\\User',
  `module` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` longtext COLLATE utf8mb4_unicode_ci,
  `action` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actor_id` bigint unsigned NOT NULL,
  `actor_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'Botble\\ACL\\Models\\User',
  `reference_id` bigint unsigned NOT NULL,
  `reference_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_histories_user_id_index` (`user_id`),
  KEY `audit_histories_module_index` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_histories`
--

LOCK TABLES `audit_histories` WRITE;
/*!40000 ALTER TABLE `audit_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Botble\\ACL\\Models\\User',
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int unsigned NOT NULL DEFAULT '0',
  `is_featured` tinyint NOT NULL DEFAULT '0',
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_index` (`parent_id`),
  KEY `categories_status_index` (`status`),
  KEY `categories_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Uncategorized',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,0,1,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(2,'Travel',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(3,'Guides',2,NULL,'published',1,'Botble\\ACL\\Models\\User',NULL,0,0,0,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(4,'Destination',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(5,'Food',4,NULL,'published',1,'Botble\\ACL\\Models\\User',NULL,0,0,0,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(6,'Hotels',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(7,'Review',6,NULL,'published',1,'Botble\\ACL\\Models\\User',NULL,0,0,0,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(8,'Healthy',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-12-21 19:14:32','2025-12-21 19:14:32'),(9,'Lifestyle',0,'Explore our collection of articles and insights in this category.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-12-21 19:14:32','2025-12-21 19:14:32');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_translations`
--

DROP TABLE IF EXISTS `categories_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`categories_id`),
  KEY `idx_categories_trans_categories_id` (`categories_id`),
  KEY `idx_categories_trans_category_lang` (`categories_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_translations`
--

LOCK TABLES `categories_translations` WRITE;
/*!40000 ALTER TABLE `categories_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_field_options`
--

DROP TABLE IF EXISTS `contact_custom_field_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_field_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `custom_field_id` bigint unsigned NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_field_options`
--

LOCK TABLES `contact_custom_field_options` WRITE;
/*!40000 ALTER TABLE `contact_custom_field_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_field_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_field_options_translations`
--

DROP TABLE IF EXISTS `contact_custom_field_options_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_field_options_translations` (
  `contact_custom_field_options_id` bigint unsigned NOT NULL,
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`contact_custom_field_options_id`),
  KEY `idx_contact_cfo_trans_cfo_id` (`contact_custom_field_options_id`),
  KEY `idx_contact_cfo_trans_cfo_lang` (`contact_custom_field_options_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_field_options_translations`
--

LOCK TABLES `contact_custom_field_options_translations` WRITE;
/*!40000 ALTER TABLE `contact_custom_field_options_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_field_options_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_fields`
--

DROP TABLE IF EXISTS `contact_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placeholder` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '999',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_fields`
--

LOCK TABLES `contact_custom_fields` WRITE;
/*!40000 ALTER TABLE `contact_custom_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_fields_translations`
--

DROP TABLE IF EXISTS `contact_custom_fields_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_fields_translations` (
  `contact_custom_fields_id` bigint unsigned NOT NULL,
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`contact_custom_fields_id`),
  KEY `idx_contact_cf_trans_cf_id` (`contact_custom_fields_id`),
  KEY `idx_contact_cf_trans_cf_lang` (`contact_custom_fields_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_fields_translations`
--

LOCK TABLES `contact_custom_fields_translations` WRITE;
/*!40000 ALTER TABLE `contact_custom_fields_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_fields_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_replies`
--

DROP TABLE IF EXISTS `contact_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_replies`
--

LOCK TABLES `contact_replies` WRITE;
/*!40000 ALTER TABLE `contact_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_fields` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Leora McDermott','madilyn42@example.org','(781) 753-5031','990 Wendy Keys Suite 012\nSouth Amarashire, CO 06503-0677','Modi perspiciatis et enim.','Quas doloribus blanditiis possimus illum. Consectetur distinctio ducimus quia nam. Corrupti exercitationem perspiciatis et sit. Ratione nihil magni eveniet recusandae consequatur nemo suscipit animi. Dignissimos omnis qui quo dolores praesentium similique molestiae. Adipisci officiis qui distinctio quia ut sed enim. Debitis deserunt ut alias accusantium voluptatem. Cum nisi vero veritatis possimus. Ad sunt consectetur qui voluptate labore omnis molestias. Eaque porro et fugiat dolores.',NULL,'unread','2025-12-21 19:14:31','2025-12-21 19:14:31'),(2,'Abigayle Block','jarret45@example.org','828-450-2581','6061 Gleason Isle Apt. 182\nGracielaport, KS 32322','Quas non qui et tempora cupiditate rem quia aut.','Vero fugiat quo et voluptatem totam ut aspernatur. Ducimus magnam magni voluptatem repudiandae quia. Voluptas dignissimos nulla cum possimus. Et unde illum officia. Non et earum enim animi ad alias. Ratione consequuntur repudiandae quae tenetur iste est. Repellat hic voluptatem eius quis nihil molestiae praesentium ducimus. Dolorem eos voluptatibus maxime voluptate velit enim. Vel culpa consectetur dicta eveniet quo autem hic et. Perspiciatis dolor dolores quia ipsam iure.',NULL,'unread','2025-12-21 19:14:31','2025-12-21 19:14:31'),(3,'Serena Miller','bertram33@example.org','1-689-415-2035','197 Pagac Stream Suite 791\nAnaisville, DE 77856-1786','Quo corrupti sed aspernatur occaecati quam autem.','Quas quo laborum sequi quidem. Eaque autem quibusdam eligendi et et tenetur. Illum hic consequatur velit illum ducimus autem. Autem nobis fugiat non illum. Nemo sint vitae dolores ducimus. In iste aut et cumque. Nobis nihil voluptatum debitis quod reprehenderit dolores iusto reiciendis. Qui perspiciatis quia id voluptas sint voluptatibus. Eveniet veniam officia unde fugit. Aliquam aut quis itaque velit dolores. Blanditiis quo quidem est qui doloribus et non.',NULL,'unread','2025-12-21 19:14:31','2025-12-21 19:14:31'),(4,'Emmy Sanford PhD','vupton@example.net','+1.248.562.2949','75836 Weldon Tunnel Suite 806\nMarquardtport, ME 87612-4187','Qui quo ipsa sunt officiis in sint.','Placeat suscipit possimus earum sunt accusamus aut necessitatibus. Incidunt perferendis enim optio. Nihil qui et earum nobis. Iusto occaecati et architecto et nobis quisquam voluptatem et. Quod in voluptatum doloremque nesciunt suscipit. Quasi id nobis iusto explicabo aut. Et facilis voluptas quod vitae dolorem rem. Quo quas ducimus ea praesentium.',NULL,'unread','2025-12-21 19:14:31','2025-12-21 19:14:31'),(5,'Jaron Davis','hleffler@example.com','803.377.7923','36374 Kub Shores Apt. 741\nBednarville, NM 11763-1459','Facere laboriosam molestiae id labore.','Reiciendis maxime sit commodi illo sint voluptate eveniet. Dicta minus itaque sunt aut repudiandae. Tempore officia nesciunt atque sed velit. Non repellendus est quia. Commodi eligendi consequuntur quisquam. Aut amet neque reprehenderit. Sint est voluptatem quam necessitatibus aperiam consequatur corrupti. Iusto voluptas laborum et dolorum sed voluptatibus. Numquam qui ut similique optio similique.',NULL,'read','2025-12-21 19:14:31','2025-12-21 19:14:31'),(6,'Gia Klocko','cgrimes@example.com','828-455-8234','394 Mabel Ford\nJuliahaven, MA 52061','Eum blanditiis sit harum asperiores aut.','Amet et earum quidem ullam quod. Quo sunt sed quo ut quia porro. Tempora repellendus dolore quibusdam dicta quo nam. Optio repellendus rerum qui perspiciatis et quis. Et ut placeat eos illum. Aut ut non et atque. Error qui optio explicabo. Officia illo laborum fuga non pariatur impedit. Reiciendis aliquid quia soluta earum. Quam omnis maxime rem sit. Fugit quo beatae est id id tempora voluptatem.',NULL,'read','2025-12-21 19:14:31','2025-12-21 19:14:31'),(7,'Arturo Skiles','prince39@example.org','+1-380-908-5889','963 Pollich Flats Suite 923\nNew Kallieland, IA 49941','Quidem maxime veritatis natus reprehenderit.','Velit et qui vitae aperiam omnis. Omnis in cum excepturi eos velit blanditiis. Rerum molestiae voluptas tenetur eos ab velit. Voluptas odit quia illo qui sit velit sint sit. Ullam modi recusandae quae quia. Quaerat et eum explicabo dolor quia. Et alias omnis deleniti et velit est nihil. Pariatur saepe omnis sit assumenda fugit. In porro pariatur consequatur saepe. Ipsum occaecati qui sunt inventore deleniti sunt dolorem. Earum corrupti non laborum qui repudiandae atque ipsum.',NULL,'unread','2025-12-21 19:14:31','2025-12-21 19:14:31'),(8,'Nannie DuBuque DVM','magnolia.skiles@example.org','1-540-439-6968','14711 Dare Islands\nSouth Monteborough, IN 94999','Illo quam est molestiae voluptatem accusamus.','Id rerum tenetur consequatur eum numquam repellat. Pariatur quo facere quidem omnis voluptatem ut rem dolor. Illo velit fugit repellat iste inventore. Consequuntur quod qui doloribus quibusdam rerum cumque commodi suscipit. Totam quae minima sed atque autem. Praesentium dolor est assumenda magni. Necessitatibus ratione saepe quisquam est corporis ipsam sint et. Sit sequi maxime quia.',NULL,'read','2025-12-21 19:14:31','2025-12-21 19:14:31'),(9,'Prof. Margarette Graham','robel.allene@example.org','+17813074606','3555 Arnaldo Drive Apt. 713\nDeionbury, SD 31889','Tempore eos nostrum est magni eveniet et a.','Expedita est et eius beatae hic. Aut quia ipsam sunt dolor. Provident totam quibusdam fugit illum aut adipisci aliquam. Dolore non totam officia ad et eos in. Molestias nam rerum necessitatibus omnis asperiores. Nobis nemo est dolor sunt. Hic amet alias sunt deserunt. Sapiente sed et sapiente quidem recusandae quisquam. Officia nemo voluptatem illum autem praesentium quia. Eum ducimus aliquid similique. Voluptate tempora quo porro. Dolorum veniam facere voluptates dolor.',NULL,'unread','2025-12-21 19:14:31','2025-12-21 19:14:31'),(10,'Prof. Bonita Ziemann III','greenfelder.julie@example.org','+1-608-949-2350','97385 Zackery Inlet Suite 516\nJosueview, FL 93934','Sed vero aut aut.','Ipsam omnis dolor numquam placeat. Illum et dignissimos voluptatum omnis. Sed illo ut illum quas consequatur. In repellendus occaecati cumque error illum consequatur. Ratione voluptatibus illum veritatis aut. Qui placeat et itaque et veniam. Voluptatem qui aspernatur nisi quidem quasi assumenda minima. Perspiciatis ullam consequatur eligendi rem aut et. In culpa id recusandae labore dolores quia. Sit et qui quo in exercitationem rerum et. Velit at excepturi incidunt ipsa.',NULL,'read','2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_widget_settings`
--

DROP TABLE IF EXISTS `dashboard_widget_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_widget_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `widget_id` bigint unsigned NOT NULL,
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dashboard_widget_settings_user_id_index` (`user_id`),
  KEY `dashboard_widget_settings_widget_id_index` (`widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_widget_settings`
--

LOCK TABLES `dashboard_widget_settings` WRITE;
/*!40000 ALTER TABLE `dashboard_widget_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashboard_widget_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_widgets`
--

DROP TABLE IF EXISTS `dashboard_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_widgets`
--

LOCK TABLES `dashboard_widgets` WRITE;
/*!40000 ALTER TABLE `dashboard_widgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashboard_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_tokens`
--

DROP TABLE IF EXISTS `device_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_tokens_token_unique` (`token`),
  KEY `device_tokens_user_type_user_id_index` (`user_type`,`user_id`),
  KEY `device_tokens_platform_is_active_index` (`platform`,`is_active`),
  KEY `device_tokens_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_tokens`
--

LOCK TABLES `device_tokens` WRITE;
/*!40000 ALTER TABLE `device_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `fob_comments`
--

DROP TABLE IF EXISTS `fob_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fob_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reply_to` bigint unsigned DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fob_comments_author_type_author_id_index` (`author_type`,`author_id`),
  KEY `fob_comments_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `fob_comments_reply_to_index` (`reply_to`),
  KEY `fob_comments_reference_url_index` (`reference_url`),
  KEY `fob_comments_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fob_comments`
--

LOCK TABLES `fob_comments` WRITE;
/*!40000 ALTER TABLE `fob_comments` DISABLE KEYS */;
INSERT INTO `fob_comments` VALUES (1,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',3,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','This is really helpful, thank you!','approved','192.168.1.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-19 19:14:33','2025-12-21 19:14:33'),(2,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',6,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I found this article to be quite informative.','approved','192.168.1.2','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-04 19:14:33','2025-12-21 19:14:33'),(3,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','Wow, I never knew about this before!','approved','192.168.1.3','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-21 19:14:33','2025-12-21 19:14:33'),(4,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','Great job on explaining such a complex topic.','approved','192.168.1.4','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-25 19:14:33','2025-12-21 19:14:33'),(5,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',9,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','I have a question about the third paragraph.','approved','192.168.1.5','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-27 19:14:33','2025-12-21 19:14:33'),(6,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',4,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','This article changed my perspective entirely.','approved','192.168.1.6','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-10 19:14:33','2025-12-21 19:14:33'),(7,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',16,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I appreciate the effort you put into this.','approved','192.168.1.7','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-25 19:14:33','2025-12-21 19:14:33'),(8,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','This is exactly what I was looking for, thank you!','approved','192.168.1.8','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-05 19:14:33','2025-12-21 19:14:33'),(9,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','I disagree with some points mentioned here, though.','approved','192.168.1.9','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-25 19:14:33','2025-12-21 19:14:33'),(10,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',6,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','Could you provide more examples to illustrate your point?','approved','192.168.1.10','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-28 19:14:33','2025-12-21 19:14:33'),(11,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',1,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','I wish there were more articles like this out there.','approved','192.168.1.11','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-24 19:14:33','2025-12-21 19:14:33'),(12,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',14,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'m bookmarking this for future reference.','approved','192.168.1.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-13 19:14:33','2025-12-21 19:14:33'),(13,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',14,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','I\'ve shared this with my friends, they loved it!','approved','192.168.1.13','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-11 19:14:33','2025-12-21 19:14:33'),(14,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',3,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','This article is a must-read for everyone interested in the topic.','approved','192.168.1.14','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-04 19:14:33','2025-12-21 19:14:33'),(15,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',16,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','Thank you for shedding light on this important issue.','approved','192.168.1.15','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-11 19:14:33','2025-12-21 19:14:33'),(16,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',1,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','I\'ve been searching for information on this topic, glad I found this article.','approved','192.168.1.16','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-18 19:14:33','2025-12-21 19:14:33'),(17,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',11,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I\'m blown away by the insights shared in this article.','approved','192.168.1.17','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-26 19:14:33','2025-12-21 19:14:33'),(18,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',4,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','This article tackles a complex topic with clarity.','approved','192.168.1.18','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-20 19:14:33','2025-12-21 19:14:33'),(19,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',13,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','I\'m going to reflect on the ideas presented in this article.','approved','192.168.1.19','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-23 19:14:33','2025-12-21 19:14:33'),(20,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',2,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','The author\'s passion for the subject shines through in this article.','approved','192.168.1.20','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-08 19:14:33','2025-12-21 19:14:33'),(21,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','This article challenged my preconceptions in a thought-provoking way.','approved','192.168.1.21','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-04 19:14:33','2025-12-21 19:14:33'),(22,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',14,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'ve added this article to my reading list, it\'s worth revisiting.','approved','192.168.1.22','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-18 19:14:33','2025-12-21 19:14:33'),(23,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',14,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','This article offers practical advice that I can apply in real life.','approved','192.168.1.23','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-13 19:14:33','2025-12-21 19:14:33'),(24,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','I\'m going to recommend this article to my study group.','approved','192.168.1.24','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-08 19:14:33','2025-12-21 19:14:33'),(25,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',6,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','The examples provided really helped me understand the concept better.','approved','192.168.1.25','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-18 19:14:33','2025-12-21 19:14:33'),(26,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',14,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','I resonate with the ideas presented here.','approved','192.168.1.26','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-07 19:14:33','2025-12-21 19:14:33'),(27,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',11,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','This article made me think critically about the topic.','approved','192.168.1.27','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-16 19:14:33','2025-12-21 19:14:33'),(28,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',11,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','I\'ll definitely come back to this article for reference.','approved','192.168.1.28','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-17 19:14:33','2025-12-21 19:14:33'),(29,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','I\'ve shared this on social media, it\'s too good not to share.','approved','192.168.1.29','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-21 19:14:33','2025-12-21 19:14:33'),(30,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',10,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','This article presents a balanced view on a controversial topic.','approved','192.168.1.30','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-10 19:14:33','2025-12-21 19:14:33'),(31,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','I\'m glad I stumbled upon this article, it\'s a gem.','approved','192.168.1.31','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-23 19:14:33','2025-12-21 19:14:33'),(32,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',11,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'ve been struggling with this, your article helped a lot.','approved','192.168.1.32','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-20 19:14:33','2025-12-21 19:14:33'),(33,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','I\'ve learned something new today, thanks to this article.','approved','192.168.1.33','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-29 19:14:33','2025-12-21 19:14:33'),(34,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',11,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','Kudos to the author for a well-researched piece.','approved','192.168.1.34','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-13 19:14:33','2025-12-21 19:14:33'),(35,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',2,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','I\'m impressed by the depth of knowledge demonstrated here.','approved','192.168.1.35','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-12 19:14:33','2025-12-21 19:14:33'),(36,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',7,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','This article challenged my assumptions in a good way.','approved','192.168.1.36','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-28 19:14:33','2025-12-21 19:14:33'),(37,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',9,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I\'ve shared this with my colleagues, it\'s worth discussing.','approved','192.168.1.37','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-06 19:14:33','2025-12-21 19:14:33'),(38,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',1,'http://stories.test','Lisa Martinez','lisa.martinez@example.com','https://friendsofbotble.com','The information presented here is very valuable.','approved','192.168.1.38','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-22 19:14:33','2025-12-21 19:14:33'),(39,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',6,'http://stories.test','Robert Garcia','robert.garcia@example.com','https://friendsofbotble.com','You have a talent for explaining complex topics clearly.','approved','192.168.1.39','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-25 19:14:33','2025-12-21 19:14:33'),(40,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','Jessica Rodriguez','jessica.rodriguez@example.com','https://friendsofbotble.com','I\'m inspired to learn more about this after reading your article.','approved','192.168.1.40','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-28 19:14:33','2025-12-21 19:14:33'),(41,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',9,'http://stories.test','John Smith','john.smith@example.com','https://friendsofbotble.com','This article deserves wider recognition.','approved','192.168.1.41','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-30 19:14:33','2025-12-21 19:14:33'),(42,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',13,'http://stories.test','Emily Johnson','emily.johnson@example.com','https://friendsofbotble.com','I\'m grateful for the insights shared in this piece.','approved','192.168.1.42','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-12 19:14:33','2025-12-21 19:14:33'),(43,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',12,'http://stories.test','Michael Brown','michael.brown@example.com','https://friendsofbotble.com','The author presents a balanced view on a controversial topic.','approved','192.168.1.43','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-17 19:14:33','2025-12-21 19:14:33'),(44,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',5,'http://stories.test','Sarah Davis','sarah.davis@example.com','https://friendsofbotble.com','I\'m glad I stumbled upon this article, it\'s','approved','192.168.1.44','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-15 19:14:33','2025-12-21 19:14:33'),(45,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',15,'http://stories.test','James Wilson','james.wilson@example.com','https://friendsofbotble.com','I\'ve been searching for information on this topic, glad I found this article. It\'s incredibly insightful and provides a comprehensive overview of the subject matter. I appreciate the effort put into researching and writing this piece. It\'s truly eye-opening and has given me a new perspective. Thank you for sharing your knowledge with us!','approved','192.168.1.45','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-17 19:14:33','2025-12-21 19:14:33'),(46,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',7,'http://stories.test','Jennifer Taylor','jennifer.taylor@example.com','https://friendsofbotble.com','This article is a masterpiece! It dives deep into the topic and offers valuable insights that are both thought-provoking and enlightening. The author\'s expertise is evident throughout, making it a compelling read from start to finish. I\'ll definitely be coming back to this for reference in the future.','approved','192.168.1.46','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-12-08 19:14:33','2025-12-21 19:14:33'),(47,NULL,NULL,NULL,'Botble\\Blog\\Models\\Post',8,'http://stories.test','David Anderson','david.anderson@example.com','https://friendsofbotble.com','I\'m amazed by the depth of analysis in this article. It covers a wide range of aspects related to the topic, providing a comprehensive understanding. The clarity of explanation is commendable, making complex concepts easy to grasp. This article has enriched my understanding and sparked further curiosity. Kudos to the author!','approved','192.168.1.47','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36','2025-11-26 19:14:33','2025-12-21 19:14:33');
/*!40000 ALTER TABLE `fob_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galleries_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries`
--

LOCK TABLES `galleries` WRITE;
/*!40000 ALTER TABLE `galleries` DISABLE KEYS */;
INSERT INTO `galleries` VALUES (1,'Perfect','Harum quia iste reiciendis iusto est asperiores. Culpa reprehenderit consequatur accusamus omnis autem nihil. Et voluptatum libero magni qui.',1,0,'galleries/1.jpg',1,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(2,'New Day','Similique necessitatibus iste quisquam. Laborum aut incidunt eius rerum quae id enim. Fugiat incidunt maiores eum sint eos delectus aut est.',1,0,'galleries/2.jpg',1,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(3,'Happy Day','Explicabo quis et ullam enim. Aspernatur enim magni in nihil provident illo. Est omnis error beatae ad laudantium autem. Et sit corporis quae et.',1,0,'galleries/3.jpg',1,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(4,'Nature','Aut saepe doloribus quia aut iste. Itaque odio et rerum saepe sit rem. Perspiciatis quia asperiores deserunt illo in.',1,0,'galleries/4.jpg',1,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(5,'Morning','Amet unde ex consequatur. Voluptatem quod beatae accusamus voluptas blanditiis illo quia magnam. Quo occaecati sapiente odit a quam modi.',1,0,'galleries/5.jpg',1,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(6,'Photography','Nobis et quia esse corporis quam. Sed vel facilis voluptatem dicta autem aliquam. Quae sit et qui fugit repellendus ex et.',1,0,'galleries/6.jpg',1,'published','2025-12-21 19:14:32','2025-12-21 19:14:32');
/*!40000 ALTER TABLE `galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galleries_translations`
--

DROP TABLE IF EXISTS `galleries_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `galleries_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `galleries_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`galleries_id`),
  KEY `idx_galleries_trans_galleries_id` (`galleries_id`),
  KEY `idx_galleries_trans_gallery_lang` (`galleries_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `galleries_translations`
--

LOCK TABLES `galleries_translations` WRITE;
/*!40000 ALTER TABLE `galleries_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `galleries_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_meta`
--

DROP TABLE IF EXISTS `gallery_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `images` text COLLATE utf8mb4_unicode_ci,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_meta_reference_id_index` (`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_meta`
--

LOCK TABLES `gallery_meta` WRITE;
/*!40000 ALTER TABLE `gallery_meta` DISABLE KEYS */;
INSERT INTO `gallery_meta` VALUES (1,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"Eos voluptatem molestiae commodi facilis quo sit possimus. Asperiores et dolores recusandae excepturi quae.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Sit repellendus nobis ipsum aliquam rerum accusantium tempora. Facilis est impedit eligendi. Ut rerum aut est.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"Ratione aperiam assumenda delectus soluta omnis quia perferendis. Non nihil praesentium ad minus sint aut officiis sit. Quod unde qui odio itaque.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Ullam mollitia sit sapiente rerum. Quis hic deserunt omnis. Qui ratione ut eligendi aperiam.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"Cupiditate eveniet suscipit quod qui. Et voluptas cum sed numquam praesentium illum ea. Possimus qui sed odit aut praesentium id.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Nisi vel perferendis explicabo. Voluptatem qui reprehenderit aperiam omnis. Et in quasi sint nihil quod est.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"Ipsa aut accusantium molestiae veniam libero repellendus. Totam eius voluptatum libero vel nesciunt ut esse.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Voluptatem quasi dolorem sunt vero quibusdam. A doloribus dolor qui sit culpa. Magnam sunt voluptas quo impedit vel iste vero.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"Numquam sequi placeat accusantium in nesciunt quia. Consequatur dolorem repellat corporis voluptatibus.\"}]',1,'Botble\\Gallery\\Models\\Gallery','2025-12-21 19:14:32','2025-12-21 19:14:32'),(2,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"Eos voluptatem molestiae commodi facilis quo sit possimus. Asperiores et dolores recusandae excepturi quae.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Sit repellendus nobis ipsum aliquam rerum accusantium tempora. Facilis est impedit eligendi. Ut rerum aut est.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"Ratione aperiam assumenda delectus soluta omnis quia perferendis. Non nihil praesentium ad minus sint aut officiis sit. Quod unde qui odio itaque.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Ullam mollitia sit sapiente rerum. Quis hic deserunt omnis. Qui ratione ut eligendi aperiam.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"Cupiditate eveniet suscipit quod qui. Et voluptas cum sed numquam praesentium illum ea. Possimus qui sed odit aut praesentium id.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Nisi vel perferendis explicabo. Voluptatem qui reprehenderit aperiam omnis. Et in quasi sint nihil quod est.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"Ipsa aut accusantium molestiae veniam libero repellendus. Totam eius voluptatum libero vel nesciunt ut esse.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Voluptatem quasi dolorem sunt vero quibusdam. A doloribus dolor qui sit culpa. Magnam sunt voluptas quo impedit vel iste vero.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"Numquam sequi placeat accusantium in nesciunt quia. Consequatur dolorem repellat corporis voluptatibus.\"}]',2,'Botble\\Gallery\\Models\\Gallery','2025-12-21 19:14:32','2025-12-21 19:14:32'),(3,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"Eos voluptatem molestiae commodi facilis quo sit possimus. Asperiores et dolores recusandae excepturi quae.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Sit repellendus nobis ipsum aliquam rerum accusantium tempora. Facilis est impedit eligendi. Ut rerum aut est.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"Ratione aperiam assumenda delectus soluta omnis quia perferendis. Non nihil praesentium ad minus sint aut officiis sit. Quod unde qui odio itaque.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Ullam mollitia sit sapiente rerum. Quis hic deserunt omnis. Qui ratione ut eligendi aperiam.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"Cupiditate eveniet suscipit quod qui. Et voluptas cum sed numquam praesentium illum ea. Possimus qui sed odit aut praesentium id.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Nisi vel perferendis explicabo. Voluptatem qui reprehenderit aperiam omnis. Et in quasi sint nihil quod est.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"Ipsa aut accusantium molestiae veniam libero repellendus. Totam eius voluptatum libero vel nesciunt ut esse.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Voluptatem quasi dolorem sunt vero quibusdam. A doloribus dolor qui sit culpa. Magnam sunt voluptas quo impedit vel iste vero.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"Numquam sequi placeat accusantium in nesciunt quia. Consequatur dolorem repellat corporis voluptatibus.\"}]',3,'Botble\\Gallery\\Models\\Gallery','2025-12-21 19:14:32','2025-12-21 19:14:32'),(4,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"Eos voluptatem molestiae commodi facilis quo sit possimus. Asperiores et dolores recusandae excepturi quae.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Sit repellendus nobis ipsum aliquam rerum accusantium tempora. Facilis est impedit eligendi. Ut rerum aut est.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"Ratione aperiam assumenda delectus soluta omnis quia perferendis. Non nihil praesentium ad minus sint aut officiis sit. Quod unde qui odio itaque.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Ullam mollitia sit sapiente rerum. Quis hic deserunt omnis. Qui ratione ut eligendi aperiam.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"Cupiditate eveniet suscipit quod qui. Et voluptas cum sed numquam praesentium illum ea. Possimus qui sed odit aut praesentium id.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Nisi vel perferendis explicabo. Voluptatem qui reprehenderit aperiam omnis. Et in quasi sint nihil quod est.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"Ipsa aut accusantium molestiae veniam libero repellendus. Totam eius voluptatum libero vel nesciunt ut esse.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Voluptatem quasi dolorem sunt vero quibusdam. A doloribus dolor qui sit culpa. Magnam sunt voluptas quo impedit vel iste vero.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"Numquam sequi placeat accusantium in nesciunt quia. Consequatur dolorem repellat corporis voluptatibus.\"}]',4,'Botble\\Gallery\\Models\\Gallery','2025-12-21 19:14:32','2025-12-21 19:14:32'),(5,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"Eos voluptatem molestiae commodi facilis quo sit possimus. Asperiores et dolores recusandae excepturi quae.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Sit repellendus nobis ipsum aliquam rerum accusantium tempora. Facilis est impedit eligendi. Ut rerum aut est.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"Ratione aperiam assumenda delectus soluta omnis quia perferendis. Non nihil praesentium ad minus sint aut officiis sit. Quod unde qui odio itaque.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Ullam mollitia sit sapiente rerum. Quis hic deserunt omnis. Qui ratione ut eligendi aperiam.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"Cupiditate eveniet suscipit quod qui. Et voluptas cum sed numquam praesentium illum ea. Possimus qui sed odit aut praesentium id.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Nisi vel perferendis explicabo. Voluptatem qui reprehenderit aperiam omnis. Et in quasi sint nihil quod est.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"Ipsa aut accusantium molestiae veniam libero repellendus. Totam eius voluptatum libero vel nesciunt ut esse.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Voluptatem quasi dolorem sunt vero quibusdam. A doloribus dolor qui sit culpa. Magnam sunt voluptas quo impedit vel iste vero.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"Numquam sequi placeat accusantium in nesciunt quia. Consequatur dolorem repellat corporis voluptatibus.\"}]',5,'Botble\\Gallery\\Models\\Gallery','2025-12-21 19:14:32','2025-12-21 19:14:32'),(6,'[{\"img\":\"galleries\\/1.jpg\",\"description\":\"Eos voluptatem molestiae commodi facilis quo sit possimus. Asperiores et dolores recusandae excepturi quae.\"},{\"img\":\"galleries\\/2.jpg\",\"description\":\"Sit repellendus nobis ipsum aliquam rerum accusantium tempora. Facilis est impedit eligendi. Ut rerum aut est.\"},{\"img\":\"galleries\\/3.jpg\",\"description\":\"Ratione aperiam assumenda delectus soluta omnis quia perferendis. Non nihil praesentium ad minus sint aut officiis sit. Quod unde qui odio itaque.\"},{\"img\":\"galleries\\/4.jpg\",\"description\":\"Ullam mollitia sit sapiente rerum. Quis hic deserunt omnis. Qui ratione ut eligendi aperiam.\"},{\"img\":\"galleries\\/5.jpg\",\"description\":\"Cupiditate eveniet suscipit quod qui. Et voluptas cum sed numquam praesentium illum ea. Possimus qui sed odit aut praesentium id.\"},{\"img\":\"galleries\\/6.jpg\",\"description\":\"Nisi vel perferendis explicabo. Voluptatem qui reprehenderit aperiam omnis. Et in quasi sint nihil quod est.\"},{\"img\":\"galleries\\/7.jpg\",\"description\":\"Ipsa aut accusantium molestiae veniam libero repellendus. Totam eius voluptatum libero vel nesciunt ut esse.\"},{\"img\":\"galleries\\/8.jpg\",\"description\":\"Voluptatem quasi dolorem sunt vero quibusdam. A doloribus dolor qui sit culpa. Magnam sunt voluptas quo impedit vel iste vero.\"},{\"img\":\"galleries\\/9.jpg\",\"description\":\"Numquam sequi placeat accusantium in nesciunt quia. Consequatur dolorem repellat corporis voluptatibus.\"}]',6,'Botble\\Gallery\\Models\\Gallery','2025-12-21 19:14:32','2025-12-21 19:14:32');
/*!40000 ALTER TABLE `gallery_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gallery_meta_translations`
--

DROP TABLE IF EXISTS `gallery_meta_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gallery_meta_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gallery_meta_id` bigint unsigned NOT NULL,
  `images` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`gallery_meta_id`),
  KEY `idx_gallery_meta_trans_gm_id` (`gallery_meta_id`),
  KEY `idx_gallery_meta_trans_gm_lang` (`gallery_meta_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gallery_meta_translations`
--

LOCK TABLES `gallery_meta_translations` WRITE;
/*!40000 ALTER TABLE `gallery_meta_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `gallery_meta_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `language_meta`
--

DROP TABLE IF EXISTS `language_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language_meta` (
  `lang_meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lang_meta_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_meta_origin` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`lang_meta_id`),
  KEY `language_meta_reference_id_index` (`reference_id`),
  KEY `meta_code_index` (`lang_meta_code`),
  KEY `meta_origin_index` (`lang_meta_origin`),
  KEY `meta_reference_type_index` (`reference_type`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language_meta`
--

LOCK TABLES `language_meta` WRITE;
/*!40000 ALTER TABLE `language_meta` DISABLE KEYS */;
INSERT INTO `language_meta` VALUES (1,'en_US','f130dc0874058d3c44634685d9b91e0a',1,'Botble\\Menu\\Models\\MenuLocation'),(2,'en_US','bfedee3832999f59f29470d50e29dffc',1,'Botble\\Menu\\Models\\Menu'),(3,'en_US','d881fb3fa20b66001939b448dceb0bdf',2,'Botble\\Menu\\Models\\Menu');
/*!40000 ALTER TABLE `language_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `lang_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_locale` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_flag` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `lang_order` int NOT NULL DEFAULT '0',
  `lang_is_rtl` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  KEY `lang_locale_index` (`lang_locale`),
  KEY `lang_code_index` (`lang_code`),
  KEY `lang_is_default_index` (`lang_is_default`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'English','en','en_US','us',1,0,0);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_files`
--

DROP TABLE IF EXISTS `media_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `folder_id` bigint unsigned NOT NULL DEFAULT '0',
  `mime_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `visibility` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  PRIMARY KEY (`id`),
  KEY `media_files_user_id_index` (`user_id`),
  KEY `media_files_index` (`folder_id`,`user_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_files`
--

LOCK TABLES `media_files` WRITE;
/*!40000 ALTER TABLE `media_files` DISABLE KEYS */;
INSERT INTO `media_files` VALUES (2,0,'default','default',2,'image/jpeg',2100,'users/default.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(3,0,'author','author',3,'image/jpeg',17163,'general/author.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(4,0,'favicon','favicon',3,'image/png',734,'general/favicon.png','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(5,0,'featured','featured',3,'image/png',7905,'general/featured.png','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(6,0,'logo-white','logo-white',3,'image/png',1620,'general/logo-white.png','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(7,0,'logo','logo',3,'image/png',1732,'general/logo.png','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(8,0,'1','1',4,'image/jpeg',9803,'news/1.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(9,0,'10','10',4,'image/jpeg',9803,'news/10.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(10,0,'11','11',4,'image/jpeg',9803,'news/11.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(11,0,'12','12',4,'image/jpeg',9803,'news/12.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(12,0,'13','13',4,'image/jpeg',9803,'news/13.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(13,0,'14','14',4,'image/jpeg',9803,'news/14.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(14,0,'15','15',4,'image/jpeg',9803,'news/15.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(15,0,'16','16',4,'image/jpeg',9803,'news/16.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(16,0,'17','17',4,'image/jpeg',9803,'news/17.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(17,0,'18','18',4,'image/jpeg',9803,'news/18.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(18,0,'19','19',4,'image/jpeg',9803,'news/19.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(19,0,'2','2',4,'image/jpeg',9803,'news/2.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(20,0,'3','3',4,'image/jpeg',9803,'news/3.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(21,0,'4','4',4,'image/jpeg',9803,'news/4.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(22,0,'5','5',4,'image/jpeg',9803,'news/5.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(23,0,'6','6',4,'image/jpeg',9803,'news/6.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(24,0,'7','7',4,'image/jpeg',9803,'news/7.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(25,0,'8','8',4,'image/jpeg',9803,'news/8.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(26,0,'9','9',4,'image/jpeg',9803,'news/9.jpg','[]','2025-12-21 19:14:31','2025-12-21 19:14:31',NULL,'public'),(27,0,'1','1',5,'image/jpeg',9803,'categories/1.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(28,0,'2','2',5,'image/jpeg',9803,'categories/2.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(29,0,'3','3',5,'image/jpeg',9803,'categories/3.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(30,0,'4','4',5,'image/jpeg',9803,'categories/4.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(31,0,'5','5',5,'image/jpeg',9803,'categories/5.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(32,0,'6','6',5,'image/jpeg',9803,'categories/6.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(33,0,'7','7',5,'image/jpeg',9803,'categories/7.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(34,0,'1','1',6,'image/jpeg',9803,'galleries/1.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(35,0,'10','10',6,'image/jpeg',9803,'galleries/10.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(36,0,'2','2',6,'image/jpeg',9803,'galleries/2.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(37,0,'3','3',6,'image/jpeg',9803,'galleries/3.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(38,0,'4','4',6,'image/jpeg',9803,'galleries/4.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(39,0,'5','5',6,'image/jpeg',9803,'galleries/5.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(40,0,'6','6',6,'image/jpeg',9803,'galleries/6.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(41,0,'7','7',6,'image/jpeg',9803,'galleries/7.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(42,0,'8','8',6,'image/jpeg',9803,'galleries/8.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(43,0,'9','9',6,'image/jpeg',9803,'galleries/9.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(44,0,'1','1',7,'image/jpeg',9963,'banners/1.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(45,0,'2','2',7,'image/jpeg',9963,'banners/2.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public'),(46,0,'3','3',7,'image/jpeg',9963,'banners/3.jpg','[]','2025-12-21 19:14:32','2025-12-21 19:14:32',NULL,'public');
/*!40000 ALTER TABLE `media_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_folders`
--

DROP TABLE IF EXISTS `media_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_folders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_folders_user_id_index` (`user_id`),
  KEY `media_folders_index` (`parent_id`,`user_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_folders`
--

LOCK TABLES `media_folders` WRITE;
/*!40000 ALTER TABLE `media_folders` DISABLE KEYS */;
INSERT INTO `media_folders` VALUES (2,0,'users',NULL,'users',0,'2025-12-21 19:14:31','2025-12-21 19:14:31',NULL),(3,0,'general',NULL,'general',0,'2025-12-21 19:14:31','2025-12-21 19:14:31',NULL),(4,0,'news',NULL,'news',0,'2025-12-21 19:14:31','2025-12-21 19:14:31',NULL),(5,0,'categories',NULL,'categories',0,'2025-12-21 19:14:31','2025-12-21 19:14:31',NULL),(6,0,'galleries',NULL,'galleries',0,'2025-12-21 19:14:32','2025-12-21 19:14:32',NULL),(7,0,'banners',NULL,'banners',0,'2025-12-21 19:14:32','2025-12-21 19:14:32',NULL);
/*!40000 ALTER TABLE `media_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_settings`
--

DROP TABLE IF EXISTS `media_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `media_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_settings`
--

LOCK TABLES `media_settings` WRITE;
/*!40000 ALTER TABLE `media_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_locations`
--

DROP TABLE IF EXISTS `menu_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_locations_menu_id_created_at_index` (`menu_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_locations`
--

LOCK TABLES `menu_locations` WRITE;
/*!40000 ALTER TABLE `menu_locations` DISABLE KEYS */;
INSERT INTO `menu_locations` VALUES (1,1,'main-menu','2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `menu_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_nodes`
--

DROP TABLE IF EXISTS `menu_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_nodes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_font` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT '0',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `css_class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `has_child` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_nodes_menu_id_index` (`menu_id`),
  KEY `menu_nodes_parent_id_index` (`parent_id`),
  KEY `reference_id` (`reference_id`),
  KEY `reference_type` (`reference_type`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_nodes`
--

LOCK TABLES `menu_nodes` WRITE;
/*!40000 ALTER TABLE `menu_nodes` DISABLE KEYS */;
INSERT INTO `menu_nodes` VALUES (1,1,0,NULL,NULL,'/','elegant-icon icon_house_alt mr-5',0,'Home',NULL,'_self',1,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(2,1,1,NULL,NULL,'/',NULL,0,'Home default',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(3,1,1,2,'Botble\\Page\\Models\\Page','/home-2',NULL,1,'Home 2',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(4,1,1,3,'Botble\\Page\\Models\\Page','/home-3',NULL,2,'Home 3',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(5,1,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,1,'Travel',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(6,1,0,4,'Botble\\Blog\\Models\\Category',NULL,NULL,2,'Destination',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(7,1,0,6,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Hotels',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(8,1,0,9,'Botble\\Blog\\Models\\Category',NULL,NULL,4,'Lifestyle',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(9,1,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,5,'Blog',NULL,'_self',1,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(10,1,9,9,'Botble\\Page\\Models\\Page','/blog-grid-layout',NULL,0,'Grid layout',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(11,1,9,7,'Botble\\Page\\Models\\Page','/blog-list-layout',NULL,1,'List layout',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(12,1,9,8,'Botble\\Page\\Models\\Page','/blog-big-layout',NULL,2,'Big layout',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(13,1,0,NULL,NULL,'/galleries',NULL,6,'Galleries',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(14,1,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,7,'Contact',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(15,2,0,NULL,NULL,'/',NULL,0,'Homepage',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(16,2,0,5,'Botble\\Page\\Models\\Page','/contact',NULL,1,'Contact',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(17,2,0,4,'Botble\\Page\\Models\\Page','/blog',NULL,2,'Blog',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(18,2,0,2,'Botble\\Blog\\Models\\Category',NULL,NULL,3,'Travel',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31'),(19,2,0,NULL,NULL,'/galleries',NULL,4,'Galleries',NULL,'_self',0,'2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `menu_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Main menu','main-menu','published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(2,'Quick links','quick-links','published','2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meta_boxes`
--

DROP TABLE IF EXISTS `meta_boxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meta_boxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meta_boxes_reference_id_index` (`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meta_boxes`
--

LOCK TABLES `meta_boxes` WRITE;
/*!40000 ALTER TABLE `meta_boxes` DISABLE KEYS */;
INSERT INTO `meta_boxes` VALUES (1,'bio','[\"Hi, I\\u2019m System Admin, Your Blogging Journey Guide \\ud83d\\udd8b\\ufe0f. Writing, one blog post at a time, to inspire, inform, and ignite your curiosity. Join me as we explore the world through words and embark on a limitless adventure of knowledge and creativity. Let\\u2019s bring your thoughts to life on these digital pages. \\ud83c\\udf1f #BloggingAdventures\"]',1,'Botble\\ACL\\Models\\User','2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `meta_boxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'2013_04_09_032329_create_base_tables',1),(3,'2013_04_09_062329_create_revisions_table',1),(4,'2014_10_12_000000_create_users_table',1),(5,'2014_10_12_100000_create_password_reset_tokens_table',1),(6,'2016_06_10_230148_create_acl_tables',1),(7,'2016_06_14_230857_create_menus_table',1),(8,'2016_06_28_221418_create_pages_table',1),(9,'2016_10_05_074239_create_setting_table',1),(10,'2016_11_28_032840_create_dashboard_widget_tables',1),(11,'2016_12_16_084601_create_widgets_table',1),(12,'2017_05_09_070343_create_media_tables',1),(13,'2017_11_03_070450_create_slug_table',1),(14,'2019_01_05_053554_create_jobs_table',1),(15,'2019_08_19_000000_create_failed_jobs_table',1),(16,'2019_12_14_000001_create_personal_access_tokens_table',1),(17,'2022_04_20_100851_add_index_to_media_table',1),(18,'2022_04_20_101046_add_index_to_menu_table',1),(19,'2022_07_10_034813_move_lang_folder_to_root',1),(20,'2022_08_04_051940_add_missing_column_expires_at',1),(21,'2022_09_01_000001_create_admin_notifications_tables',1),(22,'2022_10_14_024629_drop_column_is_featured',1),(23,'2022_11_18_063357_add_missing_timestamp_in_table_settings',1),(24,'2022_12_02_093615_update_slug_index_columns',1),(25,'2023_01_30_024431_add_alt_to_media_table',1),(26,'2023_02_16_042611_drop_table_password_resets',1),(27,'2023_04_23_005903_add_column_permissions_to_admin_notifications',1),(28,'2023_05_10_075124_drop_column_id_in_role_users_table',1),(29,'2023_08_21_090810_make_page_content_nullable',1),(30,'2023_09_14_021936_update_index_for_slugs_table',1),(31,'2023_12_07_095130_add_color_column_to_media_folders_table',1),(32,'2023_12_17_162208_make_sure_column_color_in_media_folders_nullable',1),(33,'2024_04_04_110758_update_value_column_in_user_meta_table',1),(34,'2024_05_04_030654_improve_social_links',1),(35,'2024_05_12_091229_add_column_visibility_to_table_media_files',1),(36,'2024_07_07_091316_fix_column_url_in_menu_nodes_table',1),(37,'2024_07_12_100000_change_random_hash_for_media',1),(38,'2024_09_30_024515_create_sessions_table',1),(39,'2024_12_01_000000_add_indexes_to_pages_translations_table',1),(40,'2024_12_01_000000_add_key_prefix_index_to_slugs_table',1),(41,'2024_12_19_000001_create_device_tokens_table',1),(42,'2024_12_19_000002_create_push_notifications_table',1),(43,'2024_12_19_000003_create_push_notification_recipients_table',1),(44,'2024_12_30_000001_create_user_settings_table',1),(45,'2025_07_06_030754_add_phone_to_users_table',1),(46,'2025_07_31_add_performance_indexes_to_slugs_table',1),(47,'2025_11_10_000000_cleanup_duplicate_widgets',1),(48,'2025_11_30_100000_add_sessions_invalidated_at_to_users_table',1),(49,'2020_11_18_150916_ads_create_ads_table',2),(50,'2021_12_02_035301_add_ads_translations_table',2),(51,'2023_04_17_062645_add_open_in_new_tab',2),(52,'2023_11_07_023805_add_tablet_mobile_image',2),(53,'2024_04_01_043317_add_google_adsense_slot_id_to_ads_table',2),(54,'2025_04_21_000000_add_tablet_mobile_image_to_ads_translations_table',2),(55,'2024_04_27_100730_improve_analytics_setting',3),(56,'2023_08_11_060908_create_announcements_table',4),(57,'2025_02_11_153025_add_action_label_to_announcement_translations',4),(58,'2015_06_29_025744_create_audit_history',5),(59,'2023_11_14_033417_change_request_column_in_table_audit_histories',5),(60,'2025_05_05_000001_add_user_type_to_audit_histories_table',5),(61,'2025_11_07_000001_add_actor_type_to_audit_histories_table',5),(62,'2015_06_18_033822_create_blog_table',6),(63,'2021_02_16_092633_remove_default_value_for_author_type',6),(64,'2021_12_03_030600_create_blog_translations',6),(65,'2022_04_19_113923_add_index_to_table_posts',6),(66,'2023_08_29_074620_make_column_author_id_nullable',6),(67,'2024_07_30_091615_fix_order_column_in_categories_table',6),(68,'2024_12_01_000000_add_indexes_to_blog_translations_tables',6),(69,'2025_01_06_033807_add_default_value_for_categories_author_type',6),(70,'2016_06_17_091537_create_contacts_table',7),(71,'2023_11_10_080225_migrate_contact_blacklist_email_domains_to_core',7),(72,'2024_03_20_080001_migrate_change_attribute_email_to_nullable_form_contacts_table',7),(73,'2024_03_25_000001_update_captcha_settings_for_contact',7),(74,'2024_04_19_063914_create_custom_fields_table',7),(75,'2024_12_01_000000_add_indexes_to_contact_translations_tables',7),(76,'2024_01_16_050056_create_comments_table',8),(77,'2016_10_13_150201_create_galleries_table',9),(78,'2021_12_03_082953_create_gallery_translations',9),(79,'2022_04_30_034048_create_gallery_meta_translations_table',9),(80,'2023_08_29_075308_make_column_user_id_nullable',9),(81,'2024_12_01_000000_add_indexes_to_gallery_translations_tables',9),(82,'2016_10_03_032336_create_languages_table',10),(83,'2023_09_14_022423_add_index_for_language_table',10),(84,'2021_10_25_021023_fix-priority-load-for-language-advanced',11),(85,'2021_12_03_075608_create_page_translations',11),(86,'2023_07_06_011444_create_slug_translations_table',11),(87,'2024_12_01_000000_add_indexes_to_slugs_translations_table',11),(88,'2017_10_24_154832_create_newsletter_table',12),(89,'2024_03_25_000001_update_captcha_settings_for_newsletter',12),(90,'2016_10_07_193005_create_translations_table',13),(91,'2023_12_12_105220_drop_translations_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'subscribed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pages_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Home','<div>[about-banner title=\"Hello, I’m &lt;span&gt;Steven&lt;/span&gt;\" subtitle=\"Welcome to my blog\" text_muted=\"Travel Blogger., Content Writer., Food Guides\" image=\"general/featured.png\" newsletter_title=\"Don\'t miss out on the latest news about Travel tips, Hotels review, Food guide...\" image=\"general/featured.png\" show_newsletter_form=\"yes\"][/about-banner]</div><div>[featured-posts title=\"Featured posts\" enable_lazy_loading=\"yes\"][/featured-posts]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>',1,NULL,'homepage',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(2,'Home 2','<div>[featured-posts-slider-full][/featured-posts-slider-full]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>',1,NULL,'homepage',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(3,'Home 3','<div>[featured-posts-slider][/featured-posts-slider]</div><div>[blog-categories-posts category_id=\"2\" enable_lazy_loading=\"yes\"][/blog-categories-posts]</div><div>[categories-with-posts category_id_1=\"3\" category_id_2=\"4\" category_id_3=\"5\" enable_lazy_loading=\"yes\"][/categories-with-posts]</div><div>[featured-categories title=\"Categories\" enable_lazy_loading=\"yes\"][/featured-categories]</div>',1,NULL,'homepage',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(4,'Blog','---',1,NULL,'default',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(5,'Contact','<p>Address: North Link Building, 10 Admiralty Street, 757695 Singapore</p><p>Hotline: 18006268</p><p>Email: contact@botble.com</p><p>[google-map]North Link Building, 10 Admiralty Street, 757695 Singapore[/google-map]</p><p>For the fastest reply, please use the contact form below.</p><p>[contact-form][/contact-form]</p>',1,NULL,'default',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(6,'Cookie Policy','<h3>EU Cookie Consent</h3><p>To use this website we are using Cookies and collecting some Data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.</p><h4>Essential Data</h4><p>The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.</p><p>- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.</p><p>- XSRF-Token Cookie: Laravel automatically generates a CSRF \"token\" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.</p>',1,NULL,'default',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(7,'Blog List layout','<div>[blog-list limit=\"12\"][/blog-list]</div>',1,NULL,'right-sidebar',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(8,'Blog Big layout','<div>[blog-big limit=\"12\"][/blog-big]</div>',1,NULL,'default',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31'),(9,'Blog Grid layout','<div>[blog-big limit=\"12\"][/blog-big]</div>',1,NULL,'right-sidebar',NULL,'published','2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages_translations`
--

DROP TABLE IF EXISTS `pages_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pages_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`pages_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages_translations`
--

LOCK TABLES `pages_translations` WRITE;
/*!40000 ALTER TABLE `pages_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_categories`
--

DROP TABLE IF EXISTS `post_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_categories` (
  `category_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  KEY `post_categories_category_id_index` (`category_id`),
  KEY `post_categories_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_categories`
--

LOCK TABLES `post_categories` WRITE;
/*!40000 ALTER TABLE `post_categories` DISABLE KEYS */;
INSERT INTO `post_categories` VALUES (2,1),(3,1),(4,2),(1,2),(8,3),(4,3),(1,4),(5,5),(6,5),(4,6),(3,7),(1,7),(6,8),(3,8),(3,9),(8,9),(3,10),(5,11),(9,11),(4,12),(7,12),(6,13),(8,13),(4,14),(7,14),(2,15),(6,16),(8,16);
/*!40000 ALTER TABLE `post_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tags`
--

DROP TABLE IF EXISTS `post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_tags` (
  `tag_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  KEY `post_tags_tag_id_index` (`tag_id`),
  KEY `post_tags_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tags`
--

LOCK TABLES `post_tags` WRITE;
/*!40000 ALTER TABLE `post_tags` DISABLE KEYS */;
INSERT INTO `post_tags` VALUES (2,1),(3,1),(5,2),(3,2),(4,2),(2,3),(3,3),(5,3),(5,4),(2,4),(4,5),(1,5),(5,5),(1,6),(2,6),(3,6),(5,7),(1,7),(3,7),(2,8),(4,8),(4,9),(1,9),(1,10),(5,10),(3,11),(4,11),(2,11),(5,12),(3,12),(1,12),(1,13),(2,13),(1,14),(5,14),(2,15),(3,15),(1,15),(3,16),(5,16),(2,16);
/*!40000 ALTER TABLE `post_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` int unsigned NOT NULL DEFAULT '0',
  `format_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_author_id_index` (`author_id`),
  KEY `posts_author_type_index` (`author_type`),
  KEY `posts_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'The Top 2020 Handbag Trends to Know','Facere ab consequatur perferendis et nam sed iste. Dolore sapiente sit deleniti. Commodi repudiandae id tempore sed et quibusdam. Labore ipsum itaque sed blanditiis velit sint.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/7.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/17.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/11.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/1.jpg',773,'video','2025-12-21 19:14:32','2025-12-21 19:14:32'),(2,'Top Search Engine Optimization Strategies!','Odio asperiores omnis exercitationem quo asperiores autem quo illum. Architecto ut excepturi autem. Excepturi aut consequatur rerum hic quisquam. In odio enim natus iste laborum deserunt debitis.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/10.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/16.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/15.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/2.jpg',2448,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(3,'Which Company Would You Choose?','Officia non iste doloribus odio in rerum quod magnam. Dolore aut laudantium qui fugit nemo. Quod ullam est nostrum. Fugiat quisquam minima quis nisi molestiae provident.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/10.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/15.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/10.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/3.jpg',1509,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(4,'Used Car Dealer Sales Tricks Exposed','Sapiente fugit nisi blanditiis nihil magni consequuntur. Suscipit repellendus perspiciatis doloremque. Tenetur iusto quia rem. Esse nesciunt esse tempore.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/3.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/11.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/15.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/17.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/4.jpg',2191,'video','2025-12-21 19:14:32','2025-12-21 19:14:32'),(5,'20 Ways To Sell Your Product Faster','Est eos ex qui laudantium ipsam quos ab. Facilis qui eum qui autem in. Enim cupiditate saepe non in qui et iusto.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/8.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/18.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/14.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/5.jpg',658,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(6,'The Secrets Of Rich And Famous Writers','Quasi aspernatur totam atque. Non ab et sint alias quia tempora. Voluptatibus natus nihil nesciunt.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/5.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/8.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/16.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/16.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/6.jpg',1721,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(7,'Imagine Losing 20 Pounds In 14 Days!','Debitis libero quis necessitatibus eum esse quia. Quas laborum natus necessitatibus aut est. Aut ex et sint aliquam rerum sed. Eum nobis aut voluptate. Debitis eos ea culpa quis et magnam.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/2.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/11.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/15.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/11.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/7.jpg',1153,'video','2025-12-21 19:14:32','2025-12-21 19:14:32'),(8,'Are You Still Using That Slow, Old Typewriter?','Libero ut et aut et. Labore inventore veniam dignissimos tempore alias.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/2.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/8.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/15.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/14.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/8.jpg',1544,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(9,'A Skin Cream That’s Proven To Work','Facilis ut magni animi. Perspiciatis quae ut est maiores quia voluptatem ut. Et amet porro consequuntur blanditiis aperiam et aut.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/9.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/18.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/12.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/9.jpg',325,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(10,'10 Reasons To Start Your Own, Profitable Website!','Voluptatem eveniet eius eum. Ut autem vero consequatur autem eveniet eum. Aspernatur velit sequi eligendi consectetur unde quo. Porro quae explicabo ipsa illum quis.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/10.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/19.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/12.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',1,'news/10.jpg',895,'video','2025-12-21 19:14:32','2025-12-21 19:14:32'),(11,'Simple Ways To Reduce Your Unwanted Wrinkles!','Qui vero incidunt autem numquam est doloremque pariatur. Natus autem qui aut officiis quod id nisi. Consequatur dolor debitis fugit nesciunt quis tempore aut eligendi.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/10.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/17.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/18.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/11.jpg',2420,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(12,'Apple iMac with Retina 5K display review','Fugit occaecati enim neque animi velit. Blanditiis iure et suscipit et. Cum sequi sequi nemo. Quaerat consequatur ad sint quia consequatur ut inventore.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/6.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/19.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/13.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/12.jpg',934,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(13,'10,000 Web Site Visitors In One Month:Guaranteed','Minima quo distinctio beatae aut. Id sit aut sed nihil debitis est. Fugiat soluta est iusto voluptas explicabo debitis illum ipsam. Magni sint maiores odio perspiciatis officiis ut.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/1.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/10.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/16.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/17.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/13.jpg',2107,'video','2025-12-21 19:14:32','2025-12-21 19:14:32'),(14,'Unlock The Secrets Of Selling High Ticket Items','Asperiores alias cum iste sed cum dolore quos officia. Est unde temporibus ad sed ex. Aut quasi ut in laborum in ab quam. A veritatis consectetur molestiae sint earum excepturi.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/5.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/8.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/13.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/14.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/14.jpg',2254,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(15,'4 Expert Tips On How To Choose The Right Men’s Wallet','Possimus est maiores aut facere enim et eos. Tempora molestiae et ratione voluptate aut reprehenderit dolorem. Et fugit officiis ut sint.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/3.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/10.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/17.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/12.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/15.jpg',2046,'default','2025-12-21 19:14:32','2025-12-21 19:14:32'),(16,'Sexy Clutches: How to Buy &amp; Wear a Designer Clutch Bag','Facilis beatae praesentium eos. Architecto sit sit qui laborum est delectus. Eveniet quia repudiandae similique et.','<p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until.</p><p>[youtube-video]https://www.youtube.com/watch?v=SlPhMPnQ58k[/youtube-video]</p>   <hr class=\"wp-block-separator is-style-dots\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness <a href=\"/\">nightingale</a> the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <figure class=\"wp-block-gallery columns-3 wp-block-image\">\n                        <ul>\n                            <li><a href=\"/\"><img src=\"/storage/news/4.jpg\" alt=\"image 1\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/6.jpg\" alt=\"image 2\"></a></li>\n                            <li><a href=\"/\"><img src=\"/storage/news/15.jpg\" alt=\"image 3\"></a></li>\n                        </ul>\n                        <figcaption> <i class=\"ti-credit-card mr-5\"></i>Image credit: Behance </figcaption>\n                    </figure>\n                    <hr class=\"section-divider\">\n                    <p>Yet more some certainly yet alas abandonedly whispered <a href=\"/\">intriguingly</a><sup><a href=\"/\">[2]</a></sup> well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less <a href=\"/\">however</a> hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                    <h2>The Guitar Legends</h2>\n                    <p>Furrowed this in the upset <a href=\"/\">some across</a><sup><a href=\"/\">[3]</a></sup> tiger oh loaded house gosh whispered <a href=\"/\">faltering alas</a><sup><a href=\"/\">[4]</a></sup> ouch cuckoo coward in scratched undid together bit fumblingly so besides salamander heron during the jeepers hello fitting jauntily much smoothly globefish darn blessedly far so along bluebird leopard and.</p>\n                    <blockquote>\n                        <p>Integer eu faucibus <a href=\"/\">dolor</a><sup><a href=\"/\">[5]</a></sup>. Ut venenatis tincidunt diam elementum imperdiet. Etiam accumsan semper nisl eu congue. Sed aliquam magna erat, ac eleifend lacus rhoncus in.</p>\n                    </blockquote>\n                    <p>Fretful human far recklessly while caterpillar well a well blubbered added one a some far whispered rampantly whispered while irksome far clung irrespective wailed more rosily and where saluted while black dear so yikes as considering recast to some crass until cow much less and rakishly overdrew consistent for by responsible oh one hypocritical less bastard hey oversaw zebra browbeat a well.</p>\n                    <h3>Getting Crypto Rich</h3>\n                    <hr class=\"wp-block-separator is-style-wide\">\n                    <div class=\"wp-block-image\">\n                        <figure class=\"alignleft is-resized\">\n                            <img class=\"border-radius-5\" src=\"/storage/news/11.jpg\" alt=\"image 4\">\n                            <figcaption> And far contrary smoked some contrary among stealthy </figcaption>\n                        </figure>\n                    </div>\n                    <p>And far contrary smoked some contrary among stealthy engagingly suspiciously a cockatoo far circa sank dully lewd slick cracked llama the much gecko yikes more squirrel sniffed this and the the much within uninhibited this abominable a blubbered overdid foresaw through alas the pessimistic.</p>\n                    <p>Gosh jaguar ostrich quail one excited dear hello and bound and the and bland moral misheard roadrunner flapped lynx far that and jeepers giggled far and far bald that roadrunner python inside held shrewdly the manatee.</p>\n                    <br>\n                    <hr class=\"section-divider\">\n                    <p>Thanks sniffed in hello after in foolhardy and some far purposefully much one at the much conjointly leapt skimpily that quail sheep some goodness nightingale the instead exited expedient up far ouch mellifluous altruistic and and lighted more instead much when ferret but the.</p>\n                    <p>Yet more some certainly yet alas abandonedly whispered intriguingly well extensive one howled talkative admonishingly below a rethought overlaid dear gosh activated less however hawk yet oh scratched ostrich some outside crud irrespective lightheartedly and much far amenably that the elephant since when.</p>\n                ','published',1,'Botble\\ACL\\Models\\User',0,'news/16.jpg',1034,'video','2025-12-21 19:14:32','2025-12-21 19:14:32');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_translations`
--

DROP TABLE IF EXISTS `posts_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posts_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`posts_id`),
  KEY `idx_posts_trans_posts_id` (`posts_id`),
  KEY `idx_posts_trans_post_lang` (`posts_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_translations`
--

LOCK TABLES `posts_translations` WRITE;
/*!40000 ALTER TABLE `posts_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_notification_recipients`
--

DROP TABLE IF EXISTS `push_notification_recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_notification_recipients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `push_notification_id` bigint unsigned NOT NULL,
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `device_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `fcm_response` json DEFAULT NULL,
  `error_message` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pnr_notification_user_index` (`push_notification_id`,`user_type`,`user_id`),
  KEY `pnr_user_status_index` (`user_type`,`user_id`,`status`),
  KEY `pnr_user_read_index` (`user_type`,`user_id`,`read_at`),
  KEY `pnr_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_notification_recipients`
--

LOCK TABLES `push_notification_recipients` WRITE;
/*!40000 ALTER TABLE `push_notification_recipients` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_notification_recipients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_notifications`
--

DROP TABLE IF EXISTS `push_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `target_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_count` int NOT NULL DEFAULT '0',
  `failed_count` int NOT NULL DEFAULT '0',
  `delivered_count` int NOT NULL DEFAULT '0',
  `read_count` int NOT NULL DEFAULT '0',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `push_notifications_type_created_at_index` (`type`,`created_at`),
  KEY `push_notifications_status_scheduled_at_index` (`status`,`scheduled_at`),
  KEY `push_notifications_created_by_index` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_notifications`
--

LOCK TABLES `push_notifications` WRITE;
/*!40000 ALTER TABLE `push_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisions`
--

DROP TABLE IF EXISTS `revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `revisionable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revisionable_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revisions_revisionable_id_revisionable_type_index` (`revisionable_id`,`revisionable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisions`
--

LOCK TABLES `revisions` WRITE;
/*!40000 ALTER TABLE `revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_users` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_users_user_id_index` (`user_id`),
  KEY `role_users_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_users`
--

LOCK TABLES `role_users` WRITE;
/*!40000 ALTER TABLE `role_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`),
  KEY `roles_created_by_index` (`created_by`),
  KEY `roles_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Admin','{\"users.index\":true,\"users.create\":true,\"users.edit\":true,\"users.destroy\":true,\"roles.index\":true,\"roles.create\":true,\"roles.edit\":true,\"roles.destroy\":true,\"core.system\":true,\"core.cms\":true,\"core.manage.license\":true,\"systems.cronjob\":true,\"core.tools\":true,\"tools.data-synchronize\":true,\"media.index\":true,\"files.index\":true,\"files.create\":true,\"files.edit\":true,\"files.trash\":true,\"files.destroy\":true,\"folders.index\":true,\"folders.create\":true,\"folders.edit\":true,\"folders.trash\":true,\"folders.destroy\":true,\"settings.index\":true,\"settings.common\":true,\"settings.options\":true,\"settings.email\":true,\"settings.media\":true,\"settings.admin-appearance\":true,\"settings.cache\":true,\"settings.datatables\":true,\"settings.email.rules\":true,\"settings.phone-number\":true,\"settings.others\":true,\"menus.index\":true,\"menus.create\":true,\"menus.edit\":true,\"menus.destroy\":true,\"optimize.settings\":true,\"pages.index\":true,\"pages.create\":true,\"pages.edit\":true,\"pages.destroy\":true,\"plugins.index\":true,\"plugins.edit\":true,\"plugins.remove\":true,\"plugins.marketplace\":true,\"sitemap.settings\":true,\"core.appearance\":true,\"theme.index\":true,\"theme.activate\":true,\"theme.remove\":true,\"theme.options\":true,\"theme.custom-css\":true,\"theme.custom-js\":true,\"theme.custom-html\":true,\"theme.robots-txt\":true,\"settings.website-tracking\":true,\"widgets.index\":true,\"ads.index\":true,\"ads.create\":true,\"ads.edit\":true,\"ads.destroy\":true,\"ads.settings\":true,\"analytics.general\":true,\"analytics.page\":true,\"analytics.browser\":true,\"analytics.referrer\":true,\"analytics.settings\":true,\"announcements.index\":true,\"announcements.create\":true,\"announcements.edit\":true,\"announcements.destroy\":true,\"announcements.settings\":true,\"audit-log.index\":true,\"audit-log.destroy\":true,\"backups.index\":true,\"backups.create\":true,\"backups.restore\":true,\"backups.destroy\":true,\"plugins.blog\":true,\"posts.index\":true,\"posts.create\":true,\"posts.edit\":true,\"posts.destroy\":true,\"categories.index\":true,\"categories.create\":true,\"categories.edit\":true,\"categories.destroy\":true,\"tags.index\":true,\"blog.reports\":true,\"tags.create\":true,\"tags.edit\":true,\"tags.destroy\":true,\"blog.settings\":true,\"posts.export\":true,\"posts.import\":true,\"captcha.settings\":true,\"contacts.index\":true,\"contacts.edit\":true,\"contacts.destroy\":true,\"contact.custom-fields\":true,\"contact.settings\":true,\"fob-comment.index\":true,\"fob-comment.comments.index\":true,\"fob-comment.comments.edit\":true,\"fob-comment.comments.destroy\":true,\"fob-comment.comments.reply\":true,\"fob-comment.settings\":true,\"galleries.index\":true,\"galleries.create\":true,\"galleries.edit\":true,\"galleries.destroy\":true,\"languages.index\":true,\"languages.create\":true,\"languages.edit\":true,\"languages.destroy\":true,\"translations.import\":true,\"translations.export\":true,\"property-translations.import\":true,\"property-translations.export\":true,\"newsletter.index\":true,\"newsletter.destroy\":true,\"newsletter.settings\":true,\"plugins.translation\":true,\"translations.locales\":true,\"translations.theme-translations\":true,\"translations.index\":true,\"theme-translations.export\":true,\"other-translations.export\":true,\"theme-translations.import\":true,\"other-translations.import\":true,\"api.settings\":true,\"api.sanctum-token.index\":true,\"api.sanctum-token.create\":true,\"api.sanctum-token.destroy\":true}','Admin users role',1,1,1,'2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'media_random_hash','bcfb1753357d2c14b6a51d6c4f51f18e',NULL,'2025-12-21 19:14:32'),(2,'api_enabled','0',NULL,'2025-12-21 19:14:32'),(3,'activated_plugins','[\"language\",\"language-advanced\",\"ads\",\"analytics\",\"announcement\",\"audit-log\",\"backup\",\"blog\",\"captcha\",\"contact\",\"cookie-consent\",\"fob-comment\",\"gallery\",\"newsletter\",\"rss-feed\",\"translation\"]',NULL,'2025-12-21 19:14:32'),(4,'analytics_dashboard_widgets','0','2025-12-21 19:14:30','2025-12-21 19:14:30'),(5,'enable_recaptcha_botble_contact_forms_fronts_contact_form','1','2025-12-21 19:14:30','2025-12-21 19:14:30'),(6,'enable_recaptcha_botble_newsletter_forms_fronts_newsletter_form','1','2025-12-21 19:14:30','2025-12-21 19:14:30'),(7,'theme','stories',NULL,'2025-12-21 19:14:32'),(8,'show_admin_bar','1',NULL,'2025-12-21 19:14:32'),(9,'language_hide_default','1',NULL,'2025-12-21 19:14:32'),(10,'language_switcher_display','dropdown',NULL,'2025-12-21 19:14:32'),(11,'language_display','all',NULL,'2025-12-21 19:14:32'),(12,'language_hide_languages','[]',NULL,'2025-12-21 19:14:32'),(13,'theme-stories-cookie_consent_message','Your experience on this site will be improved by allowing cookies ',NULL,'2025-12-21 19:14:32'),(14,'theme-stories-cookie_consent_learn_more_url','/cookie-policy',NULL,'2025-12-21 19:14:32'),(15,'theme-stories-cookie_consent_learn_more_text','Cookie Policy',NULL,'2025-12-21 19:14:32'),(16,'theme-stories-social_links','[[{\"key\":\"name\",\"value\":\"Facebook\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-facebook\"},{\"key\":\"url\",\"value\":\"https:\\/\\/www.facebook.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#3b5999\"}],[{\"key\":\"name\",\"value\":\"X (Twitter)\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-x\"},{\"key\":\"url\",\"value\":\"https:\\/\\/x.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#000\"}],[{\"key\":\"name\",\"value\":\"linkedin\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-linkedin\"},{\"key\":\"url\",\"value\":\"https:\\/\\/www.linkedin.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#0a66c2\"}]]',NULL,'2025-12-21 19:14:32'),(17,'theme-stories-social_sharing','[[{\"key\":\"social\",\"value\":\"facebook\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-facebook\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#3b5999\"}],[{\"key\":\"social\",\"value\":\"x\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-x\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#000\"}],[{\"key\":\"social\",\"value\":\"linkedin\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-linkedin\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#0a66c2\"}]]',NULL,'2025-12-21 19:14:32'),(18,'theme-stories-site_title','Stories - Laravel Personal Blog Script',NULL,'2025-12-21 19:14:32'),(19,'theme-stories-seo_description','Stories is a clean and minimal Laravel blog script perfect for writers who need to create a personal blog site with simple creative features and effects to make readers feel the pleasure of reading blog posts and articles.',NULL,'2025-12-21 19:14:32'),(20,'theme-stories-copyright','©%Y Stories - Laravel Personal Blog Script',NULL,'2025-12-21 19:14:32'),(21,'theme-stories-designed_by','Designed by AliThemes | All rights reserved.',NULL,'2025-12-21 19:14:32'),(22,'theme-stories-favicon','general/favicon.png',NULL,'2025-12-21 19:14:32'),(23,'theme-stories-site_description','Start writing, no matter what. The water does not flow until the faucet is turned on.',NULL,'2025-12-21 19:14:32'),(24,'theme-stories-address','123 Main Street New York, NY 100012',NULL,'2025-12-21 19:14:32'),(25,'theme-stories-facebook','https://facebook.com',NULL,'2025-12-21 19:14:32'),(26,'theme-stories-twitter','https://twitter.com',NULL,'2025-12-21 19:14:32'),(27,'theme-stories-youtube','https://youtube.com',NULL,'2025-12-21 19:14:32'),(28,'theme-stories-homepage_id','1',NULL,'2025-12-21 19:14:32'),(29,'theme-stories-blog_page_id','4',NULL,'2025-12-21 19:14:32'),(30,'theme-stories-logo','general/logo.png',NULL,'2025-12-21 19:14:32'),(31,'theme-stories-action_button_text','Buy Now',NULL,'2025-12-21 19:14:32'),(32,'theme-stories-action_button_url','https://botble.com/go/stories',NULL,'2025-12-21 19:14:32'),(33,'theme-stories-vi-primary_font','Roboto',NULL,'2025-12-21 19:14:32'),(34,'admin_favicon','general/favicon.png',NULL,'2025-12-21 19:14:32'),(35,'admin_logo','general/logo-white.png',NULL,'2025-12-21 19:14:32'),(36,'announcement_max_width','1110',NULL,NULL),(37,'announcement_text_color','#687385',NULL,NULL),(38,'announcement_background_color','#f8f8f8',NULL,NULL),(39,'announcement_text_alignment','start',NULL,NULL),(40,'announcement_dismissible','1',NULL,NULL),(41,'announcement_font_size','12',NULL,NULL),(42,'announcement_font_size_unit','px',NULL,NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slugs`
--

DROP TABLE IF EXISTS `slugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slugs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slugs_reference_id_index` (`reference_id`),
  KEY `slugs_key_index` (`key`),
  KEY `slugs_prefix_index` (`prefix`),
  KEY `slugs_reference_index` (`reference_id`,`reference_type`),
  KEY `idx_key_prefix` (`key`,`prefix`),
  KEY `idx_slugs_reference` (`reference_type`,`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slugs`
--

LOCK TABLES `slugs` WRITE;
/*!40000 ALTER TABLE `slugs` DISABLE KEYS */;
INSERT INTO `slugs` VALUES (1,'home',1,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(2,'home-2',2,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(3,'home-3',3,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(4,'blog',4,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(5,'contact',5,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(6,'cookie-policy',6,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(7,'blog-list-layout',7,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(8,'blog-big-layout',8,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(9,'blog-grid-layout',9,'Botble\\Page\\Models\\Page','','2025-12-21 19:14:31','2025-12-21 19:14:31'),(10,'uncategorized',1,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(11,'travel',2,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(12,'guides',3,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(13,'destination',4,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(14,'food',5,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(15,'hotels',6,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(16,'review',7,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(17,'healthy',8,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(18,'lifestyle',9,'Botble\\Blog\\Models\\Category','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(19,'general',1,'Botble\\Blog\\Models\\Tag','tag','2025-12-21 19:14:32','2025-12-21 19:14:32'),(20,'design',2,'Botble\\Blog\\Models\\Tag','tag','2025-12-21 19:14:32','2025-12-21 19:14:32'),(21,'fashion',3,'Botble\\Blog\\Models\\Tag','tag','2025-12-21 19:14:32','2025-12-21 19:14:32'),(22,'branding',4,'Botble\\Blog\\Models\\Tag','tag','2025-12-21 19:14:32','2025-12-21 19:14:32'),(23,'modern',5,'Botble\\Blog\\Models\\Tag','tag','2025-12-21 19:14:32','2025-12-21 19:14:32'),(24,'the-top-2020-handbag-trends-to-know',1,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(25,'top-search-engine-optimization-strategies',2,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(26,'which-company-would-you-choose',3,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(27,'used-car-dealer-sales-tricks-exposed',4,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(28,'20-ways-to-sell-your-product-faster',5,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(29,'the-secrets-of-rich-and-famous-writers',6,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(30,'imagine-losing-20-pounds-in-14-days',7,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(31,'are-you-still-using-that-slow-old-typewriter',8,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(32,'a-skin-cream-thats-proven-to-work',9,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(33,'10-reasons-to-start-your-own-profitable-website',10,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(34,'simple-ways-to-reduce-your-unwanted-wrinkles',11,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(35,'apple-imac-with-retina-5k-display-review',12,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(36,'10000-web-site-visitors-in-one-monthguaranteed',13,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(37,'unlock-the-secrets-of-selling-high-ticket-items',14,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(38,'4-expert-tips-on-how-to-choose-the-right-mens-wallet',15,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(39,'sexy-clutches-how-to-buy-wear-a-designer-clutch-bag',16,'Botble\\Blog\\Models\\Post','','2025-12-21 19:14:32','2025-12-21 19:14:32'),(40,'perfect',1,'Botble\\Gallery\\Models\\Gallery','galleries','2025-12-21 19:14:32','2025-12-21 19:14:32'),(41,'new-day',2,'Botble\\Gallery\\Models\\Gallery','galleries','2025-12-21 19:14:32','2025-12-21 19:14:32'),(42,'happy-day',3,'Botble\\Gallery\\Models\\Gallery','galleries','2025-12-21 19:14:32','2025-12-21 19:14:32'),(43,'nature',4,'Botble\\Gallery\\Models\\Gallery','galleries','2025-12-21 19:14:32','2025-12-21 19:14:32'),(44,'morning',5,'Botble\\Gallery\\Models\\Gallery','galleries','2025-12-21 19:14:32','2025-12-21 19:14:32'),(45,'photography',6,'Botble\\Gallery\\Models\\Gallery','galleries','2025-12-21 19:14:32','2025-12-21 19:14:32');
/*!40000 ALTER TABLE `slugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slugs_translations`
--

DROP TABLE IF EXISTS `slugs_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slugs_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slugs_id` bigint unsigned NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  PRIMARY KEY (`lang_code`,`slugs_id`),
  KEY `idx_slugid_key_prefix` (`slugs_id`,`key`,`prefix`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slugs_translations`
--

LOCK TABLES `slugs_translations` WRITE;
/*!40000 ALTER TABLE `slugs_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `slugs_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'General',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(2,'Design',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(3,'Fashion',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(4,'Branding',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-12-21 19:14:32','2025-12-21 19:14:32'),(5,'Modern',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-12-21 19:14:32','2025-12-21 19:14:32');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags_translations`
--

DROP TABLE IF EXISTS `tags_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`tags_id`),
  KEY `idx_tags_trans_tags_id` (`tags_id`),
  KEY `idx_tags_trans_tag_lang` (`tags_id`,`lang_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags_translations`
--

LOCK TABLES `tags_translations` WRITE;
/*!40000 ALTER TABLE `tags_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_meta`
--

DROP TABLE IF EXISTS `user_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_meta_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_meta`
--

LOCK TABLES `user_meta` WRITE;
/*!40000 ALTER TABLE `user_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_settings_user_type_user_id_key_unique` (`user_type`,`user_id`,`key`),
  KEY `user_settings_user_type_user_id_index` (`user_type`,`user_id`),
  KEY `user_settings_key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_settings`
--

LOCK TABLES `user_settings` WRITE;
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_id` bigint unsigned DEFAULT NULL,
  `super_user` tinyint(1) NOT NULL DEFAULT '0',
  `manage_supers` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `last_login` timestamp NULL DEFAULT NULL,
  `sessions_invalidated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin@company.com',NULL,NULL,'$2y$12$XAcBUyi7PbvawE1inGmjvO5jQnYJMir66AeUFJWQirR9dreQNH9ru',NULL,'2025-12-21 19:14:31','2025-12-21 19:14:31','System','Admin','admin',2,1,1,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sidebar_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT '0',
  `data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `widgets_unique_index` (`theme`,`sidebar_id`,`widget_id`,`position`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
INSERT INTO `widgets` VALUES (1,'CustomMenuWidget','footer_sidebar','stories',0,'{\"id\":\"CustomMenuWidget\",\"name\":\"Quick links\",\"menu_id\":\"quick-links\"}','2025-12-21 19:14:31','2025-12-21 19:14:31'),(2,'TagsWidget','footer_sidebar','stories',1,'{\"id\":\"TagsWidget\",\"name\":\"Tags\",\"number_display\":5}','2025-12-21 19:14:31','2025-12-21 19:14:31'),(3,'NewsletterWidget','footer_sidebar','stories',2,'{\"id\":\"NewsletterWidget\",\"name\":\"Newsletter\"}','2025-12-21 19:14:31','2025-12-21 19:14:31'),(4,'AboutWidget','primary_sidebar','stories',0,'{\"id\":\"AboutWidget\",\"name\":\"Hello, I\'m Steven\",\"description\":\"Hi, I\\u2019m Steven, a Florida native, who left my career in corporate wealth management six years ago to embark on a summer of soul searching that would change the course of my life forever.\",\"image\":\"general\\/author.jpg\"}','2025-12-21 19:14:31','2025-12-21 19:14:31'),(5,'PopularPostsWidget','primary_sidebar','stories',1,'{\"id\":\"PopularPostsWidget\",\"name\":\"Most popular\",\"number_display\":5}','2025-12-21 19:14:31','2025-12-21 19:14:31'),(6,'GalleriesWidget','primary_sidebar','stories',2,'{\"id\":\"GalleriesWidget\",\"name\":\"Galleries\",\"number_display\":6}','2025-12-21 19:14:31','2025-12-21 19:14:31');
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-22  9:14:33
