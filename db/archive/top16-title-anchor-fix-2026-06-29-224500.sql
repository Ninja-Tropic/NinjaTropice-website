UPDATE wp_posts
SET post_content = REPLACE(
	post_content,
	'<!-- wp:heading {"level":1} -->\n<h1>Top 16 Training Video Production Companies (2023)</h1>\n<!-- /wp:heading -->\n\n',
	''
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;

UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			post_content,
			'<a href="#title1">B2B Training Done Right: 7 Essential Components Every Quality Corporate Training Video Production Company Must Have</a>',
			'<a href="/top-16-training-video-production-companies-2023/#title1">B2B Training Done Right: 7 Essential Components Every Quality Corporate Training Video Production Company Must Have</a>'
		),
		'<a href="#title2">Top 16 Training Video Production Companies in 2023</a>',
		'<a href="/top-16-training-video-production-companies-2023/#title2">Top 16 Training Video Production Companies in 2023</a>'
	),
	'<a href="#title3">Need a Video Production Company for Your Training Videos?</a>',
	'<a href="/top-16-training-video-production-companies-2023/#title3">Need a Video Production Company for Your Training Videos?</a>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;

UPDATE wp_posts
SET post_content = REPLACE(
	post_content,
	'<h2 class="wp-block-heading">B2B Training Done Right: 7 Essential Components Every Quality Corporate Training Video Production Company Must Have</h2>',
	'<h2 id="title1" class="wp-block-heading">B2B Training Done Right: 7 Essential Components Every Quality Corporate Training Video Production Company Must Have</h2>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;

UPDATE wp_posts
SET post_content = REPLACE(
	post_content,
	'<!-- wp:paragraph -->\n<p>Now that your checklist is complete, it''s time to turn your attention to finding the best video production companies for corporate videos. Here are our top picks, and it''s your call to decide which one is right for your project needs.</p>\n<!-- /wp:paragraph -->',
	'<!-- wp:heading {"level":2} -->\n<h2 id="title2" class="wp-block-heading">Top 16 Training Video Production Companies in 2023</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Now that your checklist is complete, it''s time to turn your attention to finding the best video production companies for corporate videos. Here are our top picks, and it''s your call to decide which one is right for your project needs.</p>\n<!-- /wp:paragraph -->'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;

UPDATE wp_posts
SET post_content = REPLACE(
	post_content,
	'<h2 class="wp-block-heading">">Need a Video Production Company for Your Training Videos?</h2>',
	'<h2 id="title3" class="wp-block-heading">Need a Video Production Company for Your Training Videos?</h2>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;
