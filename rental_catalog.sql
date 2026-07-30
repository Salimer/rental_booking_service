/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-11.8.6-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: jaccsx_test
-- ------------------------------------------------------
-- Server version	11.8.6-MariaDB-0+deb13u1 from Debian

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `types`
--

DROP TABLE IF EXISTS `types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(100) NOT NULL,
  `name_en` varchar(100) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `pricing_mode_default` enum('per_night','per_hour','per_slot') NOT NULL DEFAULT 'per_night',
  `icon` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `types_status_order_idx` (`status`,`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `types`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `types` WRITE;
/*!40000 ALTER TABLE `types` DISABLE KEYS */;
INSERT INTO `types` VALUES
(4,'فندق','Hotel','فندقنا يتميز بموقعه الهادئ والقريب من أهم المجمعات التجارية والمطاعم في المدينة. نوفر لك غرفاً وأجنحة واسعة ومؤثثة بالكامل لتشعر وكأنك في بيتك، مع توفر مطبخ صغير متكامل في الأجنحة، إنترنت سريع ومجاني، ومواقف سيارات أرضية خاصة ومراقبة على مدار الساعة لضمان راحتك وأمانك','Our hotel is known for its quiet location, close to the city\'s top malls and restaurants. We offer spacious, fully furnished rooms and suites designed to make you feel at home, featuring a fully equipped kitchenette in suites, fast free Wi-Fi, and private underground parking monitored 24/7 for your comfort and security','per_night','2026-07-03-6a476a10d6c3a.png',1,1,'2026-06-24 21:25:05','2026-07-03 08:51:44'),
(5,'شقة',NULL,NULL,NULL,'per_night',NULL,2,1,'2026-07-21 22:11:38','2026-07-21 22:11:50'),
(6,'شالية',NULL,NULL,NULL,'per_night',NULL,3,1,'2026-07-21 22:26:41','2026-07-21 22:26:41'),
(7,'استراحة',NULL,NULL,NULL,'per_night','2026-07-30-6a6b414eef892.png',4,1,'2026-07-30 13:16:23','2026-07-30 13:19:26');
/*!40000 ALTER TABLE `types` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(150) NOT NULL,
  `name_en` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `countries_name_ar_unique` (`name_ar`),
  UNIQUE KEY `countries_name_en_unique` (`name_en`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES
(1,'اليمن','Yemen','active','2026-06-24 18:21:20','2026-06-24 18:21:20');
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` bigint(20) unsigned NOT NULL,
  `name_ar` varchar(150) NOT NULL,
  `name_en` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `city_unique_en` (`country_id`,`name_en`),
  UNIQUE KEY `city_unique_ar` (`country_id`,`name_ar`),
  CONSTRAINT `cities_rental_country_id_foreign` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES
(1,1,'صنعاء','Sanaa','active','2026-06-24 18:21:38','2026-06-24 18:21:38'),
(2,1,'عدن','Aden','active','2026-06-29 20:55:34','2026-06-29 20:55:34'),
(3,1,'إب','Ibb','active','2026-06-29 20:58:01','2026-06-29 20:58:01');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `neighborhoods`
--

DROP TABLE IF EXISTS `neighborhoods`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `neighborhoods` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` bigint(20) unsigned NOT NULL,
  `name_ar` varchar(150) NOT NULL,
  `name_en` varchar(150) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `neighborhood_unique_en` (`city_id`,`name_en`),
  UNIQUE KEY `neighborhood_unique_ar` (`city_id`,`name_ar`),
  CONSTRAINT `neighborhoods_rental_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `neighborhoods`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `neighborhoods` WRITE;
/*!40000 ALTER TABLE `neighborhoods` DISABLE KEYS */;
INSERT INTO `neighborhoods` VALUES
(2,1,'حي الستين','حي الستين','active','2026-07-21 22:12:43','2026-07-25 16:44:51'),
(3,1,'حي الحصبة (جولة الحباري)','حي الحصبة (جولة الحباري)','active','2026-07-21 22:13:32','2026-07-25 16:44:37'),
(4,1,'حي الزبيري','حي الزبيري','active','2026-07-21 22:16:04','2026-07-25 16:44:07'),
(6,1,'حي عصر','حي عصر','active','2026-07-21 22:23:13','2026-07-25 16:43:59'),
(7,1,'حي خولان','حي خولان','active','2026-07-21 22:24:26','2026-07-25 16:43:46'),
(8,1,'حي الحصبة (جولة الساعة)','حي الحصبة (جولة الساعة)','active','2026-07-21 22:49:37','2026-07-25 16:43:30'),
(9,1,'حي حدة','حي حدة','active','2026-07-22 14:32:30','2026-07-25 16:42:38'),
(10,3,'حي السبل','حي السبل','active','2026-07-24 21:18:42','2026-07-24 21:18:42'),
(11,1,'حي بيت بوس','حي بيت بوس','active','2026-07-25 16:45:24','2026-07-25 16:45:24'),
(12,1,'حي ارتل','حي ارتل','active','2026-07-29 16:10:17','2026-07-29 16:10:17');
/*!40000 ALTER TABLE `neighborhoods` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `orgs`
--

DROP TABLE IF EXISTS `orgs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `orgs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `code` varchar(100) NOT NULL,
  `vendor_id` bigint(20) unsigned NOT NULL,
  `module_id` bigint(20) unsigned NOT NULL,
  `zone_id` bigint(20) unsigned DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address_ar` text DEFAULT NULL,
  `address_en` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `contact_phone` varchar(30) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `cover_photo` varchar(255) DEFAULT NULL,
  `preferred_currency` varchar(10) NOT NULL DEFAULT 'SAR',
  `status` enum('active','inactive','suspended','pending') NOT NULL DEFAULT 'pending',
  `commission` decimal(5,2) NOT NULL DEFAULT 10.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orgs_code_unique` (`code`),
  KEY `orgs_vendor_id_foreign` (`vendor_id`),
  KEY `orgs_module_id_foreign` (`module_id`),
  KEY `orgs_zone_id_foreign` (`zone_id`),
  KEY `orgs_status_idx` (`status`),
  CONSTRAINT `orgs_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`),
  CONSTRAINT `orgs_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`),
  CONSTRAINT `orgs_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orgs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `orgs` WRITE;
/*!40000 ALTER TABLE `orgs` DISABLE KEYS */;
INSERT INTO `orgs` VALUES
(1,'شركة جاك لإدارة الفنادق','JAC Management Hotels','JACM-8A91',370,21,NULL,NULL,NULL,NULL,NULL,NULL,'7811478900','info@jac-management.com',NULL,NULL,'YERN','active',10.00,'المكتب الرئيسي لإدارة وتنسيق حجوزات الفنادق والوحدات السكنية التابعة للمجموعة','2026-06-24 21:07:38','2026-07-18 20:01:16'),
(2,'رست نايت','Rest Night','REST-EEC2',371,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967777800716','ibrahim.restnight@gmail.com','2026-07-19-6a5d233187b2f.png','2026-07-19-6a5d21e580c2b.png','YERN','active',10.00,NULL,'2026-07-05 19:34:47','2026-07-23 13:16:06'),
(3,'كراون بلازا','Crowne','CROW-8FCB',372,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967778053142','jamal@crowne.com',NULL,'2026-07-10-6a510b2ede633.png','YERN','active',10.00,NULL,'2026-07-10 16:06:38','2026-07-23 13:31:16'),
(4,'انوار اليمن','Anwar Al-Yemen','ANWA-1FCA',373,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967778967784','anwar@gmail.com',NULL,NULL,'YERN','active',10.00,NULL,'2026-07-13 21:10:33','2026-07-23 13:30:23'),
(5,'يمن جراند','Yemen Grand','YEME-75F2',374,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967776300957','yemengrand@gmail.com',NULL,NULL,'YERN','active',10.00,NULL,'2026-07-15 10:01:44','2026-07-23 13:17:02'),
(6,'بحر الاحلام','SeaOfDream','SEA-1192',375,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967775441945','seaofdream@gmail.com',NULL,NULL,'YERN','active',10.00,NULL,'2026-07-15 13:16:41','2026-07-20 20:37:38'),
(11,'برج اليمن الدولي','Yemen International Tower','YEME-4471',380,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967779444993','ibrahimYemenInternationalTower@gmail.com',NULL,NULL,'YERN','active',10.00,NULL,'2026-07-21 23:13:29','2026-07-21 23:13:29'),
(12,'أورنتال إب','ORIENTAL IBB','ORIE-139F',381,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967774415823','Ali@gmail.com','2026-07-22-6a601ff3c050a.png','2026-07-22-6a6005d081898.png','YERN','active',10.00,NULL,'2026-07-22 00:50:40','2026-07-22 02:42:11'),
(13,'سوا','SAWA','SAWA-8BE4',382,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967777959697','Bashir@gmail.com','2026-07-23-6a6140e9a92a2.png','2026-07-23-6a6140e9ac045.png','YERN','active',8.00,NULL,'2026-07-22 23:15:05','2026-07-22 23:15:05'),
(14,'قصر لازوردي','QASR L\'AZURDE','QASR-803C',383,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967777031116','mohammedaloqab@gmail.com',NULL,NULL,'YERN','active',10.00,NULL,'2026-07-23 13:48:13','2026-07-23 13:48:13'),
(15,'اون لاين','Online Tourist','ONLI-8262',384,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967771166200','amiin@gmail.com','2026-07-26-6a666c9d187d7.png','2026-07-27-6a667687281c6.png','YERN','active',10.00,NULL,'2026-07-26 21:22:53','2026-07-26 22:05:11'),
(16,'شالية اوليف','Olive','OLIV-E0ED',385,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967730992733','chaletolive@gmail.com','2026-07-27-6a67ab48c15c3.png',NULL,'YERN','active',10.00,NULL,'2026-07-27 18:54:25','2026-07-27 20:12:13'),
(17,'رومانسية العرب','Romancea Al Arab','ROMA-EFFD',386,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967770000273','AHMMED@GMAIL.COM','2026-07-27-6a67a379300e5.png','2026-07-28-6a67e5549e4e7.png','YERN','active',10.00,'*الوصف:**\r\n\r\nيقع الفندق في موقع مميز، ويجمع بين التصميم العصري والراحة، ليقدم تجربة إقامة مناسبة للأفراد والعائلات ورجال الأعمال. يضم الفندق مجموعة متنوعة من الغرف والأجنحة المصممة بعناية لتلبية مختلف الاحتياجات، بدءًا من الغرف القياسية وصولًا إلى الأجنحة العائلية والأجنحة الملكية.\r\n\r\nتتميز الوحدات بأثاث فندقي أنيق، وإضاءة هادئة، ومساحات مريحة، مع توفير جميع المرافق الأساسية داخل كل وحدة، بما في ذلك شاشة ذكية، وخدمة الإنترنت اللاسلكي (Wi-Fi)، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ودورات مياه مجهزة بكافة المستلزمات الأساسية. كما تضم بعض الأجنحة صالات جلوس مستقلة، ومجالس عربية، ومناطق طعام، وشرفات بإطلالات مميزة، وأحواض جاكوزي، لتوفير أعلى مستويات الراحة والخصوصية.\r\n\r\nويحرص الفندق على توفير بيئة هادئة ونظيفة، مع خدمة استقبال، ومواقف سيارات، وخدمات ضيافة متكاملة، ليضمن للضيوف إقامة مريحة وآمنة طوال فترة إقامتهم.\r\n\r\n### المميزات\r\n\r\n* 🏨 غرف وأجنحة متنوعة بمساحات مختلفة.\r\n* 👨‍👩‍👧‍👦 أجنحة عائلية واسعة.\r\n* 👑 أجنحة ملكية وعرائسية فاخرة.\r\n* 🛏️ أسرّة كينغ سايز وأسرّة منفصلة مريحة.\r\n* 🛋️ صالات جلوس مستقلة في عدد من الوحدات.\r\n* 🏛️ مجالس عربية مريحة.\r\n* 🍽️ مناطق طعام في الأجنحة المختارة.\r\n* 🌅 شرفات بإطلالات مميزة في بعض الوحدات.\r\n* 🛁 أحواض جاكوزي في الأجنحة الملكية.\r\n* 📺 شاشات ذكية.\r\n* 🛜 خدمة Wi-Fi.\r\n* 🧊 ثلاجات صغيرة.\r\n* 👗 خزائن ملابس.\r\n* 📞 هواتف أرضية.\r\n* 🚿 دورات مياه مجهزة بكافة المستلزمات الأساسية.\r\n* 🚗 مواقف سيارات.\r\n* 🧹 نظافة وخدمة ضيافة.\r\n* 🌟 أجواء هادئة وخصوصية عالية.','2026-07-27 19:29:13','2026-07-28 00:10:12'),
(18,'الواحة الخضراء','Green Oasis Chalet','GREE-8827',387,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967783399988','greenchalet@gmail.com','2026-07-28-6a68df0f0c4ea.png',NULL,'YERN','active',10.00,NULL,'2026-07-28 17:54:48','2026-07-28 18:11:01'),
(19,'رواق هابي','Happy Riwaq','HAPP-8F74',388,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967777846097','Bakil@gmail.com','2026-07-28-6a68f158d9471.png','2026-07-28-6a68f158d98bd.png','YERN','active',10.00,'## استراحة ومقيل رواق هابي\r\n\r\n### الوصف:\r\n\r\nاستراحة ومقيل **رواق هابي** هي وجهة مثالية للتجمعات العائلية، والمقايل، واستقبال الضيوف، حيث تجمع بين المساحات الواسعة، والأجواء الهادئة، والتجهيزات العملية التي توفر الراحة والخصوصية. تضم الاستراحة **عدة مجالس عربية متنوعة الأحجام** ومجهزة بجلسات أرضية مريحة تتناسب مع مختلف أعداد الزوار، بالإضافة إلى **مجلس استقبال واسع**، وتلفزيونات بشاشات مسطحة في عدد من المجالس، ونوافذ كبيرة توفر إضاءة طبيعية وإطلالات جميلة، مع دورات مياه نظيفة ومجهزة بكافة المستلزمات الأساسية، لتمنح الزوار تجربة مريحة ومتكاملة تناسب اللقاءات العائلية والاجتماعية والمناسبات المختلفة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ عدة مجالس عربية بمساحات متنوعة.\r\n* 🛋️ جلسات أرضية مريحة تتناسب مع مختلف الأعداد.\r\n* 👥 مجلس استقبال واسع.\r\n* 📺 شاشات تلفزيون في المجالس.\r\n* 🌅 نوافذ كبيرة بإضاءة طبيعية وإطلالات جميلة.\r\n* ❄️ أجواء مريحة وتهوية جيدة.\r\n* ✨ تصميم داخلي بسيط وأنيق.\r\n* 🚿 دورات مياه مجهزة بكافة المستلزمات الأساسية.\r\n* 🧹 نظافة وخصوصية عالية.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل، والتجمعات العائلية، واستقبال الضيوف.','2026-07-28 19:13:44','2026-07-28 19:56:02'),
(20,'أبراج هلتون إب','HLTON IBB Towers','HLTO-B85B',389,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967779775779','Yousef@gmail.com','2026-07-28-6a69120d6bd73.png','2026-07-28-6a69168b03eaa.png','YERN','active',10.00,NULL,'2026-07-28 21:33:17','2026-07-28 21:52:27'),
(21,'الساعة الذهبية','Golden clock','GOLD-7B6C',390,21,NULL,NULL,NULL,NULL,NULL,NULL,'+967777000341','goldenclock@gmail.com','2026-07-30-6a6b5733a81dc.png',NULL,'YERN','active',10.00,NULL,'2026-07-30 14:52:51','2026-07-30 14:52:51');
/*!40000 ALTER TABLE `orgs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `org_settings`
--

DROP TABLE IF EXISTS `org_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `org_settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `org_id` bigint(20) unsigned NOT NULL,
  `cancellation_policy_en` text DEFAULT NULL,
  `cancellation_policy_ar` text DEFAULT NULL,
  `check_in_time` time NOT NULL DEFAULT '14:00:00',
  `check_out_time` time NOT NULL DEFAULT '11:00:00',
  `min_advance_booking_days` int(10) unsigned NOT NULL DEFAULT 1,
  `max_advance_booking_days` int(10) unsigned NOT NULL DEFAULT 365,
  `allow_instant_booking` tinyint(1) NOT NULL DEFAULT 1,
  `requires_id_verification` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `free_night_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `free_night_min_nights` tinyint(3) unsigned NOT NULL DEFAULT 3,
  `free_night_max_nights` tinyint(3) unsigned DEFAULT NULL,
  `free_nights_count` tinyint(3) unsigned NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `org_settings_rental_org_id_unique` (`org_id`),
  CONSTRAINT `org_settings_rental_org_id_foreign` FOREIGN KEY (`org_id`) REFERENCES `orgs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `org_settings` WRITE;
/*!40000 ALTER TABLE `org_settings` DISABLE KEYS */;
INSERT INTO `org_settings` VALUES
(1,1,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,1,1,'2026-06-24 21:07:38','2026-06-24 21:07:38',0,3,NULL,1),
(2,2,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,0,1,'2026-07-05 19:34:47','2026-07-23 13:16:06',1,3,NULL,1),
(3,3,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,0,1,'2026-07-10 16:06:38','2026-07-23 13:31:16',0,3,NULL,1),
(4,4,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,0,1,'2026-07-13 21:10:33','2026-07-23 13:30:23',1,3,NULL,1),
(5,5,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',0,30,0,1,'2026-07-15 10:01:44','2026-07-23 13:17:02',1,3,NULL,1),
(6,6,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,0,1,'2026-07-15 13:16:41','2026-07-23 13:16:41',1,3,NULL,1),
(11,11,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,0,1,'2026-07-21 23:13:29','2026-07-23 13:16:35',1,3,NULL,1),
(12,12,'No cancellations are allowed after the booking is confirmed','ليس هناك إلغا بعد تاكيد الحجز','14:00:00','14:00:00',1,30,0,1,'2026-07-22 00:50:40','2026-07-23 13:16:29',0,3,NULL,1),
(13,13,'Cancellation Policy\r\n\r\n* If a reservation is canceled less than **12 hours** before the scheduled check-in time, **50% of the daily room rate** will be charged.\r\n* If the reservation is not canceled before the check-in time or in the event of a **no-show**, **one full day\'s room rate** will be charged.\r\n\r\n**Thank you for your understanding. We look forward to serving you.**','سياسة إلغاء الحجز\r\n عند إلغاء الحجز قبل موعد تسجيل الدخول بأقل من 12 ساعة، يتم خصم 50% من قيمة أجرة اليوم.\r\n في حال عدم إلغاء الحجز قبل موعد تسجيل الدخول أو عدم حضور النزيل (عدم الحضور**)، يتم خصم قيمة أجرة يوم واحد كاملة.\r\n\r\nشكرًا لتفهمكم، ونتطلع إلى خدمتكم دائمًا.','12:00:00','12:59:00',1,30,0,1,'2026-07-22 23:15:05','2026-07-24 18:24:31',0,3,NULL,1),
(14,14,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,0,1,'2026-07-23 13:48:13','2026-07-23 13:48:13',0,3,NULL,1),
(15,15,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,30,1,1,'2026-07-26 21:22:53','2026-07-26 21:22:53',0,3,NULL,1),
(16,16,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','09:00:00','21:00:00',1,2,0,1,'2026-07-27 18:54:25','2026-07-28 13:51:42',0,3,NULL,1),
(17,17,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','13:00:00','12:00:00',1,30,1,1,'2026-07-27 19:29:13','2026-07-27 19:29:13',0,3,NULL,1),
(18,18,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','21:00:00','21:00:00',1,2,0,1,'2026-07-28 17:54:48','2026-07-28 17:54:48',0,3,NULL,1),
(19,19,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','00:00:00',1,30,1,1,'2026-07-28 19:13:44','2026-07-28 19:13:44',0,3,NULL,1),
(20,20,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','12:00:00','13:00:00',1,30,1,1,'2026-07-28 21:33:17','2026-07-28 21:33:17',0,3,NULL,1),
(21,21,'Moderate (Cancel up to 24h prior)','إلغاء متوسط (إلغاء مجاني حتى 24 ساعة قبل الحجز)','14:00:00','12:00:00',1,2,0,1,'2026-07-30 14:52:51','2026-07-30 14:52:51',0,3,NULL,1);
/*!40000 ALTER TABLE `org_settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `org_staff`
--

DROP TABLE IF EXISTS `org_staff`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `org_staff` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `org_id` bigint(20) unsigned NOT NULL,
  `vendor_id` bigint(20) unsigned DEFAULT NULL,
  `vendor_employee_id` bigint(20) unsigned DEFAULT NULL,
  `role` enum('owner','manager','receptionist') NOT NULL DEFAULT 'receptionist',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `invited_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `org_staff_unique` (`org_id`,`vendor_id`,`vendor_employee_id`),
  KEY `org_staff_vendor_id_foreign` (`vendor_id`),
  KEY `org_staff_vendor_employee_id_foreign` (`vendor_employee_id`),
  CONSTRAINT `org_staff_rental_org_id_foreign` FOREIGN KEY (`org_id`) REFERENCES `orgs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `org_staff_vendor_employee_id_foreign` FOREIGN KEY (`vendor_employee_id`) REFERENCES `vendor_employees` (`id`) ON DELETE CASCADE,
  CONSTRAINT `org_staff_vendor_id_foreign` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `org_staff`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `org_staff` WRITE;
/*!40000 ALTER TABLE `org_staff` DISABLE KEYS */;
/*!40000 ALTER TABLE `org_staff` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `org_id` bigint(20) unsigned NOT NULL,
  `type_id` bigint(20) unsigned NOT NULL,
  `city_id` bigint(20) unsigned DEFAULT NULL,
  `neighborhood_id` bigint(20) unsigned DEFAULT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `address_ar` text DEFAULT NULL,
  `address_en` text DEFAULT NULL,
  `latitude` varchar(50) DEFAULT NULL,
  `longitude` varchar(50) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `rules_ar` text DEFAULT NULL,
  `rules_en` text DEFAULT NULL,
  `avg_rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `rating_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `status` enum('active','inactive','draft') NOT NULL DEFAULT 'draft',
  `slug` varchar(255) DEFAULT NULL,
  `meta_title` varchar(150) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `star_rating` tinyint(3) unsigned DEFAULT NULL COMMENT '1-5 hotel class stars',
  PRIMARY KEY (`id`),
  KEY `properties_org_status_idx` (`org_id`,`status`),
  KEY `properties_type_status_idx` (`type_id`,`status`),
  KEY `properties_rental_city_id_foreign` (`city_id`),
  KEY `properties_rental_neighborhood_id_foreign` (`neighborhood_id`),
  KEY `properties_avg_rating_index` (`avg_rating`),
  CONSTRAINT `properties_rental_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `properties_rental_neighborhood_id_foreign` FOREIGN KEY (`neighborhood_id`) REFERENCES `neighborhoods` (`id`) ON DELETE SET NULL,
  CONSTRAINT `properties_rental_org_id_foreign` FOREIGN KEY (`org_id`) REFERENCES `orgs` (`id`),
  CONSTRAINT `properties_rental_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `types` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
INSERT INTO `properties` VALUES
(1,1,4,1,NULL,'فندق رويال فاملي','Royal Family Hotel','فندقنا يتميز بموقعه الهادئ والقريب من أهم المجمعات التجارية والمطاعم في المدينة. نوفر لك غرفاً وأجنحة واسعة ومؤثثة بالكامل، مع توفر مطبخ صغير متكامل في الأجنحة، إنترنت سريع ومجاني، ومواقف سيارات أرضية خاصة ومراقبة على مدار الساعة لضمان راحتك وأمانك','Our hotel is known for its quiet location, close to the city\'s top malls and restaurants. We offer spacious, fully furnished rooms and suites designed to make you feel at home, featuring a fully equipped kitchenette in suites, fast free Wi-Fi, and private underground parking monitored 24/7 for your comfort and security','صنعاء، حدة ، جسر المدينة، شارع الصرمي Haddah, Al-Madina Bridge, Al-Sarmi Street, صنعاء‎، اليَمَن','صنعاء، حدة ، جسر المدينة، شارع الصرمي Haddah, Al-Madina Bridge, Al-Sarmi Street, صنعاء‎، اليَمَن','15.3085587','44.1908728',NULL,'[\"2026-06-25-6a3d18abcce73.png\",\"2026-06-25-6a3d18abcefa0.png\",\"2026-06-25-6a3d18abcf10a.png\"]','1. إبراز الهوية الرسمية وعقد الزواج للعائلات عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n3. التدخين والحفلات ممنوعة تماماً داخل الغرف والوحدات.\r\n4. الالتزام بالعدد المحدد للنزلاء ووقت المغادرة (12:00 ظهراً)','1. Official ID and marriage certificate (for families) required upon check-in.\r\n2. Guests are financially responsible for any damage to property or furniture.\r\n3. Smoking and parties are strictly prohibited inside rooms and units.\r\n4. Compliance with max occupancy and check-out time (12:00 PM) is required',0.00,0,0,1,'inactive','royal-family-hotel',NULL,NULL,'2026-06-24 21:33:38','2026-07-18 20:01:16',4),
(2,1,4,1,NULL,'فندق ومنتجع نارسس صنعاء','Narcissus Sana\'a Hotel & Resort','يجمع فندق ومنتجع نارسس صنعاء بين الفخامة العصرية والأصالة اليمنية العريقة. يقدم المنتجع تجربة إقامة استثنائية من خلال غرف وأجنحة فاخرة مجهزة بأحدث التقنيات، بالإضافة إلى إطلالات ساحرة، مطاعم عالمية، مرافق ترفيهية متكاملة، ونادٍ صحي (سبا) لضمان أعلى مستويات الراحة والرفاهية لضيوفه','\"Narcissus Sana\'a Hotel & Resort combines modern luxury with authentic Yemeni heritage. The resort offers an exceptional stay experience featuring luxurious rooms and suites equipped with the latest technology, alongside stunning views, international dining options, integrated recreational facilities, and a premium spa to ensure the ultimate comfort and relaxation for our guests','شارع تعز ,تقاطع, شارع 22 مايو, صنعاء‎، اليَمَن','شارع تعز ,تقاطع, شارع 22 مايو, صنعاء‎، اليَمَن','15.2975567','44.237632','2026-06-29-6a42ccb4f335c.png','[\"2026-06-29-6a42ccb4f3579.png\"]',NULL,NULL,0.00,0,0,1,'inactive','narcissus-sanaa-hotel-resort',NULL,NULL,'2026-06-25 13:44:44','2026-07-27 20:10:21',5),
(3,1,4,1,NULL,'فندق دروب هيلتون','Daroub Hilton Hotel','فندق فاخر يتميز بموقع استراتيجي وإطلالات ساحرة، يقدم غرفاً وأجنحة عصرية مجهزة بالكامل، مع مرافق ترفيهية متكاملة، خدمة استقبال على مدار الساعة، ومواقف سيارات مجانية لضمان إقامة مريحة ومميزة لجميع الضيوف.','A luxury hotel featuring a strategic location and stunning views. It offers fully equipped modern rooms and suites, integrated recreational facilities, 24-hour reception, and free parking to ensure a comfortable and premium stay for all guests','شارع تعز شارع السفينه، صنعاء‎، اليَمَن','شارع تعز شارع السفينه، صنعاء‎، اليَمَن','15.311614295903285','44.2327755689621',NULL,'[\"2026-06-30-6a42eb983245b.png\"]',NULL,NULL,0.00,0,0,1,'inactive','daroub-hilton-hotel',NULL,NULL,'2026-06-29 23:03:04','2026-07-27 20:09:58',5),
(5,2,4,1,7,'فندق رست نايت','Rest Night Hotel','فندق عصري وهادئ يوفر إقامة مريحة للنزلاء، يتميز بغرف حديثة مؤثثة بالكامل ومجهزة بكافة وسائل الراحة، مع خدمات فندقية متكاملة على مدار الساعة.','A modern and peaceful hotel offering a comfortable stay, featuring fully equipped contemporary rooms with premium 24-hour hospitality services','شارع خولان، جوار الجوازات، صنعاء‎، اليَمَن','شارع خولان، جوار الجوازات، صنعاء‎، اليَمَن','15.3160487','44.2378197','2026-07-21-6a5f79c176740.png','[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n4. الالتزام بالوقت والمغادرة (12:00 ظهراً)',NULL,0.00,0,0,1,'active','rest-night-hotel',NULL,NULL,'2026-07-05 19:43:32','2026-07-21 22:24:52',4),
(6,3,4,1,6,'فندق كراون بلازا','كراون بلازا','فندق كراون يقدم تجربة إقامة مريحة وعملية، ويعد الخيار المثالي للمسافرين الباحثين عن الهدوء والقيمة المميزة. يوفر العقار غرفاً وأجنحة مؤثثة بشكل متكامل، مع موقع حيوي يسهل الوصول منه إلى أهم الخدمات والمراكز في المدينة.',NULL,'85R6+FMR، صنعاء‎، اليَمَن','85R6+FMR، صنعاء‎، اليَمَن','15.341273','44.161761',NULL,'[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n4. الالتزام بالوقت والمغادرة (12:00 ظهراً)',NULL,0.00,0,0,1,'active','kraon-blaza',NULL,NULL,'2026-07-10 16:14:49','2026-07-23 21:47:48',3),
(7,4,4,1,3,'فندق انوار اليمن','Anwar Al-Yemen','يقدم فندق انوار اليمن تجربة إقامة مريحة وبسيطة، مما يجعله الخيار الأمثل للزوار الباحثين عن إقامة اقتصادية وعملية. يوفر الفندق غرفاً نظيفة ومجهزة بالخدمات الأساسية التي تلبي الاحتياجات اليومية، مع موقع متميز يسهل الوصول منه إلى مختلف الوجهات والمراكز الحيوية.',NULL,'فندق انوار اليمن . اليابني للصرافه سابقاً الحصبه، 96H7+HQ5، جوله الحباري، صنعاء‎، اليَمَن','فندق انوار اليمن . اليابني للصرافه سابقاً الحصبه، 96H7+HQ5، جوله الحباري، صنعاء‎، اليَمَن','15.3788933','44.2144953',NULL,'[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n4. الالتزام بالوقت والمغادرة (12:00 ظهراً)',NULL,0.00,0,0,1,'active','anwar-al-yemen',NULL,NULL,'2026-07-13 21:23:45','2026-07-21 22:22:19',2),
(8,5,4,1,4,'فندق يمن جراند','فندق يمن جراند','يقدم فندق يمن جراند إقامة مريحة وعملية تجمع بين الخدمة المناسبة والأسعار الاقتصادية المتميزة. يوفر الفندق غرفاً مجهزة بكافة الخدمات الأساسية التي تضمن راحة ضيوفه، ويتميز بموقعه الحيوي.',NULL,'شارع الزبيري، صنعاء‎، اليَمَن','شارع الزبيري، صنعاء‎، اليَمَن','15.3494592','44.2093016','2026-07-29-6a6a1748abb08.png','[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n4. الالتزام بالوقت والمغادرة (12:00 ظهراً)',NULL,0.00,0,0,1,'active','fndk-ymn-grand',NULL,NULL,'2026-07-15 10:04:20','2026-07-29 16:07:52',2),
(9,6,4,1,2,'فندق بحر الاحلام','فندق بحر الاحلام','يقدم فندق بحر الأحلام تجربة إقامة هادئة ومريحة تتميز بالبساطة والأسعار الاقتصادية المناسبة. يوفر الفندق غرفاً نظيفة ومجهزة بكامل المستلزمات الأساسية لراحة الضيوف، مع موقع مميز يسهل الوصول منه إلى أهم الوجهات والخدمات المحيطة.',NULL,'953G+J3G، شارع الستين، صنعاء‎، اليَمَن','953G+J3G، شارع الستين، صنعاء‎، اليَمَن','15.3540724','44.1751932',NULL,'[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n4. الالتزام بالوقت والمغادرة (12:00 ظهراً)',NULL,0.00,0,0,1,'active','fndk-bhr-alahlam',NULL,NULL,'2026-07-15 13:20:38','2026-07-21 22:17:16',3),
(13,11,4,1,11,'فندق برج اليمن الدولي','فندق برج اليمن الدولي','يقدم فندق بحر الأحلام تجربة إقامة هادئة ومريحة تتميز بالبساطة والأسعار الاقتصادية المناسبة. يوفر الفندق غرفاً نظيفة ومجهزة بكامل المستلزمات الأساسية لراحة الضيوف، مع موقع مميز يسهل الوصول منه إلى أهم الوجهات والخدمات المحيطة.',NULL,'76GC+M67، صنعاء‎،، صنعاء‎، اليَمَن','76GC+M67، صنعاء‎،، صنعاء‎، اليَمَن','15.2767373','44.22058639999999','2026-07-22-6a5ff05c0adb9.png','[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n4. الالتزام بالوقت والمغادرة (12:00 ظهراً)',NULL,0.00,0,0,1,'active','fndk-brg-alymn-aldoly',NULL,NULL,'2026-07-21 23:19:08','2026-07-25 16:45:46',4),
(14,12,4,3,10,'فندق أورنتال إب','Oriental Ibb Hotel','## نبذة عن الفندق\r\n\r\nيقع الفندق في مدينة إب على خط السبل – جولة الثلاثين – بجوار مطعم سماء، ويتميز بموقعه الحيوي الذي يسهّل الوصول إلى أهم المرافق والخدمات في المدينة، مما يجعله خيارًا مناسبًا للإقامة سواء بغرض السياحة أو العمل.\r\n\r\n## الموقع\r\n\r\n* يقع على خط السبل – جولة الثلاثين.\r\n* بجوار مطعم سماء.\r\n* قريب من الأسواق والمحال التجارية.\r\n* بالقرب من المطاعم والمقاهي والخدمات اليومية.\r\n* سهولة الوصول إلى مختلف أحياء مدينة إب عبر الطرق الرئيسية.\r\n\r\n## الغرف\r\n\r\nيوفر الفندق غرفًا مريحة ومجهزة لتلبية احتياجات النزلاء، وتشمل:\r\n\r\n* أسرة مريحة.\r\n* تكييف هواء.\r\n* تلفزيون.\r\n* حمام خاص مزود بالمستلزمات الأساسية.\r\n* خدمة تنظيف يومية.\r\n* تتوفر خيارات متنوعة تناسب الإقامة القصيرة والطويلة.\r\n\r\n## الخدمات والمرافق\r\n\r\n* استقبال يعمل على مدار 24 ساعة.\r\n* خدمة الإنترنت اللاسلكي (Wi-Fi).\r\n* خدمة الغرف.\r\n* مواقف سيارات (حسب التوفر).\r\n* بيئة هادئة ومناسبة للعائلات.\r\n* مناسب لرجال الأعمال والمسافرين.\r\n\r\n## مميزات الفندق\r\n\r\n* موقع مميز في قلب مدينة إب.\r\n* سهولة الوصول إلى الأسواق والمطاعم والخدمات.\r\n* غرف نظيفة ومريحة.\r\n* طاقم عمل ودود ومتعاون.\r\n* قيمة جيدة مقابل السعر.\r\n\r\n## آراء النزلاء\r\n\r\n* إشادة بحسن الاستقبال والتعامل.\r\n* تقييمات إيجابية لنظافة الغرف.\r\n* رضا عن الموقع وسهولة التنقل.\r\n* بعض الملاحظات حول تفاوت سرعة الإنترنت وإمكانية تطوير بعض المرافق.\r\n\r\n## مناسب لـ\r\n\r\n* العائلات.\r\n* رجال الأعمال.\r\n* السياح والزوار.\r\n* المسافرين الباحثين عن إقامة مريحة في موقع حيوي داخل مدينة إب.','## Hotel Overview\r\n\r\nThe hotel is located in Ibb City on Al-Sabl Road, Thirtieth Roundabout, next to Sama Restaurant. It enjoys a convenient location with easy access to the city\'s main attractions, commercial areas, and essential services, making it an ideal choice for both business and leisure travelers.\r\n\r\n## Location\r\n\r\n* Located on Al-Sabl Road – Thirtieth Roundabout.\r\n* Next to Sama Restaurant.\r\n* Close to markets and shopping areas.\r\n* Near restaurants, cafés, and daily services.\r\n* Easy access to major roads and different districts of Ibb City.\r\n\r\n## Rooms\r\n\r\nThe hotel offers comfortable rooms designed for both short and long stays, featuring:\r\n\r\n* Comfortable beds.\r\n* Air conditioning.\r\n* Flat-screen TV.\r\n* Private bathroom with essential amenities.\r\n* Daily housekeeping.\r\n* Various room options to suit different guest needs.\r\n\r\n## Services & Facilities\r\n\r\n* 24-hour front desk.\r\n* Free Wi-Fi in most hotel areas.\r\n* Room service.\r\n* On-site parking (subject to availability).\r\n* Family-friendly environment.\r\n* Suitable for business travelers.\r\n\r\n## Hotel Highlights\r\n\r\n* Prime location in the heart of Ibb City.\r\n* Easy access to restaurants, shopping, and local attractions.\r\n* Clean and comfortable rooms.\r\n* Friendly and professional staff.\r\n* Excellent value for money.\r\n\r\n## Guest Reviews\r\n\r\nGuests frequently praise the hotel for:\r\n\r\n* Friendly and welcoming staff.\r\n* Clean and well-maintained rooms.\r\n* Convenient central location.\r\n* Good value compared to other hotels in Ibb.\r\n\r\nSome guests have noted:\r\n\r\n* Wi-Fi performance may vary during peak times.\r\n* Certain facilities could benefit from further improvements.\r\n\r\n## Ideal For\r\n\r\n* Families.\r\n* Business travelers.\r\n* Tourists and visitors.\r\n* Guests looking for a comfortable stay with easy access to the city\'s services and attractions.','خط السبل-جولة الثلاثين-جوار مطعم سماء، إب، اليَمَن','خط السبل-جولة الثلاثين-جوار مطعم سماء، إب، اليَمَن','13.9720098','44.1387027','2026-07-22-6a602011da322.png','[\"2026-07-22-6a601eb6d9ec1.png\",\"2026-07-22-6a601eb6da357.png\",\"2026-07-22-6a601eb6da459.png\",\"2026-07-22-6a601eb6da558.png\",\"2026-07-22-6a601eb6da69f.png\",\"2026-07-22-6a601eb6da7e0.png\",\"2026-07-22-6a601eb6daa60.png\",\"2026-07-22-6a601eb6dac0b.png\",\"2026-07-22-6a601eb6dadfb.png\",\"2026-07-22-6a601eb6daff5.png\",\"2026-07-22-6a601eb6db2c3.png\"]','## شروط وقواعد الفندق للنزلاء\r\n\r\n* إبراز الهوية الشخصية أو جواز السفر عند تسجيل الدخول.\r\n* الالتزام بأوقات تسجيل الدخول والمغادرة المحددة.\r\n* احترام هدوء الفندق وراحة النزلاء الآخرين.\r\n* المحافظة على ممتلكات الفندق، ويتحمل النزيل تكلفة أي أضرار.\r\n* يمنع حمل السلاح إلى وحدات الإقامة.\r\n* يمنع إدخال المواد الممنوعة أو الخطرة إلى الفندق.\r\n* الالتزام بتعليمات الأمن والسلامة داخل الفندق.\r\n* استقبال الزوار وفق أنظمة الفندق وتسجيل بياناتهم عند الحاجة.\r\n* تخضع عمليات الإلغاء والتعديل لسياسة الحجز المعتمدة.\r\n* قبول الحجز يعني الموافقة على شروط وقواعد الفندق.','## Hotel Policies & Guest Rules\r\n\r\n* Guests must present a valid ID card or passport upon check-in.\r\n* Guests must follow the hotel’s specified check-in and check-out times.\r\n* Guests are required to respect the quiet environment and comfort of other guests.\r\n* Guests are responsible for any damage caused to hotel property.\r\n* Carrying weapons inside guest rooms or hotel facilities is strictly prohibited.\r\n* Bringing prohibited or hazardous materials into the hotel is not allowed.\r\n* Guests must comply with hotel safety and security regulations.\r\n* Visitors are allowed according to hotel policies and may be required to provide identification details.\r\n* Cancellation and reservation modification requests are subject to the hotel’s booking policy.\r\n* Confirming a reservation means accepting and agreeing to all hotel terms and policies.',0.00,0,0,1,'active','oriental-ibb-hotel',NULL,NULL,'2026-07-22 02:36:54','2026-07-24 21:19:16',4),
(15,13,4,3,NULL,'فندق واجنحة سوا','Sawa Hotel & Suites','فندق وأجنحة سوا السياحي – إب\r\n\r\nاستمتعوا بإقامة مميزة في **فندق وأجنحة سوا السياحي** بمدينة إب، حيث يجتمع التصميم العصري مع الراحة والضيافة الراقية لتوفير تجربة إقامة متكاملة تناسب العائلات، رجال الأعمال، والزوار الباحثين عن الراحة والخصوصية.\r\n\r\nيقدم الفندق مجموعة متنوعة من **الغرف والأجنحة الفندقية المجهزة بعناية**، والتي تتميز بالمساحات المريحة، الأثاث الأنيق، والأجواء الهادئة التي توفر للنزلاء إقامة ممتعة ومريحة سواء للإقامات القصيرة أو الطويلة.\r\n\r\nيحرص الفندق على تقديم أعلى مستويات الخدمة من خلال فريق استقبال وضيافة محترف، مع توفير مجموعة من الخدمات والمرافق التي تلبي احتياجات الضيوف، ومنها:\r\n\r\n* غرف وأجنحة مريحة ومجهزة بالكامل.\r\n* خدمة استقبال وضيافة على مدار الساعة.\r\n* خدمة الغرف لتلبية احتياجات النزلاء.\r\n* خدمة إنترنت لاسلكي (Wi-Fi).\r\n* تكييف هواء في وحدات الإقامة.\r\n* شاشات تلفزيون داخل الغرف والأجنحة.\r\n* مواقف سيارات مخصصة للضيوف.\r\n* مطعم يقدم خيارات متنوعة للوجبات الخفيفة.\r\n* مصعد لتسهيل حركة الضيوف.\r\n* بيئة هادئة ونظيفة مع الاهتمام بالخصوصية.\r\n\r\nيتميز **فندق وأجنحة سوا السياحي** بموقعه المناسب في مدينة إب، مما يجعله خيارًا مثاليًا للإقامة أثناء الرحلات السياحية، زيارات العمل، والمناسبات العائلية، مع سهولة الوصول إلى مختلف الخدمات والمرافق المحيطة.\r\n\r\nنحن في فندق وأجنحة سوا نحرص على جعل إقامة ضيوفنا أكثر راحة ورضا من خلال خدمة مميزة، اهتمام بالتفاصيل، وأجواء تجمع بين الراحة والجودة.\r\n\r\n**فندق وأجنحة سوا السياحي... إقامة مريحة، خدمة مميزة، وتجربة تستحق التكرار.**','Sawa Hotel & Suites – Ibb\r\n\r\nExperience a comfortable and memorable stay at **Sawa Hotel & Suites – Ibb**, where modern comfort, privacy, and warm hospitality come together to provide an exceptional accommodation experience for families, business travelers, and visitors.\r\n\r\nThe hotel offers a selection of well-designed rooms and suites equipped with modern amenities to ensure a relaxing stay, whether for short visits or extended stays.\r\n\r\nGuests can enjoy a range of facilities and services, including:\r\n\r\n* Fully equipped rooms and suites.\r\n* 24-hour reception service.\r\n* Room service.\r\n* Free Wi-Fi.\r\n* Air conditioning.\r\n* Television facilities.\r\n* Guest parking.\r\n* Restaurant services.\r\n* Elevator access.\r\n* A clean, quiet, and comfortable environment.\r\n\r\nWith its convenient location in Ibb, **Sawa Hotel & Suites** is an ideal choice for guests visiting for tourism, business, or family occasions.\r\n\r\nOur dedicated team is committed to providing excellent service and ensuring every guest enjoys a comfortable and pleasant stay.\r\n\r\n**Sawa Hotel & Suites – Your comfort, our priority.**','إب، اليَمَن','إب، اليَمَن','13.9646762','44.1637576','2026-07-23-6a6156937a3cb.png','[\"2026-07-23-6a6156937a87e.png\",\"2026-07-23-6a6156937aac8.png\",\"2026-07-23-6a6156937ac5d.png\",\"2026-07-23-6a6156937adf2.png\",\"2026-07-23-6a6156937b0a1.png\",\"2026-07-23-6a6156937b666.png\",\"2026-07-23-6a6156937ba8a.png\",\"2026-07-23-6a6156937c006.png\",\"2026-07-23-6a6156937c4b7.png\",\"2026-07-23-6a6156937c9b7.png\",\"2026-07-23-6a6156937cec4.png\",\"2026-07-23-6a6156937d402.png\",\"2026-07-23-6a6156937da34.png\",\"2026-07-23-6a6156937dbba.png\",\"2026-07-23-6a6156937dd4c.png\",\"2026-07-23-6a6156937e2ba.png\",\"2026-07-23-6a6156937e91f.png\",\"2026-07-23-6a6156937eacb.png\",\"2026-07-23-6a6156937ec50.png\"]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الاثاث, والمستاجر مسؤول عن اي تلف.\r\n3. الإلتزام بالوقت والمغادرة (12:00ظهر)','1. **A valid government-issued ID must be presented upon check-in.**\r\n2. **Guests are responsible for maintaining the condition of the furniture and will be held liable for any damage caused during their stay.**\r\n3. **Guests are required to check out by 12:00 PM (noon).**',0.00,0,0,1,'active','sawa-hotel-suites',NULL,NULL,'2026-07-23 00:47:31','2026-07-24 18:19:16',4),
(16,14,4,1,9,'فندق  قصر لازوردي','فندق  قصر لازوردي','يقدم فندق قصر لازوردي تجربة إقامة مريحة وهادئة تتميز بالنظافة العالية والخدمة المتكاملة. يوفر الفندق غرفاً وأجنحة مجهزة بكامل المستلزمات الأساسية، مع توفر مصعد كهربائي وخدمة إنترنت (Wi-Fi)، وموقع مميز يسهل الوصول منه إلى أهم الوجهات والخدمات المحيطة.',NULL,'شارع حده امام، مجمع فلل حدة، صنعاء‎، اليَمَن','شارع حده امام، مجمع فلل حدة، صنعاء‎، اليَمَن','15.3020889','44.1824799','2026-07-23-6a620f348957e.png','[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n4. الالتزام بالوقت والمغادرة (12:00 ظهراً)',NULL,0.00,0,0,1,'active','fndk-ksr-lazordy',NULL,NULL,'2026-07-23 13:55:16','2026-07-23 19:51:05',4),
(17,15,4,3,NULL,'فندق اون لاين السياحي','Online Tourist Hotel','فندق أون لاين السياحي – إب\r\n\r\nالوصف:\r\n\r\nيقع **فندق أون لاين السياحي** في مدينة **إب**، ويوفر تجربة إقامة مريحة تجمع بين الموقع المميز، والتصميم العصري، والخدمات التي تلبي احتياجات المسافرين من العائلات، والأزواج، ورجال الأعمال. يضم الفندق مجموعة متنوعة من الغرف والأجنحة المصممة بعناية، والتي تختلف في المساحات والتجهيزات لتناسب جميع الاحتياجات، بدءًا من الغرف الاقتصادية وصولًا إلى الأجنحة العائلية والأجنحة الملكية.\r\n\r\nيتميز الفندق بأثاث فندقي أنيق، وتصاميم داخلية حديثة، وإضاءة هادئة، مع توفير جميع المرافق الأساسية داخل الوحدات، مثل الشاشات الذكية، وخدمة الإنترنت اللاسلكي (Wi-Fi)، والثلاجات الصغيرة، وخزائن الملابس، والهواتف الأرضية، ودورات المياه المجهزة بكافة المستلزمات الأساسية. كما تضم بعض الأجنحة شرفات بإطلالات على المدينة، ومجالس عربية، وصالات جلوس مستقلة، ومناطق طعام، وأحواض جاكوزي، لتوفير أعلى مستويات الراحة والخصوصية.\r\n\r\nيحرص الفندق على تقديم بيئة هادئة ونظيفة، مع خدمة استقبال متوفرة، ومواقف سيارات، وخدمات ضيافة مصممة لضمان إقامة مريحة وآمنة لجميع الضيوف.\r\n\r\n### المميزات\r\n\r\n* 🏨 غرف وأجنحة متنوعة تناسب جميع الاحتياجات.\r\n* 👨‍👩‍👧‍👦 أجنحة عائلية بمساحات واسعة.\r\n* 👑 أجنحة ملكية وعرائسية فاخرة.\r\n* 🛏️ أسرّة كينغ سايز وأسرّة منفصلة مريحة.\r\n* 🛋️ صالات جلوس ومجالس عربية في عدد من الوحدات.\r\n* 🍽️ مناطق طعام في الأجنحة المختارة.\r\n* 🌅 شرفات بإطلالات على المدينة في بعض الوحدات.\r\n* 🛁 أحواض جاكوزي في الأجنحة الملكية.\r\n* 📺 شاشات ذكية.\r\n* 🛜 خدمة Wi-Fi.\r\n* 🧊 ثلاجات صغيرة داخل الوحدات.\r\n* 👗 خزائن ملابس.\r\n* 📞 هواتف أرضية.\r\n* 🚿 دورات مياه مجهزة بكافة المستلزمات الأساسية.\r\n* 🚗 مواقف سيارات.\r\n* 🧹 نظافة يومية وخدمة ضيافة.\r\n* 🌟 أجواء هادئة وخصوصية عالية.\r\n\r\n### لماذا تختار فندق أون لاين السياحي؟\r\n\r\n* ✅ موقع مميز في مدينة إب.\r\n* ✅ خيارات إقامة تناسب الأفراد والعائلات والمجموعات.\r\n* ✅ وحدات سكنية متنوعة بمساحات مختلفة.\r\n* ✅ تجهيزات حديثة وتصميم عصري.\r\n* ✅ مستوى عالٍ من الراحة والخصوصية.\r\n* ✅ قيمة ممتازة مقابل السعر.\r\n* ✅ مناسب للإقامة القصيرة والطويلة.','Online Tourist Hotel – Ibb\r\n\r\nDescription\r\n\r\n**Online Tourist Hotel** is located in the city of **Ibb**, offering a comfortable stay that combines a convenient location, modern design, and quality services for families, couples, and business travelers. The hotel features a wide selection of rooms and suites, thoughtfully designed with different layouts and amenities to suit every guest\'s needs, ranging from standard rooms to spacious family suites and luxurious royal suites.\r\n\r\nThe hotel is furnished with elegant hotel-style décor, contemporary interiors, and ambient lighting, while every unit is equipped with essential amenities, including a Smart TV, private Wi-Fi, a mini refrigerator, wardrobe, telephone, and a private bathroom with all basic toiletries. Selected suites also feature private balconies with city views, Arabic majlis seating areas, separate living rooms, dining areas, and Jacuzzi bathtubs, providing exceptional comfort and privacy.\r\n\r\nThe hotel is committed to maintaining a clean, peaceful, and welcoming environment, with a dedicated reception desk, on-site parking, and hospitality services designed to ensure a comfortable and secure stay for every guest.\r\n\r\n## Features\r\n\r\n* 🏨 A variety of rooms and suites to suit different needs\r\n* 👨‍👩‍👧‍👦 Spacious family suites\r\n* 👑 Luxurious royal and bridal suites\r\n* 🛏️ Comfortable king-size and twin beds\r\n* 🛋️ Separate living rooms and traditional Arabic majlis seating in selected units\r\n* 🍽️ Dining areas available in selected suites\r\n* 🌅 Private balconies with city views in selected units\r\n* 🛁 Jacuzzi bathtubs in royal suites\r\n* 📺 Smart TVs\r\n* 🛜 Private Wi-Fi\r\n* 🧊 Mini refrigerators\r\n* 👗 Wardrobes\r\n* 📞 Telephones\r\n* 🚿 Private bathrooms with all essential amenities\r\n* 🚗 On-site parking\r\n* 🧹 Daily housekeeping and hospitality services\r\n* 🌟 Peaceful atmosphere with enhanced privacy\r\n\r\n## Why Choose Online Tourist Hotel?\r\n\r\n* ✅ Prime location in the heart of Ibb\r\n* ✅ Accommodation options for individuals, families, and groups\r\n* ✅ A wide range of rooms and suites with different layouts\r\n* ✅ Modern design and well-appointed amenities\r\n* ✅ High standards of comfort and privacy\r\n* ✅ Excellent value for money\r\n* ✅ Ideal for both short-term and extended stays','X48W+JG2، شارع الثلاثين، إب، اليَمَن','X48W+JG2، شارع الثلاثين، إب، اليَمَن','13.966527812258683','44.14626896381378','2026-07-26-6a66738f060d4.png','[]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n3. الالتزام بالوقت والمغادرة (12:00 ظهراً).','1. **Please present a valid government-issued ID upon check-in.**\r\n2. **Guests are responsible for maintaining the condition of the furniture and will be liable for any damages.**\r\n3. **Please adhere to the check-out time, which is 12:00 PM (noon).**',0.00,0,0,1,'active','online-tourist-hotel',NULL,NULL,'2026-07-26 21:52:31','2026-07-26 21:52:31',4),
(18,17,4,3,NULL,'فندق رومانسية العرب السياحي','Romance Al Arab Tourist Hotel','الفندق\r\n\r\n**الوصف:**\r\n\r\nيقع الفندق في موقع مميز، ويجمع بين التصميم العصري والراحة، ليقدم تجربة إقامة مناسبة للأفراد والعائلات ورجال الأعمال. يضم الفندق مجموعة متنوعة من الغرف والأجنحة المصممة بعناية لتلبية مختلف الاحتياجات، بدءًا من الغرف القياسية وصولًا إلى الأجنحة العائلية والأجنحة الملكية.\r\n\r\nتتميز الوحدات بأثاث فندقي أنيق، وإضاءة هادئة، ومساحات مريحة، مع توفير جميع المرافق الأساسية داخل كل وحدة، بما في ذلك شاشة ذكية، وخدمة الإنترنت اللاسلكي (Wi-Fi)، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ودورات مياه مجهزة بكافة المستلزمات الأساسية. كما تضم بعض الأجنحة صالات جلوس مستقلة، ومجالس عربية، ومناطق طعام، وشرفات بإطلالات مميزة، وأحواض جاكوزي، لتوفير أعلى مستويات الراحة والخصوصية.\r\n\r\nويحرص الفندق على توفير بيئة هادئة ونظيفة، مع خدمة استقبال، ومواقف سيارات، وخدمات ضيافة متكاملة، ليضمن للضيوف إقامة مريحة وآمنة طوال فترة إقامتهم.\r\n\r\n### المميزات\r\n\r\n* 🏨 غرف وأجنحة متنوعة بمساحات مختلفة.\r\n* 👨‍👩‍👧‍👦 أجنحة عائلية واسعة.\r\n* 👑 أجنحة ملكية وعرائسية فاخرة.\r\n* 🛏️ أسرّة كينغ سايز وأسرّة منفصلة مريحة.\r\n* 🛋️ صالات جلوس مستقلة في عدد من الوحدات.\r\n* 🏛️ مجالس عربية مريحة.\r\n* 🍽️ مناطق طعام في الأجنحة المختارة.\r\n* 🌅 شرفات بإطلالات مميزة في بعض الوحدات.\r\n* 🛁 أحواض جاكوزي في الأجنحة الملكية.\r\n* 📺 شاشات ذكية.\r\n* 🛜 خدمة Wi-Fi.\r\n* 🧊 ثلاجات صغيرة.\r\n* 👗 خزائن ملابس.\r\n* 📞 هواتف أرضية.\r\n* 🚿 دورات مياه مجهزة بكافة المستلزمات الأساسية.\r\n* 🚗 مواقف سيارات.\r\n* 🧹 نظافة وخدمة ضيافة.\r\n* 🌟 أجواء هادئة وخصوصية عالية.','# Hotel\r\n\r\n## Description\r\n\r\nThe hotel enjoys a prime location and combines modern design with exceptional comfort, offering a pleasant stay for individuals, families, and business travelers. It features a wide selection of rooms and suites, thoughtfully designed to meet a variety of accommodation needs, ranging from standard rooms to spacious family suites and luxurious royal suites.\r\n\r\nEach accommodation unit is furnished with elegant hotel-style furniture, soothing lighting, and comfortable interiors. Guests can enjoy essential in-room amenities, including a smart TV, private Wi-Fi, a mini refrigerator, a wardrobe, a landline telephone, and a private bathroom fully equipped with essential toiletries. Selected suites also feature separate living rooms, traditional Arabic majlis seating areas, dining areas, private balconies with scenic views, and Jacuzzi tubs, ensuring the highest levels of comfort, privacy, and relaxation.\r\n\r\nThe hotel is committed to providing a clean, peaceful, and welcoming environment, complemented by a reception service, on-site parking, and comprehensive hospitality services to ensure a comfortable and secure stay for every guest.\r\n\r\n## Features\r\n\r\n* 🏨 A variety of rooms and suites with different layouts.\r\n* 👨‍👩‍👧‍👦 Spacious family suites.\r\n* 👑 Luxurious royal and honeymoon suites.\r\n* 🛏️ Comfortable king-size and twin beds.\r\n* 🛋️ Separate living rooms in selected units.\r\n* 🏛️ Comfortable traditional Arabic majlis.\r\n* 🍽️ Dining areas in selected suites.\r\n* 🌅 Private balconies with scenic views in selected units.\r\n* 🛁 Jacuzzi tubs in the royal suites.\r\n* 📺 Smart TVs.\r\n* 🛜 Private Wi-Fi.\r\n* 🧊 Mini refrigerators.\r\n* 👗 Wardrobes.\r\n* 📞 Landline telephones.\r\n* 🚿 Private bathrooms equipped with essential amenities.\r\n* 🚗 On-site parking.\r\n* 🧹 Housekeeping and hospitality services.\r\n* 🌟 Peaceful atmosphere and enhanced privacy.','شارع الثلاثين، إب، اليَمَن','شارع الثلاثين، إب، اليَمَن','13.9667174','44.1431604','2026-07-27-6a67a60b59b61.png','[]',NULL,NULL,0.00,0,0,1,'active','romance-al-arab-tourist-hotel',NULL,NULL,'2026-07-27 19:40:11','2026-07-27 19:40:11',5),
(19,16,6,1,9,'شاليه أوليف','شاليه أوليف','استمتع بتجربة إقامة فاخرة ولحظات من الاسترخاء والخصوصية التامة في شاليه أوليف الفاخر. يضم الشاليه غرفة نوم أنيقة ومجهزة لراحتك، إلى جانب جلسة خارجية مميزة تتيح لك الاستمتاع بالأجواء الساحرة أمام شلال مائي يضفي لمسة من الهدوء والجمال على المكان، لتستمتع بأوقات لا تُنسى في بيئة تجمع بين الفخامة والراحة.',NULL,'شارع حده، صنعاء‎، اليَمَن','شارع حده، صنعاء‎، اليَمَن','15.3439269','44.1978101','2026-07-28-6a6913cb514fd.png','[]','أوقات الدخول والخروج: الالتزام بمواعيد تسليم واستلام الشاليه المحددة.\r\n\r\nسعة الشاليه: الحد الأقصى 25 شخصاً، ويُحسب 1000 ريال عن كل شخص إضافي.\r\n\r\nمبلغ التأمين: دفع تأمين مسترد للتعويض عن أي تلفيات أو أضرار بالممتلكات.\r\n\r\nالمحافظة والنظافة: إبقاء المرافق نظيفة وعدم العبث بالأجهزة أو الشلال.',NULL,0.00,0,0,1,'active','shalyh-aolyf',NULL,NULL,'2026-07-27 20:12:13','2026-07-28 21:40:43',4),
(20,18,6,1,12,'شالية الواحة الخضراء','شالية الواحة الخضراء','استمتع بتجربة إقامة واستجمام فاخرة في شاليهات الواحة الخضراء الممتدة على أربعة أدوار متكاملة؛ حيث يضم الدور الأول مسبحاً بجاكوزي وبخار وجيم مع مجالس مطلة، ويحتوي الدور الثاني على غرف نوم عائلية وشبابية ومطبخ وكافيه وركن تصوير. وتستمتع في الدور الثالث بمجلس تراثي على الطراز الصنعاني واستراحة سطح بحديقة وألعاب أطفال وشلالات وركن شواء، وصولاً إلى الدور الرابع الذي يضم طيرمانة زجاجية بإطلالة بانورامية وقاعة مجهزة للمناسبات والحفلات.',NULL,'765C+97P، أرتل، اليَمَن','765C+97P، أرتل، اليَمَن','15.2584492','44.2206704','2026-07-28-6a68e2a584078.png','[]','أوقات الدخول والخروج: الالتزام بمواعيد تسليم واستلام الشاليه المحددة.\r\n\r\nسعة الشاليه: الحد الأقصى 25 شخصاً، ويُحسب 1000 ريال عن كل شخص إضافي.\r\n\r\nمبلغ التأمين: دفع تأمين مسترد للتعويض عن أي تلفيات أو أضرار بالممتلكات.\r\n\r\nالمحافظة والنظافة: إبقاء المرافق نظيفة وعدم العبث بالأجهزة أو الشلال.',NULL,0.00,0,0,1,'active','shaly-aloah-alkhdraaa',NULL,NULL,'2026-07-28 18:11:01','2026-07-29 16:10:32',4),
(21,19,7,3,NULL,'استراحة ومقيل رواق هابي','Happy Riwaq Rest & Lounge','## استراحة ومقيل رواق هابي\r\n\r\n### الوصف:\r\n\r\nاستراحة ومقيل **رواق هابي** هي وجهة مثالية للتجمعات العائلية، والمقايل، واستقبال الضيوف، حيث تجمع بين المساحات الواسعة، والأجواء الهادئة، والتجهيزات العملية التي توفر الراحة والخصوصية. تضم الاستراحة **عدة مجالس عربية متنوعة الأحجام** ومجهزة بجلسات أرضية مريحة تتناسب مع مختلف أعداد الزوار، بالإضافة إلى **مجلس استقبال واسع**، وتلفزيونات بشاشات مسطحة في عدد من المجالس، ونوافذ كبيرة توفر إضاءة طبيعية وإطلالات جميلة، مع دورات مياه نظيفة ومجهزة بكافة المستلزمات الأساسية، لتمنح الزوار تجربة مريحة ومتكاملة تناسب اللقاءات العائلية والاجتماعية والمناسبات المختلفة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ عدة مجالس عربية بمساحات متنوعة.\r\n* 🛋️ جلسات أرضية مريحة تتناسب مع مختلف الأعداد.\r\n* 👥 مجلس استقبال واسع.\r\n* 📺 شاشات تلفزيون في المجالس.\r\n* 🌅 نوافذ كبيرة بإضاءة طبيعية وإطلالات جميلة.\r\n* ❄️ أجواء مريحة وتهوية جيدة.\r\n* ✨ تصميم داخلي بسيط وأنيق.\r\n* 🚿 دورات مياه مجهزة بكافة المستلزمات الأساسية.\r\n* 🧹 نظافة وخصوصية عالية.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل، والتجمعات العائلية، واستقبال الضيوف.','# Happy Riwaq Rest & Lounge\r\n\r\n## Description:\r\n\r\n**Happy Riwaq Rest & Lounge** is an ideal destination for family gatherings, traditional afternoon gatherings (Maqyal), and hosting guests. It combines spacious seating areas, a peaceful atmosphere, and practical amenities that ensure comfort and privacy. The lounge features **multiple Arabic majlis rooms of various sizes**, furnished with comfortable floor seating to accommodate different group sizes. It also includes a **spacious reception majlis**, flat-screen TVs in several seating areas, and large windows that provide natural light and pleasant views. Clean private bathrooms equipped with essential amenities complete the experience, making it a comfortable and welcoming venue for family gatherings, social events, and special occasions.\r\n\r\n## Features\r\n\r\n* 🏛️ Multiple Arabic majlis rooms in various sizes.\r\n* 🛋️ Comfortable floor seating for different group sizes.\r\n* 👥 Spacious reception majlis.\r\n* 📺 Flat-screen TVs in the seating areas.\r\n* 🌅 Large windows with natural light and pleasant views.\r\n* ❄️ Comfortable atmosphere with good ventilation.\r\n* ✨ Simple and elegant interior design.\r\n* 🚿 Private bathrooms equipped with essential amenities.\r\n* 🧹 Clean, comfortable, and private environment.\r\n* 👨‍👩‍👧‍👦 Ideal for Maqyal gatherings, family events, and hosting guests.','X596+9G2, N1, إب، اليَمَن','X596+9G2, N1, إب، اليَمَن','13.9683973','44.1612561','2026-07-28-6a68fb426631f.png','[\"2026-07-28-6a68fb42666d2.png\",\"2026-07-28-6a68fb426681c.png\",\"2026-07-28-6a68fb4266928.png\",\"2026-07-28-6a68fb4266a32.png\",\"2026-07-28-6a68fb4266b38.png\",\"2026-07-28-6a68fb4266c04.png\",\"2026-07-28-6a68fb4266ddf.png\",\"2026-07-28-6a68fb4266ebd.png\",\"2026-07-28-6a68fb426703e.png\",\"2026-07-28-6a68fb426710a.png\"]','1. إبراز الهوية الرسمية عند الدخول.\r\n2. المحافظة على سلامة الأثاث، والمستأجر مسؤول عن أي تلفيات.\r\n3. الالتزام بالوقت والمغادرة','1. Present a valid official ID upon check-in.\r\n2. Please take care of the furniture. Guests are responsible for any damage caused during their stay.\r\n3. Please adhere to the designated check-out time.',0.00,0,0,1,'active','happy-riwaq-rest-lounge',NULL,NULL,'2026-07-28 19:56:02','2026-07-30 13:16:44',4),
(22,21,4,1,NULL,'فندق الساعة الذهبية','فندق الساعة الذهبية','يقدم فندق الساعة الذهبية تجربة إقامة مريحة تجمع بين النظافة العالية والموقع الاستراتيجي المميز. يقع الفندق في قلب منطقة حيوية بالقرب من كافة الخدمات والمرافق الأساسية، مما يجعله الخيار المثالي للزوار والباحثين عن الراحة والسهولة في التنقل.',NULL,'96P5+8W4، صنعاء‎، اليَمَن','96P5+8W4، صنعاء‎، اليَمَن','15.3857536','44.209835','2026-07-30-6a6b58cd66bd2.png','[]','إبراز الهوية الشخصية، العائلية، أو جواز السفر عند الدخول.\r\n\r\nيمنع دخول السلاح للغرف والأجنحة.\r\n\r\nإيداع المبالغ والمجوهرات والأشياء الثمينة لدى أمانات الإدارة بسند رسمي، والإدارة غير مسؤولة عن أي مفقودات خلاف ذلك.\r\n\r\nيمنع إدخال أو تناول المشروبات الكحولية داخل الشقق.\r\n\r\nالحفاظ على المحتويات والأثاث مع الالتزام بالنظافة والهدوء.\r\n\r\nعدم إخراج مفاتيح الشقق أو الأجنحة خارج الفندق.\r\n\r\nموعد المغادرة وإخلاء الشقق الساعة (12) ظهراً، وإلا يُعد الحجز مستمراً.\r\n\r\nعند وجود أي تقصير في الخدمة يرجى إبلاغ الإدارة فوراً.',NULL,0.00,0,0,1,'active','fndk-alsaaa-althhby',NULL,NULL,'2026-07-30 14:59:41','2026-07-30 14:59:41',4);
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `units`
--

DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `property_id` bigint(20) unsigned NOT NULL,
  `name_ar` varchar(150) NOT NULL,
  `name_en` varchar(150) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `pricing_mode` enum('per_night','per_hour','per_slot') NOT NULL DEFAULT 'per_night',
  `max_guests` int(10) unsigned NOT NULL DEFAULT 1,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `quantity` int(10) unsigned NOT NULL DEFAULT 1,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `units_rental_property_id_foreign` (`property_id`),
  CONSTRAINT `units_rental_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `units`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `units` WRITE;
/*!40000 ALTER TABLE `units` DISABLE KEYS */;
INSERT INTO `units` VALUES
(9,1,'جناح عائلي ديلوكس(تجريبي)','Deluxe Family Suite','جناح عائلي واسع ومريح مصمم خصيصاً للعائلات. يتكون من غرفتي نوم، صالة جلوس مستقلة، مطبخ صغير مجهز بالكامل (ثلاجة، غلاية، مايكرويف)، شاشات ذكية متصلة بالإنترنت، وحمامين مستقلين مع كافة مستلزمات العناية','A spacious and comfortable family suite specially designed for families. It features two bedrooms, a separate living room, a fully equipped kitchenette (fridge, kettle, microwave), smart TVs, and two independent bathrooms with all amenities','per_night',4,'[\"2026-06-25-6a3d1a0b13c3d.png\",\"2026-06-25-6a3d1a0b14247.png\",\"2026-06-25-6a3d1a0b1444c.png\",\"2026-06-25-6a3d1a0b14669.png\"]',1,'active','2026-06-24 23:03:13','2026-07-07 11:44:49'),
(10,1,'غرفة قياسية مزدوجة(تجريبي)','Standard Double Room','غرفة مريحة وعملية مؤثثة بالكامل، تحتوي على سرير مزدوج كبير، شاشة مسطحة، نظام تكييف، ثلاجة صغيرة، وغلاية لصنع القهوة والشاي، بالإضافة إلى حمام خاص مزود بجميع المستلزمات الأساسية. توفر إقامة اقتصادية ومثالية لشخصين.','A comfortable and practical fully furnished room, featuring a large double bed, flat-screen TV, air conditioning, a mini-fridge, and a tea/coffee maker, along with a private bathroom equipped with all essential amenities. Ideal for an affordable stay for two.','per_night',2,'[\"2026-06-25-6a3d1f6f12230.png\",\"2026-06-29-6a42cbffd4ce4.png\"]',1,'active','2026-06-25 13:30:39','2026-07-07 11:44:39'),
(11,2,'جناح عائلي(تجريبي)','Family Suite','جناح عائلي فاخر يتكون من غرفتي نوم منفصلتين وصالة جلوس مستقلة، تكييف مركزي وعزل صوت، إنترنت واي فاي سريع ومجاني، شاشات ذكية 4K، ثلاجة صغيرة وآلة قهوة، حمام رخامي فاخر، وخدمة غرف على مدار 24 ساعة.','Luxury family suite featuring 2 separate bedrooms and a private living room, central AC, soundproofing, free high-speed Wi-Fi, 4K Smart TVs, a mini-fridge, Espresso machine, luxury marble bathroom, and 24-hour room service.','per_night',5,'[\"2026-06-25-6a3d24ddb3436.png\",\"2026-06-25-6a3d24ddb3a26.png\",\"2026-06-25-6a3d24ddb3cfc.png\",\"2026-06-25-6a3d255a10e23.png\",\"2026-06-25-6a3d255a113bf.png\",\"2026-06-25-6a3d255a115f4.png\"]',1,'active','2026-06-25 13:53:49','2026-07-07 11:44:31'),
(12,3,'جناح جونيور فاخر (تجريبي)','Luxury Junior Suite','جناح واسع بتصميم عصري يضم سرير كينج كبير ومنطقة جلوس أنيقة ومدمجة، تكييف مركزي وعزل صوت، إنترنت واي فاي سريع ومجاني، شاشة ذكية 55 بوصة، آلة صنع قهوة إسبريسو وثلاجة صغيرة، مع حمام رخامي فاخر ومستلزمات عناية مجانية.','Spacious suite with a modern design featuring a large King bed and an elegant integrated seating area, central AC, soundproofing, free high-speed Wi-Fi, 55\" Smart TV, Espresso machine, mini-fridge, and a luxury marble bathroom with free toiletries','per_night',5,'[\"2026-07-02-6a46ae3d5ce50.png\",\"2026-07-02-6a46aefa7230b.png\",\"2026-07-02-6a46aefa728d6.png\"]',1,'active','2026-06-29 23:07:48','2026-07-07 11:44:21'),
(13,5,'خيمة عائلية واسعة لـ 6 أشخاص','Tent VIP','الوصف:\r\n\r\nاستمتع بإقامة مريحة في خيمة واسعة تتسع حتى 6 أشخاص، ومجهزة بفرش عربي تقليدي يوفر أجواءً أصيلة ومريحة، بالإضافة إلى شاشة ذكية، إنترنت مجاني وسريع (Wi-Fi)، مقابس كهربائية للشحن، وسهولة الوصول عبر المصعد.\r\n\r\nالمميزات:\r\n\r\n🎪 خيمة واسعة (تتسع لـ 6 أشخاص)\r\n🛋️ جلسة وفرش عربي تقليدي\r\n📺 شاشة ذكية\r\n🔌 مقابس كهربائية للشحن\r\n🛜 إنترنت مجاني وسريع (Wi-Fi)\r\n🛗 مصعد','A spacious ground tent for 6 guests, equipped with traditional Arabic bedding, a Smart TV, free fast Wi-Fi, and power outlets for charging.','per_night',6,'[\"2026-07-05-6a4aac551c8ff.png\"]',2,'active','2026-07-05 07:20:36','2026-07-25 19:58:41'),
(14,5,'طيرمانة(ملكي)','Public Tent','الوصف:\r\n\r\nمجلس عام واسع بتصميم تراثي يتسع لـ 40 شخصاً، مجهز بفرش عربي متكامل وجلسات مريحة. يحتوي المجلس على شاشة تلفزيون ذكية كبيرة مخصصة لبث المباريات والفعاليات، بالإضافة إلى إنترنت مجاني عالي السرعة (Wi-Fi).\r\n\r\nالمميزات:\r\n\r\n🏛️ مجلس عام واسع بتصميم تراثي (يتسع لـ 40 شخصاً)\r\n🛋️ جلسة وفرش عربي متكامل\r\n📺 شاشة تلفزيون ذكية كبيرة (مخصصة للمباريات والفعاليات)\r\n🛜 إنترنت مجاني عالي السرعة (Wi-Fi)','A spacious public tent and shared lounge that accommodates up to 40 guests, fully furnished with traditional Arabic seating. Equipped with a large Smart TV for live sports broadcasting, free high-speed Wi-Fi, and distributed power outlets.','per_night',40,'[\"2026-07-05-6a4abdf8245b2.png\",\"2026-07-05-6a4abdf82482f.png\",\"2026-07-05-6a4abdf82501b.png\"]',1,'active','2026-07-05 20:46:31','2026-07-25 19:56:56'),
(17,5,'خيمة (طيرمانة) VIP بحجم متوسط','Tent VIP med','الوصف:\r\n\r\nخيمة بطابع تراثي مميز وجلسة أرضية مريحة تتسع لـ 8 أشخاص، تُعد مكاناً مثالياً لجمعة ممتعة. الخيمة مجهزة بشاشة ذكية 50 بوصة لمتابعة مبارياتكم المفضلة وتطبيقات الترفيه مثل اليوتيوب، بالإضافة إلى إنترنت مجاني وسريع (Wi-Fi).\r\n\r\nالمميزات:\r\n\r\n🎪 خيمة بطابع تراثي مميز (تتسع لـ 8 أشخاص)\r\n🛋️ جلسة أرضية مريحة\r\n📺 شاشة ذكية 50 بوصة (يدعم اليوتيوب والمباريات)\r\n🛜 إنترنت مجاني وسريع (Wi-Fi)','A cozy traditional tent featuring comfortable Arabic seating for up to 6 guests. Perfect for social gatherings, fully equipped with a 50-inch Smart TV for your favorite matches and YouTube, high-speed Wi-Fi, and full air conditioning','per_night',8,'[\"2026-07-06-6a4ba3b0d3b53.png\"]',3,'active','2026-07-06 13:46:40','2026-07-25 19:55:49'),
(19,5,'خيمة (طيرمانة) VIP بحجم كبير','Tent VIP large','الوصف:\r\n\r\nخيمة واسعة تتسع لـ 15 شخصاً، مجهزة بفرش عربي تقليدي يمنحك أجواءً تراثية مريحة، ومزودة بشاشة تلفزيون ذكية بالإضافة إلى إنترنت مجاني وسريع (Wi-Fi).\r\n\r\nالمميزات:\r\n\r\n🎪 خيمة واسعة (تتسع لـ 15 شخصاً)\r\n🛋️ جلسة وفرش عربي تقليدي\r\n📺 شاشة تلفزيون ذكية\r\n🛜 إنترنت مجاني وسريع (Wi-Fi)',NULL,'per_night',15,'[\"2026-07-06-6a4baaba359b5.png\",\"2026-07-06-6a4baaba36a30.png\"]',3,'active','2026-07-06 14:16:42','2026-07-25 19:26:07'),
(24,5,'غرفة بسريرين منفصلين وجلسة جانبية','Cozy Twin Room with Seating','الوصف:\r\n\r\nغرفة تحتوي على سريرين منفصلين مجهزين بالكامل، وتضم جلسة جانبية مريحة، ودولاب ملابس واسع، وثلاجة صغيرة، بالإضافة إلى شاشة ذكية، وهاتف أرضي للتواصل مع الخدمة، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان مجهزان بالكامل\r\n🛋️ جلسة جانبية مريحة\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📺 شاشة ذكية\r\n📞 هاتف أرضي للخدمة\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية','A hotel room featuring separate twin beds and a comfortable side seating area for relaxation. Equipped with a Smart TV, a spacious wardrobe, a dedicated high-speed Wi-Fi modem, and a clean private bathroom','per_night',2,'[\"2026-07-07-6a4d659b60ebc.png\",\"2026-07-07-6a4d659b6143e.png\",\"2026-07-07-6a4d659b61727.png\",\"2026-07-07-6a4d659b6191c.png\"]',1,'active','2026-07-07 21:46:19','2026-07-25 19:22:28'),
(25,5,'غرفة عائلي بسرير ماستر وجلسة جانبية','غرفة عائلي بسرير ماستر وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سرير ماستر كبير ومريح، وتضم جلسة جانبية متكاملة للاسترخاء، ودولاب ملابس واسع، وثلاجة صغيرة، بالإضافة إلى شاشة ذكية، وهاتف أرضي للتواصل، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير ماستر كبير ومريح\r\n🛋️ جلسة جانبية متكاملة\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📺 شاشة ذكية\r\n📞 هاتف أرضي للتواصل\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-08-6a4d6f0239b84.png\",\"2026-07-08-6a4d6f0239fc8.png\",\"2026-07-08-6a4d6f023a2f7.png\",\"2026-07-08-6a4d6f023a4f7.png\"]',1,'active','2026-07-07 22:25:12','2026-07-25 19:20:27'),
(26,5,'جناح فندقي راقٍ بغرفة ماستر وصالة مستقلة','جناح فندقي راقٍ بغرفة ماستر وصالة مستقلة','الوصف:\r\n\r\nجناح فندقي متكامل يمنحك تجربة إقامة مريحة وهادئة، يتكون من غرفة نوم رئيسية تحتوي على سرير ماستر كبير، وصالة جلوس مستقلة مجهزة بجلسة شعبية أنيقة وشاشة ذكية. كما يشتمل الجناح على دولاب ملابس واسع، ثلاجة صغيرة، هاتف أرضي، ومودم إنترنت لضمان اتصال سريع ومستمر (Wi-Fi)، بالإضافة إلى دورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم رئيسية (سرير ماستر كبير)\r\n🛋️ صالة جلوس مستقلة (جلسة شعبية أنيقة)\r\n📺 شاشة ذكية\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📞 هاتف أرضي\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',3,'[\"2026-07-08-6a4d7a04a8b8d.png\",\"2026-07-08-6a4d7a04a9108.png\",\"2026-07-08-6a4d7a04a940b.png\"]',1,'active','2026-07-07 23:13:24','2026-07-25 19:19:05'),
(27,5,'غرفة عائلي بسرير ماستر وجلسة جانبية','غرفة عائلي بسرير ماستر وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سرير ماستر كبير ومريح، وتضم جلسة جانبية متكاملة للاسترخاء، ودولاب ملابس واسع، وثلاجة صغيرة، بالإضافة إلى شاشة ذكية، وهاتف أرضي للتواصل، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير ماستر كبير ومريح\r\n🛋️ جلسة جانبية متكاملة\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📺 شاشة ذكية\r\n📞 هاتف أرضي للتواصل\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-08-6a4d7e379141f.png\",\"2026-07-08-6a4d7e3791a0a.png\",\"2026-07-08-6a4d7e3791cab.png\",\"2026-07-08-6a4d7e3791e67.png\"]',1,'active','2026-07-07 23:31:19','2026-07-25 19:12:48'),
(28,5,'غرفة بثلاثة أسرة منفصلة وجلسة جانبية','غرفة بثلاثة أسرة منفصلة وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على ثلاثة أسرة منفصلة مجهزة بالكامل، وتضم جلسة جانبية مريحة، ودولاب ملابس واسع، وثلاجة صغيرة، بالإضافة إلى شاشة ذكية، وهاتف أرضي للتواصل مع الخدمة، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ ثلاثة أسرة منفصلة مجهزة بالكامل\r\n🛋️ جلسة جانبية مريحة\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📺 شاشة ذكية\r\n📞 هاتف أرضي \r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',3,'[\"2026-07-08-6a4d81e128485.png\",\"2026-07-08-6a4d81e128e3b.png\",\"2026-07-08-6a4d81e128fe3.png\",\"2026-07-08-6a4d81e129242.png\"]',1,'active','2026-07-07 23:46:57','2026-07-25 19:10:48'),
(29,5,'شقة فندقية واسعة بغرفتي نوم وصالة مستقلة','شقة فندقية واسعة بغرفتي نوم وصالة مستقلة','الوصف:\r\n\r\nشقة فندقية تتميز بالنظافة والاتساع لتوفير إقامة مريحة. تتكون الشقة من غرفتي نوم؛ غرفة رئيسية تحتوي على سرير ماستر كبير، وغرفة ثانية تحتوي على سريرين منفصلين. تضم الشقة صالة جلوس مستقلة وشاشة ذكية، بالإضافة إلى ثلاجة منفصلة، ومطبخ مجهز بدواليب تخزين علوية وسفلية وحوض غسيل، ودورة مياه واسعة تجمع بين التصميم العربي والأوروبي ومزودة بحوض استحمام (بانيو)، مع توفر إنترنت مجاني (Wi-Fi).\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم رئيسية (سرير ماستر كبير)\r\n🛏️ غرفة نوم ثانية (سريران منفصلان)\r\n🛋️ صالة جلوس مستقلة\r\n📺 شاشة ذكية\r\n🧊 ثلاجة منفصلة\r\n🍳 مطبخ (دواليب علوية وسفلية وحوض غسيل)\r\n🚿 دورة مياه واسعة (حوض استحمام \"بانيو\" + تصميم عربي وأوروبي)\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',1,'[\"2026-07-08-6a4d9fea8b5db.png\",\"2026-07-08-6a4d9fea8bea9.png\",\"2026-07-08-6a4d9fea8c20a.png\",\"2026-07-08-6a4d9fea8c4f3.png\",\"2026-07-08-6a4d9fea8c871.png\",\"2026-07-08-6a4d9fea8cb7d.png\"]',2,'active','2026-07-08 01:55:06','2026-07-25 19:03:51'),
(30,5,'شقة عائلية بغرفتي نوم وصالة مستقلة','شقة عائلية بغرفتي نوم وصالة مستقلة','الوصف:\r\n\r\nشقة فندقية عائلية متكاملة تتميز بالنظافة والاتساع لتوفير إقامة مريحة. تتكون الشقة من غرفتي نوم؛ غرفة رئيسية تحتوي على سرير ماستر مزدوج كبير، وغرفة ثانية تحتوي على سريرين منفصلين. تضم الشقة صالة جلوس مستقلة ومجهزة بطقم كنب كلاسيكي أنيق وشاشة تلفزيون ذكية، بالإضافة إلى ثلاجة منفصلة، ومطبخ مستقل مجهز بدواليب تخزين وحوض غسيل، ودورة مياه واسعة تشتمل على حوض استحمام (بانيو) وتوفر الخيارين العربي والأوروبي، بالإضافة إلى إنترنت مجاني (Wi-Fi).\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم رئيسية (سرير ماستر مزدوج كبير)\r\n🛏️ غرفة نوم ثانية (سريران منفصلان)\r\n🛋️ صالة جلوس مستقلة (طقم كنب كلاسيكي)\r\n📺 شاشة تلفزيون ذكية\r\n🧊 ثلاجة منفصلة\r\n🍳 مطبخ مستقل (دواليب تخزين وحوض غسيل)\r\n🚿 دورة مياه واسعة (حوض استحمام \"بانيو\" + حمام عربي وأوروبي)\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',1,'[\"2026-07-08-6a4eba261a938.png\",\"2026-07-08-6a4eba261b86e.png\",\"2026-07-08-6a4eba261bd94.png\",\"2026-07-08-6a4eba261c48a.png\",\"2026-07-08-6a4eba261cc1b.png\",\"2026-07-25-6a64f9b8547a3.png\"]',1,'active','2026-07-08 21:59:18','2026-07-25 19:00:24'),
(31,6,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسريرين منفصلين بجودة فندقية ممتازة، وتضم جلسة جانبية مريحة للاسترخاء، وتسريحة أنيقة مزودة بمرآة، وشاشة تلفزيون لمتابعة برامجك المفضلة، بالإضافة إلى إنترنت (Wi-Fi) ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان بجودة فندقية ممتازة\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n🪞 تسريحة أنيقة بمرآة\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت (Wi-Fi)',NULL,'per_night',2,'[\"2026-07-10-6a51461975a9e.png\",\"2026-07-10-6a51461975fab.png\"]',4,'active','2026-07-10 20:15:44','2026-07-25 18:54:25'),
(32,6,'غرفة عائلي بسرير ماستر وجلسة جانبية','غرفة عائلي بسرير ماستر وجلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسرير كبير بجودة فندقية ممتازة، وتضم جلسة جانبية مريحة للاسترخاء، وتسريحة أنيقة مزودة بمرآة، وشاشة تلفزيون لمتابعة برامجك المفضلة، بالإضافة إلى إنترنت مجاني (Wi-Fi) ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير كبير بجودة فندقية ممتازة\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n🪞 تسريحة أنيقة بمرآة\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت(Wi-Fi)',NULL,'per_night',2,'[\"2026-07-10-6a51487227b56.png\",\"2026-07-10-6a5148722802d.png\"]',2,'active','2026-07-10 20:30:58','2026-07-25 18:52:11'),
(33,6,'غرفة بثلاثة أسرة منفصلة وجلسة جانبية','غرفة بثلاثة أسرة منفصلة وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على ثلاثة أسرة منفصلة، وتضم جلسة جانبية مريحة، وتسريحة أنيقة مزودة بمرآة، ومجهزة بشاشة ذكية، بالإضافة إلى إنترنت مجاني (Wi-Fi) ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ ثلاثة أسرة منفصلة\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة ذكية\r\n🪞 تسريحة أنيقة بمرآة\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',2,'[\"2026-07-10-6a514b9fa3e64.png\",\"2026-07-10-6a514b9fa4400.png\"]',1,'active','2026-07-10 20:44:31','2026-07-25 18:12:18'),
(34,7,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسريرين منفصلين بجودة فندقية، وتضم جلسة جانبية مريحة للاسترخاء، وشاشة تلفزيون لمتابعة برامجك المفضلة، بالإضافة إلى إنترنت مجاني (Wi-Fi) ودورة مياه خاصة بكافة المستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية مريح\r\n📺 شاشة تلفزيون\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت (Wi-Fi)',NULL,'per_night',1,'[\"2026-07-14-6a562a17ca245.png\",\"2026-07-14-6a562a17cab0a.png\"]',10,'active','2026-07-13 21:27:03','2026-07-25 17:35:38'),
(35,7,'غرفة عائلي بسرير ماستر وجلسة جانبية','غرفة عائلي بسرير ماستر وجلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسرير كبير، وتضم جلسة جانبية مريحة للاسترخاء، وتسريحة أنيقة مزودة بمرآة، وشاشة تلفزيون لمتابعة برامجك المفضلة، بالإضافة إلى إنترنت مجاني (Wi-Fi) ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير كبير (ماستر)\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n🪞 تسريحة أنيقة بمرآة\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',4,'[\"2026-07-14-6a568f9ccefac.png\",\"2026-07-14-6a568f9ccf546.png\"]',4,'active','2026-07-14 19:48:59','2026-07-25 17:34:24'),
(36,7,'غرفة بثلاثة أسرة منفصلة وجلسة جانبية','غرفة بثلاثة أسرة منفصلة وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على ثلاثة أسرة منفصلة، وتضم جلسة جانبية مريحة، وتسريحة أنيقة مزودة بمرآة، ومجهزة بشاشة ذكية، بالإضافة إلى إنترنت مجاني (Wi-Fi) ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ ثلاثة أسرة منفصلة\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة ذكية\r\n🪞 تسريحة أنيقة بمرآة\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',2,'[\"2026-07-14-6a5686517c8f7.png\",\"2026-07-14-6a5686517d215.png\",\"2026-07-14-6a5686517d49c.png\"]',7,'active','2026-07-14 19:56:17','2026-07-25 17:31:52'),
(37,8,'غرفة بثلاثة أسرة منفصلة وجلسة جانبية','غرفة بثلاثة أسرة منفصلة وجلسة جانبية','الوصف:\r\n\r\nغرفة بثلاثة أسرة منفصلة، وتضم جلسة جانبية مريحة، وتسريحة أنيقة مزودة بمراية، بالإضافة إلى شاشة ذكية.ودولاب ملابس, كما تحتوي الغرفة على دورة مياه بحميع مسلتزماتها.\r\n\r\nالمميزات:\r\n\r\n🛏️ ثلاثة أسرة منفصلة\r\n🛋️ جلسة جانبية\r\n📺 شاشة ذكية\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة ودولاب ملابس\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-15-6a574e14d6b8e.png\",\"2026-07-21-6a5f21cbb779c.png\"]',1,'active','2026-07-15 10:08:36','2026-07-25 17:30:07'),
(38,8,'جناح بغرفة سرير ماستر وصالة مستقلة','جناح بغرفة سرير ماستر وصالة مستقلة','الوصف:\r\nجناح فندقي متكامل يمنحك تجربة إقامة مريحة وهادئة، يتكون من غرفة نوم رئيسية تحتوي على سرير ماستر كبير، وصالة جلوس مجهزة بشاشة ذكية. الجناح مزود بدولاب ملابس واسع، وهاتف أرضي، ومودم إنترنت مجاني (Wi-Fi)، بالإضافة إلى دورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم (سرير ماستر كبير)\r\n🛋️ صالة جلوس\r\n📺 شاشة ذكية\r\n🚪 دولاب ملابس واسع\r\n📞 هاتف أرضي\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',2,'[\"2026-07-15-6a574f87d63f7.png\",\"2026-07-21-6a5f8ae304de9.png\",\"2026-07-21-6a5f8ae305284.png\"]',1,'active','2026-07-15 10:14:47','2026-07-25 17:28:47'),
(39,8,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسريرين منفصلين بجودة فندقية جيدة. تضم الغرفة جلسة جانبية مريحة للاسترخاء، بالإضافة إلى شاشة تلفزيون لمتابعة برامجك المفضلة.دولاب ملابس, كما تحتوي الغرفة على دورة مياه بكافة المستلزمات.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🚪 دولاب ملابس\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n🛁 دورة مياه خاصة بمستلزماتها\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-15-6a5751c89f3cc.png\",\"2026-07-15-6a5751c89fb28.png\"]',1,'active','2026-07-15 10:24:24','2026-07-24 20:10:20'),
(40,9,'جناح من ثلاث غرف نوم مع جلسةجانبية','جناح من ثلاث غرف نوم مع جلسةجانبية','الوصف:\r\nجناح واسع ومريح يتكون من 3 غرف نوم (غرفة بسرير كبير، غرفة بسريرين منفصلين، وغرفة بسرير فردي). تتميز كل غرفة بوجود شاشة ذكية مستقلة، وجلسة جانبية مريحة، بالإضافة إلى إنترنت مجاني (Wi-Fi) ودورة مياه مجهزة بالكامل بكافة المستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ 3 غرف نوم (سرير كبير + سريران منفصلان + سرير فردي)\r\n🛋️ جلسات جانبية مريحة\r\n📺 شاشة ذكية مستقلة لكل غرفة\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',2,'[\"2026-07-15-6a577f4bd42fa.png\",\"2026-07-15-6a577f4bd4c00.png\",\"2026-07-15-6a577f4bd4f1a.png\",\"2026-07-15-6a577f4bd508d.png\"]',1,'active','2026-07-15 13:38:35','2026-07-25 17:25:01'),
(41,9,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nغرفة مريحة تحتوي على سريرين منفصلين وجلسةجانبية بنقوش مميزة. الغرفة تحتوي  على شاشة ذكية  وإنترنت مجاني (Wi-Fi)، بالإضافة إلى دورة مياه خاصة تحتوي على كافة المستلزمات الأساسية لضمان إقامة مريحة.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية \r\n📺 شاشة ذكية\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',2,'[\"2026-07-16-6a5930a51b507.png\",\"2026-07-16-6a5930a51d7e4.png\",\"2026-07-16-6a5930a51da77.png\"]',1,'active','2026-07-16 20:27:33','2026-07-25 17:22:35'),
(42,9,'غرفة بسرير ماستر(كبير) وجلسة جانبية','غرفة بسرير ماستر(كبير) وجلسة جانبية','الوصف:\r\nغرفة مريحة تحتوي على سرير كبير مزدوج وجلسة جانبية متميزة. الغرفة مجهزة بإنترنت مجاني (Wi-Fi)، شاشة ذكية، بالإضافة إلى دورة مياه. \r\n\r\nالمميزات:\r\n\r\n🛏️ سرير كبير (ماستر) مريح\r\n🛋️ جلسة أرضية جانبية لشخصين\r\n🛜 واي فاي (Wi-Fi) مجاني وسريع\r\n📺 شاشة ذكية مستقلة\r\n🚿 دورة مياه خاصة \r\n🔌 مقابس كهربائية للشحن',NULL,'per_night',2,'[\"2026-07-16-6a59398ac33d7.png\",\"2026-07-16-6a59398ac3952.png\",\"2026-07-16-6a59398ac3c1d.png\"]',1,'active','2026-07-16 21:05:30','2026-07-25 17:20:04'),
(43,6,'جناح من غرفتي نوم وجلسة جانبية','جناح من غرفتي نوم وجلسة جانبية','الوصف:\r\n\r\nجناح يتكون من غرفتي نوم، إحداهما تضم سريرًا كبيرًا والثانية سريرين منفصلين. يحتوي الجناح على جلسة جانبية مريحة، ومجهز بشاشة ذكية مع اتصال إنترنت (Wi-Fi)، بالإضافة إلى دورة مياه خاصة تتوفر بها كافة المستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفتا نوم (غرفة بسرير كبير + غرفة بسريرين منفصلين)\r\n🛋️ جلسة جانبية\r\n📺 شاشة ذكية\r\n🛜 واي فاي\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-19-6a5d357339b50.png\",\"2026-07-19-6a5d35733a2c4.png\",\"2026-07-19-6a5d35733a528.png\"]',1,'active','2026-07-19 21:37:07','2026-07-19 21:47:00'),
(44,6,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nغرفة مريحة تحتوي على سريرين منفصلين وجلسة جانبية بنقوش مميزة. الغرفة تحتوي  على شاشة ذكية  وإنترنت مجاني (Wi-Fi)، بالإضافة إلى دورة مياه.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية \r\n📺 شاشة ذكية\r\n🛜 واي فاي\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-19-6a5d3725d896b.png\",\"2026-07-19-6a5d3725d9210.png\"]',1,'active','2026-07-19 21:44:21','2026-07-19 21:47:53'),
(45,6,'غرفة عائلي بسرير ماستر وجلسة جانبية','غرفة عائلي بسرير ماستر وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سريرًا كبيرًا وجلسة جانبية. الغرفة مجهزة بشاشة ذكية و انتر نت، مرآة وطاولات جانبية، بالإضافة إلى دورة مياه خاصة تتوفر بها كافة المستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير كبير (ماستر)\r\n🛋️ جلسة جانبية لشخصين\r\n📺 شاشة ذكية \r\n🛜 انتر نت \r\n🪞 مرآة وطاولة جانبية \r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-20-6a5e68942f9ff.png\",\"2026-07-20-6a5e689430348.png\"]',1,'active','2026-07-20 19:27:32','2026-07-20 19:32:11'),
(46,6,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سريرين منفصلين وجلسة جانبية مريحة. الغرفة يوجد فيها شاشة ذكية وطاولة جانبية، و إنترنت (Wi-Fi) ودورة مياه خاصة توفر لك المستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريرين منفصلاين\r\n🛋️ جلسة جانبية\r\n📺 شاشة ذكية \r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-20-6a5e6a5b009d9.png\",\"2026-07-20-6a5e6a5b0110f.png\"]',1,'active','2026-07-20 19:35:07','2026-07-25 16:37:52'),
(47,6,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','غرفة تحتوي على سريرين منفصلين وجلسة جانبية، ومجهزة بشاشة ذكية وطاولة جانبية، بالإضافة انترنت (Wi-Fi) ودورة مياه خاصة بالمستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية مع طاولة\r\n📺 شاشة ذكية\r\n🛜 إنترنت (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-20-6a5e6c9ee98aa.png\",\"2026-07-20-6a5e6c9eea029.png\"]',1,'active','2026-07-20 19:44:46','2026-07-28 21:37:36'),
(48,8,'جناح من غرفتي نوم بجلسة جانبية','جناح من غرفتي نوم بجلسة جانبية','الوصف:\r\n\r\nجناح يتكون من غرفتي نوم، إحداهما بسرير كبير والأخرى بسريرين منفصلين، مع جلسة جانبية. يتوفر في الجناح شاشتان ذكيتان (شاشة في كل غرفة)، خزانة ملابس، تسريحة بمرآة، إنترنت (Wi-Fi)، ودورة مياه خاصة بالمستلزمات الأساسية، و مصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفتا نوم (سرير كبير + سريران منفصلان)\r\n🛋️ جلسة جانبية\r\n📺 شاشتان ذكيتان (شاشة لكل غرفة)\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة وخزانة ملابس\r\n🛗 مصعد بالفندق\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-20-6a5e787c16db3.png\",\"2026-07-20-6a5e787c1734e.png\",\"2026-07-20-6a5e787c175e9.png\",\"2026-07-20-6a5e787c178ad.png\",\"2026-07-20-6a5e787c17bf5.png\"]',1,'active','2026-07-20 20:35:24','2026-07-20 20:35:24'),
(49,8,'جناح من غرفة نوم وصالة بمجلس','جناح من غرفة نوم وصالة بمجلس','الوصف:\r\n\r\nجناح يتكون من غرفة نوم تحتوي على سرير كبير وسرير صغير، وصالة مستقلة تضم مجلسًا. يحتوي الجناح على شاشة ذكية، دولاب ملابس، تسريحة بمرآة، و انتر نت ودورة مياه خاصة بالمستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم (سرير كبير + سرير صغير)\r\n🛋️ صالة بمجلس\r\n📺 شاشة ذكية\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة ودولاب ملابس\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-21-6a5f2782eaced.png\",\"2026-07-21-6a5f2782eb2a3.png\",\"2026-07-21-6a5f2782eb406.png\",\"2026-07-21-6a5f2782eb67b.png\"]',1,'active','2026-07-21 09:02:10','2026-07-21 09:02:10'),
(50,8,'جناح من غرفة نوم وصالة بمجلس','جناح من غرفة نوم وصالة بمجلس','الوصف:\r\n\r\nجناح يتكون من غرفة نوم تحتوي على سرير كبير، وصالة تضم مجلسًا، يحتوي  على شاشة ذكية، دولاب ملابس، تسريحة ، إنترنت (Wi-Fi) ودورة مياه خاصة بالمستلزمات الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم بسرير كبير\r\n🛋️ صالة بمجلس\r\n📺 شاشة ذكية\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة ودولاب ملابس\r\n🚿 دورة مياه خاصة بالمستلزمات\r\n🛗 مصعد',NULL,'per_night',2,'[\"2026-07-21-6a5f8c4c9392a.png\",\"2026-07-21-6a5f8c4c9417d.png\",\"2026-07-21-6a5f8c4c9452b.png\",\"2026-07-21-6a5f8c4c9497c.png\"]',1,'active','2026-07-21 16:00:28','2026-07-21 16:12:12'),
(51,8,'غرفة بثلاثة أسرة منفصلة','غرفة بثلاثة أسرة منفصلة','الوصف:\r\n\r\nغرفة تحتوي على ثلاثة أسرة منفصلة، ومجهزة بشاشة ذكية، دولاب ملابس، تسريحة ،  إنترنت (Wi-Fi) ودورة مياه خاصة بالمستلزمات الأساسية، مع توفر مصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ ثلاثة أسرة منفصلة\r\n📺 شاشة ذكية\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة ودولاب ملابس\r\n🛗 مصعد بالفندق\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-21-6a5f95033284e.png\",\"2026-07-21-6a5f95033338c.png\"]',1,'active','2026-07-21 16:49:23','2026-07-21 16:49:23'),
(52,8,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سريرين منفصلين وجلسة جانبية، ومجهزة بشاشة ذكية، دولاب ملابس، تسريحة، إنترنت (Wi-Fi) ودورة مياه خاصة بالمستلزمات الأساسية، مع توفر مصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية (أريكة)\r\n📺 شاشة ذكية\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة ودولاب ملابس\r\n🛗 مصعد بالفندق\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-21-6a5f95ec52ba9.png\",\"2026-07-21-6a5f95ec53161.png\"]',1,'active','2026-07-21 16:53:16','2026-07-21 16:53:16'),
(53,8,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سريرين منفصلين وجلسة جانبية، و شاشة، دولاب ملابس، تسريحة ، إنترنت (Wi-Fi) ودورة مياه خاصة بالمستلزمات الأساسية، مع توفر مصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية (أريكة)\r\n📺 شاشة ذكية\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة ودولاب ملابس\r\n🛗 مصعد بالفندق\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-21-6a5fa404cc1f8.png\",\"2026-07-21-6a5fa404cc78a.png\"]',1,'active','2026-07-21 17:53:24','2026-07-21 17:53:24'),
(54,8,'غرفة بسريرين منفصلين وجلسة جانبية','غرفة بسريرين منفصلين وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سريرين منفصلين وجلسة جانبية، و شاشة، دولاب ملابس، تسريحة ، إنترنت (Wi-Fi) ودورة مياه خاصة بالمستلزمات الأساسية، مع توفر مصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية (أريكة)\r\n📺 شاشة ذكية\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🪞 تسريحة بمرآة ودولاب ملابس\r\n🛗 مصعد بالفندق\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-21-6a5fa40ae741e.png\",\"2026-07-21-6a5fa40ae7a12.png\"]',1,'active','2026-07-21 17:53:30','2026-07-21 17:53:30'),
(58,15,'الجناح غرفتان ومجلس وصالة طعام 402','Suite 402','الجناح العائلي 402\r\n\r\n**الوصف:**\r\n\r\nجناح عائلي فاخر صُمم بعناية ليوفر إقامة مريحة تناسب العائلات والمجموعات. يضم الجناح **غرفتي نوم**، بالإضافة إلى **صالة جلوس مستقلة** توفر مساحة مثالية للاسترخاء، و**مجلس عربي مستقل** لاستقبال الضيوف والتجمعات العائلية، إلى جانب **منطقة طعام** مخصصة لتناول الوجبات. كما يحتوي الجناح على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفتا نوم\r\n* 🛋️ صالة جلوس مستقلة\r\n* 🏛️ مجلس عربي مستقل\r\n* 🍽️ منطقة طعام\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* ✨ تصميم عصري بأثاث فاخر وإضاءة LED','**Suite 402 – Luxury Family Suite**\r\n\r\nA spacious luxury family suite featuring **two bedrooms**, a **comfortable living room**, a **separate Arabic-style majlis**, and a **dining area**. Designed with modern décor, elegant furnishings, stylish LED lighting, and premium hotel-quality bedding, it offers a peaceful and comfortable stay, making it the perfect choice for families and groups.','per_night',6,'[\"2026-07-23-6a6189d09ec90.png\",\"2026-07-23-6a6189d09f4e1.png\",\"2026-07-23-6a6189d09f7b7.png\",\"2026-07-23-6a6189d09fec1.png\",\"2026-07-23-6a6189d0a014a.png\",\"2026-07-23-6a6189d0a0709.png\"]',1,'active','2026-07-23 04:26:08','2026-07-26 00:38:59'),
(59,15,'الجناح غرفتين ومجلس 202','Suite 202','.### الجناح الملكي 602\r\n\r\n**الوصف:**\r\n\r\nجناح ملكي صُمم بعناية ليجمع بين الفخامة والراحة والخصوصية، ويوفر تجربة إقامة راقية تناسب العرسان والعائلات الصغيرة. يضم الجناح **غرفة رئيسية بسرير كينغ سايز مريح**، و**غرفة ثانية تحتوي على سريرين منفصلين**، بالإضافة إلى **مجلس عربي مستقل** يوفر مساحة مثالية للاسترخاء واستقبال الضيوف. كما يحتوي الجناح على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة فاخرة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفة رئيسية بسرير كينغ سايز\r\n* 🛏️ غرفة ثانية بسريرين منفصلين\r\n* 🏛️ مجلس عربي\r\n* 👗 دولاب ملابس واسع\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* ✨ تصميم عصري بإضاءة LED هادئة\r\n* 💖 مثالي للعرسان والعائلات الصغيرة الباحثين عن إقامة راقية ومريحة','**Family Suite 202**\r\nEnjoy a comfortable and relaxing stay in **Family Suite 202**, thoughtfully designed to provide a refined atmosphere for families and groups. The suite features spacious interiors that combine elegance with comfort, including a separate living room furnished with stylish, high-quality furniture and cozy bedrooms that ensure a restful and enjoyable stay.\r\n\r\nThe suite includes a **master bedroom with a king-size bed**, a **second bedroom with two single beds**, and a **spacious living area**, making it an ideal choice for families or guests seeking privacy, comfort, and a home-like experience.','per_night',6,'[\"2026-07-26-6a65481ba9870.png\",\"2026-07-26-6a65481baa1ad.png\",\"2026-07-26-6a65481baa7b1.png\",\"2026-07-26-6a65481baae9d.png\",\"2026-07-26-6a65481bab4a2.png\"]',1,'active','2026-07-23 04:46:05','2026-07-26 00:34:51'),
(60,16,'جناح من غرفة نوم بسرير مزدوج وصالة','جناح من غرفة نوم بسرير مزدوج وصالة','الوصف:\r\n\r\nجناح يتكون من غرفة نوم تحتوي على سرير مزدوج كبير خشبي مع كومودينو جانبي وأباجورة، \r\nوصالة تضم أريكة جلدية بلون ذهبي، مرآة طولية متحركة، وعلاقة ملابس.ودولاب ملابس \r\n كما يضم الجناح ركن مغسلة خارجية وادراج خشبية، ودورة مياه خاصة تحتوي على كابينة دش زجاجية،\r\n مرحاض فرنجي، حامل مناشف، مع توفر إنترنت مجاني  (Wi-Fi) ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم بسرير مزدوج كبير (خشبي)\r\n🚪 دولاب ملابس\r\n🛋️ أريكة جلدية جانبية\r\n🪞 مرآة طولية متحركة\r\n👔 علاقة ملابس خشبية\r\n🚰 ركن مغسلة خارجية بخزائن خشبية\r\n🚿 دورة مياه خاصة بكابينة دش زجاجية\r\n مرحاض فرنجي،\r\n🧺 حامل مناشف \r\n🛜 انترنت(Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-23-6a62102de00e9.png\",\"2026-07-23-6a62102de08f0.png\",\"2026-07-23-6a62102de0b93.png\",\"2026-07-23-6a62102de0ebb.png\",\"2026-07-23-6a62102de11f8.png\"]',1,'active','2026-07-23 13:59:25','2026-07-23 14:02:10'),
(61,16,'غرفة بسرير مزدوج وجلسة جانبية','غرفة بسرير مزدوج وجلسة جانبية','الوصف:\r\n\r\nغرفة تحتوي على سرير مزدوج كبير خشبي مع كومودينو جانبي، أريكة جلدية بلون ذهبي مع طاولة شاي زجاجية، دولاب ملابس بسحاب، مكتب خشبي مع شاشة ذكية ومروحة جدارية، وعلاقة ملابس. كما تضم ركن مغسلة خارجية بخزائن خشبية، ودورة مياه خاصة تحتوي على مرحاض فرنجي، شطاف، ومغسلة رخامية بمرآة، مع توفر خدمة إنترنت (Wi-Fi) ومصعد بالفندق.\r\n\r\nوالمميزات:\r\n\r\n🛏️ سرير مزدوج كبير (خشبي)\r\n🛋️ أريكة جلدية جانبية مع طاولة زجاجية\r\n🚪 دولاب ملابس أبواب سحاب\r\n📺 شاشة ذكية ومكتب خشبي\r\n🌀 مروحة جدارية\r\n👔 علاقة ملابس خشبية\r\n🚰 ركن مغسلة خارجية بخزائن خشبية\r\n🚿 دورة مياه خاصة بمرحاض فرنجي، ومغسلة رخامية بمرآة\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-23-6a6276a6bb2d2.png\",\"2026-07-23-6a6276a6bb780.png\",\"2026-07-23-6a6276a6bba6c.png\"]',1,'active','2026-07-23 16:21:04','2026-07-23 21:16:38'),
(62,16,'غرفة واسعة بسرير مزدوج وجلسة جانبية','غرفة واسعة بسرير مزدوج وجلسة جانبية','الوصف:\r\n\r\nغرفة واسعة تحتوي على سرير مزدوج كبير خشبي مع كومودينو جانبي، تسريحة بمرآة وكرسي، \r\nدولاب ملابس كبير، وثلاجة صغيرة، بالإضافة إلى أريكة جلسة جانبية مريحة مع طاولة، مكتب خشبي بشاشة ذكية، ودورة مياه خاصة مجهزة بمرحاض فرنجي ودش علوي ومغسلة، مع توفر خدمة إنترنت (Wi-Fi) ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير مزدوج كبير (خشبي)\r\n🚪 دولاب ملابس كبير\r\n🧊 ثلاجة صغيرة (ميني بار)\r\n🪞 تسريحة بمرآة وكرسي\r\n🛋️ جلسة جانبية (أريكة وطاولة)\r\n📺 شاشة ذكية ومكتب خشبي\r\n🚿 دورة مياه خاصة بمرحاض فرنجي ودش علوي ومغسلة\r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',1,'[\"2026-07-23-6a623390be9b9.png\",\"2026-07-23-6a623390bef1b.png\",\"2026-07-23-6a623390bf264.png\",\"2026-07-23-6a623390bf4d2.png\",\"2026-07-23-6a623390bf782.png\"]',1,'active','2026-07-23 16:30:24','2026-07-23 16:30:24'),
(63,16,'غرفة واسعة بسريرين منفصلين وجلسة جانبيّة','غرفة واسعة بسريرين منفصلين وجلسة جانبيّة','الوصف:\r\n\r\nغرفة واسعة تحتوي على سريرين منفصلين مع كومودينو جانبي، دولاب ملابس كبير من 4 أبواب، ثلاجة صغيرة، مرآة طولية متحركة، أريكتين جلسة جانبية مع طاولة وسجل، مكتب خشبي، شاشة ذكية معلقة، ومروحة جدارية. كما تضم دورة مياه خاصة مجهزة بحوض استحمام (بانيو)، دش علوي، مرحاض فرنجي، ومغسلة مع مرآة، مع توفر خدمة إنترنت مجاني (Wi-Fi) ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🚪 دولاب ملابس خشبي كبير (4 أبواب)\r\n🧊 ثلاجة صغيرة (ميني بار)\r\n🪞 مرآة طولية متحركة\r\n🛋️ أريكتان للجلسة الجانبية مع طاولة\r\n📺 شاشة ذكية معلقة ومكتب خشبي\r\n🌀 مروحة جدارية\r\n🛁 دورة مياه خاصة بحوض استحمام (بانيو)، \r\nودش علوي، مرحاض \r\n🛜 اتصال إنترنت (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-23-6a6261e9c96d9.png\",\"2026-07-23-6a6261e9c9f4a.png\",\"2026-07-23-6a6261e9ca26b.png\",\"2026-07-23-6a6261e9ca5e3.png\",\"2026-07-23-6a6261e9ca931.png\"]',1,'active','2026-07-23 16:35:08','2026-07-23 19:48:09'),
(64,15,'الجناح غرفتان ومجلس وصالة طعام 506','Suite 506','الجناح العائلي 506\r\n\r\n**الوصف:**\r\n\r\nجناح عائلي واسع صُمم بعناية ليوفر إقامة مريحة تناسب العائلات والمجموعات. يضم الجناح **غرفتي نوم**، بالإضافة إلى **صالة جلوس مستقلة** توفر مساحة مثالية للاسترخاء، و**منطقة طعام** مخصصة لتناول الوجبات، إلى جانب **شرفة خاصة بإطلالة على المدينة** تمنح الضيوف أجواءً هادئة وإطلالة مميزة. كما يحتوي الجناح على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفتا نوم\r\n* 🛋️ صالة جلوس مستقلة\r\n* 🍽️ منطقة طعام\r\n* 🌅 شرفة خاصة بإطلالة على المدينة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* ✨ أثاث فاخر ومفروشات فندقية\r\n* 💖 مناسب للعائلات والمجموعات','# **Family Suite 506**\r\n\r\nEnjoy a luxurious and comfortable stay in **Family Suite 506**, thoughtfully designed to provide the perfect accommodation for families and groups. The suite features **two spacious bedrooms**, a cozy living room, a separate dining area, and a **private balcony overlooking the city**, creating a relaxing and memorable stay.\r\n\r\nElegantly furnished with modern décor, LED lighting, and premium hotel furnishings, the suite is equipped with flat-screen TVs, air conditioning, high-speed Wi-Fi, and essential amenities to ensure maximum comfort throughout your stay.\r\n\r\n## **Key Features**\r\n\r\n* Two spacious bedrooms\r\n* Comfortable living room\r\n* Separate dining area\r\n* Private balcony with city views\r\n* Flat-screen TVs\r\n* Refrigerator and service area\r\n* Premium furniture and hotel-quality furnishings\r\n* Modern design with LED lighting\r\n* Air conditioning and complimentary Wi-Fi\r\n* Ideal for families and groups\r\n* Peaceful atmosphere with enhanced privacy\r\n\r\n**\"Spacious Comfort, Exceptional Luxury, and an Unforgettable Family Stay.\"**','per_night',6,'[\"2026-07-23-6a62641718b07.png\",\"2026-07-23-6a62641718fe0.png\",\"2026-07-23-6a6264171919a.png\",\"2026-07-23-6a626417193cc.png\",\"2026-07-23-6a62641719595.png\",\"2026-07-23-6a626417197ad.png\",\"2026-07-23-6a626417199b9.png\"]',1,'active','2026-07-23 19:57:27','2026-07-26 00:24:01'),
(65,15,'الجناح العائلي الكبير بغرفتي نوم وصالة جلوس ومجلس502','Large Family Suite with Two Bedrooms, Living Room, and Arabic Majlis502','الجناح العائلي الكبير بغرفتي نوم وصالة جلوس ومجلس\r\n\r\n**الوصف:**\r\n\r\nجناح عائلي كبير مكوّن من **غرفتي نوم**، صُمم بعناية ليوفر إقامة مريحة تناسب العائلات والمجموعات. يضم الجناح **غرفة رئيسية بسرير كينغ سايز مريح**، وغرفة ثانية تحتوي على **سريرين منفصلين**، بالإضافة إلى **صالة جلوس مستقلة** للاسترخاء، و**مجلس عربي واسع** يوفر مساحة مثالية للتجمعات العائلية واستقبال الضيوف. كما يحتوي الجناح على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة وهادئة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفة رئيسية بسرير كينغ سايز\r\n* 🛏️ غرفة ثانية بسريرين منفصلين\r\n* 🛋️ صالة جلوس مستقلة\r\n* 🏛️ مجلس عربي واسع\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Large Family Suite with Two Bedrooms, Living Room, and Arabic Majlis\r\n\r\n**Description:**\r\n\r\nA spacious family suite featuring **two bedrooms**, thoughtfully designed to provide a comfortable stay for families and groups. The suite includes a **master bedroom with a comfortable king-size bed**, and a **second bedroom with two separate beds**. It also features a **separate living room** for relaxation and an **Arabic majlis** that offers a welcoming space for family gatherings and entertaining guests.\r\n\r\nThe suite includes a **private bathroom** equipped with all essential amenities and provides a range of in-room facilities, including a **smart TV, mini refrigerator, wardrobe, landline telephone, and a private Wi-Fi modem**, ensuring a comfortable and enjoyable stay.\r\n\r\n### Features\r\n\r\n* 🛏️ Master bedroom with a king-size bed\r\n* 🛏️ Second bedroom with two separate beds\r\n* 🛋️ Separate living room\r\n* 🏛️ Spacious Arabic majlis\r\n* 👗 Wardrobe\r\n* 🧊 Mini refrigerator\r\n* 📺 Smart TV\r\n* 📞 Landline telephone for guest services\r\n* 🛜 Private Wi-Fi modem\r\n* 🚿 Private bathroom with essential amenities','per_night',6,'[\"2026-07-23-6a626a8203d79.png\",\"2026-07-23-6a626a82041c8.png\",\"2026-07-23-6a626a8204381.png\",\"2026-07-23-6a626a82044d3.png\",\"2026-07-23-6a626a8204656.png\",\"2026-07-23-6a626a820479a.png\"]',1,'active','2026-07-23 20:24:50','2026-07-25 21:15:34'),
(66,16,'جناح عائلي غرفتين وصالة كبيرة','جناح عائلي غرفتين وصالة كبيرة','الوصف:\r\n\r\nجناح عائلي يضم غرفة نوم رئيسية بسرير مزدوج مع تسريحة ودولاب ملابس كبير وخزنة آمنة، وغرفة ثانية تحتوي على سريرين منفصلين. كما يحتوي الجناح على صالة مع كنب وشاشة ذكية وطاولة طعام بـ 6 كراسي، ومطبخ مستقل مجهز بثلاجة كبيرة وفرن وميكروويف، ودورة مياه مجهزة بحوض استحمام (بانيو)، مع توفر خدمة إنترنت مجاني  ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم رئيسية بسرير مزدوج\r\n🛏️ غرفة نوم ثانية بسريرين منفصلين\r\n🚪 دولاب ملابس كبير وتسريحة \r\n🔒 خزنة آمنة\r\n🛋️ صالة مع كنب وشاشة تلفزيون\r\n🍽️ طاولة طعام بـ 6 كراسي\r\n🍳 مطبخ (ثلاجة كبيرة، فرن، ميكروويف)\r\n🛁 دورة مياه بحوض استحمام (بانيو) ومرحاض فرنجي\r\n🛜 إنترنت (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-23-6a6275989c521.png\",\"2026-07-23-6a6275989ca32.png\",\"2026-07-23-6a6275989cc6d.png\",\"2026-07-23-6a6275989cece.png\",\"2026-07-23-6a6275989d16d.png\",\"2026-07-23-6a6275989d404.png\",\"2026-07-23-6a6275989d627.png\",\"2026-07-23-6a6275989d7ff.png\",\"2026-07-23-6a6275989da43.png\",\"2026-07-23-6a6275989dc7f.png\",\"2026-07-23-6a6275989ded2.png\"]',1,'active','2026-07-23 21:07:14','2026-07-23 21:13:44'),
(67,16,'جناح صغير غرفة نوم ومجلس','جناح صغير غرفة نوم ومجلس','الوصف:\r\n\r\nجناح صغير يضم غرفة نوم بسريركبير مع دولاب ملابس خشبي وكمودينو جانبي، ومكتب خشبي مع علاقة ملابس، ومجلس مع طاولة وشاشة، ودورة مياه خاصة بحوض استحمام (بانيو) ومرحاض فرنجي ، مع إنترنت مجاني  (Wi-Fi) ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم ماستر (سرير مزدوج ودولاب)\r\n🛋️ مجلس مع شاشة تلفزيون\r\n💼 مكتب عمل وشماعة ملابس\r\n🛁 دورة مياه (بانيو)\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-23-6a6279323bfec.png\",\"2026-07-23-6a6279323c51e.png\",\"2026-07-23-6a627b9362887.png\",\"2026-07-23-6a627b9362a3a.png\",\"2026-07-23-6a627b9362c8d.png\"]',1,'active','2026-07-23 21:27:30','2026-07-24 19:47:10'),
(68,15,'جناح عائلي غرفتين بجلسات جانبية وحمام( 206)','**Family Suite with Two Bedrooms, Side Seating Areas, and One Bathroom (206).','جناح عائلي بغرفتي نوم وجلسات جانبية\r\n\r\n**الوصف:**\r\n\r\nجناح عائلي مكوّن من **غرفتي نوم**، صُمم بعناية ليوفر إقامة مريحة تناسب العائلات والمجموعات الصغيرة. يضم الجناح **غرفة رئيسية بسرير كينغ سايز مريح مع جلسة جانبية**، وغرفة ثانية تحتوي على **سريرين منفصلين مع جلسة جانبية**، بالإضافة إلى **صالة جلوس مستقلة** توفر مساحة إضافية للاسترخاء والتجمع. كما يحتوي الجناح على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة وهادئة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفة رئيسية بسرير كينغ سايز\r\n* 🛏️ غرفة ثانية بسريرين منفصلين\r\n* 🛋️ جلسة جانبية في الغرفة الرئيسية\r\n* 🛋️ جلسة جانبية في الغرفة الثانية\r\n* 🛋️ صالة جلوس مستقلة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Family Suite with Two Bedrooms and Side Seating Areas\r\n\r\n**Description:**\r\n\r\nA family suite featuring **two bedrooms**, thoughtfully designed to provide a comfortable stay for families and small groups. The suite includes a **master bedroom with a comfortable king-size bed and a cozy side seating area**, and a **second bedroom with two separate beds and its own side seating area**. It also offers a **separate living room** that provides additional space for relaxation and gathering.\r\n\r\nThe suite features a **private bathroom** equipped with all essential amenities and includes a range of in-room facilities such as a **smart TV, mini refrigerator, wardrobe, landline telephone, and a private Wi-Fi modem**, ensuring a comfortable and enjoyable stay.\r\n\r\n### Features\r\n\r\n* 🛏️ Master bedroom with a king-size bed\r\n* 🛏️ Second bedroom with two separate beds\r\n* 🛋️ Side seating area in the master bedroom\r\n* 🛋️ Side seating area in the second bedroom\r\n* 🛋️ Separate living room\r\n* 👗 Wardrobe\r\n* 🧊 Mini refrigerator\r\n* 📺 Smart TV\r\n* 📞 Landline telephone for guest services\r\n* 🛜 Private Wi-Fi modem\r\n* 🚿 Private bathroom with essential amenities','per_night',4,'[\"2026-07-23-6a627babd7e9a.png\",\"2026-07-23-6a627babd8691.png\",\"2026-07-23-6a627babd8b0f.png\",\"2026-07-23-6a627babd9055.png\",\"2026-07-23-6a627babd95af.png\"]',1,'active','2026-07-23 21:38:03','2026-07-25 21:01:41'),
(69,15,'جناح عائلي بغرفتي نوم وشرفة خاصة (406)','Family Suite with Two Bedrooms and a Private Balcony','جناح عائلي بغرفتي نوم وشرفة خاصة\r\n\r\n**الوصف:**\r\n\r\nجناح عائلي مكوّن من **غرفتي نوم**، صُمم بعناية ليوفر إقامة مريحة تناسب العائلات. يضم الجناح **غرفة رئيسية بسرير كينغ سايز مريح مع جلسة جانبية**، وغرفة ثانية تحتوي على **سريرين منفصلين**، بالإضافة إلى **شرفة خاصة بإطلالة خارجية**، ودورة مياه خاصة مجهزة بكافة المستلزمات الأساسية. كما يوفر الجناح جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، ليمنح الضيوف تجربة إقامة مريحة وهادئة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفة رئيسية بسرير كينغ سايز\r\n* 🛏️ غرفة ثانية بسريرين منفصلين\r\n* 🛋️ جلسة جانبية مريحة\r\n* 🌅 شرفة خاصة بإطلالة خارجية\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 🪞 مرآة مع طاولة تجميل\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Family Suite with Two Bedrooms and a Private Balcony\r\n\r\n### Description\r\n\r\nEnjoy a comfortable stay in our **Family Suite with Two Bedrooms**, thoughtfully designed to provide a relaxing experience for families. The suite features a **master bedroom with a comfortable king-size bed and a cozy seating area**, along with a **second bedroom with two separate beds**. It also includes a **private balcony with an outdoor view** and a **private bathroom equipped with all essential amenities**. To ensure a pleasant stay, the suite is equipped with a smart TV, a mini refrigerator, a wardrobe, a landline telephone, and a private Wi-Fi modem for fast and reliable internet access.\r\n\r\n### Features\r\n\r\n* 🛏️ Master bedroom with a king-size bed\r\n* 🛏️ Second bedroom with two separate beds\r\n* 🛋️ Comfortable seating area\r\n* 🌅 Private balcony with an outdoor view\r\n* 👗 Wardrobe\r\n* 🧊 Mini refrigerator\r\n* 🪞 Vanity table with mirror\r\n* 📺 Smart TV\r\n* 📞 Landline telephone for guest services\r\n* 🛜 Private Wi-Fi modem\r\n* 🚿 Private bathroom with essential amenities','per_night',4,'[\"2026-07-23-6a62806fbc752.png\",\"2026-07-23-6a62806fbcbd8.png\",\"2026-07-23-6a62806fbce1d.png\",\"2026-07-23-6a62806fbd08c.png\",\"2026-07-23-6a62806fbd2e3.png\",\"2026-07-23-6a62806fbd635.png\"]',1,'active','2026-07-23 21:58:23','2026-07-25 20:48:50'),
(70,16,'جناح عائلي غرفتين وصالة ومطبخ','جناح عائلي غرفتين وصالة ومطبخ','الوصف:\r\n\r\nجناح عائلي يتكون من غرفتي نوم، الأولى بسرير مزدوج كبير وتسريحة بمرآة ودولاب ملابس خشبي وخزنة آمنة، والثانية بسريرين منفصلين ودولاب خشبي. يحتوي الجناح على صالة مع كنب وشاشة وطاولة طعام بـ 4 كراسي، ومطبخ مستقل مجهز بثلاجة وحوض غسيل وخزائن خشبية، ودورة مياه خاصة بحوض استحمام (بانيو) ومرحاض فرنجي ، مع توفر إنترنت مجاني  ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفتا نوم (واحدة مزدوجة والأخرى بسريرين)\r\n🛋️ صالة مع شاشة ذكية \r\n🔒 خزنة آمنة (خزنة ملابس)\r\n🍽️ طاولة طعام (4 كراسي)\r\n🍳 مطبخ مستقل مجهز\r\n🛁 دورة مياه (بانيو وشطاف)\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-24-6a62821ad6819.png\",\"2026-07-24-6a62821ad6d8b.png\",\"2026-07-24-6a62821ad6fb5.png\",\"2026-07-24-6a62821ad7200.png\",\"2026-07-24-6a62821ad7405.png\",\"2026-07-24-6a62821ad762f.png\",\"2026-07-24-6a62821ad78b2.png\",\"2026-07-24-6a62821ad7aa3.png\",\"2026-07-24-6a62821ad7cb6.png\",\"2026-07-24-6a62821ad7f62.png\"]',1,'active','2026-07-23 22:03:41','2026-07-24 19:46:19'),
(71,15,'الجناح غرفتان شرفة خاصة ومجلس عربي 601','Royal Bridal Suite 601','الجناح الملكي العرائسي 601\r\n\r\n**الوصف:**\r\n\r\nجناح ملكي عرائسي صُمم بعناية ليمنح العرسان والعائلات الصغيرة تجربة إقامة تجمع بين الفخامة والراحة والخصوصية. يضم الجناح **غرفتي نوم**، تشمل **غرفة رئيسية بسرير كينغ سايز مع شرفة خاصة**، وغرفة ثانية تحتوي على **سريرين منفصلين مع جلسة جانبية**، بالإضافة إلى **مجلس عربي واسع** يوفر مساحة مثالية للاسترخاء واستقبال الضيوف. كما يحتوي الجناح على **حوض جاكوزي كبير** داخل **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة فاخرة لا تُنسى.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفة رئيسية بسرير كينغ سايز\r\n* 🛏️ غرفة ثانية بسريرين منفصلين\r\n* 🌅 شرفة خاصة\r\n* 🛋️ جلسة جانبية\r\n* 🏛️ مجلس عربي واسع\r\n* 🛁 حوض جاكوزي كبير\r\n* 👗 دولاب ملابس واسع\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* 💖 مثالي للعرسان والعائلات الصغيرة الباحثين عن إقامة راقية ومريحة','## Royal Honeymoon Suite 601\r\n\r\nExperience a luxurious stay in the **Royal Honeymoon Suite 601**, thoughtfully designed to provide newlyweds and small families with exceptional comfort and privacy. The suite features two elegant bedrooms, including a luxurious master bedroom with a private balcony, a spacious living majlis, and a premium bathroom with a Jacuzzi bathtub, creating the perfect blend of elegance and relaxation.\r\n\r\n### Master Bedroom\r\n\r\n* Luxurious king-size bed.\r\n* Private balcony with comfortable outdoor seating.\r\n* Modern décor with premium finishes.\r\n* Spacious wardrobe.\r\n* Ambient LED lighting for a romantic atmosphere.\r\n\r\n### Second Bedroom\r\n\r\n* Two comfortable single beds.\r\n* Cozy seating area.\r\n* Spacious wardrobe.\r\n* Elegant design with ample space.\r\n\r\n### Living Majlis\r\n\r\n* Spacious living area with elegant furnishings.\r\n* Comfortable seating for relaxation or entertaining guests.\r\n* Dining table.\r\n* Modern décor with stylish lighting.\r\n\r\n### Bathroom\r\n\r\n* Luxury bathroom with a large Jacuzzi bathtub.\r\n* Premium finishes and spacious layout.\r\n* Designed for relaxation and a rejuvenating experience.\r\n\r\n### Features\r\n\r\n* Two separate bedrooms.\r\n* Master bedroom with a private balcony.\r\n* Spacious and comfortable living majlis.\r\n* Luxury bathroom with a Jacuzzi bathtub.\r\n* Air conditioning.\r\n* Flat-screen TV.\r\n* Premium furniture and hotel-quality furnishings.\r\n* Modern design with LED lighting.\r\n* Ideal for honeymooners and small families, offering the highest levels of comfort, privacy, and luxury.','per_night',4,'[\"2026-07-24-6a62854a62129.png\",\"2026-07-24-6a62854a62808.png\",\"2026-07-24-6a62854a6304c.png\",\"2026-07-24-6a62854a6338d.png\",\"2026-07-24-6a62854a63612.png\",\"2026-07-24-6a62854a63898.png\",\"2026-07-24-6a62854a63b29.png\",\"2026-07-24-6a62854a63cb3.png\"]',1,'active','2026-07-23 22:19:06','2026-07-26 20:09:21'),
(72,16,'جناح عائلي غرفتين ومجلس ومطبخ','جناح عائلي غرفتين ومجلس ومطبخ','الوصف:\r\n\r\nجناح عائلي يتكون من غرفتي نوم، الأولى بسرير مزدوج كبير مع دولاب ملابس وتسريحة وكمودينو جانبي، والثانية بسريرين منفصلين مع دولاب ملابس ومكتب خشبي وتسريحة. يحتوي الجناح على مجلس كنب مع طاولة وشاشة وطاولة طعام بـ 6 كراسي، ومطبخ مستقل مجهز بموقد غاز وثلاجة وحوض غسيل وخزائن خشبية وغلاية ماء، ودورة مياه خاصة بحوض استحمام (بانيو) ومرحاض فرنجي، مع إنترنت مجاني  ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفتا نوم (واحدة مزدوجة والأخرى بسريرين)\r\n🛋️ مجلس مع شاشة تلفزيون\r\n🍽️ طاولة طعام (6 كراسي)\r\n💼 مكتب عمل\r\n🍳 مطبخ مستقل مجهز بالكامل\r\n🛁 دورة مياه (بانيو)\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-24-6a62866a34ba3.png\",\"2026-07-24-6a62866a3513b.png\",\"2026-07-24-6a62866a35338.png\",\"2026-07-24-6a62866a355c9.png\",\"2026-07-24-6a62866a35801.png\",\"2026-07-24-6a62866a35bde.png\",\"2026-07-24-6a62866a35e9a.png\",\"2026-07-24-6a62866a360c3.png\",\"2026-07-24-6a62866a36304.png\"]',1,'active','2026-07-23 22:23:54','2026-07-24 19:45:09'),
(73,15,'مجلس 16 شخص','Majlis','مجلس راقي وفخم مطل\r\n يتسع 16 شخص\r\nدورة مياة خاصة','A spacious, elegant, and luxurious majlis with beautiful views, comfortably seating up to 16 guests','per_night',15,'[\"2026-07-24-6a62871fcde9a.png\",\"2026-07-24-6a62871fce4bd.png\"]',1,'active','2026-07-23 22:26:55','2026-07-27 20:58:26'),
(74,16,'جناح عائلي فاخر غرفة وصالة بمجلس','جناح عائلي فاخر غرفة وصالة بمجلس','الوصف:\r\n\r\nجناح عائلي يتكون من غرفة نوم تحتوي على سرير مزدوج كبير ودولاب ملابس خشبي وتسريحة وكمودينو جانبي. يحتوي الجناح على مجلس مع طاولة وسط وطاولة طعام بـ 4 كراسي ومكتب عمل جانبي، إضافة إلى مكتبة تلفزيون مع شاشة، ومطبخ مستقل واسع مجهز بموقد غاز ومايكروويف وحوض غسيل وخزائن خشبية متكاملة، ودورة مياه خاصة بحوض استحمام (بانيو) ومرحاض فرنجي وشطاف ودش استحمامي،إنترنت مجاني ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم ماستر (تسريحة ودولاب)\r\n🛋️ مجلس مع شاشة تلفزيون\r\n🍽️ طاولة طعام (4 كراسي)\r\n💼 مكتب عمل\r\n🍳 مطبخ مستقل مجهز بالكامل\r\n🛁 دورة مياه (بانيو وشطاف)\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🛗 مصعد بالفندقق',NULL,'per_night',2,'[\"2026-07-24-6a628b33ee4a3.png\",\"2026-07-24-6a628b33ee9c4.png\",\"2026-07-24-6a628b33eec4a.png\",\"2026-07-24-6a628b33eeefc.png\",\"2026-07-24-6a628b33ef102.png\",\"2026-07-24-6a628b33ef36b.png\",\"2026-07-24-6a628b33ef594.png\",\"2026-07-24-6a628b33ef782.png\",\"2026-07-24-6a628d6c542b0.png\",\"2026-07-24-6a628d6c54a6b.png\"]',1,'active','2026-07-23 22:44:19','2026-07-24 23:24:29'),
(75,15,'جناح غرفتان ومجلس ودورة مياة  302','Suite 302','جناح \r\n**الوصف:**\r\n\r\nجناح ملكي صُمم بعناية ليجمع بين الفخامة والراحة والخصوصية، ويوفر تجربة إقامة راقية تناسب العرسان والعائلات الصغيرة. يضم الجناح **غرفة رئيسية بسرير كينغ سايز مريح**، و**غرفة ثانية تحتوي على سريرين منفصلين**، بالإضافة إلى **مجلس عربي مستقل** يوفر مساحة مثالية للاسترخاء واستقبال الضيوف. كما يحتوي الجناح على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة فاخرة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفة رئيسية بسرير كينغ سايز\r\n* 🛏️ غرفة ثانية بسريرين منفصلين\r\n* 🏛️ مجلس عربي مستقل\r\n* 👗 دولاب ملابس واسع\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* ✨ تصميم عصري بإضاءة LED هادئة\r\n* 💖 مثالي للعرسان والعائلات الصغيرة الباحثين عن إقامة راقية ومريحة','## Family Suite 302\r\n\r\nEnjoy a comfortable stay in **Family Suite 302**, thoughtfully designed to provide a relaxing atmosphere for families and small groups. The suite features two separate bedrooms and a stylish living area with a traditional Arabic majlis, combining modern design with comfort and privacy.\r\n\r\n### First Bedroom\r\n\r\n* One comfortable king-size bed.\r\n* Premium hotel-quality furnishings.\r\n* Spacious wardrobe.\r\n* Modern LED lighting.\r\n* Large windows providing plenty of natural light.\r\n\r\n### Second Bedroom\r\n\r\n* Two comfortable single beds.\r\n* Spacious wardrobe.\r\n* Premium hotel-quality furnishings.\r\n* Elegant design with ample space.\r\n\r\n### Majlis\r\n\r\n* Comfortable traditional Arabic majlis with an elegant design.\r\n* Spacious seating area, ideal for families and guests.\r\n* Flat-screen TV.\r\n* Modern décor with ambient lighting.\r\n\r\n### Features\r\n\r\n* Two separate bedrooms.\r\n* Separate Arabic majlis.\r\n* Air conditioning.\r\n* Flat-screen TV.\r\n* Premium furniture and hotel-quality furnishings.\r\n* Elegant ceramic flooring.\r\n* Modern LED lighting.\r\n* Ideal for families and small groups.\r\n* Offers comfort and privacy throughout your stay.','per_night',4,'[\"2026-07-24-6a628fd6cb2ab.png\",\"2026-07-24-6a628fd6cb91d.png\",\"2026-07-24-6a628fd6cbb00.png\",\"2026-07-24-6a628fd6cbc5f.png\"]',1,'active','2026-07-23 23:04:06','2026-07-26 20:07:19'),
(76,16,'جناح عائلي مفتوح بمجلس ومطبخ مستقل','جناح عائلي مفتوح بمجلس ومطبخ مستقل','الوصف:\r\n\r\nجناح عائلي مفتوح يجمع بين منطقة النوم والمجلس في مساحة واحدة واسعة، يحتوي على سرير مزدوج كبير وشيزلونج مريح، ومجلس مع طاولة وسط وشاشة ذكية. يتضمن الجناح طاولة طعام دائرية بـ 4 كراسي، ومكتب عمل خشبي وشماعة ملابس، بالإضافة إلى مطبخ مستقل مجهز بثلاجة ومايكروويف وغلاية وحوض غسيل وخزائن خشبية، ودورة مياه خاصة بحوض استحمام (بانيو) ودش استحمامي ومرحاض فرنجي،إنترنت مجاني  ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير مزدوج وشيزلونج (مساحة مفتوحة)\r\n🛋️ مجلس مع شاشة تلفزيون\r\n🍽️ طاولة طعام (4 كراسي)\r\n💼 مكتب عمل وشماعة ملابس\r\n🍳 مطبخ مستقل مجهز بالكامل\r\n🛁 دورة مياه (بانيو وشطاف)\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-24-6a629022b31e2.png\",\"2026-07-24-6a629022b37e4.png\",\"2026-07-24-6a629022b3a87.png\",\"2026-07-24-6a629022b3e01.png\",\"2026-07-24-6a629022b40fd.png\",\"2026-07-24-6a629022b4350.png\"]',1,'active','2026-07-23 23:05:22','2026-07-24 19:42:44'),
(77,16,'جناح عائلي الملكي غرفة نوم وثلاثة مجالس ومطبخ','جناح عائلي الملكي غرفة نوم وثلاثة مجالس ومطبخ','الوصف:\r\nجناح عائلي واسع يتكون من غرفة نوم خاصة تحتوي على سرير مزدوج كبير وشيزلونج استرخاء ودولاب ملابس خشبي وتسريحة مع مرآة، بالإضافة إلى ثلاثة مجالس كنب مستقلة مجهزة بمكتبة وشاشة تلفزيون. يتضمن الجناح صالة طعام مستقلة بطاولة تتسع لـ 10 أشخاص، ومكتب عمل مع كرسي دوار، ومطبخ مستقل مجهز بموقد غاز وثلاجة وموزع مياه وخزائن خشبية، ودورة مياه خاصة بحوض استحمام (بانيو) ودش استحمامي ومرحاض فرنجي ومرحاض عربي، مع توفر إنترنت مجاني (Wi-Fi) ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم ماستر مع تسريحة ودولاب\r\n🛋️ 3 مجالس كنب مستقلة مع شاشة تلفزيون\r\n🍽️ طاولة طعام تتسع لـ 10 أشخاص\r\n💼 مكتب عمل\r\n🍳 مطبخ مستقل مجهز بالكامل\r\n🛁 دورة مياه (بانيو، فرنجي، وعربي)\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-24-6a6295640bb1d.png\",\"2026-07-24-6a6295640c0d2.png\",\"2026-07-24-6a6295640c316.png\",\"2026-07-24-6a6295640c640.png\",\"2026-07-24-6a6295640c8da.png\",\"2026-07-24-6a6295640cbb4.png\",\"2026-07-24-6a6295640ce96.png\",\"2026-07-24-6a6295640d086.png\",\"2026-07-24-6a6295640d2fa.png\",\"2026-07-24-6a6295640d614.png\"]',1,'active','2026-07-23 23:27:48','2026-07-24 23:46:20'),
(78,16,'طيرمانة ملكية بإطلالة بانورامية','طيرمانة ملكية بإطلالة بانورامية','الوصف:\r\nطيرمانة فاخرة بإطلالة بانورامية على المدينة، تحتوي على جلسة عربية واسعة، وشاشة تلفزيون، وطاولات تقديم، ودورة مياه خاصة، مع توفر خدمة Wi-Fi ومصعد بالفندق.\r\n\r\nالمميزات:\r\n\r\n🛋️ جلسة عربية واسعة\r\n🪟 نوافذ كبيرة بإطلالة بانورامية\r\n📺 شاشة ذكية\r\n☕ طاولات تقديم\r\n🛁 دورة مياه خاصة\r\n🛜 إنترنت مجاني\r\n🛗 مصعد بالفندق',NULL,'per_night',2,'[\"2026-07-24-6a629a8757571.png\",\"2026-07-24-6a629a8757e15.png\",\"2026-07-24-6a629a8758279.png\"]',1,'active','2026-07-23 23:49:43','2026-07-24 23:49:45'),
(79,15,'الجناح العرائسي غرفة بسرير كنج سايز وإطلالة بانوراما وجاكوزي 700','Royal Honeymoon Suite 700','### الجناح العرائسي الملكي 700\r\n\r\n**الوصف:**\r\n\r\nجناح عرائسي ملكي صُمم بعناية ليمنح العرسان تجربة إقامة استثنائية تجمع بين الفخامة والرومانسية والخصوصية. يتميز الجناح **بسرير كينغ سايز فاخر**، و**جلسة داخلية راقية** تضم **كرسيًا معلقًا للاسترخاء**، بالإضافة إلى **إطلالة بانورامية مميزة** تمنح المكان أجواءً هادئة وساحرة. كما يحتوي الجناح على **حوض جاكوزي كبير** داخل **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، ليمنح الضيوف تجربة إقامة فاخرة لا تُنسى.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز فاخر\r\n* 🛋️ جلسة داخلية راقية\r\n* 🪑 كرسي معلق للاسترخاء\r\n* 🛁 حوض جاكوزي كبير\r\n* 🌄 إطلالة بانورامية مميزة\r\n* 👗 دولاب ملابس واسع\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* 💖 مثالي للعرسان والأزواج الباحثين عن إقامة رومانسية وفاخرة','## Royal Bridal Suite 700\r\n\r\nExperience an exceptional stay in the **Royal Bridal Suite 700**, thoughtfully designed to offer newlyweds a luxurious and romantic retreat. This spacious open-plan suite features breathtaking panoramic city views, elegant royal-style furnishings, and a lavish bathroom with a large Jacuzzi, creating an unforgettable atmosphere of comfort, privacy, and sophistication.\r\n\r\n### Sleeping Area\r\n\r\n* Luxurious King-size bed.\r\n* Premium hotel-quality bedding.\r\n* Elegant royal-inspired décor with romantic LED lighting.\r\n* Large panoramic windows offering stunning city views.\r\n\r\n### Lounge Area\r\n\r\n* Comfortable luxury seating.\r\n* Stylish hanging swing chair for relaxation.\r\n* Flat-screen TV.\r\n* Elegant hospitality corner.\r\n\r\n### Bathroom\r\n\r\n* Luxurious bathroom with premium finishes.\r\n* Large Jacuzzi bathtub.\r\n* Spacious layout designed for ultimate comfort and relaxation.\r\n\r\n### Features\r\n\r\n* Spacious open-plan royal suite.\r\n* Luxury King-size bed.\r\n* Large Jacuzzi bathtub.\r\n* Panoramic city views.\r\n* Elegant lounge area with a hanging swing chair.\r\n* Flat-screen TV.\r\n* Air conditioning.\r\n* Premium furniture and hotel-quality furnishings.\r\n* Modern interior design with ambient LED lighting.\r\n* **Perfect for honeymooners and guests seeking a luxurious, romantic, and unforgettable stay.**','per_night',2,'[\"2026-07-24-6a62a2d91ffce.png\",\"2026-07-24-6a62a2d9204fb.png\",\"2026-07-24-6a62a2d9206b2.png\",\"2026-07-24-6a62a2d92083b.png\",\"2026-07-24-6a62a2d920a2c.png\",\"2026-07-24-6a62a2d920bdb.png\",\"2026-07-24-6a62a2d920d53.png\"]',1,'active','2026-07-24 00:25:13','2026-07-26 20:05:29'),
(80,15,'جناح غرفتان ومجلس ودورة مياة مع جاكوزي 602','Royal Bridal Suite 602','### الجناح الملكي 602\r\n\r\n**الوصف:**\r\n\r\nجناح ملكي صُمم بعناية ليجمع بين الفخامة والراحة والخصوصية، ويوفر تجربة إقامة راقية تناسب العرسان والعائلات الصغيرة. يضم الجناح **غرفة رئيسية بسرير كينغ سايز مريح**، و**غرفة ثانية تحتوي على سريرين منفصلين**، بالإضافة إلى **مجلس عربي مستقل** يوفر مساحة مثالية للاسترخاء واستقبال الضيوف. كما يحتوي الجناح على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ويوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة فاخرة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفة رئيسية بسرير كينغ سايز\r\n* 🛏️ غرفة ثانية بسريرين منفصلين\r\n* 🏛️ مجلس عربي مستقل\r\n* 👗 دولاب ملابس واسع\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* ✨ تصميم عصري بإضاءة LED هادئة\r\n* 💖 مثالي للعرسان والعائلات الصغيرة الباحثين عن إقامة راقية ومريحة','## Royal Bridal Suite 602\r\n\r\nEnjoy an elegant stay in the **Royal Bridal Suite 602**, thoughtfully designed to combine luxury, comfort, and privacy. The suite features a stylish master bedroom, an additional bedroom with two single beds, and a spacious traditional Arabic sitting room, making it an ideal choice for honeymooners and small families seeking a memorable stay.\r\n\r\n### Suite Details\r\n\r\n**Master Bedroom**\r\n\r\n* Comfortable king-size bed.\r\n* Premium hotel-quality bedding.\r\n* Elegant design with modern LED lighting.\r\n* Cozy seating area by the window.\r\n* Spacious wardrobe.\r\n\r\n**Second Bedroom**\r\n\r\n* Two comfortable single beds.\r\n* High-quality hotel bedding.\r\n* Wardrobe.\r\n* Large windows providing plenty of natural light.\r\n\r\n**Arabic Majlis (Sitting Room)**\r\n\r\n* Spacious and comfortable traditional Arabic majlis.\r\n* Elegant seating suitable for family and guests.\r\n* Flat-screen TV.\r\n* Modern décor with soft ambient lighting.\r\n\r\n**Bathroom**\r\n\r\n* Luxurious bathroom with premium finishes.\r\n* Modern shower cabin.\r\n* Spacious layout designed for comfort and relaxation.\r\n\r\n### Features\r\n\r\n* Master bedroom with a king-size bed.\r\n* Additional bedroom with two single beds.\r\n* Separate traditional Arabic majlis.\r\n* Luxury bathroom with a modern shower cabin.\r\n* Flat-screen TV.\r\n* Air conditioning.\r\n* Premium furniture and hotel-quality furnishings.\r\n* Elegant flooring and modern LED lighting.\r\n* **Perfect for honeymooners and small families seeking a comfortable and elegant stay.**','per_night',2,'[\"2026-07-24-6a62a4fd61c18.png\",\"2026-07-24-6a62a4fd62d4f.png\",\"2026-07-24-6a62a4fd63ba3.png\",\"2026-07-24-6a62a4fd64bd3.png\",\"2026-07-24-6a62a4fd652ae.png\",\"2026-07-24-6a62a4fd65e33.png\"]',1,'active','2026-07-24 00:34:21','2026-07-26 20:04:28'),
(81,15,'جناح عائلي بثلاث غرف نوم ومجلس عربي306','Family Suite 306','جناح عائلي بثلاث غرف نوم ومجلس عربي\r\n\r\n**الوصف:**\r\n\r\nجناح عائلي واسع مكوّن من **ثلاث غرف نوم**، صُمم بعناية ليوفر إقامة مريحة تناسب العائلات الكبيرة والمجموعات. يضم الجناح **غرفتين رئيسيتين، كل منهما تحتوي على سرير كينغ سايز مريح**، بالإضافة إلى **غرفة ثالثة تضم سريرين منفصلين**، مما يوفر مساحة مريحة للنوم والاسترخاء. كما يحتوي الجناح على **مجلس عربي واسع** مثالي للتجمعات العائلية واستقبال الضيوف، و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية. ويوفر الجناح جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ غرفتان رئيسيتان بسرير كينغ سايز\r\n* 🛏️ غرفة ثالثة بسريرين منفصلين\r\n* 🏛️ مجلس عربي واسع\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Family Suite 306\r\n\r\nEnjoy a comfortable stay in **Family Suite 306**, thoughtfully designed to provide a peaceful and relaxing atmosphere for families and small groups. The suite features two spacious bedrooms and a cozy Arabic-style sitting room, combining modern design, comfort, and privacy in a quiet location away from noise and disturbance.\r\n\r\n### Suite Details\r\n\r\n**Master Bedroom**\r\n\r\n* Comfortable King-size bed.\r\n* Premium hotel-quality bedding.\r\n* Cozy seating area by the window.\r\n* Spacious wardrobe.\r\n* Modern LED lighting.\r\n\r\n**Second Bedroom**\r\n\r\n* Two comfortable single beds.\r\n* Premium hotel-quality bedding.\r\n* Wardrobe with a vanity mirror.\r\n* Spacious layout with soft, relaxing lighting.\r\n\r\n**Arabic Majlis**\r\n\r\n* Private Arabic-style sitting room with an elegant design.\r\n* Comfortable seating for family and guests.\r\n* **32-inch flat-screen TV.**\r\n* Quiet and relaxing ambiance.\r\n\r\n### Features\r\n\r\n* Master bedroom with a King-size bed.\r\n* Second bedroom with two single beds.\r\n* Private Arabic-style majlis.\r\n* **32-inch flat-screen TV.**\r\n* Complimentary Wi-Fi.\r\n* Air conditioning.\r\n* Premium furniture and hotel-quality furnishings.\r\n* Modern interior with LED lighting.\r\n* Quiet location away from noise and disturbance.\r\n* **Ideal for families and small groups seeking a comfortable, peaceful, and relaxing stay.**','per_night',2,'[\"2026-07-24-6a62a868b4b42.png\",\"2026-07-24-6a62a868b5128.png\",\"2026-07-24-6a62a868b5381.png\",\"2026-07-24-6a62a868b550a.png\",\"2026-07-24-6a62a868b5671.png\",\"2026-07-24-6a62a868b57ff.png\"]',1,'active','2026-07-24 00:48:56','2026-07-25 23:24:28'),
(82,15,'غرفة عائلية بسرير كينغ سايز وجلسة جانبية205','Room 205','غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Room 205\r\n\r\nEnjoy a comfortable stay in **Room 205**, thoughtfully designed to provide a peaceful and modern atmosphere for both solo travelers and couples. The room features a comfortable King-size bed, premium hotel-quality bedding, a cozy seating area, and a private bathroom equipped with everything you need for a relaxing stay.\r\n\r\n### Features\r\n\r\n* Comfortable King-size bed.\r\n* Premium hotel-quality bedding.\r\n* Cozy seating area.\r\n* Flat-screen TV.\r\n* Mini refrigerator.\r\n* Air conditioning.\r\n* Private bathroom.\r\n* Wardrobe with vanity mirror.\r\n* Modern LED lighting.\r\n* **Ideal for solo travelers and couples seeking a comfortable and peaceful stay.**','per_night',2,'[\"2026-07-24-6a62aab60bfd3.png\",\"2026-07-24-6a62aab60c46e.png\",\"2026-07-24-6a62aab60c5e1.png\",\"2026-07-24-6a62aab60c74b.png\"]',1,'active','2026-07-24 00:58:46','2026-07-25 23:12:18'),
(83,15,'الغرفة عائلية بجلسة جانبية ودورة مياة مع جاكوزي 301','Royal Room 301','الغرفة عائلية بجلسة جانبية ودورة مياة مع جاكوزي 301\r\n\r\n**الوصف:**\r\n\r\nغرفة ملكية صُممت بعناية لتمنح العرسان والأزواج تجربة إقامة فاخرة تجمع بين الراحة والرومانسية. تضم الغرفة **سرير كينغ سايز فاخر**، بالإضافة إلى **جلسة داخلية مريحة**، و**حوض جاكوزي خاص** للاسترخاء، مع **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية. كما توفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس واسع، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة راقية ومميزة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز فاخر\r\n* 🛁 حوض جاكوزي خاص\r\n* 🛋️ جلسة داخلية مريحة\r\n* 👗 دولاب ملابس واسع\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية\r\n* ✨ تصميم عصري بإضاءة LED هادئة\r\n* 💖 مثالية للعرسان والأزواج','## Royal Room 301\r\n\r\nEnjoy a luxurious stay in **Royal Room 301**, thoughtfully designed to provide a romantic atmosphere for honeymooners and couples. The room features a luxurious King-size bed, a cozy seating area, and a private Jacuzzi, complemented by modern décor and elegant lighting that create a truly relaxing and sophisticated experience.\r\n\r\n### Features\r\n\r\n* Luxurious King-size bed.\r\n* Private Jacuzzi.\r\n* Cozy seating area.\r\n* Flat-screen TV.\r\n* Mini refrigerator.\r\n* Large wardrobe with mirrors.\r\n* Luxury bathroom with a modern shower cabin.\r\n* Air conditioning.\r\n* Modern LED lighting.\r\n* **Ideal for honeymooners and couples seeking a romantic and luxurious stay.**','per_night',2,'[\"2026-07-24-6a62ac8fc3a08.png\",\"2026-07-24-6a62ac8fc4a87.png\",\"2026-07-24-6a62ac8fc4fd2.png\",\"2026-07-24-6a62ac8fc619e.png\"]',1,'active','2026-07-24 01:06:39','2026-07-26 20:01:37'),
(84,15,'غرفة بثلاثة أسرة مفردة وجلسة جانبية 203. 1×3','Room 203 – 3 Single Beds','غرفة بثلاثة أسرة مفردة وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة مريحة صُممت بعناية لتناسب العائلات الصغيرة والمجموعات. تضم الغرفة **ثلاثة أسرة مفردة مجهزة بالكامل**، بالإضافة إلى **جلسة جانبية مريحة** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ ثلاثة أسرة مفردة مجهزة بالكامل\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-24-6a62ae2daddda.png\",\"2026-07-24-6a62ae2dae6cc.png\"]',1,'active','2026-07-24 01:13:33','2026-07-25 23:01:35'),
(85,15,'غرفة 203 – غرفة بثلاثة أسرة مفردة وجلسة جانبية','Room 303 – 3 Single Beds','غرفة بثلاثة أسرة مفردة وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة مريحة صُممت بعناية لتناسب العائلات الصغيرة والمجموعات. تضم الغرفة **ثلاثة أسرة مفردة مجهزة بالكامل**، بالإضافة إلى **جلسة جانبية مريحة** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ ثلاثة أسرة مفردة مجهزة بالكامل\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',': Room 303\r\n\r\nEnjoy a comfortable stay in **Room 203**, thoughtfully designed for small families and groups. The room features **three comfortable single beds**, a cozy seating area, modern LED lighting, and large windows that create a bright and relaxing atmosphere for a peaceful and enjoyable stay.\r\n\r\n### Features\r\n\r\n* Three comfortable single beds.\r\n* High-quality hotel bedding.\r\n* Cozy seating area.\r\n* Flat-screen TV.\r\n* Air conditioning.\r\n* Wardrobe.\r\n* Modern LED lighting.\r\n* Large windows with elegant curtains.\r\n* **Ideal for small families and groups seeking a comfortable and peaceful stay.**','per_night',2,'[\"2026-07-24-6a62af9e9ebbd.png\",\"2026-07-24-6a62af9e9f2a7.png\"]',1,'active','2026-07-24 01:19:42','2026-07-25 22:58:39'),
(86,15,'غرفة عائلية بسرير كينغ سايز وجلسة جانبية 304','Family Room 304','غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-24-6a62b0ca2fd9d.png\",\"2026-07-24-6a62b0ca305be.png\",\"2026-07-24-6a62b0ca307ff.png\",\"2026-07-24-6a62b0ca309f4.png\"]',1,'active','2026-07-24 01:24:42','2026-07-25 22:54:07'),
(87,15,'غرفة عائلية بسرير كينغ سايز وجلسة جانبية وشرفة مطلة 401','Family Room 401','غرفة عائلية بسرير كينغ سايز وجلسة جانبية وشرفة مطلة\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء، و**شرفة خاصة بإطلالة خارجية** تمنح الضيوف أجواءً ممتعة وإضاءة طبيعية. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 🌅 شرفة خاصة بإطلالة خارجية\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-24-6a62ba3c66a75.png\",\"2026-07-24-6a62ba3c67932.png\",\"2026-07-24-6a62ba3c67f97.png\",\"2026-07-24-6a62ba3c684a3.png\",\"2026-07-24-6a62ba3c68983.png\"]',1,'active','2026-07-24 02:05:00','2026-07-25 22:49:10'),
(88,15,'غرفة عائلية بسرير كينغ سايز وجلسة جانبية 404','Room 404','غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-24-6a62bb2f145ce.png\"]',1,'active','2026-07-24 02:09:03','2026-07-25 22:41:35'),
(89,15,'غرفة عائلية بسرير كينغ سايز وجلسة جانبية 501','Room 501','غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-24-6a62bc8ed8fd8.png\",\"2026-07-24-6a62bc8ed942c.png\"]',1,'active','2026-07-24 02:14:54','2026-07-25 22:38:11'),
(90,15,'غرفة عائلية بسرير كينغ سايز وجلسة جانبية 504','Room 504','غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-24-6a62bd9a58586.png\",\"2026-07-24-6a62bd9a58b86.png\"]',1,'active','2026-07-24 02:19:22','2026-07-25 22:33:42'),
(91,15,'غرفة عائلي بسرير ماستر وجلسة جانبية وشرفة مطلة ( 505)','Family Room 505 – Featuring a Master Bed and a Cozy Side Seating Area','### غرفة عائلية بسرير كينغ سايز وجلسة جانبية وشرفة مطلة\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، و**جلسة جانبية مريحة** للاسترخاء، بالإضافة إلى **شرفة خاصة بإطلالة خارجية** تمنح الضيوف أجواءً مميزة وإضاءة طبيعية. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 🌅 شرفة خاصة بإطلالة خارجية\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-24-6a62bf29c90cb.png\",\"2026-07-24-6a62bf29caa53.png\",\"2026-07-24-6a62bf29cb903.png\",\"2026-07-24-6a62bf29cc027.png\",\"2026-07-24-6a62bf29ccb34.png\",\"2026-07-24-6a62bf29ccf9a.png\"]',1,'active','2026-07-24 02:26:01','2026-07-25 22:25:08'),
(92,15,'غرفة عائلية بسرير ماستر وجلسة جانبية(305)','Family Room 305 – Featuring a Master Bed and a Cozy Side Seating Area','### غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Family Room with King Bed and Side Seating Area\r\n\r\n**Description:**\r\n\r\nA family room thoughtfully designed to provide a comfortable and relaxing stay for couples and small families. The room features a **comfortable king-size bed**, along with a **cozy side seating area** ideal for unwinding. It also includes a **private bathroom** equipped with all essential amenities. The room offers all the necessary conveniences, including a **smart TV**, **mini refrigerator**, **wardrobe**, **landline telephone**, and a **private Wi-Fi modem**, ensuring a comfortable and enjoyable stay.\r\n\r\n### Features\r\n\r\n* 🛏️ Comfortable King-Size Bed\r\n* 🛋️ Cozy Side Seating Area\r\n* 👗 Wardrobe\r\n* 🧊 Mini Refrigerator\r\n* 📺 Smart TV\r\n* 📞 Landline Telephone for Guest Services\r\n* 🛜 Private Wi-Fi Modem\r\n* 🚿 Private Bathroom with Essential Amenities','per_night',2,'[\"2026-07-24-6a62c025298e4.png\",\"2026-07-24-6a62c02529f05.png\"]',1,'active','2026-07-24 02:30:13','2026-07-25 22:16:11'),
(93,15,'غرفة 201 سريران مفردان وجلسة جانبية ودورة مياة','Room 201 (Twin Beds)','### غرفة بسريرين منفصلين وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة مريحة صُممت بعناية لتناسب الأصدقاء، وزملاء العمل، والعائلات الصغيرة. تضم الغرفة **سريرين منفصلين مجهزين بالكامل**، بالإضافة إلى **جلسة جانبية مريحة** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سريران منفصلان مجهزان بالكامل\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Twin Room with Side Seating Area\r\n\r\n**Description:**\r\n\r\nA comfortable room thoughtfully designed to accommodate friends, business travelers, and small families. The room features **two fully equipped separate beds**, along with a **cozy side seating area** that provides the perfect space to relax. It also includes a **private bathroom** equipped with all essential amenities. The room offers a range of in-room facilities, including a **smart TV**, **mini refrigerator**, **wardrobe**, **landline telephone**, and a **private Wi-Fi modem**, ensuring a comfortable and enjoyable stay.\r\n\r\n### Features\r\n\r\n* 🛏️ Two Fully Equipped Separate Beds\r\n* 🛋️ Cozy Side Seating Area\r\n* 👗 Wardrobe\r\n* 🧊 Mini Refrigerator\r\n* 📺 Smart TV\r\n* 📞 Landline Telephone for Guest Services\r\n* 🛜 Private Wi-Fi Modem\r\n* 🚿 Private Bathroom with Essential Amen','per_night',2,'[\"2026-07-24-6a62c1bd17996.png\",\"2026-07-24-6a62c1bd18c33.png\"]',1,'active','2026-07-24 02:37:01','2026-07-25 22:10:36'),
(94,15,'غرفة عائلي بسرير ماستر وجلسة جانبية(503)','Family Room 503 – Featuring a Master Bed and a Cozy Side Seating Area','### غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Family Room with King Bed and Side Seating Area\r\n\r\n**Description:**\r\n\r\nA family room thoughtfully designed to provide a comfortable and relaxing stay for couples and small families. The room features a **comfortable king-size bed**, along with a **cozy side seating area** ideal for unwinding. It also includes a **private bathroom** equipped with all essential amenities. The room offers all the necessary conveniences, including a **smart TV**, **mini refrigerator**, **wardrobe**, **landline telephone**, and a **private Wi-Fi modem**, ensuring a comfortable and enjoyable stay.\r\n\r\n### Features\r\n\r\n* 🛏️ Comfortable King-Size Bed\r\n* 🛋️ Cozy Side Seating Area\r\n* 👗 Wardrobe\r\n* 🧊 Mini Refrigerator\r\n* 📺 Smart TV\r\n* 📞 Landline Telephone for Guest Services\r\n* 🛜 Private Wi-Fi Modem\r\n* 🚿 Private Bathroom with Essential Amenities','per_night',2,'[\"2026-07-24-6a62c267c8fdb.png\"]',1,'active','2026-07-24 02:39:51','2026-07-25 22:04:11'),
(95,15,'غرفة عائلية بسرير ماستر وجلسة جانبية( 504):','Family Room 504 – Featuring a Master Bed and a Cozy Side Seating Area','### غرفة عائلية بسرير كينغ سايز وجلسة جانبية\r\n\r\n**الوصف:**\r\n\r\nغرفة عائلية صُممت بعناية لتوفر إقامة مريحة وهادئة تناسب الأزواج والعائلات الصغيرة. تضم الغرفة **سرير كينغ سايز مريح**، بالإضافة إلى **جلسة جانبية** توفر مساحة مثالية للاسترخاء. كما تحتوي على **دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، وتوفر جميع المرافق الأساسية، بما في ذلك شاشة ذكية، وثلاجة صغيرة، ودولاب ملابس، وهاتف أرضي، ومودم إنترنت خاص (Wi-Fi)، لتجربة إقامة مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🛏️ سرير كينغ سايز مريح\r\n* 🛋️ جلسة جانبية مريحة\r\n* 👗 دولاب ملابس\r\n* 🧊 ثلاجة صغيرة\r\n* 📺 شاشة ذكية\r\n* 📞 هاتف أرضي للتواصل مع الخدمة\r\n* 🛜 مودم إنترنت خاص (Wi-Fi)\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية','## Family Room with King Bed and Side Seating Area\r\n\r\n**Description:**\r\n\r\nA family room thoughtfully designed to provide a comfortable and relaxing stay for couples and small families. The room features a **comfortable king-size bed**, along with a **cozy side seating area** ideal for unwinding. It also includes a **private bathroom** equipped with all essential amenities. The room offers all the necessary conveniences, including a **smart TV**, **mini refrigerator**, **wardrobe**, **landline telephone**, and a **private Wi-Fi modem**, ensuring a comfortable and enjoyable stay.\r\n\r\n### Features\r\n\r\n* 🛏️ Comfortable King-Size Bed\r\n* 🛋️ Cozy Side Seating Area\r\n* 👗 Wardrobe\r\n* 🧊 Mini Refrigerator\r\n* 📺 Smart TV\r\n* 📞 Landline Telephone for Guest Services\r\n* 🛜 Private Wi-Fi Modem\r\n* 🚿 Private Bathroom with Essential Amenities','per_night',2,'[\"2026-07-24-6a62c30807ae3.png\"]',1,'active','2026-07-24 02:42:32','2026-07-25 21:58:40'),
(96,15,'طيرمانة1( 8 اشخاص)','طيرمانة1  تتسع 8 اشخاص','طيرمانة مجلس عربي\r\n\r\n**الوصف:**\r\n\r\nطيرمانة مجلس عربي صُممت بعناية لتوفر أجواءً مريحة ومناسبة للتجمعات العائلية واستقبال الضيوف. تضم **مجلسًا عربيًا واسعًا** مجهزًا بـ **8 مداكئ مريحة**، بالإضافة إلى **شاشة ذكية** لمزيد من الترفيه، و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتوفير تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع\r\n* 🛋️ 8 مداكئ مريحة\r\n* 📺 شاشة ذكية\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-26-6a654af817822.png\"]',1,'active','2026-07-26 00:47:04','2026-07-26 19:45:20'),
(97,15,'طيرمانة2 (6 اشخاص)','طيرمانة2 (تتسع6 اشخاص)','الوصف:\r\n\r\nطيرمانة مجلس عربي صُممت بعناية لتوفر أجواءً مريحة ومناسبة للتجمعات العائلية واستقبال الضيوف. تضم مجلسًا عربيًا واسعًامجهزًا بـ6 مداكئ مريحة بالإضافة إلى شاشة ذكية لمزيد من الترفيه، ودورة مياه خاصة مجهزة بكافة المستلزمات الأساسية، لتوفير تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع\r\n* 🛋️ 6 مداكئ مريحة\r\n* 📺 شاشة ذكية\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-26-6a654c36031a4.png\",\"2026-07-26-6a654c360498b.png\"]',1,'active','2026-07-26 00:52:22','2026-07-26 20:22:02'),
(98,15,'طيرمانة3 (6 اشخاص)','طيرمانة3 (تتسع6 اشخاص)','### طيرمانة مجلس عربي\r\n\r\n**الوصف:**\r\n\r\nطيرمانة مجلس عربي صُممت بعناية لتوفر أجواءً مريحة ومناسبة للتجمعات العائلية واستقبال الضيوف. تضم **مجلسًا عربيًا واسعًا** مجهزًا بـ **6 مداكئ مريحة**، بالإضافة إلى **شاشة ذكية** لمزيد من الترفيه، و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتوفير تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع\r\n* 🛋️ 6 مداكئ مريحة\r\n* 📺 شاشة ذكية\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-26-6a654cd478a4e.png\"]',1,'active','2026-07-26 00:55:00','2026-07-26 19:43:00'),
(99,15,'طيرمانة4 (6 اشخاص)','طيرمانة4 (تتسع6 اشخاص)','### طيرمانة مجلس عربي\r\n\r\n**الوصف:**\r\n\r\nطيرمانة مجلس عربي صُممت بعناية لتوفر أجواءً مريحة ومناسبة للتجمعات العائلية واستقبال الضيوف. تضم **مجلسًا عربيًا واسعًا** مجهزًا بـ **6 مداكئ مريحة**، بالإضافة إلى **شاشة ذكية** لمزيد من الترفيه، و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتوفير تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع\r\n* 🛋️ 6 مداكئ مريحة\r\n* 📺 شاشة ذكية\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-26-6a654d7de2c82.png\",\"2026-07-26-6a654d7de3979.png\"]',1,'active','2026-07-26 00:57:49','2026-07-26 19:42:26'),
(100,15,'طيرمانة5 (6 اشخاص)','طيرمانة5 (تتسع6 اشخاص)','طيرمانة مجلس عربي\r\n\r\n**الوصف:**\r\n\r\nطيرمانة مجلس عربي صُممت بعناية لتوفر أجواءً مريحة ومناسبة للتجمعات العائلية واستقبال الضيوف. تضم **مجلسًا عربيًا واسعًا** مجهزًا بـ **6 مداكئ مريحة**، بالإضافة إلى **شاشة ذكية** لمزيد من الترفيه، و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتوفير تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع\r\n* 🛋️ 6 مداكئ مريحة\r\n* 📺 شاشة ذكية\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[]',1,'active','2026-07-26 00:59:37','2026-07-26 19:41:53'),
(101,15,'طيرمانة6  (9 اشخاص)','طيرمانة6  (تتسع 9 اشخاص)','الوصف:\r\n\r\nطيرمانة مجلس عربي صُممت بعناية لتوفر أجواءً مريحة ومناسبة للتجمعات العائلية واستقبال الضيوف. تضم مجلسًا عربيًا واسعًامجهزًا بـ 9 مداكئ مريحة، بالإضافة إلى شاشة ذكية لمزيد من الترفيه، ودورة مياه خاصة مجهزة بكافة المستلزمات الأساسية، لتوفير تجربة تجمع مريحة ومتكاملة.\r\n\r\n المميزات\r\n\r\n 🏛️ مجلس عربي واسع\r\n 🛋️ 9 مداكئ مريحة\r\n 📺 شاشة ذكية\r\n 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-26-6a654f00d8426.png\",\"2026-07-26-6a654f00d90ae.png\"]',1,'active','2026-07-26 01:04:16','2026-07-26 20:23:09'),
(102,5,'جناح من غرفة نوم وصالة بمجلس','جناح من غرفة نوم وصالة بمجلس','الوصف:\r\n\r\nجناح فندقي أنيق يمنحك تجربة إقامة مريحة وهادئة، يتكون من غرفة نوم رئيسية تحتوي على سرير ماستر كبير، وصالة جلوس مستقلة مجهزة بجلسة عربية/شعبيّة أنيقة وشاشة تلفزيون، بالإضافة إلى شرفة (بلكونة) للإطلالة والاسترخاء. يشتمل الجناح على دولاب ملابس واسع، ثلاجة، هاتف أرضي، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم رئيسية (سرير ماستر كبير)\r\n🛋️ صالة جلوس مستقلة (جلسة عربية/شعبية أنيقة)\r\n🪴 شرفة (بلكونة)\r\n📺 شاشة تلفزيون\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة\r\n📞 هاتف أرضي\r\n🛜 إنترنت مجاني (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-26-6a6669702a406.png\",\"2026-07-26-6a6669702ab01.png\",\"2026-07-26-6a6669702aea2.png\",\"2026-07-26-6a6669702b278.png\"]',1,'active','2026-07-26 21:09:20','2026-07-26 22:00:00'),
(103,5,'جناح من غرفة نوم وصالة بكنب','جناح من غرفة نوم وصالة بكنب','الوصف:\r\n\r\nجناح فندقي أنيق يمنحك تجربة إقامة مريحة وهادئة، يتكون من غرفة نوم رئيسية تحتوي على سرير ماستر كبير، وصالة جلوس مستقلة مجهزة بجلسة أنيقة وشاشة مسطحة (تلفزيون)، بالإضافة إلى شرفة (بلكونة) للإطلالة والاسترخاء. يشتمل الجناح على دولاب ملابس واسع، ثلاجة، هاتف أرضي، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، ودورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم رئيسية (سرير ماستر كبير)\r\n🛋️ صالة جلوس مستقلة (جلسة أنيقة)\r\n🪴 شرفة (بلكونة)\r\n📺 شاشة تلفزيون\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة\r\n📞 هاتف أرضي\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-26-6a667171e20d7.png\",\"2026-07-26-6a667171e2606.png\",\"2026-07-26-6a667171e2846.png\",\"2026-07-26-6a667171e2a26.png\",\"2026-07-26-6a667171e2c43.png\"]',1,'active','2026-07-26 21:43:29','2026-07-26 22:01:39'),
(105,5,'غرفة نوم بسريرين منفصلين و جلسة جانبية','غرفة نوم بسريرين منفصلين مع جلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسريرين منفصلين بجودة فندقية ممتازة. تضم الغرفة جلسة جانبية مريحة للاسترخاء، وتلفزيون بشاشة مسطحة، بالإضافة إلى ثلاجة صغيرة، هاتف أرضي، ودورة مياه واسعة تجمع بين المرحاض العربي والأوروبي بكافة المستلزمات الأساسية، مع توفر إنترنت مجاني (Wi-Fi).\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان بجودة فندقية ممتازة\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n🧊 ثلاجة صغيرة\r\n📞 هاتف أرضي\r\n🚿 دورة مياه واسعة (حمام عربي وأوروبي)\r\n🛜 إنترنت مجاني (Wi-Fi)',NULL,'per_night',2,'[\"2026-07-26-6a6674f576713.png\",\"2026-07-26-6a6674f576c40.png\",\"2026-07-26-6a6674f576e8c.png\"]',1,'active','2026-07-26 21:58:29','2026-07-26 22:08:49'),
(106,5,'غرفة نوم بسريرين منفصلين و جلسة','غرفة نوم بسريرين منفصلين و جلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسريرين منفصلين بجودة فندقية ممتازة. تضم الغرفة جلسة جانبية مريحة للاسترخاء، وشاشة تلفزيون مسطحة، بالإضافة إلى دولاب ملابس واسع، ثلاجة صغيرة، هاتف أرضي للتواصل، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، مع دورة مياه خاصة بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان بجودة فندقية ممتازة\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📞 هاتف أرضي\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة بالمستلزمات الأساسية',NULL,'per_night',2,'[\"2026-07-27-6a66781d919de.png\",\"2026-07-27-6a66781d91f92.png\",\"2026-07-27-6a66781d92227.png\",\"2026-07-27-6a66781d9242c.png\"]',1,'active','2026-07-26 22:11:57','2026-07-26 22:12:35'),
(107,5,'غرفة نوم بسريرين وجلسة جانبية','غرفة نوم بسريرين وجلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة في هذه الغرفة المجهزة بسريرين منفصلين بجودة فندقية ممتازة. تضم الغرفة جلسة جانبية مريحة للاسترخاء، وشاشة تلفزيون مسطحة، بالإضافة إلى دولاب ملابس واسع، ثلاجة صغيرة، هاتف أرضي للتواصل، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، مع دورة مياه خاصة تجمع بين المرحاض العربي والأوروبي بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان بجودة فندقية ممتازة\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📞 هاتف أرضي\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة واسعة (حمام عربي وأوروبي)',NULL,'per_night',2,'[\"2026-07-27-6a6679d13d496.png\",\"2026-07-27-6a6679d13d9a2.png\",\"2026-07-27-6a6679d13db7e.png\"]',1,'active','2026-07-26 22:19:13','2026-07-26 22:19:13'),
(108,5,'غرفة بسرير ماستر(كبير) وجلسة جانبية','غرفة بسرير ماستر(كبير) وجلسة جانبية','الوصف:\r\n\r\nاستمتع بإقامة مريحة وهادئة في هذه الغرفة المجهزة بسرير ماستر كبير بجودة فندقية ممتازة. تضم الغرفة جلسة جانبية مريحة للاسترخاء، وشاشة تلفزيون مسطحة مع تسريحة بمرآة، بالإضافة إلى دولاب ملابس واسع، ثلاجة صغيرة، هاتف أرضي للتواصل، ومودم إنترنت خاص لضمان اتصال سريع ومستمر (Wi-Fi)، مع دورة مياه خاصة تجمع بين المرحاض العربي والأوروبي بكافة مستلزماتها الأساسية.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير ماستر كبير\r\n🛋️ جلسة جانبية مريحة\r\n📺 شاشة تلفزيون\r\n👗 دولاب ملابس واسع\r\n🧊 ثلاجة صغيرة\r\n📞 هاتف أرضي\r\n🛜 مودم إنترنت خاص (Wi-Fi)\r\n🚿 دورة مياه خاصة واسعة (حمام عربي وأوروبي)',NULL,'per_night',2,'[\"2026-07-27-6a667a990a081.png\",\"2026-07-27-6a667a990a601.png\",\"2026-07-27-6a667a990a859.png\"]',1,'active','2026-07-26 22:22:33','2026-07-26 22:22:33'),
(109,16,'طيرمانة (ملكي)','طيرمانة (ملكي)','الوصف:\r\n\r\nاستمتع بتجربة استضافة راقية وفاخرة في هذا المجلس الملكي الواسع المصمم بالتراث الشعبي الأنيق. يحتوي المجلس على جلسات أرضية مرتفعة وفاخرة تتسع لأعداد كبيرة، مع طاولات ضيافة أنيقة تتوسط المكان، وشاشة تلفزيون مسطحة، مع إضاءة متكاملة وتصميم يدخل فيه الزجاج ليعطي إطلالة مميزة وأجواء دافئة ومريحة للمناسبات والتجمعات.\r\n\r\nالمميزات:\r\n\r\n🛋️ جلسات شعبية/ملكية واسعة تتسع لأعداد كبيرة\r\n☕ طاولات ضيافة أنيقة\r\n📺 شاشة تلفزيون مسطحة\r\n🪟 واجهات زجاجية وإضاءة ممتازة\r\n❄️ تكييف وأجواء مريحة',NULL,'per_night',26,'[\"2026-07-27-6a667ca569eeb.png\",\"2026-07-27-6a667ca56a773.png\"]',1,'active','2026-07-26 22:31:17','2026-07-26 22:31:17'),
(110,19,'شاليه أوليف الفاخر','شاليه أوليف الفاخر','الوصف:\r\n\r\nاستمتع بتجربة إقامة فاخرة ولحظات ساحرة من الاسترخاء والخصوصية التامة في شاليه أوليف. يتميز الشاليه بحوش خارجي واسع مجهز بعشب صناعي وجلسات عصرية وسط أجواء الطبيعة الخلابة، تشمل طاولة طعام خارجية للتجمعات العائلية، جلسة عريشة (برجولة) دافئة مُزينة بالإضاءات الخافتة والنباتات، وركن مخصص للباربكيو والشواء، بالإضافة إلى شلال مائي صخري يضفي أجواءً من الهدوء والجمال. كما يضم الشاليه صالتين واسعتين بأثاث مودرن أنيق، وغرفة نوم مريحة وأنيقة تكتمل فيها تجربة الرفاهية مع وجود جاكوزي وساونا للاسترخاء والتجديد.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم مريحة وأنيقة\r\n♨️ جاكوزي وساونا للاسترخاء والتجديد\r\n🛋️ صالتان داخليتان واسعتان بأثاث مودرن فاخر\r\n🌿 جلسات خارجية وسط الطبيعة الخلابة وحوش بعشب صناعي\r\n🛖 جلسة خارجية مظللة (برجولة) بديكورات نباتية وإضاءة دافئة\r\n🍖 ركن مخصص للباربكيو والشواء\r\n🍽️ طاولة طعام خارجية لتجمعاتكم العائلية\r\n🌊 شلال مائي يضيف جواً من الاسترخاء\r\n❄️ تكييف كامل وأجواء مريحة في جميع المرفقات',NULL,'per_hour',25,'[\"2026-07-28-6a68d88e5e311.png\",\"2026-07-28-6a68d88e5e856.png\",\"2026-07-28-6a68d88e5ead7.png\",\"2026-07-28-6a68d88e5ed70.png\",\"2026-07-28-6a68d88e5ef1d.png\",\"2026-07-28-6a68d88e5f213.png\",\"2026-07-28-6a68d88e5f4e2.png\",\"2026-07-28-6a68d88e5f799.png\",\"2026-07-28-6a68d88e5fa81.png\"]',1,'active','2026-07-27 23:15:56','2026-07-28 17:27:58'),
(111,20,'شالية الواحة الخضراء','شالية الواحة الخضراء','الوصف:\r\n\r\nاستمتع بتجربة إقامة استثنائية تجمع بين الترفيه والهدوء في شاليهات الواحة الخضراء. يتيح لك الشاليه الاسترخاء في الدور الأول مع مسبح دافئ مزود بهيدرومساج، غرفة سونا، وصالة ألعاب (بلياردو وتنس) مطلة على المسبح. ويحتوي الدور الثاني على غرف نوم عائلية مريحة، مطبخ أمريكي، وركن للقهوة. وفي الدور الثالث، تتألق الجلسة الخارجية بحديقة معلقة مع ألعاب أطفال، شلال مضاء، وركن باربكيو للمشاوي. وتكتمل التجربة في الدور الرابع بـ ديوان فاخر بطلة بانورامية، وقاعة مجهزة لاستضافة أجمل المناسبات.\r\n\r\nالمميزات:\r\n\r\n🏊‍♂️ مسبح داخلي دافئ مع هيدرومساج وسونا\r\n🎱 صالة ألعاب ترفيهية (بلياردو وتنس طاولة)\r\n🛏️ غرف نوم وأجنحة عائلية مجهزة بالكامل\r\n🛋️ ديوان فاخر بجلسات ملكية وإطلالة بانورامية\r\n🎉 قاعة مناسبات وحفلات متكاملة ومجهزة\r\n🛝 حديقة معلقة بألعاب أطفال وشلال مضاء\r\n🍖 ركن باربكيو مخصص للمشاوي\r\n☕ مطبخ أمريكي حديث وركن للقهوة',NULL,'per_slot',30,'[\"2026-07-28-6a68e701f1ff5.png\",\"2026-07-28-6a68e701f2467.png\",\"2026-07-28-6a68e701f2567.png\",\"2026-07-28-6a68e701f26c5.png\",\"2026-07-28-6a68e701f27cd.png\",\"2026-07-28-6a68e701f28d7.png\",\"2026-07-28-6a68e701f2a0b.png\",\"2026-07-28-6a68e701f2b3d.png\",\"2026-07-28-6a68e701f2c6f.png\",\"2026-07-28-6a68e701f2d83.png\",\"2026-07-28-6a68e701f2ec6.png\",\"2026-07-28-6a68e701f2ff0.png\",\"2026-07-28-6a68e701f3153.png\",\"2026-07-28-6a68e701f3288.png\",\"2026-07-28-6a68e701f33d6.png\",\"2026-07-28-6a68e701f3520.png\",\"2026-07-28-6a68e701f3686.png\",\"2026-07-28-6a68e701f37f9.png\"]',1,'active','2026-07-28 18:29:37','2026-07-28 18:38:54'),
(112,6,'غرفة بسريرين وجلسة جانبية','غرفة بسريرين وجلسة جانبية','الوصف:\r\n\r\nغرفة مريحة تحتوي على سريرين منفصلين وجلسة جانبية مريحة، مجهزة بشاشة ذكية وطاولة جانبية، بالإضافة إلى إنترنت (Wi-Fi) ودورة مياه خاصة مزودة بكافة المستلزمات الأساسية لإقامة متكاملة.\r\n\r\nالمميزات:\r\n\r\n🛏️ سريران منفصلان\r\n🛋️ جلسة جانبية مع طاولة\r\n📺 شاشة ذكية\r\n🛜 إنترنت (Wi-Fi) مجاني\r\n🚿 دورة مياه خاصة بالمستلزمات',NULL,'per_night',2,'[\"2026-07-28-6a6913829d6c4.png\",\"2026-07-28-6a6913829de8a.png\"]',3,'active','2026-07-28 21:39:30','2026-07-28 21:45:24'),
(113,6,'غرفة بثلاثة أسرة منفصلة وجلسة جانبية','غرفة بثلاثة أسرة منفصلة وجلسة جانبية','الوصف:\r\n\r\nغرفة واسعة ومشرقة تحتوي على ثلاثة أسرة فردية، مجهزة بشاشة معلقة وطاولة جانبية، وتتميز بنوافذ كبيرة بإطلالة مفتوحة تسمح بدخول الضوء الطبيعي، بالإضافة إلى إنترنت (Wi-Fi) مجاني ودورة مياه خاصة توفر لك إقامة مريحة.\r\n\r\nالمميزات:\r\n\r\n🛏️ ثلاثة أسرة فردية خشبية\r\n🪟 نوافذ واسعة بإطلالة مميزة وإضاءة طبيعية\r\n📺 شاشة \r\n🛜 إنترنت (Wi-Fi) \r\n🗄️ كومودينو\r\n🚿 دورة مياه خاصة',NULL,'per_night',2,'[\"2026-07-28-6a6916cdb7531.png\",\"2026-07-28-6a691738dc8ee.png\"]',2,'active','2026-07-28 21:53:33','2026-07-28 21:55:20'),
(114,6,'غرفة عائلي بسرير ماستر وجلسة جانبية','غرفة عائلي بسرير ماستر وجلسة جانبية','الوصف:\r\n\r\nغرفة عائلية مريحة تحتوي على سرير ماستر وجلسة جانبية مع طاولة، و شاشة، إنترنت (Wi-Fi) ، ودورة مياه خاصة لتوفير إقامة هادئة ومميزة.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير ماستر كبير (زوجي)\r\n🛋️ جلسة جانبية مع طاولة\r\n🪟 نافذة بإطلالة جبلية وطبيعية\r\n📺 شاشة \r\n🛜 إنترنت (Wi-Fi) \r\n🚿 دورة مياه خاصة',NULL,'per_night',2,'[\"2026-07-28-6a69183f151ea.png\",\"2026-07-28-6a69183f15b7b.png\"]',1,'active','2026-07-28 21:59:43','2026-07-28 21:59:43'),
(115,16,'خيمة (طيرمانة) VIP بحجم كبير','خيمة (طيرمانة) VIP بحجم كبير','الوصف:\r\n\r\nطيرمانة VIP واسعة ومريحة، تتميز بجلسة دائرية فخمة ومريحة ومجهزة بطاولات زجاجية أنيقة وشاشة كبيرة لتجربة ترفيهية متكاملة، مع تصميم داخلي مميز يجمع بين الأصالة والراحة، بالإضافة إلى إنترنت مجاني (Wi-Fi) ودورة مياه خاصة.\r\n\r\nالمميزات:\r\n\r\n🛋️ جلسة VIP دائرية واسعة ومريحة\r\n📺 شاشة ذكية كبيرة\r\n🪟 طيرمانة\r\n🛜 إنترنت (Wi-Fi) \r\n🚿 دورة مياه خاصة\r\n🪑 طاولات زجاجية أنيقة',NULL,'per_night',10,'[\"2026-07-29-6a691a27dc0c5.png\",\"2026-07-29-6a691a27dc8a4.png\",\"2026-07-29-6a691a27dccdb.png\"]',1,'active','2026-07-28 22:07:51','2026-07-28 22:07:51'),
(116,13,'غرفة ملكي بسرير عائلي وصالة','غرفة ملكي بسرير عائلي وصالة','الوصف:\r\n\r\nغرفة نوم ملكي فاخرة بتصميم مودرن أنيق، تحتوي على سرير ماستر كينج فاخر، تسريحة، وشيزلونج استرخاء، بالإضافة إلى شاشة ذكية ، إنترنت مجاني، ودورة مياه خاصة لإقامة هادئة ومميزة.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير ماستر كينج فاخر\r\n🪞 تسريحة مع مرآة\r\n🛋️ شيزلونج (كرسي استرخاء)\r\n📺 شاشة معلقة\r\n💡 إضاءة مخفية وتصميم مودرن\r\n🛜 إنترنت (Wi-Fi) مجاني\r\n🚿 دورة مياه خاصة',NULL,'per_night',2,'[\"2026-07-29-6a6a0b05ca42d.png\",\"2026-07-29-6a6a0b05ca9d9.png\",\"2026-07-29-6a6a0b05cab9b.png\"]',1,'active','2026-07-29 15:15:33','2026-07-29 15:15:33'),
(117,13,'غرفة ديلوكس كينج','غرفة ديلوكس كينج','الوصف:\r\n\r\nغرفة نوم عرسان واسعة بتصميم مودرن أنيق، تحتوي على سرير ماستر كينج مع جلسة صالة جانبية مريحة وطاولة خشبية، وشاشة ذكية، إنترنت مجاني ، ودورة مياه خاصة لإقامة هادئة ومميزة.\r\n\r\nالمميزات:\r\n\r\n🛏️ سرير ماستر كينج\r\n🛋️ جلسة صالة جانبية بكنب فاخر\r\n📺 شاشة معلقة\r\n💡 إضاءة مخفية وديكور مودرن\r\n🛜 إنترنت (Wi-Fi) مجاني\r\n🚿 دورة مياه خاصة',NULL,'per_night',2,'[\"2026-07-29-6a6a0dc601d19.png\",\"2026-07-29-6a6a0dc602186.png\"]',1,'active','2026-07-29 15:27:18','2026-07-29 15:27:18'),
(118,14,'طيرمانة فاخرة','Private Lounge with Private Bathroom','استمتع بأجواء راقية في **الطيرمانة الفاخرة 602**، المصممة لتوفير تجربة مريحة تناسب التجمعات العائلية والمقايل واستقبال الضيوف. تضم **مجلسًا عربيًا فاخرًا يتسع لـ 9 أشخاص** مع جلسات أرضية مريحة، وديكور عصري، وإضاءة LED هادئة، وستائر أنيقة تضفي مزيدًا من الخصوصية، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتمنح الضيوف تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة LED.\r\n* 🪟 ستائر توفر الخصوصية.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل والتجمعات العائلية واستقبال الضيوف.',NULL,'per_night',8,'[\"2026-07-29-6a6a464350270.png\",\"2026-07-29-6a6a4643508ee.png\"]',1,'active','2026-07-29 19:28:19','2026-07-29 19:42:57'),
(119,14,'طيرمانة','طيرمانة','استمتع بأجواء راقية في **الطيرمانة الفاخرة المصممة لتوفير تجربة مريحة تناسب التجمعات العائلية والمقايل واستقبال الضيوف. تضم **مجلسًا عربيًا فاخرًا يتسع لـ 8 أشخاص** مع جلسات أرضية مريحة، وديكور عصري، وإضاءة LED هادئة، وستائر أنيقة تضفي مزيدًا من الخصوصية، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتمنح الضيوف تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة LED.\r\n* 🪟 ستائر توفر الخصوصية.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل والتجمعات العائلية واستقبال الضيوف.',NULL,'per_night',8,'[\"2026-07-29-6a6a48b7e7541.png\",\"2026-07-29-6a6a48b7e7c9f.png\"]',1,'active','2026-07-29 19:38:47','2026-07-29 19:48:35'),
(120,14,'طيرمانة فاخرة','طيرمانة فاخرة','استمتع بأجواء راقية في **الطيرمانة الفاخرة المصممة لتوفير تجربة مريحة تناسب التجمعات العائلية والمقايل واستقبال الضيوف. تضم **مجلسًا عربيًا فاخرًا يتسع لـ 10أشخاص** مع جلسات أرضية مريحة، وديكور عصري، وإضاءة LED هادئة، وستائر أنيقة تضفي مزيدًا من الخصوصية، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتمنح الضيوف تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 10 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة LED.\r\n* 🪟 ستائر توفر الخصوصية.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل والتجمعات العائلية واستقبال الضيوف.',NULL,'per_night',9,'[\"2026-07-29-6a6a4ad8969c9.png\",\"2026-07-29-6a6a4ad896ec3.png\"]',1,'active','2026-07-29 19:47:52','2026-07-29 19:47:52'),
(121,14,'طيرمانة فاخرة','طيرمانة فاخرة','استمتع بأجواء راقية في **الطيرمانة الفاخرة، المصممة لتوفير تجربة مريحة تناسب التجمعات العائلية والمقايل واستقبال الضيوف. تضم **مجلسًا عربيًا فاخرًا يتسع لـ 10 أشخاص** مع جلسات أرضية مريحة، وديكور عصري، وإضاءة LED هادئة، وستائر أنيقة تضفي مزيدًا من الخصوصية، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتمنح الضيوف تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 10 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة LED.\r\n* 🪟 ستائر توفر الخصوصية.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل والتجمعات العائلية واستقبال الضيوف.',NULL,'per_night',9,'[\"2026-07-29-6a6a4be0eb26b.png\",\"2026-07-29-6a6a4be0ec62e.png\"]',1,'active','2026-07-29 19:52:16','2026-07-29 19:52:16'),
(122,14,'طيرمانة فاخرة','طيرمانة فاخرة','استمتع بأجواء راقية في **الطيرمانة الفاخرة، المصممة لتوفير تجربة مريحة تناسب التجمعات العائلية والمقايل واستقبال الضيوف. تضم **مجلسًا عربيًا فاخرًا يتسع لـ 10 أشخاص** مع جلسات أرضية مريحة، وديكور عصري، وإضاءة LED هادئة، وستائر أنيقة تضفي مزيدًا من الخصوصية، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتمنح الضيوف تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 10 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة LED.\r\n* 🪟 ستائر توفر الخصوصية.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل والتجمعات العائلية واستقبال الضيوف.',NULL,'per_night',9,'[\"2026-07-29-6a6a4cec10c84.png\",\"2026-07-29-6a6a4cec11415.png\"]',1,'active','2026-07-29 19:56:44','2026-07-29 19:56:44'),
(123,14,'طيرمانة فاخرة','طيرمانة فاخرة','استمتع بأجواء راقية في **الطيرمانة الفاخرة، المصممة لتوفير تجربة مريحة تناسب التجمعات العائلية والمقايل واستقبال الضيوف. تضم **مجلسًا عربيًا فاخرًا يتسع لـ 12 أشخاص** مع جلسات أرضية مريحة، وديكور عصري، وإضاءة LED هادئة، وستائر أنيقة تضفي مزيدًا من الخصوصية، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، لتمنح الضيوف تجربة تجمع مريحة ومتكاملة.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 12 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة LED.\r\n* 🪟 ستائر توفر الخصوصية.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👨‍👩‍👧‍👦 مناسبة للمقايل والتجمعات العائلية واستقبال الضيوف.',NULL,'per_night',10,'[\"2026-07-29-6a6a4e7607010.png\"]',1,'active','2026-07-29 20:03:18','2026-07-29 20:03:18'),
(124,21,'مجلس عربي','مجلس عربي','## مجلس عربي فاخر\r\n\r\n### الوصف:\r\n\r\nاستمتع بأجواء تجمع راقية في **المجلس العربي الفاخر**، المصمم بعناية ليوفر الراحة والخصوصية للمقايل واستقبال الضيوف. يتسع المجلس **لـ 15 شخصًا**، ويضم جلسات عربية أرضية مريحة موزعة بشكل عملي، مع نوافذ كبيرة تسمح بدخول الإضاءة الطبيعية، وتصميم داخلي أنيق يمنح المكان أجواءً هادئة ومريحة، بالإضافة إلى **تلفزيون بشاشة مسطحة** وطاولات خدمة، ليكون خيارًا مثاليًا للتجمعات العائلية واللقاءات الاجتماعية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع.\r\n* 👥 يتسع لـ 15 شخصًا.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 🪟 نوافذ كبيرة بإضاءة طبيعية.\r\n* 💡 تصميم عصري وأجواء هادئة.\r\n* ☕ طاولات خدمة موزعة داخل المجلس.\r\n* ✨ مناسب للمقايل والتجمعات واستقبال الضيوف.',NULL,'per_night',15,'[\"2026-07-29-6a6a52df8f469.png\"]',1,'active','2026-07-29 20:22:07','2026-07-29 20:22:07'),
(125,21,'مجلس عربي','مجلس عربي','استمتع بأجواء مريحة وراقية في **المجلس العربي الفاخر**، المصمم لاستقبال الضيوف والمقايل. يتسع المجلس **لـ 8 أشخاص**، ويضم جلسات عربية أرضية مريحة بتصميم أنيق يوفر أقصى درجات الراحة، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ليمنح الضيوف تجربة تجمع مريحة وخصوصية عالية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة هادئة.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👥 مناسب للمقايل واستقبال الضيوف.',NULL,'per_night',8,'[\"2026-07-29-6a6a552e7c148.png\",\"2026-07-29-6a6a552e7c4cc.png\",\"2026-07-29-6a6a552e7c61c.png\"]',1,'active','2026-07-29 20:31:58','2026-07-29 20:37:01'),
(126,21,'مجلس عربي','مجلس عربي','استمتع بأجواء مريحة وراقية في **المجلس العربي الفاخر**، المصمم لاستقبال الضيوف والمقايل. يتسع المجلس **لـ 8 أشخاص**، ويضم جلسات عربية أرضية مريحة بتصميم أنيق يوفر أقصى درجات الراحة، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ليمنح الضيوف تجربة تجمع مريحة وخصوصية عالية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة هادئة.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👥 مناسب للمقايل واستقبال الضيوف',NULL,'per_night',8,'[\"2026-07-29-6a6a55af56adf.png\"]',1,'active','2026-07-29 20:34:07','2026-07-29 20:36:37'),
(127,21,'مجلس عربي','مجلس عربي','استمتع بأجواء مريحة وراقية في **المجلس العربي الفاخر**، المصمم لاستقبال الضيوف والمقايل. يتسع المجلس **لـ 8 أشخاص**، ويضم جلسات عربية أرضية مريحة بتصميم أنيق يوفر أقصى درجات الراحة، بالإضافة إلى **تلفزيون بشاشة مسطحة** و**دورة مياه خاصة** مجهزة بكافة المستلزمات الأساسية، ليمنح الضيوف تجربة تجمع مريحة وخصوصية عالية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة هادئة.\r\n* 🚿 دورة مياه خاصة مجهزة بكافة المستلزمات الأساسية.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👥 مناسب للمقايل واستقبال الضيوف.',NULL,'per_night',8,'[\"2026-07-29-6a6a562175b7f.png\"]',1,'active','2026-07-29 20:36:01','2026-07-29 20:36:01'),
(128,21,'مجلس عربي واسع','مجلس عربي واسع','مجلس عربي واسع لـ 16 شخص\r\n الوصف\r\nاستمتع بأجواء مريحة في **مجلس عربي واسع يتسع حتى 16 شخصًا**، صُمم ليمنح الضيوف مساحة رحبة وخصوصية عالية، ويتميز بجلسات عربية أرضية مريحة موزعة حول المجلس، مع ديكور أنيق، وإضاءة هادئة، ونوافذ كبيرة تسمح بدخول الضوء الطبيعي، بالإضافة إلى شاشة تلفزيون وطاولات ضيافة، ليكون خيارًا مثاليًا للمقايل، واستقبال الضيوف، واللقاءات الاجتماعية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع يتسع لـ 16 شخصًا.\r\n* 🛋️ جلسات عربية أرضية مريحة.\r\n* 📺 شاشة تلفزيون.\r\n* 🌅 نوافذ كبيرة بإضاءة طبيعية.\r\n* ❄️ أجواء هادئة وتهوية جيدة.\r\n* ✨ تصميم داخلي أنيق.\r\n* 🧹 نظافة وخصوصية عالية.\r\n* ☕ مناسب للمقايل واستقبال الضيوف واللقاءات الاجتماعية.',NULL,'per_night',15,'[\"2026-07-29-6a6a587913d66.png\"]',1,'active','2026-07-29 20:46:01','2026-07-29 20:46:01'),
(129,21,'مجلس عربي','مجلس عربي','مجلس عربي فاخر\r\n\r\nالوصف:\r\n\r\nاستمتع بأجواء مريحة وراقية في **المجلس العربي الفاخر**، المصمم لاستقبال الضيوف والمقايل. يتسع المجلس **لـ 8 أشخاص**، ويضم جلسات عربية أرضية مريحة بتصميم أنيق يوفر أقصى درجات الراحة، بالإضافة إلى **تلفزيون بشاشة مسطحة**، ليمنح الضيوف تجربة تجمع مريحة وخصوصية عالية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة هادئة.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👥 مناسب للمقايل واستقبال الضيوف.',NULL,'per_night',8,'[]',1,'active','2026-07-29 20:49:01','2026-07-29 20:54:01'),
(130,21,'مجلس عربي','مجلس عربي','مجلس عربي فاخر\r\nالوصف:\r\n\r\nاستمتع بأجواء مريحة وراقية في **المجلس العربي الفاخر**، المصمم لاستقبال الضيوف والمقايل. يتسع المجلس **لـ 8 أشخاص**، ويضم جلسات عربية أرضية مريحة بتصميم أنيق يوفر أقصى درجات الراحة، بالإضافة إلى **تلفزيون بشاشة مسطحة**، ليمنح الضيوف تجربة تجمع مريحة وخصوصية عالية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة هادئة.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👥 مناسب للمقايل واستقبال الضيوف.',NULL,'per_night',8,'[\"2026-07-29-6a6a59d6ae267.png\"]',1,'active','2026-07-29 20:51:50','2026-07-29 20:53:47'),
(131,21,'مجلس عربي','مجلس عربي','مجلس عربي فاخر\r\nالوصف:\r\n\r\nاستمتع بأجواء مريحة وراقية في **المجلس العربي الفاخر**، المصمم لاستقبال الضيوف والمقايل. يتسع المجلس **لـ 8 أشخاص**، ويضم جلسات عربية أرضية مريحة بتصميم أنيق يوفر أقصى درجات الراحة، بالإضافة إلى **تلفزيون بشاشة مسطحة**، ليمنح الضيوف تجربة تجمع مريحة وخصوصية عالية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة هادئة.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👥 مناسب للمقايل واستقبال الضيوف.',NULL,'per_night',8,'[\"2026-07-29-6a6a5a3e21cdf.png\"]',1,'active','2026-07-29 20:53:34','2026-07-29 20:53:34'),
(132,21,'مجلس عربي','مجلس عربي','الوصف:\r\n\r\nاستمتع بأجواء مريحة وراقية في **المجلس العربي الفاخر**، المصمم لاستقبال الضيوف والمقايل. يتسع المجلس **لـ 8 أشخاص**، ويضم جلسات عربية أرضية مريحة بتصميم أنيق يوفر أقصى درجات الراحة، بالإضافة إلى **تلفزيون بشاشة مسطحة**، ليمنح الضيوف تجربة تجمع مريحة وخصوصية عالية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي فاخر.\r\n* 👥 يتسع لـ 8 أشخاص.\r\n* 🛋️ جلسات أرضية مريحة.\r\n* 📺 تلفزيون بشاشة مسطحة.\r\n* 💡 تصميم عصري وإضاءة هادئة.\r\n* ✨ أجواء هادئة ونظيفة.\r\n* 👥 مناسب للمقايل واستقبال الضيوف.',NULL,'per_night',8,'[\"2026-07-29-6a6a5ac3cddf6.png\"]',1,'active','2026-07-29 20:55:47','2026-07-29 20:55:47'),
(133,21,'مجلس عربي','مجلس عربي','### مجلس عربي واسع لـ 16 شخص\r\n\r\n### الوصف\r\n\r\nاستمتع بأجواء مريحة في **مجلس عربي واسع يتسع حتى 16 شخصًا**، صُمم ليمنح الضيوف مساحة رحبة وخصوصية عالية، ويتميز بجلسات عربية أرضية مريحة موزعة حول المجلس، مع ديكور أنيق، وإضاءة هادئة، ونوافذ كبيرة تسمح بدخول الضوء الطبيعي، بالإضافة إلى شاشة تلفزيون وطاولات ضيافة، ليكون خيارًا مثاليًا للمقايل، واستقبال الضيوف، واللقاءات الاجتماعية.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع يتسع لـ 16 شخصًا.\r\n* 🛋️ جلسات عربية أرضية مريحة.\r\n* 📺 شاشة تلفزيون.\r\n* 🌅 نوافذ كبيرة بإضاءة طبيعية.\r\n* ❄️ أجواء هادئة وتهوية جيدة.\r\n* ✨ تصميم داخلي أنيق.\r\n* 🧹 نظافة وخصوصية عالية.\r\n* ☕ مناسب للمقايل واستقبال الضيوف واللقاءات الاجتماعية.',NULL,'per_night',15,'[\"2026-07-29-6a6a5bbe290eb.png\"]',1,'active','2026-07-29 20:59:58','2026-07-29 20:59:58'),
(134,21,'مجلس عربي واسع للمناسبات','مجلس عربي واسع للمناسبات','الوصف:\r\n\r\nاستمتع بأجواء رحبة ومميزة في **المجلس العربي للمناسبات**، المصمم لاستقبال المناسبات والمقايل واللقاءات الاجتماعية. يتسع المجلس **لأكثر من 50 شخصًا**، ويتميز بجلسات عربية أرضية مريحة موزعة بعناية لتوفير أقصى درجات الراحة، مع تصميم داخلي أنيق، وإضاءة هادئة، وشاشة تلفزيون، ومساحة واسعة تمنح الضيوف تجربة مريحة ومناسبة لمختلف المناسبات.\r\n\r\n### المميزات\r\n\r\n* 🏛️ مجلس عربي واسع.\r\n* 👥 يتسع لأكثر من 50 شخصًا.\r\n* 🛋️ جلسات عربية أرضية مريحة.\r\n* 📺 شاشة تلفزيون.\r\n* 💡 تصميم أنيق وإضاءة هادئة.\r\n* ✨ مساحة واسعة وخصوصية عالية.\r\n* 🧹 نظافة وترتيب مميزان.\r\n* 🎉 مناسب للمناسبات، والمقايل، واستقبال الضيوف.',NULL,'per_night',50,'[\"2026-07-29-6a6a5c86aa898.png\"]',1,'active','2026-07-29 21:03:18','2026-07-29 21:03:18'),
(135,21,'خيمة بمجلس عربي','خيمة بمجلس عربي','خيمة بمجلس عربي يسع 6 اشخاص',NULL,'per_night',5,'[\"2026-07-29-6a6a5d1fa14b8.png\"]',1,'active','2026-07-29 21:05:51','2026-07-29 21:05:51'),
(136,22,'جناح عائلي غرفتين ومجلس','جناح عائلي غرفتين ومجلس','الوصف\r\nجناح عائلي مجهز بالكامل، يتكون من غرفتي نوم (غرفة رئيسية بسرير مزدوج، وغرفة إضافية بسريرين مفردين)، ومجلس عربي تقليدي واسع ومريح، بالإضافة إلى دواليب ملابس، تسريحة، ودورة مياه متكاملة، مع توفر مصعد وإنترنت مجاني (Wi-Fi) لتوفير إقامة مريحة ومثالية للعائلات.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم رئيسية بسرير مزدوج\r\n🛏️ غرفة نوم إضافية بسريرين مفردين\r\n🛋️ مجلس عربي تقليدي واسع\r\n🛗 مصعد كهربائي\r\n🛜 إنترنت مجاني\r\n🚪 خزانة ملابس خشبية\r\n🪞 تسريحة مع مرآة\r\n🚿 دورة مياه متكاملة (مرحاض إفرنجي وعربي)\r\n🪟 إطلالة بضوء طبيعي مع ستائر',NULL,'per_night',2,'[\"2026-07-30-6a6b5b827d323.png\",\"2026-07-30-6a6b5b827d916.png\",\"2026-07-30-6a6b5b827dc7b.png\",\"2026-07-30-6a6b5b827dfd8.png\",\"2026-07-30-6a6b5b827e1ac.png\",\"2026-07-30-6a6b5b827e752.png\"]',1,'active','2026-07-30 15:11:14','2026-07-30 15:11:14'),
(137,22,'جناح ملكي عائلي فاخر','جناح ملكي عائلي فاخر','الوصف:\r\n\r\nجناح عائلي واسع ومكتمل، يتكون من غرفتي نوم (غرفة تحتوي على 3 أسرة مفردة، وغرفة تحتوي على سريرين مفردين)، وصالة مجلس عربي واسع بتصميم أنيق، بالإضافة إلى شاشة ذكية، دواليب ملابس، ودورة مياه متكاملة، مع توفر مصعد وإنترنت مجاني (Wi-Fi) لإقامة عائلية مريحة ومميزة.\r\n\r\nالمميزات:\r\n\r\n🛏️ غرفة نوم تحتوي على 3 أسرة مفردة\r\n🛏️ غرفة نوم تحتوي على سريرين مفردين\r\n🛋️ مجلس عربي واسع\r\n📺 شاشة \r\n🛗 مصعد كهربائي\r\n🛜 إنترنت (Wi-Fi) مجاني\r\n🚪 خزانة ملابس وتسريحة\r\n🚿 دورة مياه خاصة\r\n🪟 إضاءة طبيعية وستائر فخمة',NULL,'per_night',2,'[\"2026-07-30-6a6b89d822470.png\",\"2026-07-30-6a6b89d822d1e.png\",\"2026-07-30-6a6b89d82315d.png\",\"2026-07-30-6a6b89d82366a.png\"]',1,'active','2026-07-30 18:28:56','2026-07-30 18:28:56');
/*!40000 ALTER TABLE `units` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `amenities`
--

DROP TABLE IF EXISTS `amenities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `amenities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name_ar` varchar(150) NOT NULL,
  `name_en` varchar(150) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=310 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `amenities`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `amenities` WRITE;
/*!40000 ALTER TABLE `amenities` DISABLE KEYS */;
INSERT INTO `amenities` VALUES
(141,'مصعد',NULL,NULL,NULL,'2026-07-06 17:52:49','2026-07-06 17:52:49'),
(154,'خيمة تتسع ل 15 شخص',NULL,NULL,NULL,'2026-07-06 17:57:12','2026-07-06 17:57:12'),
(190,'موقف سيارة',NULL,NULL,NULL,'2026-07-07 11:42:00','2026-07-07 11:42:00'),
(224,'خيمة تتسع ل15 شخص',NULL,NULL,NULL,'2026-07-07 21:59:52','2026-07-07 21:59:52'),
(240,'مطبخ',NULL,'ثلاجة',NULL,'2026-07-08 22:00:38','2026-07-08 22:00:38'),
(264,'مجلس',NULL,'16 شخص',NULL,'2026-07-24 23:11:02','2026-07-25 23:43:11'),
(265,'دورة مياه واحدة',NULL,NULL,NULL,'2026-07-24 23:11:02','2026-07-24 23:11:02'),
(267,'غرفة نوم',NULL,'سرير كينغ سايز\r\nجلسة جانبية',NULL,'2026-07-24 23:34:03','2026-07-25 21:58:40'),
(269,'3 مجالس',NULL,NULL,NULL,'2026-07-24 23:44:52','2026-07-24 23:44:52'),
(270,'طيرمانة',NULL,NULL,NULL,'2026-07-24 23:49:45','2026-07-24 23:49:45'),
(271,'صالة مع كنب',NULL,NULL,NULL,'2026-07-25 15:32:12','2026-07-25 15:32:12'),
(272,'غرفتين نوم',NULL,NULL,NULL,'2026-07-25 15:32:12','2026-07-25 15:32:12'),
(274,'غرفة نوم واحدة',NULL,NULL,NULL,'2026-07-25 16:36:19','2026-07-25 16:36:19'),
(275,'ثلاث غرف نوم',NULL,NULL,NULL,'2026-07-25 17:25:01','2026-07-25 17:25:01'),
(276,'صالة مع مجلس',NULL,NULL,NULL,'2026-07-25 17:28:47','2026-07-25 17:28:47'),
(277,'صالةمع مجلس',NULL,NULL,NULL,'2026-07-25 19:03:51','2026-07-25 19:03:51'),
(281,'دورة مياة','Bathroom','جاكوزي',NULL,'2026-07-25 19:23:21','2026-07-25 23:31:41'),
(282,'طيرمانة (مجلس)',NULL,NULL,NULL,'2026-07-25 19:56:56','2026-07-25 19:56:56'),
(284,'شرفة مطلة.',NULL,NULL,NULL,'2026-07-25 20:48:50','2026-07-25 20:48:50'),
(285,'غرفتا نوم',NULL,'سرير كينغ سايز\r\nسريران منفصلان',NULL,'2026-07-25 21:01:41','2026-07-25 21:01:41'),
(287,'صالة جلوس',NULL,NULL,NULL,'2026-07-25 21:01:41','2026-07-25 21:01:41'),
(288,'غرفتان',NULL,'سرير كينغ سايز\r\nسريران منفصلان',NULL,'2026-07-25 21:15:34','2026-07-25 21:15:34'),
(289,'مجلس عربي',NULL,'يتسع ل8 اشخاص',NULL,'2026-07-25 21:15:34','2026-07-26 00:47:04'),
(290,'غرفة واحدة',NULL,'سرير كينغ سايز\r\nجلسة جانبية',NULL,'2026-07-25 22:04:11','2026-07-25 22:04:11'),
(292,'شرفة ( إطلالة)',NULL,NULL,NULL,'2026-07-25 22:26:02','2026-07-25 22:26:02'),
(294,'شرفة وإطلالة',NULL,NULL,NULL,'2026-07-25 22:49:10','2026-07-25 22:49:10'),
(296,'جاكوزي',NULL,NULL,NULL,'2026-07-25 23:09:19','2026-07-25 23:09:19'),
(300,'إطلالة بانورامية',NULL,NULL,NULL,'2026-07-25 23:35:57','2026-07-25 23:35:57'),
(302,'شرفة',NULL,NULL,NULL,'2026-07-25 23:49:12','2026-07-25 23:49:12'),
(303,'صالة طعام',NULL,NULL,NULL,'2026-07-26 00:24:01','2026-07-26 00:24:01'),
(304,'صالة  بمجلس',NULL,NULL,NULL,'2026-07-26 21:09:20','2026-07-26 21:09:20'),
(305,'صالة  بكنب',NULL,NULL,NULL,'2026-07-26 21:43:29','2026-07-26 21:43:29'),
(306,'جاكوزي / ساونا',NULL,NULL,NULL,'2026-07-27 23:15:56','2026-07-27 23:15:56'),
(307,'ركن شواء',NULL,NULL,NULL,'2026-07-27 23:15:56','2026-07-27 23:15:56'),
(309,'دورة مياة خاصة',NULL,NULL,NULL,'2026-07-29 20:31:58','2026-07-29 20:31:58');
/*!40000 ALTER TABLE `amenities` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `unit_amenity`
--

DROP TABLE IF EXISTS `unit_amenity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `unit_amenity` (
  `unit_id` bigint(20) unsigned NOT NULL,
  `amenity_id` bigint(20) unsigned NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`unit_id`,`amenity_id`),
  KEY `unit_amenity_rental_amenity_id_foreign` (`amenity_id`),
  CONSTRAINT `unit_amenity_rental_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `unit_amenity_rental_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `unit_amenity`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `unit_amenity` WRITE;
/*!40000 ALTER TABLE `unit_amenity` DISABLE KEYS */;
INSERT INTO `unit_amenity` VALUES
(13,270,1),
(14,282,1),
(17,270,1),
(19,270,1),
(24,265,1),
(24,267,1),
(25,265,1),
(25,267,1),
(26,265,1),
(26,267,1),
(26,276,1),
(27,265,1),
(27,267,1),
(28,265,1),
(28,267,1),
(29,240,1),
(29,265,1),
(29,272,1),
(29,277,1),
(30,240,1),
(30,265,1),
(30,271,1),
(30,272,1),
(31,265,1),
(31,267,1),
(32,265,1),
(32,267,1),
(33,265,1),
(33,267,1),
(34,265,1),
(34,267,1),
(35,265,1),
(35,267,1),
(36,265,1),
(36,267,1),
(37,265,1),
(37,267,1),
(38,265,1),
(38,272,1),
(38,276,1),
(39,141,1),
(39,265,1),
(39,267,1),
(40,265,1),
(40,275,1),
(41,265,1),
(41,267,1),
(42,265,1),
(42,267,1),
(43,265,1),
(43,272,1),
(44,265,1),
(44,274,1),
(45,265,1),
(45,274,1),
(46,265,1),
(46,274,1),
(47,265,1),
(47,274,1),
(48,141,1),
(48,265,1),
(48,272,1),
(49,141,1),
(49,264,1),
(49,265,1),
(49,267,1),
(50,141,1),
(50,264,1),
(50,265,1),
(50,267,1),
(51,141,1),
(51,265,1),
(51,267,1),
(52,141,1),
(52,265,1),
(52,267,1),
(53,141,1),
(53,265,1),
(53,267,1),
(54,141,1),
(54,265,1),
(54,267,1),
(58,281,1),
(58,288,1),
(58,289,1),
(59,281,1),
(59,288,1),
(59,289,1),
(60,141,1),
(60,265,1),
(60,267,1),
(61,141,1),
(61,265,1),
(61,267,1),
(62,141,1),
(62,265,1),
(62,267,1),
(62,271,1),
(63,141,1),
(63,265,1),
(63,267,1),
(63,271,1),
(64,281,1),
(64,288,1),
(64,289,1),
(64,294,1),
(64,303,1),
(65,265,1),
(65,287,1),
(65,288,1),
(65,289,1),
(66,141,1),
(66,240,1),
(66,265,1),
(66,271,1),
(66,272,1),
(67,141,1),
(67,264,1),
(67,265,1),
(67,267,1),
(68,281,1),
(68,285,2),
(68,287,1),
(69,281,1),
(69,284,1),
(70,141,1),
(70,240,1),
(70,265,1),
(70,271,1),
(70,272,1),
(71,288,1),
(71,296,1),
(71,302,1),
(72,141,1),
(72,240,1),
(72,264,1),
(72,265,1),
(72,272,1),
(73,264,1),
(74,141,1),
(74,240,1),
(74,264,1),
(74,265,1),
(74,267,1),
(75,281,1),
(75,288,1),
(76,141,1),
(76,240,1),
(76,264,1),
(76,265,1),
(76,267,1),
(77,141,1),
(77,240,1),
(77,265,1),
(77,267,1),
(77,269,1),
(78,141,1),
(78,270,1),
(79,281,1),
(79,290,1),
(79,296,1),
(79,300,1),
(80,281,1),
(80,288,1),
(81,275,1),
(81,281,1),
(82,281,1),
(82,290,1),
(83,281,1),
(83,290,1),
(84,281,1),
(84,290,1),
(85,281,1),
(85,290,1),
(86,281,1),
(86,290,1),
(87,281,1),
(87,290,1),
(87,294,1),
(88,281,1),
(88,290,1),
(89,281,1),
(89,290,1),
(90,281,1),
(90,290,1),
(91,281,1),
(91,290,1),
(91,292,1),
(92,265,1),
(92,290,1),
(93,265,1),
(93,290,1),
(94,265,1),
(94,290,1),
(95,265,1),
(95,290,1),
(96,265,1),
(96,289,1),
(97,264,1),
(97,265,1),
(98,265,1),
(98,289,1),
(99,265,1),
(99,289,1),
(100,265,1),
(100,289,1),
(101,264,1),
(102,265,1),
(102,267,1),
(102,304,1),
(103,265,1),
(103,267,1),
(103,305,1),
(105,265,1),
(105,267,1),
(106,265,1),
(106,267,1),
(107,265,1),
(107,267,1),
(108,265,1),
(108,267,1),
(109,270,1),
(110,274,1),
(110,306,1),
(110,307,1),
(112,265,1),
(112,267,1),
(113,265,1),
(113,267,1),
(114,265,1),
(114,267,1),
(116,265,1),
(116,267,1),
(117,265,1),
(117,267,1),
(125,309,1),
(136,141,1),
(136,264,1),
(136,272,1),
(137,264,1),
(137,265,1),
(137,272,1);
/*!40000 ALTER TABLE `unit_amenity` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `prices` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `priceable_id` bigint(20) unsigned NOT NULL,
  `priceable_type` varchar(255) NOT NULL,
  `price_type` varchar(30) NOT NULL DEFAULT 'default',
  `name` varchar(150) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `days_of_week` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`days_of_week`)),
  `price_yer_n` decimal(15,2) NOT NULL,
  `price_yer_s` decimal(15,2) NOT NULL,
  `price_sar` decimal(15,2) NOT NULL,
  `price_usd` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prices_priceable_idx` (`priceable_type`,`priceable_id`,`price_type`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prices`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `prices` WRITE;
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
INSERT INTO `prices` VALUES
(1,9,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,50000.00,30000.00,214.00,57.00,'2026-06-25 11:48:21','2026-06-25 11:48:21'),
(2,10,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,30000.00,15000.00,100.00,25.00,'2026-06-25 13:30:39','2026-06-25 13:30:39'),
(3,10,'App\\Models\\Unit','weekend',NULL,NULL,NULL,'[\"5\",\"6\"]',25000.00,13000.00,90.00,20.00,'2026-06-25 13:30:39','2026-06-25 13:30:39'),
(4,9,'App\\Models\\Unit','weekend',NULL,NULL,NULL,'[\"5\",\"6\"]',50000.00,30000.00,199.00,57.00,'2026-06-25 13:31:01','2026-06-25 13:31:01'),
(5,11,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,40000.00,25000.00,290.00,75.00,'2026-06-25 13:53:49','2026-06-25 13:53:49'),
(6,11,'App\\Models\\Unit','weekend',NULL,NULL,NULL,'[\"5\",\"6\"]',40000.00,25000.00,290.00,75.00,'2026-06-25 13:53:49','2026-06-25 13:56:29'),
(7,12,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,89999.99,75000.00,500.00,130.00,'2026-06-29 23:07:48','2026-07-06 20:15:41'),
(8,13,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,18000.00,43.00,12.00,'2026-07-05 07:20:36','2026-07-05 19:59:28'),
(9,14,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,70000.00,210000.00,500.00,132.00,'2026-07-05 20:46:31','2026-07-05 21:35:26'),
(12,17,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,24000.00,57.00,15.00,'2026-07-06 13:46:40','2026-07-06 14:02:33'),
(14,19,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,45000.00,107.00,29.00,'2026-07-06 14:16:42','2026-07-06 14:16:42'),
(19,24,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,30000.00,71.00,18.80,'2026-07-07 21:46:19','2026-07-07 21:46:19'),
(20,25,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,30000.00,71.00,18.86,'2026-07-07 22:25:12','2026-07-07 22:25:12'),
(21,26,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,45000.00,107.00,28.30,'2026-07-07 23:13:24','2026-07-07 23:13:24'),
(22,27,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,30000.00,71.00,18.86,'2026-07-07 23:31:19','2026-07-07 23:31:19'),
(23,28,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,45000.00,107.00,28.30,'2026-07-07 23:46:57','2026-07-07 23:46:57'),
(24,29,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,20000.00,60000.00,143.00,38.00,'2026-07-08 01:55:06','2026-07-08 01:55:06'),
(25,30,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,20000.00,60000.00,143.00,38.00,'2026-07-08 21:59:18','2026-07-08 21:59:18'),
(26,31,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,15000.00,107.00,29.00,'2026-07-10 20:15:44','2026-07-10 20:15:44'),
(27,32,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,15000.00,107.00,29.00,'2026-07-10 20:30:58','2026-07-10 20:30:58'),
(28,33,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,18000.00,129.00,34.00,'2026-07-10 20:44:31','2026-07-10 20:44:31'),
(29,34,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,4000.00,12000.00,28.50,7.99,'2026-07-13 21:27:03','2026-07-13 21:27:03'),
(30,35,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,15000.00,35.71,9.43,'2026-07-14 19:48:59','2026-07-14 19:48:59'),
(31,36,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,15000.00,35.71,9.43,'2026-07-14 19:56:17','2026-07-14 20:33:58'),
(32,37,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,18000.00,42.85,11.32,'2026-07-15 10:08:36','2026-07-15 10:08:36'),
(33,38,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,24000.00,57.14,15.00,'2026-07-15 10:14:47','2026-07-15 10:14:47'),
(34,39,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,18000.00,42.85,11.32,'2026-07-15 10:24:24','2026-07-15 10:24:24'),
(35,40,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,36000.00,85.71,22.64,'2026-07-15 13:38:35','2026-07-15 13:38:35'),
(36,41,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,0.00,0.00,0.00,'2026-07-16 20:27:33','2026-07-16 20:29:14'),
(37,42,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,0.00,0.00,0.00,'2026-07-16 21:05:30','2026-07-16 21:05:30'),
(38,42,'App\\Models\\Unit','weekend',NULL,NULL,NULL,'[\"5\",\"6\"]',0.00,0.00,0.00,0.00,'2026-07-16 21:05:30','2026-07-16 21:05:30'),
(39,43,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-19 21:37:07','2026-07-19 21:37:07'),
(40,44,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,0.00,0.00,0.00,'2026-07-19 21:44:21','2026-07-19 21:44:21'),
(41,45,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,4999.99,0.00,0.00,0.00,'2026-07-20 19:27:32','2026-07-20 19:27:32'),
(42,46,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,0.00,0.00,0.00,'2026-07-20 19:35:07','2026-07-20 19:35:52'),
(43,47,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,4999.99,0.00,0.00,0.00,'2026-07-20 19:44:46','2026-07-20 19:44:46'),
(44,48,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-20 20:35:24','2026-07-20 20:35:24'),
(45,49,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-21 09:02:10','2026-07-21 09:02:10'),
(46,50,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-21 16:00:28','2026-07-21 16:00:28'),
(47,51,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,0.00,0.00,0.00,'2026-07-21 16:49:23','2026-07-21 16:49:23'),
(48,52,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,0.00,0.00,0.00,'2026-07-21 16:53:16','2026-07-21 16:53:16'),
(49,53,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5999.99,0.00,0.00,0.00,'2026-07-21 17:53:24','2026-07-21 17:53:24'),
(50,54,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5999.99,0.00,0.00,0.00,'2026-07-21 17:53:30','2026-07-21 17:53:30'),
(51,55,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,0.00,0.00,0.00,'2026-07-21 20:33:08','2026-07-21 20:33:08'),
(52,56,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-21 21:50:24','2026-07-21 21:50:24'),
(53,57,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-21 22:09:29','2026-07-21 22:09:29'),
(54,58,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 04:26:08','2026-07-23 04:26:08'),
(55,59,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 04:46:05','2026-07-23 04:46:05'),
(56,60,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,0.00,0.00,0.00,'2026-07-23 13:59:25','2026-07-23 13:59:25'),
(57,61,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,11999.99,0.00,0.00,0.00,'2026-07-23 16:21:04','2026-07-23 16:21:04'),
(58,62,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,18000.00,0.00,0.00,0.00,'2026-07-23 16:30:24','2026-07-23 16:30:24'),
(59,63,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,0.00,0.00,0.00,'2026-07-23 16:35:08','2026-07-23 16:35:08'),
(60,64,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 19:57:27','2026-07-23 19:57:27'),
(61,65,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 20:24:50','2026-07-23 20:24:50'),
(62,66,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,30000.00,0.00,0.00,0.00,'2026-07-23 21:07:14','2026-07-23 21:07:14'),
(63,67,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,17999.99,0.00,0.00,0.00,'2026-07-23 21:27:30','2026-07-23 21:27:30'),
(64,68,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 21:38:03','2026-07-23 21:38:03'),
(65,69,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 21:58:23','2026-07-23 21:58:23'),
(66,70,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,30000.00,0.00,0.00,0.00,'2026-07-23 22:03:41','2026-07-23 22:03:41'),
(67,71,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 22:19:06','2026-07-23 22:19:06'),
(68,72,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,29999.99,0.00,0.00,0.00,'2026-07-23 22:23:54','2026-07-23 22:23:54'),
(69,73,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 22:26:55','2026-07-23 22:26:55'),
(70,74,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,25000.00,0.00,0.00,0.00,'2026-07-23 22:44:19','2026-07-23 22:44:19'),
(71,75,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-23 23:04:06','2026-07-23 23:04:06'),
(72,76,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,35000.00,0.00,0.00,0.00,'2026-07-23 23:05:22','2026-07-23 23:05:22'),
(73,77,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,35000.00,0.00,0.00,0.00,'2026-07-23 23:27:48','2026-07-23 23:27:48'),
(74,78,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,20000.00,0.00,0.00,0.00,'2026-07-23 23:49:43','2026-07-23 23:49:43'),
(75,79,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 00:25:13','2026-07-24 00:25:13'),
(76,80,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 00:34:21','2026-07-24 00:34:21'),
(77,81,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 00:48:56','2026-07-24 00:48:56'),
(78,82,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 00:58:46','2026-07-24 00:58:46'),
(79,83,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 01:06:39','2026-07-24 01:06:39'),
(80,84,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 01:13:33','2026-07-24 01:13:33'),
(81,85,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 01:19:42','2026-07-24 01:19:42'),
(82,86,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 01:24:42','2026-07-24 01:24:42'),
(83,87,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:05:00','2026-07-24 02:05:00'),
(84,88,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:09:03','2026-07-24 02:09:03'),
(85,89,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:14:54','2026-07-24 02:14:54'),
(86,90,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:19:22','2026-07-24 02:19:22'),
(87,91,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:26:01','2026-07-24 02:26:01'),
(88,92,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:30:13','2026-07-24 02:30:13'),
(89,93,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:37:01','2026-07-24 02:37:01'),
(90,94,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:39:51','2026-07-24 02:39:51'),
(91,95,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-24 02:42:32','2026-07-24 02:42:32'),
(92,96,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-26 00:47:04','2026-07-26 00:47:04'),
(93,97,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-26 00:52:22','2026-07-26 00:52:22'),
(94,98,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-26 00:55:00','2026-07-26 00:55:00'),
(95,99,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-26 00:57:49','2026-07-26 00:57:49'),
(96,100,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-26 00:59:37','2026-07-26 00:59:37'),
(97,101,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-26 01:04:16','2026-07-26 01:04:16'),
(98,102,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,0.00,0.00,0.00,'2026-07-26 21:09:20','2026-07-26 21:09:20'),
(99,103,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.02,0.00,0.00,0.00,'2026-07-26 21:43:29','2026-07-26 21:43:29'),
(101,105,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-26 21:58:29','2026-07-26 21:58:29'),
(102,106,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-26 22:11:57','2026-07-26 22:11:57'),
(103,107,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-26 22:19:13','2026-07-26 22:19:13'),
(104,108,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,9999.97,0.00,0.00,0.00,'2026-07-26 22:22:33','2026-07-26 22:22:33'),
(105,109,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,30000.00,0.00,0.00,0.00,'2026-07-26 22:31:17','2026-07-26 22:31:17'),
(106,110,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,50000.00,0.00,0.00,0.00,'2026-07-27 23:15:56','2026-07-27 23:15:56'),
(107,111,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,0.00,0.00,0.00,0.00,'2026-07-28 18:29:38','2026-07-28 18:29:38'),
(108,112,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,0.00,0.00,0.00,'2026-07-28 21:39:30','2026-07-28 21:39:30'),
(109,113,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5999.94,0.00,0.00,0.00,'2026-07-28 21:53:33','2026-07-28 21:55:20'),
(110,114,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,5000.00,0.00,0.00,0.00,'2026-07-28 21:59:43','2026-07-28 21:59:52'),
(111,115,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,0.00,0.00,0.00,'2026-07-28 22:07:51','2026-07-28 22:07:51'),
(112,116,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,30000.00,0.00,0.00,0.00,'2026-07-29 15:15:33','2026-07-29 15:15:33'),
(113,117,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,30000.00,0.00,0.00,0.00,'2026-07-29 15:27:18','2026-07-29 15:27:18'),
(114,118,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-29 19:28:19','2026-07-29 19:32:18'),
(115,119,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-29 19:38:47','2026-07-29 19:38:47'),
(116,120,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-29 19:47:52','2026-07-29 19:47:52'),
(117,121,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-29 19:52:16','2026-07-29 19:52:16'),
(118,122,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-29 19:56:44','2026-07-29 19:56:44'),
(119,123,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,15000.00,0.00,0.00,0.00,'2026-07-29 20:03:18','2026-07-29 20:03:18'),
(120,124,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-29 20:22:07','2026-07-29 20:22:07'),
(121,125,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-29 20:31:58','2026-07-29 20:31:58'),
(122,126,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-29 20:34:07','2026-07-29 20:34:07'),
(123,127,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,10000.00,0.00,0.00,0.00,'2026-07-29 20:36:01','2026-07-29 20:36:01'),
(124,128,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-29 20:46:01','2026-07-29 20:46:01'),
(125,129,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-29 20:49:01','2026-07-29 20:49:01'),
(126,130,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-29 20:51:50','2026-07-29 20:51:50'),
(127,131,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-29 20:53:34','2026-07-29 20:53:34'),
(128,132,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,8000.00,0.00,0.00,0.00,'2026-07-29 20:55:47','2026-07-29 20:56:02'),
(129,133,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-29 20:59:58','2026-07-29 20:59:58'),
(130,134,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,50000.00,0.00,0.00,0.00,'2026-07-29 21:03:18','2026-07-29 21:03:18'),
(131,135,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,6000.00,0.00,0.00,0.00,'2026-07-29 21:05:51','2026-07-29 21:05:51'),
(132,136,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,20000.00,0.00,0.00,0.00,'2026-07-30 15:11:14','2026-07-30 15:11:14'),
(133,137,'App\\Models\\Unit','default',NULL,NULL,NULL,NULL,12000.00,0.00,0.00,0.00,'2026-07-30 18:28:56','2026-07-30 18:28:56');
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `org_id` bigint(20) unsigned DEFAULT NULL,
  `property_id` bigint(20) unsigned DEFAULT NULL,
  `code` varchar(50) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `discount_type` enum('percent','fixed') NOT NULL DEFAULT 'percent',
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `min_booking_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `expire_date` date DEFAULT NULL,
  `max_uses` int(10) unsigned DEFAULT NULL,
  `limit_per_user` tinyint(3) unsigned DEFAULT NULL,
  `used_count` int(10) unsigned NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_by` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`),
  KEY `coupons_rental_org_id_foreign` (`org_id`),
  KEY `coupons_rental_property_id_foreign` (`property_id`),
  CONSTRAINT `coupons_rental_org_id_foreign` FOREIGN KEY (`org_id`) REFERENCES `orgs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `coupons_rental_property_id_foreign` FOREIGN KEY (`property_id`) REFERENCES `properties` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-07-30 19:44:49
