<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Boreal_Bees
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header blog-entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
			if ( 'event' === get_post_type() ) :
				?>
				<div class="entry-meta">
					<?php
					the_category( ', ' );
					echo get_the_term_list( get_the_ID(), 'event_category' );
					?>
				</div><!-- .entry-meta -->
				<h1 class="post-entry-title">
					<?php
					the_title();
					?>
				</h1><!-- .entry-title -->
				<h3>
					<?php
					echo get_post_meta( get_the_ID(), 'event_date_box', true );
					?>
				</h3><!-- .entry-date -->
				<div class="post-thumbnail">
					<?php if(has_post_thumbnail()){
						the_post_thumbnail();} ?>
				</div><!-- .entry-thumbnail -->
			<?php endif; 
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		?>
	</header><!-- .entry-header -->

	
	<?php
	if (is_archive(  )) :
			$event_date_box = get_post_meta( get_the_ID(), 'event_date_box', true );
			$event_permalink = esc_url( get_permalink() );
			?>
			<a class="link-no-style" href='<?php echo($event_permalink) ?>' rel="bookmark">
				<article class="event-list-item">
					<div class="event-thumb">
						<?php
							if(has_post_thumbnail()){
								the_post_thumbnail();}
							
						?>
					</div><!-- .entry-thumbnail -->
					<div class="event-info">
						<h2><?php the_title() ?></h2><!-- .entry-title -->
						<h3><?php echo($event_date_box) ?></h3><!-- .entry-date -->
						<div class='content'>
						<?php the_content(
							sprintf(
								wp_kses(
									/* translators: %s: Name of current post. Only visible to screen readers */
									__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'bb' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								wp_kses_post( get_the_title() )
							)
						) ?>
						</div>
					</div>
				</article>
			</a>
		<?php
				
		
		else :
			?>
			<div class="entry-content blog-entry-content">
				<?php the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'bb' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					)
				);

				wp_link_pages(
					array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'bb' ),
						'after'  => '</div>',
					)
				); ?>
			</div><!-- .entry-content -->
		<?php endif;
		
		?>
		
</article><!-- #post-<?php the_ID(); ?> -->
