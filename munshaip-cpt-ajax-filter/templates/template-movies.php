<?php
/**
 * Template Name: Template Movies
 */

get_header();

$args =[
    'post_type' => 'movie',
    'posts_per_page' => -1,
    ];

    $movies = new WP_Query($args); ?>
    <main>

        <div class="movie_container" style="width: 80%; margin: 0 auto;">
            <br>
            <div class="js-filter">
                <?php $terms = get_terms(['taxonomy'=>'movie_type']);
                if($terms):?>
                    <select id="cat" name="cat">
                        <option value="">Select Category</option>
                        <?php foreach ($terms as $term): ?>
                            <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                        <?php endforeach;?>
                    </select>
                <?php endif; ?>
                <select name="popularity" id="popularity">
                    <option value="">Select Popularity</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>

        <?php if($movies->have_posts()): ?>
            <div class="js-movies row">
                <?php while ($movies->have_posts()): $movies->the_post(); ?>







                <div class="column column-4">
                <?php if(has_post_thumbnail()): ?>
                    <picture><img width="500" height="250" src="<?php the_post_thumbnail_url(); ?>" alt="<?php the_title(); ?>"> </picture>
                <?php endif; ?>
                <h4><?php the_title(); ?></h4>
                <?php
                    $cats = get_the_terms(get_the_ID(),'movie_type');
                    $popularity = get_post_meta(get_the_ID(), '_popularity', true);

                   
                    if(!empty($cats) || !empty($popularity)):
                ?>
                    <ul>
                        <?php if(!empty($cats)): ?>
                            <li>
                            <strong>Category: </strong>
                            <?php foreach ($cats as $cat){
                                echo "<span>$cat->name</span>";
                            }
                            ?>
                            </li>
                        <?php endif;

                        if(!empty($popularity)):?>
                            <li>
                            <strong>Rating: </strong>
                            <?php echo $popularity; ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>

                







                <?php
                endwhile;
                ?>
            </div>
        <?php endif; ?>
        </div>
    </main>


    <?php
get_footer();
?>