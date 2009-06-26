-- MySQL dump 10.13  Distrib 5.1.32, for Win32 (ia32)
--
-- Host: localhost    Database: tiki_db_for_acceptance_tests
-- ------------------------------------------------------
-- Server version	5.1.34-community-log

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
-- Table structure for table `galaxia_activities`
--

DROP TABLE IF EXISTS `galaxia_activities`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_activities` (
  `activityId` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `normalized_name` varchar(80) DEFAULT NULL,
  `pId` int(14) NOT NULL DEFAULT '0',
  `type` enum('start','end','split','switch','join','activity','standalone') DEFAULT NULL,
  `isAutoRouted` char(1) DEFAULT NULL,
  `flowNum` int(10) DEFAULT NULL,
  `isInteractive` char(1) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `description` text,
  `expirationTime` int(6) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`activityId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_activities`
--

LOCK TABLES `galaxia_activities` WRITE;
/*!40000 ALTER TABLE `galaxia_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_activity_roles`
--

DROP TABLE IF EXISTS `galaxia_activity_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_activity_roles` (
  `activityId` int(14) NOT NULL DEFAULT '0',
  `roleId` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`activityId`,`roleId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_activity_roles`
--

LOCK TABLES `galaxia_activity_roles` WRITE;
/*!40000 ALTER TABLE `galaxia_activity_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_activity_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_instance_activities`
--

DROP TABLE IF EXISTS `galaxia_instance_activities`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_instance_activities` (
  `instanceId` int(14) NOT NULL DEFAULT '0',
  `activityId` int(14) NOT NULL DEFAULT '0',
  `started` int(14) NOT NULL DEFAULT '0',
  `ended` int(14) NOT NULL DEFAULT '0',
  `user` varchar(200) DEFAULT '',
  `status` enum('running','completed') DEFAULT NULL,
  PRIMARY KEY (`instanceId`,`activityId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_instance_activities`
--

LOCK TABLES `galaxia_instance_activities` WRITE;
/*!40000 ALTER TABLE `galaxia_instance_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_instance_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_instance_comments`
--

DROP TABLE IF EXISTS `galaxia_instance_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_instance_comments` (
  `cId` int(14) NOT NULL AUTO_INCREMENT,
  `instanceId` int(14) NOT NULL DEFAULT '0',
  `user` varchar(200) DEFAULT '',
  `activityId` int(14) DEFAULT NULL,
  `hash` varchar(34) DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `comment` text,
  `activity` varchar(80) DEFAULT NULL,
  `timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`cId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_instance_comments`
--

LOCK TABLES `galaxia_instance_comments` WRITE;
/*!40000 ALTER TABLE `galaxia_instance_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_instance_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_instances`
--

DROP TABLE IF EXISTS `galaxia_instances`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_instances` (
  `instanceId` int(14) NOT NULL AUTO_INCREMENT,
  `pId` int(14) NOT NULL DEFAULT '0',
  `started` int(14) DEFAULT NULL,
  `name` varchar(200) NOT NULL DEFAULT 'No Name',
  `owner` varchar(200) DEFAULT NULL,
  `nextActivity` int(14) DEFAULT NULL,
  `nextUser` varchar(200) DEFAULT NULL,
  `ended` int(14) DEFAULT NULL,
  `status` enum('active','exception','aborted','completed') DEFAULT NULL,
  `properties` longblob,
  PRIMARY KEY (`instanceId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_instances`
--

LOCK TABLES `galaxia_instances` WRITE;
/*!40000 ALTER TABLE `galaxia_instances` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_instances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_processes`
--

DROP TABLE IF EXISTS `galaxia_processes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_processes` (
  `pId` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) DEFAULT NULL,
  `isValid` char(1) DEFAULT NULL,
  `isActive` char(1) DEFAULT NULL,
  `version` varchar(12) DEFAULT NULL,
  `description` text,
  `lastModif` int(14) DEFAULT NULL,
  `normalized_name` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`pId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_processes`
--

LOCK TABLES `galaxia_processes` WRITE;
/*!40000 ALTER TABLE `galaxia_processes` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_processes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_roles`
--

DROP TABLE IF EXISTS `galaxia_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_roles` (
  `roleId` int(14) NOT NULL AUTO_INCREMENT,
  `pId` int(14) NOT NULL DEFAULT '0',
  `lastModif` int(14) DEFAULT NULL,
  `name` varchar(80) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`roleId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_roles`
--

LOCK TABLES `galaxia_roles` WRITE;
/*!40000 ALTER TABLE `galaxia_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_transitions`
--

DROP TABLE IF EXISTS `galaxia_transitions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_transitions` (
  `pId` int(14) NOT NULL DEFAULT '0',
  `actFromId` int(14) NOT NULL DEFAULT '0',
  `actToId` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`actFromId`,`actToId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_transitions`
--

LOCK TABLES `galaxia_transitions` WRITE;
/*!40000 ALTER TABLE `galaxia_transitions` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_transitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_user_roles`
--

DROP TABLE IF EXISTS `galaxia_user_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_user_roles` (
  `pId` int(14) NOT NULL DEFAULT '0',
  `roleId` int(14) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`roleId`,`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_user_roles`
--

LOCK TABLES `galaxia_user_roles` WRITE;
/*!40000 ALTER TABLE `galaxia_user_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_user_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `galaxia_workitems`
--

DROP TABLE IF EXISTS `galaxia_workitems`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `galaxia_workitems` (
  `itemId` int(14) NOT NULL AUTO_INCREMENT,
  `instanceId` int(14) NOT NULL DEFAULT '0',
  `orderId` int(14) NOT NULL DEFAULT '0',
  `activityId` int(14) NOT NULL DEFAULT '0',
  `properties` longblob,
  `started` int(14) DEFAULT NULL,
  `ended` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  PRIMARY KEY (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `galaxia_workitems`
--

LOCK TABLES `galaxia_workitems` WRITE;
/*!40000 ALTER TABLE `galaxia_workitems` DISABLE KEYS */;
/*!40000 ALTER TABLE `galaxia_workitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messu_archive`
--

DROP TABLE IF EXISTS `messu_archive`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `messu_archive` (
  `msgId` int(14) NOT NULL AUTO_INCREMENT,
  `user` varchar(40) NOT NULL DEFAULT '',
  `user_from` varchar(40) NOT NULL DEFAULT '',
  `user_to` text,
  `user_cc` text,
  `user_bcc` text,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `hash` varchar(32) DEFAULT NULL,
  `replyto_hash` varchar(32) DEFAULT NULL,
  `date` int(14) DEFAULT NULL,
  `isRead` char(1) DEFAULT NULL,
  `isReplied` char(1) DEFAULT NULL,
  `isFlagged` char(1) DEFAULT NULL,
  `priority` int(2) DEFAULT NULL,
  PRIMARY KEY (`msgId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `messu_archive`
--

LOCK TABLES `messu_archive` WRITE;
/*!40000 ALTER TABLE `messu_archive` DISABLE KEYS */;
/*!40000 ALTER TABLE `messu_archive` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messu_messages`
--

DROP TABLE IF EXISTS `messu_messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `messu_messages` (
  `msgId` int(14) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) NOT NULL DEFAULT '',
  `user_from` varchar(200) NOT NULL DEFAULT '',
  `user_to` text,
  `user_cc` text,
  `user_bcc` text,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `hash` varchar(32) DEFAULT NULL,
  `replyto_hash` varchar(32) DEFAULT NULL,
  `date` int(14) DEFAULT NULL,
  `isRead` char(1) DEFAULT NULL,
  `isReplied` char(1) DEFAULT NULL,
  `isFlagged` char(1) DEFAULT NULL,
  `priority` int(2) DEFAULT NULL,
  PRIMARY KEY (`msgId`),
  KEY `userIsRead` (`user`,`isRead`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `messu_messages`
--

LOCK TABLES `messu_messages` WRITE;
/*!40000 ALTER TABLE `messu_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `messu_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messu_sent`
--

DROP TABLE IF EXISTS `messu_sent`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `messu_sent` (
  `msgId` int(14) NOT NULL AUTO_INCREMENT,
  `user` varchar(40) NOT NULL DEFAULT '',
  `user_from` varchar(40) NOT NULL DEFAULT '',
  `user_to` text,
  `user_cc` text,
  `user_bcc` text,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `hash` varchar(32) DEFAULT NULL,
  `replyto_hash` varchar(32) DEFAULT NULL,
  `date` int(14) DEFAULT NULL,
  `isRead` char(1) DEFAULT NULL,
  `isReplied` char(1) DEFAULT NULL,
  `isFlagged` char(1) DEFAULT NULL,
  `priority` int(2) DEFAULT NULL,
  PRIMARY KEY (`msgId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `messu_sent`
--

LOCK TABLES `messu_sent` WRITE;
/*!40000 ALTER TABLE `messu_sent` DISABLE KEYS */;
/*!40000 ALTER TABLE `messu_sent` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `sessions` (
  `sesskey` char(32) NOT NULL,
  `expiry` int(11) unsigned NOT NULL,
  `expireref` varchar(64) DEFAULT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`sesskey`),
  KEY `expiry` (`expiry`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_actionlog`
--

DROP TABLE IF EXISTS `tiki_actionlog`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_actionlog` (
  `actionId` int(8) NOT NULL AUTO_INCREMENT,
  `action` varchar(255) NOT NULL DEFAULT '',
  `lastModif` int(14) DEFAULT NULL,
  `object` varchar(255) DEFAULT NULL,
  `objectType` varchar(32) NOT NULL DEFAULT '',
  `user` varchar(200) DEFAULT '',
  `ip` varchar(15) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `categId` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`actionId`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_actionlog`
--

LOCK TABLES `tiki_actionlog` WRITE;
/*!40000 ALTER TABLE `tiki_actionlog` DISABLE KEYS */;
INSERT INTO `tiki_actionlog` VALUES (1,'Created',1245962685,'HomePage','wiki page',NULL,'127.0.0.1','add=3562',0),(2,'Updated',1245980845,'HomePage','wiki page','admin','127.0.0.1','add=168&amp;del=4',0),(3,'Created',1245980884,'EnglishTestPage','wiki page','admin','127.0.0.1','add=31',0);
/*!40000 ALTER TABLE `tiki_actionlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_actionlog_conf`
--

DROP TABLE IF EXISTS `tiki_actionlog_conf`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_actionlog_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(32) NOT NULL DEFAULT '',
  `objectType` varchar(32) NOT NULL DEFAULT '',
  `status` char(1) DEFAULT '',
  PRIMARY KEY (`action`,`objectType`),
  KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=37 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_actionlog_conf`
--

LOCK TABLES `tiki_actionlog_conf` WRITE;
/*!40000 ALTER TABLE `tiki_actionlog_conf` DISABLE KEYS */;
INSERT INTO `tiki_actionlog_conf` VALUES (1,'Created','wiki page','y'),(2,'Updated','wiki page','y'),(3,'Removed','wiki page','y'),(4,'Viewed','wiki page','n'),(5,'Viewed','forum','n'),(6,'Posted','forum','n'),(7,'Replied','forum','n'),(8,'Updated','forum','n'),(9,'Viewed','file gallery','n'),(10,'Viewed','image gallery','n'),(11,'Uploaded','file gallery','n'),(12,'Uploaded','image gallery','n'),(13,'*','category','n'),(14,'*','login','n'),(15,'Posted','message','n'),(16,'Replied','message','n'),(17,'Viewed','message','n'),(18,'Removed version','wiki page','n'),(19,'Removed last version','wiki page','n'),(20,'Rollback','wiki page','n'),(21,'Removed','forum','n'),(22,'Downloaded','file gallery','n'),(23,'Posted','comment','n'),(24,'Replied','comment','n'),(25,'Updated','comment','n'),(26,'Removed','comment','n'),(27,'Renamed','wiki page','n'),(28,'Created','sheet','n'),(29,'Updated','sheet','n'),(30,'Removed','sheet','n'),(31,'Viewed','sheet','n'),(32,'Viewed','blog','n'),(33,'Posted','blog','n'),(34,'Updated','blog','n'),(35,'Removed','blog','n'),(36,'Removed','file','n');
/*!40000 ALTER TABLE `tiki_actionlog_conf` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_actionlog_params`
--

DROP TABLE IF EXISTS `tiki_actionlog_params`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_actionlog_params` (
  `actionId` int(8) NOT NULL,
  `name` varchar(40) NOT NULL,
  `value` text,
  KEY `actionId` (`actionId`),
  KEY `nameValue` (`name`,`value`(200))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_actionlog_params`
--

LOCK TABLES `tiki_actionlog_params` WRITE;
/*!40000 ALTER TABLE `tiki_actionlog_params` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_actionlog_params` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_article_types`
--

DROP TABLE IF EXISTS `tiki_article_types`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_article_types` (
  `type` varchar(50) NOT NULL,
  `use_ratings` varchar(1) DEFAULT NULL,
  `show_pre_publ` varchar(1) DEFAULT NULL,
  `show_post_expire` varchar(1) DEFAULT 'y',
  `heading_only` varchar(1) DEFAULT NULL,
  `allow_comments` varchar(1) DEFAULT 'y',
  `show_image` varchar(1) DEFAULT 'y',
  `show_avatar` varchar(1) DEFAULT NULL,
  `show_author` varchar(1) DEFAULT 'y',
  `show_pubdate` varchar(1) DEFAULT 'y',
  `show_expdate` varchar(1) DEFAULT NULL,
  `show_reads` varchar(1) DEFAULT 'y',
  `show_size` varchar(1) DEFAULT 'n',
  `show_topline` varchar(1) DEFAULT 'n',
  `show_subtitle` varchar(1) DEFAULT 'n',
  `show_linkto` varchar(1) DEFAULT 'n',
  `show_image_caption` varchar(1) DEFAULT 'n',
  `show_lang` varchar(1) DEFAULT 'n',
  `creator_edit` varchar(1) DEFAULT NULL,
  `comment_can_rate_article` char(1) DEFAULT NULL,
  PRIMARY KEY (`type`),
  KEY `show_pre_publ` (`show_pre_publ`),
  KEY `show_post_expire` (`show_post_expire`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_article_types`
--

LOCK TABLES `tiki_article_types` WRITE;
/*!40000 ALTER TABLE `tiki_article_types` DISABLE KEYS */;
INSERT INTO `tiki_article_types` VALUES ('Article',NULL,NULL,'y',NULL,'y','y',NULL,'y','y',NULL,'y','n','n','n','n','n','n',NULL,NULL),('Review','y',NULL,'y',NULL,'y','y',NULL,'y','y',NULL,'y','n','n','n','n','n','n',NULL,NULL),('Event',NULL,NULL,'n',NULL,'y','y',NULL,'y','y',NULL,'y','n','n','n','n','n','n',NULL,NULL),('Classified',NULL,NULL,'n','y','n','y',NULL,'y','y',NULL,'y','n','n','n','n','n','n',NULL,NULL);
/*!40000 ALTER TABLE `tiki_article_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_articles`
--

DROP TABLE IF EXISTS `tiki_articles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_articles` (
  `articleId` int(8) NOT NULL AUTO_INCREMENT,
  `topline` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `linkto` varchar(255) DEFAULT NULL,
  `lang` varchar(16) DEFAULT NULL,
  `state` char(1) DEFAULT 's',
  `authorName` varchar(60) DEFAULT NULL,
  `topicId` int(14) DEFAULT NULL,
  `topicName` varchar(40) DEFAULT NULL,
  `size` int(12) DEFAULT NULL,
  `useImage` char(1) DEFAULT NULL,
  `image_name` varchar(80) DEFAULT NULL,
  `image_caption` text,
  `image_type` varchar(80) DEFAULT NULL,
  `image_size` int(14) DEFAULT NULL,
  `image_x` int(4) DEFAULT NULL,
  `image_y` int(4) DEFAULT NULL,
  `image_data` longblob,
  `publishDate` int(14) DEFAULT NULL,
  `expireDate` int(14) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `heading` text,
  `body` text,
  `hash` varchar(32) DEFAULT NULL,
  `author` varchar(200) DEFAULT NULL,
  `nbreads` int(14) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `points` int(14) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `isfloat` char(1) DEFAULT NULL,
  PRIMARY KEY (`articleId`),
  KEY `title` (`title`),
  KEY `heading` (`heading`(255)),
  KEY `body` (`body`(255)),
  KEY `nbreads` (`nbreads`),
  KEY `author` (`author`(32)),
  KEY `topicId` (`topicId`),
  KEY `publishDate` (`publishDate`),
  KEY `expireDate` (`expireDate`),
  KEY `type` (`type`),
  FULLTEXT KEY `ft` (`title`,`heading`,`body`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_articles`
--

LOCK TABLES `tiki_articles` WRITE;
/*!40000 ALTER TABLE `tiki_articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_banners`
--

DROP TABLE IF EXISTS `tiki_banners`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_banners` (
  `bannerId` int(12) NOT NULL AUTO_INCREMENT,
  `client` varchar(200) NOT NULL DEFAULT '',
  `url` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `alt` varchar(250) DEFAULT NULL,
  `which` varchar(50) DEFAULT NULL,
  `imageData` longblob,
  `imageType` varchar(200) DEFAULT NULL,
  `imageName` varchar(100) DEFAULT NULL,
  `HTMLData` text,
  `fixedURLData` varchar(255) DEFAULT NULL,
  `textData` text,
  `fromDate` int(14) DEFAULT NULL,
  `toDate` int(14) DEFAULT NULL,
  `useDates` char(1) DEFAULT NULL,
  `mon` char(1) DEFAULT NULL,
  `tue` char(1) DEFAULT NULL,
  `wed` char(1) DEFAULT NULL,
  `thu` char(1) DEFAULT NULL,
  `fri` char(1) DEFAULT NULL,
  `sat` char(1) DEFAULT NULL,
  `sun` char(1) DEFAULT NULL,
  `hourFrom` varchar(4) DEFAULT NULL,
  `hourTo` varchar(4) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `maxImpressions` int(8) DEFAULT NULL,
  `impressions` int(8) DEFAULT NULL,
  `maxClicks` int(8) DEFAULT NULL,
  `clicks` int(8) DEFAULT NULL,
  `zone` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`bannerId`),
  KEY `ban1` (`zone`,`useDates`,`impressions`,`maxImpressions`,`hourFrom`,`hourTo`,`fromDate`,`toDate`,`mon`,`tue`,`wed`,`thu`,`fri`,`sat`,`sun`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_banners`
--

LOCK TABLES `tiki_banners` WRITE;
/*!40000 ALTER TABLE `tiki_banners` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_banning`
--

DROP TABLE IF EXISTS `tiki_banning`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_banning` (
  `banId` int(12) NOT NULL AUTO_INCREMENT,
  `mode` enum('user','ip') DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `ip1` char(3) DEFAULT NULL,
  `ip2` char(3) DEFAULT NULL,
  `ip3` char(3) DEFAULT NULL,
  `ip4` char(3) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `date_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `date_to` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `use_dates` char(1) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `message` text,
  PRIMARY KEY (`banId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_banning`
--

LOCK TABLES `tiki_banning` WRITE;
/*!40000 ALTER TABLE `tiki_banning` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_banning` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_banning_sections`
--

DROP TABLE IF EXISTS `tiki_banning_sections`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_banning_sections` (
  `banId` int(12) NOT NULL DEFAULT '0',
  `section` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`banId`,`section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_banning_sections`
--

LOCK TABLES `tiki_banning_sections` WRITE;
/*!40000 ALTER TABLE `tiki_banning_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_banning_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_blog_activity`
--

DROP TABLE IF EXISTS `tiki_blog_activity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_blog_activity` (
  `blogId` int(8) NOT NULL DEFAULT '0',
  `day` int(14) NOT NULL DEFAULT '0',
  `posts` int(8) DEFAULT NULL,
  PRIMARY KEY (`blogId`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_blog_activity`
--

LOCK TABLES `tiki_blog_activity` WRITE;
/*!40000 ALTER TABLE `tiki_blog_activity` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_blog_activity` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_blog_posts`
--

DROP TABLE IF EXISTS `tiki_blog_posts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_blog_posts` (
  `postId` int(8) NOT NULL AUTO_INCREMENT,
  `blogId` int(8) NOT NULL DEFAULT '0',
  `data` text,
  `data_size` int(11) unsigned NOT NULL DEFAULT '0',
  `created` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `trackbacks_to` text,
  `trackbacks_from` text,
  `title` varchar(255) DEFAULT NULL,
  `priv` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`postId`),
  KEY `data` (`data`(255)),
  KEY `blogId` (`blogId`),
  KEY `created` (`created`),
  FULLTEXT KEY `ft` (`data`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_blog_posts`
--

LOCK TABLES `tiki_blog_posts` WRITE;
/*!40000 ALTER TABLE `tiki_blog_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_blog_posts_images`
--

DROP TABLE IF EXISTS `tiki_blog_posts_images`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_blog_posts_images` (
  `imgId` int(14) NOT NULL AUTO_INCREMENT,
  `postId` int(14) NOT NULL DEFAULT '0',
  `filename` varchar(80) DEFAULT NULL,
  `filetype` varchar(80) DEFAULT NULL,
  `filesize` int(14) DEFAULT NULL,
  `data` longblob,
  PRIMARY KEY (`imgId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_blog_posts_images`
--

LOCK TABLES `tiki_blog_posts_images` WRITE;
/*!40000 ALTER TABLE `tiki_blog_posts_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_blog_posts_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_blogs`
--

DROP TABLE IF EXISTS `tiki_blogs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_blogs` (
  `blogId` int(8) NOT NULL AUTO_INCREMENT,
  `created` int(14) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `user` varchar(200) DEFAULT '',
  `public` char(1) DEFAULT NULL,
  `posts` int(8) DEFAULT NULL,
  `maxPosts` int(8) DEFAULT NULL,
  `hits` int(8) DEFAULT NULL,
  `activity` decimal(4,2) DEFAULT NULL,
  `heading` text,
  `use_find` char(1) DEFAULT NULL,
  `use_title` char(1) DEFAULT NULL,
  `add_date` char(1) DEFAULT NULL,
  `add_poster` char(1) DEFAULT NULL,
  `allow_comments` char(1) DEFAULT NULL,
  `show_avatar` char(1) DEFAULT NULL,
  PRIMARY KEY (`blogId`),
  KEY `title` (`title`),
  KEY `description` (`description`(255)),
  KEY `hits` (`hits`),
  FULLTEXT KEY `ft` (`title`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_blogs`
--

LOCK TABLES `tiki_blogs` WRITE;
/*!40000 ALTER TABLE `tiki_blogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_calendar_categories`
--

DROP TABLE IF EXISTS `tiki_calendar_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_calendar_categories` (
  `calcatId` int(11) NOT NULL AUTO_INCREMENT,
  `calendarId` int(14) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`calcatId`),
  UNIQUE KEY `catname` (`calendarId`,`name`(16))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_calendar_categories`
--

LOCK TABLES `tiki_calendar_categories` WRITE;
/*!40000 ALTER TABLE `tiki_calendar_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_calendar_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_calendar_items`
--

DROP TABLE IF EXISTS `tiki_calendar_items`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_calendar_items` (
  `calitemId` int(14) NOT NULL AUTO_INCREMENT,
  `calendarId` int(14) NOT NULL DEFAULT '0',
  `start` int(14) NOT NULL DEFAULT '0',
  `end` int(14) NOT NULL DEFAULT '0',
  `locationId` int(14) DEFAULT NULL,
  `categoryId` int(14) DEFAULT NULL,
  `nlId` int(12) NOT NULL DEFAULT '0',
  `priority` enum('1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '1',
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `lang` char(16) NOT NULL DEFAULT 'en',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `recurrenceId` int(14) DEFAULT NULL,
  `changed` tinyint(1) DEFAULT '0',
  `user` varchar(200) DEFAULT '',
  `created` int(14) NOT NULL DEFAULT '0',
  `lastmodif` int(14) NOT NULL DEFAULT '0',
  `allday` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`calitemId`),
  KEY `calendarId` (`calendarId`),
  KEY `fk_calitems_recurrence` (`recurrenceId`),
  FULLTEXT KEY `ft` (`name`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_calendar_items`
--

LOCK TABLES `tiki_calendar_items` WRITE;
/*!40000 ALTER TABLE `tiki_calendar_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_calendar_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_calendar_locations`
--

DROP TABLE IF EXISTS `tiki_calendar_locations`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_calendar_locations` (
  `callocId` int(14) NOT NULL AUTO_INCREMENT,
  `calendarId` int(14) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` blob,
  PRIMARY KEY (`callocId`),
  UNIQUE KEY `locname` (`calendarId`,`name`(16))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_calendar_locations`
--

LOCK TABLES `tiki_calendar_locations` WRITE;
/*!40000 ALTER TABLE `tiki_calendar_locations` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_calendar_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_calendar_options`
--

DROP TABLE IF EXISTS `tiki_calendar_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_calendar_options` (
  `calendarId` int(14) NOT NULL DEFAULT '0',
  `optionName` varchar(120) NOT NULL DEFAULT '',
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`calendarId`,`optionName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_calendar_options`
--

LOCK TABLES `tiki_calendar_options` WRITE;
/*!40000 ALTER TABLE `tiki_calendar_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_calendar_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_calendar_recurrence`
--

DROP TABLE IF EXISTS `tiki_calendar_recurrence`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_calendar_recurrence` (
  `recurrenceId` int(14) NOT NULL AUTO_INCREMENT,
  `calendarId` int(14) NOT NULL DEFAULT '0',
  `start` int(4) NOT NULL DEFAULT '0',
  `end` int(4) NOT NULL DEFAULT '2359',
  `allday` tinyint(1) NOT NULL DEFAULT '0',
  `locationId` int(14) DEFAULT NULL,
  `categoryId` int(14) DEFAULT NULL,
  `nlId` int(12) NOT NULL DEFAULT '0',
  `priority` enum('1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '1',
  `status` enum('0','1','2') NOT NULL DEFAULT '0',
  `url` varchar(255) DEFAULT NULL,
  `lang` char(16) NOT NULL DEFAULT 'en',
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` blob,
  `weekly` tinyint(1) DEFAULT '0',
  `weekday` tinyint(1) DEFAULT NULL,
  `monthly` tinyint(1) DEFAULT '0',
  `dayOfMonth` int(2) DEFAULT NULL,
  `yearly` tinyint(1) DEFAULT '0',
  `dateOfYear` int(4) DEFAULT NULL,
  `nbRecurrences` int(8) DEFAULT NULL,
  `startPeriod` int(14) DEFAULT NULL,
  `endPeriod` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `created` int(14) NOT NULL DEFAULT '0',
  `lastmodif` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`recurrenceId`),
  KEY `calendarId` (`calendarId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_calendar_recurrence`
--

LOCK TABLES `tiki_calendar_recurrence` WRITE;
/*!40000 ALTER TABLE `tiki_calendar_recurrence` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_calendar_recurrence` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_calendar_roles`
--

DROP TABLE IF EXISTS `tiki_calendar_roles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_calendar_roles` (
  `calitemId` int(14) NOT NULL DEFAULT '0',
  `username` varchar(200) NOT NULL DEFAULT '',
  `role` enum('0','1','2','3','6') NOT NULL DEFAULT '0',
  PRIMARY KEY (`calitemId`,`username`(16),`role`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_calendar_roles`
--

LOCK TABLES `tiki_calendar_roles` WRITE;
/*!40000 ALTER TABLE `tiki_calendar_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_calendar_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_calendars`
--

DROP TABLE IF EXISTS `tiki_calendars`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_calendars` (
  `calendarId` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  `customlocations` enum('n','y') NOT NULL DEFAULT 'n',
  `customcategories` enum('n','y') NOT NULL DEFAULT 'n',
  `customlanguages` enum('n','y') NOT NULL DEFAULT 'n',
  `custompriorities` enum('n','y') NOT NULL DEFAULT 'n',
  `customparticipants` enum('n','y') NOT NULL DEFAULT 'n',
  `customsubscription` enum('n','y') NOT NULL DEFAULT 'n',
  `customstatus` enum('n','y') NOT NULL DEFAULT 'y',
  `created` int(14) NOT NULL DEFAULT '0',
  `lastmodif` int(14) NOT NULL DEFAULT '0',
  `personal` enum('n','y') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`calendarId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_calendars`
--

LOCK TABLES `tiki_calendars` WRITE;
/*!40000 ALTER TABLE `tiki_calendars` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_calendars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_categories`
--

DROP TABLE IF EXISTS `tiki_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_categories` (
  `categId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `parentId` int(12) DEFAULT NULL,
  `hits` int(8) DEFAULT NULL,
  PRIMARY KEY (`categId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_categories`
--

LOCK TABLES `tiki_categories` WRITE;
/*!40000 ALTER TABLE `tiki_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_categorized_objects`
--

DROP TABLE IF EXISTS `tiki_categorized_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_categorized_objects` (
  `catObjectId` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catObjectId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_categorized_objects`
--

LOCK TABLES `tiki_categorized_objects` WRITE;
/*!40000 ALTER TABLE `tiki_categorized_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_categorized_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_category_objects`
--

DROP TABLE IF EXISTS `tiki_category_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_category_objects` (
  `catObjectId` int(12) NOT NULL DEFAULT '0',
  `categId` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catObjectId`,`categId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_category_objects`
--

LOCK TABLES `tiki_category_objects` WRITE;
/*!40000 ALTER TABLE `tiki_category_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_category_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_category_sites`
--

DROP TABLE IF EXISTS `tiki_category_sites`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_category_sites` (
  `categId` int(10) NOT NULL DEFAULT '0',
  `siteId` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`categId`,`siteId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_category_sites`
--

LOCK TABLES `tiki_category_sites` WRITE;
/*!40000 ALTER TABLE `tiki_category_sites` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_category_sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_chart_items`
--

DROP TABLE IF EXISTS `tiki_chart_items`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_chart_items` (
  `itemId` int(14) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `chartId` int(14) NOT NULL DEFAULT '0',
  `created` int(14) DEFAULT NULL,
  `URL` varchar(250) DEFAULT NULL,
  `votes` int(14) DEFAULT NULL,
  `points` int(14) DEFAULT NULL,
  `average` decimal(4,2) DEFAULT NULL,
  PRIMARY KEY (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_chart_items`
--

LOCK TABLES `tiki_chart_items` WRITE;
/*!40000 ALTER TABLE `tiki_chart_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_chart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_charts`
--

DROP TABLE IF EXISTS `tiki_charts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_charts` (
  `chartId` int(14) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `hits` int(14) DEFAULT NULL,
  `singleItemVotes` char(1) DEFAULT NULL,
  `singleChartVotes` char(1) DEFAULT NULL,
  `suggestions` char(1) DEFAULT NULL,
  `autoValidate` char(1) DEFAULT NULL,
  `topN` int(6) DEFAULT NULL,
  `maxVoteValue` int(4) DEFAULT NULL,
  `frequency` int(14) DEFAULT NULL,
  `showAverage` char(1) DEFAULT NULL,
  `isActive` char(1) DEFAULT NULL,
  `showVotes` char(1) DEFAULT NULL,
  `useCookies` char(1) DEFAULT NULL,
  `lastChart` int(14) DEFAULT NULL,
  `voteAgainAfter` int(14) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  PRIMARY KEY (`chartId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_charts`
--

LOCK TABLES `tiki_charts` WRITE;
/*!40000 ALTER TABLE `tiki_charts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_charts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_charts_rankings`
--

DROP TABLE IF EXISTS `tiki_charts_rankings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_charts_rankings` (
  `chartId` int(14) NOT NULL DEFAULT '0',
  `itemId` int(14) NOT NULL DEFAULT '0',
  `position` int(14) NOT NULL DEFAULT '0',
  `timestamp` int(14) NOT NULL DEFAULT '0',
  `lastPosition` int(14) NOT NULL DEFAULT '0',
  `period` int(14) NOT NULL DEFAULT '0',
  `rvotes` int(14) NOT NULL DEFAULT '0',
  `raverage` decimal(4,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`chartId`,`itemId`,`period`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_charts_rankings`
--

LOCK TABLES `tiki_charts_rankings` WRITE;
/*!40000 ALTER TABLE `tiki_charts_rankings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_charts_rankings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_charts_votes`
--

DROP TABLE IF EXISTS `tiki_charts_votes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_charts_votes` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `itemId` int(14) NOT NULL DEFAULT '0',
  `timestamp` int(14) DEFAULT NULL,
  `chartId` int(14) DEFAULT NULL,
  PRIMARY KEY (`user`,`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_charts_votes`
--

LOCK TABLES `tiki_charts_votes` WRITE;
/*!40000 ALTER TABLE `tiki_charts_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_charts_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_chat_channels`
--

DROP TABLE IF EXISTS `tiki_chat_channels`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_chat_channels` (
  `channelId` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  `max_users` int(8) DEFAULT NULL,
  `mode` char(1) DEFAULT NULL,
  `moderator` varchar(200) DEFAULT NULL,
  `active` char(1) DEFAULT NULL,
  `refresh` int(6) DEFAULT NULL,
  PRIMARY KEY (`channelId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_chat_channels`
--

LOCK TABLES `tiki_chat_channels` WRITE;
/*!40000 ALTER TABLE `tiki_chat_channels` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_chat_channels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_chat_messages`
--

DROP TABLE IF EXISTS `tiki_chat_messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_chat_messages` (
  `messageId` int(8) NOT NULL AUTO_INCREMENT,
  `channelId` int(8) NOT NULL DEFAULT '0',
  `data` varchar(255) DEFAULT NULL,
  `poster` varchar(200) NOT NULL DEFAULT 'anonymous',
  `timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`messageId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_chat_messages`
--

LOCK TABLES `tiki_chat_messages` WRITE;
/*!40000 ALTER TABLE `tiki_chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_chat_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_chat_users`
--

DROP TABLE IF EXISTS `tiki_chat_users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_chat_users` (
  `nickname` varchar(200) NOT NULL DEFAULT '',
  `channelId` int(8) NOT NULL DEFAULT '0',
  `timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`nickname`,`channelId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_chat_users`
--

LOCK TABLES `tiki_chat_users` WRITE;
/*!40000 ALTER TABLE `tiki_chat_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_chat_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_comments`
--

DROP TABLE IF EXISTS `tiki_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_comments` (
  `threadId` int(14) NOT NULL AUTO_INCREMENT,
  `object` varchar(255) NOT NULL DEFAULT '',
  `objectType` varchar(32) NOT NULL DEFAULT '',
  `parentId` int(14) DEFAULT NULL,
  `userName` varchar(200) DEFAULT '',
  `commentDate` int(14) DEFAULT NULL,
  `hits` int(8) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `points` decimal(8,2) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `average` decimal(8,4) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `data` text,
  `hash` varchar(32) DEFAULT NULL,
  `user_ip` varchar(15) DEFAULT NULL,
  `summary` varchar(240) DEFAULT NULL,
  `smiley` varchar(80) DEFAULT NULL,
  `message_id` varchar(128) DEFAULT NULL,
  `in_reply_to` varchar(128) DEFAULT NULL,
  `comment_rating` tinyint(2) DEFAULT NULL,
  `archived` char(1) DEFAULT NULL,
  `approved` char(1) NOT NULL DEFAULT 'y',
  PRIMARY KEY (`threadId`),
  UNIQUE KEY `no_repeats` (`parentId`,`userName`(40),`title`(100),`commentDate`,`message_id`(40),`in_reply_to`(40)),
  KEY `title` (`title`),
  KEY `data` (`data`(255)),
  KEY `hits` (`hits`),
  KEY `tc_pi` (`parentId`),
  KEY `objectType` (`object`,`objectType`),
  KEY `commentDate` (`commentDate`),
  KEY `threaded` (`message_id`,`in_reply_to`,`parentId`),
  FULLTEXT KEY `ft` (`title`,`data`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_comments`
--

LOCK TABLES `tiki_comments` WRITE;
/*!40000 ALTER TABLE `tiki_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_content`
--

DROP TABLE IF EXISTS `tiki_content`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_content` (
  `contentId` int(8) NOT NULL AUTO_INCREMENT,
  `description` text,
  `contentLabel` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`contentId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_content`
--

LOCK TABLES `tiki_content` WRITE;
/*!40000 ALTER TABLE `tiki_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_content_templates`
--

DROP TABLE IF EXISTS `tiki_content_templates`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_content_templates` (
  `templateId` int(10) NOT NULL AUTO_INCREMENT,
  `content` longblob,
  `name` varchar(200) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  PRIMARY KEY (`templateId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_content_templates`
--

LOCK TABLES `tiki_content_templates` WRITE;
/*!40000 ALTER TABLE `tiki_content_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_content_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_content_templates_sections`
--

DROP TABLE IF EXISTS `tiki_content_templates_sections`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_content_templates_sections` (
  `templateId` int(10) NOT NULL DEFAULT '0',
  `section` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`templateId`,`section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_content_templates_sections`
--

LOCK TABLES `tiki_content_templates_sections` WRITE;
/*!40000 ALTER TABLE `tiki_content_templates_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_content_templates_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_contributions`
--

DROP TABLE IF EXISTS `tiki_contributions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_contributions` (
  `contributionId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `description` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`contributionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_contributions`
--

LOCK TABLES `tiki_contributions` WRITE;
/*!40000 ALTER TABLE `tiki_contributions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_contributions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_contributions_assigned`
--

DROP TABLE IF EXISTS `tiki_contributions_assigned`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_contributions_assigned` (
  `contributionId` int(12) NOT NULL,
  `objectId` int(12) NOT NULL,
  PRIMARY KEY (`objectId`,`contributionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_contributions_assigned`
--

LOCK TABLES `tiki_contributions_assigned` WRITE;
/*!40000 ALTER TABLE `tiki_contributions_assigned` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_contributions_assigned` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_cookies`
--

DROP TABLE IF EXISTS `tiki_cookies`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_cookies` (
  `cookieId` int(10) NOT NULL AUTO_INCREMENT,
  `cookie` text,
  PRIMARY KEY (`cookieId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_cookies`
--

LOCK TABLES `tiki_cookies` WRITE;
/*!40000 ALTER TABLE `tiki_cookies` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_cookies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_copyrights`
--

DROP TABLE IF EXISTS `tiki_copyrights`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_copyrights` (
  `copyrightId` int(12) NOT NULL AUTO_INCREMENT,
  `page` varchar(200) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `authors` varchar(200) DEFAULT NULL,
  `copyright_order` int(11) DEFAULT NULL,
  `userName` varchar(200) DEFAULT '',
  PRIMARY KEY (`copyrightId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_copyrights`
--

LOCK TABLES `tiki_copyrights` WRITE;
/*!40000 ALTER TABLE `tiki_copyrights` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_copyrights` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_directory_categories`
--

DROP TABLE IF EXISTS `tiki_directory_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_directory_categories` (
  `categId` int(10) NOT NULL AUTO_INCREMENT,
  `parent` int(10) DEFAULT NULL,
  `name` varchar(240) DEFAULT NULL,
  `description` text,
  `childrenType` char(1) DEFAULT NULL,
  `sites` int(10) DEFAULT NULL,
  `viewableChildren` int(4) DEFAULT NULL,
  `allowSites` char(1) DEFAULT NULL,
  `showCount` char(1) DEFAULT NULL,
  `editorGroup` varchar(200) DEFAULT NULL,
  `hits` int(12) DEFAULT NULL,
  PRIMARY KEY (`categId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_directory_categories`
--

LOCK TABLES `tiki_directory_categories` WRITE;
/*!40000 ALTER TABLE `tiki_directory_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_directory_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_directory_search`
--

DROP TABLE IF EXISTS `tiki_directory_search`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_directory_search` (
  `term` varchar(250) NOT NULL DEFAULT '',
  `hits` int(14) DEFAULT NULL,
  PRIMARY KEY (`term`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_directory_search`
--

LOCK TABLES `tiki_directory_search` WRITE;
/*!40000 ALTER TABLE `tiki_directory_search` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_directory_search` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_directory_sites`
--

DROP TABLE IF EXISTS `tiki_directory_sites`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_directory_sites` (
  `siteId` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(240) DEFAULT NULL,
  `description` text,
  `url` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `hits` int(12) DEFAULT NULL,
  `isValid` char(1) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `cache` longblob,
  `cache_timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`siteId`),
  KEY `isValid` (`isValid`),
  KEY `url` (`url`),
  FULLTEXT KEY `ft` (`name`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_directory_sites`
--

LOCK TABLES `tiki_directory_sites` WRITE;
/*!40000 ALTER TABLE `tiki_directory_sites` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_directory_sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_download`
--

DROP TABLE IF EXISTS `tiki_download`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_download` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(255) NOT NULL DEFAULT '',
  `userId` int(8) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL DEFAULT '',
  `date` int(14) NOT NULL DEFAULT '0',
  `IP` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `object` (`object`,`userId`,`type`),
  KEY `userId` (`userId`),
  KEY `type` (`type`),
  KEY `date` (`date`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_download`
--

LOCK TABLES `tiki_download` WRITE;
/*!40000 ALTER TABLE `tiki_download` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_download` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_drawings`
--

DROP TABLE IF EXISTS `tiki_drawings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_drawings` (
  `drawId` int(12) NOT NULL AUTO_INCREMENT,
  `version` int(8) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `filename_draw` varchar(250) DEFAULT NULL,
  `filename_pad` varchar(250) DEFAULT NULL,
  `timestamp` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  PRIMARY KEY (`drawId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_drawings`
--

LOCK TABLES `tiki_drawings` WRITE;
/*!40000 ALTER TABLE `tiki_drawings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_drawings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_dsn`
--

DROP TABLE IF EXISTS `tiki_dsn`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_dsn` (
  `dsnId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `dsn` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`dsnId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_dsn`
--

LOCK TABLES `tiki_dsn` WRITE;
/*!40000 ALTER TABLE `tiki_dsn` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_dsn` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_dynamic_variables`
--

DROP TABLE IF EXISTS `tiki_dynamic_variables`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_dynamic_variables` (
  `name` varchar(40) NOT NULL,
  `data` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_dynamic_variables`
--

LOCK TABLES `tiki_dynamic_variables` WRITE;
/*!40000 ALTER TABLE `tiki_dynamic_variables` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_dynamic_variables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_events`
--

DROP TABLE IF EXISTS `tiki_events`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_events` (
  `callback_type` int(1) NOT NULL DEFAULT '3',
  `order` int(2) NOT NULL DEFAULT '50',
  `event` varchar(200) NOT NULL DEFAULT '',
  `file` varchar(200) NOT NULL DEFAULT '',
  `object` varchar(200) NOT NULL DEFAULT '',
  `method` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`callback_type`,`order`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_events`
--

LOCK TABLES `tiki_events` WRITE;
/*!40000 ALTER TABLE `tiki_events` DISABLE KEYS */;
INSERT INTO `tiki_events` VALUES (1,20,'user_registers','lib/registration/registrationlib.php','registrationlib','callback_tikiwiki_setup_custom_fields'),(3,50,'user_registers','lib/registration/registrationlib.php','registrationlib','callback_tikiwiki_save_registration'),(5,20,'user_registers','lib/registration/registrationlib.php','registrationlib','callback_logslib_user_registers'),(5,25,'user_registers','lib/registration/registrationlib.php','registrationlib','callback_tikiwiki_send_email'),(5,30,'user_registers','lib/registration/registrationlib.php','registrationlib','callback_tikimail_user_registers');
/*!40000 ALTER TABLE `tiki_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_extwiki`
--

DROP TABLE IF EXISTS `tiki_extwiki`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_extwiki` (
  `extwikiId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `extwiki` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`extwikiId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_extwiki`
--

LOCK TABLES `tiki_extwiki` WRITE;
/*!40000 ALTER TABLE `tiki_extwiki` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_extwiki` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_faq_questions`
--

DROP TABLE IF EXISTS `tiki_faq_questions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_faq_questions` (
  `questionId` int(10) NOT NULL AUTO_INCREMENT,
  `faqId` int(10) DEFAULT NULL,
  `position` int(4) DEFAULT NULL,
  `question` text,
  `answer` text,
  PRIMARY KEY (`questionId`),
  KEY `faqId` (`faqId`),
  KEY `question` (`question`(255)),
  KEY `answer` (`answer`(255)),
  FULLTEXT KEY `ft` (`question`,`answer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_faq_questions`
--

LOCK TABLES `tiki_faq_questions` WRITE;
/*!40000 ALTER TABLE `tiki_faq_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_faq_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_faqs`
--

DROP TABLE IF EXISTS `tiki_faqs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_faqs` (
  `faqId` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `created` int(14) DEFAULT NULL,
  `questions` int(5) DEFAULT NULL,
  `hits` int(8) DEFAULT NULL,
  `canSuggest` char(1) DEFAULT NULL,
  PRIMARY KEY (`faqId`),
  KEY `title` (`title`),
  KEY `description` (`description`(255)),
  KEY `hits` (`hits`),
  FULLTEXT KEY `ft` (`title`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_faqs`
--

LOCK TABLES `tiki_faqs` WRITE;
/*!40000 ALTER TABLE `tiki_faqs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_feature`
--

DROP TABLE IF EXISTS `tiki_feature`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_feature` (
  `feature_id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `feature_name` varchar(150) NOT NULL,
  `parent_id` mediumint(9) NOT NULL,
  `status` varchar(12) NOT NULL DEFAULT 'active',
  `setting_name` varchar(50) DEFAULT NULL,
  `feature_type` varchar(30) NOT NULL DEFAULT 'feature',
  `template` varchar(50) DEFAULT NULL,
  `permission` varchar(50) DEFAULT NULL,
  `ordinal` mediumint(9) NOT NULL DEFAULT '1',
  `depends_on` mediumint(9) DEFAULT NULL,
  `keyword` varchar(30) DEFAULT NULL,
  `tip` text,
  `feature_count` mediumint(9) NOT NULL DEFAULT '0',
  `feature_path` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`feature_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_feature`
--

LOCK TABLES `tiki_feature` WRITE;
/*!40000 ALTER TABLE `tiki_feature` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_feature` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_featured_links`
--

DROP TABLE IF EXISTS `tiki_featured_links`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_featured_links` (
  `url` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(200) DEFAULT NULL,
  `description` text,
  `hits` int(8) DEFAULT NULL,
  `position` int(6) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  PRIMARY KEY (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_featured_links`
--

LOCK TABLES `tiki_featured_links` WRITE;
/*!40000 ALTER TABLE `tiki_featured_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_featured_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_file_galleries`
--

DROP TABLE IF EXISTS `tiki_file_galleries`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_file_galleries` (
  `galleryId` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT 'default',
  `description` text,
  `created` int(14) DEFAULT NULL,
  `visible` char(1) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `hits` int(14) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `points` decimal(8,2) DEFAULT NULL,
  `maxRows` int(10) DEFAULT NULL,
  `public` char(1) DEFAULT NULL,
  `show_id` char(1) DEFAULT NULL,
  `show_icon` char(1) DEFAULT NULL,
  `show_name` char(1) DEFAULT NULL,
  `show_size` char(1) DEFAULT NULL,
  `show_description` char(1) DEFAULT NULL,
  `max_desc` int(8) DEFAULT NULL,
  `show_created` char(1) DEFAULT NULL,
  `show_hits` char(1) DEFAULT NULL,
  `parentId` int(14) NOT NULL DEFAULT '-1',
  `lockable` char(1) DEFAULT 'n',
  `show_lockedby` char(1) DEFAULT NULL,
  `archives` int(4) DEFAULT '-1',
  `sort_mode` char(20) DEFAULT NULL,
  `show_modified` char(1) DEFAULT NULL,
  `show_author` char(1) DEFAULT NULL,
  `show_creator` char(1) DEFAULT NULL,
  `subgal_conf` varchar(200) DEFAULT NULL,
  `show_last_user` char(1) DEFAULT NULL,
  `show_comment` char(1) DEFAULT NULL,
  `show_files` char(1) DEFAULT NULL,
  `show_explorer` char(1) DEFAULT NULL,
  `show_path` char(1) DEFAULT NULL,
  PRIMARY KEY (`galleryId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_file_galleries`
--

LOCK TABLES `tiki_file_galleries` WRITE;
/*!40000 ALTER TABLE `tiki_file_galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_file_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_file_handlers`
--

DROP TABLE IF EXISTS `tiki_file_handlers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_file_handlers` (
  `mime_type` varchar(64) DEFAULT NULL,
  `cmd` varchar(238) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_file_handlers`
--

LOCK TABLES `tiki_file_handlers` WRITE;
/*!40000 ALTER TABLE `tiki_file_handlers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_file_handlers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_files`
--

DROP TABLE IF EXISTS `tiki_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_files` (
  `fileId` int(14) NOT NULL AUTO_INCREMENT,
  `galleryId` int(14) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `created` int(14) DEFAULT NULL,
  `filename` varchar(80) DEFAULT NULL,
  `filesize` int(14) DEFAULT NULL,
  `filetype` varchar(250) DEFAULT NULL,
  `data` longblob,
  `user` varchar(200) DEFAULT '',
  `author` varchar(40) DEFAULT NULL,
  `hits` int(14) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `points` decimal(8,2) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `reference_url` varchar(250) DEFAULT NULL,
  `is_reference` char(1) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `search_data` longtext,
  `lastModif` int(14) DEFAULT NULL,
  `lastModifUser` varchar(200) DEFAULT NULL,
  `lockedby` varchar(200) DEFAULT '',
  `comment` varchar(200) DEFAULT NULL,
  `archiveId` int(14) DEFAULT '0',
  PRIMARY KEY (`fileId`),
  KEY `name` (`name`),
  KEY `description` (`description`(255)),
  KEY `created` (`created`),
  KEY `archiveId` (`archiveId`),
  KEY `galleryId` (`galleryId`),
  KEY `hits` (`hits`),
  FULLTEXT KEY `ft` (`name`,`description`,`search_data`,`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_files`
--

LOCK TABLES `tiki_files` WRITE;
/*!40000 ALTER TABLE `tiki_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_forum_attachments`
--

DROP TABLE IF EXISTS `tiki_forum_attachments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_forum_attachments` (
  `attId` int(14) NOT NULL AUTO_INCREMENT,
  `threadId` int(14) NOT NULL DEFAULT '0',
  `qId` int(14) NOT NULL DEFAULT '0',
  `forumId` int(14) DEFAULT NULL,
  `filename` varchar(250) DEFAULT NULL,
  `filetype` varchar(250) DEFAULT NULL,
  `filesize` int(12) DEFAULT NULL,
  `data` longblob,
  `dir` varchar(200) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `path` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`attId`),
  KEY `threadId` (`threadId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_forum_attachments`
--

LOCK TABLES `tiki_forum_attachments` WRITE;
/*!40000 ALTER TABLE `tiki_forum_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_forum_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_forum_reads`
--

DROP TABLE IF EXISTS `tiki_forum_reads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_forum_reads` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `threadId` int(14) NOT NULL DEFAULT '0',
  `forumId` int(14) DEFAULT NULL,
  `timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`user`,`threadId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_forum_reads`
--

LOCK TABLES `tiki_forum_reads` WRITE;
/*!40000 ALTER TABLE `tiki_forum_reads` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_forum_reads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_forums`
--

DROP TABLE IF EXISTS `tiki_forums`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_forums` (
  `forumId` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `created` int(14) DEFAULT NULL,
  `lastPost` int(14) DEFAULT NULL,
  `threads` int(8) DEFAULT NULL,
  `comments` int(8) DEFAULT NULL,
  `controlFlood` char(1) DEFAULT NULL,
  `floodInterval` int(8) DEFAULT NULL,
  `moderator` varchar(200) DEFAULT NULL,
  `hits` int(8) DEFAULT NULL,
  `mail` varchar(200) DEFAULT NULL,
  `useMail` char(1) DEFAULT NULL,
  `section` varchar(200) DEFAULT NULL,
  `usePruneUnreplied` char(1) DEFAULT NULL,
  `pruneUnrepliedAge` int(8) DEFAULT NULL,
  `usePruneOld` char(1) DEFAULT NULL,
  `pruneMaxAge` int(8) DEFAULT NULL,
  `topicsPerPage` int(6) DEFAULT NULL,
  `topicOrdering` varchar(100) DEFAULT NULL,
  `threadOrdering` varchar(100) DEFAULT NULL,
  `att` varchar(80) DEFAULT NULL,
  `att_store` varchar(4) DEFAULT NULL,
  `att_store_dir` varchar(250) DEFAULT NULL,
  `att_max_size` int(12) DEFAULT NULL,
  `ui_level` char(1) DEFAULT NULL,
  `forum_password` varchar(32) DEFAULT NULL,
  `forum_use_password` char(1) DEFAULT NULL,
  `moderator_group` varchar(200) DEFAULT NULL,
  `approval_type` varchar(20) DEFAULT NULL,
  `outbound_address` varchar(250) DEFAULT NULL,
  `outbound_mails_for_inbound_mails` char(1) DEFAULT NULL,
  `outbound_mails_reply_link` char(1) DEFAULT NULL,
  `outbound_from` varchar(250) DEFAULT NULL,
  `inbound_pop_server` varchar(250) DEFAULT NULL,
  `inbound_pop_port` int(4) DEFAULT NULL,
  `inbound_pop_user` varchar(200) DEFAULT NULL,
  `inbound_pop_password` varchar(80) DEFAULT NULL,
  `topic_smileys` char(1) DEFAULT NULL,
  `ui_avatar` char(1) DEFAULT NULL,
  `ui_flag` char(1) DEFAULT NULL,
  `ui_posts` char(1) DEFAULT NULL,
  `ui_email` char(1) DEFAULT NULL,
  `ui_online` char(1) DEFAULT NULL,
  `topic_summary` char(1) DEFAULT NULL,
  `show_description` char(1) DEFAULT NULL,
  `topics_list_replies` char(1) DEFAULT NULL,
  `topics_list_reads` char(1) DEFAULT NULL,
  `topics_list_pts` char(1) DEFAULT NULL,
  `topics_list_lastpost` char(1) DEFAULT NULL,
  `topics_list_author` char(1) DEFAULT NULL,
  `vote_threads` char(1) DEFAULT NULL,
  `forum_last_n` int(2) DEFAULT '0',
  `threadStyle` varchar(100) DEFAULT NULL,
  `commentsPerPage` varchar(100) DEFAULT NULL,
  `is_flat` char(1) DEFAULT NULL,
  `mandatory_contribution` char(1) DEFAULT NULL,
  PRIMARY KEY (`forumId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_forums`
--

LOCK TABLES `tiki_forums` WRITE;
/*!40000 ALTER TABLE `tiki_forums` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_forums` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_forums_queue`
--

DROP TABLE IF EXISTS `tiki_forums_queue`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_forums_queue` (
  `qId` int(14) NOT NULL AUTO_INCREMENT,
  `object` varchar(32) DEFAULT NULL,
  `parentId` int(14) DEFAULT NULL,
  `forumId` int(14) DEFAULT NULL,
  `timestamp` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `title` varchar(240) DEFAULT NULL,
  `data` text,
  `type` varchar(60) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `topic_smiley` varchar(80) DEFAULT NULL,
  `topic_title` varchar(240) DEFAULT NULL,
  `summary` varchar(240) DEFAULT NULL,
  `in_reply_to` varchar(128) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`qId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_forums_queue`
--

LOCK TABLES `tiki_forums_queue` WRITE;
/*!40000 ALTER TABLE `tiki_forums_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_forums_queue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_forums_reported`
--

DROP TABLE IF EXISTS `tiki_forums_reported`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_forums_reported` (
  `threadId` int(12) NOT NULL DEFAULT '0',
  `forumId` int(12) NOT NULL DEFAULT '0',
  `parentId` int(12) NOT NULL DEFAULT '0',
  `user` varchar(200) DEFAULT '',
  `timestamp` int(14) DEFAULT NULL,
  `reason` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`threadId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_forums_reported`
--

LOCK TABLES `tiki_forums_reported` WRITE;
/*!40000 ALTER TABLE `tiki_forums_reported` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_forums_reported` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_freetagged_objects`
--

DROP TABLE IF EXISTS `tiki_freetagged_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_freetagged_objects` (
  `tagId` int(12) NOT NULL AUTO_INCREMENT,
  `objectId` int(11) NOT NULL DEFAULT '0',
  `user` varchar(200) NOT NULL DEFAULT '',
  `created` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tagId`,`user`,`objectId`),
  KEY `tagId` (`tagId`),
  KEY `user` (`user`),
  KEY `objectId` (`objectId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_freetagged_objects`
--

LOCK TABLES `tiki_freetagged_objects` WRITE;
/*!40000 ALTER TABLE `tiki_freetagged_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_freetagged_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_freetags`
--

DROP TABLE IF EXISTS `tiki_freetags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_freetags` (
  `tagId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tag` varchar(30) NOT NULL DEFAULT '',
  `raw_tag` varchar(50) NOT NULL DEFAULT '',
  `lang` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`tagId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_freetags`
--

LOCK TABLES `tiki_freetags` WRITE;
/*!40000 ALTER TABLE `tiki_freetags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_freetags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_friends`
--

DROP TABLE IF EXISTS `tiki_friends`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_friends` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `friend` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`user`(120),`friend`(120))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_friends`
--

LOCK TABLES `tiki_friends` WRITE;
/*!40000 ALTER TABLE `tiki_friends` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_friends` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_friendship_requests`
--

DROP TABLE IF EXISTS `tiki_friendship_requests`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_friendship_requests` (
  `userFrom` varchar(200) NOT NULL DEFAULT '',
  `userTo` varchar(200) NOT NULL DEFAULT '',
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`userFrom`(120),`userTo`(120))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_friendship_requests`
--

LOCK TABLES `tiki_friendship_requests` WRITE;
/*!40000 ALTER TABLE `tiki_friendship_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_friendship_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_galleries`
--

DROP TABLE IF EXISTS `tiki_galleries`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_galleries` (
  `galleryId` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(80) NOT NULL DEFAULT '',
  `description` text,
  `created` int(14) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `visible` char(1) DEFAULT NULL,
  `geographic` char(1) DEFAULT NULL,
  `theme` varchar(60) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `hits` int(14) DEFAULT NULL,
  `maxRows` int(10) DEFAULT NULL,
  `rowImages` int(10) DEFAULT NULL,
  `thumbSizeX` int(10) DEFAULT NULL,
  `thumbSizeY` int(10) DEFAULT NULL,
  `public` char(1) DEFAULT NULL,
  `sortorder` varchar(20) NOT NULL DEFAULT 'created',
  `sortdirection` varchar(4) NOT NULL DEFAULT 'desc',
  `galleryimage` varchar(20) NOT NULL DEFAULT 'first',
  `parentgallery` int(14) NOT NULL DEFAULT '-1',
  `showname` char(1) NOT NULL DEFAULT 'y',
  `showimageid` char(1) NOT NULL DEFAULT 'n',
  `showdescription` char(1) NOT NULL DEFAULT 'n',
  `showcreated` char(1) NOT NULL DEFAULT 'n',
  `showuser` char(1) NOT NULL DEFAULT 'n',
  `showhits` char(1) NOT NULL DEFAULT 'y',
  `showxysize` char(1) NOT NULL DEFAULT 'y',
  `showfilesize` char(1) NOT NULL DEFAULT 'n',
  `showfilename` char(1) NOT NULL DEFAULT 'n',
  `defaultscale` varchar(10) NOT NULL DEFAULT 'o',
  `showcategories` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`galleryId`),
  KEY `name` (`name`),
  KEY `description` (`description`(255)),
  KEY `hits` (`hits`),
  KEY `parentgallery` (`parentgallery`),
  KEY `visibleUser` (`visible`,`user`),
  FULLTEXT KEY `ft` (`name`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_galleries`
--

LOCK TABLES `tiki_galleries` WRITE;
/*!40000 ALTER TABLE `tiki_galleries` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_galleries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_galleries_scales`
--

DROP TABLE IF EXISTS `tiki_galleries_scales`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_galleries_scales` (
  `galleryId` int(14) NOT NULL DEFAULT '0',
  `scale` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`galleryId`,`scale`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_galleries_scales`
--

LOCK TABLES `tiki_galleries_scales` WRITE;
/*!40000 ALTER TABLE `tiki_galleries_scales` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_galleries_scales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_games`
--

DROP TABLE IF EXISTS `tiki_games`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_games` (
  `gameName` varchar(200) NOT NULL DEFAULT '',
  `hits` int(8) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `points` int(8) DEFAULT NULL,
  PRIMARY KEY (`gameName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_games`
--

LOCK TABLES `tiki_games` WRITE;
/*!40000 ALTER TABLE `tiki_games` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_group_inclusion`
--

DROP TABLE IF EXISTS `tiki_group_inclusion`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_group_inclusion` (
  `groupName` varchar(255) NOT NULL DEFAULT '',
  `includeGroup` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`groupName`(30),`includeGroup`(30))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_group_inclusion`
--

LOCK TABLES `tiki_group_inclusion` WRITE;
/*!40000 ALTER TABLE `tiki_group_inclusion` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_group_inclusion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_group_watches`
--

DROP TABLE IF EXISTS `tiki_group_watches`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_group_watches` (
  `watchId` int(12) NOT NULL AUTO_INCREMENT,
  `group` varchar(200) NOT NULL DEFAULT '',
  `event` varchar(40) NOT NULL DEFAULT '',
  `object` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(250) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`group`(50),`event`,`object`(100)),
  KEY `watchId` (`watchId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_group_watches`
--

LOCK TABLES `tiki_group_watches` WRITE;
/*!40000 ALTER TABLE `tiki_group_watches` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_group_watches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_groupalert`
--

DROP TABLE IF EXISTS `tiki_groupalert`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_groupalert` (
  `groupName` varchar(255) NOT NULL DEFAULT '',
  `objectType` varchar(20) NOT NULL DEFAULT '',
  `objectId` varchar(10) NOT NULL DEFAULT '',
  `displayEachuser` char(1) DEFAULT NULL,
  PRIMARY KEY (`objectType`,`objectId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_groupalert`
--

LOCK TABLES `tiki_groupalert` WRITE;
/*!40000 ALTER TABLE `tiki_groupalert` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_groupalert` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_history`
--

DROP TABLE IF EXISTS `tiki_history`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_history` (
  `historyId` int(12) NOT NULL AUTO_INCREMENT,
  `pageName` varchar(160) NOT NULL DEFAULT '',
  `version` int(8) NOT NULL DEFAULT '0',
  `version_minor` int(8) NOT NULL DEFAULT '0',
  `lastModif` int(14) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  `ip` varchar(15) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `data` longblob,
  `type` varchar(50) DEFAULT NULL,
  `is_html` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pageName`,`version`),
  KEY `user` (`user`),
  KEY `historyId` (`historyId`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_history`
--

LOCK TABLES `tiki_history` WRITE;
/*!40000 ALTER TABLE `tiki_history` DISABLE KEYS */;
INSERT INTO `tiki_history` VALUES (1,'HomePage',1,0,1245962685,'','admin','0.0.0.0','Tiki initialization','{GROUP(groups=Admins)}\n!Thank you for installing Tiki.\n\nThe entire Tiki Community would like to thank you and help you get introduced to Tiki.\n\n!How To Get Started\nTiki has more than 1000 features and settings.\n\nThis allows you to create both very simple and complex websites.\n\nWe understand that so many features might seem overwhelming at first. This is why we offer you two different ways to __Get Started__ with Tiki.\n\n{SPLIT()}\n-=Easy Setup using Profiles=-\n!![tiki-admin.php?profile=&category=Featured+profiles&repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&preloadlist=y&page=profiles&list=List#profile-results|Get Started using Profiles]\n__Who Should Use This__\n*You want to get started quickly\n*You don\'t feel like learning the Admin Panel right away\n*You want to quickly test out some of Tiki\'s Features\n\n!!Featured Profiles\n\n__Collaborative Community__ ([tiki-admin.php?profile=&category=Featured+profiles&repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&preloadlist=y&page=profiles&list=List#profile-results|install profile now])\nSetup to help subject experts and enthusiasts work together to build a Knowledge Base\n*Wiki Editing\n*Personal Member Spaces\n*Forums\n*Blogs\n\n__Personal Blog and Profile__ ([tiki-admin.php?profile=&category=Featured+profiles&repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&preloadlist=y&page=profiles&list=List#profile-results|install profile now])\nSetup with many cool features to help you integrate the Social Web and establish a strong presence in the Blogosphere\n*Blog (Full set of blog related features)\n*Image Gallery\n*Calendar\n*RSS Integration\n*Video Log\n\n__Company Intranet__ ([tiki-admin.php?profile=&category=Featured+profiles&repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&preloadlist=y&page=profiles&list=List#profile-results|install profile now])\nSetup for a Corporate Intranet of a typical medium-sized business.\n*Company News Articles\n*Executive Blog\n*File Repository & Management\n*Collaborative Wiki\n\n__Small Organization Web Presence__ ([tiki-admin.php?profile=&category=Featured+profiles&repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&preloadlist=y&page=profiles&list=List#profile-results|install profile now])\nSetup for a Web Presence of a typical small business or non-profit.\n*Company News & Updates\n*Highlight Company\'s Products and Services\n*File Gallery (great for Media Kit)\n*Contact Form\n---\n-=Manual Setup using Admin Panel=-\n!![tiki-admin.php|Get Started using Admin Panel]\n__Who Should Use This__\n*You are familiar with software Admin Panels\n*You enjoy exploring and playing with many options\n*You already know Tiki\n\n\n{SPLIT}\n{ELSE}\n\n!Congratulations\nThis is the default ))HomePage(( for your Tiki. If you are seeing this page, your installation was successful.\n\nYou can change this page after logging in. Please review the [http://doc.tikiwiki.org/wiki+syntax|wiki syntax] for editing details.\n\n\n!!{img src=pics/icons/star.png alt=\"Star\"} Get started.\nTo begin configuring your site:\n{FANCYLIST()}\n1) Log in as the __admin__ with password __admin__.\n2) Change the admin password.\n3) Enable specific Tiki features.\n4) Configure the features.\n{FANCYLIST}\n\n!!{img src=pics/icons/help.png alt=\"Help\"} Need help?\nFor more information:\n*[http://info.tikiwiki.org/Learn+More|Learn more about TikiWiki].\n*[http://info.tikiwiki.org/Help+Others|Get help], including the [http://doc.tikiwiki.org|official documentation] and [http://www.tikiwiki.org/forums|support forums].\n*[http://info.tikiwiki.org/Join+the+community|Join the TikiWiki community].\n{GROUP}',NULL,0);
/*!40000 ALTER TABLE `tiki_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_hotwords`
--

DROP TABLE IF EXISTS `tiki_hotwords`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_hotwords` (
  `word` varchar(40) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_hotwords`
--

LOCK TABLES `tiki_hotwords` WRITE;
/*!40000 ALTER TABLE `tiki_hotwords` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_hotwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_html_pages`
--

DROP TABLE IF EXISTS `tiki_html_pages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_html_pages` (
  `pageName` varchar(200) NOT NULL DEFAULT '',
  `content` longblob,
  `refresh` int(10) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  PRIMARY KEY (`pageName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_html_pages`
--

LOCK TABLES `tiki_html_pages` WRITE;
/*!40000 ALTER TABLE `tiki_html_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_html_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_html_pages_dynamic_zones`
--

DROP TABLE IF EXISTS `tiki_html_pages_dynamic_zones`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_html_pages_dynamic_zones` (
  `pageName` varchar(40) NOT NULL DEFAULT '',
  `zone` varchar(80) NOT NULL DEFAULT '',
  `type` char(2) DEFAULT NULL,
  `content` text,
  PRIMARY KEY (`pageName`,`zone`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_html_pages_dynamic_zones`
--

LOCK TABLES `tiki_html_pages_dynamic_zones` WRITE;
/*!40000 ALTER TABLE `tiki_html_pages_dynamic_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_html_pages_dynamic_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_images`
--

DROP TABLE IF EXISTS `tiki_images`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_images` (
  `imageId` int(14) NOT NULL AUTO_INCREMENT,
  `galleryId` int(14) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `lon` float DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `hits` int(14) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`imageId`),
  KEY `name` (`name`),
  KEY `description` (`description`(255)),
  KEY `hits` (`hits`),
  KEY `ti_gId` (`galleryId`),
  KEY `ti_cr` (`created`),
  KEY `ti_us` (`user`),
  FULLTEXT KEY `ft` (`name`,`description`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_images`
--

LOCK TABLES `tiki_images` WRITE;
/*!40000 ALTER TABLE `tiki_images` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_images_data`
--

DROP TABLE IF EXISTS `tiki_images_data`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_images_data` (
  `imageId` int(14) NOT NULL DEFAULT '0',
  `xsize` int(8) NOT NULL DEFAULT '0',
  `ysize` int(8) NOT NULL DEFAULT '0',
  `type` char(1) NOT NULL DEFAULT '',
  `filesize` int(14) DEFAULT NULL,
  `filetype` varchar(80) DEFAULT NULL,
  `filename` varchar(80) DEFAULT NULL,
  `data` longblob,
  `etag` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`imageId`,`xsize`,`ysize`,`type`),
  KEY `t_i_d_it` (`imageId`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_images_data`
--

LOCK TABLES `tiki_images_data` WRITE;
/*!40000 ALTER TABLE `tiki_images_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_images_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_integrator_reps`
--

DROP TABLE IF EXISTS `tiki_integrator_reps`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_integrator_reps` (
  `repID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `start_page` varchar(255) NOT NULL DEFAULT '',
  `css_file` varchar(255) NOT NULL DEFAULT '',
  `visibility` char(1) NOT NULL DEFAULT 'y',
  `cacheable` char(1) NOT NULL DEFAULT 'y',
  `expiration` int(11) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`repID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_integrator_reps`
--

LOCK TABLES `tiki_integrator_reps` WRITE;
/*!40000 ALTER TABLE `tiki_integrator_reps` DISABLE KEYS */;
INSERT INTO `tiki_integrator_reps` VALUES (1,'Doxygened (1.3.4) Documentation','','index.html','doxygen.css','n','y',0,'Use this repository as rule source for all your repositories based on doxygened docs. To setup yours just add new repository and copy rules from this repository :)');
/*!40000 ALTER TABLE `tiki_integrator_reps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_integrator_rules`
--

DROP TABLE IF EXISTS `tiki_integrator_rules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_integrator_rules` (
  `ruleID` int(11) NOT NULL AUTO_INCREMENT,
  `repID` int(11) NOT NULL DEFAULT '0',
  `ord` int(2) unsigned NOT NULL DEFAULT '0',
  `srch` blob NOT NULL,
  `repl` blob NOT NULL,
  `type` char(1) NOT NULL DEFAULT 'n',
  `casesense` char(1) NOT NULL DEFAULT 'y',
  `rxmod` varchar(20) NOT NULL DEFAULT '',
  `enabled` char(1) NOT NULL DEFAULT 'n',
  `description` text NOT NULL,
  PRIMARY KEY (`ruleID`),
  KEY `repID` (`repID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_integrator_rules`
--

LOCK TABLES `tiki_integrator_rules` WRITE;
/*!40000 ALTER TABLE `tiki_integrator_rules` DISABLE KEYS */;
INSERT INTO `tiki_integrator_rules` VALUES (1,1,1,'.*<body[^>]*?>(.*?)</body.*','1','y','n','i','y','Extract code between <body> and </body> tags'),(2,1,2,'img src=(\"|\')(?!http://)','img src=1{path}/','y','n','i','y','Fix image paths'),(3,1,3,'href=(\"|\')(?!(#|(http|ftp)://))','href=1tiki-integrator.php?repID={repID}&file=','y','n','i','y','Replace internal links to integrator. Don\'t touch an external link.');
/*!40000 ALTER TABLE `tiki_integrator_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_language`
--

DROP TABLE IF EXISTS `tiki_language`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_language` (
  `source` tinyblob NOT NULL,
  `lang` char(16) NOT NULL DEFAULT '',
  `tran` tinyblob,
  PRIMARY KEY (`source`(255),`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_language`
--

LOCK TABLES `tiki_language` WRITE;
/*!40000 ALTER TABLE `tiki_language` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_language` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_languages`
--

DROP TABLE IF EXISTS `tiki_languages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_languages` (
  `lang` char(16) NOT NULL DEFAULT '',
  `language` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_languages`
--

LOCK TABLES `tiki_languages` WRITE;
/*!40000 ALTER TABLE `tiki_languages` DISABLE KEYS */;
INSERT INTO `tiki_languages` VALUES ('en','English');
/*!40000 ALTER TABLE `tiki_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_link_cache`
--

DROP TABLE IF EXISTS `tiki_link_cache`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_link_cache` (
  `cacheId` int(14) NOT NULL AUTO_INCREMENT,
  `url` varchar(250) DEFAULT NULL,
  `data` longblob,
  `refresh` int(14) DEFAULT NULL,
  PRIMARY KEY (`cacheId`),
  KEY `url` (`url`),
  KEY `urlindex` (`url`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_link_cache`
--

LOCK TABLES `tiki_link_cache` WRITE;
/*!40000 ALTER TABLE `tiki_link_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_link_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_links`
--

DROP TABLE IF EXISTS `tiki_links`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_links` (
  `fromPage` varchar(160) NOT NULL DEFAULT '',
  `toPage` varchar(160) NOT NULL DEFAULT '',
  `reltype` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`fromPage`,`toPage`),
  KEY `toPage` (`toPage`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_links`
--

LOCK TABLES `tiki_links` WRITE;
/*!40000 ALTER TABLE `tiki_links` DISABLE KEYS */;
INSERT INTO `tiki_links` VALUES ('HomePage','EnglishTestPage',NULL);
/*!40000 ALTER TABLE `tiki_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_live_support_events`
--

DROP TABLE IF EXISTS `tiki_live_support_events`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_live_support_events` (
  `eventId` int(14) NOT NULL AUTO_INCREMENT,
  `reqId` varchar(32) NOT NULL DEFAULT '',
  `type` varchar(40) DEFAULT NULL,
  `seqId` int(14) DEFAULT NULL,
  `senderId` varchar(32) DEFAULT NULL,
  `data` text,
  `timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`eventId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_live_support_events`
--

LOCK TABLES `tiki_live_support_events` WRITE;
/*!40000 ALTER TABLE `tiki_live_support_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_live_support_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_live_support_message_comments`
--

DROP TABLE IF EXISTS `tiki_live_support_message_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_live_support_message_comments` (
  `cId` int(12) NOT NULL AUTO_INCREMENT,
  `msgId` int(12) DEFAULT NULL,
  `data` text,
  `timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`cId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_live_support_message_comments`
--

LOCK TABLES `tiki_live_support_message_comments` WRITE;
/*!40000 ALTER TABLE `tiki_live_support_message_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_live_support_message_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_live_support_messages`
--

DROP TABLE IF EXISTS `tiki_live_support_messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_live_support_messages` (
  `msgId` int(12) NOT NULL AUTO_INCREMENT,
  `data` text,
  `timestamp` int(14) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  `username` varchar(200) DEFAULT NULL,
  `priority` int(2) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `assigned_to` varchar(200) DEFAULT NULL,
  `resolution` varchar(100) DEFAULT NULL,
  `title` varchar(200) DEFAULT NULL,
  `module` int(4) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`msgId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_live_support_messages`
--

LOCK TABLES `tiki_live_support_messages` WRITE;
/*!40000 ALTER TABLE `tiki_live_support_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_live_support_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_live_support_modules`
--

DROP TABLE IF EXISTS `tiki_live_support_modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_live_support_modules` (
  `modId` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(90) DEFAULT NULL,
  PRIMARY KEY (`modId`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_live_support_modules`
--

LOCK TABLES `tiki_live_support_modules` WRITE;
/*!40000 ALTER TABLE `tiki_live_support_modules` DISABLE KEYS */;
INSERT INTO `tiki_live_support_modules` VALUES (1,'wiki'),(2,'forums'),(3,'image galleries'),(4,'file galleries'),(5,'directory'),(6,'workflow'),(7,'charts');
/*!40000 ALTER TABLE `tiki_live_support_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_live_support_operators`
--

DROP TABLE IF EXISTS `tiki_live_support_operators`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_live_support_operators` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `accepted_requests` int(10) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `longest_chat` int(10) DEFAULT NULL,
  `shortest_chat` int(10) DEFAULT NULL,
  `average_chat` int(10) DEFAULT NULL,
  `last_chat` int(14) DEFAULT NULL,
  `time_online` int(10) DEFAULT NULL,
  `votes` int(10) DEFAULT NULL,
  `points` int(10) DEFAULT NULL,
  `status_since` int(14) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_live_support_operators`
--

LOCK TABLES `tiki_live_support_operators` WRITE;
/*!40000 ALTER TABLE `tiki_live_support_operators` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_live_support_operators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_live_support_requests`
--

DROP TABLE IF EXISTS `tiki_live_support_requests`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_live_support_requests` (
  `reqId` varchar(32) NOT NULL DEFAULT '',
  `user` varchar(200) NOT NULL DEFAULT '',
  `tiki_user` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `operator` varchar(200) DEFAULT NULL,
  `operator_id` varchar(32) DEFAULT NULL,
  `user_id` varchar(32) DEFAULT NULL,
  `reason` text,
  `req_timestamp` int(14) DEFAULT NULL,
  `timestamp` int(14) DEFAULT NULL,
  `status` varchar(40) DEFAULT NULL,
  `resolution` varchar(40) DEFAULT NULL,
  `chat_started` int(14) DEFAULT NULL,
  `chat_ended` int(14) DEFAULT NULL,
  PRIMARY KEY (`reqId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_live_support_requests`
--

LOCK TABLES `tiki_live_support_requests` WRITE;
/*!40000 ALTER TABLE `tiki_live_support_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_live_support_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_logs`
--

DROP TABLE IF EXISTS `tiki_logs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_logs` (
  `logId` int(8) NOT NULL AUTO_INCREMENT,
  `logtype` varchar(20) NOT NULL,
  `logmessage` text NOT NULL,
  `loguser` varchar(40) NOT NULL,
  `logip` varchar(200) DEFAULT NULL,
  `logclient` text NOT NULL,
  `logtime` int(14) NOT NULL,
  PRIMARY KEY (`logId`),
  KEY `logtype` (`logtype`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_logs`
--

LOCK TABLES `tiki_logs` WRITE;
/*!40000 ALTER TABLE `tiki_logs` DISABLE KEYS */;
INSERT INTO `tiki_logs` VALUES (1,'login','logged from change_password','admin','127.0.0.1','Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.11) Gecko/2009060215 Firefox/3.0.11 (.NET CLR 3.5.30729)',1245980718);
/*!40000 ALTER TABLE `tiki_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_mail_events`
--

DROP TABLE IF EXISTS `tiki_mail_events`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_mail_events` (
  `event` varchar(200) DEFAULT NULL,
  `object` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_mail_events`
--

LOCK TABLES `tiki_mail_events` WRITE;
/*!40000 ALTER TABLE `tiki_mail_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_mail_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_mailin_accounts`
--

DROP TABLE IF EXISTS `tiki_mailin_accounts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_mailin_accounts` (
  `accountId` int(12) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) NOT NULL DEFAULT '',
  `account` varchar(50) NOT NULL DEFAULT '',
  `pop` varchar(255) DEFAULT NULL,
  `port` int(4) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `active` char(1) DEFAULT NULL,
  `type` varchar(40) DEFAULT NULL,
  `smtp` varchar(255) DEFAULT NULL,
  `useAuth` char(1) DEFAULT NULL,
  `smtpPort` int(4) DEFAULT NULL,
  `anonymous` char(1) NOT NULL DEFAULT 'y',
  `attachments` char(1) NOT NULL DEFAULT 'n',
  `article_topicId` int(4) DEFAULT NULL,
  `article_type` varchar(50) DEFAULT NULL,
  `discard_after` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`accountId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_mailin_accounts`
--

LOCK TABLES `tiki_mailin_accounts` WRITE;
/*!40000 ALTER TABLE `tiki_mailin_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_mailin_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_menu_languages`
--

DROP TABLE IF EXISTS `tiki_menu_languages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_menu_languages` (
  `menuId` int(8) NOT NULL AUTO_INCREMENT,
  `language` char(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`menuId`,`language`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_menu_languages`
--

LOCK TABLES `tiki_menu_languages` WRITE;
/*!40000 ALTER TABLE `tiki_menu_languages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_menu_languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_menu_options`
--

DROP TABLE IF EXISTS `tiki_menu_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_menu_options` (
  `optionId` int(8) NOT NULL AUTO_INCREMENT,
  `menuId` int(8) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `position` int(4) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `perm` varchar(255) DEFAULT NULL,
  `groupname` varchar(255) DEFAULT NULL,
  `userlevel` int(4) DEFAULT '0',
  PRIMARY KEY (`optionId`),
  UNIQUE KEY `uniq_menu` (`menuId`,`name`(30),`url`(50),`position`,`section`(60),`perm`(50),`groupname`(50))
) ENGINE=MyISAM AUTO_INCREMENT=202 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_menu_options`
--

LOCK TABLES `tiki_menu_options` WRITE;
/*!40000 ALTER TABLE `tiki_menu_options` DISABLE KEYS */;
INSERT INTO `tiki_menu_options` VALUES (1,42,'o','Home','./',10,'','','',0),(3,42,'o','Contact Us','tiki-contact.php',20,'feature_contact','','',0),(4,42,'o','Stats','tiki-stats.php',23,'feature_stats','tiki_p_view_stats','',0),(5,42,'o','Categories','tiki-browse_categories.php',25,'feature_categories','tiki_p_view_categories','',0),(6,42,'o','Games','tiki-list_games.php',30,'feature_games','tiki_p_play_games','',0),(7,42,'o','Calendar','tiki-calendar.php',35,'feature_calendar','tiki_p_view_calendar','',0),(8,42,'o','Users Map','tiki-gmap_usermap.php',36,'feature_gmap','','',0),(9,42,'o','Mobile','tiki-mobile.php',37,'feature_mobile','','',0),(10,42,'o','(debug)','javascript:toggle(\'debugconsole\')',40,'feature_debug_console','tiki_p_admin','',0),(11,42,'s','MyTiki','tiki-my_tiki.php',50,'feature_mytiki','','Registered',0),(12,42,'o','MyTiki Home','tiki-my_tiki.php',51,'feature_mytiki','','Registered',0),(13,42,'o','Preferences','tiki-user_preferences.php',55,'feature_mytiki,feature_userPreferences','','Registered',0),(14,42,'o','Messages','messu-mailbox.php',60,'feature_mytiki,feature_messages','tiki_p_messages','Registered',0),(15,42,'o','Tasks','tiki-user_tasks.php',65,'feature_mytiki,feature_tasks','tiki_p_tasks','Registered',0),(16,42,'o','Bookmarks','tiki-user_bookmarks.php',70,'feature_mytiki,feature_user_bookmarks','tiki_p_create_bookmarks','Registered',0),(17,42,'o','Modules','tiki-user_assigned_modules.php',75,'feature_mytiki,user_assigned_modules','tiki_p_configure_modules','Registered',0),(18,42,'o','Newsreader','tiki-newsreader_servers.php',80,'feature_mytiki,feature_newsreader','tiki_p_newsreader','Registered',0),(19,42,'o','Webmail','tiki-webmail.php',85,'feature_mytiki,feature_webmail','tiki_p_use_webmail','Registered',0),(20,42,'o','Notepad','tiki-notepad_list.php',90,'feature_mytiki,feature_notepad','tiki_p_notepad','Registered',0),(21,42,'o','My Files','tiki-userfiles.php',95,'feature_mytiki,feature_userfiles','tiki_p_userfiles','Registered',0),(22,42,'o','User Menu','tiki-usermenu.php',100,'feature_mytiki,feature_usermenu','tiki_p_usermenu','Registered',0),(23,42,'o','Mini Calendar','tiki-minical.php',105,'feature_mytiki,feature_minical','tiki_p_minical','Registered',0),(24,42,'o','My Watches','tiki-user_watches.php',110,'feature_mytiki,feature_user_watches','','Registered',0),(25,42,'s','Workflow','tiki-g-user_processes.php',150,'feature_workflow','tiki_p_use_workflow','',0),(26,42,'o','Admin Processes','tiki-g-admin_processes.php',155,'feature_workflow','tiki_p_admin_workflow','',0),(27,42,'o','Monitor Processes','tiki-g-monitor_processes.php',160,'feature_workflow','tiki_p_admin_workflow','',0),(28,42,'o','Monitor Activities','tiki-g-monitor_activities.php',165,'feature_workflow','tiki_p_admin_workflow','',0),(29,42,'o','Monitor Instances','tiki-g-monitor_instances.php',170,'feature_workflow','tiki_p_admin_workflow','',0),(30,42,'o','User Processes','tiki-g-user_processes.php',175,'feature_workflow','tiki_p_use_workflow','',0),(31,42,'o','User activities','tiki-g-user_activities.php',180,'feature_workflow','tiki_p_use_workflow','',0),(32,42,'o','User instances','tiki-g-user_instances.php',185,'feature_workflow','tiki_p_use_workflow','',0),(33,42,'s','Community','tiki-list_users.php',187,'feature_friends','tiki_p_list_users','',0),(34,42,'o','User List','tiki-list_users.php',188,'feature_friends','tiki_p_list_users','',0),(35,42,'o','Friendship Network','tiki-friends.php',189,'feature_friends','','Registered',0),(36,42,'s','Wiki','tiki-index.php',200,'feature_wiki','tiki_p_view','',0),(37,42,'o','Wiki Home','tiki-index.php',202,'feature_wiki','tiki_p_view','',0),(38,42,'o','Last Changes','tiki-lastchanges.php',205,'feature_wiki,feature_lastChanges','tiki_p_view','',0),(39,42,'o','Dump','dump/new.tar',210,'feature_wiki,feature_dump','tiki_p_view','',0),(40,42,'o','Rankings','tiki-wiki_rankings.php',215,'feature_wiki,feature_wiki_rankings','tiki_p_view','',0),(41,42,'o','List Pages','tiki-listpages.php',220,'feature_wiki,feature_listPages','tiki_p_view','',0),(42,42,'o','Orphan Pages','tiki-orphan_pages.php',225,'feature_wiki,feature_listorphanPages','tiki_p_view','',0),(43,42,'o','Sandbox','tiki-editpage.php?page=sandbox',230,'feature_wiki,feature_sandbox','tiki_p_view','',0),(44,42,'o','Multiple Print','tiki-print_pages.php',235,'feature_wiki,feature_wiki_multiprint','tiki_p_view','',0),(45,42,'o','Send Pages','tiki-send_objects.php',240,'feature_wiki,feature_comm','tiki_p_view,tiki_p_send_pages','',0),(46,42,'o','Received Pages','tiki-received_pages.php',245,'feature_wiki,feature_comm','tiki_p_view,tiki_p_admin_received_pages','',0),(47,42,'o','Structures','tiki-admin_structures.php',250,'feature_wiki,feature_wiki_structure','tiki_p_view','',0),(197,42,'o','Mind Map','tiki-mindmap.php',255,'feature_wiki_mindmap','tiki_p_view','',0),(48,42,'s','Image Galleries','tiki-galleries.php',300,'feature_galleries','tiki_p_view_image_gallery','',0),(49,42,'o','Galleries','tiki-galleries.php',305,'feature_galleries','tiki_p_list_image_galleries','',0),(50,42,'o','Rankings','tiki-galleries_rankings.php',310,'feature_galleries,feature_gal_rankings','tiki_p_list_image_galleries','',0),(51,42,'o','Upload Image','tiki-upload_image.php',315,'feature_galleries','tiki_p_upload_images','',0),(52,42,'o','Directory Batch','tiki-batch_upload.php',318,'feature_galleries,feature_gal_batch','tiki_p_batch_upload','',0),(53,42,'o','System Gallery','tiki-list_gallery.php?galleryId=0',320,'feature_galleries','tiki_p_admin_galleries','',0),(54,42,'s','Articles','tiki-view_articles.php',350,'feature_articles','tiki_p_read_article','',0),(55,42,'o','Articles Home','tiki-view_articles.php',355,'feature_articles','tiki_p_read_article','',0),(56,42,'o','List Articles','tiki-list_articles.php',360,'feature_articles','tiki_p_read_article','',0),(57,42,'o','Rankings','tiki-cms_rankings.php',365,'feature_articles,feature_cms_rankings','tiki_p_read_article','',0),(58,42,'o','Submit Article','tiki-edit_submission.php',370,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_submit_article','',0),(59,42,'o','View submissions','tiki-list_submissions.php',375,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_submit_article','',0),(60,42,'o','View submissions','tiki-list_submissions.php',375,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_approve_submission','',0),(61,42,'o','View Submissions','tiki-list_submissions.php',375,'feature_articles,feature_submissions','tiki_p_read_article,tiki_p_remove_submission','',0),(62,42,'o','New Article','tiki-edit_article.php',380,'feature_articles','tiki_p_read_article,tiki_p_edit_article','',0),(63,42,'o','Send Articles','tiki-send_objects.php',385,'feature_articles,feature_comm','tiki_p_read_article,tiki_p_send_articles','',0),(64,42,'o','Received Articles','tiki-received_articles.php',385,'feature_articles,feature_comm','tiki_p_read_article,tiki_p_admin_received_articles','',0),(196,42,'o','Admin Types','tiki-article_types.php',395,'feature_articles','tiki_p_articles_admin_types','',0),(195,42,'o','Admin Topics','tiki-admin_topics.php',390,'feature_articles','tiki_p_articles_admin_topics','',0),(67,42,'s','Blogs','tiki-list_blogs.php',450,'feature_blogs','tiki_p_read_blog','',0),(68,42,'o','List Blogs','tiki-list_blogs.php',455,'feature_blogs','tiki_p_read_blog','',0),(69,42,'o','Rankings','tiki-blog_rankings.php',460,'feature_blogs,feature_blog_rankings','tiki_p_read_blog','',0),(70,42,'o','Create/Edit Blog','tiki-edit_blog.php',465,'feature_blogs','tiki_p_read_blog,tiki_p_create_blogs','',0),(71,42,'o','Post','tiki-blog_post.php',470,'feature_blogs','tiki_p_read_blog,tiki_p_blog_post','',0),(72,42,'o','Admin Posts','tiki-list_posts.php',475,'feature_blogs','tiki_p_read_blog,tiki_p_blog_admin','',0),(73,42,'s','Forums','tiki-forums.php',500,'feature_forums','tiki_p_forum_read','',0),(74,42,'o','List Forums','tiki-forums.php',505,'feature_forums','tiki_p_forum_read','',0),(75,42,'o','Rankings','tiki-forum_rankings.php',510,'feature_forums,feature_forum_rankings','tiki_p_forum_read','',0),(76,42,'o','Admin Forums','tiki-admin_forums.php',515,'feature_forums','tiki_p_forum_read,tiki_p_admin_forum','',0),(77,42,'s','Directory','tiki-directory_browse.php',550,'feature_directory','tiki_p_view_directory','',0),(78,42,'o','Submit a new link','tiki-directory_add_site.php',555,'feature_directory','tiki_p_submit_link','',0),(79,42,'o','Browse Directory','tiki-directory_browse.php',560,'feature_directory','tiki_p_view_directory','',0),(80,42,'o','Admin Directory','tiki-directory_admin.php',565,'feature_directory','tiki_p_view_directory,tiki_p_admin_directory_cats','',0),(81,42,'o','Admin Directory','tiki-directory_admin.php',565,'feature_directory','tiki_p_view_directory,tiki_p_admin_directory_sites','',0),(82,42,'o','Admin Directory','tiki-directory_admin.php',565,'feature_directory','tiki_p_view_directory,tiki_p_validate_links','',0),(83,42,'s','File Galleries','tiki-list_file_gallery.php',600,'feature_file_galleries','tiki_p_view_file_gallery','',0),(84,42,'o','List Galleries','tiki-list_file_gallery.php',605,'feature_file_galleries','tiki_p_list_file_galleries','',0),(85,42,'o','Rankings','tiki-file_galleries_rankings.php',610,'feature_file_galleries,feature_file_galleries_rankings','tiki_p_list_file_galleries','',0),(86,42,'o','Upload File','tiki-upload_file.php',615,'feature_file_galleries','tiki_p_view_file_gallery,tiki_p_upload_files','',0),(87,42,'s','FAQs','tiki-list_faqs.php',650,'feature_faqs','tiki_p_view_faqs','',0),(88,42,'o','List FAQs','tiki-list_faqs.php',665,'feature_faqs','tiki_p_view_faqs','',0),(89,42,'o','Admin FAQs','tiki-list_faqs.php',660,'feature_faqs','tiki_p_admin_faqs','',0),(90,42,'s','Maps','tiki-map.php',700,'feature_maps','tiki_p_map_view','',0),(91,42,'o','Mapfiles','tiki-map_edit.php',705,'feature_maps','tiki_p_map_view','',0),(92,42,'o','Layer Management','tiki-map_upload.php',710,'feature_maps','tiki_p_map_edit','',0),(93,42,'s','Quizzes','tiki-list_quizzes.php',750,'feature_quizzes','tiki_p_take_quiz','',0),(94,42,'o','List Quizzes','tiki-list_quizzes.php',755,'feature_quizzes','tiki_p_take_quiz','',0),(95,42,'o','Quiz Stats','tiki-quiz_stats.php',760,'feature_quizzes','tiki_p_view_quiz_stats','',0),(96,42,'o','Admin Quizzes','tiki-edit_quiz.php',765,'feature_quizzes','tiki_p_admin_quizzes','',0),(97,42,'s','TikiSheet','tiki-sheets.php',780,'feature_sheet','tiki_p_view_sheet','',0),(98,42,'s','Trackers','tiki-list_trackers.php',800,'feature_trackers','tiki_p_view_trackers','',0),(99,42,'o','List Trackers','tiki-list_trackers.php',805,'feature_trackers','tiki_p_view_trackers','',0),(100,42,'o','Admin Trackers','tiki-admin_trackers.php',810,'feature_trackers','tiki_p_admin_trackers','',0),(101,42,'s','Surveys','tiki-list_surveys.php',850,'feature_surveys','tiki_p_take_survey','',0),(102,42,'o','List Surveys','tiki-list_surveys.php',855,'feature_surveys','tiki_p_take_survey','',0),(103,42,'o','Stats','tiki-survey_stats.php',860,'feature_surveys','tiki_p_view_survey_stats','',0),(104,42,'o','Admin Surveys','tiki-admin_surveys.php',865,'feature_surveys','tiki_p_admin_surveys','',0),(105,42,'s','Newsletters','tiki-newsletters.php',900,'feature_newsletters','tiki_p_subscribe_newsletters','',0),(106,42,'s','Newsletters','tiki-newsletters.php',900,'feature_newsletters','tiki_p_send_newsletters','',0),(107,42,'s','Newsletters','tiki-newsletters.php',900,'feature_newsletters','tiki_p_admin_newsletters','',0),(108,42,'o','Send Newsletters','tiki-send_newsletters.php',905,'feature_newsletters','tiki_p_send_newsletters','',0),(109,42,'o','Admin Newsletters','tiki-admin_newsletters.php',910,'feature_newsletters','tiki_p_admin_newsletters','',0),(112,42,'s','Charts','tiki-charts.php',1000,'feature_charts','tiki_p_view_chart','',0),(113,42,'o','Admin Charts','tiki-admin_charts.php',1005,'feature_charts','tiki_p_admin_charts','',0),(114,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin','',0),(116,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_categories','',0),(117,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_banners','',0),(118,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_templates','',0),(119,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_cookies','',0),(120,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_dynamic','',0),(121,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_mailin','',0),(122,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_content_templates','',0),(123,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_edit_html_pages','',0),(124,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_view_referer_stats','',0),(125,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_drawings','',0),(126,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_admin_shoutbox','',0),(127,42,'r','Admin','tiki-admin.php',1050,'','tiki_p_live_support_admin','',0),(128,42,'r','Admin','tiki-admin.php',1050,'','user_is_operator','',0),(129,42,'r','Admin','tiki-admin.php',1050,'feature_integrator','tiki_p_admin_integrator','',0),(198,42,'r','Admin','tiki-admin.php',1050,'feature_edit_templates','tiki_p_edit_templates','',0),(199,42,'r','Admin','tiki-admin.php',1050,'feature_view_tpl','tiki_p_edit_templates','',0),(200,42,'r','Admin','tiki-admin.php',1050,'feature_editcss','tiki_p_create_css','',0),(130,42,'o','Admin Home','tiki-admin.php',1051,'','tiki_p_admin','',0),(131,42,'o','Live Support','tiki-live_support_admin.php',1055,'feature_live_support','tiki_p_live_support_admin','',0),(132,42,'o','Live Support','tiki-live_support_admin.php',1055,'feature_live_support','user_is_operator','',0),(133,42,'o','Banning','tiki-admin_banning.php',1060,'feature_banning','tiki_p_admin_banning','',0),(134,42,'o','Calendar','tiki-admin_calendars.php',1065,'feature_calendar','tiki_p_admin_calendar','',0),(135,42,'o','Users','tiki-adminusers.php',1070,'','tiki_p_admin_users','',0),(136,42,'o','Groups','tiki-admingroups.php',1075,'','tiki_p_admin','',0),(189,42,'o','Wiki Cache','tiki-list_cache.php',1080,'cachepages','tiki_p_admin','',0),(138,42,'o','Modules','tiki-admin_modules.php',1085,'','tiki_p_admin','',0),(139,42,'o','Hotwords','tiki-admin_hotwords.php',1095,'feature_hotwords','tiki_p_admin','',0),(140,42,'o','RSS Modules','tiki-admin_rssmodules.php',1100,'','tiki_p_admin_rssmodules','',0),(141,42,'o','Menus','tiki-admin_menus.php',1105,'','tiki_p_edit_menu','',0),(142,42,'o','Polls','tiki-admin_polls.php',1110,'feature_polls','tiki_p_admin_polls','',0),(143,42,'o','Mail Notifications','tiki-admin_notifications.php',1120,'','tiki_p_admin_notifications','',0),(182,42,'o','Search Stats','tiki-search_stats.php',1125,'feature_search_stats','tiki_p_admin','',0),(145,42,'o','Theme Control','tiki-theme_control.php',1130,'feature_theme_control','tiki_p_admin','',0),(183,42,'o','QuickTags','tiki-admin_quicktags.php',1135,'','tiki_p_admin_quicktags','',0),(148,42,'o','Categories','tiki-admin_categories.php',1145,'feature_categories','tiki_p_admin_categories','',0),(149,42,'o','Banners','tiki-list_banners.php',1150,'feature_banners','tiki_p_admin_banners','',0),(150,42,'o','Edit Templates','tiki-edit_templates.php',1155,'feature_edit_templates','tiki_p_edit_templates','',0),(147,42,'o','View Templates','tiki-edit_templates.php',1155,'feature_view_tpl','tiki_p_edit_templates','',2),(146,42,'o','Edit CSS','tiki-edit_css.php',1158,'feature_editcss','tiki_p_create_css','',2),(151,42,'o','Drawings','tiki-admin_drawings.php',1160,'feature_drawings','tiki_p_admin_drawings','',0),(152,42,'o','Dynamic content','tiki-list_contents.php',1165,'feature_dynamic_content','tiki_p_admin_dynamic','',0),(153,42,'o','Mail-in','tiki-admin_mailin.php',1175,'feature_mailin','tiki_p_admin_mailin','',0),(154,42,'o','HTML Pages','tiki-admin_html_pages.php',1185,'feature_html_pages','tiki_p_edit_html_pages','',0),(155,42,'o','Shoutbox','tiki-shoutbox.php',1190,'feature_shoutbox','tiki_p_admin_shoutbox','',0),(156,42,'o','Shoutbox Words','tiki-admin_shoutbox_words.php',1191,'feature_shoutbox','tiki_p_admin_shoutbox','',0),(157,42,'o','Referer Stats','tiki-referer_stats.php',1195,'feature_referer_stats','tiki_p_view_referer_stats','',0),(158,42,'o','Integrator','tiki-admin_integrator.php',1205,'feature_integrator','tiki_p_admin_integrator','',0),(159,42,'o','phpinfo','tiki-phpinfo.php',1215,'','tiki_p_admin','',0),(163,42,'o','Tiki Logs','tiki-syslog.php',1245,'','tiki_p_admin','',0),(164,42,'o','Security Admin','tiki-admin_security.php',1250,'','tiki_p_admin','',0),(165,42,'o','Action Log','tiki-admin_actionlog.php',1255,'feature_actionlog','tiki_p_admin','',0),(166,42,'o','Comments','tiki-list_comments.php',1260,'feature_wiki_comments','tiki_p_admin','',0),(167,42,'o','Comments','tiki-list_comments.php',1260,'feature_article_comments','tiki_p_admin','',0),(168,42,'o','Comments','tiki-list_comments.php',1260,'feature_blog_comments','tiki_p_admin','',0),(169,42,'o','Comments','tiki-list_comments.php',1260,'feature_file_galleries_comments','tiki_p_admin','',0),(170,42,'o','Comments','tiki-list_comments.php',1260,'feature_image_galleries_comments','tiki_p_admin','',0),(171,42,'o','Comments','tiki-list_comments.php',1260,'feature_poll_comments','tiki_p_admin','',0),(172,42,'o','Comments','tiki-list_comments.php',1260,'feature_faq_comments','tiki_p_admin','',0),(173,42,'o','Freetags','tiki-browse_freetags.php',27,'feature_freetags','tiki_p_view_freetags','',0),(174,42,'r','Admin','',1050,'','tiki_p_admin_contribution','',0),(175,42,'o','Contribution','tiki-admin_contribution.php',1265,'feature_contribution','tiki_p_admin_contribution','',0),(176,42,'o','List TikiSheets','tiki-sheets.php',782,'feature_sheet','tiki_p_view_sheet','',0),(177,42,'o','Directory batch','tiki-batch_upload_files.php',617,'feature_file_galleries_batch','tiki_p_batch_upload_file_dir','',0),(178,42,'o','Contacts','tiki-contacts.php',87,'feature_mytiki,feature_contacts','','Registered',0),(179,42,'o','Tiki Calendar','tiki-action_calendar.php',36,'feature_action_calendar','tiki_p_view_tiki_calendar','',0),(180,42,'r','Admin','',1050,'','tiki_p_admin_users','',0),(187,42,'o','Search','tiki-searchresults.php',13,'feature_search','tiki_p_search','',0),(184,42,'r','Admin','',1050,'','tiki_p_admin_quicktags','',0),(185,42,'r','Admin','',1050,'','tiki_p_edit_menu','',0),(188,42,'o','Tiki Cache/Sys Admin','tiki-admin_system.php',1230,'','tiki_p_clean_cache','',0),(186,42,'r','Admin','',1050,'','tiki_p_clean_cache','',0),(190,42,'s','Articles','tiki-view_articles.php',350,'feature_articles','tiki_p_articles_read_heading','',0),(191,42,'o','Articles Home','tiki-view_articles.php',355,'feature_articles','tiki_p_articles_read_heading','',0),(192,42,'o','List Articles','tiki-list_articles.php',360,'feature_articles','tiki_p_articles_read_heading','',0),(193,42,'o','Action Log','tiki-admin_actionlog.php',1255,'feature_actionlog','tiki_p_view_actionlog','',0),(194,42,'o','Action Log','tiki-admin_actionlog.php',1255,'feature_actionlog','tiki_p_view_actionlog_owngroups','',0),(201,42,'o','Content Templates','tiki-admin_content_templates.php',1256,'','tiki_p_edit_content_templates','',0);
/*!40000 ALTER TABLE `tiki_menu_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_menus`
--

DROP TABLE IF EXISTS `tiki_menus`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_menus` (
  `menuId` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `type` char(1) DEFAULT NULL,
  `icon` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_menus`
--

LOCK TABLES `tiki_menus` WRITE;
/*!40000 ALTER TABLE `tiki_menus` DISABLE KEYS */;
INSERT INTO `tiki_menus` VALUES (42,'Application menu','Main extensive navigation menu','d',NULL);
/*!40000 ALTER TABLE `tiki_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_minical_events`
--

DROP TABLE IF EXISTS `tiki_minical_events`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_minical_events` (
  `user` varchar(200) DEFAULT '',
  `eventId` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) DEFAULT NULL,
  `description` text,
  `start` int(14) DEFAULT NULL,
  `end` int(14) DEFAULT NULL,
  `security` char(1) DEFAULT NULL,
  `duration` int(3) DEFAULT NULL,
  `topicId` int(12) DEFAULT NULL,
  `reminded` char(1) DEFAULT NULL,
  PRIMARY KEY (`eventId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_minical_events`
--

LOCK TABLES `tiki_minical_events` WRITE;
/*!40000 ALTER TABLE `tiki_minical_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_minical_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_minical_topics`
--

DROP TABLE IF EXISTS `tiki_minical_topics`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_minical_topics` (
  `user` varchar(200) DEFAULT '',
  `topicId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `filetype` varchar(200) DEFAULT NULL,
  `filesize` varchar(200) DEFAULT NULL,
  `data` longblob,
  `path` varchar(250) DEFAULT NULL,
  `isIcon` char(1) DEFAULT NULL,
  PRIMARY KEY (`topicId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_minical_topics`
--

LOCK TABLES `tiki_minical_topics` WRITE;
/*!40000 ALTER TABLE `tiki_minical_topics` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_minical_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_minichat`
--

DROP TABLE IF EXISTS `tiki_minichat`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_minichat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(31) DEFAULT NULL,
  `ts` int(10) unsigned NOT NULL,
  `user` varchar(31) DEFAULT NULL,
  `nick` varchar(31) DEFAULT NULL,
  `msg` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `channel` (`channel`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_minichat`
--

LOCK TABLES `tiki_minichat` WRITE;
/*!40000 ALTER TABLE `tiki_minichat` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_minichat` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_modules`
--

DROP TABLE IF EXISTS `tiki_modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_modules` (
  `moduleId` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `position` char(1) NOT NULL DEFAULT '',
  `ord` int(4) NOT NULL DEFAULT '0',
  `type` char(1) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `cache_time` int(14) DEFAULT NULL,
  `rows` int(4) DEFAULT NULL,
  `params` varchar(255) NOT NULL DEFAULT '',
  `groups` text,
  PRIMARY KEY (`name`(100),`position`,`ord`,`params`(140)),
  KEY `positionType` (`position`,`type`),
  KEY `moduleId` (`moduleId`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_modules`
--

LOCK TABLES `tiki_modules` WRITE;
/*!40000 ALTER TABLE `tiki_modules` DISABLE KEYS */;
INSERT INTO `tiki_modules` VALUES (1,'login_box','r',1,NULL,NULL,0,NULL,'','a:2:{i:0;s:10:\"Registered\";i:1;s:9:\"Anonymous\";}'),(2,'mnu_application_menu','l',1,NULL,NULL,0,NULL,'flip=y','a:2:{i:0;s:10:\"Registered\";i:1;s:9:\"Anonymous\";}'),(3,'quick_edit','l',2,NULL,NULL,0,NULL,'','a:1:{i:0;s:6:\"Admins\";}'),(4,'since_last_visit_new','r',40,NULL,NULL,0,NULL,'','a:1:{i:0;s:6:\"Admins\";}');
/*!40000 ALTER TABLE `tiki_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_newsletter_groups`
--

DROP TABLE IF EXISTS `tiki_newsletter_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_newsletter_groups` (
  `nlId` int(12) NOT NULL DEFAULT '0',
  `groupName` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`nlId`,`groupName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_newsletter_groups`
--

LOCK TABLES `tiki_newsletter_groups` WRITE;
/*!40000 ALTER TABLE `tiki_newsletter_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_newsletter_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_newsletter_included`
--

DROP TABLE IF EXISTS `tiki_newsletter_included`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_newsletter_included` (
  `nlId` int(12) NOT NULL DEFAULT '0',
  `includedId` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nlId`,`includedId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_newsletter_included`
--

LOCK TABLES `tiki_newsletter_included` WRITE;
/*!40000 ALTER TABLE `tiki_newsletter_included` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_newsletter_included` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_newsletter_subscriptions`
--

DROP TABLE IF EXISTS `tiki_newsletter_subscriptions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_newsletter_subscriptions` (
  `nlId` int(12) NOT NULL DEFAULT '0',
  `email` varchar(255) NOT NULL DEFAULT '',
  `code` varchar(32) DEFAULT NULL,
  `valid` char(1) DEFAULT NULL,
  `subscribed` int(14) DEFAULT NULL,
  `isUser` char(1) NOT NULL DEFAULT 'n',
  PRIMARY KEY (`nlId`,`email`,`isUser`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_newsletter_subscriptions`
--

LOCK TABLES `tiki_newsletter_subscriptions` WRITE;
/*!40000 ALTER TABLE `tiki_newsletter_subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_newsletter_subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_newsletters`
--

DROP TABLE IF EXISTS `tiki_newsletters`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_newsletters` (
  `nlId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  `created` int(14) DEFAULT NULL,
  `lastSent` int(14) DEFAULT NULL,
  `editions` int(10) DEFAULT NULL,
  `users` int(10) DEFAULT NULL,
  `allowUserSub` char(1) DEFAULT 'y',
  `allowAnySub` char(1) DEFAULT NULL,
  `unsubMsg` char(1) DEFAULT 'y',
  `validateAddr` char(1) DEFAULT 'y',
  `frequency` int(14) DEFAULT NULL,
  `allowTxt` char(1) DEFAULT 'y',
  `author` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`nlId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_newsletters`
--

LOCK TABLES `tiki_newsletters` WRITE;
/*!40000 ALTER TABLE `tiki_newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_newsreader_marks`
--

DROP TABLE IF EXISTS `tiki_newsreader_marks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_newsreader_marks` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `serverId` int(12) NOT NULL DEFAULT '0',
  `groupName` varchar(255) NOT NULL DEFAULT '',
  `timestamp` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`(100),`serverId`,`groupName`(100))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_newsreader_marks`
--

LOCK TABLES `tiki_newsreader_marks` WRITE;
/*!40000 ALTER TABLE `tiki_newsreader_marks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_newsreader_marks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_newsreader_servers`
--

DROP TABLE IF EXISTS `tiki_newsreader_servers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_newsreader_servers` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `serverId` int(12) NOT NULL AUTO_INCREMENT,
  `server` varchar(250) DEFAULT NULL,
  `port` int(4) DEFAULT NULL,
  `username` varchar(200) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`serverId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_newsreader_servers`
--

LOCK TABLES `tiki_newsreader_servers` WRITE;
/*!40000 ALTER TABLE `tiki_newsreader_servers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_newsreader_servers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_object_ratings`
--

DROP TABLE IF EXISTS `tiki_object_ratings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_object_ratings` (
  `catObjectId` int(12) NOT NULL DEFAULT '0',
  `pollId` int(12) NOT NULL DEFAULT '0',
  PRIMARY KEY (`catObjectId`,`pollId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_object_ratings`
--

LOCK TABLES `tiki_object_ratings` WRITE;
/*!40000 ALTER TABLE `tiki_object_ratings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_object_ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_objects`
--

DROP TABLE IF EXISTS `tiki_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_objects` (
  `objectId` int(12) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) DEFAULT NULL,
  `itemId` varchar(255) DEFAULT NULL,
  `description` text,
  `created` int(14) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `href` varchar(200) DEFAULT NULL,
  `hits` int(8) DEFAULT NULL,
  PRIMARY KEY (`objectId`),
  KEY `type` (`type`,`objectId`),
  KEY `itemId` (`itemId`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_objects`
--

LOCK TABLES `tiki_objects` WRITE;
/*!40000 ALTER TABLE `tiki_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_page_drafts`
--

DROP TABLE IF EXISTS `tiki_page_drafts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_page_drafts` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `pageName` varchar(255) NOT NULL,
  `data` mediumtext,
  `description` varchar(200) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  PRIMARY KEY (`pageName`(120),`user`(120))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_page_drafts`
--

LOCK TABLES `tiki_page_drafts` WRITE;
/*!40000 ALTER TABLE `tiki_page_drafts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_page_drafts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_page_footnotes`
--

DROP TABLE IF EXISTS `tiki_page_footnotes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_page_footnotes` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `pageName` varchar(250) NOT NULL DEFAULT '',
  `data` text,
  PRIMARY KEY (`user`(150),`pageName`(100))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_page_footnotes`
--

LOCK TABLES `tiki_page_footnotes` WRITE;
/*!40000 ALTER TABLE `tiki_page_footnotes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_page_footnotes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_pages`
--

DROP TABLE IF EXISTS `tiki_pages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_pages` (
  `page_id` int(14) NOT NULL AUTO_INCREMENT,
  `pageName` varchar(160) NOT NULL DEFAULT '',
  `hits` int(8) DEFAULT NULL,
  `data` mediumtext,
  `description` varchar(200) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `version` int(8) NOT NULL DEFAULT '0',
  `user` varchar(200) DEFAULT '',
  `ip` varchar(15) DEFAULT NULL,
  `flag` char(1) DEFAULT NULL,
  `points` int(8) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `cache` longtext,
  `wiki_cache` int(10) DEFAULT NULL,
  `cache_timestamp` int(14) DEFAULT NULL,
  `pageRank` decimal(4,3) DEFAULT NULL,
  `creator` varchar(200) DEFAULT NULL,
  `page_size` int(10) unsigned DEFAULT '0',
  `lang` varchar(16) DEFAULT NULL,
  `lockedby` varchar(200) DEFAULT NULL,
  `is_html` tinyint(1) DEFAULT '0',
  `created` int(14) DEFAULT NULL,
  `wysiwyg` char(1) DEFAULT NULL,
  `wiki_authors_style` varchar(20) DEFAULT '',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `pageName` (`pageName`),
  KEY `data` (`data`(255)),
  KEY `pageRank` (`pageRank`),
  KEY `lastModif` (`lastModif`),
  FULLTEXT KEY `ft` (`pageName`,`description`,`data`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_pages`
--

LOCK TABLES `tiki_pages` WRITE;
/*!40000 ALTER TABLE `tiki_pages` DISABLE KEYS */;
INSERT INTO `tiki_pages` VALUES (1,'HomePage',1,'{GROUP(groups=Admins)}\r\n!Thank you for installing Tiki.\r\n\r\nThe entire Tiki Community would like to thank you and help you get introduced to Tiki.\r\n\r\n!How To Get Started\r\nTiki has more than 1000 features and settings.\r\n\r\nThis allows you to create both very simple and complex websites.\r\n\r\nWe understand that so many features might seem overwhelming at first. This is why we offer you two different ways to __Get Started__ with Tiki.\r\n\r\n{SPLIT()}\r\n-=Easy Setup using Profiles=-\r\n!![tiki-admin.php?profile=&amp;category=Featured+profiles&amp;repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&amp;preloadlist=y&amp;page=profiles&amp;list=List#profile-results|Get Started using Profiles]\r\n__Who Should Use This__\r\n*You want to get started quickly\r\n*You don\'t feel like learning the Admin Panel right away\r\n*You want to quickly test out some of Tiki\'s Features\r\n\r\n!!Featured Profiles\r\n\r\n__Collaborative Community__ ([tiki-admin.php?profile=&amp;category=Featured+profiles&amp;repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&amp;preloadlist=y&amp;page=profiles&amp;list=List#profile-results|install profile now])\r\nSetup to help subject experts and enthusiasts work together to build a Knowledge Base\r\n*Wiki Editing\r\n*Personal Member Spaces\r\n*Forums\r\n*Blogs\r\n\r\n__Personal Blog and Profile__ ([tiki-admin.php?profile=&amp;category=Featured+profiles&amp;repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&amp;preloadlist=y&amp;page=profiles&amp;list=List#profile-results|install profile now])\r\nSetup with many cool features to help you integrate the Social Web and establish a strong presence in the Blogosphere\r\n*Blog (Full set of blog related features)\r\n*Image Gallery\r\n*Calendar\r\n*RSS Integration\r\n*Video Log\r\n\r\n__Company Intranet__ ([tiki-admin.php?profile=&amp;category=Featured+profiles&amp;repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&amp;preloadlist=y&amp;page=profiles&amp;list=List#profile-results|install profile now])\r\nSetup for a Corporate Intranet of a typical medium-sized business.\r\n*Company News Articles\r\n*Executive Blog\r\n*File Repository &amp; Management\r\n*Collaborative Wiki\r\n\r\n__Small Organization Web Presence__ ([tiki-admin.php?profile=&amp;category=Featured+profiles&amp;repository=http%3a%2f%2fprofiles.tikiwiki.org%2fprofiles&amp;preloadlist=y&amp;page=profiles&amp;list=List#profile-results|install profile now])\r\nSetup for a Web Presence of a typical small business or non-profit.\r\n*Company News &amp; Updates\r\n*Highlight Company\'s Products and Services\r\n*File Gallery (great for Media Kit)\r\n*Contact Form\r\n---\r\n-=Manual Setup using Admin Panel=-\r\n!![tiki-admin.php|Get Started using Admin Panel]\r\n__Who Should Use This__\r\n*You are familiar with software Admin Panels\r\n*You enjoy exploring and playing with many options\r\n*You already know Tiki\r\n\r\n\r\n{SPLIT}\r\n{ELSE}\r\n\r\n!Congratulations\r\nThis is the default ))HomePage(( for your Tiki. If you are seeing this page, your installation was successful.\r\n\r\nYou can change this page after logging in. Please review the [http://doc.tikiwiki.org/wiki+syntax|wiki syntax] for editing details.\r\n\r\n\r\n!!{img src=pics/icons/star.png alt=&quot;Star&quot;} Get started.\r\nTo begin configuring your site:\r\n{FANCYLIST()}\r\n1) Log in as the __admin__ with password __admin__.\r\n2) Change the admin password.\r\n3) Enable specific Tiki features.\r\n4) Configure the features.\r\n{FANCYLIST}\r\n\r\n!!{img src=pics/icons/help.png alt=&quot;Help&quot;} Need help?\r\nFor more information:\r\n*[http://info.tikiwiki.org/Learn+More|Learn more about TikiWiki].\r\n*[http://info.tikiwiki.org/Help+Others|Get help], including the [http://doc.tikiwiki.org|official documentation] and [http://www.tikiwiki.org/forums|support forums].\r\n*[http://info.tikiwiki.org/Join+the+community|Join the TikiWiki community].\r\n{GROUP}\r\n\r\nPages for testing\r\n\r\n((EnglishTestPage))\r\n','',1245980845,'',2,'admin','127.0.0.1','',NULL,NULL,NULL,NULL,0,NULL,'admin',3562,'en','',0,1245962685,'n',''),(2,'EnglishTestPage',0,'This is a test page in English.','',1245980884,'',1,'admin','127.0.0.1','',NULL,NULL,NULL,NULL,NULL,NULL,'admin',31,NULL,'',0,1245980884,'n','');
/*!40000 ALTER TABLE `tiki_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_pages_changes`
--

DROP TABLE IF EXISTS `tiki_pages_changes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_pages_changes` (
  `page_id` int(14) NOT NULL DEFAULT '0',
  `version` int(10) NOT NULL DEFAULT '0',
  `segments_added` int(10) DEFAULT NULL,
  `segments_removed` int(10) DEFAULT NULL,
  `segments_total` int(10) DEFAULT NULL,
  PRIMARY KEY (`page_id`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_pages_changes`
--

LOCK TABLES `tiki_pages_changes` WRITE;
/*!40000 ALTER TABLE `tiki_pages_changes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_pages_changes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_pages_translation_bits`
--

DROP TABLE IF EXISTS `tiki_pages_translation_bits`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_pages_translation_bits` (
  `translation_bit_id` int(14) NOT NULL AUTO_INCREMENT,
  `page_id` int(14) NOT NULL,
  `version` int(8) NOT NULL,
  `source_translation_bit` int(10) DEFAULT NULL,
  `original_translation_bit` int(10) DEFAULT NULL,
  `flags` set('critical') DEFAULT '',
  PRIMARY KEY (`translation_bit_id`),
  KEY `page_id` (`page_id`),
  KEY `original_translation_bit` (`original_translation_bit`),
  KEY `source_translation_bit` (`source_translation_bit`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_pages_translation_bits`
--

LOCK TABLES `tiki_pages_translation_bits` WRITE;
/*!40000 ALTER TABLE `tiki_pages_translation_bits` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_pages_translation_bits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_pageviews`
--

DROP TABLE IF EXISTS `tiki_pageviews`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_pageviews` (
  `day` int(14) NOT NULL DEFAULT '0',
  `pageviews` int(14) DEFAULT NULL,
  PRIMARY KEY (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_pageviews`
--

LOCK TABLES `tiki_pageviews` WRITE;
/*!40000 ALTER TABLE `tiki_pageviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_pageviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_plugin_security`
--

DROP TABLE IF EXISTS `tiki_plugin_security`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_plugin_security` (
  `fingerprint` varchar(200) NOT NULL,
  `status` varchar(10) NOT NULL,
  `approval_by` varchar(200) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_objectType` varchar(20) NOT NULL,
  `last_objectId` varchar(200) NOT NULL,
  PRIMARY KEY (`fingerprint`),
  KEY `last_object` (`last_objectType`,`last_objectId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_plugin_security`
--

LOCK TABLES `tiki_plugin_security` WRITE;
/*!40000 ALTER TABLE `tiki_plugin_security` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_plugin_security` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_poll_objects`
--

DROP TABLE IF EXISTS `tiki_poll_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_poll_objects` (
  `catObjectId` int(11) NOT NULL DEFAULT '0',
  `pollId` int(11) NOT NULL DEFAULT '0',
  `title` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`catObjectId`,`pollId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_poll_objects`
--

LOCK TABLES `tiki_poll_objects` WRITE;
/*!40000 ALTER TABLE `tiki_poll_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_poll_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_poll_options`
--

DROP TABLE IF EXISTS `tiki_poll_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_poll_options` (
  `pollId` int(8) NOT NULL DEFAULT '0',
  `optionId` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `position` int(4) NOT NULL DEFAULT '0',
  `votes` int(8) DEFAULT NULL,
  PRIMARY KEY (`optionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_poll_options`
--

LOCK TABLES `tiki_poll_options` WRITE;
/*!40000 ALTER TABLE `tiki_poll_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_poll_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_poll_votes`
--

DROP TABLE IF EXISTS `tiki_poll_votes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_poll_votes` (
  `pollId` int(11) NOT NULL,
  `optionId` int(11) NOT NULL,
  `voteId` int(11) NOT NULL AUTO_INCREMENT,
  `identification` varchar(300) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`voteId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_poll_votes`
--

LOCK TABLES `tiki_poll_votes` WRITE;
/*!40000 ALTER TABLE `tiki_poll_votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_poll_votes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_polls`
--

DROP TABLE IF EXISTS `tiki_polls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_polls` (
  `pollId` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `active` char(1) DEFAULT NULL,
  `publishDate` int(14) DEFAULT NULL,
  `voteConsiderationSpan` int(4) DEFAULT '0',
  PRIMARY KEY (`pollId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_polls`
--

LOCK TABLES `tiki_polls` WRITE;
/*!40000 ALTER TABLE `tiki_polls` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_polls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_preferences`
--

DROP TABLE IF EXISTS `tiki_preferences`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_preferences` (
  `name` varchar(40) NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_preferences`
--

LOCK TABLES `tiki_preferences` WRITE;
/*!40000 ALTER TABLE `tiki_preferences` DISABLE KEYS */;
INSERT INTO `tiki_preferences` VALUES ('lastUpdatePrefs','3'),('case_patched','y'),('gal_use_lib','gd');
/*!40000 ALTER TABLE `tiki_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_private_messages`
--

DROP TABLE IF EXISTS `tiki_private_messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_private_messages` (
  `messageId` int(8) NOT NULL AUTO_INCREMENT,
  `toNickname` varchar(200) NOT NULL DEFAULT '',
  `poster` varchar(200) NOT NULL DEFAULT 'anonymous',
  `timestamp` int(14) DEFAULT NULL,
  `received` tinyint(1) NOT NULL DEFAULT '0',
  `message` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`messageId`),
  KEY `received` (`received`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_private_messages`
--

LOCK TABLES `tiki_private_messages` WRITE;
/*!40000 ALTER TABLE `tiki_private_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_private_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_profile_symbols`
--

DROP TABLE IF EXISTS `tiki_profile_symbols`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_profile_symbols` (
  `domain` varchar(50) NOT NULL,
  `profile` varchar(50) NOT NULL,
  `object` varchar(50) NOT NULL,
  `type` varchar(20) NOT NULL,
  `value` varchar(50) NOT NULL,
  `named` enum('y','n') NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`domain`,`profile`,`object`),
  KEY `named` (`named`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_profile_symbols`
--

LOCK TABLES `tiki_profile_symbols` WRITE;
/*!40000 ALTER TABLE `tiki_profile_symbols` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_profile_symbols` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_programmed_content`
--

DROP TABLE IF EXISTS `tiki_programmed_content`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_programmed_content` (
  `pId` int(8) NOT NULL AUTO_INCREMENT,
  `contentId` int(8) NOT NULL DEFAULT '0',
  `publishDate` int(14) NOT NULL DEFAULT '0',
  `data` text,
  PRIMARY KEY (`pId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_programmed_content`
--

LOCK TABLES `tiki_programmed_content` WRITE;
/*!40000 ALTER TABLE `tiki_programmed_content` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_programmed_content` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_quicktags`
--

DROP TABLE IF EXISTS `tiki_quicktags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_quicktags` (
  `tagId` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `taglabel` varchar(255) DEFAULT NULL,
  `taginsert` text,
  `tagicon` varchar(255) DEFAULT NULL,
  `tagcategory` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tagId`),
  KEY `tagcategory` (`tagcategory`),
  KEY `taglabel` (`taglabel`)
) ENGINE=MyISAM AUTO_INCREMENT=159 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_quicktags`
--

LOCK TABLES `tiki_quicktags` WRITE;
/*!40000 ALTER TABLE `tiki_quicktags` DISABLE KEYS */;
INSERT INTO `tiki_quicktags` VALUES (1,'text, bold','__text__','pics/icons/text_bold.png','wiki'),(2,'text, italic','\'\'text\'\'','pics/icons/text_italic.png','wiki'),(3,'text, underline','===text===','pics/icons/text_underline.png','wiki'),(4,'table new','||r1c1|r1c2\nr2c1|r2c2||','pics/icons/table.png','wiki'),(5,'link, external','[http://example.com|text]','pics/icons/world_link.png','wiki'),(6,'link, wiki','((text))','pics/icons/page_link.png','wiki'),(7,'heading1','!text','pics/icons/text_heading_1.png','wiki'),(8,'heading2','!!text','pics/icons/text_heading_2.png','wiki'),(9,'heading3','!!!text','pics/icons/text_heading_3.png','wiki'),(10,'title bar','-=text=-','pics/icons/text_padding_top.png','wiki'),(11,'box','^text^','pics/icons/box.png','wiki'),(12,'dynamic content','{content id= }','pics/icons/database_refresh.png','wiki'),(13,'hr','---','pics/icons/page.png','wiki'),(14,'center text','::text::','pics/icons/text_align_center.png','wiki'),(15,'colored text','~~#FF0000:text~~','pics/icons/palette.png','wiki'),(16,'dynamic variable','%text%','pics/icons/book_open.png','wiki'),(17,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','wiki'),(18,'list bullets','*text','pics/icons/text_list_bullets.png','wiki'),(19,'list numbers','#text','pics/icons/text_list_numbers.png','wiki'),(20,'Email Address','[mailto:text|text]','pics/icons/email.png','wiki'),(21,'Deleted','--text--','pics/icons/text_strikethrough.png','wiki'),(22,'New wms Metadata','METADATA\r\n		\"wms_name\" \"myname\"\r\n 	\"wms_srs\" \"EPSG:4326\"\r\n 	\"wms_server_version\" \" \"\r\n 	\"wms_layers\" \"mylayers\"\r\n 	\"wms_request\" \"myrequest\"\r\n 	\"wms_format\" \" \"\r\n 	\"wms_time\" \" \"\r\n END','pics/icons/tag_blue_add.png','maps'),(23,'New Class','CLASS\r\n EXPRESSION ()\r\n SYMBOL 0\r\n OUTLINECOLOR\r\n COLOR\r\n NAME \"myclass\" \r\nEND #end of class','pics/icons/application_add.png','maps'),(24,'New Projection','PROJECTION\r\n \"init=epsg:4326\"\r\nEND','pics/icons/image_add.png','maps'),(25,'New Query','#\r\n# Start of query definitions\r\n#\r\n QUERYMAP\r\n STATUS ON\r\n STYLE HILITE\r\nEND','pics/icons/database_gear.png','maps'),(26,'New Scalebar','#\r\n# Start of scalebar\r\n#\r\nSCALEBAR\r\n IMAGECOLOR 255 255 255\r\n STYLE 1\r\n SIZE 400 2\r\n COLOR 0 0 0\r\n UNITS KILOMETERS\r\n INTERVALS 5\r\n STATUS ON\r\nEND','pics/icons/layout_add.png','maps'),(27,'New Layer','LAYER\r\n NAME\r\n TYPE\r\n STATUS ON\r\n DATA \"mydata\"\r\nEND #end of layer','pics/icons/layers.png','maps'),(28,'New Label','LABEL\r\n COLOR\r\n ANGLE\r\n FONT arial\r\n TYPE TRUETYPE\r\n POSITION\r\n PARTIALS TRUE\r\n SIZE 6\r\n BUFFER 0\r\n OUTLINECOLOR \r\nEND #end of label','pics/icons/comment_add.png','maps'),(29,'New Reference','#\r\n#start of reference\r\n#\r\n REFERENCE\r\n SIZE 120 60\r\n STATUS ON\r\n EXTENT -180 -90 182 88\r\n OUTLINECOLOR 255 0 0\r\n IMAGE \"myimagedata\"\r\n COLOR -1 -1 -1\r\nEND','pics/icons/picture_add.png','maps'),(30,'New Legend','#\r\n#start of Legend\r\n#\r\n LEGEND\r\n KEYSIZE 18 12\r\n POSTLABELCACHE TRUE\r\n STATUS ON\r\nEND','pics/icons/note_add.png','maps'),(31,'New Web','#\r\n# Start of web interface definition\r\n#\r\nWEB\r\n TEMPLATE \"myfile/url\"\r\n MINSCALE 1000\r\n MAXSCALE 40000\r\n IMAGEPATH \"myimagepath\"\r\n IMAGEURL \"mypath\"\r\nEND','pics/icons/world_link.png','maps'),(32,'New Outputformat','OUTPUTFORMAT\r\n NAME\r\n DRIVER \" \"\r\n MIMETYPE \"myimagetype\"\r\n IMAGEMODE RGB\r\n EXTENSION \"png\"\r\nEND','pics/icons/newspaper_go.png','maps'),(33,'New Mapfile','#\r\n# Start of mapfile\r\n#\r\nNAME MYMAPFLE\r\n STATUS ON\r\nSIZE \r\nEXTENT\r\nUNITS \r\nSHAPEPATH \" \"\r\nIMAGETYPE \" \"\r\nFONTSET \" \"\r\nIMAGECOLOR -1 -1 -1\r\n\r\n#remove this text and add objects here\r\n\r\nEND # end of mapfile','pics/icons/world_add.png','maps'),(34,'bold','__text__','pics/icons/text_bold.png','newsletters'),(35,'italic','\'\'text\'\'','pics/icons/text_italic.png','newsletters'),(36,'underline','===text===','pics/icons/text_underline.png','newsletters'),(37,'external link','[http://example.com|text|nocache]','pics/icons/world_link.png','newsletters'),(38,'heading1','!text','pics/icons/text_heading_1.png','newsletters'),(39,'heading2','!!text','pics/icons/text_heading_2.png','newsletters'),(40,'heading3','!!!text','pics/icons/text_heading_3.png','newsletters'),(41,'hr','---','pics/icons/page.png','newsletters'),(42,'center text','::text::','pics/icons/text_align_center.png','newsletters'),(43,'colored text','~~#FF0000:text~~','pics/icons/palette.png','newsletters'),(44,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','newsletters'),(45,'Deleted','--text--','pics/icons/text_strikethrough.png','newsletters'),(46,'bold','__text__','pics/icons/text_bold.png','trackers'),(47,'italic','\'\'text\'\'','pics/icons/text_italic.png','trackers'),(48,'underline','===text===','pics/icons/text_underline.png','trackers'),(49,'table new','||r1c1|r1c2\nr2c1|r2c2||','pics/icons/table.png','trackers'),(50,'external link','[http://example.com|text]','pics/icons/world_link.png','trackers'),(51,'wiki link','((text))','pics/icons/page_link.png','trackers'),(52,'heading1','!text','pics/icons/text_heading_1.png','trackers'),(53,'heading2','!!text','pics/icons/text_heading_2.png','trackers'),(54,'heading3','!!!text','pics/icons/text_heading_3.png','trackers'),(55,'title bar','-=text=-','pics/icons/text_padding_top.png','trackers'),(56,'box','^text^','pics/icons/box.png','trackers'),(57,'dynamic content','{content id= }','pics/icons/database_refresh.png','trackers'),(58,'hr','---','pics/icons/page.png','trackers'),(59,'center text','::text::','pics/icons/text_align_center.png','trackers'),(60,'colored text','~~#FF0000:text~~','pics/icons/palette.png','trackers'),(61,'dynamic variable','%text%','pics/icons/book_open.png','trackers'),(62,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','trackers'),(63,'Email Address','[mailto:text|text]','pics/icons/email.png','trackers'),(64,'Deleted','--text--','pics/icons/text_strikethrough.png','trackers'),(65,'bold','__text__','pics/icons/text_bold.png','blogs'),(66,'italic','\'\'text\'\'','pics/icons/text_italic.png','blogs'),(67,'underline','===text===','pics/icons/text_underline.png','blogs'),(68,'table new','||r1c1|r1c2\nr2c1|r2c2||','pics/icons/table.png','blogs'),(69,'external link','[http://example.com|text]','pics/icons/world_link.png','blogs'),(70,'wiki link','((text))','pics/icons/page_link.png','blogs'),(71,'heading1','!text','pics/icons/text_heading_1.png','blogs'),(72,'heading2','!!text','pics/icons/text_heading_2.png','blogs'),(73,'heading3','!!!text','pics/icons/text_heading_3.png','blogs'),(74,'title bar','-=text=-','pics/icons/text_padding_top.png','blogs'),(75,'box','^text^','pics/icons/box.png','blogs'),(76,'dynamic content','{content id= }','pics/icons/database_refresh.png','blogs'),(77,'hr','---','pics/icons/page.png','blogs'),(78,'center text','::text::','pics/icons/text_align_center.png','blogs'),(79,'colored text','~~#FF0000:text~~','pics/icons/palette.png','blogs'),(80,'dynamic variable','%text%','pics/icons/book_open.png','blogs'),(81,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','blogs'),(82,'Email Address','[mailto:text|text]','pics/icons/email.png','blogs'),(83,'Deleted','--text--','pics/icons/text_strikethrough.png','blogs'),(84,'bold','__text__','pics/icons/text_bold.png','calendar'),(85,'italic','\'\'text\'\'','pics/icons/text_italic.png','calendar'),(86,'underline','===text===','pics/icons/text_underline.png','calendar'),(87,'table new','||r1c1|r1c2\nr2c1|r2c2||','pics/icons/table.png','calendar'),(88,'external link','[http://example.com|text]','pics/icons/world_link.png','calendar'),(89,'wiki link','((text))','pics/icons/page_link.png','calendar'),(90,'heading1','!text','pics/icons/text_heading_1.png','calendar'),(91,'heading2','!!text','pics/icons/text_heading_2.png','calendar'),(92,'heading3','!!!text','pics/icons/text_heading_3.png','calendar'),(93,'title bar','-=text=-','pics/icons/text_padding_top.png','calendar'),(94,'box','^text^','pics/icons/box.png','calendar'),(95,'dynamic content','{content id= }','pics/icons/database_refresh.png','calendar'),(96,'hr','---','pics/icons/page.png','calendar'),(97,'center text','::text::','pics/icons/text_align_center.png','calendar'),(98,'colored text','~~#FF0000:text~~','pics/icons/palette.png','calendar'),(99,'dynamic variable','%text%','pics/icons/book_open.png','calendar'),(100,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','calendar'),(101,'Deleted','--text--','pics/icons/text_strikethrough.png','calendar'),(102,'bold','__text__','pics/icons/text_bold.png','articles'),(103,'italic','\'\'text\'\'','pics/icons/text_italic.png','articles'),(104,'underline','===text===','pics/icons/text_underline.png','articles'),(105,'table new','||r1c1|r1c2\nr2c1|r2c2||','pics/icons/table.png','articles'),(106,'external link','[http://example.com|text]','pics/icons/world_link.png','articles'),(107,'wiki link','((text))','pics/icons/page_link.png','articles'),(108,'heading1','!text','pics/icons/text_heading_1.png','articles'),(109,'heading2','!!text','pics/icons/text_heading_2.png','articles'),(110,'heading3','!!!text','pics/icons/text_heading_3.png','articles'),(111,'title bar','-=text=-','pics/icons/text_padding_top.png','articles'),(112,'box','^text^','pics/icons/box.png','articles'),(113,'dynamic content','{content id= }','pics/icons/database_refresh.png','articles'),(114,'hr','---','pics/icons/page.png','articles'),(115,'center text','::text::','pics/icons/text_align_center.png','articles'),(116,'colored text','~~#FF0000:text~~','pics/icons/palette.png','articles'),(117,'dynamic variable','%text%','pics/icons/book_open.png','articles'),(118,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','articles'),(119,'Email Address','[mailto:text|text]','pics/icons/email.png','articles'),(120,'Deleted','--text--','pics/icons/text_strikethrough.png','articles'),(121,'bold','__text__','pics/icons/text_bold.png','faqs'),(122,'italic','\'\'text\'\'','pics/icons/text_italic.png','faqs'),(123,'underline','===text===','pics/icons/text_underline.png','faqs'),(124,'table new','||r1c1|r1c2\nr2c1|r2c2||','pics/icons/table.png','faqs'),(125,'external link','[http://example.com|text]','pics/icons/world_link.png','faqs'),(126,'wiki link','((text))','pics/icons/page_link.png','faqs'),(127,'heading1','!text','pics/icons/text_heading_1.png','faqs'),(128,'heading2','!!text','pics/icons/text_heading_2.png','faqs'),(129,'heading3','!!!text','pics/icons/text_heading_3.png','faqs'),(130,'title bar','-=text=-','pics/icons/text_padding_top.png','faqs'),(131,'box','^text^','pics/icons/box.png','faqs'),(132,'dynamic content','{content id= }','pics/icons/database_refresh.png','faqs'),(133,'hr','---','pics/icons/page.png','faqs'),(134,'center text','::text::','pics/icons/text_align_center.png','faqs'),(135,'colored text','~~#FF0000:text~~','pics/icons/palette.png','faqs'),(136,'dynamic variable','%text%','pics/icons/book_open.png','faqs'),(137,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','faqs'),(138,'Email Address','[mailto:text|text]','pics/icons/email.png','faqs'),(139,'Deleted','--text--','pics/icons/text_strikethrough.png','faqs'),(140,'bold','__text__','pics/icons/text_bold.png','forums'),(141,'italic','\'\'text\'\'','pics/icons/text_italic.png','forums'),(142,'underline','===text===','pics/icons/text_underline.png','forums'),(143,'table new','||r1c1|r1c2\nr2c1|r2c2||','pics/icons/table.png','forums'),(144,'external link','[http://example.com|text]','pics/icons/world_link.png','forums'),(145,'wiki link','((text))','pics/icons/page_link.png','forums'),(146,'heading1','!text','pics/icons/text_heading_1.png','forums'),(147,'heading2','!!text','pics/icons/text_heading_2.png','forums'),(148,'heading3','!!!text','pics/icons/text_heading_3.png','forums'),(149,'title bar','-=text=-','pics/icons/text_padding_top.png','forums'),(150,'box','^text^','pics/icons/box.png','forums'),(151,'dynamic content','{content id= }','pics/icons/database_refresh.png','forums'),(152,'hr','---','pics/icons/page.png','forums'),(153,'center text','::text::','pics/icons/text_align_center.png','forums'),(154,'colored text','~~#FF0000:text~~','pics/icons/palette.png','forums'),(155,'dynamic variable','%text%','pics/icons/book_open.png','forums'),(156,'image','{img src= width= height= align= desc= link= }','pics/icons/picture.png','forums'),(157,'Email Address','[mailto:text|text]','pics/icons/email.png','forums'),(158,'Deleted','--text--','pics/icons/text_strikethrough.png','forums');
/*!40000 ALTER TABLE `tiki_quicktags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_quiz_question_options`
--

DROP TABLE IF EXISTS `tiki_quiz_question_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_quiz_question_options` (
  `optionId` int(10) NOT NULL AUTO_INCREMENT,
  `questionId` int(10) DEFAULT NULL,
  `optionText` text,
  `points` int(4) DEFAULT NULL,
  PRIMARY KEY (`optionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_quiz_question_options`
--

LOCK TABLES `tiki_quiz_question_options` WRITE;
/*!40000 ALTER TABLE `tiki_quiz_question_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_quiz_question_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_quiz_questions`
--

DROP TABLE IF EXISTS `tiki_quiz_questions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_quiz_questions` (
  `questionId` int(10) NOT NULL AUTO_INCREMENT,
  `quizId` int(10) DEFAULT NULL,
  `question` text,
  `position` int(4) DEFAULT NULL,
  `type` char(1) DEFAULT NULL,
  `maxPoints` int(4) DEFAULT NULL,
  PRIMARY KEY (`questionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_quiz_questions`
--

LOCK TABLES `tiki_quiz_questions` WRITE;
/*!40000 ALTER TABLE `tiki_quiz_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_quiz_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_quiz_results`
--

DROP TABLE IF EXISTS `tiki_quiz_results`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_quiz_results` (
  `resultId` int(10) NOT NULL AUTO_INCREMENT,
  `quizId` int(10) DEFAULT NULL,
  `fromPoints` int(4) DEFAULT NULL,
  `toPoints` int(4) DEFAULT NULL,
  `answer` text,
  PRIMARY KEY (`resultId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_quiz_results`
--

LOCK TABLES `tiki_quiz_results` WRITE;
/*!40000 ALTER TABLE `tiki_quiz_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_quiz_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_quiz_stats`
--

DROP TABLE IF EXISTS `tiki_quiz_stats`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_quiz_stats` (
  `quizId` int(10) NOT NULL DEFAULT '0',
  `questionId` int(10) NOT NULL DEFAULT '0',
  `optionId` int(10) NOT NULL DEFAULT '0',
  `votes` int(10) DEFAULT NULL,
  PRIMARY KEY (`quizId`,`questionId`,`optionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_quiz_stats`
--

LOCK TABLES `tiki_quiz_stats` WRITE;
/*!40000 ALTER TABLE `tiki_quiz_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_quiz_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_quiz_stats_sum`
--

DROP TABLE IF EXISTS `tiki_quiz_stats_sum`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_quiz_stats_sum` (
  `quizId` int(10) NOT NULL DEFAULT '0',
  `quizName` varchar(255) DEFAULT NULL,
  `timesTaken` int(10) DEFAULT NULL,
  `avgpoints` decimal(5,2) DEFAULT NULL,
  `avgavg` decimal(5,2) DEFAULT NULL,
  `avgtime` decimal(5,2) DEFAULT NULL,
  PRIMARY KEY (`quizId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_quiz_stats_sum`
--

LOCK TABLES `tiki_quiz_stats_sum` WRITE;
/*!40000 ALTER TABLE `tiki_quiz_stats_sum` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_quiz_stats_sum` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_quizzes`
--

DROP TABLE IF EXISTS `tiki_quizzes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_quizzes` (
  `quizId` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `canRepeat` char(1) DEFAULT NULL,
  `storeResults` char(1) DEFAULT NULL,
  `questionsPerPage` int(4) DEFAULT NULL,
  `timeLimited` char(1) DEFAULT NULL,
  `timeLimit` int(14) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `taken` int(10) DEFAULT NULL,
  `immediateFeedback` char(1) DEFAULT NULL,
  `showAnswers` char(1) DEFAULT NULL,
  `shuffleQuestions` char(1) DEFAULT NULL,
  `shuffleAnswers` char(1) DEFAULT NULL,
  `publishDate` int(14) DEFAULT NULL,
  `expireDate` int(14) DEFAULT NULL,
  `bDeleted` char(1) DEFAULT NULL,
  `nVersion` int(4) NOT NULL,
  `nAuthor` int(4) DEFAULT NULL,
  `bOnline` char(1) DEFAULT NULL,
  `bRandomQuestions` char(1) DEFAULT NULL,
  `nRandomQuestions` tinyint(4) DEFAULT NULL,
  `bLimitQuestionsPerPage` char(1) DEFAULT NULL,
  `nLimitQuestionsPerPage` tinyint(4) DEFAULT NULL,
  `bMultiSession` char(1) DEFAULT NULL,
  `nCanRepeat` tinyint(4) DEFAULT NULL,
  `sGradingMethod` varchar(80) DEFAULT NULL,
  `sShowScore` varchar(80) DEFAULT NULL,
  `sShowCorrectAnswers` varchar(80) DEFAULT NULL,
  `sPublishStats` varchar(80) DEFAULT NULL,
  `bAdditionalQuestions` char(1) DEFAULT NULL,
  `bForum` char(1) DEFAULT NULL,
  `sForum` varchar(80) DEFAULT NULL,
  `sPrologue` text,
  `sData` text,
  `sEpilogue` text,
  `passingperct` int(4) DEFAULT '0',
  PRIMARY KEY (`quizId`,`nVersion`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_quizzes`
--

LOCK TABLES `tiki_quizzes` WRITE;
/*!40000 ALTER TABLE `tiki_quizzes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_received_articles`
--

DROP TABLE IF EXISTS `tiki_received_articles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_received_articles` (
  `receivedArticleId` int(14) NOT NULL AUTO_INCREMENT,
  `receivedFromSite` varchar(200) DEFAULT NULL,
  `receivedFromUser` varchar(200) DEFAULT NULL,
  `receivedDate` int(14) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `authorName` varchar(60) DEFAULT NULL,
  `size` int(12) DEFAULT NULL,
  `useImage` char(1) DEFAULT NULL,
  `image_name` varchar(80) DEFAULT NULL,
  `image_type` varchar(80) DEFAULT NULL,
  `image_size` int(14) DEFAULT NULL,
  `image_x` int(4) DEFAULT NULL,
  `image_y` int(4) DEFAULT NULL,
  `image_data` longblob,
  `publishDate` int(14) DEFAULT NULL,
  `expireDate` int(14) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `heading` text,
  `body` longblob,
  `hash` varchar(32) DEFAULT NULL,
  `author` varchar(200) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  PRIMARY KEY (`receivedArticleId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_received_articles`
--

LOCK TABLES `tiki_received_articles` WRITE;
/*!40000 ALTER TABLE `tiki_received_articles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_received_articles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_received_pages`
--

DROP TABLE IF EXISTS `tiki_received_pages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_received_pages` (
  `receivedPageId` int(14) NOT NULL AUTO_INCREMENT,
  `pageName` varchar(160) NOT NULL DEFAULT '',
  `data` longblob,
  `description` varchar(200) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `receivedFromSite` varchar(200) DEFAULT NULL,
  `receivedFromUser` varchar(200) DEFAULT NULL,
  `receivedDate` int(14) DEFAULT NULL,
  `parent` varchar(255) DEFAULT NULL,
  `position` tinyint(3) unsigned DEFAULT NULL,
  `alias` varchar(255) DEFAULT NULL,
  `structureName` varchar(250) DEFAULT NULL,
  `parentName` varchar(250) DEFAULT NULL,
  `page_alias` varchar(250) DEFAULT '',
  `pos` int(4) DEFAULT NULL,
  PRIMARY KEY (`receivedPageId`),
  KEY `structureName` (`structureName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_received_pages`
--

LOCK TABLES `tiki_received_pages` WRITE;
/*!40000 ALTER TABLE `tiki_received_pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_received_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_referer_stats`
--

DROP TABLE IF EXISTS `tiki_referer_stats`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_referer_stats` (
  `referer` varchar(255) NOT NULL DEFAULT '',
  `hits` int(10) DEFAULT NULL,
  `last` int(14) DEFAULT NULL,
  PRIMARY KEY (`referer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_referer_stats`
--

LOCK TABLES `tiki_referer_stats` WRITE;
/*!40000 ALTER TABLE `tiki_referer_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_referer_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_registration_fields`
--

DROP TABLE IF EXISTS `tiki_registration_fields`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_registration_fields` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `field` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `show` tinyint(1) NOT NULL DEFAULT '1',
  `size` varchar(10) DEFAULT '10',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_registration_fields`
--

LOCK TABLES `tiki_registration_fields` WRITE;
/*!40000 ALTER TABLE `tiki_registration_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_registration_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_related_categories`
--

DROP TABLE IF EXISTS `tiki_related_categories`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_related_categories` (
  `categId` int(10) NOT NULL DEFAULT '0',
  `relatedTo` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`categId`,`relatedTo`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_related_categories`
--

LOCK TABLES `tiki_related_categories` WRITE;
/*!40000 ALTER TABLE `tiki_related_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_related_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_rss_feeds`
--

DROP TABLE IF EXISTS `tiki_rss_feeds`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_rss_feeds` (
  `name` varchar(30) NOT NULL DEFAULT '',
  `rssVer` char(1) NOT NULL DEFAULT '1',
  `refresh` int(8) DEFAULT '300',
  `lastUpdated` int(14) DEFAULT NULL,
  `cache` longblob,
  PRIMARY KEY (`name`,`rssVer`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_rss_feeds`
--

LOCK TABLES `tiki_rss_feeds` WRITE;
/*!40000 ALTER TABLE `tiki_rss_feeds` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_rss_feeds` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_rss_modules`
--

DROP TABLE IF EXISTS `tiki_rss_modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_rss_modules` (
  `rssId` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `description` text,
  `url` varchar(255) NOT NULL DEFAULT '',
  `refresh` int(8) DEFAULT NULL,
  `lastUpdated` int(14) DEFAULT NULL,
  `showTitle` char(1) DEFAULT 'n',
  `showPubDate` char(1) DEFAULT 'n',
  `content` longblob,
  PRIMARY KEY (`rssId`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_rss_modules`
--

LOCK TABLES `tiki_rss_modules` WRITE;
/*!40000 ALTER TABLE `tiki_rss_modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_rss_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_schema`
--

DROP TABLE IF EXISTS `tiki_schema`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_schema` (
  `patch_name` varchar(100) NOT NULL,
  `install_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`patch_name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_schema`
--

LOCK TABLES `tiki_schema` WRITE;
/*!40000 ALTER TABLE `tiki_schema` DISABLE KEYS */;
INSERT INTO `tiki_schema` VALUES ('20080227_invite_tiki','2009-04-28 18:43:34'),('20090205_forum_and_comments_locking_tiki','2009-04-28 18:43:34'),('20090205_menus_items_icons_tiki','2009-04-28 18:43:34'),('20090301_browsertitle_tiki','2009-04-28 18:43:34'),('20090304_tiki_p_view_backlink_without_s_tiki','2009-04-28 18:43:34'),('20090306_menu_perms_tiki','2009-04-28 18:43:34'),('20090306_text_for_menu_options_tiki','2009-04-28 18:43:34'),('20090310_application_menu_tiki','2009-04-28 18:43:34'),('20090310_feature_search_show_object_filter_tiki','2009-04-28 18:43:34'),('20090311_remove_mod_menu_application_menu_tiki','2009-04-28 18:43:34'),('20090316_index_tiki','2009-04-28 18:43:34'),('20090323_maxUserImpressions_banners_tiki','2009-04-28 18:43:34'),('20090330_quicktags_renames_tiki','2009-04-28 18:43:34'),('20090401_newsletters_included_tiki','2009-04-28 18:43:34'),('20090401_replace_shoutjax_with_shoutbox_tiki','2009-04-28 18:43:34'),('20090401_replace_switch_lang2_with_switch_lang_tiki','2009-04-28 18:43:34'),('20090416_plugin_security_tiki','2009-04-28 18:43:34'),('20090416_quicktags_kil_email_tiki','2009-04-28 18:43:34'),('20090416_quicktags_plugin_helpers_tiki','2009-04-28 18:43:34');
/*!40000 ALTER TABLE `tiki_schema` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_score`
--

DROP TABLE IF EXISTS `tiki_score`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_score` (
  `event` varchar(40) NOT NULL DEFAULT '',
  `score` int(11) NOT NULL DEFAULT '0',
  `expiration` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`event`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_score`
--

LOCK TABLES `tiki_score` WRITE;
/*!40000 ALTER TABLE `tiki_score` DISABLE KEYS */;
INSERT INTO `tiki_score` VALUES ('login',1,0),('login_remain',2,60),('profile_fill',10,0),('profile_see',2,0),('profile_is_seen',1,0),('friend_new',10,0),('message_receive',1,0),('message_send',2,0),('article_read',2,0),('article_comment',5,0),('article_new',20,0),('article_is_read',1,0),('article_is_commented',2,0),('fgallery_new',10,0),('fgallery_new_file',10,0),('fgallery_download',5,0),('fgallery_is_downloaded',5,0),('igallery_new',10,0),('igallery_new_img',6,0),('igallery_see_img',3,0),('igallery_img_seen',1,0),('blog_new',20,0),('blog_post',5,0),('blog_read',2,0),('blog_comment',2,0),('blog_is_read',3,0),('blog_is_commented',3,0),('wiki_new',10,0),('wiki_edit',5,0),('wiki_attach_file',3,0);
/*!40000 ALTER TABLE `tiki_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_search_stats`
--

DROP TABLE IF EXISTS `tiki_search_stats`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_search_stats` (
  `term` varchar(50) NOT NULL DEFAULT '',
  `hits` int(10) DEFAULT NULL,
  PRIMARY KEY (`term`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_search_stats`
--

LOCK TABLES `tiki_search_stats` WRITE;
/*!40000 ALTER TABLE `tiki_search_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_search_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_searchindex`
--

DROP TABLE IF EXISTS `tiki_searchindex`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_searchindex` (
  `searchword` varchar(80) NOT NULL DEFAULT '',
  `location` varchar(80) NOT NULL DEFAULT '',
  `page` varchar(255) NOT NULL DEFAULT '',
  `count` int(11) NOT NULL DEFAULT '1',
  `last_update` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`searchword`,`location`,`page`(80)),
  KEY `last_update` (`last_update`),
  KEY `location` (`location`(50),`page`(200))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_searchindex`
--

LOCK TABLES `tiki_searchindex` WRITE;
/*!40000 ALTER TABLE `tiki_searchindex` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_searchindex` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_searchsyllable`
--

DROP TABLE IF EXISTS `tiki_searchsyllable`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_searchsyllable` (
  `syllable` varchar(80) NOT NULL DEFAULT '',
  `lastUsed` int(11) NOT NULL DEFAULT '0',
  `lastUpdated` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`syllable`),
  KEY `lastUsed` (`lastUsed`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_searchsyllable`
--

LOCK TABLES `tiki_searchsyllable` WRITE;
/*!40000 ALTER TABLE `tiki_searchsyllable` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_searchsyllable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_searchwords`
--

DROP TABLE IF EXISTS `tiki_searchwords`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_searchwords` (
  `syllable` varchar(80) NOT NULL DEFAULT '',
  `searchword` varchar(80) NOT NULL DEFAULT '',
  PRIMARY KEY (`syllable`,`searchword`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_searchwords`
--

LOCK TABLES `tiki_searchwords` WRITE;
/*!40000 ALTER TABLE `tiki_searchwords` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_searchwords` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_secdb`
--

DROP TABLE IF EXISTS `tiki_secdb`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_secdb` (
  `md5_value` varchar(32) NOT NULL,
  `filename` varchar(250) NOT NULL,
  `tiki_version` varchar(60) NOT NULL,
  `severity` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`md5_value`,`filename`(100),`tiki_version`),
  KEY `sdb_fn` (`filename`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_secdb`
--

LOCK TABLES `tiki_secdb` WRITE;
/*!40000 ALTER TABLE `tiki_secdb` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_secdb` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sefurl_regex_out`
--

DROP TABLE IF EXISTS `tiki_sefurl_regex_out`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sefurl_regex_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `left` varchar(256) NOT NULL,
  `right` varchar(256) DEFAULT NULL,
  `type` varchar(32) DEFAULT NULL,
  `silent` char(1) DEFAULT 'n',
  `feature` varchar(256) DEFAULT NULL,
  `comment` varchar(256) DEFAULT NULL,
  `order` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `left` (`left`),
  KEY `idx1` (`silent`,`type`,`feature`(30))
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sefurl_regex_out`
--

LOCK TABLES `tiki_sefurl_regex_out` WRITE;
/*!40000 ALTER TABLE `tiki_sefurl_regex_out` DISABLE KEYS */;
INSERT INTO `tiki_sefurl_regex_out` VALUES (1,'tiki-index.php\\?page=(.+)','$1','wiki','n','feature_wiki',NULL,0),(2,'tiki-slideshow.php\\?page=(.+)','show:$1','','n','feature_wiki',NULL,0),(3,'tiki-read_article.php\\?articleId=(\\d+)','article$1','article','n','feature_articles',NULL,0),(4,'tiki-browse_categories.php\\?parentId=(\\d+)','cat$1','category','n','feature_categories',NULL,0),(5,'tiki-view_blog.php\\?blogId=(\\d+)','blog$1','blog','n','feature_blogs',NULL,0),(6,'tiki-view_blog_post.php\\?postId=(\\d+)','blogpost$1','blogpost','n','feature_blogs',NULL,0),(7,'tiki-browse_image.php\\?imageId=(\\d+)','browseimage$1','image','n','feature_galleries',NULL,0),(8,'tiki-view_chart.php\\?chartId=(\\d+)','chart$1','chart','n','feature_charts',NULL,0),(9,'tiki-directory_browse.php\\?parent=(\\d+)','directory$1','directory','n','feature_directory',NULL,0),(10,'tiki-view_faq.php\\?faqId=(\\d+)','faq$1','faq','n','feature_faqs',NULL,0),(11,'tiki-list_file_gallery.php\\?galleryId=(\\d+)','file$1','file','n','feature_file_galleries',NULL,0),(12,'tiki-download_file.php\\?fileId=(\\d+)','dl$1','file','n','feature_file_galleries',NULL,0),(13,'tiki-view_forum.php\\?forumId=(\\d+)','forum$1','forum','n','feature_forums',NULL,0),(14,'tiki-browse_gallery.php\\?galleryId=(\\d+)','gallery$1','gallery','n','feature_galleries',NULL,0),(15,'show_image.php\\?id=(\\d+)','image$1','image','n','feature_galleries',NULL,0),(16,'show_image.php\\?id=(\\d+)&scalesize=(\\d+)','imagescale$1/$2','image','n','feature_galleries',NULL,0),(17,'tiki-newsletters.php\\?nlId=(\\d+)','newsletter$1','newsletter','n','feature_newsletters',NULL,0),(18,'tiki-take_quiz.php\\?quizId=(\\d+)','quiz$1','quiz','n','feature_quizzes',NULL,0),(19,'tiki-take_survey.php\\?surveyId=(\\d+)','survey$1','survey','n','feature_surveys',NULL,0),(20,'tiki-view_tracker.php\\?trackerId=(\\d+)','tracker$1','tracker','n','feature_trackers',NULL,0),(21,'tiki-integrator.php\\?repID=(\\d+)','int$1','','n','feature_integrator',NULL,0),(22,'tiki-view_sheets.php\\?sheetId=(\\d+)','sheet$1','sheet','n','feature_sheet',NULL,0),(23,'tiki-directory_redirect.php\\?siteId=(\\d+)','dirlink$1','directory','n','feature_directory',NULL,0),(24,'tiki-calendar.php\\?calIds\\[\\]=(\\d+)&calIds\\[\\]=(\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)','cal$1,$2,$3,$4,$5,$6,$7','calendar','n','feature_calendar','7',100),(25,'tiki-calendar.php\\?calIds\\[\\]=(\\d+)&calIds\\[\\]=(\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)','cal$1,$2,$3,$4,$5,$6','calendar','n','feature_calendar','6',101),(26,'tiki-calendar.php\\?calIds\\[\\]=(\\d+)&calIds\\[\\]=(\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)','cal$1,$2,$3,$4,$5','calendar','n','feature_calendar','5',102),(27,'tiki-calendar.php\\?calIds\\[\\]=(\\d+)&calIds\\[\\]=(\\d+)&callIds\\[\\](\\d+)&callIds\\[\\](\\d+)','cal$1,$2,$3,$4','calendar','n','feature_calendar','4',103),(28,'tiki-calendar.php\\?calIds\\[\\]=(\\d+)&calIds\\[\\]=(\\d+)&callIds\\[\\](\\d+)','cal$1,$2,$3','calendar','n','feature_calendar','3',104),(29,'tiki-calendar.php\\?calIds\\[\\]=(\\d+)&calIds\\[\\]=(\\d+)','cal$1,$2','calendar','n','feature_calendar','2',105),(30,'tiki-calendar.php\\?calIds\\[\\]=(\\d+)','cal$1','calendar','n','feature_calendar','1',106),(31,'tiki-calendar.php','calendar','calendar','n','feature_calendar',NULL,200),(32,'tiki-view_articles.php','articles','','n','feature_articles',NULL,200),(33,'tiki-list_blogs.php','blogs','','n','feature_blogs',NULL,200),(34,'tiki-browse_categories.php','categories','','n','feature_categories',NULL,200),(35,'tiki-list_charts.php','charts','','n','feature_charts',NULL,200),(36,'tiki-contact.php','contact','','n','feature_contact',NULL,200),(37,'tiki-directory_browse.php','directories','','n','feature_directory',NULL,200),(38,'tiki-list_faqs.php','faqs','','n','feature_faqs',NULL,200),(39,'tiki-file_galleries.php','files','','n','feature_file_galleries',NULL,200),(40,'tiki-forums.php','forums','','n','feature_forums',NULL,200),(41,'tiki-galleries.php','galleries','','n','feature_galleries',NULL,200),(42,'tiki-login_scr.php','login','','n','',NULL,200),(43,'tiki-my_tiki.php','my','','n','',NULL,200),(44,'tiki-newsletters.php','newsletters','newsletter','n','feature_newsletters',NULL,200),(45,'tiki-list_quizzes.php','quizzes','','n','feature_quizzes',NULL,200),(46,'tiki-stats.php','stats','','n','feature_stats',NULL,200),(47,'tiki-list_surveys.php','surveys','','n','feature_surveys',NULL,200),(48,'tiki-list_trackers.php','trackers','','n','feature_trackers',NULL,200),(49,'tiki-mobile.php','mobile','','n','feature_mobile',NULL,200),(50,'tiki-sheets.php','sheets','','n','feature_sheet',NULL,200);
/*!40000 ALTER TABLE `tiki_sefurl_regex_out` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_semantic_tokens`
--

DROP TABLE IF EXISTS `tiki_semantic_tokens`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_semantic_tokens` (
  `token` varchar(15) NOT NULL,
  `label` varchar(25) NOT NULL,
  `invert_token` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_semantic_tokens`
--

LOCK TABLES `tiki_semantic_tokens` WRITE;
/*!40000 ALTER TABLE `tiki_semantic_tokens` DISABLE KEYS */;
INSERT INTO `tiki_semantic_tokens` VALUES ('alias','Page Alias',NULL);
/*!40000 ALTER TABLE `tiki_semantic_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_semaphores`
--

DROP TABLE IF EXISTS `tiki_semaphores`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_semaphores` (
  `semName` varchar(250) NOT NULL DEFAULT '',
  `objectType` varchar(20) DEFAULT 'wiki page',
  `user` varchar(200) NOT NULL DEFAULT '',
  `timestamp` int(14) DEFAULT NULL,
  PRIMARY KEY (`semName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_semaphores`
--

LOCK TABLES `tiki_semaphores` WRITE;
/*!40000 ALTER TABLE `tiki_semaphores` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_semaphores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sent_newsletters`
--

DROP TABLE IF EXISTS `tiki_sent_newsletters`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sent_newsletters` (
  `editionId` int(12) NOT NULL AUTO_INCREMENT,
  `nlId` int(12) NOT NULL DEFAULT '0',
  `users` int(10) DEFAULT NULL,
  `sent` int(14) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `data` longblob,
  `datatxt` longblob,
  PRIMARY KEY (`editionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sent_newsletters`
--

LOCK TABLES `tiki_sent_newsletters` WRITE;
/*!40000 ALTER TABLE `tiki_sent_newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_sent_newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sent_newsletters_errors`
--

DROP TABLE IF EXISTS `tiki_sent_newsletters_errors`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sent_newsletters_errors` (
  `editionId` int(12) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `login` varchar(40) DEFAULT '',
  `error` char(1) DEFAULT '',
  KEY `editionId` (`editionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sent_newsletters_errors`
--

LOCK TABLES `tiki_sent_newsletters_errors` WRITE;
/*!40000 ALTER TABLE `tiki_sent_newsletters_errors` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_sent_newsletters_errors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sent_newsletters_files`
--

DROP TABLE IF EXISTS `tiki_sent_newsletters_files`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sent_newsletters_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `editionId` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `type` varchar(64) NOT NULL,
  `size` int(11) NOT NULL,
  `filename` varchar(256) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `editionId` (`editionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sent_newsletters_files`
--

LOCK TABLES `tiki_sent_newsletters_files` WRITE;
/*!40000 ALTER TABLE `tiki_sent_newsletters_files` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_sent_newsletters_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sessions`
--

DROP TABLE IF EXISTS `tiki_sessions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sessions` (
  `sessionId` varchar(32) NOT NULL DEFAULT '',
  `user` varchar(200) DEFAULT '',
  `timestamp` int(14) DEFAULT NULL,
  `tikihost` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`sessionId`),
  KEY `user` (`user`),
  KEY `timestamp` (`timestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sessions`
--

LOCK TABLES `tiki_sessions` WRITE;
/*!40000 ALTER TABLE `tiki_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sheet_layout`
--

DROP TABLE IF EXISTS `tiki_sheet_layout`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sheet_layout` (
  `sheetId` int(8) NOT NULL DEFAULT '0',
  `begin` int(10) NOT NULL DEFAULT '0',
  `end` int(10) DEFAULT NULL,
  `headerRow` int(4) NOT NULL DEFAULT '0',
  `footerRow` int(4) NOT NULL DEFAULT '0',
  `className` varchar(64) DEFAULT NULL,
  UNIQUE KEY `sheetId` (`sheetId`,`begin`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sheet_layout`
--

LOCK TABLES `tiki_sheet_layout` WRITE;
/*!40000 ALTER TABLE `tiki_sheet_layout` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_sheet_layout` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sheet_values`
--

DROP TABLE IF EXISTS `tiki_sheet_values`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sheet_values` (
  `sheetId` int(8) NOT NULL DEFAULT '0',
  `begin` int(10) NOT NULL DEFAULT '0',
  `end` int(10) DEFAULT NULL,
  `rowIndex` int(4) NOT NULL DEFAULT '0',
  `columnIndex` int(4) NOT NULL DEFAULT '0',
  `value` varchar(255) DEFAULT NULL,
  `calculation` varchar(255) DEFAULT NULL,
  `width` int(4) NOT NULL DEFAULT '1',
  `height` int(4) NOT NULL DEFAULT '1',
  `format` varchar(255) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  UNIQUE KEY `sheetId` (`sheetId`,`begin`,`rowIndex`,`columnIndex`),
  KEY `sheetId_2` (`sheetId`,`rowIndex`,`columnIndex`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sheet_values`
--

LOCK TABLES `tiki_sheet_values` WRITE;
/*!40000 ALTER TABLE `tiki_sheet_values` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_sheet_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_sheets`
--

DROP TABLE IF EXISTS `tiki_sheets`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_sheets` (
  `sheetId` int(8) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `author` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`sheetId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_sheets`
--

LOCK TABLES `tiki_sheets` WRITE;
/*!40000 ALTER TABLE `tiki_sheets` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_shoutbox`
--

DROP TABLE IF EXISTS `tiki_shoutbox`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_shoutbox` (
  `msgId` int(10) NOT NULL AUTO_INCREMENT,
  `message` varchar(255) DEFAULT NULL,
  `timestamp` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT '',
  `hash` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`msgId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_shoutbox`
--

LOCK TABLES `tiki_shoutbox` WRITE;
/*!40000 ALTER TABLE `tiki_shoutbox` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_shoutbox` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_shoutbox_words`
--

DROP TABLE IF EXISTS `tiki_shoutbox_words`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_shoutbox_words` (
  `word` varchar(40) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_shoutbox_words`
--

LOCK TABLES `tiki_shoutbox_words` WRITE;
/*!40000 ALTER TABLE `tiki_shoutbox_words` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_shoutbox_words` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_stats`
--

DROP TABLE IF EXISTS `tiki_stats`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_stats` (
  `object` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(20) NOT NULL DEFAULT '',
  `day` int(14) NOT NULL DEFAULT '0',
  `hits` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`object`(200),`type`,`day`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_stats`
--

LOCK TABLES `tiki_stats` WRITE;
/*!40000 ALTER TABLE `tiki_stats` DISABLE KEYS */;
INSERT INTO `tiki_stats` VALUES ('HomePage','wiki',1245902400,3),('EnglishTestPage','wiki',1245902400,1);
/*!40000 ALTER TABLE `tiki_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_structure_versions`
--

DROP TABLE IF EXISTS `tiki_structure_versions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_structure_versions` (
  `structure_id` int(14) NOT NULL AUTO_INCREMENT,
  `version` int(14) DEFAULT NULL,
  PRIMARY KEY (`structure_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_structure_versions`
--

LOCK TABLES `tiki_structure_versions` WRITE;
/*!40000 ALTER TABLE `tiki_structure_versions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_structure_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_structures`
--

DROP TABLE IF EXISTS `tiki_structures`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_structures` (
  `page_ref_id` int(14) NOT NULL AUTO_INCREMENT,
  `structure_id` int(14) NOT NULL,
  `parent_id` int(14) DEFAULT NULL,
  `page_id` int(14) NOT NULL,
  `page_version` int(8) DEFAULT NULL,
  `page_alias` varchar(240) NOT NULL DEFAULT '',
  `pos` int(4) DEFAULT NULL,
  PRIMARY KEY (`page_ref_id`),
  KEY `pidpaid` (`page_id`,`parent_id`),
  KEY `page_id` (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_structures`
--

LOCK TABLES `tiki_structures` WRITE;
/*!40000 ALTER TABLE `tiki_structures` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_structures` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_submissions`
--

DROP TABLE IF EXISTS `tiki_submissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_submissions` (
  `subId` int(8) NOT NULL AUTO_INCREMENT,
  `topline` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `linkto` varchar(255) DEFAULT NULL,
  `lang` varchar(16) DEFAULT NULL,
  `authorName` varchar(60) DEFAULT NULL,
  `topicId` int(14) DEFAULT NULL,
  `topicName` varchar(40) DEFAULT NULL,
  `size` int(12) DEFAULT NULL,
  `useImage` char(1) DEFAULT NULL,
  `image_name` varchar(80) DEFAULT NULL,
  `image_caption` text,
  `image_type` varchar(80) DEFAULT NULL,
  `image_size` int(14) DEFAULT NULL,
  `image_x` int(4) DEFAULT NULL,
  `image_y` int(4) DEFAULT NULL,
  `image_data` longblob,
  `publishDate` int(14) DEFAULT NULL,
  `expireDate` int(14) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `bibliographical_references` text,
  `resume` text,
  `heading` text,
  `body` text,
  `hash` varchar(32) DEFAULT NULL,
  `author` varchar(200) NOT NULL DEFAULT '',
  `nbreads` int(14) DEFAULT NULL,
  `votes` int(8) DEFAULT NULL,
  `points` int(14) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL,
  `isfloat` char(1) DEFAULT NULL,
  PRIMARY KEY (`subId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_submissions`
--

LOCK TABLES `tiki_submissions` WRITE;
/*!40000 ALTER TABLE `tiki_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_suggested_faq_questions`
--

DROP TABLE IF EXISTS `tiki_suggested_faq_questions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_suggested_faq_questions` (
  `sfqId` int(10) NOT NULL AUTO_INCREMENT,
  `faqId` int(10) NOT NULL DEFAULT '0',
  `question` text,
  `answer` text,
  `created` int(14) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`sfqId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_suggested_faq_questions`
--

LOCK TABLES `tiki_suggested_faq_questions` WRITE;
/*!40000 ALTER TABLE `tiki_suggested_faq_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_suggested_faq_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_survey_question_options`
--

DROP TABLE IF EXISTS `tiki_survey_question_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_survey_question_options` (
  `optionId` int(12) NOT NULL AUTO_INCREMENT,
  `questionId` int(12) NOT NULL DEFAULT '0',
  `qoption` text,
  `votes` int(10) DEFAULT NULL,
  PRIMARY KEY (`optionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_survey_question_options`
--

LOCK TABLES `tiki_survey_question_options` WRITE;
/*!40000 ALTER TABLE `tiki_survey_question_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_survey_question_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_survey_questions`
--

DROP TABLE IF EXISTS `tiki_survey_questions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_survey_questions` (
  `questionId` int(12) NOT NULL AUTO_INCREMENT,
  `surveyId` int(12) NOT NULL DEFAULT '0',
  `question` text,
  `options` text,
  `type` char(1) DEFAULT NULL,
  `position` int(5) DEFAULT NULL,
  `votes` int(10) DEFAULT NULL,
  `value` int(10) DEFAULT NULL,
  `average` decimal(4,2) DEFAULT NULL,
  `mandatory` char(1) NOT NULL DEFAULT 'n',
  `max_answers` int(5) NOT NULL DEFAULT '0',
  `min_answers` int(5) NOT NULL DEFAULT '0',
  PRIMARY KEY (`questionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_survey_questions`
--

LOCK TABLES `tiki_survey_questions` WRITE;
/*!40000 ALTER TABLE `tiki_survey_questions` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_survey_questions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_surveys`
--

DROP TABLE IF EXISTS `tiki_surveys`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_surveys` (
  `surveyId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `description` text,
  `taken` int(10) DEFAULT NULL,
  `lastTaken` int(14) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  PRIMARY KEY (`surveyId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_surveys`
--

LOCK TABLES `tiki_surveys` WRITE;
/*!40000 ALTER TABLE `tiki_surveys` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_surveys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_tags`
--

DROP TABLE IF EXISTS `tiki_tags`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_tags` (
  `tagName` varchar(80) NOT NULL DEFAULT '',
  `pageName` varchar(160) NOT NULL DEFAULT '',
  `hits` int(8) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `data` longblob,
  `lastModif` int(14) DEFAULT NULL,
  `comment` varchar(200) DEFAULT NULL,
  `version` int(8) NOT NULL DEFAULT '0',
  `user` varchar(200) NOT NULL DEFAULT '',
  `ip` varchar(15) DEFAULT NULL,
  `flag` char(1) DEFAULT NULL,
  PRIMARY KEY (`tagName`,`pageName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_tags`
--

LOCK TABLES `tiki_tags` WRITE;
/*!40000 ALTER TABLE `tiki_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_theme_control_categs`
--

DROP TABLE IF EXISTS `tiki_theme_control_categs`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_theme_control_categs` (
  `categId` int(12) NOT NULL DEFAULT '0',
  `theme` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`categId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_theme_control_categs`
--

LOCK TABLES `tiki_theme_control_categs` WRITE;
/*!40000 ALTER TABLE `tiki_theme_control_categs` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_theme_control_categs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_theme_control_objects`
--

DROP TABLE IF EXISTS `tiki_theme_control_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_theme_control_objects` (
  `objId` varchar(250) NOT NULL DEFAULT '',
  `type` varchar(250) NOT NULL DEFAULT '',
  `name` varchar(250) NOT NULL DEFAULT '',
  `theme` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`objId`(100),`type`(100))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_theme_control_objects`
--

LOCK TABLES `tiki_theme_control_objects` WRITE;
/*!40000 ALTER TABLE `tiki_theme_control_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_theme_control_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_theme_control_sections`
--

DROP TABLE IF EXISTS `tiki_theme_control_sections`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_theme_control_sections` (
  `section` varchar(250) NOT NULL DEFAULT '',
  `theme` varchar(250) NOT NULL DEFAULT '',
  PRIMARY KEY (`section`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_theme_control_sections`
--

LOCK TABLES `tiki_theme_control_sections` WRITE;
/*!40000 ALTER TABLE `tiki_theme_control_sections` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_theme_control_sections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_topics`
--

DROP TABLE IF EXISTS `tiki_topics`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_topics` (
  `topicId` int(14) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `image_name` varchar(80) DEFAULT NULL,
  `image_type` varchar(80) DEFAULT NULL,
  `image_size` int(14) DEFAULT NULL,
  `image_data` longblob,
  `active` char(1) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  PRIMARY KEY (`topicId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_topics`
--

LOCK TABLES `tiki_topics` WRITE;
/*!40000 ALTER TABLE `tiki_topics` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_topics` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_tracker_fields`
--

DROP TABLE IF EXISTS `tiki_tracker_fields`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_tracker_fields` (
  `fieldId` int(12) NOT NULL AUTO_INCREMENT,
  `trackerId` int(12) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `options` text,
  `type` char(15) DEFAULT NULL,
  `isMain` char(1) DEFAULT NULL,
  `isTblVisible` char(1) DEFAULT NULL,
  `position` int(4) DEFAULT NULL,
  `isSearchable` char(1) NOT NULL DEFAULT 'y',
  `isPublic` char(1) NOT NULL DEFAULT 'n',
  `isHidden` char(1) NOT NULL DEFAULT 'n',
  `isMandatory` char(1) NOT NULL DEFAULT 'n',
  `description` text,
  `isMultilingual` char(1) DEFAULT 'n',
  `itemChoices` text,
  `errorMsg` text,
  `visibleBy` text,
  `editableBy` text,
  `descriptionIsParsed` char(1) DEFAULT 'n',
  PRIMARY KEY (`fieldId`),
  KEY `trackerId` (`trackerId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_tracker_fields`
--

LOCK TABLES `tiki_tracker_fields` WRITE;
/*!40000 ALTER TABLE `tiki_tracker_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_tracker_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_tracker_item_attachments`
--

DROP TABLE IF EXISTS `tiki_tracker_item_attachments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_tracker_item_attachments` (
  `attId` int(12) NOT NULL AUTO_INCREMENT,
  `itemId` int(12) NOT NULL DEFAULT '0',
  `filename` varchar(80) DEFAULT NULL,
  `filetype` varchar(80) DEFAULT NULL,
  `filesize` int(14) DEFAULT NULL,
  `user` varchar(200) DEFAULT NULL,
  `data` longblob,
  `path` varchar(255) DEFAULT NULL,
  `hits` int(10) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `longdesc` blob,
  `version` varchar(40) DEFAULT NULL,
  PRIMARY KEY (`attId`),
  KEY `itemId` (`itemId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_tracker_item_attachments`
--

LOCK TABLES `tiki_tracker_item_attachments` WRITE;
/*!40000 ALTER TABLE `tiki_tracker_item_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_tracker_item_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_tracker_item_comments`
--

DROP TABLE IF EXISTS `tiki_tracker_item_comments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_tracker_item_comments` (
  `commentId` int(12) NOT NULL AUTO_INCREMENT,
  `itemId` int(12) NOT NULL DEFAULT '0',
  `user` varchar(200) DEFAULT NULL,
  `data` text,
  `title` varchar(200) DEFAULT NULL,
  `posted` int(14) DEFAULT NULL,
  PRIMARY KEY (`commentId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_tracker_item_comments`
--

LOCK TABLES `tiki_tracker_item_comments` WRITE;
/*!40000 ALTER TABLE `tiki_tracker_item_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_tracker_item_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_tracker_item_fields`
--

DROP TABLE IF EXISTS `tiki_tracker_item_fields`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_tracker_item_fields` (
  `itemId` int(12) NOT NULL DEFAULT '0',
  `fieldId` int(12) NOT NULL DEFAULT '0',
  `value` text,
  `lang` char(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`itemId`,`fieldId`,`lang`),
  KEY `fieldId` (`fieldId`),
  KEY `value` (`value`(250)),
  KEY `lang` (`lang`),
  FULLTEXT KEY `ft` (`value`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_tracker_item_fields`
--

LOCK TABLES `tiki_tracker_item_fields` WRITE;
/*!40000 ALTER TABLE `tiki_tracker_item_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_tracker_item_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_tracker_items`
--

DROP TABLE IF EXISTS `tiki_tracker_items`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_tracker_items` (
  `itemId` int(12) NOT NULL AUTO_INCREMENT,
  `trackerId` int(12) NOT NULL DEFAULT '0',
  `created` int(14) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  PRIMARY KEY (`itemId`),
  KEY `trackerId` (`trackerId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_tracker_items`
--

LOCK TABLES `tiki_tracker_items` WRITE;
/*!40000 ALTER TABLE `tiki_tracker_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_tracker_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_tracker_options`
--

DROP TABLE IF EXISTS `tiki_tracker_options`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_tracker_options` (
  `trackerId` int(12) NOT NULL DEFAULT '0',
  `name` varchar(80) NOT NULL DEFAULT '',
  `value` text,
  PRIMARY KEY (`trackerId`,`name`(30))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_tracker_options`
--

LOCK TABLES `tiki_tracker_options` WRITE;
/*!40000 ALTER TABLE `tiki_tracker_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_tracker_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_trackers`
--

DROP TABLE IF EXISTS `tiki_trackers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_trackers` (
  `trackerId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  `descriptionIsParsed` varchar(1) DEFAULT '0',
  `created` int(14) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `showCreated` char(1) DEFAULT NULL,
  `showStatus` char(1) DEFAULT NULL,
  `showLastModif` char(1) DEFAULT NULL,
  `useComments` char(1) DEFAULT NULL,
  `useAttachments` char(1) DEFAULT NULL,
  `showAttachments` char(1) DEFAULT NULL,
  `items` int(10) DEFAULT NULL,
  `showComments` char(1) DEFAULT NULL,
  `orderAttachments` varchar(255) NOT NULL DEFAULT 'filename,created,filesize,hits,desc',
  PRIMARY KEY (`trackerId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_trackers`
--

LOCK TABLES `tiki_trackers` WRITE;
/*!40000 ALTER TABLE `tiki_trackers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_trackers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_translated_objects`
--

DROP TABLE IF EXISTS `tiki_translated_objects`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_translated_objects` (
  `traId` int(14) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `objId` varchar(255) NOT NULL,
  `lang` varchar(16) DEFAULT NULL,
  PRIMARY KEY (`type`,`objId`),
  KEY `traId` (`traId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_translated_objects`
--

LOCK TABLES `tiki_translated_objects` WRITE;
/*!40000 ALTER TABLE `tiki_translated_objects` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_translated_objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_untranslated`
--

DROP TABLE IF EXISTS `tiki_untranslated`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_untranslated` (
  `id` int(14) NOT NULL AUTO_INCREMENT,
  `source` tinyblob NOT NULL,
  `lang` char(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`source`(255),`lang`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_untranslated`
--

LOCK TABLES `tiki_untranslated` WRITE;
/*!40000 ALTER TABLE `tiki_untranslated` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_untranslated` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_answers`
--

DROP TABLE IF EXISTS `tiki_user_answers`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_answers` (
  `userResultId` int(10) NOT NULL DEFAULT '0',
  `quizId` int(10) NOT NULL DEFAULT '0',
  `questionId` int(10) NOT NULL DEFAULT '0',
  `optionId` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`userResultId`,`quizId`,`questionId`,`optionId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_answers`
--

LOCK TABLES `tiki_user_answers` WRITE;
/*!40000 ALTER TABLE `tiki_user_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_answers_uploads`
--

DROP TABLE IF EXISTS `tiki_user_answers_uploads`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_answers_uploads` (
  `answerUploadId` int(4) NOT NULL AUTO_INCREMENT,
  `userResultId` int(11) NOT NULL DEFAULT '0',
  `questionId` int(11) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL DEFAULT '',
  `filetype` varchar(64) NOT NULL DEFAULT '',
  `filesize` varchar(255) NOT NULL DEFAULT '',
  `filecontent` longblob NOT NULL,
  PRIMARY KEY (`answerUploadId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_answers_uploads`
--

LOCK TABLES `tiki_user_answers_uploads` WRITE;
/*!40000 ALTER TABLE `tiki_user_answers_uploads` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_answers_uploads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_assigned_modules`
--

DROP TABLE IF EXISTS `tiki_user_assigned_modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_assigned_modules` (
  `moduleId` int(8) NOT NULL,
  `name` varchar(200) NOT NULL DEFAULT '',
  `position` char(1) NOT NULL DEFAULT '',
  `ord` int(4) NOT NULL DEFAULT '0',
  `type` char(1) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`name`(30),`user`,`position`,`ord`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_assigned_modules`
--

LOCK TABLES `tiki_user_assigned_modules` WRITE;
/*!40000 ALTER TABLE `tiki_user_assigned_modules` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_assigned_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_bookmarks_folders`
--

DROP TABLE IF EXISTS `tiki_user_bookmarks_folders`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_bookmarks_folders` (
  `folderId` int(12) NOT NULL AUTO_INCREMENT,
  `parentId` int(12) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  `name` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`user`,`folderId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_bookmarks_folders`
--

LOCK TABLES `tiki_user_bookmarks_folders` WRITE;
/*!40000 ALTER TABLE `tiki_user_bookmarks_folders` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_bookmarks_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_bookmarks_urls`
--

DROP TABLE IF EXISTS `tiki_user_bookmarks_urls`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_bookmarks_urls` (
  `urlId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `data` longblob,
  `lastUpdated` int(14) DEFAULT NULL,
  `folderId` int(12) NOT NULL DEFAULT '0',
  `user` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`urlId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_bookmarks_urls`
--

LOCK TABLES `tiki_user_bookmarks_urls` WRITE;
/*!40000 ALTER TABLE `tiki_user_bookmarks_urls` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_bookmarks_urls` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_mail_accounts`
--

DROP TABLE IF EXISTS `tiki_user_mail_accounts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_mail_accounts` (
  `accountId` int(12) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) NOT NULL DEFAULT '',
  `account` varchar(50) NOT NULL DEFAULT '',
  `pop` varchar(255) DEFAULT NULL,
  `current` char(1) DEFAULT NULL,
  `port` int(4) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `pass` varchar(100) DEFAULT NULL,
  `msgs` int(4) DEFAULT NULL,
  `smtp` varchar(255) DEFAULT NULL,
  `useAuth` char(1) DEFAULT NULL,
  `smtpPort` int(4) DEFAULT NULL,
  `flagsPublic` char(1) DEFAULT 'n',
  `autoRefresh` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`accountId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_mail_accounts`
--

LOCK TABLES `tiki_user_mail_accounts` WRITE;
/*!40000 ALTER TABLE `tiki_user_mail_accounts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_mail_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_menus`
--

DROP TABLE IF EXISTS `tiki_user_menus`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_menus` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `menuId` int(12) NOT NULL AUTO_INCREMENT,
  `url` varchar(250) DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `position` int(4) DEFAULT NULL,
  `mode` char(1) DEFAULT NULL,
  PRIMARY KEY (`menuId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_menus`
--

LOCK TABLES `tiki_user_menus` WRITE;
/*!40000 ALTER TABLE `tiki_user_menus` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_modules`
--

DROP TABLE IF EXISTS `tiki_user_modules`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_modules` (
  `name` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(40) DEFAULT NULL,
  `data` longblob,
  `parse` char(1) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_modules`
--

LOCK TABLES `tiki_user_modules` WRITE;
/*!40000 ALTER TABLE `tiki_user_modules` DISABLE KEYS */;
INSERT INTO `tiki_user_modules` VALUES ('mnu_application_menu','Menu','{menu id=42}','n');
/*!40000 ALTER TABLE `tiki_user_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_notes`
--

DROP TABLE IF EXISTS `tiki_user_notes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_notes` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `noteId` int(12) NOT NULL AUTO_INCREMENT,
  `created` int(14) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lastModif` int(14) DEFAULT NULL,
  `data` text,
  `size` int(14) DEFAULT NULL,
  `parse_mode` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`noteId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_notes`
--

LOCK TABLES `tiki_user_notes` WRITE;
/*!40000 ALTER TABLE `tiki_user_notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_postings`
--

DROP TABLE IF EXISTS `tiki_user_postings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_postings` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `posts` int(12) DEFAULT NULL,
  `last` int(14) DEFAULT NULL,
  `first` int(14) DEFAULT NULL,
  `level` int(8) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_postings`
--

LOCK TABLES `tiki_user_postings` WRITE;
/*!40000 ALTER TABLE `tiki_user_postings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_postings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_preferences`
--

DROP TABLE IF EXISTS `tiki_user_preferences`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_preferences` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `prefName` varchar(40) NOT NULL DEFAULT '',
  `value` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`user`,`prefName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_preferences`
--

LOCK TABLES `tiki_user_preferences` WRITE;
/*!40000 ALTER TABLE `tiki_user_preferences` DISABLE KEYS */;
INSERT INTO `tiki_user_preferences` VALUES ('admin','realName','System Administrator');
/*!40000 ALTER TABLE `tiki_user_preferences` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_quizzes`
--

DROP TABLE IF EXISTS `tiki_user_quizzes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_quizzes` (
  `user` varchar(200) DEFAULT '',
  `quizId` int(10) DEFAULT NULL,
  `timestamp` int(14) DEFAULT NULL,
  `timeTaken` int(14) DEFAULT NULL,
  `points` int(12) DEFAULT NULL,
  `maxPoints` int(12) DEFAULT NULL,
  `resultId` int(10) DEFAULT NULL,
  `userResultId` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`userResultId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_quizzes`
--

LOCK TABLES `tiki_user_quizzes` WRITE;
/*!40000 ALTER TABLE `tiki_user_quizzes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_reports`
--

DROP TABLE IF EXISTS `tiki_user_reports`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `interval` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `view` varchar(8) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `type` varchar(5) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time_to_send` datetime NOT NULL,
  `always_email` tinyint(1) NOT NULL,
  `last_report` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_reports`
--

LOCK TABLES `tiki_user_reports` WRITE;
/*!40000 ALTER TABLE `tiki_user_reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_reports_cache`
--

DROP TABLE IF EXISTS `tiki_user_reports_cache`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_reports_cache` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `event` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `data` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_reports_cache`
--

LOCK TABLES `tiki_user_reports_cache` WRITE;
/*!40000 ALTER TABLE `tiki_user_reports_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_reports_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_taken_quizzes`
--

DROP TABLE IF EXISTS `tiki_user_taken_quizzes`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_taken_quizzes` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `quizId` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`user`,`quizId`(50))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_taken_quizzes`
--

LOCK TABLES `tiki_user_taken_quizzes` WRITE;
/*!40000 ALTER TABLE `tiki_user_taken_quizzes` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_taken_quizzes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_tasks`
--

DROP TABLE IF EXISTS `tiki_user_tasks`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_tasks` (
  `taskId` int(14) NOT NULL AUTO_INCREMENT,
  `last_version` int(4) NOT NULL DEFAULT '0',
  `user` varchar(200) NOT NULL DEFAULT '',
  `creator` varchar(200) NOT NULL,
  `public_for_group` varchar(30) DEFAULT NULL,
  `rights_by_creator` char(1) DEFAULT NULL,
  `created` int(14) NOT NULL,
  `status` char(1) DEFAULT NULL,
  `priority` int(2) DEFAULT NULL,
  `completed` int(14) DEFAULT NULL,
  `percentage` int(4) DEFAULT NULL,
  PRIMARY KEY (`taskId`),
  UNIQUE KEY `creator` (`creator`,`created`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_tasks`
--

LOCK TABLES `tiki_user_tasks` WRITE;
/*!40000 ALTER TABLE `tiki_user_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_tasks_history`
--

DROP TABLE IF EXISTS `tiki_user_tasks_history`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_tasks_history` (
  `belongs_to` int(14) NOT NULL,
  `task_version` int(4) NOT NULL DEFAULT '0',
  `title` varchar(250) NOT NULL,
  `description` text,
  `start` int(14) DEFAULT NULL,
  `end` int(14) DEFAULT NULL,
  `lasteditor` varchar(200) NOT NULL,
  `lastchanges` int(14) NOT NULL,
  `priority` int(2) NOT NULL DEFAULT '3',
  `completed` int(14) DEFAULT NULL,
  `deleted` int(14) DEFAULT NULL,
  `status` char(1) DEFAULT NULL,
  `percentage` int(4) DEFAULT NULL,
  `accepted_creator` char(1) DEFAULT NULL,
  `accepted_user` char(1) DEFAULT NULL,
  PRIMARY KEY (`belongs_to`,`task_version`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_tasks_history`
--

LOCK TABLES `tiki_user_tasks_history` WRITE;
/*!40000 ALTER TABLE `tiki_user_tasks_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_tasks_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_votings`
--

DROP TABLE IF EXISTS `tiki_user_votings`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_votings` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `ip` varchar(15) DEFAULT NULL,
  `id` varchar(255) NOT NULL DEFAULT '',
  `optionId` int(10) NOT NULL DEFAULT '0',
  `time` int(14) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user`(100),`id`(100)),
  KEY `ip` (`ip`),
  KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_votings`
--

LOCK TABLES `tiki_user_votings` WRITE;
/*!40000 ALTER TABLE `tiki_user_votings` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_votings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_user_watches`
--

DROP TABLE IF EXISTS `tiki_user_watches`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_user_watches` (
  `watchId` int(12) NOT NULL AUTO_INCREMENT,
  `user` varchar(200) NOT NULL DEFAULT '',
  `event` varchar(40) NOT NULL DEFAULT '',
  `object` varchar(200) NOT NULL DEFAULT '',
  `title` varchar(250) DEFAULT NULL,
  `type` varchar(200) DEFAULT NULL,
  `url` varchar(250) DEFAULT NULL,
  `email` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`user`(50),`event`,`object`(100),`email`(50)),
  KEY `watchId` (`watchId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_user_watches`
--

LOCK TABLES `tiki_user_watches` WRITE;
/*!40000 ALTER TABLE `tiki_user_watches` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_user_watches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_userfiles`
--

DROP TABLE IF EXISTS `tiki_userfiles`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_userfiles` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `fileId` int(12) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `filename` varchar(200) DEFAULT NULL,
  `filetype` varchar(200) DEFAULT NULL,
  `filesize` varchar(200) DEFAULT NULL,
  `data` longblob,
  `hits` int(8) DEFAULT NULL,
  `isFile` char(1) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  PRIMARY KEY (`fileId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_userfiles`
--

LOCK TABLES `tiki_userfiles` WRITE;
/*!40000 ALTER TABLE `tiki_userfiles` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_userfiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_userpoints`
--

DROP TABLE IF EXISTS `tiki_userpoints`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_userpoints` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `points` decimal(8,2) DEFAULT NULL,
  `voted` int(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_userpoints`
--

LOCK TABLES `tiki_userpoints` WRITE;
/*!40000 ALTER TABLE `tiki_userpoints` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_userpoints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_users`
--

DROP TABLE IF EXISTS `tiki_users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_users` (
  `user` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(40) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `lastLogin` int(14) DEFAULT NULL,
  PRIMARY KEY (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_users`
--

LOCK TABLES `tiki_users` WRITE;
/*!40000 ALTER TABLE `tiki_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_users_score`
--

DROP TABLE IF EXISTS `tiki_users_score`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_users_score` (
  `user` char(200) NOT NULL DEFAULT '',
  `event_id` char(200) NOT NULL DEFAULT '',
  `expire` int(14) NOT NULL DEFAULT '0',
  `tstamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user`(110),`event_id`(110)),
  KEY `user` (`user`(110),`event_id`(110),`expire`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_users_score`
--

LOCK TABLES `tiki_users_score` WRITE;
/*!40000 ALTER TABLE `tiki_users_score` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_users_score` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_webmail_contacts`
--

DROP TABLE IF EXISTS `tiki_webmail_contacts`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_webmail_contacts` (
  `contactId` int(12) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(80) DEFAULT NULL,
  `lastName` varchar(80) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `nickname` varchar(200) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`contactId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_webmail_contacts`
--

LOCK TABLES `tiki_webmail_contacts` WRITE;
/*!40000 ALTER TABLE `tiki_webmail_contacts` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_webmail_contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_webmail_contacts_ext`
--

DROP TABLE IF EXISTS `tiki_webmail_contacts_ext`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_webmail_contacts_ext` (
  `contactId` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `hidden` tinyint(1) NOT NULL,
  `fieldId` int(10) unsigned NOT NULL,
  KEY `contactId` (`contactId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_webmail_contacts_ext`
--

LOCK TABLES `tiki_webmail_contacts_ext` WRITE;
/*!40000 ALTER TABLE `tiki_webmail_contacts_ext` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_webmail_contacts_ext` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_webmail_contacts_fields`
--

DROP TABLE IF EXISTS `tiki_webmail_contacts_fields`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_webmail_contacts_fields` (
  `user` varchar(200) NOT NULL,
  `fieldname` varchar(255) NOT NULL,
  `order` int(2) NOT NULL DEFAULT '0',
  `show` char(1) NOT NULL DEFAULT 'n',
  `fieldId` int(10) unsigned NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`fieldId`),
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_webmail_contacts_fields`
--

LOCK TABLES `tiki_webmail_contacts_fields` WRITE;
/*!40000 ALTER TABLE `tiki_webmail_contacts_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_webmail_contacts_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_webmail_contacts_groups`
--

DROP TABLE IF EXISTS `tiki_webmail_contacts_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_webmail_contacts_groups` (
  `contactId` int(12) NOT NULL,
  `groupName` varchar(255) NOT NULL,
  PRIMARY KEY (`contactId`,`groupName`(200))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_webmail_contacts_groups`
--

LOCK TABLES `tiki_webmail_contacts_groups` WRITE;
/*!40000 ALTER TABLE `tiki_webmail_contacts_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_webmail_contacts_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_webmail_messages`
--

DROP TABLE IF EXISTS `tiki_webmail_messages`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_webmail_messages` (
  `accountId` int(12) NOT NULL DEFAULT '0',
  `mailId` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(200) NOT NULL DEFAULT '',
  `isRead` char(1) DEFAULT NULL,
  `isReplied` char(1) DEFAULT NULL,
  `isFlagged` char(1) DEFAULT NULL,
  `flaggedMsg` varchar(50) DEFAULT '',
  PRIMARY KEY (`accountId`,`mailId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_webmail_messages`
--

LOCK TABLES `tiki_webmail_messages` WRITE;
/*!40000 ALTER TABLE `tiki_webmail_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_webmail_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_webservice`
--

DROP TABLE IF EXISTS `tiki_webservice`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_webservice` (
  `service` varchar(25) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `body` text,
  `schema_version` varchar(5) DEFAULT NULL,
  `schema_documentation` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`service`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_webservice`
--

LOCK TABLES `tiki_webservice` WRITE;
/*!40000 ALTER TABLE `tiki_webservice` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_webservice` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_webservice_template`
--

DROP TABLE IF EXISTS `tiki_webservice_template`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_webservice_template` (
  `service` varchar(25) NOT NULL,
  `template` varchar(25) NOT NULL,
  `engine` varchar(15) NOT NULL,
  `output` varchar(15) NOT NULL,
  `content` text NOT NULL,
  `last_modif` int(11) DEFAULT NULL,
  PRIMARY KEY (`service`,`template`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_webservice_template`
--

LOCK TABLES `tiki_webservice_template` WRITE;
/*!40000 ALTER TABLE `tiki_webservice_template` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_webservice_template` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_wiki_attachments`
--

DROP TABLE IF EXISTS `tiki_wiki_attachments`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_wiki_attachments` (
  `attId` int(12) NOT NULL AUTO_INCREMENT,
  `page` varchar(200) NOT NULL DEFAULT '',
  `filename` varchar(80) DEFAULT NULL,
  `filetype` varchar(80) DEFAULT NULL,
  `filesize` int(14) DEFAULT NULL,
  `user` varchar(200) NOT NULL DEFAULT '',
  `data` longblob,
  `path` varchar(255) DEFAULT NULL,
  `hits` int(10) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`attId`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_wiki_attachments`
--

LOCK TABLES `tiki_wiki_attachments` WRITE;
/*!40000 ALTER TABLE `tiki_wiki_attachments` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_wiki_attachments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tiki_zones`
--

DROP TABLE IF EXISTS `tiki_zones`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `tiki_zones` (
  `zone` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`zone`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `tiki_zones`
--

LOCK TABLES `tiki_zones` WRITE;
/*!40000 ALTER TABLE `tiki_zones` DISABLE KEYS */;
/*!40000 ALTER TABLE `tiki_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_grouppermissions`
--

DROP TABLE IF EXISTS `users_grouppermissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users_grouppermissions` (
  `groupName` varchar(255) NOT NULL DEFAULT '',
  `permName` varchar(40) NOT NULL DEFAULT '',
  `value` char(1) DEFAULT '',
  PRIMARY KEY (`groupName`(30),`permName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users_grouppermissions`
--

LOCK TABLES `users_grouppermissions` WRITE;
/*!40000 ALTER TABLE `users_grouppermissions` DISABLE KEYS */;
INSERT INTO `users_grouppermissions` VALUES ('Anonymous','tiki_p_view',''),('Admins','tiki_p_admin','');
/*!40000 ALTER TABLE `users_grouppermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_groups`
--

DROP TABLE IF EXISTS `users_groups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users_groups` (
  `groupName` varchar(255) NOT NULL DEFAULT '',
  `groupDesc` varchar(255) DEFAULT NULL,
  `groupHome` varchar(255) DEFAULT NULL,
  `usersTrackerId` int(11) DEFAULT NULL,
  `groupTrackerId` int(11) DEFAULT NULL,
  `usersFieldId` int(11) DEFAULT NULL,
  `groupFieldId` int(11) DEFAULT NULL,
  `registrationChoice` char(1) DEFAULT NULL,
  `registrationUsersFieldIds` text,
  `userChoice` char(1) DEFAULT NULL,
  `groupDefCat` int(12) DEFAULT '0',
  `groupTheme` varchar(255) DEFAULT '',
  PRIMARY KEY (`groupName`(30))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users_groups`
--

LOCK TABLES `users_groups` WRITE;
/*!40000 ALTER TABLE `users_groups` DISABLE KEYS */;
INSERT INTO `users_groups` VALUES ('Anonymous','Public users not logged',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,''),('Registered','Users logged into the system',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,''),('Admins','Administrator and accounts managers.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,'');
/*!40000 ALTER TABLE `users_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_objectpermissions`
--

DROP TABLE IF EXISTS `users_objectpermissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users_objectpermissions` (
  `groupName` varchar(255) NOT NULL DEFAULT '',
  `permName` varchar(40) NOT NULL DEFAULT '',
  `objectType` varchar(20) NOT NULL DEFAULT '',
  `objectId` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`objectId`,`objectType`,`groupName`(30),`permName`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users_objectpermissions`
--

LOCK TABLES `users_objectpermissions` WRITE;
/*!40000 ALTER TABLE `users_objectpermissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_objectpermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_permissions`
--

DROP TABLE IF EXISTS `users_permissions`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users_permissions` (
  `permName` varchar(40) NOT NULL DEFAULT '',
  `permDesc` varchar(250) DEFAULT NULL,
  `level` varchar(80) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `admin` varchar(1) DEFAULT NULL,
  `feature_check` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`permName`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users_permissions`
--

LOCK TABLES `users_permissions` WRITE;
/*!40000 ALTER TABLE `users_permissions` DISABLE KEYS */;
INSERT INTO `users_permissions` VALUES ('tiki_p_admin_calendar','Can create/admin calendars','admin','calendar','y',NULL),('tiki_p_add_events','Can add events in the calendar','registered','calendar',NULL,NULL),('tiki_p_change_events','Can change events in the calendar','registered','calendar',NULL,NULL),('tiki_p_view_calendar','Can browse the calendar','basic','calendar',NULL,NULL),('tiki_p_view_events','Can view events details','registered','calendar',NULL,NULL),('tiki_p_view_tiki_calendar','Can view Tikiwiki tools calendar','basic','calendar',NULL,NULL),('tiki_p_admin_categories','Can admin categories','editors','category','y',NULL),('tiki_p_edit_categorized','Can edit items in categories','registered','category',NULL,NULL),('tiki_p_view_categories','Can view categories','basic','category',NULL,NULL),('tiki_p_view_categorized','Can view categorized items','basic','category',NULL,NULL),('tiki_p_search_categorized','Can search on objects of this category','basic','category',NULL,NULL),('tiki_p_admin_charts','Can admin charts','admin','charts','y',NULL),('tiki_p_autoval_chart_suggestio','Autovalidate suggestions','editors','charts',NULL,NULL),('tiki_p_suggest_chart_item','Can suggest items','basic','charts',NULL,NULL),('tiki_p_view_chart','Can view charts','basic','charts',NULL,NULL),('tiki_p_vote_chart','Can vote','basic','charts',NULL,NULL),('tiki_p_admin_chat','Administrator, can create channels remove channels etc','editors','chat','y',NULL),('tiki_p_chat','Can use the chat system','registered','chat',NULL,NULL),('tiki_p_admin_cms','Can admin the cms','editors','cms','y',NULL),('tiki_p_approve_submission','Can approve submissions','editors','cms',NULL,NULL),('tiki_p_articles_admin_topics','Can admin article topics','editors','cms',NULL,NULL),('tiki_p_articles_admin_types','Can admin article types','editors','cms',NULL,NULL),('tiki_p_articles_read_heading','Can read article headings','basic','cms',NULL,NULL),('tiki_p_autoapprove_submission','Submited articles automatically approved','editors','cms',NULL,NULL),('tiki_p_edit_article','Can edit articles','editors','cms',NULL,NULL),('tiki_p_edit_submission','Can edit submissions','editors','cms',NULL,NULL),('tiki_p_read_article','Can read articles','basic','cms',NULL,NULL),('tiki_p_remove_article','Can remove articles','editors','cms',NULL,NULL),('tiki_p_remove_submission','Can remove submissions','editors','cms',NULL,NULL),('tiki_p_submit_article','Can submit articles','basic','cms',NULL,NULL),('tiki_p_topic_read','Can read a topic (Applies only to individual topic perms)','basic','cms',NULL,NULL),('tiki_p_admin_contribution','Can admin contributions','admin','contribution','y',NULL),('tiki_p_admin_directory','Can admin the directory','editors','directory','y',NULL),('tiki_p_admin_directory_cats','Can admin directory categories','editors','directory',NULL,NULL),('tiki_p_admin_directory_sites','Can admin directory sites','editors','directory',NULL,NULL),('tiki_p_autosubmit_link','Submited links are valid','editors','directory',NULL,NULL),('tiki_p_submit_link','Can submit sites to the directory','basic','directory',NULL,NULL),('tiki_p_validate_links','Can validate submited links','editors','directory',NULL,NULL),('tiki_p_view_directory','Can use the directory','basic','directory',NULL,NULL),('tiki_p_admin_drawings','Can admin drawings','editors','drawings','y',NULL),('tiki_p_edit_drawings','Can edit drawings','basic','drawings',NULL,NULL),('tiki_p_admin_faqs','Can admin faqs','editors','faqs','y',NULL),('tiki_p_suggest_faq','Can suggest faq questions','basic','faqs',NULL,NULL),('tiki_p_view_faqs','Can view faqs','basic','faqs',NULL,NULL),('tiki_p_admin','Administrator, can manage users groups and permissions, Hotwords and all the weblog features','admin','tiki','y',NULL),('tiki_p_admin_users','Can admin users','admin','tiki',NULL,NULL),('tiki_p_access_closed_site','Can access site when closed','admin','tiki',NULL,NULL),('tiki_p_admin_banners','Administrator, can admin banners','admin','tiki',NULL,NULL),('tiki_p_admin_banning','Can ban users or ips','admin','tiki',NULL,NULL),('tiki_p_admin_dynamic','Can admin the dynamic content system','editors','tiki',NULL,NULL),('tiki_p_admin_integrator','Can admin integrator repositories and rules','admin','tiki',NULL,NULL),('tiki_p_admin_mailin','Can admin mail-in accounts','admin','tiki',NULL,NULL),('tiki_p_admin_objects','Can edit object permissions','admin','tiki',NULL,NULL),('tiki_p_admin_rssmodules','Can admin rss modules','admin','tiki',NULL,NULL),('tiki_p_clean_cache','Can clean cache','editors','tiki',NULL,NULL),('tiki_p_create_css','Can create new css suffixed with -user','registered','tiki',NULL,NULL),('tiki_p_detach_translation','Can remove association between two pages in a translation set','registered','tiki',NULL,NULL),('tiki_p_edit_cookies','Can admin cookies','editors','tiki',NULL,NULL),('tiki_p_edit_languages','Can edit translations and create new languages','editors','tiki',NULL,NULL),('tiki_p_edit_menu','Can edit menu','admin','menus',NULL,NULL),('tiki_p_edit_menu_option','Can edit menu option','admin','menus',NULL,NULL),('tiki_p_edit_templates','Can edit site templates','admin','tiki',NULL,NULL),('tiki_p_search','Can search','basic','tiki',NULL,NULL),('tiki_p_site_report','Can report a link to the webmaster','basic','tiki',NULL,NULL),('tiki_p_subscribe_groups','Can subscribe to groups','registered','tiki',NULL,NULL),('tiki_p_tell_a_friend','Can send a link to a friend','Basic','tiki',NULL,NULL),('tiki_p_use_HTML','Can use HTML in pages','editors','tiki',NULL,NULL),('tiki_p_view_actionlog','Can view action log','registered','tiki',NULL,NULL),('tiki_p_view_actionlog_owngroups','Can view action log for users of his own groups','registered','tiki',NULL,NULL),('tiki_p_view_integrator','Can view integrated repositories','basic','tiki',NULL,NULL),('tiki_p_view_referer_stats','Can view referer stats','editors','tiki',NULL,NULL),('tiki_p_view_stats','Can view site stats','basic','tiki',NULL,NULL),('tiki_p_view_templates','Can view site templates','admin','tiki',NULL,NULL),('tiki_p_blog_admin','Can admin blogs','editors','blogs','y',NULL),('tiki_p_assign_perm_blog','Can assign perms to blog','admin','blogs',NULL,NULL),('tiki_p_blog_post','Can post to a blog','registered','blogs',NULL,NULL),('tiki_p_create_blogs','Can create a blog','editors','blogs',NULL,NULL),('tiki_p_read_blog','Can read blogs','basic','blogs',NULL,NULL),('tiki_p_admin_file_galleries','Can admin file galleries','editors','file galleries','y',NULL),('tiki_p_assign_perm_file_gallery','Can assign perms to file gallery','admin','file galleries',NULL,NULL),('tiki_p_batch_upload_file_dir','Can use Directory Batch Load','editors','file galleries',NULL,NULL),('tiki_p_batch_upload_files','Can upload zip files with files','editors','file galleries',NULL,NULL),('tiki_p_create_file_galleries','Can create file galleries','editors','file galleries',NULL,NULL),('tiki_p_download_files','Can download files','basic','file galleries',NULL,NULL),('tiki_p_edit_gallery_file','Can edit a gallery file','editors','file galleries',NULL,NULL),('tiki_p_list_file_galleries','Can list file galleries','basic','file galleries',NULL,NULL),('tiki_p_upload_files','Can upload files','registered','file galleries',NULL,NULL),('tiki_p_view_fgal_explorer','Can view file galleries explorer','basic','file galleries',NULL,NULL),('tiki_p_view_fgal_path','Can view file galleries path','basic','file galleries',NULL,NULL),('tiki_p_view_file_gallery','Can view file galleries','basic','file galleries',NULL,NULL),('tiki_p_admin_forum','Can admin forums','editors','forums','y',NULL),('tiki_p_forum_attach','Can attach to forum posts','registered','forums',NULL,NULL),('tiki_p_forum_autoapp','Auto approve forum posts','editors','forums',NULL,NULL),('tiki_p_forum_edit_own_posts','Can edit own forum posts','registered','forums',NULL,NULL),('tiki_p_forum_post','Can post in forums','registered','forums',NULL,NULL),('tiki_p_forum_post_topic','Can start threads in forums','registered','forums',NULL,NULL),('tiki_p_forum_read','Can read forums','basic','forums',NULL,NULL),('tiki_p_forums_report','Can report msgs to moderator','registered','forums',NULL,NULL),('tiki_p_forum_vote','Can vote comments in forums','registered','forums',NULL,NULL),('tiki_p_admin_freetags','Can admin freetags','admin','freetags','y',NULL),('tiki_p_admin_galleries','Can admin Image Galleries','editors','image galleries','y',NULL),('tiki_p_assign_perm_image_gallery','Can assign perms to image gallery','admin','image galleries',NULL,NULL),('tiki_p_batch_upload_image_dir','Can use Directory Batch Load','editors','image galleries',NULL,NULL),('tiki_p_batch_upload_images','Can upload zip files with images','editors','image galleries',NULL,NULL),('tiki_p_create_galleries','Can create image galleries','editors','image galleries',NULL,NULL),('tiki_p_freetags_tag','Can tag objects','registered','freetags',NULL,NULL),('tiki_p_list_image_galleries','Can list image galleries','basic','image galleries',NULL,NULL),('tiki_p_unassign_freetags','Can unassign tags from an object','basic','freetags',NULL,NULL),('tiki_p_upload_images','Can upload images','registered','image galleries',NULL,NULL),('tiki_p_view_freetags','Can browse freetags','basic','freetags',NULL,NULL),('tiki_p_view_image_gallery','Can view image galleries','basic','image galleries',NULL,NULL),('tiki_p_admin_games','Can admin games','editors','games','y',NULL),('tiki_p_play_games','Can play games','basic','games',NULL,NULL),('tiki_p_admin_newsletters','Can admin newsletters','admin','newsletters','y',NULL),('tiki_p_batch_subscribe_email','Can subscribe many e-mails at once (requires tiki_p_subscribe email)','editors','newsletters',NULL,NULL),('tiki_p_send_newsletters','Can send newsletters','editors','newsletters',NULL,NULL),('tiki_p_subscribe_email','Can subscribe any email to newsletters','editors','newsletters',NULL,NULL),('tiki_p_subscribe_newsletters','Can subscribe to newsletters','basic','newsletters',NULL,NULL),('tiki_p_admin_polls','Can admin polls','admin','polls','y',NULL),('tiki_p_view_poll_results','Can view poll results','basic','polls',NULL,NULL),('tiki_p_vote_poll','Can vote polls','basic','polls',NULL,NULL),('tiki_p_view_poll_voters','Can view poll voters','basic','polls',NULL,NULL),('tiki_p_admin_quicktags','Can admin quicktags','admin','quicktags','y',NULL),('tiki_p_admin_quizzes','Can admin quizzes','editors','quizzes','y',NULL),('tiki_p_take_quiz','Can take quizzes','basic','quizzes',NULL,NULL),('tiki_p_view_quiz_stats','Can view quiz stats','basic','quizzes',NULL,NULL),('tiki_p_view_user_results','Can view user quiz results','editors','quizzes',NULL,NULL),('tiki_p_admin_sheet','Can admin sheet','admin','sheet','y',NULL),('tiki_p_edit_sheet','Can create and edit sheets','editors','sheet',NULL,NULL),('tiki_p_view_sheet','Can view sheet','basic','sheet',NULL,NULL),('tiki_p_view_sheet_history','Can view sheet history','admin','sheet',NULL,NULL),('tiki_p_admin_shoutbox','Can admin shoutbox (Edit/remove msgs)','editors','shoutbox','y',NULL),('tiki_p_post_shoutbox','Can post messages in shoutbox','basic','shoutbox',NULL,NULL),('tiki_p_view_shoutbox','Can view shoutbox','basic','shoutbox',NULL,NULL),('tiki_p_admin_surveys','Can admin surveys','editors','surveys','y',NULL),('tiki_p_take_survey','Can take surveys','basic','surveys',NULL,NULL),('tiki_p_view_survey_stats','Can view survey stats','basic','surveys',NULL,NULL),('tiki_p_admin_trackers','Can admin trackers','editors','trackers','y',NULL),('tiki_p_attach_trackers','Can attach files to tracker items','registered','trackers',NULL,NULL),('tiki_p_comment_tracker_items','Can insert comments for tracker items','basic','trackers',NULL,NULL),('tiki_p_tracker_view_comments','Can view tracker items comments','basic','trackers',NULL,NULL),('tiki_p_create_tracker_items','Can create new items for trackers','registered','trackers',NULL,NULL),('tiki_p_list_trackers','Can list trackers','basic','trackers',NULL,NULL),('tiki_p_modify_tracker_items','Can change tracker items','registered','trackers',NULL,NULL),('tiki_p_tracker_view_ratings','Can view rating result for tracker items','basic','trackers',NULL,NULL),('tiki_p_tracker_vote_ratings','Can vote a rating for tracker items','registered','trackers',NULL,NULL),('tiki_p_view_trackers','Can view trackers','basic','trackers',NULL,NULL),('tiki_p_view_trackers_closed','Can view trackers closed items','registered','trackers',NULL,NULL),('tiki_p_view_trackers_pending','Can view trackers pending items','editors','trackers',NULL,NULL),('tiki_p_watch_trackers','Can watch tracker','registered','trackers',NULL,NULL),('tiki_p_export_tracker','Can export tracker items','registered','trackers',NULL,NULL),('tiki_p_admin_wiki','Can admin the wiki','editors','wiki','y','feature_wiki'),('tiki_p_assign_perm_wiki_page','Can assign perms to wiki pages','admin','wiki',NULL,'feature_wiki'),('tiki_p_edit','Can edit pages','registered','wiki',NULL,'feature_wiki'),('tiki_p_edit_copyrights','Can edit copyright notices','editors','wiki',NULL,'wiki_feature_copyrights'),('tiki_p_edit_dynvar','Can edit dynamic variables','editors','wiki',NULL,NULL),('tiki_p_edit_structures','Can create and edit structures','editors','wiki',NULL,'feature_wiki_structure'),('tiki_p_export_wiki','Can export wiki pages using the export feature','admin','wiki',NULL,'feature_wiki_export'),('tiki_p_lock','Can lock pages','editors','wiki',NULL,'feature_wiki'),('tiki_p_minor','Can save as minor edit','registered','wiki',NULL,'feature_wiki'),('tiki_p_remove','Can remove','editors','wiki',NULL,'feature_wiki'),('tiki_p_rename','Can rename pages','editors','wiki',NULL,'feature_wiki'),('tiki_p_rollback','Can rollback pages','editors','wiki',NULL,'feature_wiki'),('tiki_p_upload_picture','Can upload pictures to wiki pages','registered','wiki',NULL,'feature_wiki_pictures'),('tiki_p_use_as_template','Can use the page as a tracker template','basic','wiki',NULL,'feature_wiki_templates'),('tiki_p_view','Can view page/pages','basic','wiki',NULL,'feature_wiki'),('tiki_p_watch_structure','Can watch structure','registered','wiki',NULL,'feature_wiki_structure'),('tiki_p_wiki_admin_attachments','Can admin attachments to wiki pages','editors','wiki',NULL,'feature_wiki_attachments'),('tiki_p_wiki_admin_ratings','Can add and change ratings on wiki pages','admin','wiki',NULL,'feature_wiki_ratings'),('tiki_p_wiki_attach_files','Can attach files to wiki pages','registered','wiki',NULL,'feature_wiki_attachments'),('tiki_p_wiki_view_attachments','Can view wiki attachments and download','registered','wiki',NULL,'feature_wiki_attachments'),('tiki_p_wiki_view_comments','Can view wiki comments','basic','wiki',NULL,'feature_wiki_comments'),('tiki_p_wiki_view_history','Can view wiki history','basic','wiki',NULL,'feature_history'),('tiki_p_wiki_view_ratings','Can view rating of wiki pages','basic','wiki',NULL,'feature_wiki_ratings'),('tiki_p_wiki_view_source','Can view source of wiki pages','basic','wiki',NULL,'feature_source'),('tiki_p_wiki_vote_ratings','Can participate to rating of wiki pages','registered','wiki',NULL,'feature_wiki_ratings'),('tiki_p_admin_workflow','Can admin workflow processes','admin','workflow','y',NULL),('tiki_p_abort_instance','Can abort a process instance','editors','workflow',NULL,NULL),('tiki_p_exception_instance','Can declare an instance as exception','registered','workflow',NULL,NULL),('tiki_p_send_instance','Can send instances after completion','registered','workflow',NULL,NULL),('tiki_p_use_workflow','Can execute workflow activities','registered','workflow',NULL,NULL),('tiki_p_admin_received_articles','Can admin received articles','editors','comm',NULL,NULL),('tiki_p_admin_received_pages','Can admin received pages','editors','comm',NULL,NULL),('tiki_p_send_articles','Can send articles to other sites','editors','comm',NULL,NULL),('tiki_p_sendme_articles','Can send articles to this site','registered','comm',NULL,NULL),('tiki_p_sendme_pages','Can send pages to this site','registered','comm',NULL,NULL),('tiki_p_send_pages','Can send pages to other sites','registered','comm',NULL,NULL),('tiki_p_admin_tikitests','Can admin the TikiTests','admin','tikitests',NULL,NULL),('tiki_p_edit_tikitests','Can edit TikiTests','editors','tikitests',NULL,NULL),('tiki_p_play_tikitests','Can replay the TikiTests','registered','tikitests',NULL,NULL),('tiki_p_cache_bookmarks','Can cache user bookmarks','admin','user',NULL,NULL),('tiki_p_configure_modules','Can configure modules','registered','user',NULL,NULL),('tiki_p_create_bookmarks','Can create user bookmarks','registered','user',NULL,NULL),('tiki_p_minical','Can use the mini event calendar','registered','user',NULL,NULL),('tiki_p_newsreader','Can use the newsreader','registered','user',NULL,NULL),('tiki_p_notepad','Can use the notepad','registered','user',NULL,NULL),('tiki_p_tasks_admin','Can admin public tasks','admin','user',NULL,NULL),('tiki_p_tasks','Can use tasks','registered','user',NULL,NULL),('tiki_p_tasks_receive','Can receive tasks from other users','registered','user',NULL,NULL),('tiki_p_tasks_send','Can send tasks to other users','registered','user',NULL,NULL),('tiki_p_userfiles','Can upload personal files','registered','user',NULL,NULL),('tiki_p_usermenu','Can create items in personal menu','registered','user',NULL,NULL),('tiki_p_broadcast_all','Can broadcast messages to all user','admin','messu',NULL,NULL),('tiki_p_broadcast','Can broadcast messages to groups','admin','messu',NULL,NULL),('tiki_p_messages','Can use the messaging system','registered','messu',NULL,NULL),('tiki_p_admin_comments','Can admin comments','admin','comments','y',NULL),('tiki_p_edit_comments','Can edit all comments','editors','comments',NULL,NULL),('tiki_p_post_comments','Can post new comments','registered','comments',NULL,NULL),('tiki_p_read_comments','Can read comments','basic','comments',NULL,NULL),('tiki_p_remove_comments','Can delete comments','editors','comments',NULL,NULL),('tiki_p_vote_comments','Can vote comments','registered','comments',NULL,NULL),('tiki_p_admin_content_templates','Can admin content templates','admin','content templates','y',NULL),('tiki_p_edit_content_templates','Can edit content templates','editors','content templates',NULL,NULL),('tiki_p_use_content_templates','Can use content templates','registered','content templates',NULL,NULL),('tiki_p_edit_html_pages','Can edit HTML pages','editors','html pages',NULL,NULL),('tiki_p_view_html_pages','Can view HTML pages','basic','html pages',NULL,NULL),('tiki_p_list_users','Can list registered users','registered','community',NULL,NULL),('tiki_p_live_support_admin','Admin live support system','admin','support','y',NULL),('tiki_p_live_support','Can use live support system','basic','support',NULL,NULL),('tiki_p_map_create','Can create new mapfile','admin','maps',NULL,NULL),('tiki_p_map_delete','Can delete mapfiles','admin','maps',NULL,NULL),('tiki_p_map_edit','Can edit mapfiles','editors','maps',NULL,NULL),('tiki_p_map_view','Can view mapfiles','basic','maps',NULL,NULL),('tiki_p_map_view_mapfiles','Can view contents of mapfiles','registered','maps',NULL,NULL),('tiki_p_use_webmail','Can use webmail','registered','webmail',NULL,NULL),('tiki_p_use_group_webmail','Can use group webmail','registered','webmail',NULL,NULL),('tiki_p_admin_group_webmail','Can administrate group webmail accounts','registered','webmail',NULL,NULL),('tiki_p_use_personal_webmail','Can use personal webmail accounts','registered','webmail',NULL,NULL),('tiki_p_admin_personal_webmail','Can administrate personal webmail accounts','registered','webmail',NULL,NULL),('tiki_p_plugin_viewdetail','Can view unapproved plugin details','registered','wiki',NULL,NULL),('tiki_p_plugin_preview','Can execute unapproved plugin','registered','wiki',NULL,NULL),('tiki_p_plugin_approve','Can approve plugin execution','editors','wiki',NULL,NULL),('tiki_p_trust_input','Trust all user inputs (no security checks)','admin','tiki',NULL,NULL),('tiki_p_view_backlink','View page backlinks','basic','wiki',NULL,NULL),('tiki_p_admin_notifications','Can admin mail notifications','editors','mail notifications',NULL,NULL),('tiki_p_invite','Can invite user in groups','editors','tiki',NULL,NULL);
/*!40000 ALTER TABLE `users_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_usergroups`
--

DROP TABLE IF EXISTS `users_usergroups`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users_usergroups` (
  `userId` int(8) NOT NULL DEFAULT '0',
  `groupName` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`userId`,`groupName`(30))
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users_usergroups`
--

LOCK TABLES `users_usergroups` WRITE;
/*!40000 ALTER TABLE `users_usergroups` DISABLE KEYS */;
INSERT INTO `users_usergroups` VALUES (1,'Admins');
/*!40000 ALTER TABLE `users_usergroups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_users`
--

DROP TABLE IF EXISTS `users_users`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `users_users` (
  `userId` int(8) NOT NULL AUTO_INCREMENT,
  `email` varchar(200) DEFAULT NULL,
  `login` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(30) DEFAULT '',
  `provpass` varchar(30) DEFAULT NULL,
  `default_group` varchar(255) DEFAULT NULL,
  `lastLogin` int(14) DEFAULT NULL,
  `currentLogin` int(14) DEFAULT NULL,
  `registrationDate` int(14) DEFAULT NULL,
  `challenge` varchar(32) DEFAULT NULL,
  `pass_confirm` int(14) DEFAULT NULL,
  `email_confirm` int(14) DEFAULT NULL,
  `hash` varchar(34) DEFAULT NULL,
  `created` int(14) DEFAULT NULL,
  `avatarName` varchar(80) DEFAULT NULL,
  `avatarSize` int(14) DEFAULT NULL,
  `avatarFileType` varchar(250) DEFAULT NULL,
  `avatarData` longblob,
  `avatarLibName` varchar(200) DEFAULT NULL,
  `avatarType` char(1) DEFAULT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  `valid` varchar(32) DEFAULT NULL,
  `unsuccessful_logins` int(14) DEFAULT '0',
  `openid_url` varchar(255) DEFAULT NULL,
  `waiting` char(1) DEFAULT NULL,
  PRIMARY KEY (`userId`),
  KEY `score` (`score`),
  KEY `login` (`login`),
  KEY `registrationDate` (`registrationDate`),
  KEY `openid_url` (`openid_url`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `users_users`
--

LOCK TABLES `users_users` WRITE;
/*!40000 ALTER TABLE `users_users` DISABLE KEYS */;
INSERT INTO `users_users` VALUES (1,'','admin','','',NULL,1245980692,1245980719,NULL,NULL,1245980719,NULL,'$1$mNWHo7gs$cLpqW1.AbTRllSSsVCtMu.',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,0,NULL,NULL);
/*!40000 ALTER TABLE `users_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-06-26  2:35:09
