<?php get_header();
    $myArtists = new WP_Query([
        'author' => get_current_user_id(),
        'post_type' => 'artist',
        'posts_per_page' => 15
    ]);

    get_template_part('template-parts/my-artists', '', ['artists' => $myArtists, 'artists_type' => 'favorite']);

get_footer(); 
