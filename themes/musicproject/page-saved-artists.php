<?php
get_header();
require get_theme_file_path('inc/artists-tabs-links.php');
$tabs = ARTIST_TABS;
$paged = get_query_var('paged') ?: 1;
$saved_artists = my_saved_items('artist', 'artist', $paged, 15)['saved_items'];
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]);

    if (!is_user_logged_in()) : ?>
        <div class="bg-blue-100 text-blue-900 font-bold px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
            Only logged in users can save artists from search
        </div>
    <?php get_footer();
        return;
    endif
    ?>

    <?php if (is_user_logged_in() && !$saved_artists->found_posts) : ?>
        <div class="bg-blue-100 text-blue-900 font-bold px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
            Discover artists through search and save them
        </div>
    <?php get_footer();
        return;
    endif
    ?>

    <?php if ($saved_artists->found_posts) : ?>
        <?php get_template_part('template-parts/artists-grid', null, [
            'artists' => $saved_artists,
            'title' => 'Saved artists'
        ]) ?>
    <?php endif ?>

    <?php if ($saved_artists->found_posts > 15) : ?>
        <div class="paginate-saved-content-js cursor-pointer"
            data-type="saved-artists"
            data-page=2
            data-max-pages="<?php echo $saved_artists->max_num_pages ?>">
            view more
        </div>
    <?php endif ?>
</div>

<?php get_footer(); ?>