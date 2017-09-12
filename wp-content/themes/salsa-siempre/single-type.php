<?php
/**
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) {
	the_post(); ?>
	<div>
		<?php if (get_the_post_thumbnail()) {
			get_template_part("post-image");
		}
		?><article class="post">
			<header class="post-header">
				<h1 class="post-title"><?php the_title(); ?></h1>
			</header>
			<?php $registration = get_page_by_title('Zapisy');
			if ($registration) { ?>
				<a class="post-action" href="<?php echo get_page_link($registration->ID) ?>">Zapisz się!</a>
			<?php } ?>
			<div class="post-content">
				<?php the_content(); ?>
			</div>
		</article>
	</div>
	<?php
	function type_classes_clauses($clauses) {
		remove_filter('posts_clauses', 'type_classes_clauses');
		$clauses['join'] .= 'INNER JOIN wp_posts AS L ON L.ID = mt1.meta_value';
		$clauses['orderby'] = 'L.menu_order ASC';
		return $clauses;
	}
	add_filter('posts_clauses','type_classes_clauses');

	$classes = new WP_Query(array(
		'post_type' => 'class',
		'meta_query' => array (
			array(
				'key' => 'type',
				'value' => get_the_ID()
			),
			array('key' => 'level')
		)
	));

	if ( $classes->have_posts() ) { ?>
		<section class="teacher-classes">
			<h2 class="page-title">Kliknij na wybrany kurs i zapisz się!</h2>
			<?php while ($classes->have_posts()) { $classes->the_post();
				get_template_part("classes-item");
			} ?>
		</section>
	<?php }

} ?>
</main>

<?php get_footer(); ?>
