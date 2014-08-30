<?php
/**
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
	<?php if ( have_posts() ) :
		echo "<!--";
		while ( have_posts() ) : the_post();
			get_template_part("content", get_post_format());
		endwhile;
		echo "-->";
	else :
		get_template_part("content", "none");
	endif; ?>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
