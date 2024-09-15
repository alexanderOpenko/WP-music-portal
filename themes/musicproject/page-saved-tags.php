<?php
get_header();
require get_theme_file_path('inc/tags-tabs-links.php');
$tabs = TAG_TABS;
$paged = get_query_var('paged') ?: 1;
$saved_tags = my_saved_items('tag', 'musictag', $paged, 15)['saved_items'];
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <?php if ($saved_tags->found_posts) : ?>
        <?php get_template_part('template-parts/tags-grid', null, ['tags' => $saved_tags]) ?>
    <?php endif ?>

    <?php if ($saved_tags->found_posts > 15) : ?>
        <div class="paginate-saved-content-js cursor-pointer"
            data-type="saved-tags"
            data-page=2
            data-max-pages="<?php echo $saved_tags->max_num_pages ?>">
            view more
        </div>
    <?php endif ?>
</div>

<?php get_footer(); ?>