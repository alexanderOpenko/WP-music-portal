<?php add_action('rest_api_init', 'tagRoutes');

function tagRoutes() {
  register_rest_route('music/v1', 'manageTag', array(
    'methods' => 'POST',
    'callback' => 'createTag'
  ));
}

function createTag($data) {
    $title = sanitize_text_field($data['title']);
    $isExistTag = post_exists($title, '', '', 'musictag');

    if ($isExistTag == 0) {
      $mediaId = media_handle_upload('image_tag', 0);

      return wp_insert_post([
        'post_type' => 'musictag',
        'post_status' => 'publish',
        'post_title' => $data['title'],
        'post_content' => $data['content'],
        'meta_input' => [
          'tag_image' => $mediaId
        ]
      ], true);
    } else {
      return new WP_REST_Response(["message" =>  "Content already exist"], 409);
    }
}