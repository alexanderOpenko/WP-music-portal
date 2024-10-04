<?php
get_header();
$post_id = 0;

while (have_posts()) {
  the_post();
  $post_id = get_the_ID();
  $tag_ids = get_post_field('tag', $post_id, false);
  $artists = recomended_post_type(6, 'artist', $tag_ids);
  $image_id = get_field('artist_image', get_field('artist')[0]);
  $image_url = get_post_image_custom($image_id, 'full');
  $my_saved_songs = my_saved_items('songs', 'song', 1, -1)['my_items_ids'];
  $is_saved_song = false;

  if (in_array(get_the_id(), $my_saved_songs)) {
    $is_saved_song = true;
  }

  $save_icon_args = [
    'action' => 'save',
    'post_id' => get_the_id(),
    'type' => 'songs'
  ];

  if ($is_saved_song) {
    $save_icon_args['action'] = 'unsave';
    $save_icon_args['btn_class'] = 'saved-song';
  }

  get_template_part('template-parts/banner', null, [
    'image_url' => $image_url,
    'title' => 'play song',
    'listens' => get_field('play_count'),
    'save_icon_args' => $save_icon_args
  ]);
  wp_reset_postdata();
}
?>

<div class="content-container">
  <?php get_template_part('template-parts/edit-description', null, [
    'post_type' => 'song',
    'post_id' => $post_id,
    'content' => get_the_content(),
    'post_author' => get_the_author_meta('ID')
  ]) ?>

  <div class="accordion">
    <?php if (get_the_author_meta('ID') === get_current_user_id()) : ?>

      <div class="flex justify-between items-center mb-4">
        <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Edit tags', 'close_name' => 'Collapse tag edditing', 'is_open' => false]) ?>
      </div>
    <?php endif ?>

    <div class="accordion-content invisible">
      <form class="update-song-tag">
        <input type="hidden" value="<?php echo $post_id ?>" name="post_id" />

        <div class="flex items-start w-full max-w-[550px]">
          <?php get_template_part('template-parts/tag-field', null, ['tag_ids' => $tag_ids]) ?>
          <button class="mb-2 update-tag-button" type="submit">
            submit
          </button>
        </div>
      </form>
    </div>
  </div>

  <?php
  get_template_part('template-parts/tags-list', null, ['tag_ids' => $tag_ids]);
  get_template_part('template-parts/song-item', null, ['save_icon_args' => $save_icon_args]);
  ?>

  <?php get_template_part('template-parts/similar-artists', null, [
    'artists' => $artists,
    'current_artist' => get_field('artist', $post_id)[0]
  ]) ?>
</div>

<?php get_footer(); ?>