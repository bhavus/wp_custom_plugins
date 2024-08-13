<?php
// Register Custom Post Type
function custom_product_cpt() {
    $labels = array(
        'name'                  => _x( 'Products', 'Post Type General Name', 'text_domain' ),
        'singular_name'         => _x( 'Product', 'Post Type Singular Name', 'text_domain' ),
        'menu_name'             => __( 'Products', 'text_domain' ),
        'name_admin_bar'        => __( 'Product', 'text_domain' ),
        'archives'              => __( 'Product Archives', 'text_domain' ),
        'attributes'            => __( 'Product Attributes', 'text_domain' ),
        'parent_item_colon'     => __( 'Parent Product:', 'text_domain' ),
        'all_items'             => __( 'All Products', 'text_domain' ),
        'add_new_item'          => __( 'Add New Product', 'text_domain' ),
        'add_new'               => __( 'Add New', 'text_domain' ),
        'new_item'              => __( 'New Product', 'text_domain' ),
        'edit_item'             => __( 'Edit Product', 'text_domain' ),
        'update_item'           => __( 'Update Product', 'text_domain' ),
        'view_item'             => __( 'View Product', 'text_domain' ),
        'view_items'            => __( 'View Products', 'text_domain' ),
        'search_items'          => __( 'Search Product', 'text_domain' ),
        'not_found'             => __( 'Not found', 'text_domain' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
        'featured_image'        => __( 'Featured Image', 'text_domain' ),
        'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
        'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
        'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
        'insert_into_item'      => __( 'Insert into product', 'text_domain' ),
        'uploaded_to_this_item' => __( 'Uploaded to this product', 'text_domain' ),
        'items_list'            => __( 'Products list', 'text_domain' ),
        'items_list_navigation' => __( 'Products list navigation', 'text_domain' ),
        'filter_items_list'     => __( 'Filter products list', 'text_domain' ),
    );
    $args = array(
        'label'                 => __( 'Product', 'text_domain' ),
        'description'           => __( 'Custom Post Type for Products', 'text_domain' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields',"page-attributes", "post-formats", "gallery" ),
        'taxonomies'            => array( 'prod_cat', 'prod_tag' ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => array('slug' => 'product'),
        'show_in_rest'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,		
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
    );
    register_post_type( 'product', $args );
}
add_action( 'init', 'custom_product_cpt', 0 );

// Register Custom Taxonomy
function custom_product_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Product Categories', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Product Category', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Product Categories', 'text_domain' ),
        'all_items'                  => __( 'All Categories', 'text_domain' ),
        'parent_item'                => __( 'Parent Category', 'text_domain' ),
        'parent_item_colon'          => __( 'Parent Category:', 'text_domain' ),
        'new_item_name'              => __( 'New Category Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Category', 'text_domain' ),
        'edit_item'                  => __( 'Edit Category', 'text_domain' ),
        'update_item'                => __( 'Update Category', 'text_domain' ),
        'view_item'                  => __( 'View Category', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
        'popular_items'              => __( 'Popular Categories', 'text_domain' ),
        'search_items'               => __( 'Search Categories', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
        'no_terms'                   => __( 'No categories', 'text_domain' ),
        'items_list'                 => __( 'Categories list', 'text_domain' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'text_domain' ),
    );
    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'query_var'                  => true,
        'rewrite'                    => array('slug' => 'prod_cat'),
    );
    register_taxonomy( 'prod_cat', array( 'product' ), $args );

    $tag_labels = array(
        'name'                       => _x( 'Product Tags', 'Taxonomy General Name', 'text_domain' ),
        'singular_name'              => _x( 'Product Tag', 'Taxonomy Singular Name', 'text_domain' ),
        'menu_name'                  => __( 'Product Tags', 'text_domain' ),
        'all_items'                  => __( 'All Tags', 'text_domain' ),
        'new_item_name'              => __( 'New Tag Name', 'text_domain' ),
        'add_new_item'               => __( 'Add New Tag', 'text_domain' ),
        'edit_item'                  => __( 'Edit Tag', 'text_domain' ),
        'update_item'                => __( 'Update Tag', 'text_domain' ),
        'view_item'                  => __( 'View Tag', 'text_domain' ),
        'separate_items_with_commas' => __( 'Separate tags with commas', 'text_domain' ),
        'add_or_remove_items'        => __( 'Add or remove tags', 'text_domain' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
        'popular_items'              => __( 'Popular Tags', 'text_domain' ),
        'search_items'               => __( 'Search Tags', 'text_domain' ),
        'not_found'                  => __( 'Not Found', 'text_domain' ),
        'no_terms'                   => __( 'No tags', 'text_domain' ),
        'items_list'                 => __( 'Tags list', 'text_domain' ),
        'items_list_navigation'      => __( 'Tags list navigation', 'text_domain' ),
    );
    $tag_args = array(
        'labels'                     => $tag_labels,
        'hierarchical'               => false, // Set to true if you want hierarchical tags like categories
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'query_var'                  => true,
        'rewrite'                    => array('slug' => 'prod_tag'), // Customize the slug
    );
    register_taxonomy( 'prod_tag', array( 'product' ), $tag_args );
}
add_action( 'init', 'custom_product_taxonomy', 0 );


///////////////////  ADD COLUMN AND FILTERS //////////////////////////




/*-------------------------------------------------------------
  Rename Featured image to Product Image
--------------------------------------------------------------*/
function product_rename_featured_image_label($args, $post_type) {
    if ($post_type === 'product') { // Replace 'post' with the post type where you want to rename the label
        $args['labels']['featured_image'] = 'Product Image';
        $args['labels']['set_featured_image'] = 'Set Product Image';
        $args['labels']['remove_featured_image'] = 'Remove Product Image';
        $args['labels']['use_featured_image'] = 'Use as Product Image';
    }
    return $args;
}
add_filter('register_post_type_args', 'product_rename_featured_image_label', 10, 2);

/*-------------------------------------------------------------
   Set custom columns for the 'product' post type
--------------------------------------------------------------*/

function set_custom_edit_product_list_columns($columns) {
    // Remove unwanted columns
    unset($columns['categories']);
    unset($columns['tags']);
    
    // Set custom columns
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'product_thumbnail' => 'Product Thumbnail',
        'title' => 'Product Title',
        'prod_cat' => 'Product Category',
        'product_price' => 'Product Price',
        'product_date' => 'Product Date',
        'date' => 'Date',
    );

    return $columns;
}
add_filter('manage_edit-product_columns', 'set_custom_edit_product_list_columns');

/*-------------------------------------------------------------
  Display custom column values for the 'product' post type
--------------------------------------------------------------*/

function display_custom_product_column_values( $column, $post_id ) {
    switch ( $column ) {
        case 'prod_cat':
            // Get the terms for the 'prod_cat' taxonomy
            $terms = get_the_terms($post_id, 'prod_cat');
            if (!empty($terms) && !is_wp_error($terms)) {
                $term_list = array();
                foreach ($terms as $term) {
                    $term_list[] = esc_html($term->name);
                }
                echo implode(', ', $term_list);
            } else {
                echo 'No Categories';
            }
            break;

        case 'product_thumbnail':
            // Display the post thumbnail
            if (has_post_thumbnail($post_id)) {
                echo get_the_post_thumbnail($post_id, array(50, 50));
            } else {
                echo 'No Thumbnail';
            }
            break;

        case 'product_price':
            // Display the product price custom field
            $p_price = get_post_meta($post_id, 'product_price', true);
            echo (!empty($p_price)) ? '$' . esc_html($p_price) : 'No Price';
            break;

        case 'product_date':
            // Display the post date
            $p_date = get_post_meta($post_id, 'product_date', true);
            echo (!empty($p_date)) ? date('m/d/Y', strtotime($p_date)) : 'No Date';
            break;
           
    }
}
add_action('manage_product_posts_custom_column', 'display_custom_product_column_values', 10, 2);

/*-------------------------------------------------------------
  Make the custom columns sortable
--------------------------------------------------------------*/
function custom_product_sortable_columns($columns) {
    $columns['prod_cat'] = 'prod_cat';
    $columns['product_price'] = 'product_price';
    $columns['product_date'] = 'product_date';
    return $columns;
}
add_filter('manage_edit-product_sortable_columns', 'custom_product_sortable_columns');


/*-------------------------------------------------------------
 ( admin panel side product_filter_by_category ) to product post type
--------------------------------------------------------------*/

add_action('restrict_manage_posts', 'product_list_custom_filters');
function product_list_custom_filters(){
    global $typenow;
    $show_taxonomy = 'prod_cat';
    $selected_category = isset($_GET[$show_taxonomy]) ? intval($_GET[$show_taxonomy]) : "";

    if ($typenow == 'product') {
        
        // Category Filter
        $args = array(
            'show_option_all' => 'Show all categories',
            'taxonomy' => $show_taxonomy,
            'show_count' => true,
            'name' => $show_taxonomy,
            'selected' => $selected_category,
        );
        wp_dropdown_categories($args); //wp_dropdown_users()

        // Price Order Filter
        $selected_order = isset($_GET['product_price']) ? $_GET['product_price'] : "";
        ?>
        <select name="product_price">
            <option value=""><?php _e('Order by Price', 'textdomain'); ?></option>
            <option value="asc" <?php selected($selected_order, 'asc'); ?>><?php _e('Price: Low to High', 'textdomain'); ?></option>
            <option value="desc" <?php selected($selected_order, 'desc'); ?>><?php _e('Price: High to Low', 'textdomain'); ?></option>
        </select>
        <?php


         // Date Order Filter
        $selected_date_order = isset($_GET['product_date']) ? $_GET['product_date'] : "";
        ?>
        <select name="product_date">
            <option value=""><?php _e('Order by Date', 'textdomain'); ?></option>
            <option value="asc" <?php selected($selected_date_order, 'asc'); ?>><?php _e('Oldest First', 'textdomain'); ?></option>
            <option value="desc" <?php selected($selected_date_order, 'desc'); ?>><?php _e('Newest First', 'textdomain'); ?></option>
        </select>
        <?php
    }
}

// Handle the filtering and sorting logic for custom filters
add_filter('pre_get_posts', 'product_list_filter_query');
function product_list_filter_query($query){
    global $pagenow;
    $show_taxonomy = 'prod_cat';

    if ($pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'product' && $query->is_main_query()) {

        // Category Filter
        if (isset($_GET[$show_taxonomy]) && !empty($_GET[$show_taxonomy])) {
            $query->query_vars[$show_taxonomy] = intval($_GET[$show_taxonomy]);
        }

       // Price Order Filter
        if (isset($_GET['product_price']) && !empty($_GET['product_price'])) {
            $order = $_GET['product_price'];
            $query->set('meta_key', 'product_price');
            $query->set('orderby', 'meta_value_num');
            $query->set('order', $order == 'asc' ? 'ASC' : 'DESC');
        }

        // Date Order Filter
        if (isset($_GET['product_date']) && !empty($_GET['product_date'])) {
            $date_order = $_GET['product_date'];
            $query->set('orderby', 'date');
            $query->set('order', $date_order == 'asc' ? 'ASC' : 'DESC');
        }

    }
}


/////////////////// METABOX FOR CPT 'PRODUCT' ////////////////////////////////


/*-------------------------------------------------------------
  (step-1) add product price ,date meta field to events post type
--------------------------------------------------------------*/
function medic_add_post_meta_boxes() {
    add_meta_box(
        "product-meta-id", // div id containing rendered fields
        "Product Details", // section heading displayed as text
        "display_html_form_product_meta_callback", // callback function to render fields
        "product", // name of post type on which to render fields
        "side", // location on the screen
        "low" // placement priority
    );
   
}
add_action( "admin_init", "medic_add_post_meta_boxes" );

/*-------------------------------------------------------------
  (step-2) above callback function Design html code
--------------------------------------------------------------*/
function display_html_form_product_meta_callback($post)
{
    global $post;
    $product_price = get_post_meta($post->ID, 'product_price', true);
    $product_date  = get_post_meta($post->ID, 'product_date', true);
    ?>
    
    <label for="product_price">Product Price: </label>
    <input type="number" id="product_price" name="product_price" value="<?php echo esc_attr($product_price); ?>">
    <label for="product_date">Product Date: </label>
    <input type="date" id="product_date" name="product_date" value="<?php echo esc_attr($product_date); ?>">
    
    <?php
}

/*-------------------------------------------------------------
  (Step-3) Save post publish then event metabox saved event date
--------------------------------------------------------------*/
function save_the_product_meta_value($post_id) {
    if (array_key_exists('product_price', $_POST)) {
        update_post_meta($post_id, 'product_price', sanitize_text_field($_POST['product_price']));
    }
    if (array_key_exists('product_date', $_POST)) {
        update_post_meta($post_id, 'product_date', sanitize_text_field($_POST['product_date']));
    }
}
add_action('save_post', 'save_the_product_meta_value');