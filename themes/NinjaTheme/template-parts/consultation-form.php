<?php
/**
 * Reusable consultation / HubSpot form section.
 * Same content and markup as the acf/hubspot-form block used on other pages.
 *
 * @package NinjaTheme
 */
?>
<section id="consultation-form" class="hubspot-form">
    <div class="hubspot-form__container container">
        <div class="hubspot-form__cta">

            <div class="hubspot-form__content">
                <h2 class="hubspot-form__title">
                    <?php echo ninjatheme_gradient_text( 'Ready to start developing' ); ?>
                    <?php esc_html_e( " your learner's Training experience?" ); ?>
                </h2>
                <div class="hubspot-form__description">
                    <p><?php esc_html_e( 'Discover the power of video training animation by scheduling a FREE consultation with one of our Ninja Tropic experts' ); ?></p>
                </div>
            </div>

            <div class="hubspot-form__actions">
                <div class="hubspot-form__form-card">
                    <div class="hubspot-form__form">
                        <script charset="utf-8" type="text/javascript" src="//js.hsforms.net/forms/embed/v2.js"></script>
                        <script>
                            hbspt.forms.create({
                                region: "na1",
                                portalId: "7816367",
                                formId: "394c2aa7-ab2d-43b4-8cf2-d230dd4662bf"
                            });
                        </script>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
