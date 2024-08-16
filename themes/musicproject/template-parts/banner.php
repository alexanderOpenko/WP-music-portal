<div class="banner">
    <div class="left-container py-[60px] px-[80px] capitalize">
        <h1 class="text-[48px] text-zinc-700">
            <?php the_title(); ?>
        </h1>

        <button class="uppercase play-first-artist-song-js primary-button py-[9px] px-[20px] flex items-center">
            <div class="chartlist-play-button mr-[4px]">
            </div>

            <div>
                <?php echo $args['title'] ?>
            </div>
        </button>
    </div>

    <div class="right-container">
        <img class="object-cover" src="<?php echo esc_url($args['image_url']); ?>" />
    </div>
</div>