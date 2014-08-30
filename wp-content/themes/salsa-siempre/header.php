<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Salsa Siempre
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700&subset=latin,latin-ext">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'salsa-siempre' ); ?></a>

	<header class="header" role="banner">
		<div class="header-inner">
			<img class="header-logo" src="<?php bloginfo('template_directory'); ?>/img/logo.png"/>

			<nav class="main-navigation" role="navigation">
				<button class="menu-toggle"><?php _e( 'Primary Menu', 'salsa-siempre' ); ?></button>
				<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
			</nav><!-- .main-navigation -->
		</div>
	</header><!-- .header -->

	<div class="content">
