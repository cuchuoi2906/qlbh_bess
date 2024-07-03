<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="<?php echo asset('libs/bootstrap/css/bootstrap.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('/libs/swiper/swiper-bundle.min.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/font.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/style.css') ?>" />
        <link rel="stylesheet" href="<?php echo asset('css/home.css') ?>" />
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
        <header id="header">
            <div class="container">
                <div class="main-header align-items-center justify-content-between d-xl-flex d-none">
                    <div class="logo">
                        <img src="<?= asset('/images/logo.png') ?>" alt="logo" />
                    </div>
                    <div class="main-menu d-flex align-items-center">
                        <ul class="d-flex align-items-center m-0 p-0">
                            <li><a class="active" href="./home.html">Trang chủ</a></li>
                            <li><a href="/">Sản phẩm</a></li>
                            <li><a href="/">Tin tức</a></li>
                            <li><a href="/">Khách hàng thân thiết</a></li>
                            <li><a href="/">Hướng dẫn đặt hàng</a></li>
                        </ul>
                    </div>
                    <div class="btn-right">
                        <button class="btn-custom"><a href="/login">Đăng nhập</a></button>
                    </div>
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
        <div class="main-content">
            <div id="section-1" class="mt-0 mt-xl-3">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-7 mb-3 mb-xl-0">
                            <div class="swiper slide-banner">
                                <div class="swiper-wrapper">
                                    <div class="swiper-slide">
                                        <img src="<?= asset('/images/slide-banner-img.png') ?>" alt="" />
                                    </div>
                                    <div class="swiper-slide">
                                        <img src="<?= asset('/images/slide-banner-img.png') ?>" alt="" />
                                    </div>
                                </div>
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <form action="" class="form-order">
                                <div class="form-content">
                                    <h2 class="form-title">Liên hệ đặt hàng</h2>
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-search">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="11.6" cy="11.6" r="7.6" stroke="#8A909F" stroke-width="1.5" />
                                                <path d="M17.2 17.2L20 20" stroke="#8A909F" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Bạn đang tìm sản phẩm gì"
                                            aria-label="basic-search"
                                            aria-describedby="basic-search"
                                        />
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text" id="input-fullname">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="12" cy="7.2" r="3.2" stroke="#1C274C" stroke-width="1.5" />
                                                <path
                                                    d="M18.4 16.4C18.4 18.3882 18.4 20 12 20C5.59998 20 5.59998 18.3882 5.59998 16.4C5.59998 14.4118 8.46535 12.8 12 12.8C15.5346 12.8 18.4 14.4118 18.4 16.4Z"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Họ và tên*"
                                            aria-label="input-fullname"
                                            aria-describedby="input-fullname"
                                        />
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text" id="input-phone">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M11.2301 5.85294L11.7493 6.78328C12.2179 7.62287 12.0298 8.72426 11.2918 9.46224C11.2918 9.46224 11.2918 9.46224 11.2918 9.46225C11.2917 9.46236 10.3967 10.3575 12.0196 11.9804C13.6419 13.6026 14.537 12.709 14.5378 12.7082C14.5378 12.7082 14.5378 12.7082 14.5378 12.7082C15.2758 11.9703 16.3772 11.7822 17.2167 12.2507L18.1471 12.7699C19.4149 13.4775 19.5646 15.2554 18.4502 16.3698C17.7806 17.0394 16.9603 17.5604 16.0536 17.5948C14.527 17.6526 11.9346 17.2663 9.33419 14.6658C6.73373 12.0654 6.3474 9.47298 6.40527 7.94647C6.43964 7.03968 6.96067 6.21939 7.63027 5.54979C8.74463 4.43543 10.5226 4.58515 11.2301 5.85294Z"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                    stroke-linecap="round"
                                                />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Số điện thoại*"
                                            aria-label="input-phone"
                                            aria-describedby="input-phone"
                                        />
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text" id="input-phone">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M4 12.1631C4 10.3324 4 9.41702 4.41536 8.65819C4.83072 7.89937 5.58956 7.42841 7.10723 6.4865L8.70723 5.4935C10.3115 4.49783 11.1137 4 12 4C12.8863 4 13.6885 4.49783 15.2928 5.4935L16.8928 6.4865C18.4104 7.42841 19.1693 7.89937 19.5846 8.65819C20 9.41702 20 10.3324 20 12.1631V13.38C20 16.5007 20 18.061 19.0627 19.0305C18.1255 20 16.617 20 13.6 20H10.4C7.38301 20 5.87452 20 4.93726 19.0305C4 18.061 4 16.5007 4 13.38V12.1631Z"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                                <path d="M12 14.4L12 16.8" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </span>
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                                            <option selected>Bạn là...</option>
                                            <option value="1">Quầy thuốc</option>
                                            <option value="2">Nhà thuốc</option>
                                            <option value="3">Phòng khám</option>
                                            <option value="4">Bệnh viện</option>
                                            <option value="5">Công ty dược phẩm</option>
                                            <option value="6">Nha khoa</option>
                                            <option value="7">Thẩm mỹ viện</option>
                                            <option value="8">Trung tâm y tế</option>
                                            <option value="9">Bệnh nhân</option>
                                            <option value="10">Dược sĩ</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text" id="input-phone">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M20 20L4 20" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path
                                                    d="M19.2 20V7.2C19.2 5.69151 19.2 4.93726 18.7314 4.46863C18.2628 4 17.5085 4 16 4H14.4C12.8915 4 12.1373 4 11.6686 4.46863C11.2914 4.84586 11.2178 5.40817 11.2035 6.4"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                                <path
                                                    d="M14.4 20V9.59999C14.4 8.0915 14.4 7.33725 13.9314 6.86862C13.4628 6.39999 12.7085 6.39999 11.2 6.39999H8.00005C6.49155 6.39999 5.73731 6.39999 5.26868 6.86862C4.80005 7.33725 4.80005 8.0915 4.80005 9.59999V20"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                                <path d="M9.59998 20V17.6" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M7.19995 8.8H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M7.19995 11.2H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                                <path d="M7.19995 13.6H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round" />
                                            </svg>
                                        </span>
                                        <select class="form-select form-select-sm" aria-label=".form-select-sm example">
                                            <option selected>Tỉnh/Thành phố</option>
                                            <option value="1">Thành phố Hà Nội</option>
                                            <option value="2">Thành phố Hải Phòng</option>
                                            <option value="3">Thành phố Đà Nẵng</option>
                                            <option value="4">Thành phố Cần Thơ</option>
                                            <option value="5">Thành phố Hồ Chí Minh</option>
                                            <option value="6">An Giang</option>
                                            <option value="7">Bà Rịa – Vũng Tàu</option>
                                            <option value="8">Bắc Giang</option>
                                            <option value="9">Bắc Kạn</option>
                                            <option value="10">Bạc Liêu</option>
                                            <option value="11">Bắc Ninh</option>
                                            <option value="12">Bến Tre</option>
                                            <option value="13">Bình Định</option>
                                            <option value="14">Bình Dương</option>
                                            <option value="15">Bình Phước</option>
                                            <option value="16">Bình Thuận</option>
                                            <option value="17">Cà Mau</option>
                                            <option value="18">Cao Bằng</option>
                                            <option value="19">Đắk Lắk</option>
                                            <option value="20">Đắk Nông</option>
                                            <option value="21">Điện Biên</option>
                                            <option value="22">Đồng Nai</option>
                                            <option value="23">Đồng Tháp</option>
                                            <option value="24">Gia Lai</option>
                                            <option value="25">Hà Giang</option>
                                            <option value="26">Hà Nam</option>
                                            <option value="27">Hà Tĩnh</option>
                                            <option value="28">Hải Dương</option>
                                            <option value="29">Hậu Giang</option>
                                            <option value="30">Hòa Bình</option>
                                            <option value="31">Hưng Yên</option>
                                            <option value="32">Khánh Hòa</option>
                                            <option value="33">Kiên Giang</option>
                                            <option value="34">Kon Tum</option>
                                            <option value="35">Lai Châu</option>
                                            <option value="36">Lâm Đồng</option>
                                            <option value="37">Lạng Sơn</option>
                                            <option value="38">Lào Cai</option>
                                            <option value="39">Long An</option>
                                            <option value="40">Nam Định</option>
                                            <option value="41">Nghệ An</option>
                                            <option value="42">Ninh Bình</option>
                                            <option value="43">Ninh Thuận</option>
                                            <option value="44">Phú Thọ</option>
                                            <option value="45">Phú Yên</option>
                                            <option value="46">Quảng Bình</option>
                                            <option value="47">Quảng Nam</option>
                                            <option value="48">Quảng Ngãi</option>
                                            <option value="49">Quảng Ninh</option>
                                            <option value="50">Quảng Trị</option>
                                            <option value="51">Sóc Trăng</option>
                                            <option value="52">Sơn La</option>
                                            <option value="53">Tây Ninh</option>
                                            <option value="54">Thái Bình</option>
                                            <option value="55">Thái Nguyên</option>
                                            <option value="56">Thanh Hóa</option>
                                            <option value="57">Thừa Thiên Huế</option>
                                            <option value="58">Tiền Giang</option>
                                            <option value="59">Trà Vinh</option>
                                            <option value="60">Tuyên Quang</option>
                                            <option value="61">Vĩnh Long</option>
                                            <option value="62">Vĩnh Phúc</option>
                                            <option value="63">Yên Bái</option>
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text" id="input-address">
                                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M5.59998 10.5146C5.59998 6.9167 8.46535 4 12 4C15.5346 4 18.4 6.9167 18.4 10.5146C18.4 14.0844 16.3573 18.2499 13.1703 19.7396C12.4274 20.0868 11.5726 20.0868 10.8296 19.7396C7.64263 18.2499 5.59998 14.0844 5.59998 10.5146Z"
                                                    stroke="#1C274C"
                                                    stroke-width="1.5"
                                                />
                                                <circle cx="12" cy="10.4" r="2.4" stroke="#1C274C" stroke-width="1.5" />
                                            </svg>
                                        </span>
                                        <input
                                            type="text"
                                            class="form-control"
                                            placeholder="Địa chỉ cụ thể"
                                            aria-label="input-address"
                                            aria-describedby="input-address"
                                        />
                                    </div>
                                    <div class="d-grid">
                                        <button class="btn btn-primary" type="button">Liên hệ ngay</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div id="section-2">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-3 col-md-6">
                            <div class="utils">
                                <div class="util-icon">
                                    <img src="<?= asset('/images/icons/pills.svg') ?>" alt="pills" />
                                </div>
                                <div class="util-content">
                                    <h3 class="util-title">
                                        Đa dạng thuốc và <br />
                                        TPCN chính hãng
                                    </h3>
                                    <div class="utils-desc">Hơn 10.000 sản phẩm</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="utils">
                                <div class="util-icon">
                                    <img src="<?= asset('/images/icons/truck-fast.svg') ?>" alt="truck-fast" />
                                </div>
                                <div class="util-content">
                                    <h3 class="util-title">
                                        Đặt hàng và <br />
                                        giao hàng nhanh chóng
                                    </h3>
                                    <div class="utils-desc">Thao tác dễ dàng, giao siêu tốc 4 giờ</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="utils">
                                <div class="util-icon">
                                    <img src="<?= asset('/images/icons/medal-star.svg') ?>" alt="medal-star" />
                                </div>
                                <div class="util-content">
                                    <h3 class="util-title">
                                        Chính sách dành <br />
                                        cho thành viên
                                    </h3>
                                    <div class="utils-desc">Tích lũy lên tới 1%</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="utils">
                                <div class="util-icon">
                                    <img src="<?= asset('/images/icons/headphones_round.svg') ?>" alt="headphones_round" />
                                </div>
                                <div class="util-content">
                                    <h3 class="util-title">
                                        Đội ngũ chăm sóc <br />
                                        chuyên nghiệp
                                    </h3>
                                    <div class="utils-desc">Chăm sóc khách hàng 1:1</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="section-3">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-6 mb-3 mb-xl-0">
                            <div class="row mb-xl-0 mb-4">
                                <div class="col-6">
                                    <div class="pt-4 d-flex flex-column gap-4">
                                        <img src="<?= asset('/images/section-3-1.png') ?>" alt="section-3-1.png" />
                                        <img src="<?= asset('/images/section-3-4.png') ?>" alt="section-3-4.png" />
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column gap-4">
                                        <img src="<?= asset('/images/section-3-2.png') ?>" alt="section-3-2.png" />
                                        <img src="<?= asset('/images/section-3-3.png') ?>" alt="section-3-3.png" />
                                    </div>
                                </div>
                            </div>
                            <div class="video d-xl-none d-block">
                                <!-- <img src="./images/section-3-5.png" alt="section-3-5.png" /> -->
                                <video controls>
                                    <source src="./video/Intro.mp4" />
                                </video>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <h2 class="title-reason">LÝ DO VUADUOC.COM</h2>
                            <h3 class="title-reason">Là Sự Lựa Chọn Hàng Đầu Của Bạn</h3>
                            <div class="reason-desc">
                                Với sứ mệnh "MANG ĐẾN SỰ AN TÂM CHO KHÁCH HÀNG" – An tâm về chất lượng – An tâm về giá cả - An tâm về dịch vụ. Dược An
                                Khang tôn trọng khách hàng như những người thân, không ngừng nỗ lực để mang lại giá trị vượt trội, tư vấn tận tâm,
                                giúp khách hàng tìm ra sự lựa chọn hoàn hảo.
                            </div>
                            <button class="btn-custom">
                                <a href="">Xem thêm</a>
                            </button>
                            <div class="video d-none d-xl-block">
                                <!-- <img src="./images/section-3-5.png" alt="section-3-5.png" />  -->
                                <video controls>
                                    <source src="./video/Intro.mp4" />
                                </video>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="section-4">
                <div class="container">
                    <h3 class="section-4-title">Ưu Đãi Từ Các Hãng</h3>
                    <div class="swiper section-4-slide-1 mb-4">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-1.png') ?>" alt="" />
                            </div>
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-2.png') ?>" alt="" />
                            </div>
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-1.png') ?>" alt="" />
                            </div>
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-2.png') ?>" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="swiper section-4-slide-2">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-3.png') ?>" alt="" />
                            </div>
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-4.png') ?>" alt="" />
                            </div>
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-3.png') ?>" alt="" />
                            </div>
                            <div class="swiper-slide">
                                <img src="<?= asset('/images/section-4-4.png') ?>" alt="" />
                            </div>
                        </div>
                    </div>
                    <div class="section-4-desc text-center">Đăng nhập để xem thêm 10.000 sản phẩm của hơn 500 nhà cung cấp trên vuaduoc.com</div>
                    <div class="d-flex justify-content-center">
                        <button class="btn-custom">
                            <a href="">Khám phá ngay</a>
                        </button>
                    </div>
                </div>
            </div>
            <div id="section-5">
                <div class="container">
                    <div class="divider">
                        <div class="icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                                <path
                                    d="M27.7927 12.2074C27.7927 12.2074 27.2158 15.2001 23.8743 18.5416C20.5328 21.8831 17.5406 22.4594 17.5406 22.4594M31.2101 25.8768C28.379 28.7078 23.789 28.7078 20.958 25.8768L14.1233 19.0421C11.2922 16.2111 11.2922 11.6211 14.1233 8.79002C16.9543 5.95899 21.5443 5.95899 24.3753 8.79002L31.2101 15.6247C34.0411 18.4558 34.0411 23.0458 31.2101 25.8768Z"
                                    stroke="#018279"
                                    stroke-width="2"
                                />
                                <path d="M23.3334 12.6667L21.3334 10.6667" stroke="#018279" stroke-width="2" stroke-linecap="round" />
                                <path
                                    d="M12.9736 17.5129C9.36846 18.2897 6.66663 21.4961 6.66663 25.3334C6.66663 29.7517 10.2483 33.3334 14.6666 33.3334C18.5214 33.3334 21.7394 30.607 22.4976 26.9774"
                                    stroke="#018279"
                                    stroke-width="2"
                                />
                            </svg>
                        </div>
                    </div>
                    <div class="d-flex justify-content-center flex-column">
                        <h3 class="title-tut">3 BƯỚC Để Tạo Tài Khoản Tại</h3>
                        <h2 class="title-tut">VuaDuoc.com</h2>
                    </div>

                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link active"
                                id="pills-1-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-1"
                                type="button"
                                role="tab"
                                aria-controls="pills-1"
                                aria-selected="true"
                            >
                                Tạo tài khoản
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link"
                                id="pills-2-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-2"
                                type="button"
                                role="tab"
                                aria-controls="pills-2"
                                aria-selected="false"
                            >
                                Kích hoạt tài khoản
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link"
                                id="pills-3-tab"
                                data-bs-toggle="pill"
                                data-bs-target="#pills-3"
                                type="button"
                                role="tab"
                                aria-controls="pills-3"
                                aria-selected="false"
                            >
                                Nhận ưu đãi đơn đầu tiên
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-1" role="tabpanel" aria-labelledby="pills-1-tab">
                            <div class="row">
                                <div class="col-xl-6 mb-3 mb-xl-0">
                                    <div class="content">
                                        <div class="text mb-3">
                                            Điền đầy đủ thông tin doanh nghiệp và cung cấp<br />
                                            các loại hồ sơ sau:
                                        </div>
                                        <ul class="list-unstyled m-0 p-0">
                                            <li class="fw-light">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM11.2243 5.57574C11.4586 5.81005 11.4586 6.18995 11.2243 6.42426L7.22426 10.4243C6.98995 10.6586 6.61005 10.6586 6.37574 10.4243L4.77574 8.82426C4.54142 8.58995 4.54142 8.21005 4.77574 7.97574C5.01005 7.74142 5.38995 7.74142 5.62426 7.97574L6.8 9.15147L8.58787 7.3636L10.3757 5.57574C10.6101 5.34142 10.9899 5.34142 11.2243 5.57574Z"
                                                        fill="#018279"
                                                    />
                                                </svg>
                                                Mã số thuế
                                            </li>
                                            <li class="fw-light">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM11.2243 5.57574C11.4586 5.81005 11.4586 6.18995 11.2243 6.42426L7.22426 10.4243C6.98995 10.6586 6.61005 10.6586 6.37574 10.4243L4.77574 8.82426C4.54142 8.58995 4.54142 8.21005 4.77574 7.97574C5.01005 7.74142 5.38995 7.74142 5.62426 7.97574L6.8 9.15147L8.58787 7.3636L10.3757 5.57574C10.6101 5.34142 10.9899 5.34142 11.2243 5.57574Z"
                                                        fill="#018279"
                                                    />
                                                </svg>
                                                Giấy phép đăng ký kinh doanh
                                            </li>
                                            <li class="fw-light">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM11.2243 5.57574C11.4586 5.81005 11.4586 6.18995 11.2243 6.42426L7.22426 10.4243C6.98995 10.6586 6.61005 10.6586 6.37574 10.4243L4.77574 8.82426C4.54142 8.58995 4.54142 8.21005 4.77574 7.97574C5.01005 7.74142 5.38995 7.74142 5.62426 7.97574L6.8 9.15147L8.58787 7.3636L10.3757 5.57574C10.6101 5.34142 10.9899 5.34142 11.2243 5.57574Z"
                                                        fill="#018279"
                                                    />
                                                </svg>
                                                Giấy chứng nhận đạt chuẩn GPP
                                            </li>
                                            <li class="fw-light">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M16 8C16 12.4183 12.4183 16 8 16C3.58172 16 0 12.4183 0 8C0 3.58172 3.58172 0 8 0C12.4183 0 16 3.58172 16 8ZM11.2243 5.57574C11.4586 5.81005 11.4586 6.18995 11.2243 6.42426L7.22426 10.4243C6.98995 10.6586 6.61005 10.6586 6.37574 10.4243L4.77574 8.82426C4.54142 8.58995 4.54142 8.21005 4.77574 7.97574C5.01005 7.74142 5.38995 7.74142 5.62426 7.97574L6.8 9.15147L8.58787 7.3636L10.3757 5.57574C10.6101 5.34142 10.9899 5.34142 11.2243 5.57574Z"
                                                        fill="#018279"
                                                    />
                                                </svg>
                                                Giấy phép đăng ký kinh doanh dược
                                            </li>
                                        </ul>
                                        <div class="text my-3">
                                            Đăng ký ngay để xem thêm hơn 10.000 sản phẩm và nhận ưu đãi <br />dành riêng cho bạn!
                                        </div>
                                        <button class="btn-custom">
                                            <a href=""
                                                >Đăng ký miễn phí ngay
                                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M17.5677 9.03436C17.8801 8.72194 18.3867 8.72194 18.6991 9.03436L25.0991 15.4344C25.4115 15.7468 25.4115 16.2533 25.0991 16.5657L18.6991 22.9657C18.3867 23.2782 17.8801 23.2782 17.5677 22.9657C17.2553 22.6533 17.2553 22.1468 17.5677 21.8344L22.602 16.8H7.46675C7.02492 16.8 6.66675 16.4419 6.66675 16C6.66675 15.5582 7.02492 15.2 7.46675 15.2H22.602L17.5677 10.1657C17.2553 9.85331 17.2553 9.34678 17.5677 9.03436Z"
                                                        fill="white"
                                                    />
                                                </svg>
                                            </a>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <img src="<?= asset('/images/section-4-4.png') ?>" alt="section-5-1" />
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-2" role="tabpanel" aria-labelledby="pills-2-tab">
                            <div class="row">
                                <div class="col-xl-6 mb-3 mb-xl-0">
                                    <div class="content">
                                        <div class="text mb-3">
                                            Sau khi đăng ký tài khoản, <br />Chuyên viên CSKH của Vua dược sẽ liên hệ trong vòng 24 giờ để xác nhận
                                            thông tin đã cung cấp và kích hoạt tài khoản. <br /><br />Nếu bạn có thắc mắc, vui lòng liên hệ hotline:
                                            0342.342.366 để được hỗ trợ trực tiếp.
                                        </div>
                                        <button class="btn-custom">
                                            <a href=""
                                                >Đăng ký miễn phí ngay
                                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M17.5677 9.03436C17.8801 8.72194 18.3867 8.72194 18.6991 9.03436L25.0991 15.4344C25.4115 15.7468 25.4115 16.2533 25.0991 16.5657L18.6991 22.9657C18.3867 23.2782 17.8801 23.2782 17.5677 22.9657C17.2553 22.6533 17.2553 22.1468 17.5677 21.8344L22.602 16.8H7.46675C7.02492 16.8 6.66675 16.4419 6.66675 16C6.66675 15.5582 7.02492 15.2 7.46675 15.2H22.602L17.5677 10.1657C17.2553 9.85331 17.2553 9.34678 17.5677 9.03436Z"
                                                        fill="white"
                                                    />
                                                </svg>
                                            </a>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <img src="<?= asset('/images/section-5-1.png') ?>" alt="section-5-1" />
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-3" role="tabpanel" aria-labelledby="pills-3-tab">
                            <div class="row">
                                <div class="col-xl-6 mb-3 mb-xl-0">
                                    <div class="content">
                                        <div class="text my-3">
                                            Đặt hàng ngay và nhận voucher cho đơn hàng đầu tiên, giao hàng miễn phí trên toàn quốc. <br /><br />
                                            Nếu bạn có thắc mắc, vui lòng liên hệ hotline: 0342.342.366 để được hỗ trợ trực tiếp.
                                        </div>
                                        <button class="btn-custom">
                                            <a href=""
                                                >Đăng ký miễn phí ngay
                                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M17.5677 9.03436C17.8801 8.72194 18.3867 8.72194 18.6991 9.03436L25.0991 15.4344C25.4115 15.7468 25.4115 16.2533 25.0991 16.5657L18.6991 22.9657C18.3867 23.2782 17.8801 23.2782 17.5677 22.9657C17.2553 22.6533 17.2553 22.1468 17.5677 21.8344L22.602 16.8H7.46675C7.02492 16.8 6.66675 16.4419 6.66675 16C6.66675 15.5582 7.02492 15.2 7.46675 15.2H22.602L17.5677 10.1657C17.2553 9.85331 17.2553 9.34678 17.5677 9.03436Z"
                                                        fill="white"
                                                    />
                                                </svg>
                                            </a>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <img src="<?= asset('/images/section-5-1.png') ?>" alt="section-5-1" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="section-6">
                <div class="container">
                    <div class="d-flex">
                        <div class="content">
                            <h3 class="title-partner">Các Đối Tác Của</h3>
                            <h2 class="title-partner">VuaDuoc.com</h2>
                            <div class="partner-desc">
                                Với hơn <strong>6 năm</strong> hoạt động,<br />
                                Vuaduoc.com đã hợp tác với <br />
                                hơn <strong>50+</strong> đối tác trong và ngoài nước
                            </div>
                        </div>
                        <div class="img">
                            <img src="<?= asset('/images/section-6-1.png') ?>" alt="section-6-1.png" />
                        </div>
                    </div>
                </div>
            </div>
            <div id="section-7">
                <div class="container">
                    <h3 class="title-feedback">Đánh Giá Của Khách Hàng Về</h3>
                    <h2 class="title-feedback">VuaDuoc.com</h2>
                    <div class="swiper slide-feedback mt-4">
                        <div class="swiper-wrapper">
                            <!--DG1 -->
                            <div class="swiper-slide">
                                <div class="comment-item">
                                    <h4 class="title-quote">Giá cả phải chăng</h4>
                                    <div class="quote-desc">
                                        Giá thuốc trên Vuaduoc.com đặc biệt phải chăng so với các nhà thuốc trực tuyến khác. Tôi đã tiết kiệm được một
                                        khoản đáng kể cho nhà thuốc của mình, điều này rất quan trọng đối với ngân sách hàng tháng của tôi.
                                    </div>
                                    <div class="d-flex gap-4">
                                        <div class="avt">
                                            <img src="<?= asset('/images/section-6-1.png') ?>" alt="section-7-1.png" />
                                        </div>
                                        <div class="info d-flex flex-column">
                                            <div class="name">Chị Phạm Thị Vân Anh</div>
                                            <div class="branch">Nhà thuốc Vân Anh - Hải Dương</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--DG2 -->
                            <div class="swiper-slide">
                                <div class="comment-item">
                                    <h4 class="title-quote">Dịch vụ tuyệt vời</h4>
                                    <div class="quote-desc">
                                        Tôi rất ấn tượng với Vua Dược. Họ không chỉ cung cấp mức giá tuyệt vời cho thuốc mà dịch vụ của họ còn hàng
                                        đầu. Quá trình đặt hàng diễn ra suôn sẻ và thuốc của tôi được giao nhanh chóng.
                                    </div>
                                    <div class="d-flex gap-4">
                                        <div class="avt">
                                            <img src="<?= asset('/images/section-7-2.png') ?>" alt="section-7-2.png" />
                                        </div>
                                        <div class="info d-flex flex-column">
                                            <div class="name">Anh Nguyễn Hoàng</div>
                                            <div class="branch">Nhà thuốc Hải Nam - Hà Nội</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--DG3 -->
                            <div class="swiper-slide">
                                <div class="comment-item">
                                    <h4 class="title-quote">Sản phẩm phong phú</h4>
                                    <div class="quote-desc">
                                        Từ ngày biết đến website Vua Dược tôi không còn phải mất công đến chợ sỉ để nhặt hàng nữa. Sản phẩm ở đây rất
                                        đa dạng, đáp ứng được hầu hết các nhu cầu của quầy thuốc.
                                    </div>
                                    <div class="d-flex gap-4">
                                        <div class="avt">
                                            <img src="<?= asset('/images/section-7-3.png') ?>" alt="section-7-3.png" />
                                        </div>
                                        <div class="info d-flex flex-column">
                                            <div class="name">Chị Tôn Huỳnh Cát Tiên</div>
                                            <div class="branch">Nhà thuốc Ngọc Dung - Đà Nẵng</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--DG4 -->
                            <div class="swiper-slide">
                                <div class="comment-item">
                                    <h4 class="title-quote">Giao hàng siêu tốc</h4>
                                    <div class="quote-desc">
                                        Đội ngũ chăm sóc khách hàng hỗ trợ rất nhiệt tình trong việc đóng gói sản phẩm cẩn thận, giao hàng nhanh chóng
                                        với chi phí tiết kiệm nhất.
                                    </div>
                                    <div class="d-flex gap-4">
                                        <div class="avt">
                                            <img src="<?= asset('/images/section-7-4.png') ?>" alt="section-7-4.png" />
                                        </div>
                                        <div class="info d-flex flex-column">
                                            <div class="name">Anh Nguyễn Thành Đạt</div>
                                            <div class="branch">Nhà thuốc Hải Yến - Thanh Hóa</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--DG5 -->
                            <div class="swiper-slide">
                                <div class="comment-item">
                                    <h4 class="title-quote">Đặt hàng dễ dàng</h4>
                                    <div class="quote-desc">
                                        Thao tác tìm kiếm sản phẩm, đặt hàng ở website Vua Dược rất đơn giản, hiệu quả và giá hợp lý. Muốn mua gì cũng
                                        có , sản phẩm ở đây rất đa dạng và nhiều chương trình khuyến mãi hấp dẫn.
                                    </div>
                                    <div class="d-flex gap-4">
                                        <div class="avt">
                                            <img src="<?= asset('/images/section-7-5.png') ?>" alt="section-7-5.png" />
                                        </div>
                                        <div class="info d-flex flex-column">
                                            <div class="name">Chị Nguyễn Vũ Thảo Quyên</div>
                                            <div class="branch">Nhà thuốc Phương Thảo - Đồng Tháp</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pagination">
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-button-next"></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if(sizeof($postAll) > 0){
            ?>
                <div id="section-8">
                    <div class="container">
                        <div class="d-flex justify-content-between mb-4 mb-md-3">
                            <h3 class="title-news">Tin Tức</h3>
                            <button class="btn-custom-outline d-none d-md-inline-block">
                                <a href="/"
                                    >Xem thêm
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M17.5677 9.03436C17.8801 8.72194 18.3867 8.72194 18.6991 9.03436L25.0991 15.4344C25.4115 15.7468 25.4115 16.2533 25.0991 16.5657L18.6991 22.9657C18.3867 23.2782 17.8801 23.2782 17.5677 22.9657C17.2553 22.6533 17.2553 22.1468 17.5677 21.8344L22.602 16.8H7.46675C7.02492 16.8 6.66675 16.4419 6.66675 16C6.66675 15.5582 7.02492 15.2 7.46675 15.2H22.602L17.5677 10.1657C17.2553 9.85331 17.2553 9.34678 17.5677 9.03436Z"
                                            fill="#018279"
                                        />
                                    </svg>
                                </a>
                            </button>
                        </div>
                        <div class="swiper slide-news">
                            <div class="swiper-wrapper">
                                <?php 
                                foreach($postAll as $items){
                                    var_dump($items);
                                ?>
                                    <div class="swiper-slide">
                                        <div class="news-item">
                                            <a href="/">
                                                <div class="thumb">
                                                    <img src="<?= asset('/images/section-8-1.png') ?>" alt="section-8-1.png" />
                                                </div>
                                                <h3 class="news-title">
                                                    <?php 
                                                    $items->items;
                                                    ?>
                                                </h3>
                                                <div class="view-more">
                                                    <img src="<?= asset('/images/icons/news-icon.svg') ?>" alt="" />
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                <?php 
                                }?>
                            </div>
                        </div>
                        <button class="btn-custom-outline d-md-none d-block w-100 mt-3">
                            <a href="/"
                                >Xem thêm
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M17.5677 9.03436C17.8801 8.72194 18.3867 8.72194 18.6991 9.03436L25.0991 15.4344C25.4115 15.7468 25.4115 16.2533 25.0991 16.5657L18.6991 22.9657C18.3867 23.2782 17.8801 23.2782 17.5677 22.9657C17.2553 22.6533 17.2553 22.1468 17.5677 21.8344L22.602 16.8H7.46675C7.02492 16.8 6.66675 16.4419 6.66675 16C6.66675 15.5582 7.02492 15.2 7.46675 15.2H22.602L17.5677 10.1657C17.2553 9.85331 17.2553 9.34678 17.5677 9.03436Z"
                                        fill="#018279"
                                    />
                                </svg>
                            </a>
                        </button>
                    </div>
                </div>
            <?php 
            }?>
        </div>
        <footer>
            <div class="ft-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-5">
                            <h2 class="ft-title">
                                CÔNG TY CỔ PHẦN DỊCH VỤ THƯƠNG MẠI <br />
                                VÀ CÔNG NGHỆ DAK (DƯỢC AN KHANG)
                            </h2>
                            <div class="ft-content mb-3">
                                <div class="ft-ct-item mb-3">
                                    <strong>Văn phòng:</strong><br />
                                    <span>Tầng 9, Tòa nhà Five Season, 47 Nguyễn Tuân, Thanh Xuân, Hà Nội</span>
                                </div>
                                <div class="ft-ct-item">
                                    <strong>Số giấy chứng nhận đăng ký kinh doanh: </strong>
                                    <span>0108253503</span>
                                </div>
                                <div class="ft-ct-item">
                                    <strong>Ngày cấp:</strong>
                                    <span>07/05/2018</span>
                                </div>
                                <div class="ft-ct-item mb-3">
                                    <strong>Nơi cấp:</strong>
                                    <span>Sở Kế hoạch và Đầu tư Thành phố Hà Nội</span>
                                </div>
                            </div>
                            <div class="ft-content">
                                <!-- <img src="./images/bct.png" alt="bo cong thuong" height="60" />  -->
                                <img src="<?= asset('/images/bct.png') ?>" alt="bo cong thuong" height="60" />
                            </div>
                        </div>
                        <div class="col-md-2">
                            <h2 class="ft-title">VỀ VUADUOC.COM</h2>
                            <div class="ft-content mb-3">
                                <ul>
                                    <li><a href="">Giới Thiệu Vua Dược</a></li>
                                    <li><a href="">Tin tức</a></li>
                                    <li><a href="">Đồng Hành Nhà Thuốc</a></li>
                                </ul>
                            </div>
                            <h2 class="ft-title">LIÊN HỆ NHANH</h2>
                            <div class="ft-content">
                                <button class="phone_calling">
                                    <a href="tel:0342342366"><img src="<?= asset('/images/icons/Phone_Calling_Rounded.svg') ?>" alt="phone" />0342.342.366</a>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h2 class="ft-title">ĐIỀU KHOẢN VÀ CHÍNH SÁCH</h2>
                            <div class="ft-content">
                                <ul>
                                    <li><a href="">Điều Khoản Sử Dụng</a></li>
                                    <li><a href="">Chính Sách Bảo Mật</a></li>
                                    <li><a href="">Chính Sách Vận Chuyển</a></li>
                                    <li><a href="">Chính Sách Giải Quyết Khiếu Nại</a></li>
                                    <li><a href="">Chính Sách Kiểm Hàng Và Đổi Trả</a></li>
                                    <li><a href="">Chính Sách Khách Hàng Thân Thiết</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <h2 class="ft-title">HỖ TRỢ KHÁCH HÀNG</h2>
                            <div class="ft-content mb-3">
                                <ul>
                                    <li><a href="">Hướng Dẫn Đặt Hàng</a></li>
                                    <li><a href="">Câu Hỏi Thường Gặp</a></li>
                                </ul>
                            </div>
                            <h2 class="ft-title">KẾT NỐI VỚI CHÚNG TÔI</h2>
                            <div class="ft-content">
                                <div class="d-flex gap-3 align-items-center">
                                    <a href="https://fb.com">
                                        <img src="<?= asset('/images/icons/ic-facebook.svg') ?>" alt="facebook" />
                                    </a>
                                    <a href="https://zalo.com">
                                        <img src="<?= asset('/images/icons/ic-zalo.svg') ?>" alt="zalo" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ft-bottom">
                <div class="container">
                    <div class="copy-right">© BẢN QUYỀN THUỘC DƯỢC AN KHANG</div>
                </div>
            </div>
        </footer>
        <div id="back-to-top">
            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="27" viewBox="0 0 26 27" fill="none">
                <path d="M13 26V1M13 1L1 12.7347M13 1L25 12.7347" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div id="chat-bot">
            <svg width="54" height="54" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M23.4 45C31.3529 45 37.8 38.5529 37.8 30.6C37.8 22.6471 31.3529 16.2 23.4 16.2C15.4471 16.2 9 22.6471 9 30.6C9 32.8239 9.50414 34.93 10.4044 36.8104C10.7138 37.4567 10.8224 38.1889 10.6372 38.8811L10.0467 41.0879C9.58132 42.8273 11.1726 44.4186 12.912 43.9532L15.1189 43.3628C15.8111 43.1776 16.5433 43.2862 17.1896 43.5956C19.0699 44.4958 21.1761 45 23.4 45Z"
                    stroke="white"
                    stroke-width="1.5"
                />
                <path
                    d="M37.8 31.5033C37.9197 31.4534 38.0383 31.4016 38.1559 31.3479C38.8077 31.0501 39.5389 30.9388 40.2311 31.124L41.088 31.3533C42.8274 31.8187 44.4187 30.2274 43.9533 28.488L43.724 27.6311C43.5388 26.9389 43.6502 26.2077 43.9479 25.5559C44.6235 24.0768 45 22.4324 45 20.7C45 14.2383 39.7617 9 33.3 9C28.4359 9 24.2651 11.9682 22.5 16.1921"
                    stroke="white"
                    stroke-width="1.5"
                />
                <path
                    d="M18.9 30.6C18.9 31.5942 18.0942 32.4 17.1 32.4C16.1059 32.4 15.3 31.5942 15.3 30.6C15.3 29.6059 16.1059 28.8 17.1 28.8C18.0942 28.8 18.9 29.6059 18.9 30.6Z"
                    fill="white"
                />
                <path
                    d="M25.2 30.6C25.2 31.5942 24.3942 32.4 23.4 32.4C22.4059 32.4 21.6 31.5942 21.6 30.6C21.6 29.6059 22.4059 28.8 23.4 28.8C24.3942 28.8 25.2 29.6059 25.2 30.6Z"
                    fill="white"
                />
                <path
                    d="M31.5 30.6C31.5 31.5942 30.6942 32.4 29.7 32.4C28.7059 32.4 27.9 31.5942 27.9 30.6C27.9 29.6059 28.7059 28.8 29.7 28.8C30.6942 28.8 31.5 29.6059 31.5 30.6Z"
                    fill="white"
                />
            </svg>
        </div>
    </body>
</html>