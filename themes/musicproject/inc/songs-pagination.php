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
  } else {
    $params['author'] = get_current_user_id();
  }

  $uploaded_songs = new WP_Query($params);

  $posts = array_map(function ($post) {
    return [
      'ID' => $post->ID,
      'title' => $post->post_title,
      'link' => get_permalink($post->ID),
      'song_link' => get_field('song_link', $post->ID)['url'],  // Получаем URL поля song_link из ACF
    ];
  }, $uploaded_songs->posts);

  return new WP_REST_Response(["posts" =>  $posts], 200);
}
