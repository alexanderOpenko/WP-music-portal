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

  $my_saved_tags = my_saved_items('tag', 'musictag', 1, -1)['my_items_ids'];
  $is_saved_tag = false;

  if (in_array(get_the_id(), $my_saved_tags)) {
      $is_saved_tag = true;
  }

  $save_icon_args = [
      'action' => 'save',
      'post_id' => get_the_id(),
      'type' => 'tag'
  ];

  if ($is_saved_tag) {
      $save_icon_args['action'] = 'unsave';
      $save_icon_args['btn_class'] = 'saved-song';
  }

  get_template_part('template-parts/banner', null, ['image_url' => $image_url, 'title' => 'play tag', 'save_icon_args' => $save_icon_args]); ?>
<?php endwhile ?>

<div class="content-container">       
  <?php 
      if (get_the_author_meta('ID') === get_current_user_id()) {
          get_template_part('template-parts/delete-icon', null, ['post_type' => 'musictag', 'post_id' => get_the_id(), 'content-name' => get_the_title()]); 
      }
  ?>

  <?php if (!empty(get_the_content())) : ?>
    <div class="mb-8">
      <h2>
        About
      </h2>
      <?php the_content(); ?>
    </div>
  <?php endif ?>
  <?php get_template_part('template-parts/songs-list-by-id', null, ['song_ids' => $song_ids, 'data_attribute' => 'data-tag']) ?>
</div>
<?php
get_footer();
?>