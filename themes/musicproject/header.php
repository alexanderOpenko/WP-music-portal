<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link
  rel="stylesheet"
  href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
  integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
  crossorigin="anonymous"
  referrerpolicy="no-referrer"
/>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div id="player">
    </div>

    <div id="content-container">
        <!-- Контент будет загружаться сюда -->
    <header class="bg-black p-5 flex justify-end">
    <div class="text-white">
            <a class="ajax-link" href="<?php echo site_url('/tag/')?>"> Tags </a>
            <a class="ajax-link" href="<?php echo site_url('/favorite-songs/')?>">Songs (upload songs (mark with tags), save songs)</a>
            <a> artists (upload your artist (mark with tags), and explore artist to add to list)</a>
            <a> albums (add or save from searching albums)</a>
        </div>
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