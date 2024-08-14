<?php
get_header();

while (have_posts()) :
    the_post();
    $image_url = get_post_image_custom(get_field('artist_image'), 'full');
    $song_ids = get_field('songs', '', false);
?>
    <div class="banner">
        <div class="left-container py-[60px] px-[80px] capitalize">
            <h1 class="text-[48px] text-zinc-700">
                <?php the_title(); ?>
            </h1>

            <button class="uppercase primary-button py-[9px] px-[20px] flex items-center">
                <div class="chartlist-play-button mr-[4px]">
                </div>

                <div>
                    play artist
                </div>
            </button>
        </div>

        <div class="right-container">
            <img class="object-cover" src="<?php echo esc_url($image_url); ?>" />
        </div>
    </div>
<?php endwhile;
wp_reset_postdata();
?>

<div class="content-container">
    <?php
    while (have_posts()) :
        the_post();
        if (!empty(get_the_content())) :?>
            <div class="mb-8">
            <h2>
                About
            </h2>
                <?php the_content(); ?>
            </div>
        <?php endif ?>
    <?php endwhile; ?>

    <?php if (count(get_field('tag'))): 
            $tags = new WP_Query([
                'post_type' => 'musictag',
                'post__in' => get_field('tag', '', false),
            ]); ?>

            <div class="flex mb-8">
                <?php while($tags->have_posts()): 
                    $tags->the_post();
                    ?>
                    <button class="tag-button mr-3">
                        <?php the_title(); ?>
                    </button>
                <?php endwhile?>
            </div>
        <?php endif ?>

    <?php if ($song_ids) :
        $limited_song_ids = array_slice($song_ids, 0, 10);

        $uploaded_songs = new WP_Query([
            'post_type' => 'song',
            'post__in' => $limited_song_ids,
        ]); ?>

        <h2 class="mb-[15px]">
            Tracklist
        </h2>
        
        <div class="favsongs-page-list">
            <?php while ($uploaded_songs->have_posts()) : 
                    $uploaded_songs->the_post();
                    get_template_part('template-parts/song-item');
                endwhile; 
                wp_reset_postdata();?>
        </div>

        <?php if (count($song_ids) > 10) :?>
            <div class="upload-song-page-js cursor-pointer" data-artist="<?php echo get_the_ID() ?>" data-page=2 data-max-pages="<?php echo $uploaded_songs->max_num_pages ?>">
                view more
            </div>
        <?php endif?>
    <?php else : ?>
        <div>
            Please <a class="ajax-link" href="<?php echo home_url('/favorite-songs')?>"> create songs </a> with this artist name
        </div>
    <?php endif ?>
</div>

<?php get_footer(); ?>