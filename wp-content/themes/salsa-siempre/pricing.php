<?php
/**
 * Template Name: Pricing
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) { the_post();
	get_template_part("page-content");
}
$packages = array(
	'post_type' => 'package',
	'orderby' => 'meta_value',
	'meta_key' => 'type',
	'order' => 'ASC'
);
query_posts($packages);
if ( have_posts() ) { ?>
	<section>
	<?php $type = -1; $types = array('Karnety podstawowe', 'Oferty specjalne');
	echo "<!--";
	while ( have_posts() ) { the_post();
		if (get_field("type") != $type) {
			$type = get_field("type");
			echo "-->"; ?>
			</section>
			<section>
				<h2 class="page-title"><?php echo $types[$type] ?></h2>
			<?php
			echo "<!--";
		}
		get_template_part("package");
	}
	echo "-->"; ?>
	</section>
<?php } ?>
</main>

<?php get_footer(); ?>
