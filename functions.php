<?php
/**
 * Altitude Pro.
 *
 * This file adds the functions to the Altitude Pro Theme.
 *
 * @package Altitude
 * @author  StudioPress
 * @license GPL-2.0+
 * @link    http://my.studiopress.com/themes/altitude/
 */

// Start the engine.
include_once( get_template_directory() . '/lib/init.php' );

// Setup Theme.
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

// Set Localization (do not remove).
add_action( 'after_setup_theme', 'altitude_localization_setup' );
function altitude_localization_setup(){
	load_child_theme_textdomain( 'altitude-pro', get_stylesheet_directory() . '/languages' );
}

// Add the theme helper functions.
include_once( get_stylesheet_directory() . '/lib/helper-functions.php' );

// Add Image upload and Color select to WordPress Theme Customizer.
require_once( get_stylesheet_directory() . '/lib/customize.php' );

// Include Customizer CSS.
include_once( get_stylesheet_directory() . '/lib/output.php' );

// Include the WooCommerce setup functions.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-setup.php' );

// Include the WooCommerce custom CSS if customized.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-output.php' );

// Include notice to install Genesis Connect for WooCommerce.
include_once( get_stylesheet_directory() . '/lib/woocommerce/woocommerce-notice.php' );

// Child theme (do not remove).
define( 'CHILD_THEME_NAME', 'Altitude Pro' );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/altitude/' );
define( 'CHILD_THEME_VERSION', '1.1.3' );

// Enqueue scripts and styles.
add_action( 'wp_enqueue_scripts', 'altitude_enqueue_scripts_styles' );
function altitude_enqueue_scripts_styles() {

	wp_enqueue_script( 'altitude-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0.0' );

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'altitude-google-fonts', '//fonts.googleapis.com/css?family=Ek+Mukta:200,800', array(), CHILD_THEME_VERSION );

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script( 'altitude-responsive-menu', get_stylesheet_directory_uri() . '/js/responsive-menus' . $suffix . '.js', array( 'jquery' ), CHILD_THEME_VERSION, true );
	wp_localize_script(
		'altitude-responsive-menu',
		'genesis_responsive_menu',
		altitude_responsive_menu_settings()
	);
	
	// I added the following code 
	 //* Enqueue Parallax on non handhelds i.e., desktops, laptops etc. and not on tablets and mobiles
	 // Source: http://daneden.github.io/animate.css/
	 wp_enqueue_style( 'animate', get_stylesheet_directory_uri() . '/animate.css' );
	 wp_enqueue_script( 'waypoints', get_stylesheet_directory_uri() . '/js/jquery.waypoints.min.js', array( 'jquery' ), '1.0.0' );
	 wp_enqueue_script( 'waypoints-init', get_stylesheet_directory_uri() .'/js/waypoints-init.js' , array( 'jquery', 'waypoints' ), '1.0.0' ); 
	 
	
		
}


// Define our responsive menu settings.
function altitude_responsive_menu_settings() {

	$settings = array(
		'mainMenu'    => __( 'Menu', 'altitude-pro' ),
		'subMenu'     => __( 'Submenu', 'altitude-pro' ),
		'menuClasses' => array(
			'combine' => array(
				'.nav-primary',
				'.nav-secondary',
			),
		),
	);

	return $settings;

}

// Add HTML5 markup structure.
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

// Add Accessibility support.
add_theme_support( 'genesis-accessibility', array( '404-page', 'drop-down-menu', 'headings', 'rems', 'search-form', 'skip-links' ) );

// Add viewport meta tag for mobile browsers.
add_theme_support( 'genesis-responsive-viewport' );

// Add new image sizes.
add_image_size( 'featured-page', 1140, 400, TRUE );

// Add support for 1-column footer widget area.
add_theme_support( 'genesis-footer-widgets', 1 );

// Add support for footer menu.
add_theme_support( 'genesis-menus', array( 'secondary' => __( 'Before Header Menu', 'altitude-pro' ), 'primary' => __( 'Header Menu', 'altitude-pro' ), 'footer' => __( 'Footer Menu', 'altitude-pro' ) ) );

