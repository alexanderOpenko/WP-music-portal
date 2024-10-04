<?php add_action('rest_api_init', 'songsPagination');

function songsPagination()
{
  register_rest_route('music/v1', 'songsPaginations', [
    'methods' => 'GET',
    'callback' => 'getPage'
  ]);
}
function getPage($data)
{
  $page = sanitize_text_field($data['page']);

  $params = [
    'post_type' => 'song',
    'posts_per_page' => 15,
    'paged' => $page,
    'meta_query' => []
  ];

  if ($data['artist']) {
    $params['meta_query']['artist'] = $data['artist'];
  } else if ($data['tag']) {
    $params['meta_query']['tag'] = $data['tag'];
  } else if ($data['all_songs']) {
    $params['posts_per_page'] = 50;
  } else {
    $params['author'] = get_current_user_id();
  }

  $uploaded_songs = new WP_Query($params);

  $posts = array_map(function ($post) {
    $my_saved_songs = my_saved_items('songs', 'song', 1, -1)['my_items_ids'];
    $is_saved_song = false;

    if (in_array($post->ID, $my_saved_songs)) {
      $is_saved_song = true;
    }

    return [
      'ID' => $post->ID,
      'title' => $post->post_title,
      'link' => get_permalink($post->ID),
      'artist_link' => get_permalink(get_field('artist', $post->ID)[0]),
      'author_link' => site_url('/user-profile?user_id=' . $post->post_author),
      'author_name' => get_the_author_meta('display_name', $post->post_author),
      'author_id' => $post->post_author,
      'band' => get_field('band', $post->ID),
      'song_link' => get_field('song_link', $post->ID)['url'],
      'is_saved_song' => $is_saved_song
    ];
  }, $uploaded_songs->posts);

  return new WP_REST_Response(["posts" =>  $posts], 200);
}
