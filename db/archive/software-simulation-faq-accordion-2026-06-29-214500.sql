SET @faq_start := (
	SELECT LOCATE('\\u003cstrong\\u003eSimulation-Based Training FAQ', post_content)
	FROM wp_posts
	WHERE ID = 451
);

SET @faq_end_marker := '","_description":"field_697ad133e258b"';
SET @faq_end := (
	SELECT LOCATE(@faq_end_marker, post_content, @faq_start)
	FROM wp_posts
	WHERE ID = 451
);

SET @faq_new := '\\u003cdiv class=\\u0022nt-faq-accordion\\u0022\\u003e\\r\\n\\u003ch3 class=\\u0022nt-faq-accordion__title\\u0022\\u003eSimulation-Based Training FAQs\\u003c/h3\\u003e\\r\\n\\u003cdetails class=\\u0022nt-faq-accordion__item\\u0022\\u003e\\r\\n\\u003csummary\\u003eHow do I include simulation in eLearning training programs?\\u003c/summary\\u003e\\r\\n\\u003cdiv class=\\u0022nt-faq-accordion__body\\u0022\\u003e\\u003cp\\u003eStart by identifying the key tasks learners need to practice and map each workflow with a subject matter expert. Use authoring tools like Articulate Storyline or Adobe Captivate to build click-through interactions layered over screen recordings of the real software. For a fully custom simulation embedded directly in your LMS, a development partner like Ninja Tropic can design the full experience so learners never need to leave the platform.\\u003c/p\\u003e\\u003c/div\\u003e\\r\\n\\u003c/details\\u003e\\r\\n\\u003cdetails class=\\u0022nt-faq-accordion__item\\u0022\\u003e\\r\\n\\u003csummary\\u003eWhat is simulation in eLearning?\\u003c/summary\\u003e\\r\\n\\u003cdiv class=\\u0022nt-faq-accordion__body\\u0022\\u003e\\u003cp\\u003eSimulation in eLearning is an interactive training method that recreates a real-world environment&#8212;such as a software interface, a customer interaction, or a technical procedure&#8212;so learners can practice without any risk. Instead of watching a demo, learners complete actual tasks inside a replica of the system, building muscle memory and boosting retention before they ever touch a live environment.\\u003c/p\\u003e\\u003c/div\\u003e\\r\\n\\u003c/details\\u003e\\r\\n\\u003cdetails class=\\u0022nt-faq-accordion__item\\u0022\\u003e\\r\\n\\u003csummary\\u003eHow much does eLearning simulation software cost?\\u003c/summary\\u003e\\r\\n\\u003cdiv class=\\u0022nt-faq-accordion__body\\u0022\\u003e\\u003cp\\u003eCosts vary based on complexity and approach. Off-the-shelf authoring tools range from $400 to $2,000 per year per seat. A fully custom simulation built by an eLearning development partner typically ranges from $5,000 to $30,000 or more, depending on the number of scenarios, interactivity level, and integrations required. Ninja Tropic offers flexible packages designed to match your budget and training goals.\\u003c/p\\u003e\\u003c/div\\u003e\\r\\n\\u003c/details\\u003e\\r\\n\\u003c/div\\u003e';

UPDATE wp_posts
SET post_content = CONCAT(
		SUBSTRING(post_content, 1, @faq_start - 1),
		@faq_new,
		SUBSTRING(post_content, @faq_end)
	),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 451
	AND @faq_start > 0
	AND @faq_end > @faq_start;
