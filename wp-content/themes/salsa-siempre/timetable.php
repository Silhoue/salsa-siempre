<?php
/**
 * Template Name: Timetable
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
	<h1 class="page-title">Grafik</h1>
	<?php
	$classes = array(
		'post_type' => 'class',
		'meta_query' => array(
			array('key' => 'day_of_week'),
			array('key' => 'studio'),
			array('key' => 'start_hour')
		)
	);
	function timetable_orderby($orderby) {
		return 'wp_postmeta.meta_value ASC, mt1.meta_value ASC, mt2.meta_value ASC';
	}
	add_filter('posts_orderby','timetable_orderby');
	query_posts($classes);
	remove_filter('posts_orderby','timetable_orderby');

	if ( have_posts() ) {

		$levels = new WP_Query(array(
			'post_type' => 'level',
			'orderby' => 'title',
			'order' => 'asc'
		)); ?>
		<section class="timetable-levels">
			<h2 class="timetable-levels-title">Oznaczenia poziomów</h2>
			<ul class="timetable-levels-items">
				<?php while ( $levels->have_posts() ) { $levels->the_post(); ?><!--
				 --><li class="timetable-levels-item">
						<span class="timetable-levels-item-color"
							  style="background-color:<?php the_field('color')?>"></span>
						<span><?php the_title(); ?></span>
					</li><!--
				--><?php }
				wp_reset_postdata(); ?>
			</ul>
		</section>

		<?php the_post();
		$days_of_week = array('Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
		$studios = array('Sala 1', 'Sala 2');
		for ($day_of_week = 0; $day_of_week < 7; $day_of_week++) { $studio = -1; ?>
			<section class="timetable-day">
				<h2 class="timetable-day-title">
					<?php echo $days_of_week[$day_of_week]; ?>
				</h2>
				<?php if ((get_field('day_of_week') != $day_of_week)) { ?>
					<span class="timetable-day-empty">Aktualnie brak zajęć</span>
				<?php } else do {
					$type = get_field('type')->post_title;
					$level = get_field('level')->post_title;
					if (get_field('studio') != $studio) {
						$studio = get_field('studio'); ?>
						<h3 class="timetable-studio-title">
							<?php echo $studios[$studio]; ?>
						</h3>
					<?php }	?>
					<a class="timetable-class"
					   href="<?php esc_url(the_permalink()); ?>"
					   style="background-color:<?php echo get_field('level')->color ?>">
						<span class="timetable-class-title"><?php echo $type.' '.$level ?></span>
						<span class="timetable-class-details">
							<span><?php echo get_field('teacher_1')->name;
								if (get_field("teacher_2")) { ?>
									&amp; <?php echo get_field("teacher_2")->name;
								} ?>
							</span>
							<span class="timetable-class-hours">
								<?php the_field('start_hour'); ?>-<?php the_field('end_hour'); ?>
							</span>
						</span>
					</a>
					<?php if (!have_posts()) { break; }
					the_post();
				} while (get_field('day_of_week') == $day_of_week); ?>
			</section>

		<?php }
	} else { ?>
		<p class="page-message">
			Brak zajęć.
		</p>
	<?php } ?>
</main>

<?php get_footer(); ?>
