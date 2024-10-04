<?php add_action('rest_api_init', 'tagRoutes');

function tagRoutes()
{
  register_rest_route('music/v1', 'manageTag', array(
    'methods' => 'POST',
    'callback' => 'createTag'
  ));

  register_rest_route('music/v1', 'updateTag', array(
    'methods' => 'POST',
    'callback' => 'updateTag'
  ));

  register_rest_route('music/v1', 'allTagsPaginations', array(
    'methods' => 'GET',
    'callback' => 'allTagsPaginations'
  ));

  register_rest_route('music/v1', 'savedTagsPaginations', array(
    'methods' => 'GET',
    'callback' => 'getSavedTagsPaginations'
  ));
}

function getSavedTagsPaginations($data) {
  $saved_artists = my_saved_items('tag', 'musictag', $data['page'], 15)['saved_items'];

    $artists = array_map(function ($post) {
        $image_id = get_post_meta($post->ID, 'tag_image', true);
        $image_link = get_post_image_custom($image_id, 'thumb');

        return [
            'image_link' => $image_link ?: get_template_directory_uri() . '/images/artist-placeholder.jpg',
            'link' => get_permalink($post->ID),
            'title' => $post->post_title
        ];
    }, $saved_artists->posts);

    return new WP_REST_Response(['posts' => $artists], 200);
}

function allTagsPaginations($data) {
  $myTags = new WP_Query([
    'post_type' => 'musictag',
    'posts_per_page' => 50,
    'paged' => $data['page']
  ]);

  $tags = array_map(function ($post) {
    $image_id = get_post_meta($post->ID, 'tag_image', true);
    $image_link = get_post_image_custom($image_id, 'thumb');
    
    return [
      'image_link' =>  $image_link ?: get_template_directory_uri() . '/images/artist-placeholder.jpg',
      'link' => get_permalink($post->ID),
      'title' => $post->post_title
    ];
  }, $myTags->posts);
  return new WP_REST_Response(["posts" =>  $tags], 200);
}

function updateTag($data)
{
  print_r($data['tags']);
  $tags = $data['tags'] ?? [];

  if ($data['tags']) {
    // add_value_to_field('tag', $tags, $data['post_id']);
    update_post_meta( $data['post_id'], 'tag', $data['tags']);
  } else {
    update_post_meta( $data['post_id'], 'tag', []);
  }

  return new WP_REST_Response(['post' => get_posts(['post_type' => 'musictag', 'post__in' => $tags, 'posts_per_page' => -1])], 200);
}

function createTag($data)
{
  $title = sanitize_text_field($data['title']);
  $isExistTag = post_exists($title, '', '', 'musictag', 'publish');

  if ($isExistTag == 0) {
    $is_image_uploaded = false;
    $meta_input = [];

    if (isset($_FILES['image_tag']) && $_FILES['image_tag']['size'] > 0) {
      $is_image_uploaded = true;
    }

    if ($is_image_uploaded) {
      $mediaId = media_handle_upload('image_tag', 0);
      $meta_input['tag_image'] = $mediaId;
    }

    $tag_id = wp_insert_post([
      'post_type' => 'musictag',
      'post_status' => 'publish',
      'post_title' => $data['title'],
      'post_content' => $data['content'],
      'meta_input' => $meta_input
    ], true);

    // updateUserTags($tag_id);

    $postQuery = new WP_query([
      'post_type' => 'musictag',
      'p' => $tag_id
    ]);

    $tag = array_map(function ($post) use ($is_image_uploaded) {
      if ($is_image_uploaded) {
        $image_id = get_post_meta($post->ID, 'tag_image', true);
        $image_link = get_post_image_custom($image_id, 'thumb');
      }

      return [
        'image_link' => $is_image_uploaded ? $image_link : get_template_directory_uri() . '/images/artist-placeholder.jpg',
        'link' => get_permalink($post->ID),
        'title' => $post->post_title
      ];
    }, $postQuery->posts);
    return new WP_REST_Response(["post" =>  $tag[0]], 200);
  } else {
    return new WP_REST_Response(["message" =>  "Content already exist"], 409);
  }
}
