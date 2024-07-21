<?php add_action('rest_api_init', 'manageSearchTags');

function manageSearchTags() {
    register_rest_route('music/v1', 'searchTags', [
        'method' => 'GET',
        'callback' => 'getTags'
    ]);
}

function getTags($data) {
    $tagsQuery = new WP_Query([
        'post_type' => 'musictag',
        's' => $data['q'],
        'search_columns' => ['post_title']
    ]);

    $tags = [];

    while($tagsQuery->have_posts()) {
        $tagsQuery->the_post();
        array_push($tags, ['value' => get_the_ID(), 'text' => get_the_title()]);
    }

    return new WP_REST_Response(["tags" =>  $tags], 200);
}