-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: wordpress
-- ------------------------------------------------------
-- Server version	8.0.45

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
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_posts` (
  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int NOT NULL DEFAULT '0',
  `post_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`),
  KEY `type_status_author` (`post_type`,`post_status`,`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=2931 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--
-- WHERE:  ID=477

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (477,1,'2026-02-10 03:41:49','2026-02-10 03:41:49','<!-- wp:acf/page-heading {\"name\":\"acf/page-heading\",\"data\":{\"title\":\"\\u003ch1\\u003eUniversity-Level Education With eLearning Benefits\\r\\n\\u003c/h1\\u003e\",\"_title\":\"field_699a0f54e75a3\",\"description\":\"It can be a bit of a shock when new college students transition from interactive K12 learning methods to large-scale college lectures but integrating video-based solutions for your University students grabs their attention and aids in educational content retention.\\r\\n\\r\\nEngaging animation, captivating voiceover, and stunning graphics combine to complement your professor’s knowledge and expertise to provide a well-rounded, high-quality education. Use it to welcome new students to campus with video-based orientation or just introduce your University President’s message to the student body but eLearning is the pathway to student preference.\",\"_description\":\"field_699a0f65e75a4\",\"image\":1639,\"_image\":\"field_699a0f73e75a5\",\"link_1\":{\"title\":\"Contact us\",\"url\":\"#\",\"target\":\"\"},\"_link_1\":\"field_699a0f7ee75a6\",\"link_2\":\"\",\"_link_2\":\"field_699a0f96e75a7\",\"right_to_left\":\"0\",\"_right_to_left\":\"field_699a0f9de75a8\",\"design\":\"simple\",\"_design\":\"field_699a0fefe75a9\"},\"mode\":\"preview\"} /-->\n\n<!-- wp:columns {\"className\":\"container\"} -->\n<div class=\"wp-block-columns container\"><!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:group {\"className\":\"ninja-box px-6 py-6\",\"layout\":{\"type\":\"flex\",\"orientation\":\"vertical\",\"justifyContent\":\"stretch\"}} -->\n<a href=\"/case-studies/indiana-university-interactive-microlearning-game/\" class=\"ninja-box-link\"><div class=\"wp-block-group ninja-box px-6 py-6\"><!-- wp:image {\"id\":2465,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"http://localhost:8080/wp-content/uploads/2026/06/INDIANA-UNIVERSITY-2.webp\" alt=\"INDIANA UNIVERSITY 2\" class=\"wp-image-2465\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"fontSize\":\"medium\"} -->\n<h2 class=\"wp-block-heading has-text-align-center has-medium-font-size\">Indiana University</h2>\n<!-- /wp:heading --></div>\n<!-- /wp:group --></a></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:group {\"className\":\"ninja-box px-6 py-6\",\"layout\":{\"type\":\"flex\",\"orientation\":\"vertical\",\"justifyContent\":\"stretch\"}} -->\n<a href=\"/case-studies/johns-hopkins-immersive-medical-training/\" class=\"ninja-box-link\"><div class=\"wp-block-group ninja-box px-6 py-6\"><!-- wp:image {\"id\":1642,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"http://localhost:8080/wp-content/uploads/2026/03/porta-johns-hopkins-v2-1.webp\" alt=\"PORTA Johns Hopkins v2 1\" class=\"wp-image-1642\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"fontSize\":\"medium\"} -->\n<h2 class=\"wp-block-heading has-text-align-center has-medium-font-size\">Johns Hopkins University</h2>\n<!-- /wp:heading --></div>\n<!-- /wp:group --></a></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:group {\"className\":\"ninja-box px-6 py-6\",\"layout\":{\"type\":\"flex\",\"orientation\":\"vertical\",\"justifyContent\":\"stretch\"}} -->\n<a href=\"/case-studies/university-of-delaware-disaster-mitigation/\" class=\"ninja-box-link\"><div class=\"wp-block-group ninja-box px-6 py-6\"><!-- wp:image {\"id\":1643,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-large\"><img src=\"http://localhost:8080/wp-content/uploads/2026/03/U_DELAWARE-1024x576.webp\" alt=\"U DELAWARE\" class=\"wp-image-1643\"/></figure>\n<!-- /wp:image -->\n\n<!-- wp:heading {\"textAlign\":\"center\",\"fontSize\":\"medium\"} -->\n<h2 class=\"wp-block-heading has-text-align-center has-medium-font-size\">University of Delaware</h2>\n<!-- /wp:heading --></div>\n<!-- /wp:group --></a></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:spacer {\"height\":\"6rem\"} -->\n<div style=\"height:6rem\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:group {\"align\":\"full\",\"className\":\"container\",\"layout\":{\"type\":\"flex\",\"orientation\":\"vertical\",\"justifyContent\":\"stretch\"}} -->\n<div class=\"wp-block-group alignfull container\"><!-- wp:heading {\"textAlign\":\"center\"} -->\n<h2 class=\"wp-block-heading has-text-align-center\">Inject Creativity into your Cause-<em>Related Education</em></h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer {\"height\":\"0px\",\"style\":{\"layout\":{\"flexSize\":\"4rem\",\"selfStretch\":\"fixed\"}}} -->\n<div style=\"height:0px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:columns {\"verticalAlignment\":\"center\"} -->\n<div class=\"wp-block-columns are-vertically-aligned-center\"><!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"25%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:25%\"><!-- wp:image {\"id\":1648,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"http://localhost:8080/wp-content/uploads/2026/03/Rectangle-1-2.webp\" alt=\"\" class=\"wp-image-1648\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"25%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:25%\"><!-- wp:image {\"id\":1649,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"http://localhost:8080/wp-content/uploads/2026/03/rectangle-350.webp\" alt=\"\" class=\"wp-image-1649\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"25%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:25%\"><!-- wp:image {\"id\":1651,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"http://localhost:8080/wp-content/uploads/2026/03/Rectangle-2.webp\" alt=\"\" class=\"wp-image-1651\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column {\"verticalAlignment\":\"center\",\"width\":\"25%\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center\" style=\"flex-basis:25%\"><!-- wp:image {\"id\":1650,\"sizeSlug\":\"full\",\"linkDestination\":\"none\"} -->\n<figure class=\"wp-block-image size-full\"><img src=\"http://localhost:8080/wp-content/uploads/2026/03/Rectangle-3-2.webp\" alt=\"\" class=\"wp-image-1650\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div>\n<!-- /wp:group -->\n\n<!-- wp:spacer {\"height\":\"6rem\"} -->\n<div style=\"height:6rem\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:acf/hubspot-form {\"name\":\"acf/hubspot-form\",\"data\":{\"title\":\"Get a FREE Consultation With Ninja Tropic!\",\"_title\":\"field_697ad122e258a\",\"description\":\"A high-quality University education doesn’t mean boring lectures and bullet pointed slide decks. Attract University students to your campus through the power of creativity and engaging learning solutions that set your Higher Education Institution apart from the rest. Contact the team at Ninja Tropic for a Free consultation and let’s discuss what you need to reach your educational objectives.\",\"_description\":\"field_697ad133e258b\",\"form\":\"\\u003cscript charset=\\u0022utf-8\\u0022 type=\\u0022text/javascript\\u0022 src=\\u0022//js.hsforms.net/forms/embed/v2.js\\u0022\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e?\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e?\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e?\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e?\\u003c/span\\u003e\\u003c/script\\u003e\\r\\n\\u003cscript\\u003e\\r\\n  hbspt.forms.create({\\r\\n    region: \\u0022na1\\u0022,\\r\\n    portalId: \\u00227816367\\u0022,\\r\\n    formId: \\u0022394c2aa7-ab2d-43b4-8cf2-d230dd4662bf\\u0022\\r\\n  });\\r\\n\\u003c/script\\u003e\",\"_form\":\"field_697ad143e258c\"},\"mode\":\"preview\"} /-->\n','Universities','','publish','closed','closed','','universities','','','2026-06-22 21:34:01','2026-06-22 21:34:01','',0,'http://localhost:8080/?page_id=477',0,'page','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-29  8:19:16
