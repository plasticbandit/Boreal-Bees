<?php
/**
 * The template for displaying events page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Boreal_Bees
 */

get_header();
?>
<?php if( function_exists( 'aioseo_breadcrumbs' ) ) aioseo_breadcrumbs(); ?><!-- .breadcrumbs -->

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
			/* Start the Loop */
			$args = array(
				'post_type' => 'event'
			);
			// get events type post
			$events = new WP_Query( $args );
			if( $events->have_posts() ) :
				while( $events->have_posts() ) :
				$events->the_post();
				

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content-event', get_post_type() );
				

				endwhile;

				the_posts_navigation();

			endif;
				
		else :

			get_template_part( 'template-parts/content', 'none' );
			echo 'No events to show.';

		endif;
		?>

	</main><!-- #main -->

<?php
get_footer();
