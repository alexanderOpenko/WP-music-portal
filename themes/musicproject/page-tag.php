<?php
get_header();

require get_theme_file_path('inc/tags-tabs-links.php');
$tabs = TAG_TABS;

$userTags = new WP_Query([
    "author" => get_current_user_id(),
    "post_type" => "musictag",
]);
?>

<div class="content-container">
    <?php get_template_part('template-parts/tab-items', null, ['tabs' => $tabs]);
    if (!is_user_logged_in()) : ?>
        <div class="bg-blue-100 text-blue-900 font-bold px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
            Only logged in users can save tags from search
        </div>
    <?php get_footer();
        return;
    endif
    ?>

    <div class="bg-blue-100 text-blue-900 font-bold px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
        Help categorize and classify the music on our site by creating global tags. These tags will be used to describe songs, albums, and bands, making it easier for everyone to discover and explore music.
    </div>

    <div class="p-[10px]">
        <div class="accordion">
            <div class="flex justify-between items-center">
                <h3>
                    Create Global Music Tag
                </h3>
                <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Open form', 'close_name' => 'Close form', 'is_open' => true]) ?>
            </div>

            <div class="accordion-content visible">
                <div class="form-wrapper">
                    <form class="tag-form">
                        <div class="flex lg:flex-row flex-col">
                            <div class="max-w-1/2 w-full mr-4">
                                <label for="title">
                                    Title
                                </label>
                                <input name="title" required class="input" type="text" id="title" />

                                <label for="content">
                                    Content
                                </label>
                                <textarea name="content" id="content" class="input h-[150px]" type="text"></textarea>
                            </div>

                            <div class="max-w-1/2 w-full">

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
                            </div>
                        </div>
                    </form>
                </div>
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