<?php

//Add custom REST API endpoint and custom post type and display shortcode[news_data]

// Register custom post type
function register_news_post_type() {
    $labels = array(
        'name'               => 'News',
        'singular_name'      => 'News',
        'menu_name'          => 'News',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New News',
        'edit_item'          => 'Edit News',
        'new_item'           => 'New News',
        'view_item'          => 'View News',
        'search_items'       => 'Search News',
        'not_found'          => 'No news found',
        'not_found_in_trash' => 'No news found in Trash',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'rewrite'            => array('slug' => 'news'),
        'has_archive'        => true,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-megaphone', // Choose an icon from Dashicons
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
    );

    register_post_type('news', $args);
}

add_action('init', 'register_news_post_type');

// Add custom REST API endpoint
function news_api_route() {
    register_rest_route('custom-news-plugin/v1', '/news/', array(
        'methods'  => 'GET',
        'callback' => 'get_news',
    ));
}

add_action('rest_api_init', 'news_api_route');

// Callback function to get news data
function get_news() {
    $args = array(
        'post_type'      => 'news',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);
    $posts = $query->get_posts();

    $data = array();

    foreach ($posts as $post) {
        $data[] = array(
            'id'       => $post->ID,
            'title'    => get_the_title($post->ID),
            'content'  => apply_filters('the_content', $post->post_content),
            'excerpt'  => get_the_excerpt($post->ID),
            'thumbnail' => get_the_post_thumbnail_url($post->ID, 'medium'),
            'permalink' => get_permalink($post->ID),
            'categories' => wp_get_post_categories($post->ID),
            // Add more fields as needed
        );
    }

    return rest_ensure_response($data);
}

// Shortcode to display news data

function news_data_shortcode() {

$response = wp_remote_get('http://localhost/wp_wpacadamy/wp-json/custom-news-plugin/v1/news/');
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    

    ob_start();

    if ($data) {
        foreach ($data as $post) {
            ?>
            <div class="news-post">
                <h2><?php echo esc_html($post->title); ?></h2>
                <?php echo wp_kses_post($post->content); ?>
                <p><?php echo esc_html($post->excerpt); ?></p>
                <?php if ($post->thumbnail) : ?>
                    <img src="<?php echo esc_url($post->thumbnail); ?>" alt="<?php echo esc_attr($post->title); ?>">
                <?php endif; ?>
            </div>
            <?php
        }
    } else {
        echo 'No news found.';
       
    }

    return ob_get_clean();
}

add_shortcode('news_data', 'news_data_shortcode');
