<?php
//admin menu in plugin

function cpt_product_list_plugin_menu(){

  $capability  = apply_filters( 'product_required_capabilities', 'manage_options' );
  $parent_slug = 'product_parent_menu';

  add_menu_page('Product list Page title', 'Product List', $capability, $parent_slug, 'product_list_main_func');
  add_submenu_page($parent_slug, 'Settings page title', 'Product Settings', $capability, 'product-settings', 'product_list_func_settings');
  add_submenu_page($parent_slug, 'FAQ page title', 'Product FAQ', $capability, 'product-list-faq', 'product_list_faq_func');
  add_submenu_page($parent_slug, 'Product List Table', 'Product List Table', 
    $capability, 'product-list-table', 'product_list_table_func');
}
add_action('admin_menu', 'cpt_product_list_plugin_menu');

function product_list_main_func(){
    echo "Product list Main Page";
}

function product_list_func_settings(){
    echo "Product list Settings Page";
}

function product_list_faq_func(){
    echo "Product list FAQ Page";
}

function product_list_table_func(){
   echo '<div class="wrap">

     <h2>Product List Table - with Pagination</h2>';
 

        
 echo  '<table class="wp-list-table widefat fixed pages">
  
  <tbody id="the-list">

   <thead>
    <tr>
        <th class="manage-column column-name" scope="col">ID</th>
        <th class="manage-column column-name" scope="col">Title</th>
        <th class="manage-column column-name" scope="col">Price</th>
        <th class="manage-column column-name" scope="col">Date</th>
      
    </tr>
   </thead>

   <tfoot>
    <tr>
        <th class="manage-column column-name" scope="col">ID</th>
        <th class="manage-column column-name" scope="col">Title</th>
        <th class="manage-column column-name" scope="col">Price</th>
        <th class="manage-column column-name" scope="col">Date</th>
   
    </tr>
   </tfoot>';

   
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'meta_key' => 'price',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    );

    $query = new WP_Query($args);

   if ($query->have_posts()) {

      while ($query->have_posts()) {
            $query->the_post();
            $price = get_post_meta(get_the_ID(), 'price', true);
            $date = get_the_date();
        
            echo '<tr class="type-page">
                    <td>' . get_the_ID() . '</td>  
                    <td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>
                    <td>' . esc_html($price) . '</td>  
                    <td>'. esc_html($date) .'</td>
                 </tr>';
        }
  
    echo  '</tbody>
           </table>';
    
    echo '</div>';   //wrapper end


  } else {
        echo 'No products found.';
    }
    wp_reset_postdata();

}

