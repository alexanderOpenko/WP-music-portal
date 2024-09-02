<?php
$artists = getMostPopular(6, true)['artists']
?>

<?php if ($artists) : ?>
    <h1>
        Popular artists
    </h1>

    <?php get_template_part('template-parts/artists-grid', null, ['artists' => $artists]) ?>
<?php endif ?>