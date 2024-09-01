<?php
get_header();
$post_id = 0;

while (have_posts()) {
  the_post();
  $post_id = get_the_id();
  $tag_ids = get_post_field('tag', '', false);
  $artists = recomended_post_type('artist', $tag_ids);
  $image_id = get_post_field('artist_image', get_field('artist')[0]);
  $image_url = get_post_image_custom($image_id, 'full');
  get_template_part('template-parts/banner', null, ['image_url' => $image_url, 'title' => 'play song', 'listens' => get_field('play_count')]);
  wp_reset_postdata();
}
?>

<div class="content-container">
  <div class="accordion">
    <div class="flex justify-between items-center mb-4">
      <?php get_template_part('template-parts/single-accordion-button', null, ['open_name' => 'Edit tags', 'close_name' => 'Collapse tag edditing', 'is_open' => false]) ?>
    </div>

    <div class="accordion-content invisible h-0">
      <form class="update-song-tag">
        <input type="hidden" value="<?php echo $post_id ?>" name="post_id"/>
        <?php get_template_part('template-parts/tag-field', null, ['tag_ids' => $tag_ids]) ?>
        <button class="mb-2" type="submit">
            submit
        </button>
      </form>
    </div>
  </div>

  <?php
  get_template_part('template-parts/tags-list', null, ['tag_ids' => $tag_ids]);

  while (have_posts()) :
    the_post();
    get_template_part('template-parts/song-item');
    wp_reset_postdata();
  ?>
    <?php if ($artists->have_posts()) : ?>
      <h1>
        Similar Artists
      </h1>

      <div class="card-list artists-list cards-container">
        <?php while ($artists->have_posts()) :
          $artists->the_post();
          $image_id = get_field('artist_image');
          $image_url = get_post_image_custom(get_field('artist_image'), 'thumb');
        ?>
          <?php get_template_part('template-parts/card', null, ['image_url' => $image_url]) ?>
        <?php endwhile ?>

        <?php if ($artists->found_posts > 6) : ?>
          <a>view all</a>
        <?php endif ?>
      </div>
    <?php endif ?>
  <?php endwhile ?>
</div>

<?php get_footer(); ?>