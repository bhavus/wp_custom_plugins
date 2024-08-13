<?php
/**
* Create [product_shortcode] shortcode
**/
function product_shortcode_func( $atts ) {
    global $post;

    // Extract shortcode attributes
    $atts = shortcode_atts(
        array(
            'count' => -1,
        ),
        $atts
    );

    // Custom query
    $query = new WP_Query(
        array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => intval($atts['count']),
            // 'orderby'=>'meta_value',
            // 'meta_key' => 'product_price',
            // 'orderby' => 'ASC',
        )
    );

    // Initialize output string
    $output = '';

    // Loop through posts
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Get post meta

            $price = get_post_meta(get_the_ID(), 'product_price', true);
            $price_output = !empty($price) ? ' $ ' . esc_html($price) : '';

            // Get post thumbnail
            $thumbnail = has_post_thumbnail() ? get_the_post_thumbnail(get_the_ID(), 'thumbnail') : '';

            // Get short description
            $short_description = get_the_excerpt();

            // Get categories
            $categories = get_the_terms(get_the_ID(), 'prod_cat');
            $category_output = '';
            if (!empty($categories) && !is_wp_error($categories)) {
                $category_list = array();
                foreach ($categories as $category) {
                    $category_list[] = esc_html($category->name);
                }
                $category_output = implode(', ', $category_list);
            }

            // Append to output string
            $output .= '<div class="product">';
            $output .= $thumbnail;
            $output .= '<h2><a href="' . esc_url(get_the_permalink()) . '">' . esc_html(get_the_title()) . '</a></h2>';
            $output .= !empty($price_output) ? '<p>Price: ' . esc_html($price_output) . '</p>': '';
            $output .= !empty($category_output) ? '<p>Categories: ' . esc_html($category_output) . '</p>' : '';
            $output .= '<p>' . esc_html($short_description) . '</p>';
            $output .= '</div><br>';
        }
    } else {
        $output = 'No products found.';
    }

    // Reset post data
    wp_reset_postdata();

    // Return output string
    return $output;
}
add_shortcode('product_shortcode', 'product_shortcode_func');
