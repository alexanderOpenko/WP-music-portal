
<?php 
$tag_ids = $args['tag_ids'];
$remaining_tags = new WP_Query();

if (count($tag_ids)):
    $first_tags = new WP_Query([
        'post_type' => 'musictag',
        'post__in' => array_slice($tag_ids, 0, 4),
    ]);

    if (count($tag_ids) > 4) {
        $remaining_tags = new WP_Query([
            'post_type' => 'musictag',
            'post__in' => array_slice($tag_ids, 4),
        ]);
    } ?>

    <div class="mb-8 accordion">
        <div class="flex">
            <?php while ($first_tags->have_posts()):
                $first_tags->the_post();
            ?>
                <button class="tag-button mr-3">
                    <?php the_title(); ?>
                </button>
            <?php endwhile; wp_reset_postdata() ?>

            <?php if (count($tag_ids) > 4): ?>
                <?php get_template_part('template-parts/single-accordion-button') ?>
            <?php endif ?>
        </div>

        <?php if ($remaining_tags->have_posts()): ?>
            <div class="flex mt-4 accordion-content invisible">
                <?php while ($remaining_tags->have_posts()):
                    $remaining_tags->the_post();
                ?>
                    <button class="tag-button mr-3">
                        <?php the_title(); ?>
                    </button>
                <?php endwhile; wp_reset_postdata()?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>