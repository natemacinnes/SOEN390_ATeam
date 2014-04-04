# ************************************************************
# YouDeliberate database structure dump
# Generation Time: 2014-04-04 05:52:32 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table admins
# ------------------------------------------------------------

DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `admin_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;

INSERT INTO `admins` (`admin_id`, `login`, `password`, `created`)
VALUES
	(1,'root','teamAwesome','2014-01-28 01:20:51'),
	(2,'admin@youdeliberate.com','Moderator','2014-02-13 18:06:02');

/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;



# Dump of table comments
# ------------------------------------------------------------

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `narrative_id` int(11) unsigned DEFAULT NULL COMMENT 'Foreign key of narrative ID',
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date & time of comment submission',
  `parent_comment` int(11) unsigned DEFAULT NULL COMMENT 'Comment ID of the comment responding to, or else NULL',
  `body` text COMMENT 'Comment body text',
  `status` tinyint(4) unsigned DEFAULT '1' COMMENT '0 = unpublished, 1 = published',
  `flags` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`comment_id`),
  KEY `narrative_id` (`narrative_id`),
  KEY `parent_comment` (`parent_comment`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`narrative_id`) REFERENCES `narratives` (`narrative_id`) ON DELETE CASCADE,
  CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`parent_comment`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table narratives
# ------------------------------------------------------------

DROP TABLE IF EXISTS `narratives`;

CREATE TABLE `narratives` (
  `narrative_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `position` int(1) unsigned DEFAULT '0' COMMENT 'One of the NARRATIVE_POSITION_* integer constants',
  `audio_length` int(3) unsigned DEFAULT '0' COMMENT 'Length of audio clip in seconds',
  `created` timestamp NULL DEFAULT NULL COMMENT 'Date & time of submission''s recording',
  `uploaded` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date & time of submission''s upload',
  `uploaded_by` int(11) unsigned DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `language` varchar(2) DEFAULT NULL COMMENT 'Content language - 2-letter ISO code',
  `views` int(11) unsigned DEFAULT NULL COMMENT '# of times viewed',
  `agrees` int(11) unsigned DEFAULT NULL COMMENT '# of times agreed',
  `disagrees` int(11) unsigned DEFAULT NULL COMMENT '# of times disagreed',
  `shares` int(11) unsigned DEFAULT NULL COMMENT '# of times shared',
  `flags` int(11) unsigned DEFAULT NULL COMMENT '# of times flagged',
  `status` tinyint(4) DEFAULT '0' COMMENT '0 = unpublished, 1 = published',
  PRIMARY KEY (`narrative_id`),
  KEY `uploaded_by` (`uploaded_by`),
  CONSTRAINT `narratives_ibfk_1` FOREIGN KEY (`uploaded_by`) REFERENCES `admins` (`admin_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table tutorials
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tutorials`;

CREATE TABLE `tutorials` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `language` varchar(255) DEFAULT '',
  `url` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



# Dump of table variables
# ------------------------------------------------------------

DROP TABLE IF EXISTS `variables`;

CREATE TABLE `variables` (
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT 'Variable name (key)',
  `value` varchar(255) DEFAULT '' COMMENT 'Variable value',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
