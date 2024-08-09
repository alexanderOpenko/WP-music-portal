<?php
get_header();
?>

<div class="content-container">
    <?php
    while (have_posts()) :
        the_post(); ?>
        <div>
            <?php the_title(); ?>
        </div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>