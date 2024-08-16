<?php
get_header();
$tag_id = get_the_ID();

$tag_songs = new WP_Query([
  'post_type' => 'song',
  'meta_query' => [
    [
      'key' => 'tag',
      'value' => "{$tag_id}",
      'compare' => 'LIKE',
    ]
  ]
]);


$song_ids = wp_list_pluck($tag_songs->get_posts(), 'ID');

while (have_posts()) :
  the_post();
  $image_url = get_post_image_custom(get_field('tag_image'), 'full');

  get_template_part('template-parts/banner', null, ['image_url' => $image_url, 'title' => 'play tag']); ?>
<?php endwhile ?>

<div class="content-container">
<?php get_template_part('template-parts/songs-list', null, ['song_ids' => $song_ids, 'data_attribute' => 'data-tag']) ?>
</div>
<?php
get_footer();
?>