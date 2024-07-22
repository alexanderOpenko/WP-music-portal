<div class="content-container">
    <div class="myArtists-nav flex">
        <div>
            <a href="<?php echo site_url('/favorite-artists/') ?>" class="<?php echo $args['artists_type'] == 'favorite' ? 'active-link' : ''?>">
                favorite artists
            </a>
        </div>

        <div>
            <a href="<?php echo site_url('/saved-artists/') ?> class="<?php echo $args['artists_type'] == 'saved' ? 'active-link' : ''?>"">
                saved artists
            </a>
        </div>
    </div>

    <?php if ($args['artists_type'] == 'favorite') : ?>
        <div>
            create artist form
        </div>
    <?php endif ?>

    <div class="artists-list grid grid-cols-3 gap-4">
        <?php while($args['artists']->have_posts()) :
            $args['artists']->the_post(); ?>
            <div class="artist-card flex  flex-col">
                <div class="artist-card_image">
                    <img src="<?php echo get_template_directory_uri() . '/images/artist-placeholder.jpg'?>"/>
                </div>

                <div class="artist-card_info">
                    <?php the_title()?>
                </div>
            </div>
        <?php endwhile ?>
    </div>
</div>