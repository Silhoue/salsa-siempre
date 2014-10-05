<?php
/**
 * Template Name: Teachers
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
	<h1 class="page-title">Instruktorzy</h1>
	<?php
		$teachers = array(
			'post_type' => 'teacher',
			'order' => 'ASC'
		);
		query_posts($teachers);
	?>
	<?php if ( have_posts() ) :
		echo "<!--";
		while ( have_posts() ) : the_post();
			get_template_part("teachers-item");
		endwhile;
		echo "-->";
	else :
		get_template_part("content", "none");
	endif; ?>
</main>

<?php get_footer(); ?>