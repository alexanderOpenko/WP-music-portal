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
        $SongPostId = wp_insert_post([
            'author' => get_current_user_id(),
            'post_type' => 'song',
            'post_title' => $data['title'],
            'post_status' => 'publish',
            'post_content' => $data['content'],
            'meta_input' => [
                'tag' => explode(',', $data['tags']),
                'song_link' => ['url' => $data['song_link']],
                'band' => $data['band']
            ]
        ], true);

        $postQuery = new WP_query([
            'post_type' => 'song',
            'p' => $SongPostId
        ]);

        //for rest api response
        $post = array_map(function($post) {
            return [
                'song_link' => get_field('song_link', $post->ID)['url'],
                'link' => get_permalink($post->ID),
                'title' => $post->post_title
            ];
        },$postQuery->posts);

        // create band 
        $isExistBand = new WP_Query([
            'author' => get_current_user_id(),
            'title' => $data['band'],
            'post_type' => 'artist',
            'post_status' => 'publish',
            'posts_per_page' => 1
        ]); 

        if ($isExistBand->found_posts == 0) {
            $artistPostId = wp_insert_post([
                'post_type' => 'artist',
                'post_status' => 'publish',
                'post_title' => $data['band'],
                'meta_input' => [
                    'tag' => explode(',', $data['tags'])
                ]
            ]);

            add_value_to_field('songs', $SongPostId, $artistPostId);
        } else {       
            $artist_id = 0;

            $query = new WP_Query([
                'author' => get_current_user_id(),
                'title' => $data['band'],
                'post_type' => 'artist',
                'post_status' => 'publish',
                'posts_per_page' => 1
            ]);
        
            if ($query->have_posts()) {
                $query->the_post();
                $post_id = get_the_ID();
                wp_reset_postdata(); 
                $artist_id = $post_id;
            }

            if ($data['tags']) {
                add_value_to_field('tag', explode(',', $data['tags']), $artist_id);
            }
            add_value_to_field('songs', $SongPostId, $artist_id);
        }

        return new WP_REST_Response(["post" =>  $post[0]], 200);
    } else {
        return new WP_REST_Response(["message" =>  "Only logged in users can create a song."], 402);
    }
}
