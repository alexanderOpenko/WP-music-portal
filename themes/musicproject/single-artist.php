<?php
get_header();
$ID = 0;
$song_ids = [];
while (have_posts()) :
    the_post();
    $ID = get_the_ID();
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
        if (!empty(get_the_content())) : ?>
            <div class="mb-8">
                <h2>
                    About
                </h2>
                <?php the_content(); ?>
            </div>
        <?php endif ?>
    <?php endwhile; ?>

    <?php $tag_ids = get_field('tag', $ID, false);
        get_template_part('template-parts/tags-list', null, ['tag_ids' => $tag_ids])
    ?>

    <?php get_template_part('template-parts/songs-list', null, ['song_ids' => $song_ids, 'data_attribute' => 'data-tag']) ?>
</div>

<?php get_footer(); ?>