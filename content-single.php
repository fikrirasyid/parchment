<?php
/**
 * @package Manuscript
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php 
			if( ! manuscript_is_auto_hide_title() ) :
				the_title( '<h1 class="entry-title">', '</h1>' ); 
			endif;
		?>

		<div class="entry-meta">
			<?php manuscript_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php
		// Append post thumbnail
		if( has_post_thumbnail() ){
			echo sprintf( '<a href="%s" class="entry-featured-image" title="%s">', get_permalink( get_the_ID() ), sprintf( __( 'Permanent link to %s', 'manuscript' ), get_the_title() ) ); 
			the_post_thumbnail( 'large' );
			echo '</a>';
		}
	?>	

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'manuscript' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php manuscript_entry_footer(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
