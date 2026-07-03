UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			post_content,
			'<p>">">5. Video Portfolio & Reviews</p>',
			'<p>5. Video Portfolio & Reviews</p>'
		),
		'<p>">">It''s easy to go on and on about how great certain training video production companies are, but the proof of their excellence lies in the quality of their work.',
		'<p>It''s easy to go on and on about how great certain training video production companies are, but the proof of their excellence lies in the quality of their work.'
	),
	'<strong>Client Praise:</strong> "All material was delivered expeditiously, and feedback was responded to in equal measure.-Director, Global Communications, Semiconductor Company""></p>',
	'<strong>Client Praise:</strong> "All material was delivered expeditiously, and feedback was responded to in equal measure.-Director, Global Communications, Semiconductor Company"</p>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2581;
