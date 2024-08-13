<?php


// Enqueue Bootstrap and add modal and post CRUD code here

function enqueue_bootstrap_and_js() {
   // Enqueue Bootstrap CSS
   wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css');

   // Enqueue Bootstrap JavaScript with Popper.js
   wp_enqueue_script('popper-js', 'https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js', array('jquery'), '2.11.6', true);
   wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js', array('jquery', 'popper-js'), '5.2.1', true);

   // Enqueue custom JavaScript for modal and CRUD operations
   wp_enqueue_script('custom-js', plugin_dir_url(__FILE__) . 'assets/js/custom.js', array('jquery', 'bootstrap-js'), '1.0', true);

   // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
   wp_localize_script( 'custom-js', 'ajax_object',array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
}
add_action('wp_enqueue_scripts', 'enqueue_bootstrap_and_js');

function create_post_modal() {
   echo '
   <button type="button" id="openModal" class="btn btn-primary">Create Post</button>

   <div class="modal fade" id="postModal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
       <div class="modal-dialog">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="modalTitle">Create Post</h5>
                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
                   <input type="hidden" id="postID">
                   <div class="mb-3">
                       <label for="postTitle" class="form-label">Title</label>
                       <input type="text" class="form-control" id="postTitle">
                   </div>
                   <div class="mb-3">
                       <label for="postContent" class="form-label">Content</label>
                       <textarea class="form-control" id="postContent"></textarea>
                   </div>
                   <div class="mb-3">
                       <label for="product_price" class="form-label">Price</label>
                       <input type="number" class="form-control" id="product_price">
                   </div>
                   <div class="mb-3">
                       <label for="postThumbnail" class="form-label">Thumbnail</label>
                       <input type="file" class="form-control" id="postThumbnail">
                   </div>
               </div>
               <div class="modal-footer">
                   <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                   <button type="button" id="savePost" class="btn btn-primary">Save</button>
               </div>
           </div>
       </div>
   </div>
   ';
}
add_shortcode('create_post_modal', 'create_post_modal');


// Create or update a post
function save_post() {
   $post_id = isset($_POST['postID']) ? intval($_POST['postID']) : 0;
   $post_title = sanitize_text_field($_POST['postTitle']);
   $post_content = wp_kses_post($_POST['postContent']);
   $product_price = sanitize_text_field($_POST['product_price']);
   $thumbnail_id = null;

   if (isset($_FILES['postThumbnail']) && !empty($_FILES['postThumbnail']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');


        $uploaded_file = $_FILES['postThumbnail'];
        $upload_overrides = array('test_form' => false);

        $movefile = wp_handle_upload($uploaded_file, $upload_overrides);

        if ($movefile && !isset($movefile['error'])) {
            $thumbnail_id = $movefile['id'];
        } else {
            echo 'error';
            wp_die();
        }

        
    }

   $post_data = array(
       'ID' => $post_id,
       'post_title' => $post_title,
       'post_content' => $post_content,
       'post_type' => 'post',
       'post_status' => 'publish',
       'orderby'=>'meta_value',
       'meta_key' => 'product_price',
       'order'=>'ASC'
   );

   if ($post_id === 0) {
       $result = wp_insert_post($post_data);
   } else {
       $result = wp_update_post($post_data);
   }

   if ($result !== 0) {
        // Save thumbnail
        if ($thumbnail_id ) {
            set_post_thumbnail($result, $thumbnail_id);
        }
        // Save price as custom field
        update_post_meta($result, 'product_price', $product_price);
        echo 'success';
    } else {
        echo 'error';
    }

   wp_die();
}
add_action('wp_ajax_save_post', 'save_post');
add_action('wp_ajax_nopriv_save_post', 'save_post');

// Delete a post
function delete_post() {
   $post_id = intval($_POST['postID']);

   $result = wp_delete_post($post_id);

   if ($result !== false) {
       echo 'success';
   } else {
       echo 'error';
   }

   wp_die();
}
add_action('wp_ajax_delete_post', 'delete_post');
add_action('wp_ajax_nopriv_delete_post', 'delete_post');


function display_posts() {
   $posts = get_posts(array(
       'post_type' => 'post',
       'posts_per_page' => -1,
   ));

   $output = '<ul>';

   foreach ($posts as $post) {
       $product_price = get_post_meta($post->ID, 'product_price', true);

              
       $thumbnail = get_the_post_thumbnail($post->ID, 'thumbnail'); // Get the post thumbnail

        $output .= '<li>';
        if ($thumbnail) {
            $output .= '<span style="max-width: 100px; height: auto; margin-right: 10px class="post-thumbnail">' . $thumbnail . '</span>';
        }
       
       $output .= '<strong>' . esc_html($post->post_title) . '</strong>';
       $output .= !empty($product_price)? '  $ ' . esc_html($product_price) : '';
       $output .= ' (<a href="#" class="edit-post" data-id="' . $post->ID . '" data-title="' . esc_attr($post->post_title) . '" data-content="' . esc_attr($post->post_content) . '" data-price="' . esc_attr($product_price) . '">Edit</a>';
       $output .= ' | <a href="#" class="delete-post" data-id="' . $post->ID . '">Delete</a>)</li>';
   }

   $output .= '</ul>';

   return $output;
}
add_shortcode('display_posts', 'display_posts');


