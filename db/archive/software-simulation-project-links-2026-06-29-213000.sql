UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			REPLACE(
				post_content,
				'<a href="#"><img src="http://localhost:8080/wp-content/uploads/2026/03/porta-lexisnexis-1024x576.webp"',
				'<a href="/projects/lexisnexis/"><img src="http://localhost:8080/wp-content/uploads/2026/03/porta-lexisnexis-1024x576.webp"'
			),
			'<h3 class="wp-block-heading has-text-align-center">LexisNexis</h3>',
			'<h3 class="wp-block-heading has-text-align-center"><a href="/projects/lexisnexis/">LexisNexis</a></h3>'
		),
		'<a href="#"><img src="http://localhost:8080/wp-content/uploads/2026/03/Segment-1280x720-1-1024x576.webp"',
		'<a href="/projects/segment-2/"><img src="http://localhost:8080/wp-content/uploads/2026/03/Segment-1280x720-1-1024x576.webp"'
	),
	'<h3 class="wp-block-heading has-text-align-center">Segment</h3>',
	'<h3 class="wp-block-heading has-text-align-center"><a href="/projects/segment-2/">Segment</a></h3>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 451;
