<?php get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$my_tags = my_tags();
$songs = recomended_post_type(20, 'song', $my_tags);
$tabs = [
    [
        'title' => 'Recomended Songs',
        'slug' => 'recommended',
    ],
    [
        'title' => 'Recomended Artists',
        'slug' => 'recommended-artists',
    ],
];
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <?php if ($songs) : ?>
        <h1>
            Recomended tracks
        </h1>

        <div>
            <?php get_template_part('template-parts/songs-list', null, ['songs' => $songs]); ?>
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