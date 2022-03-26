<?php
/**
 * Template Name: Home Page
 * 
 * The template for displaying home page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Boreal_Bees
 */

get_header();
?>

	<main id="primary" class="site-main site-home">
	<!-- <section class="home-banner">
		<?php the_post_thumbnail( ) ?>
	</section> -->

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content-home', 'page' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
    
<!-- insert social widgets -->
<?php
get_sidebar( 'products' );
get_footer();
