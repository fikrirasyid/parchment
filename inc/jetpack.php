<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package Parchment
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function parchment_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'container' => 'main',
		'footer'    => 'page',
	) );

    add_theme_support( 'site-logo', array(
        'header-text' => array(
            'site-title'
        ),
        'size' => 'full',
    )); 	
}
add_action( 'after_setup_theme', 'parchment_jetpack_setup' );
