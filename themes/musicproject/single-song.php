<?php
get_header();
?>

<div class="content-container">
  <?php
  while (have_posts()) {
    the_post();
    get_template_part('template-parts/song-item') ?>
  <?php }
  echo paginate_links();
  ?>
</div>

<?php get_footer();?>