<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boreal_Bees
 */

?>

	<footer id="colophon" class="site-footer">
		<div class="social-content">
			<p><i class="fa fa-regular fa-envelope-open"></i> Join our mailing list</p>
			<input class="footer-email-input" placeholder="Your email" />
			<button class="footer-email-button">Go</button>
			<div id="social-footer">
			<?php
				get_sidebar( 'social');
			?>
			</div>
		</div>
		<div class="footer-icon-container">
		<img class="footer-logo-icon" src="<?php site_icon_url()?>" alt="Boreal Bees" />
		</div><!--site icon -->
		<div class="site-info-container">
			<nav id="site-navigation" class="main-navigation">
				<?php
				wp_nav_menu(
							array(
								'theme_location' => 'footer-menu',
								'menu_id'        => 'footer-menu',
							)
						);
						?>
			</nav>
			<div class="footer-credits">
				<?php
					/* translators: 1: Theme name, 2: Theme author. */
					printf( esc_html__( 'Copyright %2$s 2022.', 'bb' ), 'bb', '<a target="_blank" href="https://nicolamaydesign.ca">Nicola McFadden</a>' );
					?>
				<span class="sep"> | </span>
				<a target="_blank" href="<?php echo esc_url( __( 'https://wordpress.org/', 'bb' ) ); ?>">
					<?php
					/* translators: %s: CMS name, i.e. WordPress. */
					printf( esc_html__( 'Powered by %s.', 'bb' ), 'WordPress' );
					?>
				</a>
			</div><!-- footer-credits -->			
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
