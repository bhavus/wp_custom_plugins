<?php
/**************************************************************/
       /***** ADD META BOX ****/
/**************************************************************/
function add_custom_meta_box() {
    add_meta_box(
        'custom_meta_box', // $id
        'Custom Meta Box', // $title 
        'show_custom_meta_box', // $callback
        'product', // $page (changed to 'product' as per your custom post type)
        'normal', // $context
        'high' // $priority
    ); 
}
add_action('add_meta_boxes', 'add_custom_meta_box');

// Custom meta fields array
$prefix = 'custom_';
$custom_meta_fields = array(
    array(
        'label'=> 'Author Name',
        'desc'  => 'Enter the author name',
        'id'    => $prefix.'author_name',
        'type'  => 'text',
    ),
    array(
        'label'=> 'Price',
        'desc'  => 'Enter the price',
        'id'    => $prefix.'price',
        'type'  => 'number'
    ),
    array(
        'label'=> 'Author Image',
        'desc'  => 'Upload the author image',
        'id'    => $prefix.'author_image',
        'type'  => 'image'
    ),
    array(
        'label'=> 'Gender',
        'desc'  => 'Select the gender',
        'id'    => $prefix.'gender',
        'type'  => 'radio',
        'options' => array(
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other'
        )
    ),
    array(
        'label'=> 'Options',
        'desc'  => 'Select options',
        'id'    => $prefix.'options',
        'type'  => 'checkbox',
        'options' => array(
            'option1' => 'Option 1',
            'option2' => 'Option 2',
            'option3' => 'Option 3'
        )
    ),
    array(
        'label'=> 'Date',
        'desc'  => 'Enter the date',
        'id'    => $prefix.'date',
        'type'  => 'date',
    ),
    array(
        'label'=> 'Role',
        'desc'  => 'Select the role',
        'id'    => $prefix.'role',
        'type'  => 'select',
        'options' => array(
            'administrator' => 'Administrator',
            'editor' => 'Editor',
            'author' => 'Author',
            'subscriber' => 'Subscriber'
        )
    ),
    array(
        'label'=> 'Description',
        'desc'  => 'Enter the description',
        'id'    => $prefix.'description',
        'type'  => 'textarea',
    ),
    array(
        'label'=> 'File',
        'desc'  => 'Upload a file',
        'id'    => $prefix.'file',
        'type'  => 'file',
        'mime_types' => 'pdf,jpg,png'
    ),
    array(
        'label'=> 'Password',
        'desc'  => 'Enter a password',
        'id'    => $prefix.'password',
        'type'  => 'password',
    ),
    array(
        'label'=> 'Search',
        'desc'  => 'Enter search text',
        'id'    => $prefix.'search',
        'type'  => 'search',
    )
);
/**************************************************************/
       /***** SHOW META FIELD VALUE EDIT POST ****/
/**************************************************************/
// The callback function
function show_custom_meta_box() {
    
    global $custom_meta_fields, $post;

    // Use nonce for verification
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';

    foreach ($custom_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
        switch($field['type']) {
            // text field
            case 'text':
                echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                    <br /><span class="description">'.$field['desc'].'</span>';
                break;
            // number field
            case 'number':
                echo '<input type="number" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                    <br /><span class="description">'.$field['desc'].'</span>';
                break;
            // image field
            case 'image':
                echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                    <input type="button" class="button upload_image_button" value="Upload Image" />
                    <br /><span class="description">'.$field['desc'].'</span>';
                break;
            // radio field
            case 'radio':
                foreach ($field['options'] as $option => $label) {
                    $checked = ($meta == $option) ? ' checked="checked"' : '';
                    echo '<input type="radio" name="'.$field['id'].'" id="'.$field['id'].'_'.$option.'" value="'.$option.'"'.$checked.' />
                        <label for="'.$field['id'].'_'.$option.'">'.$label.'</label><br />';
                }
                echo '<span class="description">'.$field['desc'].'</span>';
                break;
            // checkbox field
            case 'checkbox':
                foreach ($field['options'] as $option => $label) {
                    $checked = is_array($meta) && in_array($option, $meta) ? ' checked="checked"' : '';
                    echo '<input type="checkbox" name="'.$field['id'].'[]" id="'.$field['id'].'_'.$option.'" value="'.$option.'"'.$checked.' />
                        <label for="'.$field['id'].'_'.$option.'">'.$label.'</label><br />';
                }
                echo '<span class="description">'.$field['desc'].'</span>';
                break;
            // date field
            case 'date':
                echo '<input type="date" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                    <br /><span class="description">'.$field['desc'].'</span>';
                break;
            // select field
            case 'select':
                echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                foreach ($field['options'] as $option => $label) {
                    $selected = ($meta == $option) ? ' selected="selected"' : '';
                    echo '<option value="'.$option.'"'.$selected.'>'.$label.'</option>';
                }
                echo '</select><br /><span class="description">'.$field['desc'].'</span>';
                break;
            // textarea field
            case 'textarea':
                echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" rows="5" cols="30">'.$meta.'</textarea>
                    <br /><span class="description">'.$field['desc'].'</span>';
                break;
            // file field
            case 'file':
                echo '<input type="file" name="'.$field['id'].'" id="'.$field['id'].'" />
                    <br /><span class="description">'.$field['desc'].' (Allowed file types: '.$field['mime_types'].')</span>';
                break;
            // password field
            case 'password':
                echo '<input type="password" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                    <br /><span class="description">'.$field['desc'].'</span>';
                break;
            // search field
            case 'search':
                echo '<input type="search" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                    <br /><span class="description">'.$field['desc'].'</span>';
                break;
        }
        echo '</td></tr>';
    }

    echo '</table>';
}
/**************************************************************/
       /***** SAVE META BOX ****/
