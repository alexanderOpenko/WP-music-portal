<?php add_action('rest_api_init', 'manageSong');

function manageSong()
{
    register_rest_route('music/v1/', 'createSong', [
        'methods' => 'POST',
        'callback' => 'createSong'
    ]);

    register_rest_route('music/v1/', 'updatePlayCount', [
        'methods' => 'GET',
        'callback' => 'updatePlayCount'
    ]);

    register_rest_route('music/v1/', 'saveSong', [
        'methods' => 'POST',
        'callback' => 'saveSong'
    ]);
}

function saveSong($data) {
    updateUserSavedItems($data['post_id'], $data['action'], $data['type']);

    return new WP_REST_Response([null, 200]);
}

function updatePlayCount($data) {
    $song_play_counts = get_field('play_count', $data['id']);
    $artist_id = get_field('artist', $data['id'])[0];
    $artist_play_counts = get_field('play_count', $artist_id);

    if (!$song_play_counts) {
        $song_play_counts = 0;
    }

    if (!$artist_play_counts) {
        $artist_play_counts = 0;
    }

    $song_play_counts++;
    $artist_play_counts++;

    update_field('play_count', $song_play_counts, $data['id']);
    update_field('play_count', $artist_play_counts, $artist_id);
}

function createSong($data)
{
    if (is_user_logged_in()) {
        // create band 
        $isExistBand = checkArtistExisting($data['band']);

        $artist_id = 0;

        if (!$isExistBand) {
            $artist_id = create_artist($data)['artist_id'];
        } else {       
            $artist_id = update_artist_tags($data)['artist_id'];
        }

        $SongPostId = wp_insert_post([
            'author' => get_current_user_id(),
            'post_type' => 'song',
            'post_title' => $data['title'],
            'post_status' => 'publish',
            'post_content' => $data['content'],
            'meta_input' => [
                'artist' => [$artist_id],
                'tag' => explode(',', $data['tags']),
                'song_link' => ['url' => $data['song_link']],
                'band' => $data['band']
            ]
        ], true);

        updateUserTags($data['tags']);

        $postQuery = new WP_query([
            'post_type' => 'song',
            'p' => $SongPostId
        ]);

        //for rest api response
        $post = array_map(function($post) {
            return [
                'ID' => $post->ID,
                'song_link' => get_field('song_link', $post->ID)['url'],
                'band' => get_field('band', $post->ID),
                'link' => get_permalink($post->ID),
                'title' => $post->post_title
            ];
        },$postQuery->posts);

        add_value_to_field('songs', $SongPostId, $artist_id);
        return new WP_REST_Response(["post" =>  $post[0]], 200);
    } else {
        return new WP_REST_Response(["message" =>  "Only logged in users can create a song."], 402);
    }
}
