<?php
/**
 * Plugin Name: Authors - Simple profiles
 * Plugin URI: https://www.xyz.in
 * Description: Simple user profiles.
 * Author: Shailesh Parmar
 * Author URI: https://www.xyz.in
 * Version: 0.1.0
 * Text Domain: spp
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Setup Constants
 */
// Plugin Folder Path.
if ( ! defined( 'ABA_PLUGIN_URL' ) ) {
    define( 'ABA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
// Plugin Folder URL.
if ( ! defined( 'ABA_PLUGIN_DIR' ) ) {
    define( 'ABA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

add_action( 'admin_enqueue_scripts','register_admin_assets');
function register_admin_assets() {
	// Only on authors posttype.
	if ( 'authors' !== get_post_type() ) {
		return;
	}

	$hooks = array(
		'post.php',
		'post-new.php',
	);

	if ( ! in_array( $hook, $hooks, true ) ) {
		return;
	}

	// Enable media assets.
	if ( ! did_action( 'wp_enqueue_media' ) ) {
		wp_enqueue_media();
	}

	// Scripts.
	wp_register_script(
		'aba-script',
		ABA_PLUGIN_URL . 'assets/js/admin.js',
		array( 'jquery' ),
		filemtime( ABA_PLUGIN_DIR . 'assets/js/admin.js' ),
		true
	);
	wp_enqueue_script( 'aba-script' );

	// Styles.
	wp_register_style(
		'aba',
		ABA_PLUGIN_URL . 'assets/css/admin.css',
		null,
		filemtime( ABA_PLUGIN_DIR . 'assets/css/admin.css' )
	);
	wp_enqueue_style( 'aba' );
}

add_action( 'wp_enqueue_scripts','register_assets');
function register_assets() {
	if ( 'authors' !== get_post_type() ) {
		return;
	}

	// Styles.
	wp_register_style(
		'aba',
		ABA_PLUGIN_URL . 'assets/css/style.css',
		null,
		filemtime( ABA_PLUGIN_DIR . 'assets/css/style.css' )
	);
	wp_enqueue_style( 'aba' );
}

add_action( 'init','mnsp_register_posttype');
function mnsp_register_posttype() {
	$labels = array(
		'name'                  => _x( 'Authors', 'Post Type General Name', 'aba' ),
		'singular_name'         => _x( 'authors', 'Post Type Singular Name', 'aba' ),
		'menu_name'             => __( 'Authors', 'aba' ),
		'name_admin_bar'        => __( 'Authors', 'aba' ),
		'archives'              => __( 'Authors Archives', 'aba' ),
		'attributes'            => __( 'Author Attributes', 'aba' ),
		'parent_item_colon'     => __( 'Parent Author:', 'aba' ),
		'all_items'             => __( 'All Authors', 'aba' ),
		'add_new_item'          => __( 'Add New Author', 'aba' ),
		'add_new'               => __( 'Add New', 'aba' ),
		'new_item'              => __( 'New Author', 'aba' ),
		'edit_item'             => __( 'Edit Author', 'aba' ),
		'update_item'           => __( 'Update Author', 'aba' ),
		'view_item'             => __( 'View Author', 'aba' ),
		'view_items'            => __( 'View Authors', 'aba' ),
		'search_items'          => __( 'Search Author', 'aba' ),
		'not_found'             => __( 'Not found', 'aba' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'aba' ),
		'featured_image'        => __( 'Featured Image', 'aba' ),
		'set_featured_image'    => __( 'Set featured image', 'aba' ),
		'remove_featured_image' => __( 'Remove featured image', 'aba' ),
		'use_featured_image'    => __( 'Use as featured image', 'aba' ),
		'insert_into_item'      => __( 'Insert into Author', 'aba' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Author', 'aba' ),
		'items_list'            => __( 'Authors list', 'aba' ),
		'items_list_navigation' => __( 'Authors list navigation', 'aba' ),
		'filter_items_list'     => __( 'Filter suthors list', 'aba' ),
	);
	$args   = array(
		'label'                => __( 'authors', 'aba' ),
		'description'          => __( 'Author Profiles', 'aba' ),
		'labels'               => $labels,
		'supports'             => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'custom-fields',"page-attributes", "post-formats", "gallery" ),
		'taxonomies'           => array(),
		// 'register_meta_box_cb' =>  'register_metabox',
		'hierarchical'         => false,
		'public'               => true,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'menu_position'        => 5,
		'menu_icon'            => 'dashicons-admin-users',
		'show_in_admin_bar'    => true,
		'show_in_nav_menus'    => true,
		'can_export'           => true,
		'has_archive'          => true,
		'exclude_from_search'  => true,
		'publicly_queryable'   => true,
		'capability_type'      => 'post',
		'show_in_rest'         => false,
	);
	register_post_type( 'authors', $args );
}

add_filter( 'post_updated_messages','updated_messages', 10, 1 );
function updated_messages( $messages ) {
	$post             = get_post();
	$post_type        = get_post_type( $post );
	$post_type_object = get_post_type_object( $post_type );

	$messages['authors'] = array(
		0  => '', // Unused. Messages start at index 1.
		1  => __( 'Author updated.', 'aba' ),
		2  => __( 'Custom field updated.', 'aba' ),
		3  => __( 'Custom field deleted.', 'aba' ),
		4  => __( 'Author updated.', 'aba' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Author restored to revision from %s', 'aba' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => __( 'Author published.', 'aba' ),
		7  => __( 'Author saved.', 'aba' ),
		8  => __( 'Author submitted.', 'aba' ),
		9  => sprintf(
			/* translators: %1$s: date and time of the revision */
			__( 'Author scheduled for: <strong>%1$s</strong>.', 'aba' ),
			// translators: Publish box date format, see http://php.net/date.
			date_i18n( __( 'M j, Y @ G:i', 'aba' ), strtotime( $post->post_date ) )
		),
		10 => __( 'Author draft updated.', 'aba' ),
	);

	if ( $post_type_object->publicly_queryable ) {
		$permalink = get_permalink( $post->ID );

		$view_link               = sprintf( '&nbsp;<a href="%s">%s</a>', esc_url( $permalink ), __( 'View author', 'aba' ) );
		$messages['authors'][1] .= $view_link;
		$messages['authors'][6] .= $view_link;
		$messages['authors'][9] .= $view_link;

		$preview_permalink           = add_query_arg( 'preview', 'true', $permalink );
		$preview_link                = sprintf( '<a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview author', 'aba' ) );
		$messages[ $post_type ][8]  .= $preview_link;
		$messages[ $post_type ][10] .= $preview_link;
	}

	return $messages;
}

add_action( 'add_meta_boxes','register_metabox', 10, 1 );
function register_metabox() {
	add_meta_box(
		'aba_metabox',
		'Details',
		'render_metabox',
		'authors',
		'normal',
		'default'
	);
	add_meta_box('author-id', 'Event Author', 'my_display_callback_author', 'authors','side','low');
}

function render_metabox( $post ) {
	require_once 'includes/views/meta-fields.php';
}

add_action( 'save_post','save_meta', 10, 2 );
function save_meta( $post_id, $post ) {

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( 'authors' !== $post->post_type ) {
		return;
	}

	// nonce check.
	if ( ! isset( $_POST['_aba_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_aba_meta_nonce'] ) ), 'aba_author_meta' ) ) {
		return;
	}

	$fields = array(
		'first_name',
		'last_name',
		'biography',
		'facebook_url',
		'linkedin_url',
		'user_id',
		'image_id',
		'gallery_image_ids',
	);

	foreach ( $fields as $field ) {
		if ( isset( $_POST[ $field ] ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( wp_unslash( $_POST[ $field ] ) ) );
		} else {
			delete_post_meta( $post_id, $field );
		}
	}
}

add_filter( 'wp_insert_post_data','save_title', '99', 2 );
function save_title( $data, $postarr ) {

	if ( 'authors' !== $data['post_type'] ) {
		return;
	}

	// nonce check.
	if ( ! isset( $_POST['_aba_title_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_aba_title_nonce'] ) ), 'aba_author_title' ) ) {
		return $data;
	}

	$first_name  = ( ! empty( $_POST['first_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : get_post_meta( $postarr['ID'], 'first_name', true );
	$last_name   = ( ! empty( $_POST['last_name'] ) ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : get_post_meta( $postarr['ID'], 'last_name', true );
	$author_name = "{$first_name} {$last_name}";

	if ( '' !== $author_name ) {
		$data['post_title'] = $author_name;
		$data['post_name']  = sanitize_title( sanitize_title_with_dashes( $author_name, '', 'save' ) );
	}

	return $data;
}


/*-------------------------------------------------------------
  Assign custom template to single authors post type
--------------------------------------------------------------*/

function get_single_and_archive_template( $template ) {
    global $post;


 
   if (isset($post) && $post->post_type == 'authors') {

        if ( is_singular( 'authors' ) ) {
            $template = ABA_PLUGIN_DIR  . 'templates/single-authors.php';
            if (file_exists($template)) {
                return $template;
            }
        } elseif ( is_post_type_archive( 'authors' ) ) {
            $template = ABA_PLUGIN_DIR  . 'templates/archive-authors.php';
        } 

    } 

    return $template;
}
add_filter( 'template_include', 'get_single_and_archive_template' );




/*-------------------------------------------------------------
 ( medic_filter_by_Author ) to event post type
--------------------------------------------------------------*/
     function my_display_callback_author($post){
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


    add_action('save_post','author_save_data',10,2);
    function author_save_data($post_id,$post){
        $author = isset($_POST['author_select']) ? $_POST['author_select'] : ''; 
        update_post_meta($post_id,'author_select', $author);
    }

    //start filter author
    add_action('restrict_manage_posts', 'author_filter');
    function author_filter(){
        global $typenow;
        if ($typenow == 'authors') {
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

    add_filter( 'parse_query','filter_by_author' );
    function filter_by_author($query){
        global $typenow;
        global $pagenow;
        $author_id= isset($_GET['author_filter']) ? $_GET['author_filter'] : '';
        if($typenow == 'authors' && $pagenow == 'edit.php' && !empty($author_id)){
            $query->query_vars["meta_key"]   = 'author_select';
            $query->query_vars["meta_value"] = $author_id;

        }
    }