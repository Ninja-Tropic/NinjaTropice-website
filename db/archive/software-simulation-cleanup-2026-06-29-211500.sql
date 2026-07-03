START TRANSACTION;

SET @benefits_old := CONCAT(
	'<!-- wp:paragraph -->', CHAR(10),
	'<p>? Improved confidence of the learner<br>? Hands-on practice without risk<br>? Increased safety and knowledge<br>? True-to-life experiences<br>? Immediate feedback without manual intervention to guide success<br>? Gaining measurable success data and knowledge retention</p>', CHAR(10),
	'<!-- /wp:paragraph -->'
);

SET @benefits_new := CONCAT(
	'<!-- wp:list -->', CHAR(10),
	'<ul class="wp-block-list"><li>Improved confidence of the learner</li><li>Hands-on practice without risk</li><li>Increased safety and knowledge</li><li>True-to-life experiences</li><li>Immediate feedback without manual intervention to guide success</li><li>Gaining measurable success data and knowledge retention</li></ul>', CHAR(10),
	'<!-- /wp:list -->'
);

UPDATE wp_posts
SET post_content = REPLACE(post_content, @benefits_old, @benefits_new),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 451;

UPDATE wp_posts
SET post_content = REPLACE(
	REPLACE(
		REPLACE(
			REPLACE(
				post_content,
				CONCAT('Simulation-Based Training FAQ', CHAR(239, 191, 189 USING utf8mb4), 's'),
				'Simulation-Based Training FAQs'
			),
			'? How do I include simulation in eLearning training programs?',
			'How do I include simulation in eLearning training programs?'
		),
		'? What is simulation in eLearning?',
		'What is simulation in eLearning?'
	),
	'? How much does eLearning simulation software cost?',
	'How much does eLearning simulation software cost?'
),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 451;

SET @clean_form := '"form":"\\u003cscript charset=\\u0022utf-8\\u0022 type=\\u0022text/javascript\\u0022 src=\\u0022//js.hsforms.net/forms/embed/v2.js\\u0022\\u003e\\u003c/script\\u003e\\r\\n\\u003cscript\\u003e\\r\\n  hbspt.forms.create({\\r\\n    region: \\u0022na1\\u0022,\\r\\n    portalId: \\u00227816367\\u0022,\\r\\n    formId: \\u0022394c2aa7-ab2d-43b4-8cf2-d230dd4662bf\\u0022\\r\\n  });\\r\\n\\u003c/script\\u003e';
SET @form_start := (
	SELECT LOCATE('"form":"', post_content, GREATEST(LOCATE('hbspt.forms.create', post_content) - 1500, 1))
	FROM wp_posts
	WHERE ID = 451
);
SET @form_end_marker := '","_form":"field_697ad143e258c"';
SET @form_end := (
	SELECT LOCATE(@form_end_marker, post_content, @form_start)
	FROM wp_posts
	WHERE ID = 451
);

UPDATE wp_posts
SET post_content = CONCAT(SUBSTRING(post_content, 1, @form_start - 1), @clean_form, SUBSTRING(post_content, @form_end)),
	post_modified = NOW(),
	post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 451
	AND @form_start > 0
	AND @form_end > @form_start;

COMMIT;
