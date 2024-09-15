<?php
$my_saved_songs = my_saved_items('songs', 'song', 1, -1)['my_items_ids'];

echo '<div class="songs-list">';

while ($args['songs']->have_posts()) {
    $save_icon_args = [
        'action' => 'save',
        'type' => 'songs'
    ];

    $is_saved_song = false;
    $args['songs']->the_post();
    $save_icon_args['post_id'] = get_the_id();

    if (in_array(get_the_id(), $my_saved_songs)) {
        $is_saved_song = true;
    }

    if ($is_saved_song) {
        $save_icon_args['action'] = 'unsave';
        $save_icon_args['btn_class'] = 'saved-song';
    }

    get_template_part('template-parts/song-item', null, ['save_icon_args' => $save_icon_args]);
}
echo '</div>';

wp_reset_postdata();