// Unregister the header right widget area.
unregister_sidebar( 'header-right' );

// Reposition the primary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header', 'genesis_do_nav', 12 );

// Remove output of primary navigation right extras.
remove_filter( 'genesis_nav_items', 'genesis_nav_right', 10, 2 );
remove_filter( 'wp_nav_menu_items', 'genesis_nav_right', 10, 2 );

// Remove navigation meta box.
add_action( 'genesis_theme_settings_metaboxes', 'altitude_remove_genesis_metaboxes' );
function altitude_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
    remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );
}

// Reposition the secondary navigation menu.
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_header', 'genesis_do_subnav', 5 );

// Remove skip link for primary navigation.
add_filter( 'genesis_skip_links_output', 'altitude_skip_links_output' );
function altitude_skip_links_output( $links ) {

	if ( isset( $links['genesis-nav-primary'] ) ) {
		unset( $links['genesis-nav-primary'] );
	}

	return $links;

}

// Add secondary-nav class if secondary navigation is used.
add_filter( 'body_class', 'altitude_secondary_nav_class' );
function altitude_secondary_nav_class( $classes ) {

	$menu_locations = get_theme_mod( 'nav_menu_locations' );

	if ( ! empty( $menu_locations['secondary'] ) ) {
		$classes[] = 'secondary-nav';
	}

	return $classes;

}

// Hook menu in footer.
add_action( 'genesis_footer', 'altitude_footer_menu', 7 );
function altitude_footer_menu() {

	genesis_nav_menu( array(
		'theme_location' => 'footer',
		'container'      => false,
		'depth'          => 1,
		'fallback_cb'    => false,
		'menu_class'     => 'genesis-nav-menu',
	) );

}

// Add Attributes for Footer Navigation.
add_filter( 'genesis_attr_nav-footer', 'genesis_attributes_nav' );

// Unregister layout settings.
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

// Unregister secondary sidebar.
unregister_sidebar( 'sidebar-alt' );

// Add support for custom header.
add_theme_support( 'custom-header', array(
	'flex-height'     => true,
	'width'           => 720,
	'height'          => 152,
	'header-selector' => '.site-title a',
	'header-text'     => false,
) );

// Add support for structural wraps.
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'nav',
	'subnav',
	'footer-widgets',
	'footer',
) );

// Modify the size of the Gravatar in the author box.
add_filter( 'genesis_author_box_gravatar_size', 'altitude_author_box_gravatar' );
function altitude_author_box_gravatar( $size ) {
	return 176;
}

// Modify the size of the Gravatar in the entry comments.
add_filter( 'genesis_comment_list_args', 'altitude_comments_gravatar' );
function altitude_comments_gravatar( $args ) {

	$args['avatar_size'] = 120;

	return $args;

}

// Add support for after entry widget.
add_theme_support( 'genesis-after-entry-widget-area' );

// Relocate after entry widget.
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

// Setup widget counts.
function altitude_count_widgets( $id ) {

	$sidebars_widgets = wp_get_sidebars_widgets();

	if ( isset( $sidebars_widgets[ $id ] ) ) {
		return count( $sidebars_widgets[ $id ] );
	}

}

function altitude_widget_area_class( $id ) {

	$count = altitude_count_widgets( $id );

	$class = '';

	if ( $count == 1 ) {
		$class .= ' widget-full';
	} elseif ( $count % 3 == 1 ) {
		$class .= ' widget-thirds';
	} elseif ( $count % 4 == 1 ) {
		$class .= ' widget-fourths';
	} elseif ( $count % 2 == 0 ) {
		$class .= ' widget-halves uneven';
	} else {
		$class .= ' widget-halves';
	}

	return $class;

}

// Relocate the post info.
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'genesis_post_info', 5 );

// Customize the entry meta in the entry header.
add_filter( 'genesis_post_info', 'altitude_post_info_filter' );
function altitude_post_info_filter( $post_info ) {

	$post_info = '[post_date format="M d Y"] [post_edit]';

	return $post_info;

}

