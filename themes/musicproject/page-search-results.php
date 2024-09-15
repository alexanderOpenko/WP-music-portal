<?php
get_header();
$type = isset($_GET['type']) ? sanitize_text_field($_GET['type']) : 'post';
$ids = isset($_GET['ids']) ? array_map('intval', explode(',', $_GET['ids'])) : [];
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$search_query = new WP_Query([
    'post_type' => $type,	
    'posts_per_page' => '20',
    'post__in' => $ids,
    'paged' => $paged // страница пагинации
]);

echo '<div class="content-container">';

if ($type === 'song' && $search_query->found_posts) {
    echo '<h1>Search results songs</h1>';
    get_template_part('template-parts/songs-list', null, ['songs' => $search_query]);
}

if ($type === 'artist' && $search_query->found_posts) {
    get_template_part('template-parts/artists-grid', null, [
        'artists' => $search_query,
        'title' => 'Search results artists'
    ]);
}

if ($type === 'musictag' && $search_query->found_posts) {
    echo '<h1>Search results tags</h1>';

    get_template_part('template-parts/tags-grid', null, ['tags' => $search_query]);
}

echo paginate_links([
    'total' => $search_query->max_num_pages,
    'current' => $paged,
    'prev_text' => __('« Previous'),
    'next_text' => __('Next »')
]);

echo '</div>';
wp_reset_query();

get_footer();
