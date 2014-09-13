<?php
/**
 * @package Salsa Siempre
 */
?>

--><section class="news-item">
	<a href="<?php esc_url(the_permalink()); ?>" rel="bookmark">
		<?php if ( get_the_post_thumbnail() ): ?>
		<div class="news-item-image-wrapper">
			<?php the_post_thumbnail("news-item-image", array("class" => "news-item-image", "alt" => "")); ?>
		</div>
		<?php endif; ?>
		<div class="news-item-caption">
			<h2 class="news-item-title"><?php the_title(); ?></h2>
			<?php if ( get_field("data_od") ): ?>
			<span class="news-item-detail">Termin:&nbsp;<?php the_field("data_od");
				if ( get_field("data_do") ): ?> - <?php the_field("data_do"); endif; ?></span>
			<?php endif; ?>
			<?php if ( get_field("miejsce") ): ?>
			<span class="news-item-detail">Miejsce:&nbsp;<?php the_field("miejsce"); ?></span>
			<?php endif; ?>
		</div>
	</a>
</section><!--
