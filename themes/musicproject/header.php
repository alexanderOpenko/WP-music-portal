<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div id="player">
    </div>

    <div id="content-container">
        <!-- Контент будет загружаться сюда -->
    <header class="bg-black p-5 flex justify-end">
        <div class="flex items-center gap-2.5">
            <?php if (is_user_logged_in()) : ?>
                <span class="text-white">
                    <a href="<?php echo site_url() ?>">
                        <?php echo get_avatar(get_current_user_id()); ?>
                    </a>
                </span>

                <a href="<?php echo wp_logout_url();  ?>" class="">
                    <span class="text-white">Log Out</span>
                </a>
            <?php else : ?>
                <a href="<?php echo wp_login_url(); ?>" class="text-white">Login</a>
                <a href="<?php echo wp_registration_url(); ?>" class="text-white">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>