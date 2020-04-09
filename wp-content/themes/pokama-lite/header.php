<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package lotus
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php
if ( function_exists( 'wp_body_open' ) ) {
    wp_body_open();
} else {
    do_action( 'wp_body_open' );
} ?>
	
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'pokama-lite' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<!-- #main-menu -->
		<nav class="main-navigation" id="main-nav">
		<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
			<span class="m_menu_icon"></span>
			<span class="m_menu_icon"></span>
			<span class="m_menu_icon"></span>
		</button>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'menu_class' => 'nav-menu menu' ) ); ?>
		</nav>

	</header><!-- #masthead -->

	<div class="site-branding">

		<?php
			if ( has_custom_logo() ) {

				pokama_lite_the_custom_logo();

			}else{ ?>

			<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>

			<?php
				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>

		<?php } ?>

	</div><!-- .site-branding -->


	<?php if(get_theme_mod('pokamalite_featured_top')) : ?>
	<?php get_template_part('template-parts/featured-bar'); ?>
	<?php endif; ?>

	<div id="content" class="site-content">
