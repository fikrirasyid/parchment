<?php
/**
 * @package Parchment
 */

// hentry separator
get_template_part( 'content', 'hentry-separator' ); 
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
	<?php
		// Append post thumbnail
		// Use strip tags instead of esc_attr because some plugin such as https://wordpress.org/plugins/subtitles/ 
		// filters get_the_title() output by adding HTML on it
		if( has_post_thumbnail() ){
			echo sprintf( '<a href="%s" class="entry-featured-image" title="%s">', get_permalink( get_the_ID() ), sprintf( __( 'Permanent link to %s', 'parchment' ), strip_tags( get_the_title() ) ) ); 
			the_post_thumbnail( 'large' );
			echo '</a>';
		}
	?>

	<header class="entry-header">
		<?php 
			if( ! parchment_is_auto_hide_title() ) :
				the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); 
			endif;
		?>

		<?php if ( 'post' == get_post_type() && 'aside' != get_post_format() ) : ?>
		<div class="entry-meta">
			<?php parchment_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
			the_content( sprintf(
				__( 'Continue Reading', 'parchment' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'parchment' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<?php if ( 'post' == get_post_type() && 'aside' == get_post_format() ) : ?>
	<div class="entry-meta">
		<?php parchment_posted_on(); ?>
	</div><!-- .entry-meta -->
	<?php endif; ?>	
</article><!-- #post-## -->