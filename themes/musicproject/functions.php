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
require get_theme_file_path('/inc/updatePosts.php');
require get_theme_file_path('/inc/update-image.php');

require_once(ABSPATH . 'wp-admin/includes/post.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/media.php');
function music_files()
{
  wp_enqueue_style('main-music-style', get_theme_file_uri('/build/index.css'), [], null);
  wp_enqueue_script('main-music-script', get_theme_file_uri('/src/index.js'), ['jquery'], false, ['in_footer' => true]);
  wp_enqueue_script('yt-iframe', 'https://www.youtube.com/iframe_api', [], false, ['in_footer' => true]);
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

  wp_enqueue_style('font-name', 'https://fonts.googleapis.com/css?family=Open+Sans|Roboto+Slab');

  wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+Display:ital,wght@0,100..900;1,100..900&display=swap');

  wp_localize_script('main-music-script', 'musicData', [
    'logged_user_id' => get_current_user_id(),
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

function my_nav_menu_submenu_css_class($classes, $args)
{
  if (isset($args->mobile)) {
    $classes[] = 'accordion-content invisible';
  }

  return $classes;
}

add_filter('nav_menu_submenu_css_class', 'my_nav_menu_submenu_css_class', 10, 3);

function add_accordion_button_to_menu($args, $item, $depth)
{

  if (isset($args->mobile) && in_array('menu-item-has-children', $item->classes) && $depth === 0) {
    $args->before = '<div class="menu-item-container flex items-center justify-between">';
    $args->after = '<button class="accordion-button w-2/4 py-[15px] px-[20px] flex justify-end" aria-expanded="false">
    <div class="icon">
    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" viewBox="0 0 330 330" xml:space="preserve">
            <path id="XMLID_225_" d="M325.607,79.393c-5.857-5.857-15.355-5.858-21.213,0.001l-139.39,139.393L25.607,79.393  c-5.857-5.857-15.355-5.858-21.213,0.001c-5.858,5.858-5.858,15.355,0,21.213l150.004,150c2.813,2.813,6.628,4.393,10.606,4.393  s7.794-1.581,10.606-4.394l149.996-150C331.465,94.749,331.465,85.251,325.607,79.393z"></path>
        </svg>
        </div>
    </button></div>';
  } else {
    $args->before = '';
    $args->after = '';
  }

  return $args;
}

add_filter('nav_menu_item_args', 'add_accordion_button_to_menu', 10, 3);


function add_additional_class_on_li($classes, $item, $args)
{
  if (isset($args->add_li_class) && in_array('menu-item-has-children', $item->classes)) {
    $classes[] = $args->add_li_class;
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'add_additional_class_on_li', 1, 3);

function auto_redirect_after_logout(){
  wp_safe_redirect( site_url('/login') );
  exit;
}

add_action('wp_logout','auto_redirect_after_logout');

add_action( 'phpmailer_init', 'smtp_phpmailer_init' );

function smtp_phpmailer_init( $phpmailer ){
	$phpmailer->IsSMTP();

	$phpmailer->CharSet    = 'UTF-8';

	$phpmailer->Host       = 'sandbox.smtp.mailtrap.io';
	$phpmailer->Username   = '26b52b7221551c';
	$phpmailer->Password   = '17c8fc736da34a';
	$phpmailer->SMTPAuth   = true;

	$phpmailer->Port       = 2525;

	$phpmailer->isHTML( true );
}

