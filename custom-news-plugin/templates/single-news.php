<?php get_header(); ?>



    
<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while (have_posts()) :
            the_post();
            get_template_part('content', 'news');
            the_post_navigation();
        endwhile;
        ?>
    </main>
</div>

<!-- Step 5: Add HTML for Popup -->

<div id="popup" style="display:none;">
    <div id="popup-content"></div>
    <button id="popup-close">Close</button>
</div>

<!-- Step 6: Add Search Form and Category Filter -->

<form id="search-form">
    <input type="text" id="search-input" placeholder="Search News">
    <button type="submit">Search</button>
</form>
<div id="search-results"></div>

<select id="category-filter">
    <option value="">Select Category</option>
    <?php
    $categories = get_categories(array(
        'taxonomy' => 'category',
        'type' => 'news'
    ));
    foreach ($categories as $category) {
        echo '<option value="' . $category->slug . '">' . $category->name . '</option>';
    }
    ?>
</select>
<div id="category-results"></div>


<?php get_sidebar(); ?>
<?php

// Step 7: Add Pagination

the_posts_pagination(array(
    'mid_size'  => 2,
    'prev_text' => __('Back', 'textdomain'),
    'next_text' => __('Next', 'textdomain'),
));
?>

<?php get_footer(); ?>
