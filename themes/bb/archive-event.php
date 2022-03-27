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

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="archive-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php
				$args = array(
					'post_type' => 'event'
				);
				$events = new WP_Query( $args );
				if( $events->have_posts() ) {
					while( $events->have_posts() ) {
					$events->the_post();
					$event_date_box = get_post_meta( get_the_ID(), 'event_date_box', true );
					$event_permalink = esc_url( get_permalink() );
					?>
					<a class="link-no-style" href='<?php echo($event_permalink) ?>' rel="bookmark">
						<article class="event-list-item">
							<div class="event-thumb">
								<?php
									if(has_post_thumbnail()){
										the_post_thumbnail();}
									}
								?>
							</div>
							<div class="event-info">
								<h2><?php the_title() ?></h2>
								<h3><?php echo($event_date_box) ?></h3>
								<div class='content'>
								<?php the_content() ?>
								</div>
							</div>
						</article>
					</a>
						
					<?php
					
				}
				else {
					echo 'No events to show.';
				}

			the_posts_navigation();

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>

	</main><!-- #main -->

<?php
// get_sidebar();
get_footer();
