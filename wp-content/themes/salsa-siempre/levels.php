<?php
/**
 * Template Name: Levels
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
	<?php while (have_posts()) { the_post(); ?>
		<h1 class="page-title"><?php the_title(); ?></h1>
	<?php }

	$types = array(
		'post_type' => 'level',
		'order' => 'asc'
	);
	query_posts($types);
	while (have_posts()) { the_post();
		get_template_part("page-content");
	} ?>
</main>

<?php get_footer(); ?>
