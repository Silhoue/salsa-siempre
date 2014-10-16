<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Salsa Siempre
 */
?>

	</div><!-- .content -->

	<footer class="footer" role="contentinfo">
		<div class="footer-inner">
			<?php $footer_items = array(
				'post_type' => 'footer-item'
			);
			query_posts($footer_items);
			if (have_posts()) { ?>
				<?php while (have_posts()) { the_post();
				?><section class="footer-item">
					<h1 class="footer-item-title"><?php the_title(); ?></h1>
					<?php the_content(); ?>
				</section><?php
				} ?>
			<?php }
			?><section class="footer-item">
				<h1 class="footer-item-title">Social media</h1>
				<ul class="footer-item-media-items">
					<li class="footer-item-media-item footer-item-media-item-fb">
						<a href="https://www.facebook.com/salsa.siempre.9"></a>
					</li
					><li class="footer-item-media-item footer-item-media-item-gp">
						<a href="https://plus.google.com/+salsasiemprepltaniec"></a>
					</li
					><li class="footer-item-media-item footer-item-media-item-yt">
						<a href="https://www.youtube.com/user/salsasiempre1"></a>
					</li>
				</ul>
			</section><!-- <section class="footer-item">
				<h1 class="footer-item-title">Newsletter</h1>
					<span class="footer-item-content">Chcesz być na bieżąco informowany o naszych wydarzeniach?

						Wpisz się na listę:
						<input type="email" placeholder="Twój adres e-mail" class="footer-newsletter-input" />
						<button type="submit" class="footer-newsletter-submit">Wyślij</button>
					</span>
			</section> -->
			<?php $partners = array(
				'post_type' => 'partner'
			);
			query_posts($partners);
			if (have_posts()) { ?>
				<section class="footer-item-partners">
					<h1 class="footer-item-title footer-item-partners-title">Partnerzy</h1>
					<ul class="footer-item-partners-items">
						<?php while (have_posts()) { the_post();
							?><li class="footer-item-partners-item">
								<a href="<?php the_field("link"); ?>">
									<?php the_post_thumbnail("thumbnail", array("alt" => get_the_title())); ?>
								</a>
							</li><?php
						} ?>
					</ul>
				</section>
			<?php } ?>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
