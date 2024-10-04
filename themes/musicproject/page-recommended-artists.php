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

    <?php if ($artists->found_posts) : ?>
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
        <?php else : ?>
        <div class="bg-blue-100 text-blue-900 font-bold text-[1.2em] px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
            There are no recommendations yet. Please save songs/tags from your search or create tags to receive music suggestions.
        </div>
    <?php endif ?>
</div>

<?php get_footer() ?>