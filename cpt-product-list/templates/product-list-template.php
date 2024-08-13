

<?php

get_header(); ?>

<h1>Product List Template</h1>

<?php

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$orderby = (isset($_GET['orderby']) && in_array($_GET['orderby'], ['price_asc', 'price_desc', 'title_asc', 'title_desc', 'date_asc', 'date_desc'])) ? $_GET['orderby'] : 'price_asc';

$order = 'ASC';
$meta_key = '';

if ($orderby == 'price_asc' || $orderby == 'price_desc') {
    $meta_key = 'price';
    $order = $orderby == 'price_asc' ? 'ASC' : 'DESC';
} elseif ($orderby == 'title_asc' || $orderby == 'title_desc') {
    $meta_key = '';
    $order = $orderby == 'title_asc' ? 'ASC' : 'DESC';
} elseif ($orderby == 'date_asc' || $orderby == 'date_desc') {
    $meta_key = '';
    $order = $orderby == 'date_asc' ? 'ASC' : 'DESC';
}

$args = array(
    'post_type' => 'product',
    'posts_per_page' => -1,
    'paged' => $paged,
    'meta_key' => $meta_key,
    'orderby' => $meta_key ? 'meta_value_num' : ($orderby == 'date_asc' || $orderby == 'date_desc' ? 'date' : 'title'),
    'order' => $order
);

$query = new WP_Query($args);

if ($query->have_posts()) {
    echo '<form method="get" id="sortForm">';
    echo '<select name="orderby" onchange="document.getElementById(\'sortForm\').submit();">';
    echo '<option value="price_asc"' . selected($orderby, 'price_asc', false) . '>Price: Low to High</option>';
    echo '<option value="price_desc"' . selected($orderby, 'price_desc', false) . '>Price: High to Low</option>';
    echo '<option value="title_asc"' . selected($orderby, 'title_asc', false) . '>Title: A to Z</option>';
    echo '<option value="title_desc"' . selected($orderby, 'title_desc', false) . '>Title: Z to A</option>';
    echo '<option value="date_asc"' . selected($orderby, 'date_asc', false) . '>Date: Oldest to Newest</option>';
    echo '<option value="date_desc"' . selected($orderby, 'date_desc', false) . '>Date: Newest to Oldest</option>';
    echo '</select>';
    echo '</form>';

    echo '<table class="product-list">';
    echo '<tr class="sp-center"><th>Title</th><th>Price</th><th>Date</th></tr>';
    while ($query->have_posts()) {
        $query->the_post();
        $price = get_post_meta(get_the_ID(), 'price', true);
        $date = get_the_date();
        echo '<tr>';
        echo '<td><a href="' . get_permalink() . '">' . get_the_title() . '</a></td>';
        echo '<td>' . esc_html($price) . '</td>';
        echo '<td>' . esc_html($date) . '</td>';
        echo '</tr>';
    }
    echo '</table>';

    $big = 999999999; // need an unlikely integer
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $query->max_num_pages
    ));
    

} else {
    echo 'No products found.';
}

wp_reset_postdata();

// echo do_shortcode('[custom_product_search]');

?>


<?php

get_footer();
?>




