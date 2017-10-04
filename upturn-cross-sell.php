<?php
/*
Plugin Name: Upturn Add To Cart page
Plugin URI: upturn.agency
Description:
Author: Upturn Agency
Version: 0.1
Author URI: https://upturn.agency
Text Domain: cross-sells
Domain Path: /lang
*/


/** REMOVING THE ADD TO CART NOTICE SINCE WE DON'T NEED IT */
add_filter( 'wc_add_to_cart_message_html', '__return_null' );


/** Redirect users after add to cart */
function upturn_add_to_cart_redirect($id) {
    $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
    $rand = rand();
    $time = time();

    $slug = get_option( 'cross-sell-page' );

    if(isset($product_id)):
        $url = get_home_url() . "/" . $slug . "/?id=" . $product_id . '&r=' . $rand . '&t=' . $time ;
    else:
        $url = WC_Cart::get_cart_url();
    endif;

    return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'upturn_add_to_cart_redirect' );

function appendStylesheet(){
    $plugin_url = plugin_dir_url( __FILE__ );
    $slug = get_option( 'cross-sell-page' );

    if(useUpturnStyle() && is_page($slug)){
        wp_enqueue_style( 'style', $plugin_url . 'assets/css/style.css' );
    }
}

add_action( 'wp_enqueue_scripts', 'appendStylesheet' );

function useUpturnStyle() {
    $temp = get_option( 'use-upturn-stylesheet' );

   if ($temp == 'yes'){
       return true;
   }
   
   return false;
}

add_filter( 'woocommerce_get_sections_products', 'cross_sell_tab' );
function cross_sell_tab( $sections ) {

    $sections['cross_sell'] = __( 'Cross sells', 'upturn-cross-sell' );
    return $sections;

}

register_activation_hook( __FILE__, 'insert_page' );

function insert_page(){
	// Create post object
	$my_post = array(
		'post_title'    => 'Cross sells',
		'post_content'  => '[upturn_add_to_cart]',
		'post_status'   => 'publish',
		'post_author'   => get_current_user_id(),
		'post_type'     => 'page',
	);

	// Insert the post into the database
	wp_insert_post( $my_post, '' );
}

// Add Shortcode
function add_to_cart_shortcode(){
    function add_to_cart_page() {
        if(is_page()):
            ob_start();
            include( plugin_dir_path( __FILE__ ) . 'cross-sells.php');
            $content = ob_get_clean();
            return $content;
        endif;
    }
    add_shortcode( 'upturn_add_to_cart', 'add_to_cart_page' );
}
add_action('init', 'add_to_cart_shortcode');


