<?php get_header();
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$my_tags = my_tags();
$songs = recomended_post_type(20, 'song', $my_tags, $paged);

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

    <?php if ($songs->found_posts) : ?>
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
    <?php else : ?>
        <div class="bg-blue-100 text-blue-900 font-bold text-[1.2em] px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
        There are no recommendations yet. Please save songs/tags from your search or create tags to receive music suggestions.
        </div>
    <?php endif ?>
</div>

<?php wp_reset_query();
        get_footer() ?>