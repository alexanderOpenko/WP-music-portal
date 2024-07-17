<?php
get_header();
$uploaded_songs = new WP_Query([
    'author' => get_current_user_id(),
    'post_type' => 'song',
    'posts_per_page' => 1,
    'paged' => get_query_var('paged') ?: 1
]);

posts_nav_link();
?>

<div class="content-container">
    <div>
        <p>
            Upload your songs
        </p>
    </div>

    <div>
        <p>
            Uploaded songs
        </p>

        <div>
            <?php while ($uploaded_songs->have_posts()) {
                $uploaded_songs->the_post();
                get_template_part('template-parts/song-item');
            }

            echo paginate_links([
                'total' => $uploaded_songs->max_num_pages,
                'current' => $paged,
                'mid_size' => 2,
                'prev_text' => __('« Назад', 'textdomain'),
                'next_text' => __('Вперед »', 'textdomain'),
            ]);
            
            wp_reset_query();
            ?>
        </div>
    </div>
</div>

<?php
get_footer()
?>