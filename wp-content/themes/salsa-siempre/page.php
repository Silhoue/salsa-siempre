<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) { the_post();
	get_template_part("page-content");
} ?>
</main>

<?php get_footer(); ?>
