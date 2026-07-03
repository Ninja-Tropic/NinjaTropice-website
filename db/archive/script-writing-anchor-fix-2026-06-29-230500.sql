UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			REPLACE(
				REPLACE(
					REPLACE(
						post_content,
						'<a href="#title2">What is Video-Based Learning?</a>',
						'<a href="/blog-elearning-training-video-script-writing-instructional-design-best-practices/#title2">What is Video-Based Learning?</a>'
					),
					'<a href="#title3">Why Video-Based Learning?</a>',
					'<a href="/blog-elearning-training-video-script-writing-instructional-design-best-practices/#title3">Why Video-Based Learning?</a>'
				),
				'<a href="#title4">Are Training Videos Effective?</a>',
				'<a href="/blog-elearning-training-video-script-writing-instructional-design-best-practices/#title4">Are Training Videos Effective?</a>'
			),
			'<a href="#title5">Styles of Video-Based Learning</a>',
			'<a href="/blog-elearning-training-video-script-writing-instructional-design-best-practices/#title5">Styles of Video-Based Learning</a>'
		),
		'<a href="#title6">Instructional Design Best Practices for eLearning & Training Video Script Writing</a>',
		'<a href="/blog-elearning-training-video-script-writing-instructional-design-best-practices/#title6">Instructional Design Best Practices for eLearning & Training Video Script Writing</a>'
	),
	'<a href="#title7">The Bottom Line</a>',
	'<a href="/blog-elearning-training-video-script-writing-instructional-design-best-practices/#title7">The Bottom Line</a>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2624;

UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			REPLACE(
				REPLACE(
					REPLACE(
						post_content,
						'<p>Video-based learning uses different formats of video (such as animation or live-action video) to share knowledge or teach skills.',
						'<p id="title2">Video-based learning uses different formats of video (such as animation or live-action video) to share knowledge or teach skills.'
					),
					'<p>Why is it so popular? Video-based learning is an effective and economical way for corporations to relay their messages to their audience.',
					'<p id="title3">Why is it so popular? Video-based learning is an effective and economical way for corporations to relay their messages to their audience.'
				),
				'<p>Online training videos are not only easy to distribute, but they provide higher retention levels than the traditional classroom:',
				'<p id="title4">Online training videos are not only easy to distribute, but they provide higher retention levels than the traditional classroom:'
			),
			'<p>There are many styles and forms of video-based learning.',
			'<p id="title5">There are many styles and forms of video-based learning.'
		),
		'<h2 class="wp-block-heading">Instructional Design Best Practices for eLearning & Training Video Script Writing</h2>',
		'<h2 id="title6" class="wp-block-heading">Instructional Design Best Practices for eLearning & Training Video Script Writing</h2>'
	),
	'<p>When it comes to style and form; consider your options:',
	'<p id="title7">When it comes to style and form; consider your options:'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2624;
