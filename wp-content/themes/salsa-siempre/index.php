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
			<?php $days_of_week = array('poniedziałki', 'wtorki', 'środy', 'czwartki', 'piątki', 'soboty', 'niedziele');
			while ($classes->have_posts()) { $classes->the_post(); ?>
				<a href="<?php esc_url(the_permalink()); ?>" class="news-classes-item">
					<div class="news-classes-item-image-wrapper">
						<?php if (get_the_post_thumbnail()) { ?>
							<?php the_post_thumbnail("news-classes-item-image", array("class" => "news-item-image", "alt" => "")); ?>
						<?php } ?>
					</div>
					<div class="news-classes-item-overlay-wrapper">
						<div class="news-classes-item-overlay">
							<h3 class="news-classes-item-title"><?php echo get_field('type')->post_title." ".get_field('level')->post_title; ?></h3>
							<span class="news-classes-item-detail">
								<?php echo $days_of_week[get_field("day_of_week")] ?> <?php the_field("start_hour"); ?>
							</span>

							<?php if (get_field("start_date")) { ?>
							<span class="news-classes-item-detail">start <?php the_field("start_date"); ?></span>
							<?php } ?>

							<span class="news-classes-item-detail">prowadzi <?php echo get_field("teacher_1")->name;
							if (get_field("teacher_2")) { ?> &amp;&nbsp;<?php echo get_field("teacher_2")->name; } ?></span>
						</div>
						<div class="news-classes-item-overlay-caption">Zapisz się!</div>
					</div>
				</a>
			<?php } ?>
		</div>
	</section><?php
	} wp_reset_postdata();
	if (have_posts()) {
		echo "<!--";
		while (have_posts()) { the_post();
			get_template_part("news-item");
		}
		echo "-->";
	} else { ?>
		<p class="page-message">
			Brak aktualności.
		</p>
	<?php } ?>
</main>

<?php get_footer(); ?>
