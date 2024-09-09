<?php 
    $my_saved_songs = my_saved_songs()['my_songs_ids'];
    $is_saved_song = false;

    if (in_array(get_the_id(), $my_saved_songs)) {
        $is_saved_song = true;
    }

    $save_icon_args = [
        'action' => 'save',
        'post_id' => get_the_id()
    ];

    if ($is_saved_song) {
        $save_icon_args['action'] = 'unsave';
        $save_icon_args['btn_class'] = 'saved-song'; 
    }
?> 

<div class="song-item relative flex items-center justify-between py-3 px-4 items-center border-0 border-b border-solid border-slate-300">
    <div class="flex items-center">
        <button class="song-play chartlist-play-button" data-song-id="<?php echo get_the_id() ?>" data-song-link="<?php echo str_replace([',', ' '], '', get_field('song_link')['url']) ?>">
        </button>

        <h3 class="m-0 !ml-5">
            <a class="ajax-link" href="<?php the_permalink(); ?>">
                <?php the_field('band') ?>
                -
                <?php the_title(); ?>
            </a>
        </h3>
    </div>

    <div class="flex">
        <?php
        if (get_the_author_meta('ID') === get_current_user_id()) : ?>
            <?php get_template_part('template-parts/delete-icon', null, ['post_type' => 'song', 'post_id' => get_the_id(), 'content-name' => get_the_title()]) ?>
        <?php endif;
            get_template_part('template-parts/save-icon', null, $save_icon_args);
        ?>
    </div>
</div>



