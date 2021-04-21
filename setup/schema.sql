
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

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_accounts` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `username` varchar(15) NOT NULL,
  `urlid` varchar(100) NOT NULL,
  `position` int(4) NOT NULL,
  PRIMARY KEY (`username`,`urlid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(15) NOT NULL,
  `password` varchar(1000) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sQuestion` varchar(200) NOT NULL,
  `sAnswer` varchar(100) NOT NULL,
  `public` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_castle` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_castle`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `parent_id_in_categories` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `parent_category_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_` (`parent_category_id`),
  CONSTRAINT `item_parent_id_in_categories` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item_id` (`item_id`),
  KEY `table_id` (`table_id`),
  CONSTRAINT `table_items_table_in_tables` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`),
  CONSTRAINT `tables_items_item_in_items` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tables` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_clicktheelephant` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_clicktheelephant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clicktheelephant` (
  `hs1` int(10) NOT NULL,
  `hs2` int(10) NOT NULL,
  `hs3` int(10) NOT NULL,
  `hs4` int(10) NOT NULL,
  `hs5` int(10) NOT NULL,
  `hs6` int(10) NOT NULL,
  `hs7` int(10) NOT NULL,
  `hs8` int(10) NOT NULL,
  `hs9` int(10) NOT NULL,
  `hs10` int(10) NOT NULL,
  `hsuser1` varchar(10) NOT NULL,
  `hsuser2` varchar(10) NOT NULL,
  `hsuser3` varchar(10) NOT NULL,
  `hsuser4` varchar(10) NOT NULL,
  `hsuser5` varchar(10) NOT NULL,
  `hsuser6` varchar(10) NOT NULL,
  `hsuser7` varchar(10) NOT NULL,
  `hsuser8` varchar(10) NOT NULL,
  `hsuser9` varchar(10) NOT NULL,
  `hsuser10` varchar(10) NOT NULL,
  `wkhs1` int(10) NOT NULL,
  `wkhs2` int(10) NOT NULL,
  `wkhs3` int(10) NOT NULL,
  `wkhs4` int(10) NOT NULL,
  `wkhs5` int(10) NOT NULL,
  `wkhs6` int(10) NOT NULL,
  `wkhs7` int(10) NOT NULL,
  `wkhs8` int(10) NOT NULL,
  `wkhs9` int(10) NOT NULL,
  `wkhs10` int(10) NOT NULL,
  `wkhsuser1` varchar(10) NOT NULL,
  `wkhsuser2` varchar(10) NOT NULL,
  `wkhsuser3` varchar(10) NOT NULL,
  `wkhsuser4` varchar(10) NOT NULL,
  `wkhsuser5` varchar(10) NOT NULL,
  `wkhsuser6` varchar(10) NOT NULL,
  `wkhsuser7` varchar(10) NOT NULL,
  `wkhsuser8` varchar(10) NOT NULL,
  `wkhsuser9` varchar(10) NOT NULL,
  `wkhsuser10` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_coach` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_coach`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `username` varchar(30) NOT NULL,
  `title` varchar(100) NOT NULL,
  `notes` varchar(1500) NOT NULL,
  `address` varchar(1500) NOT NULL,
  `category` varchar(20) NOT NULL,
  `subcategory` varchar(15) NOT NULL,
  `subsubcategory` varchar(15) NOT NULL,
  `importance` varchar(2) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `email` varchar(200) NOT NULL,
  `cat1` varchar(15) NOT NULL DEFAULT 'Education',
  `cat1sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub1sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub1sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub1sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub1sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub1sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub2sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub2sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub2sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub2sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub2sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub3sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub3sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub3sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub3sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub3sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub4sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub4sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub4sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub4sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub4sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub5sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub5sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub5sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub5sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat1sub5sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2` varchar(15) NOT NULL DEFAULT 'Entertainment',
  `cat2sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub1sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub1sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub1sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub1sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub1sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub2sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub2sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub2sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub2sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub2sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub3sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub3sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub3sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub3sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub3sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub4sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub4sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub4sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub4sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub4sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub5sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub5sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub5sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub5sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat2sub5sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3` varchar(15) NOT NULL DEFAULT 'Personal',
  `cat3sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub1sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub1sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub1sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub1sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub1sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub2sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub2sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub2sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub2sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub2sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub3sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub3sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub3sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub3sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub3sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub4sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub4sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub4sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub4sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub4sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub5sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub5sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub5sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub5sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat3sub5sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4` varchar(15) NOT NULL DEFAULT 'Shopping',
  `cat4sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub1sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub1sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub1sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub1sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub1sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub2sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub2sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub2sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub2sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub2sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub3sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub3sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub3sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub3sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub3sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub4sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub4sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub4sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub4sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub4sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub5sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub5sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub5sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub5sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat4sub5sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5` varchar(15) NOT NULL DEFAULT 'Sports',
  `cat5sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub1sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub1sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub1sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub1sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub1sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub2sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub2sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub2sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub2sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub2sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub3sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub3sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub3sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub3sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub3sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub4sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub4sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub4sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub4sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub4sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub5` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub5sub1` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub5sub2` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub5sub3` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub5sub4` varchar(15) NOT NULL DEFAULT 'Empty',
  `cat5sub5sub5` varchar(15) NOT NULL DEFAULT 'Empty'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_cocoaball` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_cocoaball`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `highscores` (
  `hs1` int(100) NOT NULL DEFAULT '0',
  `hs2` int(100) NOT NULL DEFAULT '0',
  `hs3` int(100) NOT NULL DEFAULT '0',
  `hs4` int(100) NOT NULL DEFAULT '0',
  `hs5` int(100) NOT NULL DEFAULT '0',
  `hs6` int(100) NOT NULL DEFAULT '0',
  `hs7` int(100) NOT NULL DEFAULT '0',
  `hs8` int(100) NOT NULL DEFAULT '0',
  `hs9` int(100) NOT NULL DEFAULT '0',
  `hs10` int(100) NOT NULL DEFAULT '0',
  `hs1user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs2user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs3user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs4user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs5user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs6user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs7user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs8user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs9user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `hs10user` varchar(10) NOT NULL DEFAULT 'Game 103',
  `mohs1` int(200) NOT NULL DEFAULT '0',
  `mohs2` int(200) NOT NULL DEFAULT '0',
  `mohs3` int(200) NOT NULL DEFAULT '0',
  `mohs4` int(200) NOT NULL DEFAULT '0',
  `mohs5` int(200) NOT NULL DEFAULT '0',
  `mohs6` int(200) NOT NULL DEFAULT '0',
  `mohs7` int(200) NOT NULL DEFAULT '0',
  `mohs8` int(200) NOT NULL DEFAULT '0',
  `mohs9` int(200) NOT NULL DEFAULT '0',
  `mohs10` int(200) NOT NULL DEFAULT '0',
  `mohs1user` varchar(200) NOT NULL DEFAULT '0',
  `mohs2user` varchar(200) NOT NULL DEFAULT '0',
  `mohs3user` varchar(200) NOT NULL DEFAULT '0',
  `mohs4user` varchar(200) NOT NULL DEFAULT '0',
  `mohs5user` varchar(200) NOT NULL DEFAULT '0',
  `mohs6user` varchar(200) NOT NULL DEFAULT '0',
  `mohs7user` varchar(200) NOT NULL DEFAULT '0',
  `mohs8user` varchar(200) NOT NULL DEFAULT '0',
  `mohs9user` varchar(200) NOT NULL DEFAULT '0',
  `mohs10user` varchar(200) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_daxpy` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_daxpy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `high_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL DEFAULT 'Daxpy',
  `score` int(11) NOT NULL,
  `score_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_duckdee` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_duckdee`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `high_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(9) NOT NULL DEFAULT 'Duckdee',
  `score` int(11) NOT NULL,
  `score_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_ema` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `hallaby_ema`;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_flipablox` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_flipablox`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `levels` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_user_id` varchar(50) NOT NULL,
  `level` text NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `saves` bigint(20) NOT NULL DEFAULT '0',
  `name` varchar(10) NOT NULL DEFAULT 'A Level',
  PRIMARY KEY (`id`),
  KEY `facebook_user_id` (`facebook_user_id`),
  KEY `created` (`created`),
  KEY `plays` (`saves`),
  KEY `name` (`name`),
  FULLTEXT KEY `name_text` (`name`),
  FULLTEXT KEY `facebook_user_id_name` (`facebook_user_id`),
  FULLTEXT KEY `name_fb_text` (`name`,`facebook_user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=170 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `level_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `level_id` (`level_id`),
  KEY `added_date` (`added_date`)
) ENGINE=MyISAM AUTO_INCREMENT=155 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER ` prevent_insert_within_five_minutes_saves` BEFORE INSERT ON `saves`
 FOR EACH ROW IF( 
    (SELECT count(1) FROM `saves` 
    WHERE level_id = new.level_id 
    and added_date > DATE_SUB(now(), INTERVAL 5 MINUTE) 
    and ip_address = new.ip_address
    ) > 0
) THEN
SET new.level_id = NULL;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_levels_saves` AFTER INSERT ON `saves`
 FOR EACH ROW UPDATE levels
SET saves = 
(SELECT COUNT(1) FROM saves WHERE saves.level_id = NEW.level_id)
WHERE id = NEW.level_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_game103chat` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_game103chat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` varchar(200) NOT NULL,
  `username` varchar(25) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_games` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=174 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions_controls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `control_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `action_id_control_id` (`action_id`,`control_id`),
  KEY `control_id` (`control_id`),
  KEY `action_id` (`action_id`),
  CONSTRAINT `fk_actions_controls_actions` FOREIGN KEY (`action_id`) REFERENCES `actions` (`id`),
  CONSTRAINT `fk_actions_controls_controls` FOREIGN KEY (`control_id`) REFERENCES `controls` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=374 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions_controls_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_control_id` int(11) NOT NULL,
  `download_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `action_control_id_download_id` (`action_control_id`,`download_id`),
  KEY `action_control_id` (`action_control_id`),
  KEY `download_id` (`download_id`),
  CONSTRAINT `fk_actions_controls_downloads_actions_controls` FOREIGN KEY (`action_control_id`) REFERENCES `actions_controls` (`id`),
  CONSTRAINT `fk_actions_controls_downloads_downloads` FOREIGN KEY (`download_id`) REFERENCES `downloads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `actions_controls_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action_control_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `action_control_id_entry_id` (`action_control_id`,`entry_id`),
  KEY `entry_id` (`entry_id`),
  KEY `actions_controls_id` (`action_control_id`),
  CONSTRAINT `fk_actions_controls_entries_actions_controls` FOREIGN KEY (`action_control_id`) REFERENCES `actions_controls` (`id`),
  CONSTRAINT `fk_actions_controls_entries_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9550 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` tinytext NOT NULL,
  `visits` int(10) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(90) NOT NULL,
  `url_name` varchar(50) DEFAULT NULL,
  `store_url_android` tinytext NOT NULL,
  `store_url_apple` tinytext NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `type` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_name` (`url_name`),
  KEY `name` (`name`),
  KEY `added_date` (`added_date`),
  KEY `creation_date` (`creation_date`),
  KEY `visits` (`visits`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps_characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `character_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id_character_id` (`app_id`,`character_id`),
  KEY `character_id` (`character_id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `fk_apps_characters_apps` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_apps_characters_characters` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `apps_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id_video_id` (`app_id`,`video_id`),
  KEY `video_id` (`video_id`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `fk_apps_videos_apps` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `description` varchar(160) NOT NULL,
  `url_name` varchar(15) NOT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_name` (`url_name`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id_entry_id` (`category_id`,`entry_id`),
  KEY `category_id` (`category_id`),
  KEY `entry_id` (`entry_id`),
  CONSTRAINT `fk_categories_entries_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_categories_entries_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3793 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `image_url` varchar(90) NOT NULL,
  `ipa_name` varchar(90) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `characters_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL,
  `download_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `character_id_download_id` (`character_id`,`download_id`),
  KEY `character_id` (`character_id`),
  KEY `download_id` (`download_id`),
  CONSTRAINT `fk_characters_downloads_characters` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_characters_downloads_downloads` FOREIGN KEY (`download_id`) REFERENCES `downloads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `characters_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `character_id_entry_id` (`character_id`,`entry_id`),
  KEY `character_id` (`character_id`),
  KEY `entry_id` (`entry_id`),
  CONSTRAINT `fk_characters_entries_characters` FOREIGN KEY (`character_id`) REFERENCES `characters` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_characters_entries_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `controls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_game` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `entry_id` (`entry_id`),
  KEY `added_date` (`added_date`),
  CONSTRAINT `fk_daily_game_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1567 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(90) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` tinytext NOT NULL,
  `saves` int(10) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(90) NOT NULL,
  `url_name` varchar(50) NOT NULL,
  `screenshot_url` varchar(90) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_name` (`url_name`),
  KEY `name` (`name`),
  KEY `added_date` (`added_date`),
  KEY `creation_date` (`creation_date`),
  KEY `saves` (`saves`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `downloads_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `download_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `download_id_video_id` (`download_id`,`video_id`),
  KEY `video_id` (`video_id`),
  KEY `download_id` (`download_id`),
  CONSTRAINT `fk_downloads_videos_downloads` FOREIGN KEY (`download_id`) REFERENCES `downloads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(90) NOT NULL,
  `width` int(10) unsigned NOT NULL,
  `height` int(10) unsigned NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` tinytext NOT NULL,
  `plays` int(10) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(90) NOT NULL,
  `url_name` varchar(50) NOT NULL,
  `rating` decimal(7,6) unsigned NOT NULL DEFAULT '0.000000',
  `creation_date` timestamp NULL DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'Flash',
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_name` (`url_name`),
  KEY `name` (`name`),
  KEY `added_date` (`added_date`),
  KEY `plays` (`plays`),
  KEY `rating` (`rating`),
  KEY `creation_date` (`creation_date`),
  KEY `type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=280 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `video_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entry_id_video_id` (`entry_id`,`video_id`),
  KEY `entry_id` (`entry_id`),
  KEY `video_id` (`video_id`),
  CONSTRAINT `fk_entries_videos_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `featured` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `removed_date` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `entry_id` (`entry_id`),
  KEY `added_date` (`added_date`),
  KEY `removed_date` (`removed_date`),
  CONSTRAINT `fk_featured_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `game103` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `entry_id` int(11) DEFAULT NULL,
  `download_id` int(11) DEFAULT NULL,
  `download` tinyint(1) DEFAULT '1',
  `app_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entry_id` (`entry_id`),
  UNIQUE KEY `download_id` (`download_id`),
  UNIQUE KEY `app_id` (`app_id`),
  KEY `creation_date` (`creation_date`),
  CONSTRAINT `fk_game103_apps` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`),
  CONSTRAINT `fk_game103_downloads` FOREIGN KEY (`download_id`) REFERENCES `downloads` (`id`),
  CONSTRAINT `fk_game103_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plays` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `entry_id` (`entry_id`),
  KEY `ip_address` (`ip_address`),
  KEY `added_date` (`added_date`),
  CONSTRAINT `fk_plays_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=59544 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `prevent_insert_within_five_minutes_entries_plays` BEFORE INSERT ON `plays`
 FOR EACH ROW IF( 
    (SELECT count(1) FROM `plays` 
    WHERE entry_id = new.entry_id 
    and added_date > DATE_SUB(now(), INTERVAL 5 MINUTE) 
    and ip_address = new.ip_address
    ) > 0
) THEN
SET new.entry_id = NULL;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_update_entries_plays` AFTER INSERT ON `plays`
 FOR EACH ROW UPDATE entries
SET plays = 
(SELECT COUNT(1) FROM plays WHERE plays.entry_id = NEW.entry_id)
WHERE id = NEW.entry_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `saves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `download_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `download_id` (`download_id`),
  CONSTRAINT `fk_saves_downloads` FOREIGN KEY (`download_id`) REFERENCES `downloads` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2242 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER ` prevent_insert_within_five_minutes_downloads_saves` BEFORE INSERT ON `saves`
 FOR EACH ROW IF( 
    (SELECT count(1) FROM `saves` 
    WHERE download_id = new.download_id 
    and added_date > DATE_SUB(now(), INTERVAL 5 MINUTE) 
    and ip_address = new.ip_address
    ) > 0
) THEN
SET new.download_id = NULL;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_downloads_saves` AFTER INSERT ON `saves`
 FOR EACH ROW UPDATE downloads
SET saves = 
(SELECT COUNT(1) FROM saves WHERE saves.download_id = NEW.download_id)
WHERE id = NEW.download_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `transitions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `before_entry_id` int(11) NOT NULL,
  `after_entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `before_entry_id` (`before_entry_id`),
  KEY `after_entry_id` (`after_entry_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ip_address` (`ip_address`),
  KEY `added_date` (`added_date`),
  KEY `app_id` (`app_id`),
  CONSTRAINT `fk_visits_apps` FOREIGN KEY (`app_id`) REFERENCES `apps` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `prevent_insert_within_five_minutes_apps_visits` BEFORE INSERT ON `visits`
 FOR EACH ROW IF( 
    (SELECT count(1) FROM `visits` 
    WHERE app_id = new.app_id 
    and added_date > DATE_SUB(now(), INTERVAL 5 MINUTE) 
    and ip_address = new.ip_address
    ) > 0
) THEN
SET new.app_id = NULL;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_apps_visits` AFTER INSERT ON `visits`
 FOR EACH ROW UPDATE apps
SET visits = 
(SELECT COUNT(1) FROM visits WHERE visits.app_id = NEW.app_id)
WHERE id = NEW.app_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entry_id_ip_address_added_date` (`entry_id`,`added_date`,`ip_address`),
  KEY `entry_id` (`entry_id`),
  KEY `ip_address` (`ip_address`),
  KEY `added_date` (`added_date`),
  KEY `score` (`score`),
  CONSTRAINT `fk_votes_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=558 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_update_entries_votes` AFTER INSERT ON `votes`
 FOR EACH ROW UPDATE entries
SET rating = 
(SELECT SUM(score)/COUNT(id) FROM votes WHERE votes.entry_id = NEW.entry_id)
WHERE id = NEW.entry_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_housekey` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_housekey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `house` (
  `username` varchar(10) NOT NULL,
  `hi1` varchar(30) NOT NULL DEFAULT 'None',
  `hi2` varchar(30) NOT NULL DEFAULT 'None',
  `hi3` varchar(30) NOT NULL DEFAULT 'None',
  `hi4` varchar(30) NOT NULL DEFAULT 'None',
  `hi5` varchar(30) NOT NULL DEFAULT 'None',
  `hi6` varchar(30) NOT NULL DEFAULT 'None',
  `hi7` varchar(30) NOT NULL DEFAULT 'None',
  `hi8` varchar(30) NOT NULL DEFAULT 'None',
  `hi9` varchar(30) NOT NULL DEFAULT 'None',
  `hi10` varchar(30) NOT NULL DEFAULT 'None',
  `hi11` varchar(30) NOT NULL DEFAULT 'None',
  `hi12` varchar(30) NOT NULL DEFAULT 'None',
  `hi13` varchar(30) NOT NULL DEFAULT 'None',
  `hi14` varchar(30) NOT NULL DEFAULT 'None',
  `hi15` varchar(30) NOT NULL DEFAULT 'None',
  `hi16` varchar(30) NOT NULL DEFAULT 'None',
  `hi17` varchar(30) NOT NULL DEFAULT 'None',
  `hi18` varchar(30) NOT NULL DEFAULT 'None',
  `hi19` varchar(30) NOT NULL DEFAULT 'None',
  `hi20` varchar(30) NOT NULL DEFAULT 'None',
  `hi21` varchar(30) NOT NULL DEFAULT 'None',
  `hi22` varchar(30) NOT NULL DEFAULT 'None',
  `hi23` varchar(30) NOT NULL DEFAULT 'None',
  `hi24` varchar(30) NOT NULL DEFAULT 'None',
  `hi25` varchar(30) NOT NULL DEFAULT 'None',
  `hi26` varchar(30) NOT NULL DEFAULT 'None',
  `hi27` varchar(30) NOT NULL DEFAULT 'None',
  `hi28` varchar(30) NOT NULL DEFAULT 'None',
  `hi29` varchar(30) NOT NULL DEFAULT 'None',
  `hi30` varchar(30) NOT NULL DEFAULT 'None',
  `hi31` varchar(30) NOT NULL DEFAULT 'None',
  `hi32` varchar(30) NOT NULL DEFAULT 'None',
  `hi33` varchar(30) NOT NULL DEFAULT 'None',
  `hi34` varchar(30) NOT NULL DEFAULT 'None',
  `hi35` varchar(30) NOT NULL DEFAULT 'None',
  `hi36` varchar(30) NOT NULL DEFAULT 'None',
  `hi37` varchar(30) NOT NULL DEFAULT 'None',
  `hi38` varchar(30) NOT NULL DEFAULT 'None',
  `hi39` varchar(30) NOT NULL DEFAULT 'None',
  `hi40` varchar(30) NOT NULL DEFAULT 'None',
  `hi41` varchar(30) NOT NULL DEFAULT 'None',
  `hi42` varchar(30) NOT NULL DEFAULT 'None',
  `hi43` varchar(30) NOT NULL DEFAULT 'None',
  `hi44` varchar(30) NOT NULL DEFAULT 'None',
  `hi45` varchar(30) NOT NULL DEFAULT 'None',
  `hi46` varchar(30) NOT NULL DEFAULT 'None',
  `hi47` varchar(30) NOT NULL DEFAULT 'None',
  `hi48` varchar(30) NOT NULL DEFAULT 'None',
  `hi49` varchar(30) NOT NULL DEFAULT 'None',
  `hi50` varchar(30) NOT NULL DEFAULT 'None',
  `hi51` varchar(30) NOT NULL DEFAULT 'None',
  `hi52` varchar(30) NOT NULL DEFAULT 'None',
  `hi53` varchar(30) NOT NULL DEFAULT 'None',
  `hi54` varchar(30) NOT NULL DEFAULT 'None',
  `hi55` varchar(30) NOT NULL DEFAULT 'None',
  `hi56` varchar(30) NOT NULL DEFAULT 'None',
  `hi57` varchar(30) NOT NULL DEFAULT 'None',
  `hi58` varchar(30) NOT NULL DEFAULT 'None',
  `hi59` varchar(30) NOT NULL DEFAULT 'None',
  `hi60` varchar(30) NOT NULL DEFAULT 'None',
  `hi61` varchar(30) NOT NULL DEFAULT 'None',
  `hi62` varchar(30) NOT NULL DEFAULT 'None',
  `hi63` varchar(30) NOT NULL DEFAULT 'None',
  `hi64` varchar(30) NOT NULL DEFAULT 'None',
  `1x` int(7) NOT NULL DEFAULT '50',
  `2x` int(7) NOT NULL DEFAULT '50',
  `3x` int(7) NOT NULL DEFAULT '50',
  `4x` int(7) NOT NULL DEFAULT '50',
  `5x` int(7) NOT NULL DEFAULT '50',
  `6x` int(7) NOT NULL DEFAULT '50',
  `7x` int(7) NOT NULL DEFAULT '50',
  `8x` int(7) NOT NULL DEFAULT '50',
  `9x` int(7) NOT NULL DEFAULT '50',
  `10x` int(7) NOT NULL DEFAULT '50',
  `11x` int(7) NOT NULL DEFAULT '50',
  `12x` int(7) NOT NULL DEFAULT '50',
  `13x` int(7) NOT NULL DEFAULT '50',
  `14x` int(7) NOT NULL DEFAULT '50',
  `15x` int(7) NOT NULL DEFAULT '50',
  `16x` int(7) NOT NULL DEFAULT '50',
  `17x` int(7) NOT NULL DEFAULT '50',
  `18x` int(7) NOT NULL DEFAULT '50',
  `19x` int(7) NOT NULL DEFAULT '50',
  `20x` int(7) NOT NULL DEFAULT '50',
  `21x` int(7) NOT NULL DEFAULT '50',
  `22x` int(7) NOT NULL DEFAULT '50',
  `23x` int(7) NOT NULL DEFAULT '50',
  `24x` int(7) NOT NULL DEFAULT '50',
  `25x` int(7) NOT NULL DEFAULT '50',
  `26x` int(7) NOT NULL DEFAULT '50',
  `27x` int(7) NOT NULL DEFAULT '50',
  `28x` int(7) NOT NULL DEFAULT '50',
  `29x` int(7) NOT NULL DEFAULT '50',
  `30x` int(7) NOT NULL DEFAULT '50',
  `31x` int(7) NOT NULL DEFAULT '50',
  `32x` int(7) NOT NULL DEFAULT '50',
  `33x` int(7) NOT NULL DEFAULT '50',
  `34x` int(7) NOT NULL DEFAULT '50',
  `35x` int(7) NOT NULL DEFAULT '50',
  `36x` int(7) NOT NULL DEFAULT '50',
  `37x` int(7) NOT NULL DEFAULT '50',
  `38x` int(7) NOT NULL DEFAULT '50',
  `39x` int(7) NOT NULL DEFAULT '50',
  `40x` int(7) NOT NULL DEFAULT '50',
  `41x` int(7) NOT NULL DEFAULT '50',
  `42x` int(7) NOT NULL DEFAULT '50',
  `43x` int(7) NOT NULL DEFAULT '50',
  `44x` int(7) NOT NULL DEFAULT '50',
  `45x` int(7) NOT NULL DEFAULT '50',
  `46x` int(7) NOT NULL DEFAULT '50',
  `47x` int(7) NOT NULL DEFAULT '50',
  `48x` int(7) NOT NULL DEFAULT '50',
  `49x` int(7) NOT NULL DEFAULT '50',
  `50x` int(7) NOT NULL DEFAULT '50',
  `51x` int(7) NOT NULL DEFAULT '50',
  `52x` int(7) NOT NULL DEFAULT '50',
  `53x` int(7) NOT NULL DEFAULT '50',
  `54x` int(7) NOT NULL DEFAULT '50',
  `55x` int(7) NOT NULL DEFAULT '50',
  `56x` int(7) NOT NULL DEFAULT '50',
  `57x` int(7) NOT NULL DEFAULT '50',
  `58x` int(7) NOT NULL DEFAULT '50',
  `59x` int(7) NOT NULL DEFAULT '50',
  `60x` int(7) NOT NULL DEFAULT '50',
  `61x` int(7) NOT NULL DEFAULT '50',
  `62x` int(7) NOT NULL DEFAULT '50',
  `63x` int(7) NOT NULL DEFAULT '50',
  `64x` int(7) NOT NULL DEFAULT '50',
  `1y` int(7) NOT NULL DEFAULT '50',
  `2y` int(7) NOT NULL DEFAULT '50',
  `3y` int(7) NOT NULL DEFAULT '50',
  `4y` int(7) NOT NULL DEFAULT '50',
  `5y` int(7) NOT NULL DEFAULT '50',
  `6y` int(7) NOT NULL DEFAULT '50',
  `7y` int(7) NOT NULL DEFAULT '50',
  `8y` int(7) NOT NULL DEFAULT '50',
  `9y` int(7) NOT NULL DEFAULT '50',
  `10y` int(7) NOT NULL DEFAULT '50',
  `11y` int(7) NOT NULL DEFAULT '50',
  `12y` int(7) NOT NULL DEFAULT '50',
  `13y` int(7) NOT NULL DEFAULT '50',
  `14y` int(7) NOT NULL DEFAULT '50',
  `15y` int(7) NOT NULL DEFAULT '50',
  `16y` int(7) NOT NULL DEFAULT '50',
  `17y` int(7) NOT NULL DEFAULT '50',
  `18y` int(7) NOT NULL DEFAULT '50',
  `19y` int(7) NOT NULL DEFAULT '50',
  `20y` int(7) NOT NULL DEFAULT '50',
  `21y` int(7) NOT NULL DEFAULT '50',
  `22y` int(7) NOT NULL DEFAULT '50',
  `23y` int(7) NOT NULL DEFAULT '50',
  `24y` int(7) NOT NULL DEFAULT '50',
  `25y` int(7) NOT NULL DEFAULT '50',
  `26y` int(7) NOT NULL DEFAULT '50',
  `27y` int(7) NOT NULL DEFAULT '50',
  `28y` int(7) NOT NULL DEFAULT '50',
  `29y` int(7) NOT NULL DEFAULT '50',
  `30y` int(7) NOT NULL DEFAULT '50',
  `31y` int(7) NOT NULL DEFAULT '50',
  `32y` int(7) NOT NULL DEFAULT '50',
  `33y` int(7) NOT NULL DEFAULT '50',
  `34y` int(7) NOT NULL DEFAULT '50',
  `35y` int(7) NOT NULL DEFAULT '50',
  `36y` int(7) NOT NULL DEFAULT '50',
  `37y` int(7) NOT NULL DEFAULT '50',
  `38y` int(7) NOT NULL DEFAULT '50',
  `39y` int(7) NOT NULL DEFAULT '50',
  `40y` int(7) NOT NULL DEFAULT '50',
  `41y` int(7) NOT NULL DEFAULT '50',
  `42y` int(7) NOT NULL DEFAULT '50',
  `43y` int(7) NOT NULL DEFAULT '50',
  `44y` int(7) NOT NULL DEFAULT '50',
  `45y` int(7) NOT NULL DEFAULT '50',
  `46y` int(7) NOT NULL DEFAULT '50',
  `47y` int(7) NOT NULL DEFAULT '50',
  `48y` int(7) NOT NULL DEFAULT '50',
  `49y` int(7) NOT NULL DEFAULT '50',
  `50y` int(7) NOT NULL DEFAULT '50',
  `51y` int(7) NOT NULL DEFAULT '50',
  `52y` int(7) NOT NULL DEFAULT '50',
  `53y` int(7) NOT NULL DEFAULT '50',
  `54y` int(7) NOT NULL DEFAULT '50',
  `55y` int(7) NOT NULL DEFAULT '50',
  `56y` int(7) NOT NULL DEFAULT '50',
  `57y` int(7) NOT NULL DEFAULT '50',
  `58y` int(7) NOT NULL DEFAULT '50',
  `59y` int(7) NOT NULL DEFAULT '50',
  `60y` int(7) NOT NULL DEFAULT '50',
  `61y` int(7) NOT NULL DEFAULT '50',
  `62y` int(7) NOT NULL DEFAULT '50',
  `63y` int(7) NOT NULL DEFAULT '50',
  `64y` int(7) NOT NULL DEFAULT '50'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userlist` (
  `username` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `variables` (
  `username` varchar(10) NOT NULL,
  `password` varchar(10) NOT NULL,
  `room` varchar(10) NOT NULL,
  `message` varchar(50) NOT NULL,
  `moneys` int(11) NOT NULL DEFAULT '500',
  `wood` int(11) NOT NULL DEFAULT '0',
  `fish` int(11) NOT NULL DEFAULT '0',
  `cotton` int(11) NOT NULL DEFAULT '0',
  `xp` int(11) NOT NULL DEFAULT '0',
  `attack` int(11) NOT NULL DEFAULT '5',
  `defense` int(11) NOT NULL DEFAULT '5',
  `health` int(11) NOT NULL DEFAULT '10',
  `job` varchar(10) NOT NULL DEFAULT 'Fisherman',
  `speed` int(11) NOT NULL DEFAULT '0',
  `item1` varchar(30) NOT NULL DEFAULT 'None',
  `item2` varchar(30) NOT NULL DEFAULT 'None',
  `item3` varchar(30) NOT NULL DEFAULT 'None',
  `item4` varchar(30) NOT NULL DEFAULT 'None',
  `item5` varchar(30) NOT NULL DEFAULT 'None',
  `item6` varchar(30) NOT NULL DEFAULT 'None',
  `item7` varchar(30) NOT NULL DEFAULT 'None',
  `item8` varchar(30) NOT NULL DEFAULT 'None',
  `item9` varchar(30) NOT NULL DEFAULT 'None',
  `item10` varchar(30) NOT NULL DEFAULT 'None',
  `headequipped` varchar(30) NOT NULL DEFAULT 'None',
  `bodyequipped` varchar(30) NOT NULL DEFAULT 'None',
  `lefthandequipped` varchar(30) NOT NULL DEFAULT 'None',
  `righthandequipped` varchar(30) NOT NULL DEFAULT 'None',
  `legsequipped` varchar(30) NOT NULL DEFAULT 'None',
  `leftfootequipped` varchar(30) NOT NULL DEFAULT 'None',
  `rightfootequipped` varchar(30) NOT NULL DEFAULT 'None',
  `x` int(10) NOT NULL DEFAULT '400',
  `y` int(10) NOT NULL DEFAULT '300',
  `battlewith` varchar(10) NOT NULL,
  `power` int(11) NOT NULL,
  `ready` tinyint(1) NOT NULL DEFAULT '0',
  `parcel` varchar(30) NOT NULL DEFAULT 'None'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_lewis` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_lewis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `name` varchar(20) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `description` varchar(250) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `items` (
  `item` varchar(20) NOT NULL,
  `seller` varchar(20) NOT NULL,
  `description` varchar(250) NOT NULL,
  `date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `to` varchar(20) NOT NULL,
  `from` varchar(20) NOT NULL,
  `message` varchar(2000) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `people` (
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `status` varchar(250) NOT NULL,
  `statusdate` date NOT NULL,
  `statustime` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_otherschat` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_otherschat`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `room` varchar(20) NOT NULL DEFAULT 'game103',
  `username` varchar(10) NOT NULL DEFAULT 'cocoawoof',
  `message` varchar(200) NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_outsidegames` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_outsidegames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `gameid` mediumtext NOT NULL,
  `embedcode` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `controls` mediumtext NOT NULL,
  `imageurl` mediumtext NOT NULL,
  `date` date NOT NULL DEFAULT '2013-07-08',
  `totalVotes` int(100) NOT NULL,
  `totalScore` int(100) NOT NULL,
  `rating` decimal(50,2) NOT NULL,
  `urlid` varchar(30) NOT NULL,
  `cat1` mediumtext NOT NULL,
  `cat2` mediumtext NOT NULL,
  PRIMARY KEY (`urlid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_pages` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(150) NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `url` (`url`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_phplogin` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_phplogin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(30) NOT NULL,
  `date` date NOT NULL,
  `email` varchar(40) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_pony` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_pony`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `high_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(10) NOT NULL DEFAULT 'Horse',
  `score` int(11) NOT NULL DEFAULT '0',
  `score_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_resources` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_resources`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `description` varchar(160) NOT NULL,
  `url_name` varchar(15) NOT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_name` (`url_name`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id_entry_id` (`category_id`,`entry_id`),
  KEY `category_id` (`category_id`),
  KEY `entry_id` (`entry_id`),
  CONSTRAINT `fk_categories_entries_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_categories_entries_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=180 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(90) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` tinytext NOT NULL,
  `visits` int(10) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(90) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `added_date` (`added_date`),
  KEY `visits` (`visits`)
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `visits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `entry_id` (`entry_id`),
  KEY `ip_address` (`ip_address`),
  KEY `added_date` (`added_date`),
  CONSTRAINT `fk_visits_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=78 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `prevent_insert_within_five_minutes_entries_visits` BEFORE INSERT ON `visits`
 FOR EACH ROW IF( 
    (SELECT count(1) FROM `visits` 
    WHERE entry_id = new.entry_id 
    and added_date > DATE_SUB(now(), INTERVAL 5 MINUTE) 
    and ip_address = new.ip_address
    ) > 0
) THEN
SET new.entry_id = NULL;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `update_entries_visits` AFTER INSERT ON `visits`
 FOR EACH ROW UPDATE entries
SET visits = 
(SELECT COUNT(1) FROM visits WHERE visits.entry_id = NEW.entry_id)
WHERE id = NEW.entry_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_scores` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_scores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `high_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(100) NOT NULL,
  `score` int(11) NOT NULL,
  `score_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `game` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `score` (`score`),
  KEY `score_date` (`score_date`),
  KEY `game` (`game`),
  CONSTRAINT `high_scores_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14907 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` varchar(100) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `email` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_shelter` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_shelter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `animal` (
  `animal_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(10) unsigned NOT NULL DEFAULT '0',
  `adopter_id` int(10) unsigned NOT NULL DEFAULT '0',
  `animal_type_id` int(10) unsigned NOT NULL,
  `gender` char(1) NOT NULL,
  `weight` smallint(5) unsigned NOT NULL,
  `arrival_date` date NOT NULL,
  `pickup_date` date DEFAULT NULL,
  `adoption_date` date DEFAULT NULL,
  `is_child` bit(1) NOT NULL,
  `adoptable` bit(1) NOT NULL,
  PRIMARY KEY (`animal_id`),
  KEY `owner_id` (`owner_id`),
  KEY `adopter_id` (`adopter_id`),
  KEY `animal_type_id` (`animal_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `animal_health_issue` (
  `animal_health_issue_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `animal_id` int(10) unsigned NOT NULL,
  `health_issue_id` int(10) unsigned NOT NULL,
  `date_noticed` date NOT NULL,
  `date_ended` date DEFAULT NULL,
  PRIMARY KEY (`animal_health_issue_id`),
  KEY `animal_id` (`animal_id`),
  KEY `health_issue_id` (`health_issue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `animal_type` (
  `animal_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `species` varchar(20) NOT NULL,
  `breed` varchar(20) DEFAULT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`animal_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eats` (
  `animal_type_id` int(10) unsigned NOT NULL,
  `food_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`animal_type_id`,`food_id`),
  KEY `food_id` (`food_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `employee` (
  `person_id` int(10) unsigned NOT NULL,
  `social_security` int(10) unsigned NOT NULL,
  `start_date` date NOT NULL,
  `salary` int(10) unsigned NOT NULL,
  `password` varchar(20) NOT NULL DEFAULT 'password',
  PRIMARY KEY (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `feeding` (
  `food_id` int(10) unsigned NOT NULL,
  `animal_id` int(10) unsigned NOT NULL,
  `feeding_date` date NOT NULL,
  `feeding_time` time NOT NULL,
  PRIMARY KEY (`food_id`,`animal_id`,`feeding_date`,`feeding_time`),
  KEY `animal_id` (`animal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `updateQuantity` AFTER INSERT ON `feeding`
 FOR EACH ROW UPDATE food
	SET food.quantity_remaining = food.quantity_remaining - 1
	WHERE food.food_id = feeding.feeding_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `food` (
  `food_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `quantity_remaining` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`food_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `food_company` (
  `company_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `email` varchar(75) NOT NULL,
  `phone` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `food_contact` (
  `food_id` int(10) unsigned NOT NULL,
  `company_id` int(10) unsigned NOT NULL,
  `price` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`food_id`,`company_id`),
  KEY `company_id` (`company_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `health_issue` (
  `health_issue_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `issue_type` varchar(30) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`health_issue_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `person` (
  `person_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(15) DEFAULT NULL,
  `last_name` varchar(15) NOT NULL,
  `apt_num` int(10) unsigned DEFAULT NULL,
  `st_num` int(10) unsigned NOT NULL,
  `st_name` varchar(30) NOT NULL,
  `city` varchar(20) NOT NULL,
  `state` char(2) NOT NULL,
  `email` varchar(75) NOT NULL,
  `home_phone` bigint(20) unsigned NOT NULL,
  `cell_phone` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`person_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vet` (
  `vet_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `vet_office_id` int(10) unsigned NOT NULL,
  `first_name` varchar(15) DEFAULT NULL,
  `last_name` varchar(15) NOT NULL,
  `extension` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`vet_id`),
  KEY `vet_office_id` (`vet_office_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vet_job` (
  `vet_job_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `animal_id` int(10) unsigned NOT NULL,
  `vet_id` int(10) unsigned NOT NULL,
  `job_date` date NOT NULL,
  `job_type` varchar(30) DEFAULT NULL,
  `description` varchar(300) DEFAULT NULL,
  PRIMARY KEY (`vet_job_id`),
  KEY `animal_id` (`animal_id`),
  KEY `vet_id` (`vet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vet_office` (
  `vet_office_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `st_name` varchar(30) NOT NULL,
  `st_number` int(10) unsigned NOT NULL,
  `city` varchar(20) NOT NULL,
  `state` char(2) NOT NULL,
  `phone` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`vet_office_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_uprightbookshelf` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_uprightbookshelf`;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_videos` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) NOT NULL,
  `description` varchar(160) NOT NULL,
  `url_name` varchar(15) NOT NULL,
  `sort` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_name` (`url_name`),
  KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories_entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `entry_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_id_entry_id` (`category_id`,`entry_id`),
  KEY `category_id` (`category_id`),
  KEY `entry_id` (`entry_id`),
  CONSTRAINT `fk_categories_entries_categories` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_categories_entries_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=294 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `string` varchar(200) DEFAULT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` tinytext NOT NULL,
  `views` int(10) unsigned NOT NULL DEFAULT '0',
  `image_url` varchar(90) NOT NULL,
  `url_name` varchar(100) DEFAULT NULL,
  `rating` decimal(7,6) unsigned NOT NULL DEFAULT '0.000000',
  `type` varchar(10) DEFAULT NULL,
  `width` int(10) unsigned DEFAULT NULL,
  `height` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_name` (`url_name`),
  KEY `name` (`name`),
  KEY `added_date` (`added_date`),
  KEY `views` (`views`),
  KEY `rating` (`rating`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries_old` (
  `videoid` mediumtext NOT NULL,
  `string` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `imageurl` mediumtext NOT NULL,
  `date` date NOT NULL,
  `totalVotes` int(100) NOT NULL,
  `totalScore` int(100) NOT NULL,
  `rating` decimal(50,2) NOT NULL,
  `urlid` varchar(30) NOT NULL,
  `cat1` mediumtext NOT NULL,
  `cat2` mediumtext NOT NULL,
  PRIMARY KEY (`urlid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `views` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `entry_id` (`entry_id`),
  KEY `ip_address` (`ip_address`),
  KEY `added_date` (`added_date`),
  CONSTRAINT `fk_views_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9644 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `prevent_insert_within_five_minutes_entries_views` BEFORE INSERT ON `views`
 FOR EACH ROW IF( 
    (SELECT count(1) FROM `views` 
    WHERE entry_id = new.entry_id 
    and added_date > DATE_SUB(now(), INTERVAL 5 MINUTE) 
    and ip_address = new.ip_address
    ) > 0
) THEN
SET new.entry_id = NULL;
END IF */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `fk_update_entries_views` AFTER INSERT ON `views`
 FOR EACH ROW UPDATE entries
SET views = 
(SELECT COUNT(1) FROM views WHERE views.entry_id = NEW.entry_id)
WHERE id = NEW.entry_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `added_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `score` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `added_date_entry_id_ip_address` (`added_date`,`entry_id`,`ip_address`),
  KEY `entry_id` (`entry_id`),
  KEY `score` (`score`),
  KEY `ip_address` (`ip_address`),
  KEY `added_date` (`added_date`),
  CONSTRAINT `fk_votes_entries` FOREIGN KEY (`entry_id`) REFERENCES `entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_AUTO_VALUE_ON_ZERO' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `tr_update_entries` AFTER INSERT ON `votes`
 FOR EACH ROW UPDATE entries
SET rating = 
(SELECT SUM(score)/COUNT(id) FROM votes WHERE votes.entry_id = NEW.entry_id)
WHERE id = NEW.entry_id */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_want` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_want`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `agree` (
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `agree_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`post_id`,`user_id`),
  KEY `agree_date` (`agree_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `name` varchar(20) NOT NULL,
  `display_name` varchar(20) NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `display_name` (`display_name`),
  KEY `parent_id` (`parent_id`),
  KEY `creation_date` (`creation_date`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `text` text NOT NULL,
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`,`user_id`),
  KEY `user_id` (`user_id`),
  KEY `comment_date` (`comment_date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `post_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `title` (`title`),
  KEY `post_date` (`post_date`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `join_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `points` int(10) unsigned NOT NULL,
  `hash` varchar(100) NOT NULL,
  `validated` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `points` (`points`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `join_date` (`join_date`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `hallaby_webtools` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `hallaby_webtools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `link` varchar(200) NOT NULL,
  `name` mediumtext NOT NULL,
  `description` mediumtext NOT NULL,
  `imageurl` mediumtext NOT NULL,
  `date` date NOT NULL,
  `totalVotes` int(100) NOT NULL,
  `totalScore` int(100) NOT NULL,
  `rating` decimal(50,2) NOT NULL,
  `cat1` mediumtext NOT NULL,
  `cat2` mediumtext NOT NULL,
  PRIMARY KEY (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `mysql` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `mysql`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `column_stats` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `column_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `min_value` varbinary(255) DEFAULT NULL,
  `max_value` varbinary(255) DEFAULT NULL,
  `nulls_ratio` decimal(12,4) DEFAULT NULL,
  `avg_length` decimal(12,4) DEFAULT NULL,
  `avg_frequency` decimal(12,4) DEFAULT NULL,
  `hist_size` tinyint(3) unsigned DEFAULT NULL,
  `hist_type` enum('SINGLE_PREC_HB','DOUBLE_PREC_HB') COLLATE utf8_bin DEFAULT NULL,
  `histogram` varbinary(255) DEFAULT NULL,
  PRIMARY KEY (`db_name`,`table_name`,`column_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Statistics on Columns';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `columns_priv` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Db` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `User` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Table_name` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Column_name` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Column_priv` set('Select','Insert','Update','References') CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`Host`,`Db`,`User`,`Table_name`,`Column_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column privileges';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Db` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `User` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Select_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Insert_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Update_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Delete_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Drop_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Grant_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `References_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Index_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Alter_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_tmp_table_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Lock_tables_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_view_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Show_view_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_routine_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Alter_routine_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Execute_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Event_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Trigger_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  PRIMARY KEY (`Host`,`Db`,`User`),
  KEY `User` (`User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database privileges';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `event` (
  `db` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `name` char(64) NOT NULL DEFAULT '',
  `body` longblob NOT NULL,
  `definer` char(141) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `execute_at` datetime DEFAULT NULL,
  `interval_value` int(11) DEFAULT NULL,
  `interval_field` enum('YEAR','QUARTER','MONTH','DAY','HOUR','MINUTE','WEEK','SECOND','MICROSECOND','YEAR_MONTH','DAY_HOUR','DAY_MINUTE','DAY_SECOND','HOUR_MINUTE','HOUR_SECOND','MINUTE_SECOND','DAY_MICROSECOND','HOUR_MICROSECOND','MINUTE_MICROSECOND','SECOND_MICROSECOND') DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_executed` datetime DEFAULT NULL,
  `starts` datetime DEFAULT NULL,
  `ends` datetime DEFAULT NULL,
  `status` enum('ENABLED','DISABLED','SLAVESIDE_DISABLED') NOT NULL DEFAULT 'ENABLED',
  `on_completion` enum('DROP','PRESERVE') NOT NULL DEFAULT 'DROP',
  `sql_mode` set('REAL_AS_FLOAT','PIPES_AS_CONCAT','ANSI_QUOTES','IGNORE_SPACE','IGNORE_BAD_TABLE_OPTIONS','ONLY_FULL_GROUP_BY','NO_UNSIGNED_SUBTRACTION','NO_DIR_IN_CREATE','POSTGRESQL','ORACLE','MSSQL','DB2','MAXDB','NO_KEY_OPTIONS','NO_TABLE_OPTIONS','NO_FIELD_OPTIONS','MYSQL323','MYSQL40','ANSI','NO_AUTO_VALUE_ON_ZERO','NO_BACKSLASH_ESCAPES','STRICT_TRANS_TABLES','STRICT_ALL_TABLES','NO_ZERO_IN_DATE','NO_ZERO_DATE','INVALID_DATES','ERROR_FOR_DIVISION_BY_ZERO','TRADITIONAL','NO_AUTO_CREATE_USER','HIGH_NOT_PRECEDENCE','NO_ENGINE_SUBSTITUTION','PAD_CHAR_TO_FULL_LENGTH') NOT NULL DEFAULT '',
  `comment` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `originator` int(10) unsigned NOT NULL,
  `time_zone` char(64) CHARACTER SET latin1 NOT NULL DEFAULT 'SYSTEM',
  `character_set_client` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `collation_connection` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `db_collation` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `body_utf8` longblob,
  PRIMARY KEY (`db`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Events';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `func` (
  `name` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `ret` tinyint(1) NOT NULL DEFAULT '0',
  `dl` char(128) COLLATE utf8_bin NOT NULL DEFAULT '',
  `type` enum('function','aggregate') CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User defined functions';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `gtid_slave_pos` (
  `domain_id` int(10) unsigned NOT NULL,
  `sub_id` bigint(20) unsigned NOT NULL,
  `server_id` int(10) unsigned NOT NULL,
  `seq_no` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`domain_id`,`sub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Replication slave GTID position';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help_category` (
  `help_category_id` smallint(5) unsigned NOT NULL,
  `name` char(64) NOT NULL,
  `parent_category_id` smallint(5) unsigned DEFAULT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`help_category_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='help categories';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help_keyword` (
  `help_keyword_id` int(10) unsigned NOT NULL,
  `name` char(64) NOT NULL,
  PRIMARY KEY (`help_keyword_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='help keywords';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help_relation` (
  `help_topic_id` int(10) unsigned NOT NULL,
  `help_keyword_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`help_keyword_id`,`help_topic_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='keyword-topic relation';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `help_topic` (
  `help_topic_id` int(10) unsigned NOT NULL,
  `name` char(64) NOT NULL,
  `help_category_id` smallint(5) unsigned NOT NULL,
  `description` text NOT NULL,
  `example` text NOT NULL,
  `url` text NOT NULL,
  PRIMARY KEY (`help_topic_id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='help topics';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `host` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Db` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Select_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Insert_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Update_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Delete_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Drop_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Grant_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `References_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Index_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Alter_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_tmp_table_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Lock_tables_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_view_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Show_view_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_routine_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Alter_routine_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Execute_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Trigger_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  PRIMARY KEY (`Host`,`Db`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Host privileges;  Merged with database privileges';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `index_stats` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `index_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `prefix_arity` int(11) unsigned NOT NULL,
  `avg_frequency` decimal(12,4) DEFAULT NULL,
  PRIMARY KEY (`db_name`,`table_name`,`index_name`,`prefix_arity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Statistics on Indexes';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `innodb_index_stats` (
  `database_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `index_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `stat_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `stat_value` bigint(20) unsigned NOT NULL,
  `sample_size` bigint(20) unsigned DEFAULT NULL,
  `stat_description` varchar(1024) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`database_name`,`table_name`,`index_name`,`stat_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin STATS_PERSISTENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `innodb_table_stats` (
  `database_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `n_rows` bigint(20) unsigned NOT NULL,
  `clustered_index_size` bigint(20) unsigned NOT NULL,
  `sum_of_other_index_sizes` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`database_name`,`table_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin STATS_PERSISTENT=0;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ndb_binlog_index` (
  `Position` bigint(20) unsigned NOT NULL,
  `File` varchar(255) NOT NULL,
  `epoch` bigint(20) unsigned NOT NULL,
  `inserts` bigint(20) unsigned NOT NULL,
  `updates` bigint(20) unsigned NOT NULL,
  `deletes` bigint(20) unsigned NOT NULL,
  `schemaops` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`epoch`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `plugin` (
  `name` varchar(64) NOT NULL DEFAULT '',
  `dl` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='MySQL plugins';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proc` (
  `db` char(64) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `name` char(64) NOT NULL DEFAULT '',
  `type` enum('FUNCTION','PROCEDURE') NOT NULL,
  `specific_name` char(64) NOT NULL DEFAULT '',
  `language` enum('SQL') NOT NULL DEFAULT 'SQL',
  `sql_data_access` enum('CONTAINS_SQL','NO_SQL','READS_SQL_DATA','MODIFIES_SQL_DATA') NOT NULL DEFAULT 'CONTAINS_SQL',
  `is_deterministic` enum('YES','NO') NOT NULL DEFAULT 'NO',
  `security_type` enum('INVOKER','DEFINER') NOT NULL DEFAULT 'DEFINER',
  `param_list` blob NOT NULL,
  `returns` longblob NOT NULL,
  `body` longblob NOT NULL,
  `definer` char(141) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sql_mode` set('REAL_AS_FLOAT','PIPES_AS_CONCAT','ANSI_QUOTES','IGNORE_SPACE','IGNORE_BAD_TABLE_OPTIONS','ONLY_FULL_GROUP_BY','NO_UNSIGNED_SUBTRACTION','NO_DIR_IN_CREATE','POSTGRESQL','ORACLE','MSSQL','DB2','MAXDB','NO_KEY_OPTIONS','NO_TABLE_OPTIONS','NO_FIELD_OPTIONS','MYSQL323','MYSQL40','ANSI','NO_AUTO_VALUE_ON_ZERO','NO_BACKSLASH_ESCAPES','STRICT_TRANS_TABLES','STRICT_ALL_TABLES','NO_ZERO_IN_DATE','NO_ZERO_DATE','INVALID_DATES','ERROR_FOR_DIVISION_BY_ZERO','TRADITIONAL','NO_AUTO_CREATE_USER','HIGH_NOT_PRECEDENCE','NO_ENGINE_SUBSTITUTION','PAD_CHAR_TO_FULL_LENGTH') NOT NULL DEFAULT '',
  `comment` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `character_set_client` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `collation_connection` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `db_collation` char(32) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `body_utf8` longblob,
  PRIMARY KEY (`db`,`name`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Stored Procedures';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `procs_priv` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Db` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `User` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Routine_name` char(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `Routine_type` enum('FUNCTION','PROCEDURE') COLLATE utf8_bin NOT NULL,
  `Grantor` char(141) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Proc_priv` set('Execute','Alter Routine','Grant') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Host`,`Db`,`User`,`Routine_name`,`Routine_type`),
  KEY `Grantor` (`Grantor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Procedure privileges';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `proxies_priv` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `User` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Proxied_host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Proxied_user` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `With_grant` tinyint(1) NOT NULL DEFAULT '0',
  `Grantor` char(141) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Host`,`User`,`Proxied_host`,`Proxied_user`),
  KEY `Grantor` (`Grantor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User proxy privileges';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles_mapping` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `User` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Role` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Admin_option` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  UNIQUE KEY `Host` (`Host`,`User`,`Role`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Granted roles';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `servers` (
  `Server_name` char(64) NOT NULL DEFAULT '',
  `Host` char(64) NOT NULL DEFAULT '',
  `Db` char(64) NOT NULL DEFAULT '',
  `Username` char(80) NOT NULL DEFAULT '',
  `Password` char(64) NOT NULL DEFAULT '',
  `Port` int(4) NOT NULL DEFAULT '0',
  `Socket` char(64) NOT NULL DEFAULT '',
  `Wrapper` char(64) NOT NULL DEFAULT '',
  `Owner` char(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`Server_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='MySQL Foreign Servers table';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `table_stats` (
  `db_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `table_name` varchar(64) COLLATE utf8_bin NOT NULL,
  `cardinality` bigint(21) unsigned DEFAULT NULL,
  PRIMARY KEY (`db_name`,`table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Statistics on Tables';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tables_priv` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Db` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `User` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Table_name` char(64) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Grantor` char(141) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `Table_priv` set('Select','Insert','Update','Delete','Create','Drop','Grant','References','Index','Alter','Create View','Show view','Trigger') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `Column_priv` set('Select','Insert','Update','References') CHARACTER SET utf8 NOT NULL DEFAULT '',
  PRIMARY KEY (`Host`,`Db`,`User`,`Table_name`),
  KEY `Grantor` (`Grantor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table privileges';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_zone` (
  `Time_zone_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Use_leap_seconds` enum('Y','N') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`Time_zone_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zones';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_zone_leap_second` (
  `Transition_time` bigint(20) NOT NULL,
  `Correction` int(11) NOT NULL,
  PRIMARY KEY (`Transition_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Leap seconds information for time zones';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_zone_name` (
  `Name` char(64) NOT NULL,
  `Time_zone_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zone names';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_zone_transition` (
  `Time_zone_id` int(10) unsigned NOT NULL,
  `Transition_time` bigint(20) NOT NULL,
  `Transition_type_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`Time_zone_id`,`Transition_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zone transitions';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `time_zone_transition_type` (
  `Time_zone_id` int(10) unsigned NOT NULL,
  `Transition_type_id` int(10) unsigned NOT NULL,
  `Offset` int(11) NOT NULL DEFAULT '0',
  `Is_DST` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `Abbreviation` char(8) NOT NULL DEFAULT '',
  PRIMARY KEY (`Time_zone_id`,`Transition_type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Time zone transition types';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `Host` char(60) COLLATE utf8_bin NOT NULL DEFAULT '',
  `User` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `Password` char(41) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
  `Select_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Insert_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Update_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Delete_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Drop_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Reload_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Shutdown_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Process_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `File_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Grant_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `References_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Index_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Alter_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Show_db_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Super_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_tmp_table_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Lock_tables_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Execute_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Repl_slave_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Repl_client_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_view_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Show_view_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_routine_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Alter_routine_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_user_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Event_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Trigger_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `Create_tablespace_priv` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `ssl_type` enum('','ANY','X509','SPECIFIED') CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ssl_cipher` blob NOT NULL,
  `x509_issuer` blob NOT NULL,
  `x509_subject` blob NOT NULL,
  `max_questions` int(11) unsigned NOT NULL DEFAULT '0',
  `max_updates` int(11) unsigned NOT NULL DEFAULT '0',
  `max_connections` int(11) unsigned NOT NULL DEFAULT '0',
  `max_user_connections` int(11) NOT NULL DEFAULT '0',
  `plugin` char(64) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `authentication_string` text COLLATE utf8_bin NOT NULL,
  `password_expired` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `is_role` enum('N','Y') CHARACTER SET utf8 NOT NULL DEFAULT 'N',
  `default_role` char(80) COLLATE utf8_bin NOT NULL DEFAULT '',
  `max_statement_time` decimal(12,6) NOT NULL DEFAULT '0.000000',
  PRIMARY KEY (`Host`,`User`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and global privileges';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `general_log` (
  `event_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_host` mediumtext NOT NULL,
  `thread_id` int(11) NOT NULL,
  `server_id` int(10) unsigned NOT NULL,
  `command_type` varchar(64) NOT NULL,
  `argument` mediumtext NOT NULL
) ENGINE=CSV DEFAULT CHARSET=utf8 COMMENT='General log';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `slow_log` (
  `start_time` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `user_host` mediumtext NOT NULL,
  `query_time` time(6) NOT NULL,
  `lock_time` time(6) NOT NULL,
  `rows_sent` int(11) NOT NULL,
  `rows_examined` int(11) NOT NULL,
  `db` varchar(512) NOT NULL,
  `last_insert_id` int(11) NOT NULL,
  `insert_id` int(11) NOT NULL,
  `server_id` int(10) unsigned NOT NULL,
  `sql_text` mediumtext NOT NULL,
  `thread_id` bigint(21) unsigned NOT NULL,
  `rows_affected` int(11) NOT NULL
) ENGINE=CSV DEFAULT CHARSET=utf8 COMMENT='Slow log';
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

