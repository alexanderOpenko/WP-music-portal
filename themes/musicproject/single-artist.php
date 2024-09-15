<?php
get_header();
$ID = 0;
$song_ids = [];
while (have_posts()) {
    the_post();
    $ID = get_the_ID();
    $tag_ids = get_field('tag', $ID, false);
    $image_url = get_post_image_custom(get_field('artist_image'), 'full');
    $song_ids = get_field('songs') ? get_field('songs', '', false) : [];

    $my_saved_artists = my_saved_items('artist', 'artist', 1, -1)['my_items_ids'];
    $is_saved_artist = false;

    if (in_array(get_the_id(), $my_saved_artists)) {
        $is_saved_artist = true;
    }

    $save_icon_args = [
        'action' => 'save',
        'post_id' => get_the_id(),
        'type' => 'artist'
    ];

    if ($is_saved_artist) {
        $save_icon_args['action'] = 'unsave';
        $save_icon_args['btn_class'] = 'saved-song';
    }

    get_template_part('template-parts/banner', null, ['image_url' => $image_url, 'title' => 'play artist', 'save_icon_args' => $save_icon_args]);
}
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
                <div class="flex items-start w-full max-w-[550px]">
                    <?php get_template_part('template-parts/tag-field', null, ['tag_ids' => $tag_ids]) ?>
                    <button class="m-0 update-tag-button" type="submit">
                        submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php get_template_part('template-parts/tags-list', null, ['tag_ids' => $tag_ids]) ?>
    <?php get_template_part('template-parts/songs-list-by-id', null, ['song_ids' => $song_ids, 'data_attribute' => 'data-artist']) ?>
</div>

<?php get_footer(); ?>