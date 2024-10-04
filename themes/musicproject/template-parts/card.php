<div class="card flex flex-col relative">
    <a href="<?php the_permalink() ?>" class="full-absolute z-10 ajax-link">
    </a>
    <div class="card_image relative pt-[100%]">
        <img class="full-absolute object-cover" src="<?php echo $args['image_url'] ?>" />
    </div>
    <div class="card_info p-3 bg-gray-100 text-gray-600 capitilize border border-solid border-gray-300">
        <?php the_title() ?>
    </div>
</div>