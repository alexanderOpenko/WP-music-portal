<?php
$artists = getMostPopular(6, true)['artists']
?>

<?php if ($artists) : ?>
    <h1>
        Popular artists
    </h1>

    <div class="card-list artists-list cards-container">
        <?php while ($artists->have_posts()) :
            $artists->the_post();
            $image_id = get_field('artist_image');
            $image_url = get_post_image_custom(get_field('artist_image'), 'thumb');
        ?>
            <?php get_template_part('template-parts/card', null, ['image_url' => $image_url]) ?>
        <?php endwhile ?>

        <?php if ($artists->found_posts > 6) : ?>
            <a>view all</a>
        <?php endif ?>
    </div>
<?php endif ?>