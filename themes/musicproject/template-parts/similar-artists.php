<?php if ($args['artists']->have_posts()) : ?>
    <?php

    $ids = [];
    while ($args['artists']->have_posts()) {
        $args['artists']->the_post();

        $artist_id = get_the_ID();
        if (isset($args['current_artist']) && $artist_id == $args['current_artist']) {
            continue;
        }

        $ids[] = $artist_id;
    }

    if (!count($ids)) {
        return;
    }

    $filtered_artists = array_filter($args['artists']->posts, function ($post) use ($args) {
        return $post->ID != $args['current_artist'];
    });

    $filtered_artists_query = new WP_Query([
        'post_type' => 'artist', 
        'post__in' => wp_list_pluck($filtered_artists, 'ID'), 
        'orderby' => 'post__in', 
    ]);

    get_template_part('template-parts/artists-grid', null, [
        'artists' => $filtered_artists_query,
        'title' => 'Similar artists'
    ]) ;
    ?>

    <a href="<?php echo site_url('/search-results?ids=' . implode(',', $ids)) . '&' . 'type=artist' ?>"
        class="underline decoration-1 mt-2 block">
        view similar artists
    </a>
<?php endif ?>