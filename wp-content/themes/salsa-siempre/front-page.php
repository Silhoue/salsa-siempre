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
			get_template_part("news-item");
		endwhile;
		echo "-->";
	else :
		get_template_part("content", "none");
	endif; ?>
</main>

<?php get_footer(); ?>
