<?php $textarea_classes = !$args['content'] ? 'hidden' : '' ?>

<?php if ($args['content'] || !$args['content'] && $args['post_author'] === get_current_user_id()) : ?>
    <div class="description-container">
        <div class="flex items-center">
            <h2>
                About
            </h2>

            <?php if ($args['post_author'] === get_current_user_id()) : ?>
                <div class="edit-description-js ml-[50px]" data-editting="disable">
                    <div class="edit-icon icon" >
                        <img src="<?php echo get_template_directory_uri() . '/images/edit.png' ?>" />
                    </div>

                    <div class="close-icon icon cursor-pointer hidden py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve">
                            <path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4  L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1  c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1  c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z" />
                        </svg>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <form class="edit-post-description">
            <input name="post-type" type="hidden" value="<?php echo $args['post_type'] ?>" />
            <input name="post-id" type="hidden" value="<?php echo $args['post_id'] ?>" />

            <textarea readonly name="content" class="<?php echo $textarea_classes ?>"><?php echo esc_textarea($args['content']); ?></textarea>

            <button type="submit" class="hidden">
                Submit
            </button>
        </form>
    </div>
<?php endif ?>