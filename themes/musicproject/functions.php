<?php
global $wp;
add_filter('show_admin_bar', '__return_false');

function music_features()
{
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('thumb', 400, 400);
  // add_image_size('portrait', 480, 650, true);
  // add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'music_features');

require get_theme_file_path('/inc/tag.php');
require get_theme_file_path('/inc/songs-pagination.php');
require get_theme_file_path('/inc/search-tags.php');
require get_theme_file_path('/inc/song.php');
require get_theme_file_path('/inc/artist.php');
require get_theme_file_path('/inc/search-route.php');
require get_theme_file_path('/inc/custom-functions.php');

require_once(ABSPATH . 'wp-admin/includes/post.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
function music_files()
{
  wp_enqueue_style('main-music-style', get_theme_file_uri('/build/index.css'));
  wp_enqueue_script('main-music-script', get_theme_file_uri('/src/index.js'), ['jquery'], false, ['in_footer' => true]);
  wp_enqueue_script('yt-iframe', 'https://www.youtube.com/iframe_api', [], false, ['in_footer' => true]);
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  wp_localize_script('main-music-script', 'musicData', [
    'root_url' => get_site_url(),
    'nonce' => wp_create_nonce('wp_rest'),
    'theme_url' => get_template_directory_uri()
  ]);
}

add_action('wp_enqueue_scripts', 'music_files');

function delete_song_id_from_artist_on_song_delete($post_id)
{
  if (get_post_type($post_id) == 'song') {
    $artist_field = get_post_field('artist', $post_id);
    $artist_id = $artist_field[0];
    $songs = get_field('songs', $artist_id);

    if (!empty($songs) && ($key = array_search($post_id, $songs)) !== false) {
      unset($songs[$key]);
      update_field('songs', $songs, $artist_id);
    }
  }
}
add_action('before_delete_post', 'delete_song_id_from_artist_on_song_delete');

function delete_musictag_id_from_related_posts_on_delete($post_id) 
{
  if (get_post_type($post_id) == 'musictag') {
    $args = [
      'author' => get_current_user_id(),
      'post_type' => ['artist', 'song'],
      'posts_per_page' => -1,
      'post_status' => 'publish'
    ];
    $related_posts = get_posts($args);

    foreach ($related_posts as $post) {
      $musictags = get_field('tag', $post->ID);

      if (is_array($musictags) && ($key = array_search($post_id, $musictags)) !== false) {
        unset($musictags[$key]); 
        $musictags = array_values($musictags); 
        update_field('tag', $musictags, $post->ID); 
      } 
    }
  }
}

add_action('before_delete_post', 'delete_musictag_id_from_related_posts_on_delete');

function delete_songs_when_artist_is_deleted($post_id) 
{
  if (get_post_type($post_id) == 'artist') {
    $args = [
      'post_type' => 'song',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'meta_query' => [
        'relation' => 'OR',
        [
          'key' => 'artist', 
          'value' => $post_id,
          'compare' => 'LIKE' 
        ]
      ]
    ];
    
    $songs = get_posts($args);

    foreach ($songs as $song) {
      wp_delete_post($song->ID, true); // Delete each song
    }
  }
}

add_action('before_delete_post', 'delete_songs_when_artist_is_deleted');

