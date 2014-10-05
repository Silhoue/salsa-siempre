<?php
/**
 * The template for displaying all single posts.
 *
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while ( have_posts() ) : the_post(); ?>

	<?php if (get_the_post_thumbnail()) {
		the_post_thumbnail("news-image", array("class" => "post-image", "alt" => ""));
	} ?>

	<article id="post-<?php the_ID(); ?>" class="post" <?php post_class(); ?>>
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php if ( get_field("data_od") ): ?>
			<span class="post-detail">Data: <span class="post-detail-value"><?php the_field("data_od");
				if ( get_field("data_do") ): ?>&nbsp;-&nbsp;<?php the_field("data_do"); endif; ?></span></span>
			<?php endif; ?>
			<?php if ( get_field("godzina_od") ): ?>
			<span class="post-detail">Godzina: <span class="post-detail-value"><?php the_field("godzina_od");
				if ( get_field("godzina_do") ): ?>&nbsp;-&nbsp;<?php the_field("godzina_do"); endif; ?></span></span>
			<?php endif; ?>
			<?php if ( get_field("miejsce") ): ?>
			<span class="post-detail">Miejsce:&nbsp;<span class="post-detail-value"><?php the_field("miejsce"); ?></span></span>
			<?php endif; ?>
		</header>

		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
