<?php
/**
 * @package Salsa Siempre
 */
?>

--><section class="news-item">
	<a href="<?php esc_url(the_permalink()); ?>" rel="bookmark">
		<?php if ( get_the_post_thumbnail() ):
		the_post_thumbnail();
		else: ?>
		<img src="<?php bloginfo('template_directory'); ?>/img/placeholder.png" alt=""/>
		<?php endif; ?>
		<div class="news-item-caption">
			<h2 class="news-item-title"><?php the_title(); ?></h2>
			<?php if ( get_field("data_od") ): ?>
			<span class="news-item-detail">Termin: <?php the_field("data_od");
			    if ( get_field("data_do") ): ?>-<?php the_field("data_do"); endif; ?></span>
			<?php endif; ?>
			<?php if ( get_field("miejsce") ): ?>
			<span class="news-item-detail">Miejsce: <?php the_field("miejsce"); ?></span>
			<?php endif; ?>
		</div>
	</a>
</section><!--
