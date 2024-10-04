<?php
get_header();
$songs = getMostPopularSongs(5);
$artists = getMostPopularArtists(6);
$my_tags = my_tags();
$recomended_tracks = recomended_post_type(5, 'song', $my_tags);
$popularTracksWidth = $recomended_tracks->found_posts ? 'half-space' : 'w-full';
$recomendedTracksWidth = $songs->found_posts ? 'half-space' : 'w-full';
?>

<div class="content-container !max-w-screen-lg">
    <?php if (!is_user_logged_in()) : ?>
        <div class="bg-blue-100 text-blue-900 font-bold text-[1.2em] px-[20px] py-[10px] border border-blue-200 rounded-lg shadow-md mb-5 text-center">
            Create an account to upload and save your music. Get personalized recommendations based on your favorite tags!
        </div>
    <?php endif ?>

    <div class="md:flex justify-between">
        <?php if (is_user_logged_in() && $recomended_tracks->found_posts) : ?> 
            <div class="<?php echo $recomendedTracksWidth; ?>">
                <h1>
                    Recomended tracks
                </h1>
                
                <?php 
                    get_template_part('template-parts/songs-list', null, ['songs' => $recomended_tracks]); 

                    if ($recomended_tracks->found_posts > 5) {
                        echo "<a href='" . site_url('/recommended') . "' class='underline decoration-1 mt-2 block'> View all recomended tracks</a>";
                    }
                ?>
            </div>
        <?php endif ?>

        <?php if ($songs->found_posts) : ?>
            <div class="<?php echo $popularTracksWidth; ?>">
                <h1>
                    Popular tracks
                </h1>

                <div>
                    <?php 
                    get_template_part('template-parts/songs-list', null, ['songs' => $songs]); 

                    if ($songs->found_posts > 5) {
                        echo "<a href='" . site_url('/popular') . "' class='underline decoration-1 mt-2 block'> View all popular tracks</a>";
                    }
                    ?>
                </div>
            </div>
        <?php endif ?>
    </div>

    <?php if ($artists->found_posts) : ?>
        <div>
            <?php get_template_part('template-parts/artists-grid', null, [
                'artists' => $artists,
                'title' => 'Popular Artists'
                ]) ?>
        </div>
    <?php endif ?>
</div>

<?php
get_footer();
?>