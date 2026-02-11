<!DOCTYPE html>
<html lang="en">
    <head>
        <link rel="SHORTCUT ICON" href="<?= asset('/images/logo-shortcut.jpg') ?>" type="image/x-icon" />
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="robots" content="index,follow,noodp" />
		<meta name="robots" content="noarchive">
        <link rel="stylesheet" href="<?php echo asset('libs/bootstrap/css/bootstrap.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('/libs/swiper/swiper-bundle.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/font.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/style.css') ?>?221" />
        <link rel="stylesheet" href="<?php echo asset('css/home.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/news.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/login.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/thuc-pham-chuc-nang.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/cart.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/flash-sale.css') ?>" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

        <title><?php echo $titlePage; ?></title>
        <meta name="keywords" content="Nhà thuốc An Khang, Dược An Khang, Tiệm thuốc An Khang, nhà thuốc, An Khang" />
        <meta name="description" content="<?php echo $descPage; ?>" />

        <meta content="<?= asset('/images/og-img-header.jpg') ?>" property="og:image" itemprop="thumbnailUrl" />
        <meta property="og:title" itemprop="name" content="Vua Dược đồng hành cùng sự phát triển của Nhà Thuốc -  Vuaduoc.com" />
        <meta property="og:description" content="Vua dược chuyên bán lẻ thuốc, dược phẩm, thực phẩm chức năng, thiết bị y tế. Đồng thời cung cấp thông tin hữu ích về cách phòng ngừa, nhận biết các dấu hiệu mắc bệnh để đưa ra các giải pháp trị bệnh kịp thời." />
        
        <script
            src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous"
            referrerpolicy="no-referrer"
        ></script>
        <script src="<?php echo asset('js/script.js?112') ?>"></script>
        <script src="<?php echo asset('/libs/swiper/swiper-bundle.min.js') ?>"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
		<style>
			.user-btn {
				cursor: pointer;
			}
		</style>
    </head>
    <body>
        <header id="header" class="header-1">
            <div class="container">
                <div class="main-header align-items-center justify-content-between d-xl-flex d-none">
                    <div class="logo">
                        <a href="/" title="Vua dược - Thuốc tốt , Giá tốt , Đa dạng chủng loại , Tư vấn nhiệt tình, Đặt hàng nhanh chóng">
                            <img src="<?= asset('/images/logo.png') ?>" alt="logo" />
                        </a>
                    </div>
                    <div class="main-menu d-flex align-items-center">
                        <ul class="d-flex align-items-center m-0 p-0">
                            <li><a href="/order-list" style="font-size:20px;">Danh sách đơn hàng</a></li>
                            <!--<li><a href="/order-price-high">Danh sách sản phẩm nhập giá cao</a></li>-->
                        </ul>
                    </div>
                </div>
                <div class="main-header-mb d-flex d-xl-none flex-column mt-2 mb-2">
                    <div class="justify-content-between align-items-center d-flex d-xl-none">
                        <div class="logo">
                            <a href="/" title="Vua dược - Thuốc tốt , Giá tốt , Đa dạng chủng loại , Tư vấn nhiệt tình, Đặt hàng nhanh chóng">
                                <img src="<?= asset('/images/logo.png') ?>" alt="logo" />
                            </a>
                        </div>
                        <div class="main-menu d-flex align-items-center">
                            <ul class="d-flex align-items-center m-0 p-0">
                                <li><a href="/order-list" style="font-size:20px;">Danh sách đơn hàng</a></li>
                                <!--<li><a href="/order-price-high">Danh sách sản phẩm nhập giá cao</a></li>-->
                            </ul>
                        </div>
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
                                <div class="user-btn align-items-center justify-content-center gap-2 rounded-pill">
									<div id="dropdownMenuButton1" class="d-flex" data-bs-toggle="dropdown" aria-expanded="false">
										<div class="avatar">
											<img width="37" height="37" class="rounded-circle" src="<?= asset('/images/anh_bsy.png') ?>" alt="avatar">
										</div>
										<div class="name"><?php echo isset($_SESSION["userNameFe"]) ? $_SESSION["userNameFe"] : ""; ?></div>
										<svg id="dropdownIcon" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M17.6 9.60001L12 14.4L6.40002 9.60001" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
										</svg>
									</div>
									<ul id="dropdownMenu" class="dropdown-menu position-absolute bg-light mt-3 shadow" aria-labelledby="dropdownMenuButton1">
										<li>
                                            <a class="dropdown-item" href="/logout">
                                                <img src="<?= asset('/images/logout.png') ?>" alt="bo cong thuong" style="height: 20px;margin-top: -3px;" />
                                                Thoát
                                            </a>
                                        </li>
									</ul>
                                </div>
                                <?php
                            }else{?>
                                <div class="desc mb-2">Đăng nhập để hưởng đặc quyền cho thành viên</div>
                                <div class="cta d-flex gap-2">
                                    <button>
                                        <a href="/">Đăng nhập</a>
                                    </button>
                                </div>
                            <?php 
                            }?>
                        </div>
                        <div class="sidebar-menu py-4 px-3">
                            <ul class="d-flex flex-column gap-3">
                                <li><a href="/order-list">Danh sách đơn hàng</a></li>
                                <li><a href="/order-price-high">Danh sách sản phẩm nhập giá cao</a></li>
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