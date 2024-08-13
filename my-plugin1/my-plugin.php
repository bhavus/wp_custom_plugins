<?php
/**
 * Plugin Name: My Plugin
 * Plugin URI: http://xyz.com
 * Author: Shailesh Parmar
 * Author URI: http://xyz.com
 * Version: 1.0.0
 * Text Domain: my-plugin
 * Description: A smaple plugin to learn the plugin development.
 */
if( !defined('ABSPATH') ) : exit(); endif;

/**
 * Define plugin constants
 */
define( 'MYPLUGIN_PATH', trailingslashit( plugin_dir_path(__FILE__) ) );
define( 'MYPLUGIN_URL', trailingslashit( plugins_url('/', __FILE__) ) );

/**
 * Include admin.php
 */
if( is_admin() ) {
    require_once MYPLUGIN_PATH . '/admin/admin.php';
}

/**
 * Include public.php 
 */
if( !is_admin() ) {
    require_once MYPLUGIN_PATH . '/public/public.php';
}

/**
 * Include Post Types
 */
require_once MYPLUGIN_PATH . '/inc/post-types/movie.php';
require_once MYPLUGIN_PATH . '/inc/post-types/event_post_type.php';

/**
 * Inclide Taxonomies
 */
require_once MYPLUGIN_PATH . '/inc/taxonomies/movie-taxonomy.php';

/**
 * Include Metaboxes
 */
require_once MYPLUGIN_PATH . '/inc/metaboxes/movie-metaboxes.php';

/**
 * Inlcudes Data Tables
 */
require_once MYPLUGIN_PATH . '/inc/data-tables/movie-data-table.php';

/**
 * Include Admin Menus
 */
require_once MYPLUGIN_PATH . '/inc/menus/menus.php';

/**
 * Include Settings Page
 */
require_once MYPLUGIN_PATH . '/inc/settings/settings.php';

/**
 * Include Shortcodes
 */
require_once MYPLUGIN_PATH . '/inc/shortcodes/shortcodes.php';

/**
 * Include Custom Dashboard Widgets
 */
require_once MYPLUGIN_PATH . '/inc/dashboard/widgets.php';

/**
 * Include WordPress Custom WIdgets
 */
require_once MYPLUGIN_PATH . '/inc/widgets/movie-widget.php';

/**
 * Include WordPress Custom Rest API Endpoints
 */
require_once MYPLUGIN_PATH . '/inc/restapi/custom-news-plugin.php';

/**
 * Include WordPress Custom Rest API Endpoints
 */
require_once MYPLUGIN_PATH . '/class/class-options-page.php';

