<?php

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Plugin activation hook
// register_activation_hook(__FILE__, 'cf7_custom_db_install');

add_action('init','cf7_custom_db_install');

function cf7_custom_db_install() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'cf7_submissions';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        form_id mediumint(9) NOT NULL,
        name tinytext NOT NULL,
        email varchar(100) NOT NULL,
        submission_date datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Hook into Contact Form 7 submission
add_action('wpcf7_before_send_mail', 'cf7_custom_db_save_submission');

function cf7_custom_db_save_submission($contact_form) {
    global $wpdb;

    $submission = WPCF7_Submission::get_instance();
    // $contact_form = $submission->get_contact_form();
    if ($submission) {
        $data = $submission->get_posted_data();
        $form_id = $contact_form->id();
        $name = isset($data['your-name']) ? sanitize_text_field($data['your-name']) : '';
        $email = isset($data['your-email']) ? sanitize_email($data['your-email']) : '';
        // $form_data = maybe_serialize($data);

        $table_name = $wpdb->prefix . 'cf7_submissions';

        $wpdb->insert(
            $table_name,
            array(
                'form_id' => $form_id,
                'name' => $name,
                'email' => $email,
                'submission_date' => current_time('mysql'),
            )
        );
    }
}

// Add admin menu item
add_action('admin_menu', 'cf7_custom_db_admin_menu');

function cf7_custom_db_admin_menu() {
    add_menu_page('CF7 Submissions', 'CF7 Submissions', 'manage_options', 'cf7-submissions', 'cf7_custom_db_admin_page', 'dashicons-list-view', 6);
}

function cf7_custom_db_admin_page() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'cf7_submissions';
    $submissions = $wpdb->get_results("SELECT * FROM $table_name");

    echo '<div class="wrap"><h1>Contact Form 7 Submissions</h1>';
    echo '<table class="widefat fixed" cellspacing="0">';
    echo '<thead><tr><th>ID</th><th>Form ID</th><th>Name</th><th>Email</th><th>Submission Date</th></tr></thead>';
    echo '<tbody>';

    foreach ($submissions as $submission) {
        echo '<tr>';
        echo '<td>' . esc_html($submission->id) . '</td>';
        echo '<td>' . esc_html($submission->form_id) . '</td>';
        echo '<td>' . esc_html($submission->name) . '</td>';
        echo '<td>' . esc_html($submission->email) . '</td>';
        echo '<td>' . esc_html($submission->submission_date) . '</td>';
        echo '</tr>';
    }

    echo '</tbody></table></div>';
}
