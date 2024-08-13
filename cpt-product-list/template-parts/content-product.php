<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
       <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
    <p class="mb-2 mt-1"> <?php the_date(); ?>  , By: <?php the_author_posts_link(); ?></p>
    </header>

    <div class="entry-content">

        <?php
            if(has_post_thumbnail()){
                the_post_thumbnail('large',array('class' => 'img-fluid')); 
             } else { ?>
                  <img src="<?php echo PRODUCT_LIST_URL . '/assets/images/default-thumbnail.jpg'; ?>" class="img-fluid mb-3" alt="Default Thumbnail">
            <?php } ?> 
        <?php
        
        $price = get_post_meta(get_the_ID(), 'price', true);

        echo !empty($price) ? '<p>Price: ' . esc_html($price) . '</p>' : '';
        
        the_content();

        //related category name display 
        $terms = get_the_terms( get_the_ID(), 'prod_cat' );

        if ( $terms && ! is_wp_error( $terms ) ) : 
            echo '<ul class="product-categories">';
            foreach ( $terms as $term ) {
                echo '<li>' . esc_html( $term->name ) . '</li>';
            }
            echo '</ul>';
        endif;
    

     

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'text_domain' ),
            'after'  => '</div>',
        ) );
        ?>
    </div>

    <footer class="entry-footer">
        <?php edit_post_link( esc_html__( 'Edit', 'text_domain' ), '<span class="edit-link">', '</span>' ); ?>
    </footer>
</article>
