<?php
/**
 * @package Salsa Siempre
 */
?>
<a href="<?php esc_url(the_permalink()); ?>" class="classes-item">
	<div class="classes-item-image-wrapper">
		<?php if (get_the_post_thumbnail()) { ?>
			<?php the_post_thumbnail("classes-item-image", array("class" => "classes-item-image", "alt" => "")); ?>
		<?php } ?>
	</div>
	<div class="classes-item-overlay-wrapper">
		<div class="classes-item-overlay">
			<h3 class="classes-item-title"><?php echo get_field('type')->post_title." ".get_field('level')->post_title; ?></h3>
			<span class="classes-item-detail">
				<?php $days_of_week = array('poniedziałki', 'wtorki', 'środy', 'czwartki', 'piątki', 'soboty', 'niedziele');
				echo $days_of_week[get_field("day_of_week")] ?> <?php the_field("start_hour"); ?>
			</span>

			<?php if (get_field("start_date")) { ?>
			<span class="classes-item-detail">start <?php the_field("start_date"); ?></span>
			<?php } ?>

			<span class="classes-item-detail">prowadzi <?php echo get_field("teacher_1")->name;
			if (get_field("teacher_2")) { ?> i&nbsp;<?php echo get_field("teacher_2")->name; } ?></span>
		</div>
		<div class="classes-item-overlay-caption">Zapisz się!</div>
	</div>
</a>