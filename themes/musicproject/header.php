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
    <div class="youtube-video w-full h-full max-w-[500px] max-h-[310px] flex flex-col fixed z-[-1] bottom-0 right-0">
        <div class="close-icon icon cursor-pointer close-video-js hidden p-2">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve">
                <path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4  L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1  c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1  c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z" />
            </svg>
        </div>

        <div id="player">
        </div>
    </div>

    <div class="block-overlay hidden fixed left-0 right-0 top-0 bottom-0 z-[1000] bg-slate-500/[.7]">
        <div class="flex justify-center items-center h-full">
            <div class="unblock-js flex items-center rounded-[20px] bg-amber-50 p-3">
                <div class="text-lg cursor-pointer">
                    Unblock display
                </div>

                <div class="icon sm:w-[19px] sm:h-[19px] mx-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" height="800px" width="800px" version="1.1" id="Layer_1" viewBox="0 0 330 330" xml:space="preserve">
                        <g id="XMLID_509_">
                            <path id="XMLID_510_" d="M65,330h200c8.284,0,15-6.716,15-15V145c0-8.284-6.716-15-15-15h-15V85c0-46.869-38.131-85-85-85   S80,38.131,80,85v45H65c-8.284,0-15,6.716-15,15v170C50,323.284,56.716,330,65,330z M180,234.986V255c0,8.284-6.716,15-15,15   s-15-6.716-15-15v-20.014c-6.068-4.565-10-11.824-10-19.986c0-13.785,11.215-25,25-25s25,11.215,25,25   C190,223.162,186.068,230.421,180,234.986z M110,85c0-30.327,24.673-55,55-55s55,24.673,55,55v45H110V85z" />
                        </g>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center fixed inset-0 bg-gray-800 bg-opacity-50 z-50 spinner-js hidden">
        <div class="spinner !w-[50px] !h-[50px]"></div>
    </div>

    <div class="search-overlay">
        <div class="search-overlay__top flex items-center justify-between">
            <i class="fa fa-search cursor-pointer search-overlay__icon ml-[15px] !text-[25px]" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term">
            <div class="icon cursor-pointer mb-[5px] search-overlay__close bg-transparent">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" style="enable-background:new 0 0 512 512;" version="1.1" viewBox="0 0 512 512" width="512px" xml:space="preserve">
                    <path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4  L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1  c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1  c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z" />
                </svg>
            </div>
        </div>

        <div class="content-container !max-w-7xl">
            <div id="search-overlay__results"></div>
        </div>
    </div>

    <div class="wrapper flex flex-col min-h-container-height">
        <div id="content-container" class="grow">
            <header class="relative sticky bg-white top-0 z-[200] px-5 py-6 flex items-center justify-between lg:justify-normal lg:items-center justify-end">
                <div class="absolute bottom-[5px] left-[20px]">
                    <div class="block-js flex items-center pointer cursor-pointer">
                        <span>
                            block display
                        </span>

                        <div class="icon sm:w-[19px] sm:h-[19px] mx-2">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000" version="1.1" id="Capa_1" width="800px" height="800px" viewBox="0 0 92.179 92.18" xml:space="preserve">
                                <g>
                                    <path d="M73.437,36.54v-9.192C73.437,12.268,61.169,0,46.09,0S18.744,12.268,18.744,27.348h11.355   c0-8.818,7.173-15.992,15.991-15.992c8.817,0,15.991,7.174,15.991,15.992v9.192H9.884v55.64h72.411V36.54H73.437z M50.609,71.115   V83.33h-9.037V71.115c-2.102-1.441-3.482-3.858-3.482-6.6c0-4.418,3.582-8,8-8s8,3.582,8,8   C54.09,67.257,52.71,69.674,50.609,71.115z" />
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="mobile-menu-wrapper accordion sm:w-1/3 block lg:hidden">
                    <div class="flex items-center">
                        <div class="burger-icon accordion-button" id="burgerIcon">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>

                        <?php if (is_user_logged_in()) : ?>
                            <div class="ml-4">
                                <a href="<?php echo site_url('/user-profile?user_id=' . get_current_user_id()) ?>" />
                                <?php echo get_avatar(get_current_user_id()); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="accordion-content invisible absolute right-0 left-0 top-[105px] z-[6]">
                        <?php wp_nav_menu([
                            'name' => 'menu',
                            'menu_class' => 'menu mobile-menu',
                            'mobile' => true,
                            'add_li_class' => 'accordion'
                        ]) ?>

                        <?php if (is_user_logged_in()) : ?>
                            <ul class="ml-4 menu !block">
                                <li class="menu-item">
                                    <a href="<?php echo wp_logout_url();  ?>" class="normal-link"> Log Out </a>
                                </li>
                            </ul>
                        <?php else : ?>
                            <ul class="ml-4 menu !block">
                                <li class="menu-item">
                                    <a href="<?php echo site_url('/login'); ?>" class="normal-link">Login</a>
                                </li>

                                <li class="menu-item">
                                    <a href="<?php echo site_url('/register'); ?>" class="normal-link">Sign Up</a>
                                </li>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sm:w-1/3 my-0 lg:mr-auto ml-0">
                    <div class="logo lg:text-left text-center">
                        <a href="<?php echo site_url('/') ?>">
                            <img class="w-[170px] lg:w-[200px]" src="<?php echo get_template_directory_uri() . '/images/logo-cut.png' ?>" />
                        </a>
                    </div>
                </div>

                <div class="desctop-menu hidden lg:block">
                    <div class="flex items-center">
                        <div class="js-search-trigger text-[25px] text-black site-header__search-trigger mr-4">
                            <i class="fa fa-search cursor-pointer" aria-hidden="true"></i>
                        </div>

                        <?php wp_nav_menu(['name' => 'menu']) ?>
                    </div>
                </div>

                <div class="gap-2.5 accordion relative hidden lg:block">
                    <div>
                        <?php if (is_user_logged_in()) : ?>
                            <div class="accordion-button cursor-pointer ml-4">
                                <?php echo get_avatar(get_current_user_id()); ?>
                            </div>

                            <ul class="menu z-[100] !inline-block accordion-content absolute top-[40px] right-0 left-[-50px] invisible">
                                <li>
                                    <a href="<?php echo site_url('/user-profile?user_id=' . get_current_user_id()) ?>">
                                        Profile
                                    </a>
                                </li>

                                <li>
                                    <a href="<?php echo wp_logout_url();  ?>" class="normal-link"> Log Out </a>
                                </li>
                            </ul>
                        <?php else : ?>
                            <div class="ml-4 flex items-center">
                                <a href="<?php echo site_url('/login'); ?>" class="normal-link text-black">Login</a>
                                <div class="h-[25px] w-[1px] mr-[4px] ml-[5px] bg-black"></div>
                                <a href="<?php echo site_url('/register'); ?>" class="normal-link text-black">Sign Up</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sm:w-1/3 lg:hidden flex justify-end">
                    <div class="js-search-trigger text-[25px] text-black site-header__search-trigger">
                        <i class="fa fa-search cursor-pointer" aria-hidden="true"></i>
                    </div>
                </div>
            </header>