<?php
$my_tags_post = new WP_Query([
    'author' => get_current_user_id(),
    'post_type' => 'usertags',
    'post_status' => 'publish',
]);

$my_tags_post_id = 0;
$my_tags = [];

if ($my_tags_post->have_posts()) {
    $my_tags_post->the_post();
    $my_tags_post_id = get_the_ID();
    $my_tags = get_post_field('tag', $my_tags_post_id);
}

if (!empty($my_tags)) {
    $meta_query = ['relation' => 'OR'];

    foreach ($my_tags as $tag) {
        $meta_query[] = [
            'key' => 'tag',
            'value' => '"' . $tag . '"',
            'compare' => 'LIKE',
        ];
    }

    $recomended_tracks = new WP_Query([
        'post_type' => 'song',
        'author__not_in' => [get_current_user_id()],
        'post_status' => 'publish',
        'meta_query' => $meta_query,
    ]);
}
?>
<?php if ($recomended_tracks->found_posts) : ?>
    <h1>
        Recomended tracks
    </h1>

    <div>
        <?php while ($recomended_tracks->have_posts()) {
            $recomended_tracks->the_post();
            get_template_part('template-parts/song-item');
        }
        ?>
    </div>
<?php endif ?>