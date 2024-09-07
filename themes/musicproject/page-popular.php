<?php get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$songs = getMostPopularSongs(20, $paged);
$tabs = [
    [
        'title' => 'Popular Songs',
        'slug' => 'popular',
    ],
    [
        'title' => 'Popular Artists',
        'slug' => 'popular-artists',
    ],
];
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <?php if ($songs) : ?>
        <h1>
            Popular tracks
        </h1>

        <div>
            <?php while ($songs->have_posts()) {
                $songs->the_post();
                get_template_part('template-parts/song-item');
            }
            ?>
        </div>

        <div class="text-center">
        <?php
        echo paginate_links([
            'total' => $songs->max_num_pages,
            'current' => $paged,
            'prev_text' => __('« Previous'),
            'next_text' => __('Next »')
        ]);

        ?>
        </div>
    <?php endif ?>
</div>

<?php wp_reset_query();
        get_footer() ?>