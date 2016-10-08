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

	function type_classes_clauses($clauses) {
		remove_filter('posts_clauses', 'type_classes_clauses');
		$type_id = get_the_ID();
		$clauses['where'] .= ' AND (wp_postmeta.meta_value = '.$type_id.')';
		$clauses['orderby'] = 'mt1.meta_value ASC';
		return $clauses;
	}
	add_filter('posts_clauses','type_classes_clauses');

	$classes = new WP_Query(array(
		'post_type' => 'class',
		'meta_query' => array (
			array('key' => 'type'),
			array('key' => 'level')
		)
	));

	if ( $classes->have_posts() ) { ?>
		<section class="teacher-classes">
			<h2 class="page-title">Kursy tego stylu</h2>
			<?php while ($classes->have_posts()) { $classes->the_post();
				get_template_part("classes-item");
			} ?>
		</section>
	<?php }

} ?>
</main>

<?php get_footer(); ?>
