<?php

//* Don't access this file directly
defined( 'ABSPATH' ) or die();

/*------------------------------------*\
    Create Custom Post Types
\*------------------------------------*/
add_action('init', 'spp_event_post_type');
function spp_event_post_type() {

    // Events Custom Post
    $event_labels = array(
            'labels' => array(
            'name' => __('Events', 'vicodemedia'),
            'singular_name' => __('Event', 'vicodemedia'),
            // 'featured_image' =>  __('Event Image', 'vicodemedia'),
            // 'set_featured_image' => 'Set Event Image',
            // 'remove_featured_image' => 'Remove Event Image',
            // 'use_featured_image' => 'Use as Event Image',
            'add_new' => __('Add New Event', 'vicodemedia'),
            'add_new_item' => __('Add New Event', 'vicodemedia'),
            'edit_item' => __('Edit Event', 'vicodemedia'),
            'new_item' => __('New Event', 'vicodemedia'),
            'view_item' => __('View Event', 'vicodemedia'),
            'view_items' => __('View Events', 'vicodemedia'),
            'search_items' => __('Search Events', 'vicodemedia'),
            'not_found' => __('No events found.', 'vicodemedia'),
            'not_found_in_trash' => __('No events found in trash.', 'vicodemedia'),
            'all_items' => __('All Events', 'vicodemedia'),
            'archives' => __('Event Archives', 'vicodemedia'),
            'insert_into_item' => __('Insert into Event', 'vicodemedia'),
            'uploaded_to_this_item' => __('Uploaded to this Event', 'vicodemedia'),
            'filter_items_list' => __('Filter Events list', 'vicodemedia'),
            'items_list_navigation' => __('Events list navigation', 'vicodemedia'),
            'items_list' => __('Events list', 'vicodemedia'),
            'item_published' => __('Event published.', 'vicodemedia'),
            'item_published_privately' => __('Event published privately.', 'vicodemedia'),
            'item_reverted_to_draft' => __('Event reverted to draft.', 'vicodemedia'),
            'item_scheduled' => __('Event scheduled.', 'vicodemedia'),
            'item_updated' => __('Event updated.', 'vicodemedia')
           )
        );

    $event_args = array(
        'public' => true,
        'labels' => $event_labels,
        'label' => __('Events', 'lawenforce'),
        'has_archive'   => true,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var'    => true,
        'rewrite'      => array('slug' => 'event'),
        'show_in_rest' => true,
        'supports' => array('title','editor','excerpt', 'thumbnail', 'gallery'),
        'can_export' => true
    );
    register_post_type('event', $event_args);

     // Add new taxonomy, make it hierarchical (like categories)
    $cat_labels = array(
        'name' => _x('Events Categories', 'taxonomy general name', 'textdomain'),
        'singular_name' => _x('event Category', 'taxonomy singular name', 'textdomain'),
        'search_items' => __('Search Categories', 'textdomain'),
        'all_items' => __('All Categories', 'textdomain'),
        'parent_item' => __('Parent Category', 'textdomain'),
        'parent_item_colon' => __('Parent Category:', 'textdomain'),
        'edit_item' => __('Edit Category', 'textdomain'),
        'update_item' => __('Update Category', 'textdomain'),
        'add_new_item' => __('Add New Category', 'textdomain'),
        'new_item_name' => __('New Category Name', 'textdomain'),
        'menu_name' => __('Events Category', 'textdomain'),
    );

    $cat_args = array(
        'hierarchical' => true,
        'labels' => $cat_labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'event_category'),
    );
    register_taxonomy('event_category', array('event'), $cat_args);

    //You can register a custom taxonomy for your event post type to serve as custom event tags. 
   
        $tag_labels = array(
            'name' => 'Event Tags',
            'singular_name' => 'Event Tag',
            'search_items' => 'Search Event Tags',
            'all_items' => 'All Event Tags',
            'edit_item' => 'Edit Event Tag',
            'update_item' => 'Update Event Tag',
            'add_new_item' => 'Add New Event Tag',
            'new_item_name' => 'New Event Tag Name',
            'menu_name' => 'Event Tags',
        );

        $tag_args = array(
            'hierarchical' => false, // Set to true if you want hierarchical tags like categories
            'labels' => $tag_labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'event-tag'), // Customize the slug
        );
        register_taxonomy('event_tag', 'event', $tag_args);
 
}