/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'cross_sell_settings', 10, 2 );
function cross_sell_settings( $settings, $current_section ) {

    if ( $current_section == 'cross_sell' ) {
        $settings_slider = array();
        // Add Title to the Settings
        $settings_slider[] = array( 'name' => __( 'WooCommerce cross sell settings', 'cross_sell' ), 'type' => 'title', 'desc' => __( 'The following options allow you to control your cross sells page', 'cross-sell' ), 'id' => 'cross-sell' );

        $args = array('post_type' => 'page', 'post_status' => 'publish');
        $query = new WP_Query($args);
        $posts = $query->posts;
        $postData = array(
            'default' => 'Default',
        );

        foreach ($posts as $post){
            $postData[$post->post_name] = $post->post_title;
        }

        // Use our stylesheet
        $settings_slider[] = array(
            'name'     => __( 'Use plugin styling', 'cross-sell' ),
            'desc_tip' => __( '', 'cross-sell' ),
            'id'       => 'use-upturn-stylesheet',
            'type'    => 'checkbox',
            'desc'     => __( 'Use Upturn style', 'cross-sell' )
        );

        // Add custom page
        $settings_slider[] = array(
            'name'     => __( 'Cross sell page', 'cross-sell' ),
            'desc_tip' => __( '', 'cross-sell' ),
            'id'       => 'cross-sell-page',
            'type'    => 'select',
            'options' => $postData,
            'desc'     => __( 'Custom page for cross-sell', 'cross-sell' ),
        );

        // Add first checkbox option
        $settings_slider[] = array(
            'name'     => __( 'Cross sell products', 'cross-sell' ),
            'desc_tip' => __( '', 'cross-sell' ),
            'id'       => 'cross-sell-products',
            'type'    => 'select',
            'options' => array(
                'false' => __( 'Disabled', 'cross-sell' ),
                '1'     => __( 'Position 1', 'cross-sell' ),
                '2'     => __( 'Position 2', 'cross-sell' ),
                '3'     => __( 'Position 3', 'cross-sell' ),
                '4'     => __( 'Position 4', 'cross-sell' )
            ),
            'desc'     => __( 'Enable cross sell products', 'cross-sell' ),
        );

        // Add first checkbox option
        $settings_slider[] = array(
            'name'     => __( 'Best sellers site wide', 'cross-sell' ),
            'desc_tip' => __( '', 'cross-sell' ),
            'id'       => 'best-sellers-site-wide',
            'type'    => 'select',
            'options' => array(
                'false' => __( 'Disabled', 'cross-sell' ),
                '1'     => __( 'Position 1', 'cross-sell' ),
                '2'     => __( 'Position 2', 'cross-sell' ),
                '3'     => __( 'Position 3', 'cross-sell' ),
                '4'     => __( 'Position 4', 'cross-sell' )
            ),
            'desc'     => __( 'Enable best sellers site wide', 'cross-sell' ),
        );

        // Add first checkbox option
        $settings_slider[] = array(
            'name'     => __( 'News', 'cross-sell' ),
            'desc_tip' => __( '', 'cross-sell' ),
            'id'       => 'new-products',
            'type'    => 'select',
            'options' => array(
                'false' => __( 'Disabled', 'cross-sell' ),
                '1'     => __( 'Position 1', 'cross-sell' ),
                '2'     => __( 'Position 2', 'cross-sell' ),
                '3'     => __( 'Position 3', 'cross-sell' ),
                '4'     => __( 'Position 4', 'cross-sell' )
            ),
            'desc'     => __( 'Enable news on cross sell', 'cross-sell' ),
        );

        // Add first checkbox option
        $settings_slider[] = array(
            'name'     => __( 'Sales items', 'cross-sell' ),
            'desc_tip' => __( '', 'cross-sell' ),
            'id'       => 'sales-items',
            'type'    => 'select',
            'options' => array(
                'false' => __( 'Disabled', 'cross-sell' ),
                '1'     => __( 'Position 1', 'cross-sell' ),
                '2'     => __( 'Position 2', 'cross-sell' ),
                '3'     => __( 'Position 3', 'cross-sell' ),
                '4'     => __( 'Position 4', 'cross-sell' )
            ),
            'desc'     => __( 'Enable sales items', 'cross-sell' ),
        );
	    $settings_slider[] = array(
		    'name'     => __( 'Cart expire time', 'cross-sell' ),
		    'desc_tip' => __( '', 'cross-sell' ),
		    'id'       => 'upturn_expire_time',
		    'type'    => 'number',
		    'desc'     => __( 'minutes.', 'cross-sell' ),
	    );

        $settings_slider[] = array( 'type' => 'sectionend', 'id' => 'wcslider' );
        return $settings_slider;

        /**
         * If not, return the standard settings
         **/
    } else {
        return $settings;
    }
}

function upturn_cross_sell() {
    echo '<div class="woocommerce columns-6">';
        woocommerce_cross_sell_display(6, 6, "rand");
    echo '</div>';
}

function upturn_best_sellers_site_wide(){
    echo '<h2>Our best sellers</h2>';
    $shortcode = '[best_selling_products columns="6" per_page="6"]';
    echo do_shortcode($shortcode);
}

function upturn_new_products(){
    echo '<h2>New products</h2>';
    $shortcode = '[recent_products columns="6" per_page="6"]';
    echo do_shortcode($shortcode);
}

function upturn_sales_items(){
    echo '<h2>Sales items</h2>';
    $shortcode = '[sale_products columns="6" per_page="6"]';
    echo do_shortcode($shortcode);
}

add_action('upturn_header', 'addtocart_header');
function addtocart_header(){
    get_header();
}

add_action('upturn_footer', 'addtocart_footer');
function addtocart_footer(){
    get_footer();
}