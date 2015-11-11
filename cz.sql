-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: localhost    Database: czd
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.6

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
-- Table structure for table `cz_524e9a1ede4dbc32d1285426583`
--

DROP TABLE IF EXISTS `cz_524e9a1ede4dbc32d1285426583`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_524e9a1ede4dbc32d1285426583` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `registration_no` varchar(20) NOT NULL,
  `nick_name` varchar(100) NOT NULL,
  `language` varchar(255) NOT NULL,
  `disqualified` tinyint(4) DEFAULT '0',
  `downloads_count` tinyint(4) DEFAULT '0',
  `files` varchar(256) NOT NULL,
  `actual_file` varchar(256) NOT NULL,
  `submissions` tinyint(4) DEFAULT '0',
  `correct` tinyint(4) DEFAULT '0',
  `last_submission_time` int(11) DEFAULT '0',
  `time_taken` float(8,2) DEFAULT '0.00',
  `points` float(8,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_524e9a1ede4dbc32d1285426583`
--

LOCK TABLES `cz_524e9a1ede4dbc32d1285426583` WRITE;
/*!40000 ALTER TABLE `cz_524e9a1ede4dbc32d1285426583` DISABLE KEYS */;
INSERT INTO `cz_524e9a1ede4dbc32d1285426583` VALUES (1,'25112872','catalan','Python',0,2,'catalan5.zip','catalan5.py',2,1,1285427798,278.24,500.00),(2,'S14/20508/07','culture','C++',0,5,'culture5.zip','culture5.cpp',7,1,1285865283,602.51,451.32);
/*!40000 ALTER TABLE `cz_524e9a1ede4dbc32d1285426583` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cz_7ba6a4ac763bd84031285410395`
--

DROP TABLE IF EXISTS `cz_7ba6a4ac763bd84031285410395`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_7ba6a4ac763bd84031285410395` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `registration_no` varchar(20) NOT NULL,
  `nick_name` varchar(100) NOT NULL,
  `language` varchar(255) NOT NULL,
  `disqualified` tinyint(4) DEFAULT '0',
  `downloads_count` tinyint(4) DEFAULT '0',
  `files` varchar(256) NOT NULL,
  `actual_file` varchar(256) NOT NULL,
  `submissions` tinyint(4) DEFAULT '0',
  `correct` tinyint(4) DEFAULT '0',
  `last_submission_time` int(11) DEFAULT '0',
  `time_taken` float(8,2) DEFAULT '0.00',
  `points` float(8,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_7ba6a4ac763bd84031285410395`
--

LOCK TABLES `cz_7ba6a4ac763bd84031285410395` WRITE;
/*!40000 ALTER TABLE `cz_7ba6a4ac763bd84031285410395` DISABLE KEYS */;
INSERT INTO `cz_7ba6a4ac763bd84031285410395` VALUES (1,'25112872','catalan','PHP',0,4,'catalan4.zip','catalan4.php',2,1,1285413879,3075.66,98.26);
/*!40000 ALTER TABLE `cz_7ba6a4ac763bd84031285410395` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cz_matches`
--

DROP TABLE IF EXISTS `cz_matches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_matches` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `duration` int(11) NOT NULL,
  `start_time` int(11) NOT NULL,
  `difficulty` smallint(6) NOT NULL DEFAULT '10',
  `match_points` smallint(6) NOT NULL DEFAULT '50',
  `match_ranked` tinyint(4) NOT NULL DEFAULT '0',
  `problem_sheet` varchar(255) NOT NULL,
  `answer_sheet` varchar(255) NOT NULL,
  `input_sheet` varchar(255) NOT NULL,
  `analysis` text NOT NULL,
  `match_table_name` varchar(255) NOT NULL,
  `max_submissions` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_matches`
--

LOCK TABLES `cz_matches` WRITE;
/*!40000 ALTER TABLE `cz_matches` DISABLE KEYS */;
INSERT INTO `cz_matches` VALUES (4,'CodeZone beta round #1',3600,1279794803,11,400,1,'ddecb064cbf4f3a8b5bc9279a77d40271b36d958.php','fd26b19eff936fbc229002e565967b7921851b04.txt','64fad206d050c23fe89320a65b2e1ecb3496bb23.txt','frASF','7ba6a4ac763bd84031285410395',5),(5,'CodeZone beta round #2',3600,1285864680,13,500,1,'fdd00cdc74516a4e687094e56e384d6cded3b8f4.php','9aab2bb80464192053e4a89a0cb28a98f989355e.txt','4afa399cc56460400f26df138c8a85788de45628.txt','','524e9a1ede4dbc32d1285426583',5);
/*!40000 ALTER TABLE `cz_matches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cz_profile`
--

DROP TABLE IF EXISTS `cz_profile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_profile` (
  `row_id` int(8) NOT NULL AUTO_INCREMENT,
  `registration_no` varchar(20) NOT NULL,
  `avatar_path` varchar(100) NOT NULL,
  `quote` varchar(250) NOT NULL,
  `about_me` text NOT NULL,
  `language` varchar(100) NOT NULL,
  `match_count` int(8) DEFAULT '0',
  `ranking_pts` int(8) DEFAULT '0',
  PRIMARY KEY (`row_id`)
) ENGINE=MyISAM AUTO_INCREMENT=79 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_profile`
--

LOCK TABLES `cz_profile` WRITE;
/*!40000 ALTER TABLE `cz_profile` DISABLE KEYS */;
INSERT INTO `cz_profile` VALUES (73,'ADMIN','ecm_member_74_avatar_73.png','','','',0,0);
/*!40000 ALTER TABLE `cz_profile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cz_sessions`
--

DROP TABLE IF EXISTS `cz_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_sessions` (
  `session_id` varchar(100) NOT NULL,
  `registration_no` varchar(20) DEFAULT 'undefined',
  `user_type` varchar(20) DEFAULT 'guest',
  `nick_name` varchar(20) DEFAULT 'guest',
  `session_expire` int(11) NOT NULL,
  `session_ip` varchar(30) NOT NULL,
  `session_browser` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_sessions`
--

LOCK TABLES `cz_sessions` WRITE;
/*!40000 ALTER TABLE `cz_sessions` DISABLE KEYS */;
INSERT INTO `cz_sessions` VALUES ('538523afedc3e2c5d931cec5a136971f','undefined','guest','guest',1286873219,'127.0.0.1','Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.2.3) Gecko/20100423 Ubuntu/10.04 (lucid) Firefox/3.6.3');
/*!40000 ALTER TABLE `cz_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cz_stories`
--

DROP TABLE IF EXISTS `cz_stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_stories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `published` tinyint(4) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `registration_no` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_stories`
--

LOCK TABLES `cz_stories` WRITE;
/*!40000 ALTER TABLE `cz_stories` DISABLE KEYS */;
INSERT INTO `cz_stories` VALUES (1,'Welcome to the CodeZone match arena','Hello everyone, I have now released the CodeZone match arena beta. I will be releasing a few more of <a href=\"\">CodeZone</a> updates in the weeks to come. In the meantime though help me in debugging the sofware as I work on getting a HP Pavillion Quad processor laptop so I have a laptop to test on! <strong>Anything you could throw my</strong> way would be appreciated, I will also give back in the forums, so no worries, I give back even more!',1,1275716758,'S13/20508/07'),(2,'CodeZone is open for practice','CodeZone is opening its doors to new members starting today. Please follow the simple registration procedure to activate your account. If you are having any problems then please contact the administrators. In the meantime please do some practice in the CodeZone practice arena and get familiar with the arena controls. Wishing you all the best. Happy times!',1,1275745578,'S13/20508/07'),(16,'New to CodeZone? Check out the help section','Is this your first time here? If yes then <a href=\"index.php?a=help\">check out this page</a> for more information on how to become a member and participate',1,1284836389,'S13/20508/07'),(17,'Announcing CodeZone beta Round #4','CodeZone beta round #4 is set to take place on Friday October the 8th at 14:00 hrs. Registration commences on the 2nd of October. Lots of prizes to be won.<br />\r\n<strong>First Place: </strong>Kshs. 3000<br />\r\n<strong>Second Place: </strong>Kshs. 1500<br />\r\n<strong>Third Place: </strong>Kshs. 750<br />\r\n<br />\r\nCash on the spot! Keep Codin\'',1,1285961549,'S13/20508/07'),(18,'The King of the Alps Donates $4000','The king of the alps has just donated $4000 towards the save a life fund',1,1286449162,'ADMIN'),(19,'CodeZone beta round #5 sponsored by Alcatel','The CodeZone beta round #5 is sponsored by Alcatel Solutions. Lots of prizes to be won across several areas',1,1286449217,'ADMIN'),(20,'UCI Internationale has donated  $6000','The UCI Internationale has donated  $6000 towards the CodeZone beta match #6. This is in line with their plan of hiring the finest algorithmists on the CodeZone platform to be involved in various projects under the UCI development team.',1,1286527029,'ADMIN');
/*!40000 ALTER TABLE `cz_stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cz_user_match_log`
--

DROP TABLE IF EXISTS `cz_user_match_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_user_match_log` (
  `registration_no` varchar(20) NOT NULL,
  `match_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `match_date` int(11) NOT NULL,
  `register_date` int(11) NOT NULL,
  `participated` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_user_match_log`
--

LOCK TABLES `cz_user_match_log` WRITE;
/*!40000 ALTER TABLE `cz_user_match_log` DISABLE KEYS */;
INSERT INTO `cz_user_match_log` VALUES ('S14/20508/07',5,'CodeZone beta round #2',1285864680,1285864448,1),('25112872',4,'CodeZone beta round #1',1279794803,1285410690,1),('25112872',5,'CodeZone beta round #2',1285864680,1285427400,1);
/*!40000 ALTER TABLE `cz_user_match_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cz_users`
--

DROP TABLE IF EXISTS `cz_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cz_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_names` varchar(255) NOT NULL,
  `registration_no` varchar(20) NOT NULL,
  `user_type` varchar(20) DEFAULT 'registered',
  `nick_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `register_date` datetime NOT NULL,
  `last_visit_date` datetime NOT NULL,
  `activated` tinyint(4) DEFAULT '0',
  `activation_key` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cz_users`
--

LOCK TABLES `cz_users` WRITE;
/*!40000 ALTER TABLE `cz_users` DISABLE KEYS */;
INSERT INTO `cz_users` VALUES (74,'CodeZone Admin','ADMIN','su','czAdmin','cea5ebdcbb0caaba8b661c76b707d612','','2010-09-25 23:23:17','2010-10-12 11:16:45',1,'0f4bda7a21a1b8c0aca7271d4a95735c');
/*!40000 ALTER TABLE `cz_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-10-12 11:17:29
