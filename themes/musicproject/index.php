<?php
get_header();
 ?>
<div class="content-container">
<?php
  while(have_posts()) {
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

<?php get_footer();

?>