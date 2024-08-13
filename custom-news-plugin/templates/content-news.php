

<article id="post-<?php the_ID(); ?>" <?php post_class('news-post'); ?> data-id="<?php the_ID(); ?>">
    <header class="entry-header">
        <?php
        if (is_singular()) :
            the_title('<h1 class="entry-title">', '</h1>');
        else :
            the_title('<h2 class="entry-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        endif;
        ?>
    </header>

    <div class="entry-content">
        <?php
        the_content(sprintf(
            __('Continue reading %s <span class="meta-nav">&rarr;</span>', 'textdomain'),
            the_title('<span class="screen-reader-text">"', '"</span>', false)
        ));
        ?>
    </div>
</article>
