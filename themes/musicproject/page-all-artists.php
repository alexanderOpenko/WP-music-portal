<?php get_header();
require get_theme_file_path('inc/artists-tabs-links.php');
$tabs = ARTIST_TABS;

$artists = new WP_Query([
    'post_type' => 'artist',
    'posts_per_page' => 15,
    'paged' => get_query_var('paged') ?: 1
]);
?>

<div class="content-container">
<?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]); ?>

    <?php if ($artists->found_posts) : ?>
        <?php get_template_part('template-parts/artists-grid', null, [
            'artists' => $artists,
            'title' => 'All artists'
        ]) ?>
    <?php endif ?>

    <?php if ($artists->found_posts > 15): ?>
        <div class="artists-page-js cursor-pointer"
            data-page=2
            data-max-pages="<?php echo $artists->max_num_pages ?>">
            view more
        </div>
    <?php endif ?>
</div>

<?php get_footer(); ?>