<?php
get_header();
while (have_posts()) {
  the_post();
  $tag_ids = get_post_field('tag', '', false);
  $artists = recomended_post_type($tag_ids, 'artist');
  $image_id = get_post_field('artist_image', get_field('artist')[0]);
  $image_url = get_post_image_custom($image_id, 'full');
  get_template_part('template-parts/banner', null, ['image_url' => $image_url, 'title' => 'play song', 'listens' => get_field('play_count')]);
  wp_reset_postdata();
}
?>

<div class="content-container">
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