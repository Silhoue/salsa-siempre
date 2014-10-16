<?php
/**
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) { the_post();

	if (get_the_post_thumbnail()) {
		the_post_thumbnail("full", array("class" => "post-image teacher-image", "alt" => ""));
	} ?>

	<article class="post">
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php $i = 1;
			while (get_field("sill_{$i}")) { ?>
			<span class="post-detail"><?php the_field("skill_{$i}")?>
				<span class="post-detail-value teacher-skills-rating">
				<?php $stars_count = get_field("rating_{$i}");
				for ($j = 0; $j < $stars_count; $j++) {
					echo "★";
				}
				$i++; ?>
				</span>
			</span>
			<?php } ?>
		</header>

		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>

<?php } ?>
</main>

<?php get_footer(); ?>
