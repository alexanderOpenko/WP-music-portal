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
    <div class="youtube-video w-full h-full max-w-[500px] max-h-[310px] flex flex-col absolute bottom-0 right-0 p-5">
        <div class="close-icon close-video-js hidden py-2">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve"><path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4  L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1  c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1  c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z"/></svg>
        </div>

        <div id="player">
        </div>
    </div>

    <div id="content-container">
        <!-- Контент будет загружаться сюда -->
    <header class="bg-black p-5 flex justify-end">
    <div class="text-white">
            <a class="ajax-link" href="<?php echo site_url('/tag/')?>"> Tags </a>|
            <a class="ajax-link" href="<?php echo site_url('/favorite-songs/')?>">
                Songs (upload songs (mark with tags), save songs)
            </a>|
            <a class="ajax-link" href="<?php echo site_url('/favorite-artists/')?>"> 
                artists (upload your artist (mark with tags), and explore artist to add to list)
            </a>|
            <a> albums (add or save from searching albums)</a>|
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