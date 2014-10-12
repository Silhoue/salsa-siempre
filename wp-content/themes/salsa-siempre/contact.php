<?php
/**
 * Template Name: Contact
 * @package Salsa Siempre
 */
?>
<?php get_header(); ?>

<main role="main">
<?php while ( have_posts() ) { the_post(); ?>
	<iframe class="contact-map" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d2433.8209052899965!2d16.936068!3d52.409921999999995!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47045b40cec50b75%3A0xcc387d110318904b!2sSalsa+Siempre+-+szko%C5%82a+ta%C5%84ca!5e0!3m2!1spl!2spl!4v1413130303498" frameborder="0"></iframe>
	<section class="post">
		<header class="post-header">
			<h1 class="post-title"><?php the_title(); ?></h1>
		</header>
		<div class="post-content contact-form">
			<?php the_content(); ?>
		</div>
	</section>
<?php } ?>
</main>

<?php get_footer(); ?>
