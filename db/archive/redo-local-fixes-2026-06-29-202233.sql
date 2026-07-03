START TRANSACTION;

-- Video Training Production: testimonials CTA -> Awards.
UPDATE wp_posts
SET post_content = REPLACE(
        REPLACE(post_content,
            'href="/case-studies/">Check out our clients testimonials!',
            'href="/awards/">Check out our clients testimonials!'
        ),
        'href="#">Check out our clients testimonials!',
        'href="/awards/">Check out our clients testimonials!'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 939;

-- Microlearning Solutions: San Diego Zoo Show more -> matching case study.
UPDATE wp_posts
SET post_content = INSERT(
        post_content,
        LOCATE('href="/case-studies/">Show more', post_content, LOCATE('San Diego Zoo', post_content)),
        CHAR_LENGTH('href="/case-studies/">Show more'),
        'href="/case-studies/san-diego-zoo-interactive-training/">Show more'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 363
  AND LOCATE('href="/case-studies/">Show more', post_content, LOCATE('San Diego Zoo', post_content)) > 0;

-- LMS Implementation Consultant: Get a Quote buttons -> consultation page.
UPDATE wp_posts
SET post_content = REPLACE(
        REPLACE(
            REPLACE(post_content,
                'link_2":{"title":"Get a Quote!","url":"#","target":""}',
                'link_2":{"title":"Get a Quote!","url":"/elearning-video-animation-companies-free-consultation/","target":""}'
            ),
            '<!-- wp:button {"backgroundColor":"luminous-vivid-orange","textColor":"white","style":{"border":{"radius":"50px"}}} -->',
            '<!-- wp:button {"url":"/elearning-video-animation-companies-free-consultation/","backgroundColor":"luminous-vivid-orange","textColor":"white","style":{"border":{"radius":"50px"}}} -->'
        ),
        '<a class="wp-block-button__link has-white-color has-luminous-vivid-orange-background-color has-text-color has-background wp-element-button" style="border-radius:50px">Get a Quote!</a>',
        '<a class="wp-block-button__link has-white-color has-luminous-vivid-orange-background-color has-text-color has-background wp-element-button" href="/elearning-video-animation-companies-free-consultation/" style="border-radius:50px">Get a Quote!</a>'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 381;

-- Workplace Compliance Training: related solution buttons.
UPDATE wp_posts
SET post_content = REPLACE(
        REPLACE(
            REPLACE(post_content,
                'solution_0_link":{"title":"Learn more","url":"#","target":""}',
                'solution_0_link":{"title":"Learn more","url":"/corporate-training/technical-training/","target":""}'
            ),
            'solution_1_link":{"title":"Learn more","url":"#","target":""}',
            'solution_1_link":{"title":"Learn more","url":"/corporate-training/product-training/","target":""}'
        ),
        'solution_2_link":{"title":"Learn more","url":"#","target":""}',
        'solution_2_link":{"title":"Learn more","url":"/corporate-training/employee-onboarding-training/","target":""}'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 406;

-- Marketing Communication Training: related solution buttons.
UPDATE wp_posts
SET post_content = REPLACE(
        REPLACE(
            REPLACE(post_content,
                'solution_0_link":{"title":"Learn more","url":"#","target":""}',
                'solution_0_link":{"title":"Learn more","url":"/corporate-training/product-training/","target":""}'
            ),
            'solution_1_link":{"title":"Learn more","url":"#","target":""}',
            'solution_1_link":{"title":"Learn more","url":"/corporate-training/sales-enablement-training/","target":""}'
        ),
        'solution_2_link":{"title":"Learn more","url":"#","target":""}',
        'solution_2_link":{"title":"Learn more","url":"/corporate-training/diversity-and-inclusion-training/","target":""}'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 436;

-- Universities: label the cards as case studies and remove the logo strip.
SET @logo_start := (
    SELECT LOCATE('<!-- wp:group {"align":"full","className":"container"', post_content)
    FROM wp_posts
    WHERE ID = 477
);
SET @form_start := (
    SELECT LOCATE('<!-- wp:acf/hubspot-form', post_content, @logo_start)
    FROM wp_posts
    WHERE ID = 477
);
UPDATE wp_posts
SET post_content = CONCAT(
        SUBSTRING(post_content, 1, @logo_start - 1),
        SUBSTRING(post_content, @form_start)
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 477
  AND @logo_start > 0
  AND @form_start > @logo_start;

SET @case_columns := '<!-- wp:columns {"className":"container"} -->';
SET @case_heading := '<!-- wp:heading {"textAlign":"center"} -->\n<h2 class="wp-block-heading has-text-align-center">University <em>Case Studies</em></h2>\n<!-- /wp:heading -->\n\n<!-- wp:spacer {"height":"2.5rem"} -->\n<div style="height:2.5rem" aria-hidden="true" class="wp-block-spacer"></div>\n<!-- /wp:spacer -->\n\n';
UPDATE wp_posts
SET post_content = INSERT(post_content, LOCATE(@case_columns, post_content), 0, @case_heading),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 477
  AND LOCATE('University <em>Case Studies</em>', post_content) = 0
  AND LOCATE(@case_columns, post_content) > 0;

-- Customer Education Services: Get a Quote buttons -> consultation page.
UPDATE wp_posts
SET post_content = REPLACE(
        post_content,
        '<!-- wp:button -->\n<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Get a Quote!</a></div>\n<!-- /wp:button -->',
        '<!-- wp:button {"url":"/elearning-video-animation-companies-free-consultation/"} -->\n<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="/elearning-video-animation-companies-free-consultation/">Get a Quote!</a></div>\n<!-- /wp:button -->'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 404;

-- Science of eLearning Animation: make the intro links jump to anchors.
UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>Before you can teach anyone effectively, it helps to know how the brain works.',
        '<p id="title1">Before you can teach anyone effectively, it helps to know how the brain works.'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2614
  AND LOCATE('id="title1"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<h2 class="wp-block-heading">The Science of eLearning Animation: How Does Animation Help Us Learn?</h2>',
        '<h2 id="title2" class="wp-block-heading">The Science of eLearning Animation: How Does Animation Help Us Learn?</h2>'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2614
  AND LOCATE('id="title2"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>Every day, more and more corporations are choosing to',
        '<p id="title3">Every day, more and more corporations are choosing to'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2614
  AND LOCATE('id="title3"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<h2 class="wp-block-heading">How to Incorporate Animation Into the Workplace?</h2>',
        '<h2 id="title4" class="wp-block-heading">How to Incorporate Animation Into the Workplace?</h2>'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2614
  AND LOCATE('id="title4"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>In sum, how animation is used in education simply opens the door',
        '<p id="title5">In sum, how animation is used in education simply opens the door'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2614
  AND LOCATE('id="title5"', post_content) = 0;

-- Product Knowledge Training: make the intro links jump to anchors.
UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>Before we begin, it''s important to understand the meaning of product knowledge.',
        '<p id="title1">Before we begin, it''s important to understand the meaning of product knowledge.'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2606
  AND LOCATE('id="title1"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<h2 class="wp-block-heading">Why Is Product Knowledge Important &What Are the Benefits?</h2>',
        '<h2 id="title2" class="wp-block-heading">Why Is Product Knowledge Important &What Are the Benefits?</h2>'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2606
  AND LOCATE('id="title2"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>The overall objective of your product knowledge training program should be',
        '<p id="title3">The overall objective of your product knowledge training program should be'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2606
  AND LOCATE('id="title3"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>There are quite a few different types of product knowledge you may want to incorporate',
        '<p id="title4">There are quite a few different types of product knowledge you may want to incorporate'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2606
  AND LOCATE('id="title4"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>To develop your product knowledge training and successfully scale your business,',
        '<p id="title5">To develop your product knowledge training and successfully scale your business,'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2606
  AND LOCATE('id="title5"', post_content) = 0;

UPDATE wp_posts
SET post_content = REPLACE(post_content,
        '<p>So there you have it! That''s everything you need to know to create effective product training.',
        '<p id="title6">So there you have it! That''s everything you need to know to create effective product training.'
    ),
    post_modified = NOW(),
    post_modified_gmt = UTC_TIMESTAMP()
WHERE ID = 2606
  AND LOCATE('id="title6"', post_content) = 0;

COMMIT;
