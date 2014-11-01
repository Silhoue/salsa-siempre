<?php
/**
 * Salsa Siempre functions and definitions
 *
 * @package Salsa Siempre
 */

if ( ! function_exists( 'salsa_siempre_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function salsa_siempre_setup() {
	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'salsa-siempre' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link'
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'salsa_siempre_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // salsa_siempre_setup
add_action( 'after_setup_theme', 'salsa_siempre_setup' );

/**
 * Enqueue scripts and styles.
 */
function salsa_siempre_scripts() {
	wp_enqueue_style( 'salsa-siempre-style', get_stylesheet_uri() );

	wp_enqueue_script( 'salsa-siempre-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1', true );

	wp_enqueue_script( 'salsa-siempre-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '1', true );

	if (is_home()) {
		wp_enqueue_script( 'salsa-siempre-splash', get_template_directory_uri() . '/js/splash.js', array(), '1', true );
	}
}
add_action( 'wp_enqueue_scripts', 'salsa_siempre_scripts' );

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Jetpack compatibility
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function salsa_siempre_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );
}
add_action( 'after_setup_theme', 'salsa_siempre_jetpack_setup' );

include "functions-custom.php";
