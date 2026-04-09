<?php
/**
 * The header template file
 *
 * @package NinjaTheme
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
	
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
	<header id="masthead" class="site-header">
		<div class="container">
			<div class="site-branding">
				<?php
				if ( has_custom_logo() ) :
					the_custom_logo();
				else :
					if ( is_front_page() && is_home() ) :
						?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
						<?php
					else :
						?>
						<p class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</p>
						<?php
					endif;
					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo $description; ?></p>
					<?php endif;
				endif;
				?>
			</div>

			<!-- Desktop Navigation -->
			<nav id="site-navigation" class="main-navigation desktop-navigation">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id'        => 'primary-menu',
					'menu_class'     => 'clean-menu',
					'container'      => false,
					'fallback_cb'    => false,
				) );
				?>
			</nav>

			<!-- Mobile Navigation -->
			<nav id="mobile-navigation" class="mobile-navigation">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'mobile',
					'menu_id'        => 'mobile-menu',
					'menu_class'     => 'mobile-menu-list',
					'container'      => false,
					'fallback_cb'    => false,
				) );
				?>
			</nav>

			<div class="header-actions">
				<button class="mobile-menu-toggle" aria-label="Toggle menu" aria-expanded="false">
					<span class="hamburger-line"></span>
					<span class="hamburger-line"></span>
					<span class="hamburger-line"></span>
				</button>
			</div>
		</div>
	</header>
