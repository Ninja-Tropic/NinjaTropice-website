<?php
/**
 * Block Name: Services Tabs
 *
 * Fields: tag (text), title (text), description (textarea),
 * services (repeater: title, description, image, button).
 * Left column lists service titles (tabs); right column shows the active service card.
 *
 * @package NinjaTheme
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'services-tabs-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'services-tabs';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Get ACF fields
$f = get_fields();
if( !$f ) {
    $f = array();
}

$tag = !empty($f['tag']) ? $f['tag'] : '';
$title = !empty($f['title']) ? $f['title'] : '';
$description = !empty($f['description']) ? $f['description'] : '';
$services = !empty($f['services']) ? array_values(array_filter($f['services'], function($s) {
    return !empty($s['title']);
})) : array();
?>

<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>" data-services-tabs>
    <div class="services-tabs__container container">

        <div class="services-tabs__main">
            <?php if( !empty($tag) ): ?>
                <span class="services-tabs__tag"><?php echo esc_html($tag); ?></span>
            <?php endif; ?>

            <?php if( !empty($title) ): ?>
                <h2 class="services-tabs__title"><?php echo esc_html($title); ?></h2>
            <?php endif; ?>

            <?php if( !empty($description) ): ?>
                <div class="services-tabs__description"><?php echo wp_kses_post($description); ?></div>
            <?php endif; ?>

            <?php if( !empty($services) ): ?>
                <div class="services-tabs__list" role="tablist" aria-orientation="vertical">
                    <?php foreach( $services as $i => $service ):
                        $is_active = ($i === 0);
                    ?>
                        <button type="button"
                                role="tab"
                                class="services-tabs__tab<?php echo $is_active ? ' is-active' : ''; ?>"
                                id="<?php echo esc_attr($id . '-tab-' . $i); ?>"
                                aria-controls="<?php echo esc_attr($id . '-panel-' . $i); ?>"
                                aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
                                tabindex="<?php echo $is_active ? '0' : '-1'; ?>">
                            <?php echo esc_html($service['title']); ?>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if( !empty($services) ): ?>
            <div class="services-tabs__panels">
                <?php foreach( $services as $i => $service ):
                    $is_active = ($i === 0);
                    $service_desc = !empty($service['description']) ? $service['description'] : '';
                    $service_image = !empty($service['image']) ? $service['image'] : null;
                    $service_button = !empty($service['button']) ? $service['button'] : null;
                ?>
                    <article class="services-tabs__panel<?php echo $is_active ? ' is-active' : ''; ?>"
                             role="tabpanel"
                             id="<?php echo esc_attr($id . '-panel-' . $i); ?>"
                             aria-labelledby="<?php echo esc_attr($id . '-tab-' . $i); ?>"
                             <?php echo $is_active ? '' : 'hidden'; ?>>
                        <?php if( !empty($service_image) ): ?>
                            <div class="services-tabs__media">
                                <?php echo ninjatheme_get_responsive_image( $service_image, 'large', array( 'class' => 'services-tabs__img' ) ); ?>
                            </div>
                        <?php endif; ?>

                        <h3 class="services-tabs__panel-title"><?php echo esc_html($service['title']); ?></h3>

                        <?php if( !empty($service_desc) ): ?>
                            <div class="services-tabs__panel-description"><?php echo wp_kses_post($service_desc); ?></div>
                        <?php endif; ?>

                        <?php if( !empty($service_button) ): ?>
                            <a href="<?php echo esc_url($service_button['url']); ?>"
                               class="services-tabs__button"
                               <?php if( !empty($service_button['target']) ): ?>target="<?php echo esc_attr($service_button['target']); ?>" rel="noopener"<?php endif; ?>>
                                <?php echo esc_html(!empty($service_button['title']) ? $service_button['title'] : $service_button['url']); ?>
                            </a>
                        <?php endif; ?>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
