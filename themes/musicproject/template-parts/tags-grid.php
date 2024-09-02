<div class="card-list tags-list cards-container">
    <?php while ($args['tags']->have_posts()) :
        $args['tags']->the_post();
        $image_url = get_post_image_custom(get_field('tag_image'), 'thumb');
    ?>
        <?php get_template_part('template-parts/card', null, ['image_url' => $image_url]) ?>
    <?php endwhile ?>
</div>