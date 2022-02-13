<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boreal_Bees
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<!-- typekit fonts -->
	<link rel="stylesheet" href="https://use.typekit.net/vep7hso.css">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'bb' ); ?></a>

	<header id="masthead" class="site-header site-header-sticky">
		<div class="branding-container">
			<div class="site-branding">
				<?php
					the_custom_logo();
				?>
			</div>	
		</div><!-- .site-branding -->
		<div class="header-icon-container">
		<img class="header-logo-icon" src="<?php site_icon_url()?>" alt="Boreal Bees" />
		</div><!--site icon -->
		<div class="site-nav-container">
			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
					)
				);
				?>
			</nav><!-- #site-navigation -->
		</div>
	</header><!-- #masthead -->
