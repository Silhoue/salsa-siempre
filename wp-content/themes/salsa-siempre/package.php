<?php
/**
 * @package Salsa Siempre
 */
?>

--><section class="package">
	<?php if (get_the_post_thumbnail())  { ?>
		<?php the_post_thumbnail("medium", array("class" => "news-item-image", "alt" => "")); ?>
	<?php } ?>
	<div class="package-caption">
		<h2 class="package-title"><?php the_title();
			if (get_field("price")) { ?>&nbsp;-&nbsp;<?php the_field("price");?>zł<?php } ?>
		</h2>
		<div class="package-content"><?php the_content(); ?></div>
	</div>
</section><!--
