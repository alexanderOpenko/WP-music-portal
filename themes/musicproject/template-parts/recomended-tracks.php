<?php
$my_tags = my_tags();
$recomended_tracks = recomended_post_type('song', $my_tags)
?>

<?php if ($recomended_tracks->found_posts) : ?>
    <h1>
        Recomended tracks
    </h1>

    <div>
        <?php while ($recomended_tracks->have_posts()) {
            $recomended_tracks->the_post();
            get_template_part('template-parts/song-item');
        }
        ?>
    </div>
<?php endif ?>