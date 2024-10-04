<?php function active_tab_classes($tab_name)
{
    global $wp;
    $tab_item_classes = "tab flex flex-col py-3 px-5 sm:mr-4 mb-2 sm:mb-0 text-white";
    if (explode('/', $wp->request)[0] === $tab_name) {
        $tab_item_classes .= ' active pointer-events-none';
    }
    echo esc_attr($tab_item_classes);
} 
?>

<div class="mb-8 uppercase">
    <div class="flex sm:flex-row flex-col border-b-2 border-gray-300">
        <?php if (isset($args['tabs']) && is_array($args['tabs'])) : ?>
            <?php foreach ($args['tabs'] as $tab) :
                $title = isset($tab['title']) ? $tab['title'] : '';
                $description = isset($tab['description']) ? $tab['description'] : '';
                $slug = isset($tab['slug']) ? $tab['slug'] : '';
            ?>
                <a href="<?php echo esc_url(home_url($slug)); ?>" class="<?php active_tab_classes($slug); ?>">
                    <?php echo esc_html($title); ?>
                    <span class="tab-description text-white text-xs text-gray-600"><?php echo esc_html($description); ?></span>
                </a>
            <?php endforeach ?>
        <?php endif ?>
    </div>

    <div class="w-full h-[2px] bg-slate-300 mt-4"></div>
</div>
