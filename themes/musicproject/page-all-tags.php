<?php
get_header();

require get_theme_file_path('inc/tags-tabs-links.php');
$tabs = TAG_TABS;

$all_tags = new WP_Query([
    'post_type' => 'musictag',
    'posts_per_page' => 50,
    'paged' => get_query_var('page')
])
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <div>
        <h2>
            All tags
        </h2>

        <?php get_template_part('template-parts/tags-grid', null, ['tags' => $all_tags]) ?>
    </div>

    <?php if ($all_tags->found_posts > 50): ?>
        <div class="tags-page-js cursor-pointer"
            data-page=2
            data-max-pages="<?php echo $all_tags->max_num_pages ?>">
            view more
        </div>
    <?php endif ?>
</div>

<?php get_footer() ?>