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
-- WHERE:  ID=2602

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (2602,7,'2022-12-01 08:57:12','2022-12-01 15:57:12','<!-- wp:heading {\"level\":1} -->\n<h1>10 Essential Roles For Your Online Training Development Team (Using Video Production)</h1>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Are you trying to improve your online training development for your video curriculum?</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Every online training development team has at least 10 major roles – maybe not 10 people. As an eLearning director or operations manager, you have to decide how to make online training videos and assemble your A-team to make that happen.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Who takes on the double responsibility and who you hire or subcontract. With a storyboard, this experience is much easier to prevent.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>So, let’s get into it.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title1\">Getting started with eLearning:<br />your team members</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title2\">The Botom Line</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":2} -->\n<h2 class=\"wp-block-heading\">Getting Started With eLearning: Your Online Training Development Team Members</h2>\n<!-- /wp:heading -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>1. Project Manager/Team Leader (Internal)</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Each eLearning video course is going to consist of, at minimum, a million and one different moving parts. Someone needs to be responsible for organizing, securing, and managing all your team resources – from script to post- production and LMS loading.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Entrust this leadership position to someone already on your (current or soon-to-be) online training development team with the ability to layout a detailed schedule, assign each task and deadline to the appropriate team member, and then make sure each person has the tools they need to get their job done.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>2. Camera Operator (Double Duty or Freelance)</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Someone has to work the camera to create and shoot the perfect video training. Knowing how to get a clean, focused shot is pretty simple with a stand and a little familiarity with the process. You can spend less than $1,000 on lights, a camera, and sound equipment for professional videos. Your instructional designer can take on this role, but if you have the budget, subcontracting this to a local freelancer is quite simple.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>3. On-Screen Talent</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Were you going to point the camera at an empty wall? An actor or another professional with screen experience is going to be responsible for engaging your audience and imparting information clearly. Out of all the eLearning or online development training roles, this is the one where personality really counts.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>They need to be confident, easy to understand, and above all else, engaging. Ideally, this is a subject matter expert or an authority, but this may not be possible if they are camera shy.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>4. Animator & Post-Production Video Editor</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>They say the real magic happens in the editing room. Unless you’re somehow able to pull off a Goodfella’s-esque, perfectly timed long take, you’ll need an editor with a keen eye and knowledge of editing software. They’ll be able to sew together all your takes into one smooth, finished product.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Post-production is also responsible for adding all the “little things” that give your <a href=\"https://elearningindustry.com/elearning-video-production-what-need-know\" target=\"_blank\" rel=\"noopener\">video training production</a> value: animated transitions and title cards, proper sound mixing, etc. Leave out the post production and all your camera crew’s hard work will be wasted.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>How to choose the ideal animation style for your eLearning Video Course? Read more here: <a href=\"https://www.ninjatropic.com/blog-the-5-e-learning-video-animation-styles/\">>>The 5 eLearning animation styles</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>5. Instructional Designer</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>This is the magician who’s going to craft your eLearning course. Instructional designers have an in-depth knowledge of how to structure classes and coursework, and they’ll know how to design course materials.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Your instructional designer is essentially the puppet master behind your on-screen talent and is essential to your online training development program.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>6. Subject Matter Expert (SME)</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>This person works very closely with your instructional designer, but they’re not the same role. While the instructional designer has the design chops to put everything together into a sensible course structure, the subject matter expert is the one with the knowledge.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>You’ll likely have a different subject matter expert for each course, whereas the instructional designer will be able to work on multiple projects.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Don’t make the mistake of thinking each subject matter expert can also be their own course designer. Having all the information and knowing how to present it are two wildly different skill sets.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Learn how to take the best of your SMES! <a href=\"https://www.ninjatropic.com/blog-create-a-better-course-by-interviewing-a-subject-matter-expert-sme/\">>>Create a Better Course by Interviewing a SME</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>7. Course Authoring Specialist</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>This position is the cherry on top of your eLearning cake. Once your team has learned how to make online training videos and has them completed, your course is designed and all the other pieces are in play. Now the course authoring specialist puts it all together.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Elearning courses live or die based on their presentation. The course authoring specialist is here to make sure students progress smoothly from each lesson to the next.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>A poorly authored course will leave students confused as they struggle to piece together the core concepts of your lessons.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>8. Learning Management System (LMS) Specialist</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>This is a very technical eLearning role. Once the course has been perfectly authored, the LMS specialist will need to make sure all the settings are in place on your actual website. Having video training out of order or improper progression settings can be disastrous.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Learn more about the best Interactive video LMS Platforms <a href=\"https://www.ninjatropic.com/blog-interactive-video-platforms-are-the-future-of-online-learning/\">>>Interactive Video Platforms are the Future of Online Learning</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>9. Graphic Designer</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>The graphic designer brings your brand together for your company image and <a href=\"https://rockcontent.com/blog/brand-persona/\" target=\"_blank\" rel=\"noopener\">brand persona</a>. Your eLearning website should be full of unique company assets, and you’ll want your online video development training to match. You wouldn’t dream of sending coursework out into the world without a branded logo, would you?</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":3} -->\n<h3>10. QA Tester (Quality Assurance)</h3>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Quality assurance testers don’t just glance around for typos, they go out of their way to try and break your eLearning video training. It’s their job to test out every possible user scenario to see if something crashes.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>What if a user pauses the video and tries to load the interactive quiz at the same time? Can you start a lesson on a desktop and complete it on a mobile phone without losing progress?</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>QA testers make sure all your hard work doesn’t undermine itself with accidental errors or glitches.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":2} -->\n<h2 class=\"wp-block-heading\">The Bottom Line</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Start your online video development training project or your eLearning video course off right by beginning with a solid team. Trying frantically to add positions later will only slow development and ultimately hinder your entire project. So dust off your old Indeed account, you’ve got some hiring campaigns to open.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If you need guidance on your eLearning video training project , <a href=\"https://www.ninjatropic.com/contact-us/\">Get in touch with Ninja Tropic</a>. We’re more than happy to advise your team on the right content and formatting best practices for effective learning.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Read More:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":2} -->\n<h2 class=\"wp-block-heading\">Subscribe Now!</h2>\n<!-- /wp:heading -->','10 Essential Roles For Your Online Video Training  Development Team','Looking to improve your online training development? Start with understanding how to make your online training videos engaging and impactful.','publish','closed','closed','','blog-10-essential-roles-for-your-online-video-training-development-team','','','2026-06-25 20:26:09','2026-06-25 20:26:09','',0,'http://localhost:8080/blog-10-essential-roles-for-your-online-video-training-development-team/',0,'post','',0);
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

-- Dump completed on 2026-06-30  4:50:02
