<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="noindex, nofollow" />
        <link rel="stylesheet" href="<?php echo asset('libs/bootstrap/css/bootstrap.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('/libs/swiper/swiper-bundle.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/font.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/style.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/home.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/news.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/login.css') ?>" />
        
        
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        ></script>
        <script src="<?php echo asset('js/script.js') ?>"></script>
        <script src="<?php echo asset('/libs/bootstrap/js/bootstrap.min.js') ?>"></script>
        <script src="<?php echo asset('/libs/swiper/swiper-bundle.min.js') ?>"></script>

        <title>Vua Dược</title>
    </head>
    <body>
        <header id="header" class="header-1">
            <div class="container">
                <div class="main-header align-items-center justify-content-between d-xl-flex d-none">
                    <div class="logo">
                        <a href="/" title="Vua Dược">
                            <img src="<?= asset('/images/logo.png') ?>" alt="logo" />
                        </a>
                    </div>
                    <div class="main-menu d-flex align-items-center">
                        <ul class="d-flex align-items-center m-0 p-0">
                            <li><a class="active" href="/">Trang chủ</a></li>
                            <li><a href="/products">Sản phẩm</a></li>
                            <li><a href="<?php echo url('post.listing', ['news',0]) ?>">Tin tức</a></li>
                            <li><a href="/loyal-client">Khách hàng thân thiết</a></li>
                            <li><a href="/huong-dan-dat-hang">Hướng dẫn đặt hàng</a></li>
                        </ul>
                    </div>
                    <?php 
                    if(checkLoginFe()){
                        ?>
                        <div class="user-btn d-flex align-items-center justify-content-center gap-2 rounded-pill">
                            <div class="avatar">
                                <img width="37" height="37" class="rounded-circle" src="<?= asset('/images/avatar.png') ?>" alt="avatar">
                            </div>
                            <div class="name"><?php echo isset($_SESSION["userNameFe"]) ? $_SESSION["userNameFe"] : ""; ?></div>
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.6 9.60001L12 14.4L6.40002 9.60001" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </div>
                        <?php
                    }else{
                        ?>
                        <div class="btn-right">
                            <button class="btn-custom"><a href="/login">Đăng nhập</a></button>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="main-header-mb d-flex d-xl-none flex-column">
                    <div class="justify-content-between align-items-center d-flex d-xl-none">
                        <button id="toggle-menu">
                            <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M27 9.1416L5 9.1416" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M27 16.1958L5 16.1958" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                <path d="M27 23.2505L5 23.2505" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                            </svg>
                        </button>
                        <div class="logo">
                            <img src="<?= asset('/images/logo.png') ?>" alt="logo" />
                        </div>
                        <div class="" style="width: 32px"></div>
                    </div>
                </div>
            </div>
            <div class="menu-mb">
                <div class="sidebar-left d-flex flex-column justify-content-between d-xl-none">
                    <div class="sidebar-top">
                        <div class="d-flex align-items-center justify-content-between py-4 px-3">
                            <div class="logo">
                                <img height="46" src="<?= asset('/images/logo.png') ?>" alt="logo" />
                            </div>
                            <button id="close-sidebar">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 17L17 1" stroke="#1C274C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M17 17L1 1" stroke="#1C274C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                        <div class="user-cta py-4 px-3">
                            <?php
                            if(checkLoginFe()){
                                ?>
                                <div class="user-btn d-flex align-items-center justify-content-center gap-2 rounded-pill">
                                    <div class="avatar">
                                        <img width="37" height="37" class="rounded-circle" src="<?= asset('/images/avatar.png') ?>" alt="avatar">
                                    </div>
                                    <div class="name"><?php echo isset($_SESSION["userNameFe"]) ? $_SESSION["userNameFe"] : ""; ?></div>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.6 9.60001L12 14.4L6.40002 9.60001" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                                <?php
                            }else{?>
                                <div class="desc mb-2">Đăng nhập để hưởng đặc quyền cho thành viên</div>
                                <div class="cta d-flex gap-2">
                                    <button>
                                        <a href="/login">Đăng nhập</a>
                                    </button>
                                    <button>
                                        <a href="/?1#formdangky">Đăng ký</a>
                                    </button>
                                </div>
                            <?php 
                            }?>
                        </div>
                        <div class="sidebar-menu py-4 px-3">
                            <ul class="d-flex flex-column gap-3">
                                <li><a href="/">Trang chủ</a></li>
                                <li><a href="/products">Sản phẩm</a></li>
                                <li><a href="<?php echo url('post.listing', ['news',82]) ?>">Tin tức</a></li>
                                <li><a href="/loyal-client">Khách hàng thân thiết</a></li>
                                <li><a href="/huong-dan-dat-hang">Hướng dẫn đặt hàng</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="sidebar-bottom py-4 px-3">
                        <button>
                            <a href="tel:0878929789">Tư vấn: 0342.342.366 (Miễn phí) </a>
                        </button>
                    </div>
                </div>
                <div id="sidebar-overlay" class="sidebar-overlay d-flex d-xl-none"></div>
            </div>
        </header>