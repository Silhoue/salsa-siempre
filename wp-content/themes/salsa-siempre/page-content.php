<?php
/**
 * @package Salsa Siempre
 */
?>
<div>
	<?php if (get_the_post_thumbnail()) {
		the_post_thumbnail("large", array("class" => "post-image", "alt" => ""));
	} ?>

	<article class="post">
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
		</header>
		<div class="post-content">
			<?php the_content(); ?>
		</div>
	</article>
</div>
