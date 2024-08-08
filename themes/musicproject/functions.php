<?php
require get_theme_file_path('/inc/tag.php');
require get_theme_file_path('/inc/songs-pagination.php');
require get_theme_file_path('/inc/search-tags.php');
require get_theme_file_path('/inc/song.php');
require get_theme_file_path('/inc/artist.php');
require get_theme_file_path('/inc/custom-functions.php');

require_once( ABSPATH . 'wp-admin/includes/post.php' );
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );
function music_files()
{
    wp_enqueue_style('main-music-style', get_theme_file_uri('/build/index.css'));
    wp_enqueue_script('main-music-script', get_theme_file_uri('/src/index.js'), ['jquery'], false, ['in_footer' => true]);
    wp_enqueue_script( 'yt-iframe', 'https://www.youtube.com/iframe_api', [], false, ['in_footer' => true] );

    wp_localize_script('main-music-script', 'universityData', [
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest')
    ]);
    
  add_action('after_setup_theme', 'university_features');
}

add_action('wp_enqueue_scripts', 'music_files');
