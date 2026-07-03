UPDATE wp_posts
SET post_content = REPLACE(
	post_content,
	'<!-- wp:paragraph -->\n<h2 class="wp-block-heading" id="title2">Top 16 Training Video Production Companies in 2023</h2>\n\n<p>Now that your checklist is complete, it''s time to turn your attention to finding the best video production companies for corporate videos. Here are our top picks, and it''s your call to decide which one is right for your project needs.</p>\n<!-- /wp:paragraph -->',
	'<!-- wp:heading {"level":2} -->\n<h2 id="title2" class="wp-block-heading">Top 16 Training Video Production Companies in 2023</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>Now that your checklist is complete, it''s time to turn your attention to finding the best video production companies for corporate videos. Here are our top picks, and it''s your call to decide which one is right for your project needs.</p>\n<!-- /wp:paragraph -->'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;
