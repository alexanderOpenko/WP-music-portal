<form class="save-song-form">
    <input name="post_id" type="hidden" value="<?php echo $args['post_id'] ?>"/>
    <input name="action" type="hidden" value="<?php echo $args['action'] ?>"/>

    <button
        class="save-icon-btn <?php echo isset($args['btn_class']) ? $args['btn_class'] : ''; ?>"
        type="submit"
    >
    </button>
</form>