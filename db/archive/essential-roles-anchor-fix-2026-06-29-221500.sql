UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			REPLACE(
				post_content,
				'<a href="#title1">Getting started with eLearning:<br />your team members</a>',
				'<a href="/blog-10-essential-roles-for-your-online-video-training-development-team/#title1">Getting started with eLearning:<br />your team members</a>'
			),
			'<a href="#title2">The Botom Line</a>',
			'<a href="/blog-10-essential-roles-for-your-online-video-training-development-team/#title2">The Bottom Line</a>'
		),
		'<h2 class="wp-block-heading">Getting Started With eLearning: Your Online Training Development Team Members</h2>',
		'<h2 id="title1" class="wp-block-heading">Getting Started With eLearning: Your Online Training Development Team Members</h2>'
	),
	'<h2 class="wp-block-heading">The Bottom Line</h2>',
	'<h2 id="title2" class="wp-block-heading">The Bottom Line</h2>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2602;
