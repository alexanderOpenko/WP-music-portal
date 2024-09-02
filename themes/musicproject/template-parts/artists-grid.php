<div class="card-list artists-list cards-container">
    <?php while ($args['artists']->have_posts()) :
        $args['artists']->the_post();
        $image_id = get_field('artist_image');
        $image_url = get_post_image_custom(get_field('artist_image'), 'thumb');
    ?>
        <?php get_template_part('template-parts/card', null, ['image_url' => $image_url]) ?>
    <?php endwhile ?>
</div>