<?php
/*
Plugin Name: Custom News Plugin
Description: A plugin to create a custom post type for news, with custom templates.
Version: 1.0
Author: Shailesh Parmar
*/


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}


// Register Custom Post Type
function custom_post_type_news() {
    $labels = array(
        'name'               => _x('News', 'post type general name'),
        'singular_name'      => _x('News', 'post type singular name'),
        'menu_name'          => _x('News', 'admin menu'),
        'name_admin_bar'     => _x('News', 'add new on admin bar'),
        'add_new'            => _x('Add New', 'news'),
        'add_new_item'       => __('Add New News'),
        'new_item'           => __('New News'),
        'edit_item'          => __('Edit News'),
        'view_item'          => __('View News'),
        'all_items'          => __('All News'),
        'search_items'       => __('Search News'),
        'parent_item_colon'  => __('Parent News:'),
        'not_found'          => __('No news found.'),
        'not_found_in_trash' => __('No news found in Trash.')
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'news'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments'),
        'taxonomies'         => array('category', 'post_tag')
    );

    register_post_type('news', $args);
}
add_action('init', 'custom_post_type_news');

// Include Custom Templates
function custom_news_templates($template) {
    if (is_singular('news')) {
        $template = plugin_dir_path(__FILE__) . 'templates/single-news.php';
    } elseif (is_post_type_archive('news')) {
        $template = plugin_dir_path(__FILE__) . 'templates/archive-news.php';
    }
    return $template;
}
add_filter('template_include', 'custom_news_templates');

// Add Filter to Locate Template Part for Content
function custom_news_content_template($template) {
    if (is_singular('news') || is_post_type_archive('news')) {
        $template = plugin_dir_path(__FILE__) . 'templates/content-news.php';
    }
    return $template;
}
add_filter('template_include', 'custom_news_content_template');

// Enqueue AJAX Search Script
function enqueue_ajax_search_script() {
    wp_enqueue_script('ajax-search', plugins_url('js/ajax-search.js', __FILE__), array('jquery'), null, true);
    wp_localize_script('ajax-search', 'ajax_search_params', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_ajax_search_script');

// AJAX Search Handler
function ajax_search() {
    $search_term = sanitize_text_field($_POST['search_term']);
    $args = array(
        'post_type' => 'news',
        's' => $search_term
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'news');
        }
    } else {
        echo '<p>No results found.</p>';
    }
    wp_die();
}
add_action('wp_ajax_ajax_search', 'ajax_search');
add_action('wp_ajax_nopriv_ajax_search', 'ajax_search');

// AJAX Category Filter Handler
function category_filter() {
    $category = sanitize_text_field($_POST['category']);
    $args = array(
        'post_type' => 'news',
        'category_name' => $category
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/content', 'news');
        }
    } else {
        echo '<p>No results found.</p>';
    }
    wp_die();
}
add_action('wp_ajax_category_filter', 'category_filter');
add_action('wp_ajax_nopriv_category_filter', 'category_filter');

// Load Popup Content
function load_popup_content() {
    $post_id = intval($_POST['post_id']);
    $post = get_post($post_id);
    if ($post) {
        setup_postdata($post);
        get_template_part('template-parts/content', 'popup');
        wp_reset_postdata();
    } else {
        echo '<p>No content found.</p>';
    }
    wp_die();
}
add_action('wp_ajax_load_popup_content', 'load_popup_content');
add_action('wp_ajax_nopriv_load_popup_content', 'load_popup_content');
?>
