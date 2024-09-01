<?php
get_header();
$uploaded_songs = new WP_Query([
    'author' => get_current_user_id(),
    'post_type' => 'song',
    'post_status' => 'publish',
    'posts_per_page' => 15,
    'paged' => get_query_var('paged') ?: 1
]);
?>

<div class="content-container">
    <div>
        <div class="accordion">
            <div class="flex justify-between items-center">
                <h3>
                    Upload your songs
                </h3>
                <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Open form', 'close_name' => 'Close form', 'is_open' => true]) ?>
            </div>

            <div class="accordion-content">
                <form class="song-form">
                    <div class="flex">
                        <div class="max-w-full w-full mr-4">
                            <label for="title">
                                Name
                            </label>
                            <input name="title" required class="input" type="text" id="title" />

                            <label for="content">
                                Description
                            </label>
                            <textarea name="content" id="content" class="input h-[110px]" type="text"></textarea>
                        </div>

                        <div class="max-w-full w-full">
                            <label for="band">
                                Band
                            </label>
                            <input name="band" required id="band" class="input" type="text">

                            <label for="song-link">
                                Song youtube link
                            </label>
                            <input name="song_link" required id="song-link" class="input" type="url">

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
        <h2>
            Uploaded Tracks
        </h2>

        <div>
            <div class="favsongs-page-list">
                <?php while ($uploaded_songs->have_posts()) {
                    $uploaded_songs->the_post();
                    get_template_part('template-parts/song-item');
                }
                ?>
            </div>

            <?php if ($uploaded_songs->found_posts > 15): ?>
                <div class="upload-song-page-js cursor-pointer" data-page=2 data-max-pages="<?php echo $uploaded_songs->max_num_pages ?>">
                    view more
                </div>
            <?php endif ?>
        </div>
    </div>
</div>

<?php
get_footer()
?>