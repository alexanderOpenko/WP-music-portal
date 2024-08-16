<div class="content-container">
    <div class="myArtists-nav flex">
        <div>
            <a href="<?php echo site_url('/favorite-artists/') ?>" class="<?php echo $args['artists_type'] == 'favorite' ? 'active-link' : '' ?>">
                favorite artists
            </a>
        </div>

        <div>
            <a href="<?php echo site_url('/saved-artists/') ?> class=" <?php echo $args['artists_type'] == 'saved' ? 'active-link' : '' ?>"">
                saved artists
            </a>
        </div>
    </div>

    <div class="card-list cards-container">
        <?php while ($args['artists']->have_posts()) :
            $args['artists']->the_post();
            $image_id = get_field('artist_image');
            // $metadata = wp_get_attachment_metadata($image_id);
            // print json_encode($metadata);
            $image_url = get_post_image_custom(get_field('artist_image'), 'thumb');
        ?>
            <?php get_template_part('template-parts/card', null, ['image_url' => $image_url]) ?>
        <?php endwhile ?>
    </div>
</div>