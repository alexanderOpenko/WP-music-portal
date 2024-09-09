<?php
get_header();
$saved_songs = my_saved_songs()['saved_songs'];

$tabs = [
    [
        'title' => 'Uploaded songs',
        'slug' => 'favorite-songs',
    ],
    [
        'title' => 'Saved song',
        'slug' => 'saved-songs',
    ],
];
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>
    <?php if (!is_user_logged_in()) : ?>
        <div>
            Only logged in users can save songs
        </div>
    <?php endif ?>

    <?php if (is_user_logged_in() && !$saved_songs->found_posts) : ?>
        <div>
            discover songs from search and save them
        </div>
    <?php endif ?>

    <?php if ($saved_songs->found_posts) : ?>
        <div>
            <h2>
                Saved Tracks
            </h2>

            <div>
                <div class="favsongs-page-list">
                    <?php while ($saved_songs->have_posts()) {
                        $saved_songs->the_post();
                        get_template_part('template-parts/song-item');
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<?php
get_footer()
?>