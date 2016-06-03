<?php
/**
 * Template Name: Gallery
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
	<h1 class="page-title">Galeria</h1>
	<?php
		$albums = array(
			'post_type' => 'album',
			'order' => 'ASC'
		);
		query_posts($albums);
	?>
	<?php if ( have_posts() ) {
		while ( have_posts() ) { the_post();
			get_template_part("news-item");
		}
	} ?>
</main>

<?php get_footer(); ?>
