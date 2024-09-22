<?php if (is_user_logged_in()) : ?>
    <div class="edit-post-image w-full max-w-2/4 ml-4">
        <form class="update-post-image">
            <input type="hidden" name="post_id" value="<?php echo $args['ID'] ?>" />
            <input type="hidden" value="<?php echo $args['image_id'] ?>" name="image_id" />
            <input type="hidden" value="<?php echo $args['field_name'] ?>" name="field_name" />

            <label for="image">
                <h2>
                    Change image
                </h2>
            </label>
            <?php get_template_part('template-parts/upload-button', null, ['image_name' => 'image']);
            get_template_part('template-parts/image-holder');
            ?>
            <button type="submit">
                Submit
            </button>
        </form>
    </div>
<?php endif ?>