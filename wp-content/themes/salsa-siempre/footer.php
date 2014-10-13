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
			<section class="footer-item">
				<h1 class="footer-item-title">Godziny otwarcia</h1>
				<span class="footer-item-content">STUDIO TAŃCA SALSA SIEMPRE jest otwarte przez cały okres wakacji!!!
				</span>
			</section><section class="footer-item">
				<h1 class="footer-item-title">Dane kontaktowe</h1>
				<span class="footer-item-content">Rafał Rosiak
					ul. Dominikańska 7A
					61-762 Poznań
					tel. +48 501 297 377
					tel. +48 537 733 077
					rafal@salsasiempre.pl
				</span>
			</section><section class="footer-item">
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
						<?php echo "<!--";
						while (have_posts()) { the_post();
							get_template_part("partner");
						} echo "-->"; ?>
					</ul>
				</section>
			<?php } ?>
		</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
