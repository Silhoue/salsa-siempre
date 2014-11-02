<?php
/**
 * @package Salsa Siempre
 */
?>

<?php get_header(); ?>

<main role="main">
<?php while (have_posts()) { the_post();
	$days_of_week = array('poniedziałki', 'wtorki', 'środy', 'czwartki', 'piątki', 'soboty', 'niedziele');
	$studios = array('Sala&nbsp1', 'Sala&nbsp2');

	if (get_the_post_thumbnail()) { ?>
		<div class="post-image-wrapper">
		<?php the_post_thumbnail("large", array("class" => "post-image", "alt" => "")); ?>
		</div>
	<?php } ?>
	<article class="post <?php if (get_field("is_new")) { echo "class-new"; } ?>">
		<header class="post-header">
			<h1 class="post-title"><?php echo get_field('type')->post_title." ".get_field('level')->post_title ?></h1>

			<span class="post-detail">Termin: <span class="post-detail-value"><?php
				echo $days_of_week[get_field("day_of_week")] ?>, <?php the_field("start_hour"); ?>&nbsp;-&nbsp;<?php the_field("end_hour"); ?> (<?php echo $studios[get_field("place")]; ?>)</span></span>

			<span class="post-detail">Instruktorzy: <span class="post-detail-value"><?php echo get_field("teacher_1")->post_title;
				if (get_field("teacher_2")) { ?> &amp;&nbsp;<?php echo get_field("teacher_2")->post_title; } ?></span></span>

			<?php if (get_field("start_date")) { ?>
				<span class="post-detail">Data rozpoczęcia: <span class="post-detail-value"><?php the_field("start_date"); ?></span></span>
			<?php } ?>
		</header>

		<div class="post-content">
			<?php the_content(); ?>
		</div>

		<?php $registration = get_page_by_title('Zapisy');
		if ($registration) { ?>
			<a class="post-action" href="<?php echo get_page_link($registration->ID) ?>">Zapisz się!</a>
		<?php } ?>
	</article>

<?php } ?>
</main>

<?php get_footer(); ?>
