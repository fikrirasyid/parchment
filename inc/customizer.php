<?php
/**
 * Manuscript Theme Customizer
 *
 * @package Manuscript
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function manuscript_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'background_color' )->transport  = 'postMessage';

	// Adding link color control
	$wp_customize->add_setting( 'link_color', array(
		'default'           => '#000000',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_color', array(
		'label'       => esc_html__( 'Link color', 'manuscript' ),
		'description' => esc_html__( 'Select color for your link', 'manuscript' ),
		'section'     => 'colors',
	) ) );	

	// Adding text color control
	$wp_customize->add_setting( 'text_color', array(
		'default'           => '#3B3B3B',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'			=> 'postMessage'
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_color', array(
		'label'       => esc_html__( 'Text color', 'manuscript' ),
		'description' => esc_html__( 'Select color for your text', 'manuscript' ),
		'section'     => 'colors',
	) ) );	
}
add_action( 'customize_register', 'manuscript_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function manuscript_customize_preview_js( $wp_customize ) {
	wp_enqueue_script( 'manuscript_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20150220', true );

	// Default params
	$manuscript_customizer_params = array(
		'generate_color_scheme_endpoint' 		=> esc_url( admin_url( 'admin-ajax.php?action=manuscript_generate_customizer_color_scheme' ) ),
		'generate_color_scheme_error_message' 	=> __( 'Error generating color scheme. Please try again.', 'manuscript' ),
		'clear_customizer_settings'				=> esc_url( admin_url( 'admin-ajax.php?action=manuscript_clear_customizer_settings' ) )		
	);

	// Adding proper error message when customizer fails to generate color scheme in live preview mode (theme hasn’t been activated). 
	// The color scheme is generated using wp_ajax and wp_ajax cannot be registered if the theme hasn’t been activated.
	if( ! $wp_customize->is_theme_active() ){
		$manuscript_customizer_params['generate_color_scheme_error_message'] = __( 'Color scheme cannot be generated. Please activate Materialist theme first.', 'manuscript' );
	}

	// Attaching variables
	wp_localize_script( 'manuscript_customizer', 'manuscript_customizer_params', apply_filters( 'manuscript_customizer_params', $manuscript_customizer_params ) );

	// Display color scheme previewer
	$color_scheme = get_theme_mod( 'background_color_scheme_customizer', false );

	if( $color_scheme ){
		remove_action( 'wp_enqueue_scripts', 'manuscript_color_scheme' );

		/**
		 * Reload default style, wp_add_inline_style fail when the handle doesn't exist 
		 */
		wp_enqueue_style( 'manuscript-style', get_stylesheet_uri() );
		$inline_style = wp_add_inline_style( 'manuscript-style', $color_scheme );
	}			
}
add_action( 'customize_preview_init', 'manuscript_customize_preview_js' );

/**
 * WordPress' native sanitize_hex_color seems to be hasn't been loaded
 * Provide theme's customizer with its own hex color sanitation
 */
if( ! function_exists( 'manuscript_sanitize_hex_color' ) ) :
function manuscript_sanitize_hex_color( $color ){
	if ( '' === $color ){
		return '';	
	}

	// If given string has no hash, auto add hash
	if( '#' != substr( $color, 0, 1 ) ){
		$color = '#' . $color;
	}

	// 3 or 6 hex digits, or the empty string.
	if ( preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) )
		return $color;

	return null;
}
endif;

if ( ! function_exists( 'manuscript_sanitize_hex_color_no_hash' ) ) :
function manuscript_sanitize_hex_color_no_hash( $color ){
	$color = ltrim( $color, '#' );

	if ( '' === $color )
		return '';

	return manuscript_sanitize_hex_color( '#' . $color ) ? $color : null;	
}
endif;

/**
 * Generate color scheme based on color given
 * 
 * @uses Manuscript_Simple_Color_Adjuster
 */