// Customize the entry meta in the entry footer.
add_filter( 'genesis_post_meta', 'altitude_post_meta_filter' );
function altitude_post_meta_filter( $post_meta ) {

	$post_meta = 'Written by [post_author_posts_link] [post_categories before=" &middot; Categorized: "]  [post_tags before=" &middot; Tagged: "]';

	return $post_meta;

}

// Register widget areas.
genesis_register_sidebar( array(
	'id'          => 'front-page-1',
	'name'        => __( 'Front Page 1', 'altitude-pro' ),
	'description' => __( 'This is the front page 1 section.', 'altitude-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-2',
	'name'        => __( 'Front Page 2', 'altitude-pro' ),
	'description' => __( 'This is the front page 2 section.', 'altitude-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-3',
	'name'        => __( 'Front Page 3', 'altitude-pro' ),
	'description' => __( 'This is the front page 3 section.', 'altitude-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-4',
	'name'        => __( 'Front Page 4', 'altitude-pro' ),
	'description' => __( 'This is the front page 4 section.', 'altitude-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-5',
	'name'        => __( 'Front Page 5', 'altitude-pro' ),
	'description' => __( 'This is the front page 5 section.', 'altitude-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-6',
	'name'        => __( 'Front Page 6', 'altitude-pro' ),
	'description' => __( 'This is the front page 6 section.', 'altitude-pro' ),
) );
genesis_register_sidebar( array(
	'id'          => 'front-page-7',
	'name'        => __( 'Front Page 7', 'altitude-pro' ),
	'description' => __( 'This is the front page 7 section.', 'altitude-pro' ),
) );


//* Register inner widget areas
genesis_register_sidebar( array(
 'id' => 'inner-page-1',
 'name' => __( 'Inner Page 1', 'altitude' ),
 'description' => __( 'This is the inner page 1 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
 'id' => 'inner-page-2',
 'name' => __( 'Inner Page 2', 'altitude' ),
 'description' => __( 'This is the inner page 2 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
 'id' => 'inner-page-3',
 'name' => __( 'Inner Page 3', 'altitude' ),
 'description' => __( 'This is the inner page 3 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
 'id' => 'inner-page-4',
 'name' => __( 'Inner Page 4', 'altitude' ),
 'description' => __( 'This is the inner page 4 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
 'id' => 'inner-page-5',
 'name' => __( 'Inner Page 5', 'altitude' ),
 'description' => __( 'This is the inner page 5 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
 'id' => 'inner-page-6',
 'name' => __( 'Inner Page 6', 'altitude' ),
 'description' => __( 'This is the inner page 6 section.', 'altitude' ),
) );
genesis_register_sidebar( array(
 'id' => 'inner-page-7',
 'name' => __( 'Inner Page 7', 'altitude' ),
 'description' => __( 'This is the inner page 7 section.', 'altitude' ),
) );


//* Add support for 4-column footer widget from ShowCase Pro.
add_theme_support( 'genesis-footer-widgets', 4 );


/*********** Add a category featured image. **************/
include_once( get_stylesheet_directory() . '/category-featured-image.php' );




/* ------ Sticky/Fixed Footer Functions https://9seeds.com/sticky-footer-genesis/ ---------*/
add_action( 'genesis_after_header', 'stickyfoot_wrap_begin'); // Changed genesis_before_header to after header.
function stickyfoot_wrap_begin() {
 echo '<div class="page-wrap">';
}
 
add_action( 'genesis_before_footer', 'stickyfoot_wrap_end');
function stickyfoot_wrap_end() {
 echo '</div><!-- page-wrap -->';
}


// Hello bar begin -
// Enqueue scripts and styles - https://wpbeaches.com/adding-in-a-hello-tool-bar-to-the-top-of-a-genesis-theme/
add_action( 'wp_enqueue_scripts', 'hello_bar_scripts_styles' );
function hello_bar_scripts_styles() {
	wp_enqueue_script( 'hello-bar', esc_url( get_stylesheet_directory_uri() ) . '/js/hello-bar.js', array( 'jquery' ), '1.0.0' );
}


//Add in new Widget areas
add_action( 'widgets_init', 'hello_bar_extra_widgets' );
function hello_bar_extra_widgets() {
	genesis_register_sidebar( array(
	'id'          => 'preheaderleft',
	'name'        => __( 'Pre Header Left', 'altitude-pro' ),
	'description' => __( 'This is the preheader Left area', 'altitude-pro' ),
	'before_widget' => '<div class="first one-half preheaderleft">',
    'after_widget' => '</div>',
	) );
	genesis_register_sidebar( array(
	'id'          => 'preheaderright',
	'name'        => __( 'Pre Header Right', 'altitude-pro' ),
	'description' => __( 'This is the preheader Left area', 'altitude-pro' ),
	'before_widget' => '<div class="one-half preheaderright">',
    'after_widget' => '</div>',
	) );
}

//Position the preHeader Area
add_action('genesis_before_header','hello_bar_preheader_widget');
function hello_bar_preheader_widget() {
	echo '<div class="preheadercontainer hello-bar "><div class="wrap">';
    	genesis_widget_area ('preheaderleft', array(
        'before' => '<div class="preheaderleftcontainer">',
        'after' => '</div>',));
    	genesis_widget_area ('preheaderright', array(
        'before' => '<div class="preheaderrightcontainer">',
        'after' => '</div>',));
    	echo '</div></div>';
}

// Hello bar -END-


// NB! To activate the author box go to the profile and scroll to Author Box and enable.



// https://wisdmlabs.com/blog/how-to-create-a-read-more-button-for-excerpts-in-genesis/
//* Modify the Genesis content limit read more link
add_filter( 'get_the_content_more_link', 'showcase_read_more_link' );
function showcase_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">Continue Reading</a>';
}

 /**
     * Append ellipses to excerpts and then show "Read More"  button for manual & automatic excerpts.
     * 
     * @param type $text
     * @return string
     */
     function custom_excerpt($text) {
    //    $text= substr_replace($text,"...",strpos($text, "</p>"),0);
    $excerpt = $text . '<a href="' . get_permalink() . '"><button class="read-more-btn" type="button" value="read_more">Les mer</button></a>';
    return $excerpt;
    }

    add_filter('the_excerpt', 'custom_excerpt');
       function custom_excerpt_more($more) {
            return '...';// return empty string
        }
        add_filter('excerpt_more', 'custom_excerpt_more');
        
      
    function custom_excerpt_length($length) {
      return 60;
    }
    add_filter('excerpt_length', 'custom_excerpt_length');      
    
    
    
  // Enable shortcodes in widgets - https://carriedils.com/extend-wordpress-widgets-without-plugin/
  add_filter( 'widget_text', 'shortcode_unautop' );
  add_filter('widget_text', 'do_shortcode');
  
    
    
    
    
    
 /* ------------------ WooCommerce -------------------*/   
  
    
 /* --------- WooCommerce - Single Product page -----------*/
 
 // Removes price. Adds price below short description.
 remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
 add_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 20);             
 
 // Remove product title. Adds product title above thumbnail.
 remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
 add_action('woocommerce_before_single_product', 'woocommerce_template_single_title', 10); 
 
 // Shop 
 // Remove category title. Add category title above thumbnail.  ????
 remove_action('woocommerce_shop_loop_subcategory_title', 'woocommerce_template_loop_category_title', 10);
 add_action('woocommerce_before_subcategory', 'woocommerce_template_loop_category_title', 10); 
 
 // Hide sku: https://www.skyverge.com/blog/how-to-hide-sku-woocommerce-product-pages/ 
// add_filter( 'wc_product_sku_enabled', '__return_false' );
 
 // Hide meta (skue, category and tags): https://stackoverflow.com/questions/38404187/remove-tags-from-product-page-in-woocommerce
 add_action( 'after_setup_theme', 'my_after_setup_theme' );
 function my_after_setup_theme() {
     remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
 }
 
 
 // Attribute/Variation drop down. Change drop down phrase.
 // https://stackoverflow.com/questions/32170575/how-do-i-change-button-text-from-choose-an-option-in-woocommerce
  function my_dropdown_variation_attribute_options_html($html, $args){
      $html = str_replace('Velg en', 'Velg', $html);
      return $html;
  }
  add_filter('woocommerce_dropdown_variation_attribute_options_html', 'my_dropdown_variation_attribute_options_html', 10, 2);
  
  // END - Virker når det er på Norsk...
 
 
 // Show Units sold text. Single product.
 // https://businessbloomer.com/woocommerce-show-number-products-sold-product-page/
 add_action( 'woocommerce_single_product_summary', 'bbloomer_product_sold_count', 11 );
  
 function bbloomer_product_sold_count() {
 global $product;
 $units_sold = get_post_meta( $product->get_id(), 'total_sales', true );
 if ( $units_sold ) echo '<p>' . sprintf( __( 'Units Sold: %s', 'woocommerce' ), $units_sold ) . '</p>';
 }
 
 // END
 
 
 // Extra text below the price. Single product
 // https://businessbloomer.com/woocommerce-add-text-add-cart-single-product-page/
 add_action( 'woocommerce_single_product_summary', 'return_policy', 20 );
  
 function return_policy() {
     echo '<p id="rtrn">Husk å følg med.</p>';
 }
 
 // END
  

// Add to cart message. Showing product title and the buttons Show Cart and Go to checkout.
// https://stackoverflow.com/questions/25880460/woocommerce-how-to-edit-the-added-to-cart-message also see https://businessbloomer.com/woocommerce-remove-product-successfully-added-cart-message/ 
add_filter ( 'wc_add_to_cart_message', 'wc_add_to_cart_message_filter', 10, 2 );
function wc_add_to_cart_message_filter($message, $product_id = null) {
$titles[] = get_the_title( $product_id );

$titles = array_filter( $titles );
$added_text = sprintf( _n( '%s has been added to your cart.', '%s have been added to your cart.', sizeof( $titles ), 'woocommerce' ), wc_format_list_of_items( $titles ) );

$message = sprintf( '%s <a href="%s" class="button">%s</a>&nbsp;<a href="%s" class="button">%s</a>',
                esc_html( $added_text ),
                esc_url( wc_get_page_permalink( 'checkout' ) ),
                esc_html__( 'Checkout', 'woocommerce' ),
                esc_url( wc_get_page_permalink( 'cart' ) ),
                esc_html__( 'View Cart', 'woocommerce' ));

return $message;}
 
 
 
 
 
 /* Add go to shop button below purchase button on single product page.
  // https://businessbloomer.com/woocommerce-continue-shopping-button-single-product-page/#more-72772
  // Code added from comments.
  add_action( 'woocommerce_single_product_summary', 'bbloomer_continue_shopping_button', 31 );
  function bbloomer_continue_shopping_button() {
    if ( wp_get_referer() ) echo '<a class="button continue" href="./shop">Gå til butikken</a>';
  } 
  // END
  */
 
 
 
 
 /* ------- Disable zoom/slider and lightbox https://businessbloomer.com/woocommerce-disable-zoom-gallery-slider-lightbox-single-product/  ---------*/
   
 add_action( 'after_setup_theme', 'bbloomer_remove_zoom_lightbox_theme_support' );
   function bbloomer_remove_zoom_lightbox_theme_support() { 
 	remove_theme_support( 'wc-product-gallery-zoom' );
 	remove_theme_support( 'wc-product-gallery-lightbox' );
 	remove_theme_support( 'wc-product-gallery-slider' );		
 }
 
 remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
 
 
 // END --- HOW TO DISABLE image link in addition to the above?
 
 
 
 
 
 /* ---------   WooCommerce - Shop page --------*/
 
 // Remove sorting result.
 remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
 remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);
 
 // Remove sorting drop down.
 remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
// remove_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 30);
 
 // Or  use the CSS: .storefront-sorting class with a display none
 add_action('woocommerce_single_product_summary', 'woocommerce_taxonomy_archive_description', 10);  
  
 
 /* ON login all products visible except members product. On log out only members product visible. */
 // https://businessbloomer.com/woocommerce-remove-specific-category-shop-loop/
 // https://stackoverflow.com/questions/34684881/hide-products-from-users-who-are-not-logged-in-using-tags/34689768#34689768 
 add_action( 'woocommerce_product_query', 'show_hide_products_category_shop' );
 function show_hide_products_category_shop( $q ) {
     $tax_query = (array) $q->get( 'tax_query' );
     
     if ( is_user_logged_in() ) {
         
         $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array( 'medlem' ), // Category slug here
                'operator' => 'NOT IN'
         );
  
     } else {
         $tax_query[] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => array( 'ukens pose' ), // Category slug here
                'operator' => 'NOT IN'
         );
  
     }
     $q->set( 'tax_query', $tax_query );
  
 }
 
 // END
 
 
 // Change the text on the shop purchase product button.
 // https://stackoverflow.com/questions/25880460/woocommerce-how-to-edit-the-added-to-cart-message and
 // http://businessbloomer.com/woocommerce-edit-add-to-cart-text-by-product-category/?ck_subscriber_id=140754468
 add_filter( 'woocommerce_product_add_to_cart_text', 'bbloomer_archive_custom_cart_button_text' );
   
 function bbloomer_archive_custom_cart_button_text() {
 global $product;
  
 $terms = get_the_terms( $product->ID, 'product_cat' );
  foreach ($terms as $term) {
             $product_cat = $term->name;
             break;
 }
  
 switch($product_cat)
 {
     case 'Ukens pose';
         return 'Kjøp ukens pose'; break;
     case 'Medlem';
         return 'Bli medlem'; break;
 // case 'category3'; etc...
 // return 'Category 3 button text'; break;
  
     default;
         return 'Default button text when no match found'; break;
 }
 }
 
 // END 
 
 
 //https://businessbloomer.com/woocommerce-add-nextprevious-single-product-page/ 
 // add_action( 'woocommerce_before_single_product', 'bbloomer_prev_next_product' );
   
  // and if you also want them at the bottom...
  add_action( 'woocommerce_after_single_product', 'bbloomer_prev_next_product' );
   
  function bbloomer_prev_next_product(){
   
  echo '<div class="prev_next_buttons">';
   
      // 'product_cat' will make sure to return next/prev from current category
         $previous = next_post_link('%link', '☜ Produkt: %title', TRUE, ' ', 'product_cat'); 
         // Arrow: '&larr;  Changed Previous to forrige. Changed TRUE to FALSE. Added %title.
      	$next = previous_post_link('%link', 'Produkt: %title  ☞', TRUE, ' ', 'product_cat'); 
      	// Arrow: &rarr; Changed Next to neste. Changed TRUE to FALSE
 
      echo $previous;
      echo $next;
       
  echo '</div>';
           
  }
  
  // END
 
 
 
 /* ------------ WooCommerce - Cart ----------- */
 
 
 // Remove trash icon and then add a new. I have added an fontawesome icon.
 function kia_cart_item_remove_link( $link, $cart_item_key ) {
     return str_replace( '&times;', '<span class="cart-remove-icon"><i class="fa fa-trash" aria-hidden="true"></i></span>', $link );
 }
 add_filter( 'woocommerce_cart_item_remove_link', 'kia_cart_item_remove_link', 10, 2 );
 
 
 /* ------------ WooCommerce - Checkout ----------- */


 // Remove what is PayPal text link. 
 // https://businessbloomer.com/woocommerce-remove-paypal-checkout/ 
 add_filter( 'woocommerce_gateway_icon', 'bbloomer_remove_what_is_paypal', 10, 2 );
 function bbloomer_remove_what_is_paypal( $icon_html, $gateway_id ) { 
 if( 'paypal' == $gateway_id ) { 
 $icon_html = '<img src="/wp-content/plugins/woocommerce/includes/gateways/paypal/assets/images/paypal.png" alt="PayPal Acceptance Mark">';
 }
  
 return $icon_html;
 }
 
 //END
  
  
  
  
/* --------- My Account page and checkout fields .....  -----*/

// My account fields.
// Adjusting the visible sections.
/* https://docs.woocommerce.com/document/woocommerce-endpoints-2-1/ 
*
 * Change the order of the endpoints that appear in My Account Page - WooCommerce 2.6
 * The first item in the array is the custom endpoint URL - ie http://mydomain.com/my-account/my-custom-endpoint
 * Alongside it are the names of the list item Menu name that corresponds to the URL, change these to suit
 */
function wpb_woo_my_account_order() {
 $myorder = array(
 'dashboard' => __( 'Dashboard', 'woocommerce' ),
 'orders' => __( 'Orders', 'woocommerce' ),
// 'downloads' => __( 'Downloads', 'woocommerce' ),
// 'edit-address' => __( 'Addresses', 'woocommerce' ),
 'payment-methods' => __( 'Payment Methods', 'woocommerce' ),
 'customer-logout' => __( 'Logout', 'woocommerce' ),
 );
 return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );


// Password strength meter
/**  https://www.snip2code.com/Snippet/1107676/Reduce-or-remove-WooCommerce-2-5-minimum
 *Reduce the strength requirement on the woocommerce password.
 * 
 * Strength Settings
 * 3 = Strong (default)
 * 2 = Medium
 * 1 = Weak
 * 0 = Very Weak / Anything
 */
function reduce_woocommerce_min_strength_requirement( $strength ) {
    return 1;
}
add_filter( 'woocommerce_min_password_strength', 'reduce_woocommerce_min_strength_requirement' );
  
 
 /* -------- WooCommerce - checkout fields ----------   
 // https://docs.woocommerce.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/
 // Hook in
 add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );
 
 // Our hooked in function - $fields is passed via the filter!
 function custom_override_checkout_fields( $fields ) {
      	unset($fields['billing']['billing_first_name']);
      	unset($fields['billing']['billing_last_name']);
      	unset($fields['billing']['billing_company']);
      	unset($fields['billing']['billing_country']);
      	unset($fields['billing']['billing_address_1']);
      	unset($fields['billing']['billing_address_2']);
      	unset($fields['billing']['billing_city']);
      	unset($fields['billing']['billing_postcode']);
      	unset($fields['billing']['billing_phone']);
      	unset($fields['billing']['billing_email']);
      	
      	unset($fields['order']['order_comments']);
      return $fields;
 }*/
 
 
 
/* From Helga the Viking - Kathy.  */
function kia_modify_default_address_fields( $fields ){
    if( isset( $fields['company'] ) ) unset( $fields['company'] );
    if( isset( $fields['country'] ) ) unset( $fields['country'] );
    if( isset( $fields['address_1'] ) ) unset( $fields['address_1'] );
    if( isset( $fields['address_2'] ) ) unset( $fields['address_2'] );
    if( isset( $fields['city'] ) ) unset( $fields['city'] );
    if( isset( $fields['state'] ) ) unset( $fields['state'] );
    if( isset( $fields['postcode'] ) ) unset( $fields['postcode'] );
    return $fields; 
}
add_filter( 'woocommerce_default_address_fields', 'kia_modify_default_address_fields' );

function kia_remove_billing_phone_fields( $fields ){
    // if( isset( $fields['billing_phone'] ) ) unset( $fields['billing_phone'] );
    // if( isset( $fields['billing_email'] ) ) $fields['billing_email']['class'] = array( 'form-row-wide' );
    return $fields;

}
add_filter( 'woocommerce_billing_fields', 'kia_remove_billing_phone_fields' );

// https://businessbloomer.com/woocommerce-remove-order-notes-checkout-page/
// Removes the Additional Information box.
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
