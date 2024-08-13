<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

    <?php
    if ( have_posts() ) :

        while ( have_posts() ) :
            the_post();

            // get_template_part( 'template-parts/content', 'product' );
            include PRODUCT_LIST_DIR . 'template-parts/content-product.php';

        endwhile;

        the_posts_navigation();

    else :

        // get_template_part( 'template-parts/content', 'none' );
        include PRODUCT_LIST_DIR . 'template-parts/content-none.php';

    endif;
    ?>

    </main>
</div>

<?php
get_sidebar();
get_footer();
