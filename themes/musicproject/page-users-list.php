<?php
get_header();

// Запрос всех пользователей
$users_query = new WP_User_Query(array(
    'number' => -1, // Получить всех пользователей
));

// Получение массива объектов пользователей
$users = $users_query->get_results(); ?>

<div class="content-container">
<?php if (!empty($users)) : ?>
    <div class="space-y-4"> <!-- Flex Column с отступом между элементами -->
        <?php foreach ($users as $user) : 
            $avatar_url = get_avatar_url($user->ID); // Получаем URL аватара
            $display_name = $user->display_name; // Получаем имя пользователя
        ?>
            <a href="<?php echo site_url('/user-profile?user_id=' . $user->ID)?>" class="block">
                <div class="flex items-center space-x-4"> <!-- Flex Row с отступом -->
                    <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($display_name); ?>" class="w-12 h-12 rounded-full object-cover" /> <!-- Круглый аватар -->
                    <span class="text-lg font-bold"><?php echo esc_html($display_name); ?></span> <!-- Имя пользователя -->
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <p class="text-gray-500">No users found.</p> <!-- Серый текст для сообщения -->
<?php endif; ?>
</div>
<?php get_footer(); ?>
