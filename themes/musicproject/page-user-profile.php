<?php
get_header();

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    $user = get_user_by('ID', $user_id);

    if ($user) {
        $avatar_url = get_avatar_url($user->ID);
        $display_name = $user->display_name;
        $email = $user->user_email;
        $description = get_the_author_meta('description', $user->ID);

?>
        <div class="content-container mx-auto mt-10">
            <div class="max-w-xl mx-auto p-5 bg-white shadow rounded-lg">
                <!-- Аватар -->
                <div class="flex justify-center mb-4">
                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($display_name); ?>" class="w-24 h-24 rounded-full object-cover" />
                </div>

                <h1 class="text-center text-2xl font-bold mb-2"><?php echo esc_html($display_name); ?></h1>

                <p class="text-center text-gray-600 mb-4"><?php echo esc_html($email); ?></p>

                <?php if (!empty($description)) : ?>
                    <div class="text-center text-gray-700">
                        <p><?php echo esc_html($description); ?></p>
                    </div>
                <?php endif; ?>

                <div class="text-center">
                    <?php if (get_current_user_id() === $user_id) : ?>
                        <a href="<?php echo site_url('/edit-profile') ?>"> Edit profile </a>
                    <?php endif ?>
                </div>
            </div>
        </div>
<?php
    } else {
        echo '<p>User not found.</p>';
    }
} else {
    echo '<p>No user ID provided.</p>';
}

get_footer();
?>