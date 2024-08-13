<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        <?php
        while ( have_posts() ) :
            the_post();

            // get_template_part( 'template-parts/content', 'product' );
            include PRODUCT_LIST_DIR . 'template-parts/content-product.php';

            //pagination 
            the_post_navigation();

            //comments
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile;
        ?>
    </main>
</div>

<?php
get_sidebar();
get_footer();
