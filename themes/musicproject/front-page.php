<?php
get_header();
$songs = getMostPopularSongs(5);
$artists = getMostPopularArtists(6);
$my_tags = my_tags();
$recomended_tracks = recomended_post_type(5, 'song', $my_tags);
$popularTracksWidth = $recomended_tracks->found_posts ? 'half-space' : 'w-full';
$recomendedTracksWidth = $songs->found_posts ? 'half-space' : 'w-full';
?>

<div class="content-container">
    <?php if (!is_user_logged_in()) : ?>
        <div>
            Create an account to upload and save your music. Get personalized recommendations based on your favorite tags!
        </div>
    <?php endif ?>

    <div class="md:flex justify-between">
        <?php if (is_user_logged_in()) : ?> 
            <div class="<?php echo $recomendedTracksWidth; ?>">
                <?php 
                    get_template_part('template-parts/recomended-tracks', null, ['recomended_tracks' => $recomended_tracks]); 

                    if ($recomended_tracks->found_posts > 5) {
                        echo "<a href='" . site_url('/recomended') . "'> View all recomended tracks</a>";
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
                    <?php while ($songs->have_posts()) {
                        $songs->the_post();
                        get_template_part('template-parts/song-item');
                    }

                    if ($songs->found_posts > 5) {
                        echo "<a href='" . site_url('/popular') . "'> View all popular tracks</a>";
                    }
                    ?>
                </div>
            </div>
        <?php endif ?>
    </div>

    <?php if ($artists->found_posts) : ?>
        <h1>
            Popular Artists
        </h1>

        <div>
            <?php get_template_part('template-parts/artists-grid', null, ['artists' => $artists]) ?>
        </div>
    <?php endif ?>
</div>

<?php
get_footer();
?>