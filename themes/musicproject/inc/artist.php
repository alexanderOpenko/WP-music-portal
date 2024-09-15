<?php add_action('rest_api_init', 'manage_artist');

function manage_artist() 
{
    register_rest_route('music/v1', 'createArtist', [
        'methods' => 'POST',
        'callback' => 'createArtist'
    ]);

    register_rest_route('music/v1', 'artistsPaginations', [
        'methods' => 'GET',
        'callback' => 'getSavedArtistPage'
    ]);

    register_rest_route('music/v1', 'uploadedArtistsPaginations', [
        'methods' => 'GET',
        'callback' => 'getUploadedArtistPage'
    ]);

    register_rest_route('music/v1', 'allArtistsPaginations', [
        'methods' => 'GET',
        'callback' => 'getAllArtistPage'
    ]);
}

function getAllArtistPage($data) {
    $myArtists = new WP_Query([
        'post_type' => 'artist',
        'posts_per_page' => 15,
        'paged' => $data['page']
    ]);

    $artists = array_map(function ($post) {
        $image_id = get_post_meta($post->ID, 'artist_image', true);
        $image_link = get_post_image_custom($image_id, 'thumb');

        return [
            'image_link' => $image_link ?: get_template_directory_uri() . '/images/artist-placeholder.jpg',
            'link' => get_permalink($post->ID),
            'title' => $post->post_title
        ];
    }, $myArtists->posts);

    return new WP_REST_Response(['posts' => $artists], 200);
}

function getUploadedArtistPage($data) {
    $myArtists = new WP_Query([
        'author' => get_current_user_id(),
        'post_type' => 'artist',
        'posts_per_page' => 15,
        'paged' => $data['page']
    ]);

    $artists = array_map(function ($post) {
        $image_id = get_post_meta($post->ID, 'artist_image', true);
        $image_link = get_post_image_custom($image_id, 'thumb');

        return [
            'image_link' => $image_link ?: get_template_directory_uri() . '/images/artist-placeholder.jpg',
            'link' => get_permalink($post->ID),
            'title' => $post->post_title
        ];
    }, $myArtists->posts);

    return new WP_REST_Response(['posts' => $artists], 200);
}

function getSavedArtistPage($data) {
    $saved_artists = my_saved_items('artist', 'artist', $data['page'], 15)['saved_items'];

    $artists = array_map(function ($post) {
        $image_id = get_post_meta($post->ID, 'artist_image', true);
        $image_link = get_post_image_custom($image_id, 'thumb');

        return [
            'image_link' => $image_link ?: get_template_directory_uri() . '/images/artist-placeholder.jpg',
            'link' => get_permalink($post->ID),
            'title' => $post->post_title
        ];
    }, $saved_artists->posts);

    return new WP_REST_Response(['posts' => $artists], 200);
}

function createArtist($data)
{
    if (is_user_logged_in()) {
        $isExistBand = checkArtistExisting($data['band']);

        if (!$isExistBand) {
           $artist = create_artist($data)['artist'];
           return new WP_REST_Response(["post" =>  $artist], 200);
        } else {
            return new WP_REST_Response(["message" =>  "Content already exist"], 409);
        }
    } else {
        return new WP_REST_Response(["message" =>  "Only logged in users can create a song."], 402);
    }
}

