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
-- WHERE:  ID=2624

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (2624,7,'2022-11-16 12:44:48','2022-11-16 19:44:48','<!-- wp:paragraph -->\n<p>How does your company conduct orientation, train<br />employees, and prepare staff for new projects?</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title2\">What is Video-Based Learning?</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title3\">Why Video-Based Learning?</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title4\">Are Training Videos Effective?</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title5\">Styles of Video-Based Learning</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title6\">Instructional Design Best Practices for eLearning & Training Video Script Writing</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p><a href=\"#title7\">The Bottom Line</a></p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>If your employees must undergo company-led annual training, what method(s) do you use? With today’s technologies, video-based learning is both an effective and economical way to educate and inform personnel.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Whether it’s with interactive training to teach a new concept, an animated screencast to explain how to use internal software, or a how-to video to teach the company’s mission and history, elearning continues to grow in popularity. Find out why video-based learning is so vital, and learn instructional design best practices when crafting your elearning videos or courses.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Video-based learning uses different formats of video (such as animation or live-action video) to share knowledge or teach skills. Most of us use video-based learning often (consider, for example, the effectiveness of YouTube tutorials!). In recent years, corporations have caught on to the ease of training video script writing and the effectiveness of video-based learning and have adopted it for orientations, training, and more.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Considering how difficult (and costly!) it can be to schedule in-person training seminars, it is no wonder that e-learning is now the number one area of spending for learning and development (“The Rise of E-learning,” Chief Learning Officer, 2020).</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Why is it so popular? Video-based learning is an effective and economical way for corporations to relay their messages to their audience. In addition, eLearning allows corporations to control their message and branding in a way that they cannot with instructor-led orientations or training.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Online training videos are not only easy to distribute, but they provide higher retention levels than the traditional classroom: learners recall merely 10% of textual content, yet recall 65% of visual content and 95% of audio-visual content (Crockett, 2010). According to a Forrester Research report, video-based learning is also the preferred method of instruction: employees are 75% more likely to watch a video than to read a document, email, or article (2019).</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>There are many styles and forms of video-based learning. Visual styles can include voice-overs with stock footage, animated videos, screencast videos, and whiteboard videos. (For full descriptions of these styles of elearning, check out our article The 5 eLearning Video Animation Styles.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Video-based learning can take on many different forms, including the following:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":2} -->\n<h2 class=\"wp-block-heading\">Instructional Design Best Practices for eLearning & Training Video Script Writing</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Pre-production work is key: make sure you know what the client wants, what the message is, and who the audience is (or, if you are the client, make sure you provide this information). Be sure to establish good communication between all parties (SMEs, narrators, writers, animators) early on: this will help everyone stick to deadlines and deliver the best training video script writing to produce the best video.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Begin with the objective. What is the purpose of this video? What are we trying to achieve, and who is our audience? From there, decide what type of video will best meet these needs. Not sure which style would be best? Check out NinjaTropic’s eLearning Video Portfolio for some inspiration.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Always have a clear and concise outline. When creating an outline, make sure to consider the flow from topic to topic: transitions are key! Use transition sentences, and link to previous ideas for better continuity. Finally, be sure to break information into easy-to-digest pieces: remember that your users are watching this, not reading. Consider this and pace your video accordingly.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Make sure that, above all, your video’s content is informative. Use imagery and metaphors when appropriate to help with retention, and be sure to answer questions and provide information. By doing so, you are providing an incentive for watching the video, and you’re making sure users leave with the information they came to the video to learn.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>This is your video: you get to choose the style and the tone that best reflects your company and the video’s purpose! Decide on a tone that will best connect with your audience (conversational, folksy, sophisticated, etc.), and then determine the role of the narrator. Is this person a colleague or peer? An expert? A teacher? Or maybe an omniscient narrator? The style (first person, second person, or third person) and the tone will vary based on this decision. The language will change as well. For example, a subject matter expert might use more formal language, while a colleague or peer might speak more informally and use contractions.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Remember that an elearning video is a fantastic opportunity to brand your company. Use your organization’s logos, slogans, and mottos throughout the video to keep your branding consistent. If you need some inspiration or tips, check out our article about Microlearning Video as a Branding Strategy.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>What’s next for your viewer after they watch your video? Your video should end by prompting them to take action. This might be asking them to watch a follow-up or related video, to learn more via a blog post, or to contact your company for more information. Be sure your script and video incorporate the appropriate “next steps” for your viewer.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Thoroughness is essential in an elearning video project. Prior to production, make sure the contents are accurate and up to date!</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>When it comes to style and form; consider your options: eLearning provides many! Don’t miss out on the opportunities to brand your organization: incorporating branding is simple with video-based learning. Be consistent and thorough throughout the project, and always keep your user in mind. Make the video you would want to watch if you were in their shoes!</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:paragraph -->\n<p>Will you be hiring an animator for your eLearning video? Be sure to read our article 3 Skills Your eLearning Animator Needs for Amazing Videos to ensure you hire the right person. Or perhaps you need some inspiration on training videos overall? Our blog articles will provide you with the information and inspiration needed to do just that! Better yet, contact Ninja Tropic and let us help you craft your ideal video-based learning program.</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:heading {\"level\":2} -->\n<h2 class=\"wp-block-heading\">Subscribe Now!</h2>\n<!-- /wp:heading -->','eLearning & Training Video Script Writing: Instructional Design Best Practices','Want to improve your elearning video script writing for instructional design? We&#8217;ve got you covered! Read more from the pros at Ninja Tropic.','publish','closed','closed','','blog-elearning-training-video-script-writing-instructional-design-best-practices','','','2026-06-25 21:45:58','2026-06-25 21:45:58','',0,'http://localhost:8080/blog-elearning-training-video-script-writing-instructional-design-best-practices/',0,'post','',0);
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

-- Dump completed on 2026-06-30  5:07:50
