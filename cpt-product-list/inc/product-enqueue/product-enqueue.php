<?php
//enqueue scripts and style in plugin
function my_product_list_styles() {
    wp_enqueue_style('myproduct-bt5css', PRODUCT_LIST_URL. 'assets/css/bootstrap.min.css');
    wp_enqueue_style('myproduct-style', PRODUCT_LIST_URL. 'assets/css/myproduct-style.css');
    wp_enqueue_script('myproduct-bt5js', PRODUCT_LIST_URL. 'assets/js/bootstrap.bundle.min.js', array('jquery'), null, true);

    // wp_enqueue_script('product-search', PRODUCT_LIST_URL. 'assets/js/product-search.js', array('jquery'), null, true);

    // wp_localize_script('product-search', 'ajax_obj_params', array(
    //     'ajax_url' => admin_url('admin-ajax.php'),
    //     'nonce' => wp_create_nonce('ajax-product-search-nonce')
    // ));
}
add_action('wp_enqueue_scripts', 'my_product_list_styles');