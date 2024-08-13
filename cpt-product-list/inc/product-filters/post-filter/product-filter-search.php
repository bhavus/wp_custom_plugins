<?php

// Enqueue scripts and styles
function p_filter_enqueue_scripts() {
    wp_enqueue_style('p-filter-style', plugins_url('assets/css/mnsp-style.css', __FILE__), array(), '1.0');
    wp_enqueue_script('prod-search', plugins_url('assets/js/product-search.js', __FILE__), array('jquery'), null, true);

    wp_localize_script('prod-search', 'ajax_obj_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('ajax-product-search-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'p_filter_enqueue_scripts');

// Render search form

function render_products_search_form() {
    $categories = get_categories(array(
        'taxonomy' => 'prod_cat',
        'type' => 'product'
    ));
    
    ob_start();
    ?>
    <form id="custom-product-search-form">
        <div class="spp-search">
            
            <select id="category-filter">
                <option value=""><?php _e('All Categories', 'custom-product'); ?></option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>"><?php echo esc_html($category->name); ?></option>
                <?php endforeach; ?>
            </select>

            <select id="orderby">
                <option value="date_desc"><?php _e('Newest to Oldest', 'custom-product'); ?></option>
                <option value="date_asc"><?php _e('Oldest to Newest', 'custom-product'); ?></option>
                <option value="title_asc"><?php _e('Alphabetical', 'custom-product'); ?></option>
                <option value="title_desc"><?php _e('Reverse Alphabetical', 'custom-product'); ?></option>
                <option value="price_asc"><?php _e('Price Low to High', 'custom-product'); ?></option>
                <option value="price_desc"><?php _e('Price High to Low', 'custom-product'); ?></option>
            </select>
            
            <input type="text" id="search-term" placeholder="Search Product...">
        </div>
        <ul id="search-results"></ul>
        <div id="pagination"></div>
    </form>
    <?php
    return ob_get_clean();
}
add_shortcode('custom_product_search', 'render_products_search_form');



// AJAX handler for product search
function ajax_handle_products_search() {
    check_ajax_referer('ajax-product-search-nonce', 'nonce');

    $search_term = isset($_POST['search_term']) ? sanitize_text_field($_POST['search_term']) : '';
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : '';
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    $orderby = isset($_POST['orderby']) ? sanitize_text_field($_POST['orderby']) : 'date_desc';

    $args = [
        'post_type' => 'product',
        's' => $search_term,
        'posts_per_page' => 10,
        'post_status' => 'publish',
        'paged' => $paged,
    ];

    switch ($orderby) {
        case 'title_asc':
            $args['orderby'] = 'title';
            $args['order'] = 'ASC';
            break;
        case 'title_desc':
            $args['orderby'] = 'title';
            $args['order'] = 'DESC';
            break;
        case 'price_asc':
            $args['meta_key'] = 'product_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        case 'price_desc':
            $args['meta_key'] = 'product_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'date_asc':
            $args['orderby'] = 'date';
            $args['order'] = 'ASC';
            break;
        case 'date_desc':
        default:
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
    }

    if ($category_id) {
        $args['tax_query'] = [
            [
                'taxonomy' => 'prod_cat',
                'field' => 'term_id',
                'terms' => $category_id,
            ],
        ];
    }

    $query = new WP_Query($args);
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = [
                'title' => get_the_title(),
                'permalink' => get_permalink()
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success($results);
}
add_action('wp_ajax_nopriv_ajax_products_search', 'ajax_handle_products_search');
add_action('wp_ajax_ajax_products_search', 'ajax_handle_products_search');