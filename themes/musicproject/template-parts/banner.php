<div class="banner">
    <div class="left-container bg-[#D7CFC5] z-[2] sm:z-[1] sm:w-2/4 w-0 sm:w-2/4 sm:p-9 lg:py-[60px] lg:px-[80px] capitalize flex items-center lg:items-start	">
        <div class="translate-x-[30%] lg:translate-y-2/4 sm:translate-x-0">
            <h1 class="sm:text-[48px] text-[36px] text-white sm:text-zinc-700 my-2 sm:my-4">
                <?php the_title(); ?>
            </h1>

            <div class="flex">
                <button class="uppercase play-first-artist-song-js primary-button py-[9px] px-[20px] flex items-center">
                    <div class="chartlist-play-button mr-[4px]">
                    </div>

                    <div>
                        <?php echo $args['title'] ?>
                    </div>
                </button>

                <?php if (isset($args['listens']) && $args['listens'] > 0) : ?>
                    <div class="ml-4 text-white sm:text-zinc-700">
                        <div>
                            listens
                        </div>

                        <div>
                            <?php echo $args['listens']; ?>
                        </div>
                    </div>
                <?php endif ?>

                <div>
                    <?php get_template_part('template-parts/save-icon', null, $args['save_icon_args']); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="right-container w-full sm:w-2/4 relative">
        <img class="object-cover banner-image" src="<?php echo esc_url($args['image_url']); ?>" />
    </div>
</div>