<?php
/*
Plugin Name: CPT Product List Plugin
Description: A custom plugin to manage products with custom templates & [custom_product_search].
Version: 1.0
Author: Shailesh Parmar
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// If this file called directly then abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( ! defined( 'PRODUCT_LIST_VERSION' ) ) {
    define( 'PRODUCT_LIST_VERSION', '1.0.0' );
}

if ( ! defined( 'PRODUCT_LIST_URL' ) ) {
    define( 'PRODUCT_LIST_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PRODUCT_LIST_DIR' ) ) {
    define( 'PRODUCT_LIST_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PRODUCT_LIST_DIR_NAME' ) ) {
    define( 'PRODUCT_LIST_DIR_NAME', dirname( plugin_basename( __FILE__ ) ) );
}

require_once PRODUCT_LIST_DIR . 'inc/product-enqueue/product-enqueue.php';
require_once PRODUCT_LIST_DIR . 'inc/product-menu/product-menu.php';
require_once PRODUCT_LIST_DIR . 'inc/product-posttype/product-posttype.php';

require_once PRODUCT_LIST_DIR . 'inc/product-popup/product-popup.php';
require_once PRODUCT_LIST_DIR . 'inc/product-slider/product-slider.php';
require_once PRODUCT_LIST_DIR . 'inc/product-shortcode/product-shortcode.php';
require_once PRODUCT_LIST_DIR . 'inc/product-filters/post-filter/product-filter-search.php';

require_once PRODUCT_LIST_DIR . 'inc/product-metabox/product-metabox.php';
require_once PRODUCT_LIST_DIR . 'inc/product-cf7-db/contact-form7-db.php';




///////////////////  INCLUDE TEMPLATE PAGE AND ASSIGN PAGE ////////////////////



/*-------------------------------------------------------------
  Create a custom page and assign the custom product list template
--------------------------------------------------------------*/

function create_custom_product_list_page_template() {
    $page_title = 'Product List Page Template';
    $page_content = 'This is a page with a custom product list template.';
    $page_template = 'templates/product-list-template.php'; // change template name file

    // Check if the page already exists
    $page_check = get_page_by_title($page_title, 'OBJECT', 'page');
    if (!isset($page_check->ID)) {
        $page_id = wp_insert_post(
            array(
                'post_title' => $page_title,
                'post_content' => $page_content,
                'post_status' => 'publish',
                'post_type' => 'page',
                'comment_status' => 'open',
                'ping_status' =>  'closed' ,
                'post_date' => date('Y-m-d H:i:s'),
                'post_name' => 'Product Page',
                'meta_input' => array('_wp_page_template' => $page_template)
            )
        );
    } else {
        // Update the template if the page already exists
        update_post_meta($page_check->ID, '_wp_page_template', $page_template);
    }
}
register_activation_hook(__FILE__, 'create_custom_product_list_page_template');

// Add custom template to the list of available templates to page attributes
function add_custom_template($templates) {
    $templates['templates/product-list-template.php'] = 'My Product List Template';
    return $templates;
}
add_filter('theme_page_templates', 'add_custom_template');

// load custom template page included in pluin
function load_custom_template($template) {
    if (is_page_template('templates/product-list-template.php')) {
        $plugin_template = plugin_dir_path(__FILE__) . 'templates/product-list-template.php';
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter('template_include', 'load_custom_template');
//////////////////////////////////////////////////////////////////


function custom_product_templates( $template ) {
    global $post;


 
   if (isset($post) && $post->post_type == 'product') {

        if ( is_singular( 'product' ) ) {
            $template = PRODUCT_LIST_DIR  . 'templates/single-product.php';
            if (file_exists($template)) {
                return $template;
            }
        } elseif ( is_post_type_archive( 'product' ) ) {
            $template = PRODUCT_LIST_DIR  . 'templates/archive-product.php';
        } elseif ( is_tax( 'prod_cat' ) ) {
            $template = PRODUCT_LIST_DIR  . 'templates/taxonomy-prod_cat.php';
        } elseif ( is_tax( 'prod_tag' ) ) {
            $template = PRODUCT_LIST_DIR  . 'templates/taxonomy-prod_tag.php';
        } 

    } //main if

    return $template;
}
add_filter( 'template_include', 'custom_product_templates' );
