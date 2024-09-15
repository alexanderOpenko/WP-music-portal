<?php get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$artists = getMostPopularArtists(9, $paged);
require get_theme_file_path('inc/artists-tabs-links.php');
$tabs = ARTIST_TABS;
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <?php if ($artists) : ?>
        <div>
            <?php get_template_part('template-parts/artists-grid', null, [
                    'artists' => $artists,
                    'title' => 'Popular Artists'
                ]) ?>
        </div>

        <?php
        echo paginate_links([
            'total' => $artists->max_num_pages,
            'current' => $paged,
            'prev_text' => __('« Previous'),
            'next_text' => __('Next »')
        ]);

        wp_reset_query();
        ?>
    <?php endif ?>
</div>

<?php get_footer() ?>