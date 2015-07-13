<?php
/**
 * The template for displaying all single posts.
 *
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) {
	the_post();
	get_template_part("post");
} ?>
</main>

<?php get_footer(); ?>
