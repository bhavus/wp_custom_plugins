<?php
/*
Plugin Name: CPT movie AJAX Filter
Description: Add AJAX filter to custom post type movie and category 'movie_type'.
Version: 1.0
Author: Shailesh Parmar
*/

// Plugin code goes here
add_action('init','register_custom_post_types');

function register_custom_post_types(){
    register_post_type('movie',[
       'labels' => [
           'name' => 'Movie',
           'singular_name' => 'Movie',
           'menu_name' => 'Movies',
       ],
        'public' => true,
        'publicly_queryable' =>true,
        'menu_icon' => 'dashicons-format-video',
        'has_archive' =>true,
        'rewrite' => ['slug' => 'movie'],
        'supports' => [
            'title',
            'editor',
            'thumbnail',
            'custom-fields',
        ],
    ]
    );
}
add_action('init','register_taxonomies');

function register_taxonomies(){

    register_taxonomy('movie_type',['movie'],
        [
            'hierarchical' => true,
            'labels' => [
                'name' => __('Categories'),
                'singular_name' => __('Category'),
                'menu_name' => __('Categories'),
            ],
            'show_ui' => true,
            'show_admin_column' => true,
            'rewrite' => ['slug' => __('type')],
        ]

    );
}



function add_popularity_meta_box() {
    add_meta_box(
        'popularity_meta_box', // Unique ID
        'Popularity', // Box title
        'display_popularity_meta_box', // Content callback
        'movie', // Post type (you can change this to your custom post type)
        'side', // Context
        'default' // Priority
    );
}

function display_popularity_meta_box($post) {
    $popularity = get_post_meta($post->ID, '_popularity', true);
    ?>
    <label for="popularity">Popularity:</label>
    <select name="popularity" id="popularity">
        <?php
        for ($i = 1; $i <= 5; $i++) {
            echo '<option value="' . $i . '" ' . selected($popularity, $i, false) . '>' . $i . '</option>';
        }
        ?>
    </select>
    <?php
}

function save_popularity_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['popularity'])) {
        update_post_meta($post_id, '_popularity', sanitize_text_field($_POST['popularity']));
    }
}

add_action('add_meta_boxes', 'add_popularity_meta_box');
add_action('save_post', 'save_popularity_meta');


function enqueue_cpt_scripts() {
    wp_enqueue_style('cpt-ajax-filter-style', plugin_dir_url(__FILE__) . 'assets/css/cpt-ajax-filter.css');
    wp_enqueue_script('cpt-ajax-filter-script', plugin_dir_url(__FILE__) . 'assets/js/cpt-ajax-filter.js', array('jquery'), '1.0', true);

    wp_localize_script('cpt-ajax-filter-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_enqueue_scripts', 'enqueue_cpt_scripts');

// Load Template in Plugin
function load_custom_template($template) {
    if ( get_post_type() == 'movie') {
        
        $custom_template = plugin_dir_path(__FILE__) . 'templates/template-movies.php';
        if (file_exists($custom_template)) {
            return $custom_template;
        }
    }

    return $template;
}

add_filter('template_include', 'load_custom_template');


add_action('wp_ajax_filter_posts','filter_posts');
add_action('wp_ajax_nopriv_filter_posts','filter_posts');
function filter_posts(){

    $args = [
      'post_type' => 'movie',
      'posts_per_page' => -1,
    ];
    $type = $_REQUEST['cat'];
    $popularity = $_REQUEST['popularity'];
    if(!empty($type)){
        $args['tax_query'][] = [
            'taxonomy' => 'movie_type',
            'field' => 'slug',
            'terms' => $type,
        ];
    }

    if(!empty($popularity)){
        $args['meta_query'][] = [
            'key' => '_popularity',
            'value' => $popularity,
            'compare' => '=',
        ];
    }
    // echo "<pre>";
    // print_r($args);
    // echo "</pre>";
    $movies = new WP_Query($args);
    if($movies->have_posts()):
        while ($movies->have_posts()): $movies->the_post(); 
        //get_template_part('templates/template-movies');
       // include_once(plugin_dir_path(__FILE__) . 'templates/template-movies.php');
       ?>

       <div class="column column-4">
                <?php if(has_post_thumbnail()): ?>
                    <picture><img width="500" height="250" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"> </picture>
                <?php endif; ?>
                <h4><?php the_title(); ?></h4>
                <?php
                    $cats = get_the_terms(get_the_ID(),'movie_type');
                    $popularity = get_post_meta(get_the_ID(), '_popularity', true);

                   
                    if(!empty($cats) || !empty($popularity)):
                ?>
                    <ul>
                        <?php if(!empty($cats)): ?>
                            <li>
                            <strong>Category: </strong>
                            <?php foreach ($cats as $cat){
                                echo "<span>$cat->name</span>";
                            }
                            ?>
                            </li>
                        <?php endif;

                        if(!empty($popularity)):?>
                            <li>
                            <strong>Rating: </strong>
                            <?php echo $popularity; ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>

       <?php 
       endwhile;
        wp_reset_postdata();
    else:
        echo "Post Not Found";
    endif;
    wp_die();
}