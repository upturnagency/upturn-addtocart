<?php
/*
Plugin Name: Upturn Add To Cart page
Plugin URI: upturn.agency
Description:
Author: Upturn Agency
Version: 0.1
Author URI: https://upturn.agency
Text Domain: upturn-cross-sell
*/

/**
 * Redirect users after add to cart.
 */

function my_custom_add_to_cart_redirect($id) {
    $product_id = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $_REQUEST['add-to-cart'] ) );
    $rand = rand();

    if(isset($product_id)):
        $url = get_home_url() . "/cross-sale/?id=" . $product_id . '&rand=' . $rand;
    else:
        $url = WC_Cart::get_cart_url();
    endif;

    return $url;
}

add_filter( 'woocommerce_add_to_cart_redirect', 'my_custom_add_to_cart_redirect' );


add_filter( 'woocommerce_get_sections_products', 'cross_sell_tab' );
function cross_sell_tab( $sections ) {

    $sections['cross_sell'] = __( 'Cross sells', 'upturn-cross-sell' );
    return $sections;

}

/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'cross_sell_settings', 10, 2 );
function cross_sell_settings( $settings, $current_section ) {

    if ( $current_section == 'cross_sell' ) {
        $settings_slider = array();
        // Add Title to the Settings
        $settings_slider[] = array( 'name' => __( 'Woo cross sell settings', 'cross_sell' ), 'type' => 'title', 'desc' => __( 'The following options allow you to control your cross sells page', 'cross-sell' ), 'id' => 'cross-sell' );

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

class PageTemplater {
    /**
     * A reference to an instance of this class.
     */
    private static $instance;
    /**
     * The array of templates that this plugin tracks.
     */
    protected $templates;
    /**
     * Returns an instance of this class.
     */
    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new PageTemplater();
        }
        return self::$instance;
    }
    /**
     * Initializes the plugin by setting filters and administration functions.
     */
    private function __construct() {
        $this->templates = array();
        // Add a filter to the attributes metabox to inject template into the cache.
        if ( version_compare( floatval( get_bloginfo( 'version' ) ), '4.7', '<' ) ) {
            // 4.6 and older
            add_filter(
                'page_attributes_dropdown_pages_args',
                array( $this, 'register_project_templates' )
            );
        } else {
            // Add a filter to the wp 4.7 version attributes metabox
            add_filter(
                'theme_page_templates', array( $this, 'add_new_template' )
            );
        }
        // Add a filter to the save post to inject out template into the page cache
        add_filter(
            'wp_insert_post_data',
            array( $this, 'register_project_templates' )
        );
        // Add a filter to the template include to determine if the page has our
        // template assigned and return it's path
        add_filter(
            'template_include',
            array( $this, 'view_project_template')
        );
        // Add your templates to this array.
        $this->templates = array(
            'cross-sells.php' => 'Cross sells template',
        );

    }
    /**
     * Adds our template to the page dropdown for v4.7+
     *
     */
    public function add_new_template( $posts_templates ) {
        $posts_templates = array_merge( $posts_templates, $this->templates );
        return $posts_templates;
    }
    /**
     * Adds our template to the pages cache in order to trick WordPress
     * into thinking the template file exists where it doens't really exist.
     */
    public function register_project_templates( $atts ) {
        // Create the key used for the themes cache
        $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );
        // Retrieve the cache list.
        // If it doesn't exist, or it's empty prepare an array
        $templates = wp_get_theme()->get_page_templates();
        if ( empty( $templates ) ) {
            $templates = array();
        }
        // New cache, therefore remove the old one
        wp_cache_delete( $cache_key , 'themes');
        // Now add our template to the list of templates by merging our templates
        // with the existing templates array from the cache.
        $templates = array_merge( $templates, $this->templates );
        // Add the modified cache to allow WordPress to pick it up for listing
        // available templates
        wp_cache_add( $cache_key, $templates, 'themes', 1800 );
        return $atts;
    }
    /**
     * Checks if the template is assigned to the page
     */
    public function view_project_template( $template ) {
        // Return the search template if we're searching (instead of the template for the first result)
        if ( is_search() ) {
            return $template;
        }

        // Get global post
        global $post;
        // Return template if post is empty
        if ( ! $post ) {
            return $template;
        }
        // Return default template if we don't have a custom one defined
        if ( ! isset( $this->templates[get_post_meta(
                $post->ID, '_wp_page_template', true
            )] ) ) {
            return $template;
        }
        $file = plugin_dir_path( __FILE__ ). get_post_meta(
                $post->ID, '_wp_page_template', true
            );
        // Just to be safe, we check if the file exist first
        if ( file_exists( $file ) ) {
            return $file;
        } else {
            echo $file;
        }
        // Return template
        return $template;
    }
}
add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );