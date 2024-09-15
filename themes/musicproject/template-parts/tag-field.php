<?php 
$default_tags = [];
$all_tag_ids = []; // Массив для хранения всех ID тегов

$tagsForDefaultList = new WP_Query([
    'post_type' => 'musictag',
    'posts_per_page' => 10,
]);

// Собираем ID из $tagsForDefaultList
while ($tagsForDefaultList->have_posts()) {
    $tagsForDefaultList->the_post();
    $all_tag_ids[] = get_the_ID();
    array_push($default_tags, [
        'value' => get_the_ID(), 
        'text' => get_the_title(), 
    ]);
}
wp_reset_postdata();

if (isset($args['tag_ids'])) {
    // Добавляем ID из переданных $args['tag_ids']
    $all_tag_ids = array_unique(array_merge($all_tag_ids, $args['tag_ids']));
}

// Выполняем объединённый запрос
$tags = new WP_Query([
    'post_type' => 'musictag',
    'post__in' => $all_tag_ids,
    'posts_per_page' => -1, // Получаем все посты
]);
?>

<div class="mb-[15px] tags-select-wrapper w-full">
    <select multiple name="tags[]" class="input !mb-0" type="text" id="tags-select" data-default-tags='<?php echo json_encode($default_tags) ?>'>
        <?php while ($tags->have_posts()) :
            $tags->the_post() ?>
            <option <?php echo isset($args['tag_ids']) && in_array(get_the_ID(), $args['tag_ids']) ? 'selected=true' : '' ?> value="<?php echo get_the_ID() ?>"><?php the_title() ?></option>
        <?php endwhile;
        wp_reset_postdata(); ?>
    </select>

    <div class="bg-white relative max-h-[16px] px-[16px]">
        <div id="spinner" class="spinner absolute bg-white hidden"></div>
        <div class="select-message-field">
        </div>
    </div>
</div>
