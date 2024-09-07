<?php
get_header();
$ID = 0;
$song_ids = [];
while (have_posts()) :
    the_post();
    $ID = get_the_ID();
    $tag_ids = get_field('tag', $ID, false);
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

    <div class="accordion">
        <div class="flex justify-between items-center mb-4">
            <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Edit tags', 'close_name' => 'Collapse tag edditing', 'is_open' => false]) ?>
        </div>

        <div class="accordion-content invisible h-0">
            <form class="update-song-tag">
                <input type="hidden" value="<?php echo $ID ?>" name="post_id" />
                <?php get_template_part('template-parts/tag-field', null, ['tag_ids' => $tag_ids]) ?>
                <button class="mb-2" type="submit">
                    submit
                </button>
            </form>
        </div>
    </div>

    <?php get_template_part('template-parts/tags-list', null, ['tag_ids' => $tag_ids]) ?>
    <?php get_template_part('template-parts/songs-list', null, ['song_ids' => $song_ids, 'data_attribute' => 'data-tag']) ?>
</div>

<?php get_footer(); ?>