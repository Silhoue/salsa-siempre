<?php
/**
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) {
	the_post();
	get_template_part("post");

	$images = get_field('photos');
	if ( $images ): ?>
		<section class="post-gallery">
		<?php foreach( $images as $image ):
			?><a class="post-gallery-item" href="<?php echo $image['url']; ?>">
				<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
			</a><?php
		endforeach; ?>
		</section>
	<?php endif;
} ?>
</main>

<?php get_footer(); ?>
