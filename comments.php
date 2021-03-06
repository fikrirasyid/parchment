<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Parchment
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">

	<?php // You can start editing here -- including this comment! ?>

	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
				printf( _nx( 'One thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', get_comments_number(), 'comments title', 'parchment' ),
					number_format_i18n( get_comments_number() ), '<span>' . get_the_title() . '</span>' );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-above" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'parchment' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'parchment' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'parchment' ) ); ?></div>
		</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'      => 'ol',
					'short_ping' => true,
					'avatar_size'=> 60
				) );
			?>
		</ol><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // are there comments to navigate through ?>
		<nav id="comment-nav-below" class="comment-navigation" role="navigation">
			<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'parchment' ); ?></h1>
			<div class="nav-previous"><?php previous_comments_link( __( 'Older Comments', 'parchment' ) ); ?></div>
			<div class="nav-next"><?php next_comments_link( __( 'Newer Comments', 'parchment' ) ); ?></div>
		</nav><!-- #comment-nav-below -->
		<?php endif; // check for comment navigation ?>

	<?php endif; // have_comments() ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && '0' != get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php _e( 'Comments are closed.', 'parchment' ); ?></p>
	<?php endif; ?>

	<?php 
		// Get stored variables
		$commenter 	= wp_get_current_commenter();
		$req      	= get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );

		comment_form( array(
			'title_reply'			=> __( 'Share Your Thought', 'parchment' ),
			'comment_notes_before'	=> false,
			'comment_notes_after' 	=> false,
			'comment_field'        	=> '<p class="comment-form-comment"><textarea id="comment" name="comment" cols="45" rows="8" aria-describedby="form-allowed-tags" aria-required="true" placeholder="'. __( 'Type your comment', 'parchment' ) .'"></textarea></p>',
			'fields'				=> array(
				'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' placeholder="'. __( "Your Name", "parchment" ) .'" /></p>',
				'email'  => '<p class="comment-form-email"><input id="email" name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-describedby="email-notes"' . $aria_req . ' placeholder="'. __( "Your Email", "parchment" ) .'" /></p>',
				'url'    => '<p class="comment-form-url"><label for="url"><input id="url" name="url" type="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" placeholder="'. __( "Your URL", "parchment" ) .'" /></p>',				
			)	
		) ); 
	?>

</div><!-- #comments -->
