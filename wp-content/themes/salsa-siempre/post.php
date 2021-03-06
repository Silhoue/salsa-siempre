<?php
/**
 * @package Salsa Siempre
 */

if (get_the_post_thumbnail()) {
	get_template_part("post-image");
}
?><article class="post">
	<header class="post-header">
		<h1 class="post-title"><?php the_title(); ?></h1>
		<?php if (get_field("start_date")) { ?>
		<span class="post-detail">Data: <span class="post-detail-value"><?php the_field("start_date");
			if (get_field("end_date")) { ?>&nbsp;-&nbsp;<?php the_field("end_date"); } ?></span></span>
		<?php }
		if (get_field("start_time")) { ?>
		<span class="post-detail">Godzina: <span class="post-detail-value"><?php the_field("start_time");
			if (get_field("end_time")) { ?>&nbsp;-&nbsp;<?php the_field("end_time"); } ?></span></span>
		<?php }
		if (get_field("place")) { ?>
		<span class="post-detail">Miejsce:&nbsp;<span class="post-detail-value"><?php the_field("place"); ?></span></span>
		<?php } ?>
	</header>

	<div class="post-content">
		<?php the_content(); ?>
	</div>

	<div class="post-footer">
		Ostatnia aktualizacja: <?php the_modified_date(); ?>
	</div>
</article>
