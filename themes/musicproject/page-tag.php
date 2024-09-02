<?php
get_header();

$userTags = new WP_Query([
    "author" => get_current_user_id(),
    "post_type" => "musictag",
]);
?>

<div class="content-container">
    <p>
        Help categorize and classify the music on our site by creating global tags. These tags will be used to describe songs, albums, and bands, making it easier for everyone to discover and explore music.
    </p>

    <div class="md-content-centered p-[10px]">
        <div class="accordion">
            <div class="flex justify-between items-center">
                <h3>
                    Create Global Music Tag
                </h3>
                <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Open form', 'close_name' => 'Close form', 'is_open' => true]) ?>
            </div>

            <div class="accordion-content">
                <form class="tag-form">
                    <label for="title">
                        Title
                    </label>
                    <input name="title" required class="input" type="text" id="title" />

                    <label for="content">
                        Content
                    </label>
                    <textarea name="content" id="content" class="input h-[150px]" type="text"></textarea>

                    <label for="image">
                        Image
                    </label>
                    <?php get_template_part('template-parts/upload-button', null, ['image_name' => 'image_tag']);
                    get_template_part('template-parts/image-holder');
                    ?>

                    <button class="mb-2" type="submit">
                        submit
                    </button>

                    <div class="message-field">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div>
        <p>
            Your tags
        </p>

        <?php get_template_part('template-parts/tags-grid', null, ['tags' => $userTags]) ?>
    </div>
</div>

<?php
get_footer();
?>