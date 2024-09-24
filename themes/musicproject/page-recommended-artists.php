<?php get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$artists = recomended_post_type(20, 'artist', my_tags(), $paged);

$tabs = [
    [
        'title' => 'Recommended Songs',
        'slug' => 'recommended',
    ],
    [
        'title' => 'Recommended Artists',
        'slug' => 'recommended-artists',
    ],
];
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <?php if ($artists) : ?>
        <div>
            <?php get_template_part('template-parts/artists-grid', null, [
                    'artists' => $artists,
                    'title' => 'Recommended Artists'
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