<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Parchment
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'parchment' ); ?></a>

	<a href="#drawer" data-target-id="drawer" id="toggle-drawer" class="genericon genericon-menu toggle-button"><span class="label"><?php _e( 'Menu', 'parchment' ); ?></span></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php
				// Adding support for jetpack site logo
				if ( function_exists( 'jetpack_the_site_logo' ) ) {
				    jetpack_the_site_logo();
				}
			?>
			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
