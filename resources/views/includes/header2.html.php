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
        <link rel="stylesheet" href="<?php echo asset('css/thuc-pham-chuc-nang.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/cart.css') ?>" />

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
        <header class="header-2">
            <div class="container">
                <div class="main-header-2 align-items-center justify-content-between ga-3 d-xl-flex d-none">
                    <div class="logo">
                        <a href="/">
                            <img src="<?= asset('/images/logo-4.png') ?>" alt="logo">
                        </a>
                    </div>
                    <div class="main-search-pc">
                        <div class="input-group rounded-pill">
                            <span class="input-group-text" id="main-search-pc">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.6" cy="11.6" r="7.6" stroke="#8A909F" stroke-width="1.5"></circle>
                                    <path d="M17.2 17.2L20 20" stroke="#8A909F" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </span>
                            <input type="text" class="form-control" placeholder="Bạn đang muốn tìm sản phẩm gì?" aria-label="basic-search" aria-describedby="basic-search">
                        </div>
                    </div>
                    <div class="cart">
                        <button type="button" class="position-relative">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16.8 33.6C18.1255 33.6 19.2 34.6745 19.2 36C19.2 37.3255 18.1255 38.4 16.8 38.4C15.4745 38.4 14.4 37.3255 14.4 36C14.4 34.6745 15.4745 33.6 16.8 33.6Z" stroke="white" stroke-width="2"></path>
                                <path d="M31.2 33.6001C32.5255 33.6001 33.6 34.6746 33.6 36.0001C33.6 37.3256 32.5255 38.4001 31.2 38.4001C29.8746 38.4001 28.8 37.3256 28.8 36.0001C28.8 34.6746 29.8746 33.6001 31.2 33.6001Z" stroke="white" stroke-width="2"></path>
                                <path d="M8.41793 9.74694L8.74961 8.80355L8.74961 8.80355L8.41793 9.74694ZM8.33168 8.65661C7.81066 8.47343 7.23979 8.74731 7.05661 9.26833C6.87343 9.78935 7.1473 10.3602 7.66832 10.5434L8.33168 8.65661ZM12.1373 11.7168L12.963 11.1526L12.963 11.1526L12.1373 11.7168ZM14.2203 28.1379L13.4947 28.8259L13.4947 28.8259L14.2203 28.1379ZM37.8524 20.6124L38.8319 20.8143L38.8331 20.8081L37.8524 20.6124ZM37.0528 24.492L38.0322 24.6938L38.0322 24.6938L37.0528 24.492ZM37.9752 15.5153L37.1826 16.1251L37.1826 16.1251L37.9752 15.5153ZM35.4138 28.8806L34.7821 28.1054L34.7821 28.1054L35.4138 28.8806ZM13.7329 20.416V16.0614H11.7329V20.416H13.7329ZM8.74961 8.80355L8.33168 8.65661L7.66832 10.5434L8.08626 10.6903L8.74961 8.80355ZM22.3 30.6H30.7846V28.6H22.3V30.6ZM13.7329 16.0614C13.7329 14.9256 13.7343 13.994 13.6527 13.24C13.5686 12.4636 13.3886 11.7754 12.963 11.1526L11.3117 12.281C11.4817 12.5297 11.5994 12.8562 11.6643 13.4553C11.7316 14.0768 11.7329 14.8818 11.7329 16.0614H13.7329ZM8.08626 10.6903C9.14995 11.0643 9.86511 11.3175 10.3947 11.5773C10.8982 11.8244 11.1447 12.0365 11.3117 12.281L12.963 11.1526C12.5344 10.5255 11.9624 10.1188 11.2758 9.78184C10.6152 9.45774 9.76847 9.16176 8.74961 8.80355L8.08626 10.6903ZM11.7329 20.416C11.7329 22.7449 11.7553 24.4022 11.9711 25.6624C12.1993 26.9947 12.6508 27.9359 13.4947 28.8259L14.946 27.4499C14.4036 26.8778 14.1114 26.3112 13.9424 25.3248C13.7611 24.2662 13.7329 22.7936 13.7329 20.416H11.7329ZM22.3 28.6C20.0391 28.6 18.4494 28.5976 17.2471 28.4271C16.0792 28.2615 15.4249 27.955 14.946 27.4499L13.4947 28.8259C14.402 29.7829 15.5565 30.2074 16.9663 30.4073C18.3416 30.6024 20.0986 30.6 22.3 30.6V28.6ZM12.7329 15.592H32.1421V13.592H12.7329V15.592ZM36.873 20.4105L36.0734 24.2901L38.0322 24.6938L38.8318 20.8143L36.873 20.4105ZM32.1421 15.592C33.5086 15.592 34.727 15.5933 35.6922 15.7012C36.1723 15.7549 36.5429 15.8305 36.8117 15.925C37.0885 16.0223 37.1703 16.1091 37.1826 16.1251L38.7678 14.9056C38.4249 14.4599 37.937 14.2005 37.4747 14.0381C37.0045 13.8729 36.4667 13.7754 35.9144 13.7136C34.8145 13.5907 33.4716 13.592 32.1421 13.592V15.592ZM38.8331 20.8081C39.105 19.4458 39.3313 18.3233 39.3866 17.4283C39.4431 16.5115 39.3362 15.6444 38.7678 14.9056L37.1826 16.1251C37.3246 16.3096 37.4339 16.5998 37.3904 17.3051C37.3455 18.0322 37.1552 18.9966 36.8718 20.4167L38.8331 20.8081ZM30.7846 30.6C32.0083 30.6 33.0167 30.6018 33.8262 30.5027C34.663 30.4003 35.4033 30.1792 36.0455 29.6558L34.7821 28.1054C34.5415 28.3014 34.216 28.4401 33.5833 28.5175C32.9233 28.5983 32.0584 28.6 30.7846 28.6V30.6ZM36.0734 24.2901C35.8163 25.5377 35.64 26.3844 35.4276 27.0145C35.2241 27.6186 35.0226 27.9094 34.7821 28.1054L36.0455 29.6558C36.6877 29.1325 37.0537 28.4521 37.3229 27.6532C37.5833 26.8804 37.7852 25.8924 38.0322 24.6938L36.0734 24.2901Z" fill="white"></path>
                            </svg>
                            <span class="position-absolute position-absolute top-0 end-0 badge rounded-pill bg-warning text-dark"> 0</span>
                        </button>
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
                    }?>
                </div>
                <div class="main-header-mb d-flex d-xl-none flex-column">
                    <div class="justify-content-between align-items-center d-flex d-xl-none mb-3">
                        <button id="toggle-menu">
                            <svg width="32" height="33" viewBox="0 0 32 33" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M27 9.1416L5 9.1416" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M27 16.1958L5 16.1958" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path>
                                <path d="M27 23.2505L5 23.2505" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </button>
                        <div class="logo">
                            <a href="/">
                                <img src="<?= asset('/images/logo.png') ?>" alt="logo">
                            </a>
                        </div>
                        <div class="" style="width: 32px"></div>
                    </div>
                    <div class="main-search">
                        <div class="input-group">
                            <span class="input-group-text" id="main-search">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="11.6" cy="11.6" r="7.6" stroke="#8A909F" stroke-width="1.5"></circle>
                                    <path d="M17.2 17.2L20 20" stroke="#8A909F" stroke-width="1.5" stroke-linecap="round"></path>
                                </svg>
                            </span>
                            <input type="text" class="form-control" placeholder="Bạn đang muốn tìm sản phẩm gì?" aria-label="basic-search" aria-describedby="basic-search">
                        </div>
                    </div>
                </div>
            </div>
            <div class="menu-mb">
                <div class="sidebar-left d-flex flex-column justify-content-between d-xl-none">
                    <div class="sidebar-top">
                        <div class="d-flex align-items-center justify-content-between py-4 px-3">
                            <div class="logo">
                                <img height="46" src="<?= asset('/images/logo.png') ?>" alt="logo">
                            </div>
                            <button id="close-sidebar">
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 17L17 1" stroke="#1C274C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M17 17L1 1" stroke="#1C274C" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="user-cta py-4 px-3">
                            <div class="desc mb-2">Đăng nhập để hưởng đặc quyền cho thành viên</div>
                            <div class="cta d-flex gap-2">
                                <button>
                                    <a href="/login">Đăng nhập</a>
                                </button>
                                <button>
                                    <a href="/register">Đăng ký</a>
                                </button>
                            </div>
                        </div>
                        <div class="sidebar-menu py-4 px-3">
                            <ul class="d-flex flex-column gap-3">
                                <li><a href="">Trang chủ</a></li>
                                <li><a href="">Sản phẩm</a></li>
                                <li><a href="">Tin tức</a></li>
                                <li><a href="">Khách hàng thân thiết</a></li>
                                <li><a href="">Hướng dẫn đặt hàng</a></li>
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