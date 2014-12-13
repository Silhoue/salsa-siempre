<?php
/**
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) { the_post();

	if (get_the_post_thumbnail()) { ?>
		<div class="post-image-wrapper">
		<?php the_post_thumbnail("full", array("class" => "post-image", "alt" => "")); ?>
		</div>
	<?php } ?>

	<article class="post">
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
			<?php $i = 1;
			while (get_field("skill_{$i}")) { ?>
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

	<?php
	function teacher_classes_clauses($clauses) {
		remove_filter('posts_clauses', 'teacher_classes_clauses');
		$teacher_id = get_the_ID();
		$clauses['where'] .= ' AND (wp_postmeta.meta_value = '.$teacher_id.' OR mt1.meta_value = '.$teacher_id.')';
		$clauses['orderby'] = 'mt2.meta_value ASC, DATE_FORMAT(FROM_UNIXTIME(mt3.meta_value),"%H%i") ASC';
		return $clauses;
	}
	add_filter('posts_clauses','teacher_classes_clauses');

	$classes = new WP_Query(array(
		'post_type' => 'class',
		'meta_query' => array (
			array('key' => 'teacher_1'),
			array('key' => 'teacher_2'),
			array('key' => 'day_of_week'),
			array('key' => 'start_hour')
		)
	));

	if ( $classes->have_posts() ) { ?>
		<section class="teacher-classes">
			<h2 class="page-title">Kursy tego instruktora</h2>
			<?php while ($classes->have_posts()) { $classes->the_post();
				get_template_part('classes-item');
			} ?>
		</section>
	<?php }
} ?>
</main>

<?php get_footer(); ?>
