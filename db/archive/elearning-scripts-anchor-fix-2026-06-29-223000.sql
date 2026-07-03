UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			post_content,
			'<a href="#title1">Engagement</a>',
			'<a href="/blog-e-learning-scripts-for-animated-corporate-videos-2/#title1">Engagement</a>'
		),
		'<a href="#title6">Final Script</a>',
		'<a href="/blog-e-learning-scripts-for-animated-corporate-videos-2/#title6">Final Script</a>'
	),
	'<a href="#title7">The Bottom Line</a>',
	'<a href="/blog-e-learning-scripts-for-animated-corporate-videos-2/#title7">The Bottom Line</a>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2600;

SET @engagement_start := (
	SELECT LOCATE('<p>Animation makes learning science and math fun for kids', post_content)
	FROM wp_posts
	WHERE ID = 2600
);

UPDATE wp_posts
SET post_content = INSERT(post_content, @engagement_start + 2, 0, ' id="title1"'),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2600
	AND @engagement_start > 0
	AND post_content NOT LIKE '%id="title1"%';

SET @final_script_start := (
	SELECT LOCATE('<p>After ', post_content, LOCATE('<h2 class="wp-block-heading">Editing</h2>', post_content))
	FROM wp_posts
	WHERE ID = 2600
);

UPDATE wp_posts
SET post_content = INSERT(post_content, @final_script_start + 2, 0, ' id="title6"'),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2600
	AND @final_script_start > 0
	AND post_content NOT LIKE '%id="title6"%';

SET @bottom_line_start := (
	SELECT LOCATE('<p>In the end, learning how to write an animation script', post_content)
	FROM wp_posts
	WHERE ID = 2600
);

UPDATE wp_posts
SET post_content = INSERT(post_content, @bottom_line_start + 2, 0, ' id="title7"'),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2600
	AND @bottom_line_start > 0
	AND post_content NOT LIKE '%id="title7"%';
