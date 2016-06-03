<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<?php $splash = get_page_by_title('Start');
if ($splash) { ?>
	<script type="text/javascript">
		if (!sessionStorage.splash) {
			document.querySelector(".content").className += " splashy";
		}
	</script>
	<div class="splash" style="background-image: url('<?php echo wp_get_attachment_url(get_post_thumbnail_id($splash->ID)); ?>')">
		<div class="splash-placeholder">
		</div><div class="splash-content">
			<img class="splash-logo" src="<?php bloginfo('template_directory'); ?>/img/logo.png" alt="Salsa Siempre"/>
			<p class="splash-text"><?php echo $splash->post_content; ?></p>
			<a class="splash-dismiss" href="#" tabindex="2"><?php echo get_field('dismiss', $splash->ID); ?></a>
		</div>
	</div>
<?php } ?>

<main role="main">
<?php $classes = new WP_Query(array(
	'post_type' => 'class',
	'meta_key' => 'is_new',
	'meta_value' => 1
));

if ( $classes->have_posts() ) { ?>
	<section class="news-classes">
		<div class="news-classes-inner">
			<h2 class="news-classes-title">NOWE KURSY</h2>
			<?php while ($classes->have_posts()) { $classes->the_post();
				get_template_part("classes-item");
			} ?>
		</div>
	</section><?php
	} wp_reset_postdata();
	if (have_posts()) {
		while (have_posts()) { the_post();
			get_template_part("news-item");
		}
	} else { ?>
		<p class="page-message">
			Brak aktualności.
		</p>
	<?php } ?>
</main>

<?php get_footer(); ?>
