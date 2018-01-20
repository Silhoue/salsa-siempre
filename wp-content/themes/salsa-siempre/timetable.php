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
				$types_qry = 'SELECT
						Type.title,
						Target.post_title AS target,
						COUNT(Class.id) AS class_count
					FROM (
						SELECT Post.id AS id, Post.post_title AS title, Meta.meta_value AS target_id
						FROM wp_posts Post
						INNER JOIN wp_postmeta Meta
						ON Post.id = Meta.post_id
							AND Post.post_type="type"
							AND Post.post_status="publish"
							AND Meta.meta_key="target"
					) Type
					LEFT JOIN wp_posts Target
					ON Target.id = Type.target_id
						AND Target.post_type="target"
						AND Target.post_status="publish"
					LEFT JOIN (
						SELECT Post.id AS id, Meta.meta_value AS type_id
						FROM wp_posts Post
						INNER JOIN wp_postmeta Meta
						ON Post.id = Meta.post_id
							AND Post.post_type="class"
							AND Post.post_status="publish"
							AND Meta.meta_key="type"
					) Class
					ON Type.id = Class.type_id
					GROUP BY Type.title
					ORDER BY Target.menu_order, class_count DESC';
				$types = $wpdb->get_results( $types_qry );
				?>
				<?php
				$i = 0;
				$target = null;
				foreach( $types as $type ) {
					$i+=1;
					if ($type->target != $target) {
						$target = $type->target ?>
						<h3 class="timetable-filters-title"><?php echo $type->target ?></h3>
					<?php } ?>
					<input type="radio" name="filter" id="<?php echo "filter-".$i ?>" data-type="<?php echo $type->title; ?>"/>
					<label class="timetable-filters-item" for="<?php echo "filter-".$i ?>"><?php echo $type->title." (".$type->class_count.")"; ?></label>
				<?php } ?>
			</section>
		</div>

		<?php the_post();
		$days_of_week = array('Poniedziałek', 'Wtorek', 'Środa', 'Czwartek', 'Piątek', 'Sobota', 'Niedziela');
		$studios = array('Sala 1', 'Sala 2', 'Sala 3', 'Sala 4');
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
					$start_date = substr(get_field('start_date'), 0, 5);
					if (get_field('studio') != $studio) {
						$studio = get_field('studio');
						?><h3 class="timetable-studio-title"><?php echo $studios[$studio]; ?></h3><?php
					}
					?><a class="timetable-class" href="<?php esc_url(the_permalink()); ?>"
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
						<?php if (get_field("is_new")) { ?>
							<div class="timetable-class-new">
								Nowy kurs<?php if ($start_date) echo " - od ".$start_date ?>
							</div>
						<?php } ?>
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
