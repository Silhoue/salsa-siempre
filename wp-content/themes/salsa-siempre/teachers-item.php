<?php
/**
 * @package Salsa Siempre
 */
?>
<section class="news-item">
	<a href="<?php esc_url(the_permalink()); ?>" rel="bookmark">
		<?php if ( get_the_post_thumbnail() ): ?>
		<div class="news-item-image-wrapper">
			<?php the_post_thumbnail("teachers-item-image", array("class" => "news-item-image", "alt" => "")); ?>
		</div>
		<?php endif; ?>
		<h2 class="teachers-item-title"><?php the_title(); ?></h2>
	</a>
</section>