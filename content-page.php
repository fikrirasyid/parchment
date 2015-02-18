<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package Manuscript
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php
		// Append post thumbnail
		// Use strip tags instead of esc_attr because some plugin such as https://wordpress.org/plugins/subtitles/ 
		// filters get_the_title() output by adding HTML on it
		if( has_post_thumbnail() ){
			echo sprintf( '<a href="%s" class="entry-featured-image" title="%s">', get_permalink( get_the_ID() ), sprintf( __( 'Permanent link to %s', 'manuscript' ), strip_tags( get_the_title() ) ) ); 
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
		<?php edit_post_link( __( 'Edit', 'manuscript' ), '<span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
