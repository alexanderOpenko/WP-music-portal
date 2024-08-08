<?php add_action('rest_api_init', 'manage_artist');

function manage_artist() 
{
    register_rest_route('music/v1', 'createArtist', [
        'methods' => 'POST',
        'callback' => 'createArtist'
    ]);
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