if( ! function_exists( 'manuscript_generate_color_scheme_css' ) ) :
function manuscript_generate_color_scheme_css( $color, $mode = false ){
	
	// If given string has no hash, auto add hash
	if( '#' != substr( $color, 0, 1 ) ){
		$color = '#' . $color;
	}

	// Verify color
	if( ! manuscript_sanitize_hex_color( $color ) ){
		return false;
	}

	$simple_color_adjuster = new Manuscript_Simple_Color_Adjuster;

	switch ( $mode ) {
		case 'background_color':

			$css = "
				.rtl .entry-content blockquote,
				.rtl .comment-body blockquote{
					border-right: 2px solid " . $simple_color_adjuster->darken( $color, 20 ) . ";	
				}

				body {
					background: {$color}; /* Fallback for when there is no custom background color defined. */
					position: relative;
				}

				body.not-touch-device:after{
					background: -moz-linear-gradient(top, rgba(0,0,0,0) 0%, {$color} 100%); /* FF3.6+ */
					background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(0,0,0,0)), color-stop(100%,{$color})); /* Chrome,Safari4+ */
					background: -webkit-linear-gradient(top, rgba(0,0,0,0) 0%,{$color} 100%); /* Chrome10+,Safari5.1+ */
					background: -o-linear-gradient(top, rgba(0,0,0,0) 0%,{$color} 100%); /* Opera 11.10+ */
					background: -ms-linear-gradient(top, rgba(0,0,0,0) 0%,{$color} 100%); /* IE10+ */
					background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,{$color} 100%); /* W3C */
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#00000000', endColorstr='#a6000000',GradientType=0 ); /* IE6-9 */		
				}

				input[type='text'],
				input[type='email'],
				input[type='url'],
				input[type='password'],
				input[type='search'],
				textarea {
					border-bottom: 1px solid " . $simple_color_adjuster->darken( $color, 20 ) . ";
				}

				.entry-content ol.footnotes:before{
					background: " . $simple_color_adjuster->darken( $color, 10 ) . ";
				}

				.entry-featured-image{
					box-shadow: 0 2px 2px " . $simple_color_adjuster->darken( $color, 20 ) . ";
				}

				@media screen and ( max-width: 640px ){
					.entry-featured-image{
						box-shadow: 0 2px 2px " . $simple_color_adjuster->darken( $color, 20 ) . ";
					}
				}

				.drawer-content{
					background: " . $simple_color_adjuster->lighten( $color, 10 ) . ";
					box-shadow: 1px 0 10px " . $simple_color_adjuster->darken( $color, 10 ) . ";
				}

				.drawer-content .drawer-header{
					border-bottom: 1px solid " . $simple_color_adjuster->darken( $color, 10 ) . ";
				}

				.drawer-content .drawer-navigation{
					border-bottom: 1px solid " . $simple_color_adjuster->darken( $color, 10 ) . ";	
				}

				.drawer-content .drawer-widgets .widget{
					border-bottom: 1px solid " . $simple_color_adjuster->darken( $color, 10 ) . ";	
				}

				.drawer-overlay{
					background: {$color};
				}

				@media screen and ( max-width: 783px ){
					body #toggle-drawer{
						background: {$color};
						border: 1px solid " . $simple_color_adjuster->darken( $color, 20 ) . ";			
					}
				}

				.entry-content blockquote,
				.comment-body blockquote{
					border-left: 2px solid " . $simple_color_adjuster->darken( $color, 20 ) . ";	
				}

				.entry-content table thead td,
				.comment-body table thead td{
					background: " . $simple_color_adjuster->lighten( $color, 5 ) . ";			
				}

				.comment-body img,
				.entry-content img{
					box-shadow: 0 2px 2px " . $simple_color_adjuster->darken( $color, 20 ) . ";
				}

				#cancel-comment-reply-link:hover,
				a.comment-reply-link:hover,
				a.more-link:hover,,
				.entry-footer .post-edit-link:hover{
					color: {$color};
				}
			";

			break;

		case 'text_color':
			$css = "
				.wp-caption-text {
					color: " . $simple_color_adjuster->lighten( $color, 30 ) .";
				}

				.comment-list .comment-metadata a{
					color: {$color};
				}

				.hentry-day {
					color: " . $simple_color_adjuster->lighten( $color, 30 ) .";
				}

				.entry-title .entry-subtitle{
					color: {$color};
				}

				.entry-meta{
					color: " . $simple_color_adjuster->lighten( $color, 30 ) .";
				}

				.entry-meta a{
					color: " . $simple_color_adjuster->lighten( $color, 30 ) .";
				}

				#toggle-drawer{
					color: {$color};
				}

				.widget_rss li .rsswidget:after{
					background: " . $simple_color_adjuster->lighten( $color, 30 ) .";
				}

				.widget_rss	.rss-date,
				.widget_rss .rssSummary{
					color: " . $simple_color_adjuster->lighten( $color, 30 ) .";
				}


				.entry-content blockquote,
				.comment-body blockquote {
					color: " . $simple_color_adjuster->lighten( $color, 20 ) .";
				}

				.entry-content table,
				.comment-body table{
					border-top: 1px solid " . $simple_color_adjuster->lighten( $color, 30 ) .";
				}

				.entry-content table tr td,
				.comment-body table tr td,
				.entry-content table th td,
				.comment-body table th td{
					border-bottom: 1px solid " . $simple_color_adjuster->lighten( $color, 30 ) .";	
				}

				body,
				button,
				input,
				select,
				textarea {
					color: {$color};
				}

				#cancel-comment-reply-link,
				a.comment-reply-link,
				a.more-link,
				.entry-footer .post-edit-link{
					color: {$color};
					border: 1px solid {$color};
				}

				#cancel-comment-reply-link:hover,
				a.comment-reply-link:hover,
				a.more-link:hover,
				.entry-footer .post-edit-link:hover{
					background: {$color};
				}
			";
			break;
		
		case 'link_color':

			$css = "
				button,
				input[type='button'],
				input[type='reset'],
				input[type='submit'] {
					background: {$color};
					
					&:focus,
					&:active{
						outline: none;
						background: " . $simple_color_adjuster->darken( $color, 20 ) .";
					}
				}

				button:focus,
				input[type='button']:focus,
				input[type='reset']:focus,
				input[type='submit']:focus,
				button:active,
				input[type='button']:active,
				input[type='reset']:active,
				input[type='submit']:active{
					background: " . $simple_color_adjuster->lighten( $color, 10 ) .";		
				}

				#cancel-comment-reply-link:active,
				a.comment-reply-link:active,
				a.more-link:active,
				.entry-footer .post-edit-link:active{
					background: {$color};	
				}

				a {
					color: {$color};
				}

				.site-description{
					color: {$color};
				}

				.comment-list li.comment.bypostauthor > .comment-body .avatar{
					border: 3px solid {$color};
				}

				.comment-list li.comment.bypostauthor > .comment-body .fn:after{
					color: {$color};
				}

				.drawer-content .drawer-header{
					color: {$color};	
				}

				.widget .widgettitle,	
				.widget .widget-title{
					color: {$color};
				}

				h1, h2, h3, h4, h5, h6 {
					color: {$color};
				}

				.entry-content h3,
				.comment-body h3{
					border-bottom: 1px dotted " . $simple_color_adjuster->lighten( $color, 70 ) .";	
				}
			";

			break;

		default:

			$css = false;

			break;
	}

	return $css;
}
endif;

