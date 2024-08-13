<?php


function custom_product_slider_post_type() {
    $labels = array(
        'name' => 'Slider Items',
        'singular_name' => 'Slider Item',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Slider Item',
        'edit_item' => 'Edit Slider Item',
        'new_item' => 'New Slider Item',
        'view_item' => 'View Slider Item',
        'search_items' => 'Search Slider Items',
        'not_found' => 'No slider items found',
        'not_found_in_trash' => 'No slider items found in Trash',
        'parent_item_colon' => '',
        'menu_name' => 'Slider Items'
    );
    
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'slider'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail'),
    );
    
    register_post_type('slider', $args);
}
add_action('init', 'custom_product_slider_post_type');



// Add meta box for slider settings
function custom_product_slider_meta_box() {
    add_meta_box(
        'custom_product_slider_meta_box',
        'Slider Item Details',
        'custom_product_slider_meta_box_callback',
        'slider',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'custom_product_slider_meta_box');

// Callback function to display fields in meta box
function custom_product_slider_meta_box_callback($post) {
    // Retrieve existing values for fields if they exist
    $image_id = get_post_meta($post->ID, 'slider_image', true);
    $title = get_post_meta($post->ID, 'slider_title', true);
    $description = get_post_meta($post->ID, 'slider_description', true);

    // Output fields
    echo '<label for="slider_image">Upload Image : </label>';

    echo '<input type="text" name="slider_image" id="slider_image" value="'.esc_attr($image_id).'" size="30" />
         <input type="button" class="button upload_image_button" value="Upload Image" />';
   
    echo '<br/><label for="slider_title">Title : </label>';
    echo '<input type="text" id="slider_title" name="slider_title" value="' . esc_attr($title) . '" />';
    echo '<br/><label for="slider_description">Description : </label>';
    echo '<textarea id="slider_description" name="slider_description">' . esc_textarea($description) . '</textarea>';
}

// Save meta box data
function save_custom_product_slider_meta_box_data($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    // Save custom fields
    

    if (isset($_POST['slider_image'])) {
        update_post_meta($post_id, 'slider_image', sanitize_text_field($_POST['slider_image']));
    }
    if (isset($_POST['slider_title'])) {
        update_post_meta($post_id, 'slider_title', sanitize_text_field($_POST['slider_title']));
    }
    if (isset($_POST['slider_description'])) {
        update_post_meta($post_id, 'slider_description', sanitize_textarea_field($_POST['slider_description']));
    }
}
add_action('save_post_slider', 'save_custom_product_slider_meta_box_data');



// Shortcode for displaying the slider
function custom_product_slider_shortcode($atts) {
    ob_start();
    $args = array(
        'post_type' => 'slider',
        'posts_per_page' => -1, // Retrieve all items
    );
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        echo '<div class="custom-product-slider">';
        while ($query->have_posts()) {
            $query->the_post();
            // Retrieve custom fields
            $image_id = get_post_meta(get_the_ID(), 'slider_image', true);
            $title = get_post_meta(get_the_ID(), 'slider_title', true);
            $description = get_post_meta(get_the_ID(), 'slider_description', true);
            
            // Output HTML for each slide item
            echo '<div class="slide-item">';
            echo '<img src="' . esc_url($image_id) . '" alt="' . esc_attr($title) . '" />';

            // if ($image_id) {
            //     $image = wp_get_attachment_image_src($image_id, 'full');
            //     echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr($title) . '" />';
            // }

            echo '<h3>' . esc_html($title) . '</h3>';
            echo '<p>' . esc_html($description) . '</p>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<p>No slides found.</p>';
    }
    
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode('custom_product_slider', 'custom_product_slider_shortcode');

