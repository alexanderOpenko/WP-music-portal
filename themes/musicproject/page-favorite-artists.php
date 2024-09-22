<?php get_header();
$myArtists = new WP_Query([
    'author__in' => [get_current_user_id()],
    'post_type' => 'artist',
    'posts_per_page' => 15
]);

require get_theme_file_path('inc/artists-tabs-links.php');
$tabs = ARTIST_TABS;
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]);

    if (!is_user_logged_in()) {
        echo '<div> Only logged in users can upload artists </div>';
        wp_footer();
        return;
    }
    ?>

    <div class="accordion">
        <div class="flex justify-between items-center">
            <h3>
                Add artist to your list
            </h3>
            <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Open form', 'close_name' => 'Close form', 'is_open' => true]) ?>
        </div>

        <div class="accordion-content visible">
            <form class="artist-form">
                <div class="flex">
                    <div class="max-w-1/2 w-full mr-4">
                        <label for="title">
                            Name
                        </label>
                        <input name="band" required class="input" type="text" id="title" />

                        <label for="content">
                            Description
                        </label>
                        <textarea name="artist_content" id="content" class="input h-[110px]" type="text"></textarea>
                    </div>

                    <div class="max-w-1/2 w-full">
                        <label for="image">
                            Image
                        </label>
                        <?php get_template_part('template-parts/upload-button', null, ['image_name' => 'artist_image']);
                        get_template_part('template-parts/image-holder');
                        ?>

                        <div class="accordion">
                            <div class="flex justify-between items-center mb-4">
                                <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Add tags', 'close_name' => 'Collapse tags', 'is_open' => false]) ?>
                            </div>
                            <div class="accordion-content invisible">
                                <label for="tags-select">
                                    <span>
                                        Search for existing tags or create your own. If no suitable tags are found, you can create new ones on the <a class="ajax-link" href="<?php echo site_url('/tag/') ?>">
                                            tags page
                                        </a>
                                    </span>
                                </label>

                                <?php get_template_part('template-parts/tag-field') ?>
                            </div>
                        </div>
                    </div>
                </div>

                <button class="mb-2 submit-button" type="submint">
                    submit
                </button>

                <div class="message-field">
                </div>
            </form>
        </div>
    </div>

    <?php if ($myArtists->found_posts) : ?>
        <?php get_template_part('template-parts/artists-grid', null, [
            'artists' => $myArtists,
            'title' => 'uploaded songs'
        ]) ?>
    <?php endif ?>

    <?php if ($myArtists->found_posts > 15): ?>
        <div class="upload-artist-page-js cursor-pointer"
            data-page=2
            data-max-pages="<?php echo $myArtists->max_num_pages ?>">
            view more
        </div>
    <?php endif ?>
</div>

<?php get_footer(); ?>