/**
 * AJAX endpoint for generating color scheme in near real time for customizer
 */
if( ! function_exists( 'manuscript_generate_customizer_color_scheme' ) ) :
function manuscript_generate_customizer_color_scheme(){

	if( current_user_can( 'customize' ) ){

		// Determine color key
		if( isset( $_GET['background_color'] ) && manuscript_sanitize_hex_color_no_hash( $_GET['background_color'] ) ){
			$prefix = 'background';
		} elseif( isset( $_GET['text_color'] ) && manuscript_sanitize_hex_color_no_hash( $_GET['text_color'] ) ){
			$prefix = 'text';
		} elseif( isset( $_GET['link_color'] ) && manuscript_sanitize_hex_color_no_hash( $_GET['link_color'] ) ){
			$prefix = 'link';
		} else {
			$prefix = false;
		}

		// Saving color
		if( $prefix ){

			$key = $prefix . '_color';

			// Get color
			$color = manuscript_sanitize_hex_color_no_hash( $_GET[ $key ] );

			if( $color ){

				$color = '#' . $color;

				// Generate color scheme css
				$css = manuscript_generate_color_scheme_css( $color, $key );

				// Set Color Scheme
				set_theme_mod( $key . '_scheme_customizer', $css );

				$generate = array( 'status' => true, 'colorscheme' => $css );

			} else {

				$generate = array( 'status' => false, 'colorscheme' => false );

			}

		} else {

			$generate = array( 'status' => false, 'colorscheme' => false );

		}

	} else {

		$generate = array( 'status' => false, 'colorscheme' => false );

	}

	// Transmit message
	echo json_encode( $generate ); 

	die();
}
endif;
add_action( 'wp_ajax_manuscript_generate_customizer_color_scheme', 'manuscript_generate_customizer_color_scheme' );

/**
 * Generate color scheme based on color choosen by user
 */
if ( ! function_exists( 'manuscript_save_color_scheme' ) ) :
function manuscript_save_color_scheme(){

	/**
	 * Colors
	 */
	$colors = array( 'background', 'text', 'link' );

	/**
	 * Loop and save
	 */
	foreach ( $colors as $color_prefix ) {

		$key = $color_prefix . '_color';

		$color = get_theme_mod( $key, false );

		if( $color ){

			$css = manuscript_generate_color_scheme_css( $color, $key );

			if( $css ){

				// Set color scheme
				set_theme_mod( $key . '_scheme', $css );

				// Remove customizer scheme
				remove_theme_mod( $key . '_scheme_customizer' );

			}
		}
	}
}
endif;
add_action( 'customize_save_after', 'manuscript_save_color_scheme' );

/**
 * Endpoint for clearing all customizer temporary settings
 * This is made to be triggered via JS call (upon tab is closed)
 * 
 * @return void
 */
if( ! function_exists( 'manuscript_clear_customizer_settings' ) ) :
function manuscript_clear_customizer_settings(){
	if( current_user_can( 'customize' ) ){
		remove_theme_mod( 'background_color_scheme_customizer' );		
	}

	die();
}
endif;
add_action( 'wp_ajax_manuscript_clear_customizer_settings', 'manuscript_clear_customizer_settings' );