<?php
/**
 * The sidebar containing social icons
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boreal_Bees
 */

if ( ! is_active_sidebar( 'social' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<?php dynamic_sidebar( 'social' ); ?>
</aside><!-- #secondary -->