<?php
/**
 * The sidebar containing featured products
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Boreal_Bees
 */

if ( ! is_active_sidebar( 'products' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area featured-products-container">
	<div class="featured-products">
	<?php dynamic_sidebar( 'products' ); ?>
	</div>
		
</aside><!-- #secondary -->