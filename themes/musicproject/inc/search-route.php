<?php add_action('rest_api_init', 'musicRegisterSearch');

function musicRegisterSearch()
{
  register_rest_route('music/v1', 'search', [
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'musicSearchResults'
  ]);
}

function musicSearchResults($data)
{
  $mainQuery = new WP_Query([
    'post_type' => ['song', 'musictag', 'artist'],
    's' => sanitize_text_field($data['term'])
  ]);


  $results = [
    'song_ids' => [],
    'musictag_ids' => [],
    'artist_ids' => [],
    'song' => [],
    'musictag' => [],
    'artist' => []
  ];

  while ($mainQuery->have_posts()) {
    $mainQuery->the_post();

    if (get_post_type() == 'song') {
      $results['song_ids'][] = get_the_id();

      if (count($results['song']) < 7) {
        array_push($results['song'], [
          'ID' => get_the_id(),
          'title' => get_the_title(),
          'song_link' => str_replace([',', ' '], '', get_field('song_link')['url']),
          'link' => get_the_permalink(),
          'band' => get_the_title(get_field('artist')[0]),
          'postType' => get_post_type(),
          'authorName' => get_the_author()
        ]);
      }
    }

    if (get_post_type() == 'musictag') {
      $results['musictag_ids'][] = get_the_id();

      if (count($results['musictag']) < 7) {
        array_push($results['musictag'], [
          'id' => get_the_id(),
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
        ]);
      }
    }

    if (get_post_type() == 'artist') {
      $results['artist_ids'][] = get_the_id();

      if (count($results['artist']) < 7) {
        array_push($results['artist'], [
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
        ]);
      }
    }

    if ($results['musictag']) {
      $tagsMetaQuery = ['relation' => 'OR'];

      foreach ($results['musictag'] as $item) {
        $tagsMetaQuery[] = [
          'key' => 'tag',
          'compare' => 'LIKE',
          'value' => '"' . $item['id'] . '"'
        ];
      }

      $tagRelationshipQuery = new WP_Query(array(
        'post_type' => ['song', 'artist'],
        'meta_query' => $tagsMetaQuery
      ));

      while ($tagRelationshipQuery->have_posts()) {
        $tagRelationshipQuery->the_post();

        if (get_post_type() == 'song' && !in_array(get_the_id(), $results['song_ids'])) {
          $results['song_ids'][] = get_the_id();
        }

        if (get_post_type() == 'song' && count($results['song']) < 7) {
          array_push($results['song'], [
            'ID' => get_the_id(),
            'title' => get_the_title(),
            'song_link' => str_replace([',', ' '], '', get_field('song_link')['url']),
            'link' => get_the_permalink(),
            'band' => get_the_title(get_field('artist')[0]),
            'postType' => get_post_type(),
            'authorName' => get_the_author()
          ]);
        }

        if (get_post_type() == 'artist' && !in_array(get_the_id(), $results['artist_ids'])) {
          $results['artist_ids'][] = get_the_id();
        }

        if (get_post_type() == 'artist' && count($results['artist']) < 7) {
          array_push($results['artist'], [
            'title' => get_the_title(),
            'permalink' => get_the_permalink()
          ]);
        }
      }
    }
    $results['song'] = array_values(array_unique($results['song'], SORT_REGULAR));
    $results['artist'] = array_values(array_unique($results['artist'], SORT_REGULAR));

    if (!empty($results['artist_ids'])) {
      $artistMetaQuery = ['relation' => 'OR'];
  
      foreach ($results['artist_ids'] as $artist_id) {
        $artistMetaQuery[] = [
          'key' => 'artist', // ключ для артиста в метаполях
          'compare' => 'LIKE',
          'value' => $artist_id
        ];
      }
  
      // Запрос песен на основе найденных артистов
      $artistRelationshipQuery = new WP_Query([
        'post_type' => 'song',
        'meta_query' => $artistMetaQuery
      ]);
  
      while ($artistRelationshipQuery->have_posts()) {
        $artistRelationshipQuery->the_post();

        if (!in_array(get_the_id(), $results['song_ids'])) {
            $results['song_ids'][] = get_the_id();
        }

        array_push($results['song'], [
          'ID' => get_the_id(),
          'title' => get_the_title(),
          'song_link' => str_replace([',', ' '], '', get_field('song_link')['url']),
          'link' => get_the_permalink(),
          'band' => get_the_title(get_field('artist')[0]),
          'postType' => get_post_type(),
          'authorName' => get_the_author()
        ]);
      }
  
      $results['song'] = array_values(array_unique($results['song'], SORT_REGULAR));
    }
  
  }
  return $results;
}
