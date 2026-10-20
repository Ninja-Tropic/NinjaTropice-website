<?php
/**
 * Lucide icons: ninja_lucide() helper + "Lucide Icon" ACF field type.
 *
 * SVGs live in assets/lucide/ (from the lucide-static npm package, ISC license).
 * The field stores only the icon name (e.g. "heart"); ninja_lucide() prints it inline
 * with stroke="currentColor", so the icon inherits the text color.
 *
 * @package NinjaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Return the inline SVG markup for a Lucide icon, or '' if it does not exist.
 *
 * @param string $name Icon name, e.g. "arrow-right".
 * @param array  $args size (px), stroke_width, class.
 * @return string
 */
function ninja_lucide( $name, $args = array() ) {
	static $cache = array();

	$name = is_string( $name ) ? sanitize_key( $name ) : '';
	if ( '' === $name ) {
		return '';
	}

	if ( ! isset( $cache[ $name ] ) ) {
		$file           = get_stylesheet_directory() . '/assets/lucide/' . $name . '.svg';
		$cache[ $name ] = file_exists( $file ) ? trim( preg_replace( '/<!--.*?-->/s', '', file_get_contents( $file ) ) ) : '';
	}
	$svg = $cache[ $name ];
	if ( '' === $svg ) {
		return '';
	}

	$args = wp_parse_args( $args, array(
		'size'         => 24,
		'stroke_width' => 2,
		'class'        => '',
	) );

	$svg = preg_replace( '/\sclass="[^"]*"/', '', $svg, 1 );
	$svg = preg_replace( '/\swidth="[^"]*"/', ' width="' . (int) $args['size'] . '"', $svg, 1 );
	$svg = preg_replace( '/\sheight="[^"]*"/', ' height="' . (int) $args['size'] . '"', $svg, 1 );
	$svg = preg_replace( '/\sstroke-width="[^"]*"/', ' stroke-width="' . (float) $args['stroke_width'] . '"', $svg, 1 );

	$class = trim( 'lucide lucide-' . $name . ' ' . $args['class'] );
	return preg_replace( '/^<svg/', '<svg class="' . esc_attr( $class ) . '" aria-hidden="true" focusable="false"', $svg, 1 );
}

/**
 * Register the "lucide_icon" ACF field type.
 */
function ninjatheme_register_lucide_field_type() {
	if ( ! class_exists( 'acf_field' ) ) {
		return;
	}

	class Ninja_ACF_Field_Lucide_Icon extends acf_field {

		public function initialize() {
			$this->name     = 'lucide_icon';
			$this->label    = __( 'Lucide Icon', 'ninjatheme' );
			$this->category = 'choice';
			$this->defaults = array(
				'default_value' => '',
				'allow_null'    => 1,
			);
		}

		public function input_admin_enqueue_scripts() {
			$dir = get_stylesheet_directory();
			$uri = get_stylesheet_directory_uri();

			wp_enqueue_style( 'ninja-lucide-picker', $uri . '/js/admin/lucide-picker.css', array(), filemtime( $dir . '/js/admin/lucide-picker.css' ) );
			wp_enqueue_script( 'ninja-lucide-picker', $uri . '/js/admin/lucide-picker.js', array( 'jquery', 'acf-input' ), filemtime( $dir . '/js/admin/lucide-picker.js' ), true );
			wp_localize_script( 'ninja-lucide-picker', 'ninjaLucide', array(
				'baseUrl' => $uri . '/assets/lucide/',
				'icons'   => json_decode( (string) file_get_contents( $dir . '/assets/lucide/icons.json' ) ),
			) );
		}

		public function render_field( $field ) {
			$value = is_string( $field['value'] ) ? $field['value'] : '';
			$base  = get_stylesheet_directory_uri() . '/assets/lucide/';
			?>
			<div class="ninja-lucide-field" data-base="<?php echo esc_url( $base ); ?>">
				<input type="hidden" class="ninja-lucide-field__input" name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
				<button type="button" class="button ninja-lucide-field__toggle">
					<span class="ninja-lucide-field__preview"><?php if ( $value ) : ?><img src="<?php echo esc_url( $base . $value . '.svg' ); ?>" alt=""><?php endif; ?></span>
					<span class="ninja-lucide-field__label"><?php echo $value ? esc_html( $value ) : esc_html__( 'Choose icon', 'ninjatheme' ); ?></span>
				</button>
				<button type="button" class="button-link ninja-lucide-field__clear"<?php echo $value ? '' : ' hidden'; ?>><?php esc_html_e( 'Remove', 'ninjatheme' ); ?></button>
				<div class="ninja-lucide-field__panel" hidden>
					<input type="search" class="ninja-lucide-field__search" placeholder="<?php esc_attr_e( 'Search icons…', 'ninjatheme' ); ?>">
					<div class="ninja-lucide-field__grid"></div>
				</div>
			</div>
			<?php
		}

		public function format_value( $value, $post_id, $field ) {
			return is_string( $value ) ? sanitize_key( $value ) : '';
		}
	}

	acf_register_field_type( 'Ninja_ACF_Field_Lucide_Icon' );
}
add_action( 'acf/include_field_types', 'ninjatheme_register_lucide_field_type' );
