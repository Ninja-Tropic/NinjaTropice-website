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
-- WHERE:  ID=436

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (436,1,'2026-02-09 17:14:07','2026-02-09 17:14:07','<!-- wp:acf/page-heading {\"name\":\"acf/page-heading\",\"data\":{\"title\":\"\\u003ch1\\u003eMarketing and Communication Training\\u003c/h1\\u003e\",\"_title\":\"field_699a0f54e75a3\",\"description\":\"Marketing and communication can be considered the backbone of the bottom line. Without adequately trained employees, customers are not offered the omni experience they demand in this ever-changing e-commerce world.\\r\\n\\r\\nContinuous training is crucial to keeping up with the industry and essential for creating an effective marketing and communication strategy for every business but the age-old instruction methods no longer cut it. Give your marketing employees the directive they need to help your organization skyrocket success with engaging learning solutions that produce revenue-boosting results.\",\"_description\":\"field_699a0f65e75a4\",\"image\":1402,\"_image\":\"field_699a0f73e75a5\",\"link_1\":{\"title\":\"Check out our work!\",\"url\":\"#\",\"target\":\"\"},\"_link_1\":\"field_699a0f7ee75a6\",\"link_2\":{\"title\":\"Get a Quote!\",\"url\":\"/elearning-video-animation-companies-free-consultation/\",\"target\":\"\"},\"_link_2\":\"field_699a0f96e75a7\",\"right_to_left\":\"0\",\"_right_to_left\":\"field_699a0f9de75a8\",\"design\":\"simple\",\"_design\":\"field_699a0fefe75a9\"},\"mode\":\"preview\"} /-->\n\n<!-- wp:columns {\"verticalAlignment\":null,\"className\":\"container\"} -->\n<div class=\"wp-block-columns container\"><!-- wp:column {\"verticalAlignment\":\"center\",\"className\":\"container\"} -->\n<div class=\"wp-block-column is-vertically-aligned-center container\"><!-- wp:image {\"id\":1404,\"aspectRatio\":\"4/3\",\"scale\":\"cover\",\"sizeSlug\":\"large\",\"linkDestination\":\"none\",\"align\":\"center\"} -->\n<figure class=\"wp-block-image aligncenter size-large\"><img src=\"http://localhost:8080/wp-content/uploads/2026/03/ILU_COMMUNICATIONFOSTERS_Mesa-de-trabajo-1-1024x610.webp\" alt=\"\" class=\"wp-image-1404\" style=\"aspect-ratio:4/3;object-fit:cover\"/></figure>\n<!-- /wp:image --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Communication Fosters Clarity in Every Organization</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>As we jump (with both feet) into a digitally-centered world without looking back, clear and impactful marketing and communication strategies play a crucial role in the success of businesses. It’s not enough to create a revolutionary product or service and expect customers to find, and flock to it with unquestionable force – it must be marketed and communicated to customers in ever-evolving ways. With one finger on the pulse of customer preferences, marketing and communication teams require continual training to remain competitive in the age of e-commerce – with absolute clarity.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Evolution of industry trends demands evolution of training methodology. Through a visual, engaging approach to learning solutions, microlearning provides the perfect storm of effectiveness and productivity where marketing and communication team members benefit from fast, focused training that is jam-packed with content that sets them up for success. Less unproductive time with multiplied impact creates the perfect combo for marketing and communication employee development.</p>\n<!-- /wp:paragraph --></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns -->\n\n<!-- wp:spacer {\"height\":\"6rem\"} -->\n<div style=\"height:6rem\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:acf/section-2-rows {\"name\":\"acf/section-2-rows\",\"data\":{\"right_to_left\":\"1\",\"_right_to_left\":\"field_699a2bc7383f5\",\"design\":\"mesh\",\"_design\":\"field_699a2be0383f6\",\"title\":\"\\u003ch2 class=\\u0022mt-4\\u0022\\u003eWe Help You Build a Stronger Brand\\u003c/h2\\u003e\",\"_title\":\"field_69765f2c461c3\",\"description\":\"We pair innovation with effectiveness and cater to every diverse learning style in every diverse employee. Our top-rated, innovative video production team gets to work transforming your custom content into an engagement-driven learning solution.\",\"_description\":\"field_69765f79461c4\",\"image\":935,\"_image\":\"field_699a2d9482a32\"},\"mode\":\"preview\"} /-->\n\n<!-- wp:spacer {\"height\":\"6rem\"} -->\n<div style=\"height:6rem\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>\n<!-- /wp:spacer -->\n\n<!-- wp:acf/grid-items {\"name\":\"acf/grid-items\",\"data\":{\"title\":\"Our Work\",\"_title\":\"field_6977a3f378dce\",\"description\":\"Animation, live action, graphic storytelling, and captivating narratives combine to revolutionize the eLearning corporate training industry – it’s just what we do. Check out some of our proud customers!\\r\\n\\r\\n\",\"_description\":\"field_6977a40278dcf\",\"items_0_tag\":\"\",\"_items_0_tag\":\"field_69a6591724e81\",\"items_0_title\":\"SocialRep\",\"_items_0_title\":\"field_6977a41d78dd1\",\"items_0_image\":1409,\"_items_0_image\":\"field_6977a42478dd2\",\"items_0_link\":\"\",\"_items_0_link\":\"field_6977a42d78dd3\",\"items_1_tag\":\"\",\"_items_1_tag\":\"field_69a6591724e81\",\"items_1_title\":\"InMobi\",\"_items_1_title\":\"field_6977a41d78dd1\",\"items_1_image\":1410,\"_items_1_image\":\"field_6977a42478dd2\",\"items_1_link\":\"\",\"_items_1_link\":\"field_6977a42d78dd3\",\"items_2_tag\":\"\",\"_items_2_tag\":\"field_69a6591724e81\",\"items_2_title\":\"Future (Arrow)\",\"_items_2_title\":\"field_6977a41d78dd1\",\"items_2_image\":1411,\"_items_2_image\":\"field_6977a42478dd2\",\"items_2_link\":\"\",\"_items_2_link\":\"field_6977a42d78dd3\",\"items\":3,\"_items\":\"field_6977a40c78dd0\"},\"mode\":\"preview\"} /-->\n\n<!-- wp:acf/related-solutions {\"name\":\"acf/related-solutions\",\"data\":{\"title\":\"\\u003ch3\\u003eRelated \\u003cem\\u003eSolutions\\u003c/em\\u003e\\u003c/h3\\u003e\",\"_title\":\"field_69b76e87c539d\",\"solution_0_title\":\"\\u003ch4\\u003eProduct Training\\u003c/h4\\u003e\",\"_solution_0_title\":\"field_69b76ebbc539f\",\"solution_0_description\":\"Marketing employees need thorough product training to create effective content and boost sales. Video-based training equips them to learn quickly and promote your amazing products efficiently.\",\"_solution_0_description\":\"field_69b76ecac53a0\",\"solution_0_image\":936,\"_solution_0_image\":\"field_69b76ed5c53a1\",\"solution_0_link\":{\"title\":\"Learn more\",\"url\":\"#\",\"target\":\"\"},\"_solution_0_link\":\"field_69b76edfc53a2\",\"solution_1_title\":\"\\u003ch4\\u003eSales Enablement Training\\u003c/h4\\u003e\",\"_solution_1_title\":\"field_69b76ebbc539f\",\"solution_1_description\":\"On-the-job training can only take employees so far. With engaging sales enablement training, your team can sell effectively. Invest in microlearning for concise, entertaining, and impactful training!\",\"_solution_1_description\":\"field_69b76ecac53a0\",\"solution_1_image\":1416,\"_solution_1_image\":\"field_69b76ed5c53a1\",\"solution_1_link\":{\"title\":\"Learn more\",\"url\":\"#\",\"target\":\"\"},\"_solution_1_link\":\"field_69b76edfc53a2\",\"solution_2_title\":\"\\u003ch4\\u003eBrand Training\\u003c/h4\\u003e\",\"_solution_2_title\":\"field_69b76ebbc539f\",\"solution_2_description\":\"To achieve corporate objectives, employees must understand the mission, vision, and values. Ditch old talking points for engaging eLearning that promotes social responsibility and inclusion.\",\"_solution_2_description\":\"field_69b76ecac53a0\",\"solution_2_image\":935,\"_solution_2_image\":\"field_69b76ed5c53a1\",\"solution_2_link\":{\"title\":\"Learn more\",\"url\":\"#\",\"target\":\"\"},\"_solution_2_link\":\"field_69b76edfc53a2\",\"solution\":3,\"_solution\":\"field_69b76ea9c539e\"},\"mode\":\"preview\"} /-->\n\n<!-- wp:acf/hubspot-form {\"name\":\"acf/hubspot-form\",\"data\":{\"title\":\"Get a [gradient]FREE Consultation[/gradient] With Ninja Tropic!\",\"_title\":\"field_697ad122e258a\",\"description\":\"Are you aware of the sheer power of engaging eLearning solutions but not sure where to start? Schedule a \\u003cstrong\\u003efree 30-minute consultation\\u003c/strong\\u003e with a Ninja Tropic eLearning expert to analyze your organization’s content objectives and map out the interactive blueprint to achieve them.\",\"_description\":\"field_697ad133e258b\",\"form\":\"\\u003cscript charset=\\u0022utf-8\\u0022 type=\\u0022text/javascript\\u0022 src=\\u0022//js.hsforms.net/forms/embed/v2.js\\u0022\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e﻿\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e﻿\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e﻿\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e﻿\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e﻿\\u003c/span\\u003e\\u003cspan data-mce-type=\\u0022bookmark\\u0022 style=\\u0022display: inline-block; width: 0px; overflow: hidden; line-height: 0;\\u0022 class=\\u0022mce_SELRES_start\\u0022\\u003e﻿\\u003c/span\\u003e\\u003c/script\\u003e\\r\\n\\u003cscript\\u003e\\r\\n  hbspt.forms.create({\\r\\n    region: \\u0022na1\\u0022,\\r\\n    portalId: \\u00227816367\\u0022,\\r\\n    formId: \\u0022394c2aa7-ab2d-43b4-8cf2-d230dd4662bf\\u0022\\r\\n  });\\r\\n\\u003c/script\\u003e\",\"_form\":\"field_697ad143e258c\"},\"mode\":\"preview\"} /-->','Marketing Communication Training','','publish','closed','closed','','marketing-communication-training','','','2026-06-11 19:47:54','2026-06-11 19:47:54','',392,'http://localhost:8080/?page_id=436',0,'page','',0);
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

-- Dump completed on 2026-06-29  8:15:39
