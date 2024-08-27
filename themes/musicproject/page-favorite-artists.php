<?php get_header();
$myArtists = new WP_Query([
    'author' => get_current_user_id(),
    'post_type' => 'artist',
    'posts_per_page' => 15
]);

$tags = new WP_Query([
    'post_type' => 'musictag',
    'posts_per_page' => 10,
]);

$dafault_tags = [];

while ($tags->have_posts()) {
    $tags->the_post();
    array_push($dafault_tags, ['value' => get_the_ID(), 'text' =>  get_the_title()]);
}
?>

<div class="content-container">
    <div class="accordion">
        <div class="flex justify-between items-center">
            <h3>
                Add artist to your list
            </h3>
            <?php get_template_part('template-parts/single-accordion-button') ?>
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

                        <label for="new-song-tags">
                            <span>
                                Select multiple tags. if no tags you can create it on tags page
                            </span>
                        </label>

                        <div class="mb-[15px] tags-select-wrapper">
                            <select multiple name="tags" class="input !mb-0" type="text" id="new-song-tags" data-default-tags='<?php echo json_encode($dafault_tags) ?>'>
                                <?php while ($tags->have_posts()) :
                                    $tags->the_post() ?>
                                    <option value="<?php echo get_the_ID() ?>"><?php the_title() ?></option>
                                <?php endwhile;
                                wp_reset_postdata(); ?>
                            </select>

                            <div class="bg-white relative max-h-[16px] px-[16px]">
                                <div id="spinner" class="spinner absolute bg-white hidden"></div>
                                <div class="select-message-field">
                                </div>
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