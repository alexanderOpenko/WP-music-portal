<?php

function music_post_types() {
  register_post_type('musictag', array(
    // 'capability_type' => 'music tag',
    // 'map_meta_cap' => 'true',
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt'),
    'rewrite' => array('slug' => 'musictags'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'music tags',
      'add_new_item' => 'Add New Tag',
      'edit_item' => 'Edit Tag',
      'all_items' => 'All Tags',
      'singular_name' => 'Music Tag'
    ),
    'menu_icon' => 'dashicons-calendar'
  ));

  register_post_type('song', array(
    // 'capability_type' => 'music tag',
    // 'map_meta_cap' => 'true',
    'show_in_rest' => true,
    'supports' => array('title', 'editor', 'excerpt'),
    'rewrite' => array('slug' => 'songs'),
    'has_archive' => true,
    'public' => true,
    'labels' => array(
      'name' => 'songs',
      'add_new_item' => 'Add New song',
      'edit_item' => 'Edit song',
      'all_items' => 'All songs',
      'singular_name' => 'Song'
    ),
    'menu_icon' => 'dashicons-calendar'
  ));
}

add_action('init', 'music_post_types');