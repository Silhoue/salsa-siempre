<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
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
		</header>
		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>

<?php endwhile; ?>
</main>

<?php get_footer(); ?>
