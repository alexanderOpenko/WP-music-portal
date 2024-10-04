<div class="song-item relative flex items-center justify-between py-3 items-center border-0 border-b border-solid border-slate-300">
    <div class="flex items-center">
        <button class="song-play chartlist-play-button" data-song-id="<?php echo get_the_id() ?>" data-song-link="<?php echo str_replace([',', ' '], '', get_field('song_link')['url']) ?>">
        </button>

        <h3 class="m-0 !ml-5 sm:text-lg text-base">
            <a class="underline" href="<?php echo get_permalink(get_field('artist')[0]) ?>">
                <?php echo get_the_title(get_field('artist')[0]); ?>
            </a>
            -
            <a class="underline" href="<?php the_permalink(); ?>">
                <?php the_title(); ?>
            </a>
        </h3>
    </div>

    <div class="flex items-center max-w-[30%] w-full justify-end ml-2">
        <?php
        if (get_the_author_meta('ID') === get_current_user_id()) : ?>
            <?php get_template_part('template-parts/delete-icon', null, ['post_type' => 'song', 'post_id' => get_the_id(), 'content-name' => get_the_title()]) ?>
        <?php else : ?>
            <div class="sm:text-sm text-xs text-slate-500">
                uploaded by 
                <a href="<?php echo site_url('/user-profile?user_id=' . get_the_author_meta('ID')) ?>">
                    <?php echo get_the_author_meta('display_name', get_the_author_meta('ID')); ?>
                </a>
            </div>
        <?php endif ?>

        <?php get_template_part('template-parts/save-icon', null, $args['save_icon_args']); ?>
    </div>
</div>