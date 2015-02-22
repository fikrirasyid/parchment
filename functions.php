<?php
/**
 * Parchment functions and definitions
 *
 * @package Parchment
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 620; /* pixels */
}

if ( ! function_exists( 'parchment_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function parchment_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Parchment, use a find and replace
	 * to change 'parchment' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'parchment', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'parchment' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'parchment_custom_background_args', array(
		'default-color' => 'F5F5F5',
		'default-image' => '',
	) ) );

	// Determine typography preference
	$typography = get_theme_mod( 'typography', 'serif' );

	// Adding editor style
	add_editor_style( array(
		parchment_get_google_fonts_url(),
		"editor-{$typography}.css"
	) );
}
endif; // parchment_setup
add_action( 'after_setup_theme', 'parchment_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
if( ! function_exists( 'parchment_widgets_init' ) ) :
function parchment_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'parchment' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
endif; // parchment_widgets_init
add_action( 'widgets_init', 'parchment_widgets_init' );

/**
 * Get parchment google fonts URL
 * 
 * @return mixed
 */
function parchment_get_google_fonts_url(){
	// Determine typography preference
	$typography = get_theme_mod( 'typography', 'serif' );

	switch ( $typography ) {
		case 'monospace':
			$google_fonts_url = false;
			break;

		case 'sansserif':
			$google_fonts_url = '//fonts.googleapis.com/css?family=Source+Sans+Pro:400italic,700italic,400,700|Montserrat:400,700';
			break;
		
		default:
			$google_fonts_url = '//fonts.googleapis.com/css?family=Vollkorn:400italic,700italic,400,700|Montserrat:400,700';
			break;
	}

	return apply_filters( 'parchment_get_google_fonts_url', $google_fonts_url );	
}

/**
 * Enqueue scripts and styles.
 */
if( ! function_exists( 'parchment_scripts' ) ) :
function parchment_scripts() {	
	// Load google fonts, if necessary
	$google_fonts_url = parchment_get_google_fonts_url();

	if( $google_fonts_url ){
	    wp_enqueue_style( 'parchment-google-fonts', $google_fonts_url );		
	}

	wp_enqueue_style( 'parchment-style', get_stylesheet_uri(), array(), '20150222' );

	wp_enqueue_script( 'parchment-script', get_template_directory_uri() . '/js/parchment.js', array( 'jquery' ), '20150215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
endif; // parchment_scripts
add_action( 'wp_enqueue_scripts', 'parchment_scripts' );

/**
 * Body class modifications
 */
if( ! function_exists( 'parchment_body_class' ) ) :
function parchment_body_class( $classes ){
	$typography = get_theme_mod( 'typography', false );

	if( $typography ){
		$classes[] = sanitize_title( $typography );
	}

	return $classes;
}
endif; // parchment_body_class
add_filter( 'body_class', 'parchment_body_class' );

/**
 * Display custom color scheme
 */
if( ! function_exists( 'parchment_color_scheme' ) ) :
function parchment_color_scheme(){
	/**
	 * Colors
	 */
	$colors = array( 'background', 'text', 'link' );

	/**
	 * Loop and display custom color schemes
	 */
	foreach ( $colors as $color_prefix ) {

		$name = $color_prefix . '_color_scheme';

		$color_scheme = get_theme_mod( $name, 'false' );

		if( $color_scheme ){
			wp_add_inline_style( 'parchment-style', $color_scheme );
		}
	}
}
endif; // parchment_color_scheme
add_action( 'wp_enqueue_scripts', 'parchment_color_scheme', 20 );

/**
 * Modifying excerpt suffix
 */
function parchment_excerpt_more(){
	global $post;

	$more_url 		= esc_url( get_permalink( $post->ID ) );
	$more_title 	= sprintf( __( 'Permanent link to %s', 'parchment' ), $post->post_title );
	$more_copy 		= __( 'Continue Reading', 'parchment' );

	return " &hellip; </p><p><a href='{$more_url}' title='{$more_title}' class='more-link'>{$more_copy}</a>";
}	
add_filter( 'excerpt_more', 'parchment_excerpt_more' );

/**
 * Parchment typography options
 */
function parchment_typography_options(){
	$options = array(
		'serif' 	=> __( 'Serif - Poetic', 'parchment' ),
		'sansserif' => __( 'Sans Serif - Clean', 'parchment' ),
		'monospace' => __( 'Monospace - Geeky', 'parchment' ),
	);

	return apply_filters( 'parchment_typography_options', $options );
}

/**
 * Load simple color adjuster library
 */
if( ! class_exists( 'Parchment_Simple_Color_Adjuster' ) ){
	require get_template_directory() . '/inc/simple-color-adjuster.php';
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
