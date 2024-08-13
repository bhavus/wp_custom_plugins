<?php

//third example shortcode google ads
function get_adsense($atts) {
    return '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- WordPress hosting -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-0327511395451286"
     data-ad-slot="6566424785"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>';
}
add_shortcode('adsense', 'get_adsense');

//9 example A Google Maps shortcode
function googlemap_function($atts, $content = null) {
   extract(shortcode_atts(array(
      "width" => '640',
      "height" => '480',
      "src" => ’
   ), $atts));
   return '<iframe width="'.$width.'" height="'.$height.'" src="'.$src.'&output=embed" ></iframe>';
}
add_shortcode("googlemap", "googlemap_function");
 
//10 example PDF EMBEDDING
function pdf_function($attr, $url) {
   extract(shortcode_atts(array(
       'width' => '640',
       'height' => '480'
   ), $attr));
   return '<iframe src="http://docs.google.com/viewer?url=' . $url . '&embedded=true" style="width:' .$width. '; height:' .$height. ';">Your browser does not support iframes</iframe>';
}
add_shortcode('pdf', 'pdf_function'); 


// last 11 example Displaying database table data in shortcodes
function listpost_func()
{
 global $wpdb;
    $postshow .= '<div class="recent-posts">';
	 $sql_post = $wpdb->get_results("SELECT * FROM wp_posts WHERE post_status='publish'");
	 foreach ($sql_post as $rows_post) 
	 {
	  $post_title = $rows_post->post_title;  
	   $postshow .= '<h6>'.$post_title.'</h6>';
	 }
	    $postshow .= '</div>';
	 return "$postshow";
}
add_shortcode( 'listpost', 'listpost_func' ); 

function recent_posts_func( $atts, $content = NULL ){
    $content = $content?$content:'Latest Posts';
    $a = shortcode_atts(
        array(
            'posts'=>5
        ),
        $atts
    );
    $args = array('numberposts'=>$a['posts']);
    $recent_posts = wp_get_recent_posts( $args, ARRAY_A );
    echo '<div class="recent-posts">';
    echo '<h1>'.$content.'</h1>';
    foreach($recent_posts as $post){
    ?>
    <div class="updated"><p><?php echo $post['post_title']; ?>. <a href="<?php echo get_permalink($post["ID"]); ?>"><span>Show Details</span>.</a></p></div>
    <?php
    }
    echo '</div>';
}
add_shortcode( 'latestposts', 'recent_posts_func' );


/*
function remove_menus(){
  remove_menu_page( 'index.php' );                  //Dashboard
  remove_menu_page( 'jetpack' );                    //Jetpack* 
  remove_menu_page( 'edit.php' );                   //Posts
  remove_menu_page( 'upload.php' );                 //Media
  remove_menu_page( 'edit.php?post_type=page' );    //Pages
  remove_menu_page( 'edit-comments.php' );          //Comments
  remove_menu_page( 'themes.php' );                 //Appearance
  remove_menu_page( 'plugins.php' );                //Plugins
  remove_menu_page( 'users.php' );                  //Users
  remove_menu_page( 'tools.php' );                  //Tools
  remove_menu_page( 'options-general.php' );        //Settings
}
add_action( 'admin_menu', 'remove_menus' );

// define('DISALLOW_FILE_MODS',false);  //this code wp-config.php file inner added

*/



////////////////////////////////////////////////////////////////////////////
/*
function init_sessions() {
    if (!session_id()) {
        session_start();
    }
}
add_action('init', 'init_sessions'); */

///////////////////////////////////////////////////////////////////////

// Create a URL from a string of text with PHP like wordpress

/*
function create_slug($string){
   $string = preg_replace( '/[«»""!?,.!@£$%^&*{};:()]+/', '', $string );
   $string = strtolower($string);
   $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
   return $slug;
}
$slug = create_slug('This is my page title');
echo $slug;
*/
// this should print out: this-is-my-page-title

////////////////////////////////////////////////////////////////////////////

/*
// How to: Add a Share on facebook link to your WordPress blog

// Paste the following code on your single.php file, within the loop:

<a href="http://www.facebook.com/sharer.php?u=<?php the_permalink();?>&t=<?php the_title(); ?>" target="blank">Share on Facebook</a> */

////////////////////////////////////////////////////////////////////////////

// Displaying Number of Comments in your WordPress Blog

/*
$numcomms = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_approved = '1'");
if (0 < $numcomms) $numcomms = number_format($numcomms);
echo "There's ".$numcomms." total comments on my blog";
*/

////////////////////////////////////////////////////////////////////////////

// Display Most Popular Posts in WordPress


// <h2>Popular Posts</h2>
// <ul>
?>
<?php $result = $wpdb->get_results("SELECT
comment_count,ID,post_title FROM $wpdb->posts ORDER BY comment_count
DESC LIMIT 0 , 5");
foreach ($result as $post) {
setup_postdata($post);
$postid = $post->ID;
$title = $post->post_title;
$commentcount = $post->comment_count;
if ($commentcount != 0) { ?>

<li><a href="<?php echo get_permalink($postid); ?>" title="<?php echo $title ?>">
<?php echo $title ?></a> {<?php echo $commentcount ?>}</li>
<?php } } ?>
</ul>
<?php

////////////////////////////////////////////////////////////////////////////

// get the ID of the last insert post in WordPress

// <div id="div1">

//insert new post
// Create post object
/*
$my_post = array();
$my_post['post_title'] = 'Hello world';
$my_post['post_content'] = 'This is a sample post';
$my_post['post_status'] = 'published';
$my_post['post_author'] = 7; //the id of the author
$my_post['post_category'] = array(10,12); //the id's of the categories

// Insert the post into the database
$post_id = wp_insert_post( $my_post ); //store new post id into $post_id

//now add tags to new post
$tags = array('html', 'css', 'javascript');
wp_set_object_terms( $post_id, $tags, 'post_tag', true );

*/
 // </div> 


////////////////////////////////////////////////////////////////////////////

// wp_nav_menu change sub-menu class name?

// from <ul class="sub-menu"> to <ul class="dropdown-menu">

// You can use WordPress preg_replace filter (in your theme functions.php file) example:

/*
function new_submenu_class($menu) {    
    $menu = preg_replace('/ class="sub-menu"/',' class="dropdown-menu" ',$menu);        
    return $menu;      
}
add_filter('wp_nav_menu','new_submenu_class'); 
*/

////////////////////////////////////////////////////////////////////////////

//8 example WORDPRESS MENU
function menu_function($atts, $content = null) {
   extract(
      shortcode_atts(
         array( 'name' => null, ),
         $atts
      )
   );
   return wp_nav_menu(
      array(
          'menu' => $name,
          'echo' => false
          )
   );
}
add_shortcode('menu', 'menu_function');