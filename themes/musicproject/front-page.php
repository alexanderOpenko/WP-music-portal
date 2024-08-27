<?php
get_header();
$songs = getMostPopular(9, false, true)['songs']
?>

<div class="content-container">
    <?php if (!is_user_logged_in()) : ?>
        <div>
            Create an account to upload and save your music. Get personalized recommendations based on your favorite tags!
        </div>
    <?php endif ?>

    <?php if (is_user_logged_in()) get_template_part('template-parts/recomended-tracks'); ?>

    <h1>
        Popular tracks
    </h1>

    <div>
        <?php while ($songs->have_posts()) {
            $songs->the_post();
            get_template_part('template-parts/song-item');
        }
        ?>
    </div>

    <h1>
        Popular artists
    </h1>

    <div>
        <?php get_template_part('template-parts/popular-artists') ?>
    </div>
</div>

<?php
get_footer();
?>