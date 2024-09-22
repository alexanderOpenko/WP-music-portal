<?php add_action('rest_api_init', 'manageImage');

function manageImage()
{
    register_rest_route('music/v1/', 'updateImage', [
        'methods' => 'POST',
        'callback' => 'updateImage'
    ]);
}

function updateImage($data) {
    $mediaId = 0;

    if ($data['image_id']) {
        wp_delete_attachment($data['image_id']);
    }

    if (isset($_FILES['image']) && $_FILES['image']['size'] > 0) {
        $mediaId = media_handle_upload('image', 0);
        update_post_meta($data['post_id'], $data['field_name'], $mediaId);
    }
}