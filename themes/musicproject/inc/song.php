<?php add_action('rest_api_init', 'manageSong');

function manageSong()
{
    register_rest_route('music/v1/', 'createSong', [
        'methods' => 'POST',
        'callback' => 'createSong'
    ]);
}

function createSong($data)
{
    if (is_user_logged_in()) {
        $postId = wp_insert_post([
            'author' => get_current_user_id(),
            'post_type' => 'song',
            'post_title' => $data['title'],
            'post_status' => 'publish',
            'post_content' => $data['content'],
            'meta_input' => [
                'tags' => $data['tags'],
                'song_link' => ['url' => $data['song_link']],
                'band' => $data['band']
            ]
        ], true);

        $postQuery = new WP_query([
            'post_type' => 'song',
            'p' => $postId
        ]);

        $post = array_map(function($post) {
            return [
                'song_link' => get_field('song_link', $post->ID)['url'],
                'link' => get_permalink($post->ID),
                'title' => $post->post_title
            ];
        },$postQuery->posts);

        return new WP_REST_Response(["post" =>  $post[0]], 200);
    } else {
        return new WP_REST_Response(["message" =>  "Only logged in users can create a song."], 402);
    }
}
