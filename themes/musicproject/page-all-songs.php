<?php 
get_header();

require get_theme_file_path('inc/songs-tabs-links.php');
$tabs = SONGS_TABS_LINKS; 
$all_songs = new WP_Query([
    'post_type' => 'song',
    'posts_per_page' => 50,
    'paged' => get_query_var('paged') ?: 1
])
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <?php if ($all_songs->found_posts) : ?>
        <div>
            <h2>
                All songs
            </h2>

            <div>
                <?php get_template_part('template-parts/songs-list', null, ['songs' => $all_songs]); ?>
            </div>
        </div>
    <?php endif ?>

    <?php if ($all_songs->found_posts > 50) : ?>
        <div class="upload-song-page-js cursor-pointer"
            data-all-songs="true"
            data-page=2
            data-max-pages="<?php echo $all_songs->max_num_pages ?>">
            view more
        </div>
    <?php endif ?>
</div>

<?php get_footer() ?>