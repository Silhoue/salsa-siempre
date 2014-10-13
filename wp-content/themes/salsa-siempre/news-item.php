<?php
/**
 * @package Salsa Siempre
 */
?>

--><section class="news-item">
	<a href="<?php esc_url(the_permalink()); ?>" rel="bookmark">
		<?php if (get_the_post_thumbnail()) { ?>
		<div class="news-item-image-wrapper">
			<?php the_post_thumbnail("news-item-image", array("class" => "news-item-image", "alt" => "")); ?>
		</div>
		<?php } ?>
		<div class="news-item-caption">
			<h2 class="news-item-title"><?php the_title(); ?></h2>
			<?php if (get_field("start_date")) { ?>
				<span class="news-item-detail">Termin:&nbsp;<?php the_field("start_date");
				if (get_field("end_date")) { ?> - <?php the_field("end_date"); } ?></span>
			<?php }
			if (get_field("place")) { ?>
				<span class="news-item-detail">Miejsce:&nbsp;<?php the_field("place"); ?></span>
			<?php } ?>
		</div>
	</a>
</section><!--
