<?php
/**
 * Template Name: Studio
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) { the_post();
	get_template_part("page-content");
}
$classes = new WP_Query(array(
	'post_type' => 'class',
	'meta_key' => 'is_new',
	'meta_value' => 1
));

if ( $classes->have_posts() ) { ?>
	<section class="teacher-classes">
		<h2 class="page-title">Kliknij na wybrany kurs i zapisz się!</h2>
		<?php while ($classes->have_posts()) { $classes->the_post();
			get_template_part("classes-item");
		} ?>
	</section>
<?php } ?>
</main>

<?php get_footer(); ?>
