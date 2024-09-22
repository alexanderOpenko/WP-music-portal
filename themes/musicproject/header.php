<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
        integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer" />
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <div class="youtube-video w-full h-full max-w-[500px] max-h-[310px] flex flex-col fixed z-[-1] bottom-0 right-0 p-5">
        <div class="close-icon icon cursor-pointer close-video-js hidden py-2">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve">
                <path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4  L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1  c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1  c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z" />
            </svg>
        </div>

        <div id="player">
        </div>
    </div>

    <div class="flex items-center justify-center fixed inset-0 bg-gray-800 bg-opacity-50 z-50 spinner-js hidden">
        <div class="spinner !w-[50px] !h-[50px]"></div>
    </div>

    <div class="search-overlay">
        <div class="search-overlay__top flex items-center justify-between">
            <i class="fa fa-search search-overlay__icon ml-[15px] !text-[25px]" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <div class="icon cursor-pointer mr-[15px] search-overlay__close bg-transparent">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve">
                    <path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4  L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1  c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1  c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z" />
                </svg>
            </div>
        </div>

        <div class="content-container">
            <div id="search-overlay__results"></div>
        </div>
    </div>

    <header class="bg-black p-5 flex items-start justify-end">
        <div class="mobile-menu-wrapper accordion ml-0 mr-auto my-0 block sm:hidden">
            <div class="burger-icon accordion-button" id="burgerIcon">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <div class="accordion-content invisible">
                <?php wp_nav_menu([
                    'name' => 'menu',
                    'menu_class' => 'menu mobile-menu',
                    'mobile' => true,
                    'add_li_class' => 'accordion'
                ]) ?>
            </div>
        </div>

        <div class="desctop-menu hidden sm:block">
            <?php wp_nav_menu(['name' => 'menu']) ?>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="<?php echo esc_url(site_url('/search')); ?>" class="js-search-trigger site-header__search-trigger">
                <i class="fa fa-search" aria-hidden="true"></i>
            </a>

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
                <a href="<?php echo wp_login_url(); ?>" class="normal-link text-white">Login</a>
                <a href="<?php echo wp_registration_url(); ?>" class="normal-link text-white">Sign Up</a>
            <?php endif; ?>
        </div>
    </header>

    <div id="content-container">