/*-------------------------------------------------------------
  post editor Update (Add title) Placeholder change to (Add event name)
--------------------------------------------------------------*/

function myplugin_update_event_title_placeholder($title) {
    $screen = get_current_screen();
    if( 'event' === $screen->post_type ) {
        $title = 'Add event name';
    }

    return $title;
}
add_filter('enter_title_here', 'myplugin_update_event_title_placeholder');

/*-------------------------------------------------------------
  Rename Featured image to Event Image
--------------------------------------------------------------*/
function spp_rename_featured_image_label($args, $post_type) {
    if ($post_type === 'event') { // Replace 'post' with the post type where you want to rename the label
        $args['labels']['featured_image'] = 'Event Image';
        $args['labels']['set_featured_image'] = 'Set Event Image';
        $args['labels']['remove_featured_image'] = 'Remove Event Image';
        $args['labels']['use_featured_image'] = 'Use as Event Image';
    }
    return $args;
}
add_filter('register_post_type_args', 'spp_rename_featured_image_label', 10, 2);

/*-------------------------------------------------------------
  Add custom column to ( Event Thumbnail and Event Date ) event post type
--------------------------------------------------------------*/
function spp_add_event_thumbnail_column($column) {
    $column['event_thumbnail'] = 'Event Thumbnail';
    $column['event_date'] = 'Event Date';
    return $column;
}
add_filter('manage_edit-event_columns', 'spp_add_event_thumbnail_column');

// Display event thumbnail in custom column
function spp_display_event_thumbnail_column($column, $post_id) {
  
 switch($column){
    case 'event_thumbnail':
        $e_thumbnail = get_the_post_thumbnail($post_id, 'thumbnail'); 
        echo $e_thumbnail ? $e_thumbnail : 'No Image';
        break;
    case 'event_date':
        $e_date = get_post_meta($post_id, 'event_date', true);
        echo $e_date ? $e_date : 'No Date';
        break;
  }
}
add_action('manage_event_posts_custom_column', 'spp_display_event_thumbnail_column', 10, 2);
/*-------------------------------------------------------------
  Make the (column name) Column Sortable to events post type
--------------------------------------------------------------*/
function spp_sortable_column($column) {
    $column['event_date'] = 'event_date';
    return $column;
}
add_filter('manage_edit-event_sortable_columns', 'spp_sortable_column');

/*-------------------------------------------------------------
  (step-1) add event date meta field to events post type
--------------------------------------------------------------*/
function spp_add_post_meta_boxes() {
    add_meta_box(
        "post_metadata_events_post", // div id containing rendered fields
        "Event Date", // section heading displayed as text
        "spp_event_date_callback", // callback function to render fields
        "event", // name of post type on which to render fields
        "side", // location on the screen
        "low" // placement priority
    );
    add_meta_box('author-id', 'Event Author', 'spp_my_display_callback_author', 'event','side','low');
   
}
add_action( "admin_init", "spp_add_post_meta_boxes" );

/*-------------------------------------------------------------
  (step-2) above callback function Design html
--------------------------------------------------------------*/
function spp_event_date_callback($post)
{
    global $post;
    $event_date = get_post_meta($post->ID, 'event_date', true);
    ?>
    <label for="event_date"><?php echo esc_html__('Event Date:'); ?></label>
    <input type="date" id="event_date" name="event_date" value="<?php echo esc_attr($event_date); ?>">
    <?php
}

/*-------------------------------------------------------------
  (Step-3) Save post publish then event metabox saved event date
--------------------------------------------------------------*/
function spp_save_event_date($post_id) {
    if (array_key_exists('event_date', $_POST)) {
        update_post_meta($post_id, 'event_date', sanitize_text_field($_POST['event_date']));
    }
}
add_action('save_post', 'spp_save_event_date');

/*-------------------------------------------------------------
  Assign custom template to single event post type
--------------------------------------------------------------*/
function spp_load_event_template( $template ) {
    global $post;
    if ( 'event' === $post->post_type && locate_template( array( 'single-event.php' ) ) !== $template ) {
        return plugin_dir_path( __FILE__ ) . 'single-event.php';
    }

    return $template;
}
add_filter( 'single_template', 'spp_load_event_template' );


