<?php
get_header();
$uploaded_songs = new WP_Query([
    'author' => get_current_user_id(),
    'post_type' => 'song',
    'post_status' => 'publish',
    'posts_per_page' => 1,
    'paged' => get_query_var('paged') ?: 1
]);

$tags = new WP_Query([
    'post_type' => 'musictag',
    'posts_per_page' => 2,
]);

$dafault_tags = [];

while ($tags->have_posts()) {
    $tags->the_post();
    array_push($dafault_tags, ['value' => get_the_ID(), 'text' =>  get_the_title()]);
}
?>

<div class="content-container">
    <div class="md-content-centered">
        <h3>
            Upload your songs
        </h3>

        <form class="song-form">
            <label for="title">
                Name
            </label>
            <input name="title" required class="input" type="text" id="title" />

            <label for="content">
                Description
            </label>
            <textarea name="content" id="content" class="input h-[110px]" type="text"></textarea>

            <label for="band">
                Band
            </label>
            <input name="band" required id="band" class="input" type="text">

            <label for="song-link">
                Song youtube link
            </label>
            <input name="song_link" required id="song-link" class="input" type="url">

            <label for="new-song-tags">
                <span>
                    Select multiple tags. if no tags you can create it on tags page
                </span>
            </label>

            <div class="mb-[15px] tags-select-wrapper">
                <select multiple name="tags" class="input !mb-0" type="text" id="new-song-tags" data-default-tags='<?php echo json_encode($dafault_tags) ?>'>
                    <?php while ($tags->have_posts()) :
                        $tags->the_post() ?>
                        <option value="<?php the_title() ?>"><?php the_title() ?></option>
                    <?php endwhile;
                    wp_reset_postdata();?>
                </select>

                <div class="bg-white relative max-h-[16px] px-[16px]">
                    <div id="spinner" class="spinner absolute bg-white hidden"></div>
                    <div class="select-message-field">
                    </div>
                </div>
            </div>

            <button class="mb-2">
                submit
            </button>

            <div class="message-field">
            </div>
        </form>
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

            <div class="upload-song-page-js" data-page=2>
                view more
            </div>
        </div>
    </div>
</div>

<?php
get_footer()
?>