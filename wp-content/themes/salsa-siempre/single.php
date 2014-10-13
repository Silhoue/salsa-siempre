<?php
/**
 * The template for displaying all single posts.
 *
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) { the_post(); ?>

	<?php if (get_the_post_thumbnail()) {
		the_post_thumbnail("news-image", array("class" => "post-image", "alt" => ""));
	} ?>

	<article class="post">
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php if (get_field("start_date")) { ?>
			<span class="post-detail">Data: <span class="post-detail-value"><?php the_field("start_date");
				if (get_field("end_date")) { ?>&nbsp;-&nbsp;<?php the_field("end_date"); } ?></span></span>
			<?php }
			if (get_field("start_time")) { ?>
			<span class="post-detail">Godzina: <span class="post-detail-value"><?php the_field("start_time");
				if (get_field("end_time")) { ?>&nbsp;-&nbsp;<?php the_field("end_time"); } ?></span></span>
			<?php }
			if (get_field("place")) { ?>
			<span class="post-detail">Miejsce:&nbsp;<span class="post-detail-value"><?php the_field("place"); ?></span></span>
			<?php } ?>
		</header>

		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>

<?php } ?>
</main>

<?php get_footer(); ?>
