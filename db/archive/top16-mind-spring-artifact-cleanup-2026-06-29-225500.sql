UPDATE wp_posts
SET post_content = REPLACE(
	post_content,
	'<p>">">5. Mind Spring</p>',
	'<p>5. Mind Spring</p>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;
