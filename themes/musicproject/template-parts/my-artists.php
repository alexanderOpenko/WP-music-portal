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

    <div class="artists-list grid grid-cols-3 gap-4">
        <?php while ($args['artists']->have_posts()) :
            $args['artists']->the_post();
            $image_id = get_field('artist_image');
            // $metadata = wp_get_attachment_metadata($image_id);
            // print json_encode($metadata);
            $image_url = get_post_image_custom(get_field('artist_image'), 'thumb');
        ?>
            <div class="artist-card flex flex-col relative">
                <a href="<?php the_permalink() ?>" class="full-absolute z-10 ajax-link">
                </a>
                <div class="artist-card_image relative pt-[100%]">
                    <img class="full-absolute object-cover" src="<?php echo $image_url ?>" />
                </div>
                <div class="artist-card_info">
                    <?php the_title() ?>
                </div>
            </div>
        <?php endwhile ?>
    </div>
</div>