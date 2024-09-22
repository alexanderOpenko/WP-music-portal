<?php add_action('rest_api_init', 'manage_updating_posts');

function manage_updating_posts() {
    register_rest_route('music/v1', 'updateCustomPostContent', [
        'methods' => 'POST',
        'callback' => 'updateContent'
    ]);
}

function updateContent($data) {
    return wp_update_post(['ID' => $data['post-id'], 'post_content' => $data['content']]);
}