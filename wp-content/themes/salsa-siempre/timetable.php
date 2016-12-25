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
		remove_filter('posts_orderby', 'timetable_orderby');
		return 'wp_postmeta.meta_value ASC, mt1.meta_value ASC, DATE_FORMAT(FROM_UNIXTIME(mt2.meta_value),"%H%i") ASC';
	}
	add_filter('posts_orderby','timetable_orderby');
	query_posts($classes);

	if ( have_posts() ) { ?>
		<div class="timetable-options">
			<?php $levels = new WP_Query(array(
				'post_type' => 'level'
			)); ?>
			<section class="timetable-option">
				<h2 class="timetable-option-title">Oznaczenia poziomów</h2>
				<ul class="timetable-levels">
					<?php while ( $levels->have_posts() ) { $levels->the_post();
						?><li class="timetable-levels-item">
							<span class="timetable-levels-item-color"
								  style="background-color:<?php the_field('color')?>"></span
							><span><?php the_title(); ?></span>
						</li><?php
						} ?>
				</ul>
			</section>

			<section class="timetable-option timetable-filters">
				<h2 class="timetable-option-title">Filtr kursów</h2>
				<input type="radio" name="filter" id="filter-0" checked/>
				<label class="timetable-filters-item" for="filter-0">Wszystkie kursy</label>
				<?php
				$types_qry = "SELECT T.post_title AS title, COUNT(C.id) AS count
						FROM wp_posts T
						LEFT JOIN (
							SELECT P.id AS id, M.meta_value AS type_id
							FROM wp_posts P
							INNER JOIN wp_postmeta M
							ON P.id = M.post_id
							AND P.post_type=\"class\"
							AND P.post_status=\"publish\"
							AND M.meta_key=\"type\"
						) C
						ON T.id = C.type_id
						WHERE T.post_type=\"type\"
						AND T.post_status=\"publish\"
						GROUP BY T.post_title
						ORDER BY COUNT(C.id) DESC";
				$types = $wpdb->get_results( $types_qry );
				?>
				<?php
				$i = 0;
				foreach( $types as $type ) {
					$i+=1; ?>
					<input type="radio" name="filter" id="<?php echo "filter-".$i ?>" data-type="<?php echo $type->title; ?>"/>
					<label class="timetable-filters-item" for="<?php echo "filter-".$i ?>"><?php echo $type->title; ?></label>
				<?php } ?>
			</section>
		</div>

		<?php the_post();
		$days_of_week = array('Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
		$studios = array('Sala 1', 'Sala 2', 'Sala 3');
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
					<?php }
					?><a class="timetable-class<?php if (get_field("is_new")) { echo " class-new"; } ?>"
					   href="<?php esc_url(the_permalink()); ?>"
					   style="background-color:<?php echo get_field('level')->color ?>"
					   data-type="<?php echo $type ?>">
						<span class="timetable-class-title"><?php echo $type.' '.$level ?></span>
						<span class="timetable-class-details">
							<span><?php echo get_field('teacher_1')->name;
								if (get_field("teacher_2")) { ?>
									i <?php echo get_field("teacher_2")->name;
								} ?>
							</span>
							<span class="timetable-class-hours">
								<?php the_field('start_hour'); ?>-<?php the_field('end_hour'); ?>
							</span>
						</span>
					</a><?php
					if (!have_posts()) { break; }
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
