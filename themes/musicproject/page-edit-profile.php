<?php
get_header();
?>
<div class="content-container">

  <div class="form-wrapper flex">
    <?php echo do_shortcode('[basic-user-avatars]'); ?>

    <div class="ml-4 w-full max-w-[300px]">
      <?php get_template_part('template-parts/image-holder');?>
    </div>
  </div>

  <?php while (have_posts()) {
    the_post(); ?>
    <div>
      <h2 class="text-center">
        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
      </h2>

      <?php the_content(); ?>
    </div>
  <?php }
  echo paginate_links();
  ?>
</div>

<?php get_footer();?>