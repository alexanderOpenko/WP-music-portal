<div class="card flex flex-col relative">
    <a href="<?php the_permalink() ?>" class="full-absolute z-10 ajax-link">
    </a>
    <div class="card_image relative pt-[100%]">
        <img class="full-absolute object-cover" src="<?php echo $args['image_url'] ?>" />
    </div>
    <div class="card_info">
        <?php the_title() ?>
    </div>
</div>