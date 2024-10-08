<?php
$song_ids = $args['song_ids'];

if (count($song_ids)) :
    $limited_song_ids = array_slice($song_ids, 0, 10);

    $uploaded_songs = new WP_Query([
        'post_type' => 'song',
        'post__in' => $limited_song_ids,
    ]); ?>

    <h2 class="mb-[15px]">
        Tracklist
    </h2>

    <?php get_template_part('template-parts/songs-list', null, ['songs' => $uploaded_songs]); ?>

    <?php if (count($song_ids) > 10) : ?>
        <div class="upload-song-page-js cursor-pointer" 
        <?php echo $args['data_attribute'] . '=' . get_the_ID() ?> 
        data-page=2 
        data-max-pages="<?php echo $uploaded_songs->max_num_pages ?>"
        >
            view more
        </div>
    <?php endif ?>
<?php endif ?>