/**************************************************************/
// Save the custom meta data
function save_custom_meta($post_id) {

    global $custom_meta_fields;

    // Check if nonce is set
    if (!isset($_POST['custom_meta_box_nonce'])) {
        return $post_id;
    }

    // Verify nonce
    $nonce = $_POST['custom_meta_box_nonce'];
    if (!wp_verify_nonce($nonce, basename(__FILE__))) {
        return $post_id;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;

    // Check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Loop through fields and save the data
    foreach ($custom_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = isset($_POST[$field['id']]) ? $_POST[$field['id']] : '';

        // Hash password field before saving
        if ($field['type'] === 'password' && !empty($new)) {
            $new = md5($new);
        }

        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    }
}
add_action('save_post', 'save_custom_meta');


// Enqueue media uploader script
function my_enqueue_media_uploader() {
    wp_enqueue_media();
    wp_enqueue_script('my-media-uploader', plugin_dir_url(__FILE__).'assets/js/media-uploader.js', array('jquery'));
}
add_action('admin_enqueue_scripts', 'my_enqueue_media_uploader');


/**************************************************************/
       /***** DISPLAY META VALUE FRONTEND SIDE ****/
/**************************************************************/

////////////// SHORTCODE [display_product_meta_value] ///////////////////////

add_shortcode('display_product_meta_value','product_all_meta_value_display');

// Add this function to display meta fields in the frontend
function product_all_meta_value_display() {
    $args = array(
        'post_type' => 'product', // Adjust post type if necessary
        'posts_per_page' => -1, // Retrieve all posts
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            // Output each post's meta fields
            echo '<div>';
            echo '<h2>' . get_the_title() . '</h2>';

            // Loop through each custom meta field
            global $custom_meta_fields;
            foreach ( $custom_meta_fields as $field ) {
                $meta_value = get_post_meta(get_the_ID(), $field['id'], true);

                // Display the meta_value field value if it exists
                if ( ! empty( $meta_value ) ) {

                    // Exclude password fields from being displayed
                    if ($field['type'] === 'password' && is_array( $meta_value )) {
                        // echo $meta_value;

                        continue;
                    }

                   
                    // Handle array for 'checkbox' type
                    if ( $field['type'] === 'checkbox' && is_array( $meta_value ) ) {
                        $options_output = array();
                        foreach ( $meta_value as $option ) {
                            if ( isset( $field['options'][ $option ] ) ) {
                                $options_output[] = $field['options'][ $option ];
                            }
                        }
                        echo '<p><strong>' . $field['label'] . ':</strong> '. implode( ', ', $options_output );
                    } else {
                        echo '<p><strong>' . $field['label'] . ':</strong> ' . $meta_value . '</p>'; // For other types, simply echo the value
                    }

                    echo '</p>';
                }
            } //end foreach loop

            echo '</div>';
        }
        wp_reset_postdata();
    } else {
        // no posts found
        echo 'No products found.';
    }
}






