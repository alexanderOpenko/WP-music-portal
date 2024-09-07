<?php if ($args['recomended_tracks']->found_posts) : ?>
    <h1>
        Recomended tracks
    </h1>

    <div>
        <?php while ($args['recomended_tracks']->have_posts()) {
            $args['recomended_tracks']->the_post();
            get_template_part('template-parts/song-item');
        }
        ?>
    </div>
<?php endif ?>