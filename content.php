<?php
/**
 * @package Manuscript
 */

// hentry separator
get_template_part( 'content', 'hentry-separator' ); 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php
		// Append post thumbnail
		if( has_post_thumbnail() ){
			echo sprintf( '<a href="%s" class="entry-featured-image" title="%s">', get_permalink( get_the_ID() ), sprintf( __( 'Permanent link to %s', 'manuscript' ), strip_tags( get_the_title() ) ) ); 
			the_post_thumbnail( 'large' );
			echo '</a>';
		}
	?>

	<header class="entry-header">
		<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php manuscript_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content( sprintf(
				__( 'Continue Reading', 'manuscript' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'manuscript' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->