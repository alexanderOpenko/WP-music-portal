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
</div>

<div class="md-content-centered p-[10px]">
    <h3>
        Create Global Music Tag
    </h3>

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
        <input name="image_tag" class="input" type="file" id="image" />

        <button class="mb-2">
            submit
        </button>

        <div class="message-field">
        </div>
    </form>
</div>

<div class="content-container">
    <p>
        Your tags
    </p>

    <div class="card-list cards-container">
        <?php while ($userTags->have_posts()) :
            $userTags->the_post();
            $image_url = get_post_image_custom(get_field('tag_image'), 'thumb');
        ?>
            <?php get_template_part('template-parts/card', null, ['image_url' => $image_url]) ?>
        <?php endwhile ?>
    </div>
</div>

<?php
get_footer();
?>