/*-------------------------------------------------------------
  Event Post list display by Shortcode
--------------------------------------------------------------*/
add_shortcode('events_list', 'spp_events_list');
function spp_events_list(){
    global $post;
    $args = array(
        'post_type'=>'event', 
        'post_status'=>'publish', 
        'posts_per_page'=>10, 
        'orderby'=>'meta_value',
        'meta_key' => 'event_date',
        'order'=>'ASC'
    );
    $query = new WP_Query($args);

    // ob_start();

    $content = '<ul>';
    if($query->have_posts()):
        while($query->have_posts()): $query->the_post();
            // trash event if old
            $exp_date = get_post_meta(get_the_ID(), 'event_date', true);
            // set the correct timezone
            date_default_timezone_set('America/New_York');
            $today = new DateTime();
            if($exp_date < $today->format('Y-m-d h:i:sa')){
                // Update post
                $current_post = get_post( get_the_ID(), 'ARRAY_A' );
                $current_post['post_status'] = 'trash';
                wp_update_post($current_post);
            }
            // display event
            $content .= '<li><a href="'.get_the_permalink().'">'. get_the_title() .'</a> - '.date_format(date_create(get_post_meta($post->ID, 'event_date', true)), 'jS F').'</li>'; 
        endwhile;
    else: 
        _e('Sorry, nothing to display.', 'vicodemedia');
    endif;
    $content .= '</ul>';

  // return ob_get_clean();
    return $content;
}

/*-------------------------------------------------------------
 ( spp_filter_by_category ) to event post type
--------------------------------------------------------------*/
add_action('restrict_manage_posts','spp_category_filter');
    function spp_category_filter(){
        global $typenow;
        $show_taxonomy = 'event_category';
        $selected_event_category_id = isset($_GET[$show_taxonomy]) ? intval($_GET[$show_taxonomy]) : "";

        if($typenow == 'event'){
            $args = array(
                'show_option_all' => 'Show all',
                'taxonomy' => $show_taxonomy,
                'show_count' => true,
                'name'=> $show_taxonomy,
                'selected' => $selected_event_category_id,
            );
            wp_dropdown_categories($args);
        }
    }


add_filter('parse_query', 'spp_filter_by_category');

function spp_filter_by_category($query){
    global $typenow;
    global $pagenow;
    $post_type = 'event';
    $taxonomy = 'event_category';
    $query_var = &$query->query_vars;

    if ($typenow == $post_type && $pagenow == 'edit.php' && isset($query_var[$taxonomy]) && is_numeric($query_var[$taxonomy])) {
        $term_details = get_term_by("id", $query_var[$taxonomy], $taxonomy);

        // Check if $term_details is a valid term object
        if ($term_details && !is_wp_error($term_details)) {
            $query_var[$taxonomy] = $term_details->slug;
        }
    }
}
/*-------------------------------------------------------------
 ( spp_filter_by_Author ) to event post type
--------------------------------------------------------------*/
     function spp_my_display_callback_author($post){
        ?>
        <select name="author_select" id="<?php echo $post->ID; ?>">
            <option value="">Select Author</option> <!-- Add a default option -->
            <?php 
            $author_select = get_post_meta($post->ID, 'author_select', true);
            $authors = get_users(array('role' => 'author'));

            foreach ($authors as $author) {
                $select = '';
                if ($author_select == $author->ID) {
                    $select = 'selected="selected"';
                }
                ?>
                <option value="<?php echo $author->ID; ?>" <?php echo $select; ?>><?php echo $author->display_name; ?></option>
                <?php
            }
            ?>
        </select>
        <?php
    }


    add_action('save_post','spp_author_save_data',10,2);
    function spp_author_save_data($post_id,$post){
        $author = isset($_POST['author']) ? $_POST['author'] : ''; 
        update_post_meta($post_id,'author_select', $author);
    }

    //start filter author
    add_action('restrict_manage_posts', 'spp_author_filter');
    function spp_author_filter(){
        global $typenow;
        if ($typenow == 'event') {
            // Check if the 'author_filter' parameter exists in the URL
            $author_id = isset($_GET['author_filter']) ? $_GET['author_filter'] : '';
            $args = array(
                'role'             => 'author',
                'show_option_none' => 'Select Author',
                'name'             => 'author_filter',
                'id'               => 'all_author_fileter',   // integer
                'selected'         => $author_id,
            );
            wp_dropdown_users($args);
        }
    }

    add_filter( 'parse_query','spp_filter_by_author' );
    function spp_filter_by_author($query){
        global $typenow;
        global $pagenow;
        $author_id= isset($_GET['author_filter']) ? $_GET['author_filter'] : '';
        if($typenow == 'event' && $pagenow == 'edit.php' && !empty($author_id)){
            $query->query_vars["meta_key"]   = 'author_select';
            $query->query_vars["meta_value"] = $author_id;

        }
    }

