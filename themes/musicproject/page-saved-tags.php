<?php
get_header();
require get_theme_file_path('inc/tags-tabs-links.php');
$tabs = TAG_TABS;
$paged = get_query_var('paged') ?: 1;
$saved_tags = my_saved_items('tag', 'musictag', $paged, 15)['saved_items'];
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]);

    if (!is_user_logged_in()) : ?>
        <div class="bg-blue-100 text-blue-900 font-bold px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
            Only logged in users can save tags from search
        </div>
    <?php get_footer();
        return;
    endif;
    ?>

<?php if (is_user_logged_in() && !$saved_tags->found_posts) : ?>
        <div class="bg-blue-100 text-blue-900 font-bold px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
            Discover tags through search and save them, or save songs to display their tags in this tab
        </div>
        <?php get_footer();
        return;
    endif
    ?>

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