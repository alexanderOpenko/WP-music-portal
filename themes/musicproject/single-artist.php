<?php
get_header();

while (have_posts()) :
    the_post();
    $image_url = get_post_image_custom(get_field('artist_image'), 'full');
    $song_ids = get_field('songs') ? get_field('songs', '', false) : [];
    get_template_part('template-parts/banner', null, ['image_url' => $image_url, 'title' => 'play artist'])
?>

<?php endwhile;
wp_reset_postdata();
?>

<div class="content-container">
    <?php
    while (have_posts()) :
        the_post();
        if (!empty(get_the_content())) :?>
            <div class="mb-8">
            <h2>
                About
            </h2>
                <?php the_content(); ?>
            </div>
        <?php endif ?>
    <?php endwhile; ?>

    <?php if (count(get_field('tag'))): 
            $tags = new WP_Query([
                'post_type' => 'musictag',
                'post__in' => get_field('tag', '', false),
            ]); ?>

            <div class="flex mb-8">
                <?php while($tags->have_posts()): 
                    $tags->the_post();
                    ?>
                    <button class="tag-button mr-3">
                        <?php the_title(); ?>
                    </button>
                <?php endwhile?>
            </div>
        <?php endif ?>

    <?php get_template_part('template-parts/songs-list', null, ['song_ids' => $song_ids, 'data_attribute' => 'data-tag']) ?>
</div>

<?php get_footer(); ?>