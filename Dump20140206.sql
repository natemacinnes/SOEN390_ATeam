CREATE DATABASE  IF NOT EXISTS `soen390a` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `soen390a`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: soen390.vm.diffingo.com    Database: soen390a
-- ------------------------------------------------------
-- Server version	5.5.35

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admins` (
  `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','admin','2014-01-28 06:20:51'),(2,'admin2','admin2','2014-01-28 06:20:54');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `narrative_id` int(11) unsigned DEFAULT NULL COMMENT 'Foreign key of narrative ID',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date & time of comment submission',
  `parent_comment` int(11) unsigned DEFAULT NULL COMMENT 'Comment ID of the comment responding to, or else NULL',
  `body` text COMMENT 'Comment body text',
  PRIMARY KEY (`comment_id`),
  KEY `narrative_id` (`narrative_id`),
  KEY `parent_comment` (`parent_comment`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`narrative_id`) REFERENCES `narratives` (`narrative_id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`parent_comment`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,1,'2014-01-28 06:58:15',NULL,'This one rules'),(2,1,'2014-01-28 06:58:28',1,'No it doesn\'t'),(3,2,'2014-01-28 06:58:35',NULL,'I agree');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `narrative_metrics`
--

DROP TABLE IF EXISTS `narrative_metrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `narrative_metrics` (
  `narrative_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `session_id` int(11) DEFAULT NULL,
  `event` int(2) DEFAULT NULL COMMENT 'One of the EVENT_TYPE_* integer constants',
  PRIMARY KEY (`narrative_id`),
  CONSTRAINT `narrative_metrics_ibfk_1` FOREIGN KEY (`narrative_id`) REFERENCES `narratives` (`narrative_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `narrative_metrics`
--

LOCK TABLES `narrative_metrics` WRITE;
/*!40000 ALTER TABLE `narrative_metrics` DISABLE KEYS */;
/*!40000 ALTER TABLE `narrative_metrics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `narratives`
--

DROP TABLE IF EXISTS `narratives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `narratives` (
  `narrative_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` int(1) unsigned DEFAULT '0' COMMENT 'One of the NARRATIVE_POSITION_* integer constants',
  `audio_length` int(3) unsigned DEFAULT '0' COMMENT 'Length of audio clip in seconds',
  `created` timestamp NULL DEFAULT NULL COMMENT 'Date & time of submission''s recording',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date & time of submission''s upload',
  `uploaded_by` int(11) unsigned DEFAULT NULL,
  `language` varchar(2) DEFAULT NULL COMMENT 'Content language - 2-letter ISO code',
  `views` int(11) unsigned DEFAULT NULL COMMENT '# of times viewed',
  `agrees` int(11) unsigned DEFAULT NULL COMMENT '# of times agreed',
  `disagrees` int(11) unsigned DEFAULT NULL COMMENT '# of times disagreed',
  `shares` int(11) unsigned DEFAULT NULL COMMENT '# of times shared',
  `flags` int(11) unsigned DEFAULT NULL COMMENT '# of times flagged',
  PRIMARY KEY (`narrative_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `narratives_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `narratives`
--

LOCK TABLES `narratives` WRITE;
/*!40000 ALTER TABLE `narratives` DISABLE KEYS */;
INSERT INTO `narratives` VALUES (1,2,61,'2014-01-28 06:19:27','2014-01-28 06:19:37',1,'en',23,1,4,52,3),(2,1,61,'2014-01-04 06:19:33','2014-01-28 06:19:38',1,'fr',1,2,2,2,16),(3,2,61,'2014-01-23 06:19:34','2014-01-28 06:19:39',2,'en',2,4,123,16,4),(4,1,61,'2014-01-16 06:19:34','2014-01-28 06:19:39',2,'en',5,7,5,4,7),(5,2,61,'2013-12-28 06:19:35','2014-01-28 06:19:40',1,'fr',15,2,4,9,6),(6,1,61,'2013-11-14 06:19:36','2014-01-28 06:19:41',2,'fr',7,18,34,3,65),(28,1,61,'2013-05-31 00:19:33','2014-02-02 05:30:30',1,'en',0,0,0,0,0),(29,2,61,'2013-05-31 00:19:33','2014-02-03 06:45:38',1,'en',2,3,2,4,1),(30,1,67,'2014-01-28 06:19:27','2014-01-28 06:19:37',1,'en',23,1,4,52,3),(31,2,125,'2014-01-04 06:19:33','2014-01-28 06:19:38',1,'fr',1,2,34,2,16),(32,1,635,'2014-01-23 06:19:34','2014-01-28 06:19:39',2,'en',2,4,4,16,4),(33,2,315,'2014-01-16 06:19:34','2014-01-28 06:19:39',2,'en',5,7,5,4,7),(34,1,148,'2013-12-28 06:19:35','2014-01-28 06:19:40',1,'fr',15,2,4,9,6),(35,2,258,'2013-11-14 06:19:36','2014-01-28 06:19:41',2,'fr',7,18,65,3,65),(36,1,67,'2014-01-28 06:19:27','2014-01-28 06:19:37',1,'en',23,1,4,52,3),(37,2,125,'2014-01-04 06:19:33','2014-01-28 06:19:38',1,'fr',1,2,2,2,16),(38,1,635,'2014-01-23 06:19:34','2014-01-28 06:19:39',2,'en',2,4,4,16,4),(39,2,315,'2014-01-16 06:19:34','2014-01-28 06:19:39',2,'en',5,7,5,4,7),(40,1,148,'2013-12-28 06:19:35','2014-01-28 06:19:40',1,'fr',15,2,4,9,6),(41,2,258,'2013-11-14 06:19:36','2014-01-28 06:19:41',2,'fr',7,18,1,3,65),(42,1,67,'2014-01-28 06:19:27','2014-01-28 06:19:37',1,'en',23,1,4,52,3),(43,2,125,'2014-01-04 06:19:33','2014-01-28 06:19:38',1,'fr',1,2,14,2,16),(44,1,635,'2014-01-23 06:19:34','2014-01-28 06:19:39',2,'en',2,4,4,16,4),(45,2,315,'2014-01-16 06:19:34','2014-01-28 06:19:39',2,'en',5,7,5,4,7),(46,1,148,'2013-12-28 06:19:35','2014-01-28 06:19:40',1,'fr',15,2,4,9,6),(47,2,258,'2013-11-14 06:19:36','2014-01-28 06:19:41',2,'fr',7,18,1,3,65),(48,1,67,'2014-01-28 06:19:27','2014-01-28 06:19:37',1,'en',23,1,4,52,3),(49,2,125,'2014-01-04 06:19:33','2014-01-28 06:19:38',1,'fr',1,2,2,2,16),(50,1,635,'2014-01-23 06:19:34','2014-01-28 06:19:39',2,'en',2,4,4,16,4),(51,2,315,'2014-01-16 06:19:34','2014-01-28 06:19:39',2,'en',5,7,23,4,7),(52,1,148,'2013-12-28 06:19:35','2014-01-28 06:19:40',1,'fr',15,2,4,9,6),(53,2,258,'2013-11-14 06:19:36','2014-01-28 06:19:41',2,'fr',7,18,1,3,65),(54,1,67,'2014-01-28 06:19:27','2014-01-28 06:19:37',1,'en',23,1,4,52,3),(55,2,125,'2014-01-04 06:19:33','2014-01-28 06:19:38',1,'fr',1,2,2,2,16),(56,1,635,'2014-01-23 06:19:34','2014-01-28 06:19:39',2,'en',2,4,4,16,4),(57,2,315,'2014-01-16 06:19:34','2014-01-28 06:19:39',2,'en',5,7,10,4,7),(58,1,148,'2013-12-28 06:19:35','2014-01-28 06:19:40',1,'fr',15,2,4,9,6),(59,2,258,'2013-11-14 06:19:36','2014-01-28 06:19:41',2,'fr',7,18,1,3,65),(60,0,61,'2013-05-31 00:19:33','2014-02-05 18:55:59',1,'en',0,0,0,0,0),(63,0,61,'2013-05-31 00:19:33','2014-02-05 20:36:02',1,'en',0,0,0,0,0),(64,0,47,'2013-07-11 15:22:31','2014-02-05 20:36:03',1,'en',0,0,0,0,0),(65,0,61,'2013-05-31 00:19:33','2014-02-06 21:50:41',1,'EN',0,0,0,0,0),(66,0,61,'2013-05-31 00:19:33','2014-02-06 21:51:54',1,'EN',0,0,0,0,0),(67,0,47,'2013-07-11 15:22:31','2014-02-06 21:51:54',1,'EN',0,0,0,0,0),(68,0,61,'2013-05-31 00:19:33','2014-02-06 22:29:29',1,'EN',0,0,0,0,0),(69,0,61,'2013-05-31 00:19:33','2014-02-06 22:30:07',1,'EN',0,0,0,0,0),(70,0,61,'2013-05-31 00:19:33','2014-02-06 22:30:31',1,'EN',0,0,0,0,0),(71,0,47,'2013-07-11 15:22:31','2014-02-06 22:30:31',1,'EN',0,0,0,0,0),(72,0,61,'2013-05-31 00:19:33','2014-02-06 22:33:01',1,'EN',0,0,0,0,0);
/*!40000 ALTER TABLE `narratives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `narratives.orig`
--

DROP TABLE IF EXISTS `narratives.orig`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `narratives.orig` (
  `narrative_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` int(1) unsigned DEFAULT '0' COMMENT 'One of the NARRATIVE_POSITION_* integer constants',
  `audio_length` int(3) unsigned DEFAULT '0' COMMENT 'Length of audio clip in seconds',
  `created` timestamp NULL DEFAULT NULL COMMENT 'Date & time of submission''s recording',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date & time of submission''s upload',
  `uploaded_by` int(11) unsigned DEFAULT NULL,
  `language` varchar(2) DEFAULT NULL COMMENT 'Content language - 2-letter ISO code',
  `views` int(11) unsigned DEFAULT NULL COMMENT '# of times viewed',
  `agrees` int(11) unsigned DEFAULT NULL COMMENT '# of times agreed',
  `disagrees` int(11) unsigned DEFAULT NULL COMMENT '# of times disagreed',
  `shares` int(11) unsigned DEFAULT NULL COMMENT '# of times shared',
  `flags` int(11) unsigned DEFAULT NULL COMMENT '# of times flagged',
  PRIMARY KEY (`narrative_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `narratives.orig_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `narratives.orig`
--

LOCK TABLES `narratives.orig` WRITE;
/*!40000 ALTER TABLE `narratives.orig` DISABLE KEYS */;
INSERT INTO `narratives.orig` VALUES (1,2,61,'2014-01-28 06:19:27','2014-01-28 06:19:37',1,'en',23,1,4,52,3),(2,1,61,'2014-01-04 06:19:33','2014-01-28 06:19:38',1,'fr',1,2,2,2,16),(3,2,61,'2014-01-23 06:19:34','2014-01-28 06:19:39',2,'en',2,4,4,16,4),(4,1,61,'2014-01-16 06:19:34','2014-01-28 06:19:39',2,'en',5,7,5,4,7),(5,2,61,'2013-12-28 06:19:35','2014-01-28 06:19:40',1,'fr',15,2,4,9,6),(6,1,61,'2013-11-14 06:19:36','2014-01-28 06:19:41',2,'fr',7,18,1,3,65),(28,1,61,'2013-05-31 00:19:33','2014-02-02 05:30:30',1,'en',0,0,0,0,0),(29,2,61,'2013-05-31 00:19:33','2014-02-03 06:45:38',1,'en',2,3,2,4,1),(30,1,61,'2013-05-31 00:19:33','2014-02-03 23:15:34',1,'en',0,0,0,0,0);
/*!40000 ALTER TABLE `narratives.orig` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `variables`
--

DROP TABLE IF EXISTS `variables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variables` (
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT 'Variable name (key)',
  `value` varchar(255) DEFAULT '' COMMENT 'Variable value',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `variables`
--

LOCK TABLES `variables` WRITE;
/*!40000 ALTER TABLE `variables` DISABLE KEYS */;
INSERT INTO `variables` VALUES ('portal_topic','GMO labelling');
/*!40000 ALTER TABLE `variables` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-06 20:29:12
