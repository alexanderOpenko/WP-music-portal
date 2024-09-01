<?php get_header();
$myArtists = new WP_Query([
    'author' => get_current_user_id(),
    'post_type' => 'artist',
    'posts_per_page' => 15
]);
?>

<div class="content-container">
    <div class="accordion">
        <div class="flex justify-between items-center">
            <h3>
                Add artist to your list
            </h3>
            <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Open form', 'close_name' => 'Close form', 'is_open' => true]) ?>
        </div>

        <div class="accordion-content">
            <form class="artist-form">
                <div class="flex">
                    <div class="max-w-full w-full mr-4">
                        <label for="title">
                            Name
                        </label>
                        <input name="band" required class="input" type="text" id="title" />

                        <label for="content">
                            Description
                        </label>
                        <textarea name="artist_content" id="content" class="input h-[110px]" type="text"></textarea>
                    </div>

                    <div class="max-w-full w-full">
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
                            <div class="accordion-content invisible h-0">
                                <label for="tags-select">
                                    <span>
                                        Please select multiple tags. If there are no tags available, you can create them on the tags page.
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
</div>

<?php get_template_part('template-parts/my-artists', '', ['artists' => $myArtists, 'artists_type' => 'favorite']);

get_footer(); ?>