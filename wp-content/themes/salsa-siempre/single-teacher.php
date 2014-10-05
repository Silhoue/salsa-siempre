<?php
/**
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while ( have_posts() ) : the_post(); ?>

	<?php if (get_the_post_thumbnail()):
	the_post_thumbnail("teacher-image", array("class" => "post-image teacher-image", "alt" => ""));
	endif; ?>

	<article id="post-<?php the_ID(); ?>" class="post" <?php post_class(); ?>>
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php $i = 1;
			while (get_field("nazwa_umiejętności_{$i}")): ?>
			<span class="post-detail"><?php the_field("nazwa_umiejętności_{$i}")?>
				<span class="post-detail-value teacher-skills-rating">
				<?php $stars_count = get_field("liczba_gwiazdek_{$i}");
				for ($j = 0; $j < $stars_count; $j++) {
					echo "★";
				}
				$i++; ?>
				</span>
			</span>
			<?php endwhile ?>
		</header>

		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
