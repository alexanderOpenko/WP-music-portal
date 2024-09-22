<?php
get_header();
$paged = get_query_var('paged') ?: 1;

$saved_songs = my_saved_items('songs', 'song', $paged, 15)['saved_items'];

require get_theme_file_path('inc/songs-tabs-links.php');
$tabs = SONGS_TABS_LINKS;
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); 
    if (!is_user_logged_in()) {
        echo '<div> Only logged in users can save songs </div>';
        wp_footer();
        return;
    }
    ?>
    
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

    <?php if ($saved_songs->found_posts > 15) : ?>
        <div class="paginate-saved-content-js cursor-pointer"
            data-type="saved-songs"
            data-page=2
            data-max-pages="<?php echo $saved_songs->max_num_pages ?>">
            view more
        </div>
    <?php endif ?>
</div>

<?php
get_footer()
?>