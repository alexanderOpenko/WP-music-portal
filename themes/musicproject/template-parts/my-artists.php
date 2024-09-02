<?php 
function active_tab_classes($tab_name) {
    global $wp;

    $tab_item_classes = "tab flex flex-col py-3 px-5 mr-2 text-black rounded-t-lg border border-gray-300 border-b-0";
    if ($wp->request === $tab_name) {
        $tab_item_classes .= ' active pointer-events-none';
    }
    echo $tab_item_classes;
}
?>

<div class="content-container">
    <div class="mb-8 uppercase">
        <div class="flex border-b-2 border-gray-300">
            <a href="<?php echo home_url('favorite-artists')?>" class="<?php active_tab_classes('favorite-artists') ?>">
                Uploaded Artists
                <span class="tab-description text-white text-xs text-gray-600">Your uploaded artists</span>
            </a>

            <a href="#" class="<?php active_tab_classes('savedartist') ?>">
                Saved Artists
                <span class="tab-description text-white text-xs text-gray-600">Artists saved from search</span>
            </a>
        </div>
        <div class="w-full h-[2px] bg-slate-900"></div>
    </div>

    <?php get_template_part('template-parts/artists-grid', null, ['artists' => $args['artists']]) ?>
</div>