<?php
get_header();
$saved_songs = my_saved_items('songs', 'song')['saved_items'];

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
                <?php get_template_part('template-parts/songs-list', null, ['songs' => $saved_songs]); ?>
            </div>
        </div>
    <?php endif ?>
</div>

<?php
get_footer()
?>