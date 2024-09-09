<?php

/**
 * Adds a value or values to a custom field if they are not already present.
 *
 * @param string $field_key The key of the custom field.
 * @param mixed $value The value to add to the field. Can be a single value or an array of values.
 * @param int $post_id The ID of the post to which the custom field belongs.
 */

function add_value_to_field(string $field_key, mixed $value, int $post_id): void
{
    $field = get_field($field_key, $post_id) ?: [];

    if (is_array($value)) {
        foreach ($value as $single_value) {
            if (!in_array($single_value, $field)) {
                $field[] = $single_value;
            }
        }
    } else {
        if (!in_array($value, $field)) {
            $field[] = $value;
        }
    }

    update_post_meta($post_id, $field_key, $field);
}

function remove_value_from_field(string $field_key, mixed $value, int $post_id): void
{
    $field = get_field($field_key, $post_id) ?: [];

    if (is_array($value)) {
        $field = array_diff($field, $value);
    } else {
        $field = array_diff($field, [$value]);
    }

    update_post_meta($post_id, $field_key, $field);
}

function checkArtistExisting(string $band): bool
{
    $query = new WP_Query([
        'author' => get_current_user_id(),
        'title' => $band,
        'post_type' => 'artist',
        'post_status' => 'publish',
        'posts_per_page' => 1
    ]);

    return $query->found_posts == 0 ? false : true;
}

function my_saved_items(string $field_type, string $post_type) {
    if (!is_user_logged_in()) {
        return [];
    }

    // Получаем ID первого поста 'usermeta' автора
    $my_items_post_id = get_posts([
        'author' => get_current_user_id(),
        'post_type' => 'usermeta',
        'post_status' => 'publish',
        'fields' => 'ids',
        'posts_per_page' => 1
    ]);

    // Извлекаем сохранённые ID песен, если пост найден
    $my_items_ids = !empty($my_items_post_id) ? array_values(get_field($field_type, $my_items_post_id[0])) : [];

    $saved_items = null;
    if (!empty($my_items_ids)) {
        $saved_items = new WP_Query([
            'post_type' => $post_type,
            'post__in' => $my_items_ids
        ]);
    }

    return [
        'my_items_ids' => $my_items_ids,
        'saved_items' => $saved_items,
    ];
}

function my_tags() {
    if (!is_user_logged_in()) {
        return [];
    }

    $my_tags_post = new WP_Query([
        'author' => get_current_user_id(),
        'post_type' => 'usermeta',
        'post_status' => 'publish',
    ]);
    
    $my_tags = [];
    
    if ($my_tags_post->have_posts()) {
        $my_tags_post->the_post();
        $my_tags_post_id = get_the_ID();
        $my_tags = get_post_field('tag', $my_tags_post_id);
    }

    return $my_tags !== '' ? $my_tags : [];
}
//add paged
function recomended_post_type(int $posts_count, string $post_type, array $tags = [], int $paged = 1) {
    if (empty($tags)) {
        return new WP_Query();
    }
    
    $meta_query = ['relation' => 'OR'];

    foreach ($tags as $tag) {
        $meta_query[] = [
            'key' => 'tag',
            'value' => '"' . $tag . '"',
            'compare' => 'LIKE',
        ];
    }

    $recomended_posts = new WP_Query([
        'post_type' => $post_type,
        // 'author__not_in' => [get_current_user_id()],
        'post_status' => 'publish',
        'posts_per_page' => $posts_count,
        'meta_query' => $meta_query,
        'paged' => $paged
    ]);

    return $recomended_posts;
}

function getMostPopularSongs(int $posts_count, int $paged = 1) {
    return new WP_Query([
        'post_type' => 'song',
        'posts_per_page' => $posts_count,
        'meta_key' => 'play_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'paged' => $paged
    ]);
}

function getMostPopularArtists(int $posts_count, int $paged = 1) {
    return new WP_Query([
        'post_type' => 'artist',
        'posts_per_page' => $posts_count,
        'meta_key' => 'play_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'paged' => $paged
    ]);
}

function update_artist_tags($data)
{
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

    return [
        'artist_id' => $artist_id
    ];
}

function updateUserSavedItems(array|int|string $item_id, string $action, string $post_type) {
    $query = new WP_Query([
        'author' => get_current_user_id(),
        'post_type' => 'usermeta',
        'post_status' => 'publish',
        'posts_per_page' => 1
    ]);

    if ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        wp_reset_postdata();

        if ($action === 'save') {
            add_value_to_field($post_type, $item_id, $post_id);
        } else {
            remove_value_from_field($post_type, $item_id, $post_id);
        }
    } else {
        wp_insert_post([
            'post_type' => 'usermeta',
            'post_status' => 'publish',
            'post_title' => wp_get_current_user()->nickname,
            'meta_input' => [
                'user' => get_current_user_id(),
                $post_type => [$item_id]
            ]
        ]);
    }

    return true;
}

function updateUserTags(array|int|string $tags) {
    $post_id = 0;

    $query = new WP_Query([
        'author' => get_current_user_id(),
        'post_type' => 'usermeta',
        'post_status' => 'publish',
        'posts_per_page' => 1
    ]);

    if ($query->have_posts()) {
        $query->the_post();
        $post_id = get_the_ID();
        wp_reset_postdata();
        add_value_to_field('tag', explode(',', $tags), $post_id);
    } else {
        wp_insert_post([
            'post_type' => 'usermeta',
            'post_status' => 'publish',
            'post_title' => wp_get_current_user()->nickname,
            'meta_input' => [
                'user' => get_current_user_id(),
                'tag' => explode(',', $tags)
            ]
        ]);
    }
}

function get_post_image_custom(int|string $image_data = null, $custom_size = 'thumbnail'): string
{
    if ($image_data != 0) {
    $image_url = is_numeric($image_data) ?
        wp_get_attachment_image_url($image_data, $custom_size) :
        $image_data;
    }

    return $image_url ??= get_template_directory_uri() . '/images/artist-placeholder.jpg';
}

function create_artist($data): array
{
    $is_image_uploaded = false;

    $meta_input = [
        'tag' => $data['tags'] ? explode(',', $data['tags']) : []
    ];

    if (isset($_FILES['artist_image']) && $_FILES['artist_image']['size'] > 0) {
        $is_image_uploaded = true;
    }

    if ($is_image_uploaded) {
        $mediaId = media_handle_upload('artist_image', 0);
        $meta_input['artist_image'] = $mediaId;
    }

    $artist_id = wp_insert_post([
        'post_type' => 'artist',
        'post_status' => 'publish',
        'post_title' => $data['band'],
        'post_content' => $data['artist_content'] ?? '',
        'meta_input' => $meta_input
    ]);

    $postQuery = new WP_query([
        'post_type' => 'artist',
        'p' => $artist_id
    ]);

    $artist = array_map(function ($post) use ($is_image_uploaded) {
        if ($is_image_uploaded) {
            $image_id = get_post_meta($post->ID, 'artist_image', true);
            $image_link = get_post_image_custom($image_id, 'thumb');
        }

        return [
            'image_link' => $is_image_uploaded ? $image_link : get_template_directory_uri() . '/images/artist-placeholder.jpg',
            'link' => get_permalink($post->ID),
            'title' => $post->post_title
        ];
    }, $postQuery->posts);

    return [
        'artist_id' => $artist_id,
        'artist' => $artist[0]
    ];
}
