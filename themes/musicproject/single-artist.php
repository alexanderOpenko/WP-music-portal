<?php
get_header();
$ID = 0;
$image_id = 0;
$song_ids = [];
while (have_posts()) {
    the_post();
    $ID = get_the_ID();
    $post_author = get_the_author_meta('ID');
    $content = get_the_content(null);
    $tag_ids = get_field('tag', $ID, false);
    $image_id = get_field('artist_image', false, false);
    $image_url = get_post_image_custom($image_id, 'full');
    $song_ids = get_field('songs') ? get_field('songs', '', false) : [];
    $my_saved_artists = my_saved_items('artist', 'artist', 1, -1)['my_items_ids'];
    $is_saved_artist = false;

    $artists = recomended_post_type(6, 'artist', $tag_ids);

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

    get_template_part('template-parts/banner', null, [
        'image_url' => $image_url,
        'title' => 'play artist',
        'save_icon_args' => $save_icon_args,
        'listens' => get_field('play_count'),
    ]);
}
?>

<div class="content-container">
    <?php
    if (get_the_author_meta('ID') === get_current_user_id()) {
        get_template_part('template-parts/delete-icon', null, ['post_type' => 'artist', 'post_id' => get_the_id(), 'content-name' => get_the_title()]);
    }
    ?>

    <div class="flex lg:flex-row flex-col mb-4 border-0 border-b-2 border-solid border-slate-300 pb-6">
        <div class="w-full lg:max-w-2/4 max-w-full">
            <?php get_template_part('template-parts/edit-description', null, [
                'post_type' => 'artist',
                'post_id' => $ID,
                'content' => $content,
                'post_author' => $post_author
            ]) ?>
        </div>

        <?php get_template_part('template-parts/update-image',  null, [
            'field_name' => 'artist_image',
            'image_id' => $image_id,
            'ID' => $ID
        ]) ?>
    </div>

    <div class="accordion">
        <?php if (get_the_author_meta('ID') === get_current_user_id()) : ?>
            <div class="flex justify-between items-center mb-4">
                <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Edit tags', 'close_name' => 'Collapse tag edditing', 'is_open' => false]) ?>
            </div>
        <?php endif ?>

        <div class="accordion-content invisible">
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

    <?php get_template_part('template-parts/similar-artists', null, [
        'artists' => $artists,
        'current_artist' => $ID
    ]) ?>
</div>

<?php get_footer(); ?>