/*-------------------------------------------------------------
 ( spp_add_role_by_employee ) to Add Role employee
--------------------------------------------------------------*/
function spp_add_role_by_employee() {
  add_role(
    'employee',
    __( 'Event Employee' ),
    array(
      'read'           => true,  // true allows this capability
      'edit_posts'     => true,
      'delete_posts'   => true, // Use false to explicitly deny
      'manage_options' => true
    ) );
  // gets the author role
  $role = get_role( 'employee' );
       // add capability to the author role
  $role->add_cap( 'view_dashboard' );
}
add_action( 'admin_init', 'spp_add_role_by_employee' );


/*-------------------------------------------------------------
 ( spp_bulk_actions  post editor  ) 
--------------------------------------------------------------*/


// Define a custom bulk action
function custom_bulk_action($actions) {
    $actions['email_to_me'] = 'Email to Me';
    return $actions;
}
add_filter('bulk_actions-edit-event', 'custom_bulk_action'); // Replace 'events' with your post type name

// Handle the custom bulk action
function handle_bulk_post_event_email($redirect_to, $actions, $post_ids) {
    if ($actions === 'email_to_me') {
        $bulk_messages = '';
        // Perform your custom action on the selected posts
        foreach ($post_ids as $post_id) {
            // Replace this with your custom logic
            // Example: Update post meta, change post status, etc.
            $bulk_messages .= get_the_title($post_id) . "\n \n";
        }
        wp_mail('shailesh5180@gmail.com', 'List Of Posts', $bulk_messages);

        // Redirect back to the post list with a success message
        $redirect_to = add_query_arg('bulk_emailed_posts', count($post_ids), $redirect_to);
    }
    return $redirect_to;
}
add_filter('handle_bulk_actions-edit-event', 'handle_bulk_post_event_email', 10, 3); // Replace 'events' with your post type name

// Display a success message upon custom bulk action completion
function display_bulk_action_message($bulk_messages, $bulk_counts) {
    if (!empty($bulk_counts['bulk_emailed_posts'])) {
        $bulk_messages['post']['email_to_me'] = _n(
            '%s post updated.',
            '%s posts updated.',
            intval($bulk_counts['bulk_emailed_posts']),
            printf('<div id="message" class="updated fade">Emailed %d post(s) to Alex</div>', $bulk_messages)
        );
    }
    return $bulk_messages;
}
add_filter('bulk_post_updated_messages', 'display_bulk_action_message', 10, 2);


add_action('admin_notices', function(){

    if (!empty($_REQUEST['bulk_emailed_posts'])) {
        $emailed_count = intval($_REQUEST['bulk_emailed_posts']);
        printf('<div id="message" class="updated fade">Emailed %d Post(s) to Shailesh Parmar</div>', $emailed_count);
    }

});


