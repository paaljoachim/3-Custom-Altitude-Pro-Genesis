<?php
/**
 * Boss Pro
 *
 * This file edits the posts page template in the Boss Pro Theme.
 *
 * @package Boss
 * @author  Bloom
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/boss/
 */

add_filter( 'body_class', 'boss_home_page_header_body_class' );
function boss_home_page_header_body_class( $classes ) {
    if( has_post_thumbnail() )
        $classes[] = 'with-page-header';
    return $classes;

}

add_action( 'genesis_after_header', 'boss_home_page_header', 8 );
function boss_home_page_header() {

	$output = false;
    $posts_page_id = get_option( 'page_for_posts' );
    
    // https://catapultthemes.com/adding-an-image-upload-field-to-categories/
    $category = get_category( get_query_var( 'cat' ) );
    $cat_id = $category->cat_ID;
    $image = get_term_meta ( $cat_id, 'category-image-id', true );

    if ( $image ) {

        // Remove the category title because we're going to add it later.
        // https://wpbeaches.com/remove-archive-title-on-all-the-archives-in-genesis/
        remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );

        // Remove the default page header.
        remove_action( 'genesis_after_header', 'boss_page_header', 8 );

        $image = wp_get_attachment_image_src( $image, 'boss_hero' );
        $background_image_class = 'with-background-image';
        $title = get_the_title( $posts_page_id );
        
        // https://developer.wordpress.org/reference/functions/single_cat_title/
        $current_category = single_cat_title("", false);
       
       
        $output .= '<div class="page-header bg-primary with-background-image" style="background-image: url(' . $image[0] . ');"><div class="wrap">';
        $output .= '<div class="header-content"><h1>' . $current_category . '</h1></div>';
        $output .= '</div></div>';
    }

	if ( $output ) {
		echo $output;
    }
    
}

genesis();
