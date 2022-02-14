<?php
/**
 * The sidebar containing connect options
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boreal_Bees
 */

if ( ! is_active_sidebar( 'connect' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area connect-area">
	<div class="connect-container">
		<div class="email-connect">
			<p>&#x2709; Join our mailing list</p>
			<input class="footer-email-input" placeholder="Your email" />
			<button class="footer-email-button">Go</button>
		</div>
		<?php dynamic_sidebar( 'connect' ); ?>
	</div>
</aside><!-- #secondary -->