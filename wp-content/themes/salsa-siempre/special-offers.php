<?php
/**
 * Template Name: Special offers
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
	<?php while (have_posts()) { the_post();
		get_template_part("page-content");
	}

	$types = array(
		'post_type' => 'special-offer'
	);
	query_posts($types);
	while (have_posts()) { the_post();
		get_template_part("page-content");
	} ?>
</main>

<?php get_footer(); ?>
