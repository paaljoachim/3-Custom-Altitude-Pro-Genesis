<?php
/**
 * Altitude Pro.
 *
 * This file adds the inner page to the Altitude Pro Theme.
 *
 * Template Name: Inner Page
 *
 * @package Altitude
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/altitude/
 */
 

add_action( 'genesis_meta', 'altitude_inner_page_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 * @since 1.0.0
 */
function altitude_inner_page_genesis_meta() {

	if ( is_active_sidebar( 'inner-page-1' ) || is_active_sidebar( 'inner-page-2' ) || is_active_sidebar( 'inner-page-3' ) || is_active_sidebar( 'inner-page-4' ) || is_active_sidebar( 'inner-page-5' ) || is_active_sidebar( 'inner-page-6' ) || is_active_sidebar( 'inner-page-7' ) ) {

		// Enqueue scripts.
		add_action( 'wp_enqueue_scripts', 'altitude_enqueue_altitude_script' );

		// Add inner-page body class.
		add_filter( 'body_class', 'altitude_body_class' );

		// Force full width content layout.
		add_filter( 'genesis_site_layout', '__genesis_return_full_width_content' );

		// Remove breadcrumbs.
		remove_action( 'genesis_before_loop', 'genesis_do_breadcrumbs' );

		// Remove the default Genesis loop.
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		// Add homepage widgets.
		add_action( 'genesis_loop', 'altitude_inner_page_widgets' );

		// Add featured-section body class.
		if ( is_active_sidebar( 'inner-page-1' ) ) {

			// Add image-section-start body class.
			add_filter( 'body_class', 'altitude_featured_body_class' );

		}

	}

}

// Define inner page scripts.
function altitude_enqueue_altitude_script() {
	wp_enqueue_script( 'altitude-script', get_stylesheet_directory_uri() . '/js/home.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
}

// Define inner-page body class.
function altitude_body_class( $classes ) {

	$classes[] = 'inner-page';

	return $classes;

}

// Define featured-section body class.
function altitude_featured_body_class( $classes ) {

	$classes[] = 'featured-section';

	return $classes;

}

// Add markup for inner page widgets.
function altitude_inner_page_widgets() {

	echo '<h2 class="screen-reader-text">' . __( 'Main Content', 'altitude-pro' ) . '</h2>';

	genesis_widget_area( 'inner-page-1', array(
		'before' => '<div id="inner-page-1" class="inner-page-1" tabindex="-1"><div class="image-section"><div class="flexible-widgets widget-area' . altitude_widget_area_class( 'inner-page-1' ) . '"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

	genesis_widget_area( 'inner-page-2', array(
		'before' => '<div id="inner-page-2" class="inner-page-2" tabindex="-1"><div class="image-section"><div class="flexible-widgets widget-area' . altitude_widget_area_class( 'inner-page-2' ) . '"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

	genesis_widget_area( 'inner-page-3', array(
		'before' => '<div id="inner-page-3" class="inner-page-3" tabindex="-1"><div class="image-section"><div class="flexible-widgets widget-area' . altitude_widget_area_class( 'inner-page-3' ) . '"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

	genesis_widget_area( 'inner-page-4', array(
		'before' => '<div id="inner-page-4" class="inner-page-4" tabindex="-1"><div class="image-section"><div class="flexible-widgets widget-area' . altitude_widget_area_class( 'inner-page-4' ) . '"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

	genesis_widget_area( 'inner-page-5', array(
		'before' => '<div id="inner-page-5" class="inner-page-5" tabindex="-1"><div class="image-section"><div class="flexible-widgets widget-area' . altitude_widget_area_class( 'inner-page-5' ) . '"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

	genesis_widget_area( 'inner-page-6', array(
		'before' => '<div id="inner-page-6" class="inner-page-6" tabindex="-1"><div class="image-section"><div class="flexible-widgets widget-area' . altitude_widget_area_class( 'inner-page-6' ) . '"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

	genesis_widget_area( 'inner-page-7', array(
		'before' => '<div id="inner-page-7" class="inner-page-7" tabindex="-1"><div class="image-section"><div class="flexible-widgets widget-area' . altitude_widget_area_class( 'inner-page-7' ) . '"><div class="wrap">',
		'after'  => '</div></div></div></div>',
	) );

}

// Run the Genesis loop.
genesis();
