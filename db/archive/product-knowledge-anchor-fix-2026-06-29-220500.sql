UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			REPLACE(
				REPLACE(
					REPLACE(
						REPLACE(
							REPLACE(
								REPLACE(
									REPLACE(
										REPLACE(
											REPLACE(
												post_content,
												'<a href="#title1">What Is Product Knowledge?</a>',
												'<a href="/blog-product-knowledge-training-everything-you-need-to-know/#title1">What Is Product Knowledge?</a>'
											),
											'<a href="#title2">Why Is Product Knowledge Important & What Are the Benefits?</a>',
											'<a href="/blog-product-knowledge-training-everything-you-need-to-know/#title2">Why Is Product Knowledge Important & What Are the Benefits?</a>'
										),
										'<a href="#title3">Product Knowledge Training Objectives (and Use Cases)</a>',
										'<a href="/blog-product-knowledge-training-everything-you-need-to-know/#title3">Product Knowledge Training Objectives (and Use Cases)</a>'
									),
									'<a href="#title4">What Are the Different Types of Product Knowledge?</a>',
									'<a href="/blog-product-knowledge-training-everything-you-need-to-know/#title4">What Are the Different Types of Product Knowledge?</a>'
								),
								'<a href="#title5">How to Develop, Implement, and Automate Product Knowledge Training</a>',
								'<a href="/blog-product-knowledge-training-everything-you-need-to-know/#title5">How to Develop, Implement, and Automate Product Knowledge Training</a>'
							),
							'<a href="#title6">Final Thoughts on Product Knowledge Training</a>',
							'<a href="/blog-product-knowledge-training-everything-you-need-to-know/#title6">Final Thoughts on Product Knowledge Training</a>'
						),
						'<!-- wp:paragraph -->\n<p id="title1">',
						'<!-- wp:heading {"level":2} -->\n<h2 id="title1" class="wp-block-heading">What Is Product Knowledge?</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>'
					),
					'<h2 id="title2" class="wp-block-heading">Why Is Product Knowledge Important &What Are the Benefits?</h2>',
					'<h2 id="title2" class="wp-block-heading">Why Is Product Knowledge Important & What Are the Benefits?</h2>'
				),
				'<!-- wp:paragraph -->\n<p id="title3">',
				'<!-- wp:heading {"level":2} -->\n<h2 id="title3" class="wp-block-heading">Product Knowledge Training Objectives (and Use Cases)</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>'
			),
			'<!-- wp:paragraph -->\n<p id="title4">',
			'<!-- wp:heading {"level":2} -->\n<h2 id="title4" class="wp-block-heading">What Are the Different Types of Product Knowledge?</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>'
		),
		'<!-- wp:paragraph -->\n<p id="title5">',
		'<!-- wp:heading {"level":2} -->\n<h2 id="title5" class="wp-block-heading">How to Develop, Implement, and Automate Product Knowledge Training</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>'
	),
	'<!-- wp:paragraph -->\n<p id="title6">',
	'<!-- wp:heading {"level":2} -->\n<h2 id="title6" class="wp-block-heading">Final Thoughts on Product Knowledge Training</h2>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph -->\n<p>'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2606;