/*-------------------------------------------------------------
 ( custom spp theme upload csv file post import data   ) 
--------------------------------------------------------------*/
/*
add_action('admin_notices', function(){
  echo "<br>";
    spp_csv_file_upload_callback();

});


function spp_csv_file_upload_callback()
{
   
    ?>

     <!-- Form -->
<form method='post' action='<?= $_SERVER['REQUEST_URI']; ?>' enctype='multipart/form-data'>
  <input type="file" name="import_file" accept=".csv">
  <input type="submit" name="butimport" value="Import CSV">
</form>

    <?php

    // Import CSV
if(isset($_POST['butimport'])){

  // File extension
  $extension = pathinfo($_FILES['import_file']['name'], PATHINFO_EXTENSION);

  // If file extension is 'csv'
  if(!empty($_FILES['import_file']['name']) && $extension == 'csv'){

    $totalInserted = 0;

    // Open file in read mode
    $csvFile = fopen($_FILES['import_file']['tmp_name'], 'r');

    fgetcsv($csvFile); // Skipping header row

    $i = 0;
    while (($data = fgetcsv($csvFile)) !== false) {

        // if($i > 0){ // skip header
        print_r($data);
            
            if(!empty($data[3])){

                $cat_slugs = explode(',', $data[3]);

                $cat_ids = [];
                
                if($cat_slugs){
                
                    foreach($cat_slugs as $slug){
                
                        $term_obj = get_category_by_slug( $slug );
                
                
                        if($term_obj !== false){
                
                            $cat_ids[] = $term_obj->term_id;
                
                        }else{ // category doesn't exist, let's make it
                
                            $cat_id = wp_create_category( $slug );
                
                            if(!is_wp_error( $cat_id )){
                                $cat_ids[] = $cat_id;
                            }
                
                        }
                
                    }
                
                }
                
            }

            $post_id = wp_insert_post( [
                'post_title' => $data[0],
                'post_content' => $data[1],
                'post_category' => $cat_ids,
                'post_status' => 'publish'
            ] );

             if($post_id > 0){
                $totalInserted++;
              }

            if(!is_wp_error( $post_id )){

                if(!empty($data[2])){
                
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/image.php');
                    
                    $attachment_id = media_sideload_image( $data[2], $post_id, '', 'id' );
                
                    if(!is_wp_error( $attachment_id )){
                
                        set_post_thumbnail( $post_id, $attachment_id );
                
                    }
                    
                }

            }


        // }
        $i++;
    }

}

}


}


/////////////////////////////////


// load more function by ajax
add_action('wp_ajax_load_posts_by_ajax', 'load_posts_by_ajax_callback');
add_action('wp_ajax_nopriv_load_posts_by_ajax', 'load_posts_by_ajax_callback');

function load_posts_by_ajax_callback(){
  check_ajax_referer('load_more_posts', 'security');
  $paged= $_POST['page'];

  $args = array(

    'post_type' => 'portfolio',
    'post_status' => 'publish',
    'posts_per_page' => 4,
    'paged' =>$paged,
  );

  $my_posts = new WP_Query($args);

  if ($my_posts-> have_posts()) { ?>

    <?php
      while($my_posts-> have_posts()){
        $my_posts-> the_post();

        $termsArray = get_the_terms( $post->ID, "porfiolio_category" );
        $termsSlug = ""; //initialize the variable that will contain the terms
        foreach ( $termsArray as $term ) { // for each term
        $termsSlug .= $term->slug.' '; //create a string that has all the slugs
        }

         ?>

        <div class="single-content <?php echo  $termsSLug; ?>  grid-item">
              <img class="p2" src="<?php the_post_thumbnail_url(); ?>">
        </div>
    <?php  }
    ?>
<?php  }

wp_die();
}

?>
<!-- /////////////////////////////// -->
 <?php if ($wp_query->max_num_pages >1) { ?>
                  <div class="button_load_more ">
                      <div class="btn_wrapper">
                            <a  class="btn btn-default  btn_style_more loadmore "href="#">Load More</a>
                      </div>

                  </div>
              <?php  }?>

              <!-- ////////////////////////////////// -->



<script type="text/javascript">
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    var page =2;
    jQuery(function($){
        // init isotope
        var $grid = $('.grid');
        $grid.isotope({
          // options
          itemSelector: '.grid-item',
          percentPosition: true,
        });

        $('body').on('click', '.loadmore', function(e){

          var data = {
            'action': 'load_posts_by_ajax',
            'page': page,
            'security': '<?php echo wp_create_nonce("load_more_posts"); ?>'
          };

          $.post(ajaxurl, data, function(response){
            if (response != '') {
              var $answer = $(response);

              //append items to grid
              $grid.append($answer)
              .isotope('appended', $answer);

              // layaout on imagesLoaded
              $grid.imagesLoaded(function(){
                $grid.isotope('layout');
              });
              page++;
            } else{
              $('.loadmore').text("No more Post!");
              $('.loadmore').attr("disabled", true);
              $('.loadmore').css("borderColor", "gray");
              $('.loadmore').css("color", "gray");
            }
          });
          e.preventDefault();
        });
    });
</script>

*/