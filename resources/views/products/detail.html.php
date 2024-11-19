<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
$v_text_cate = '';
$v_link_cate = '';
if($type == 'FUNCTIONIAL'){
	$v_text_cate = 'Thực Phẩm Chức Năng';
	$v_link_cate = '/products/FUNCTIONIAL-0';
}
if($type == 'COSMECEUTICALS'){
	$v_text_cate = 'Hóa Mỹ phẩm';
	$v_link_cate = '/products/COSMECEUTICALS-0';
}
if($type == 'PERSONALCARE'){
	$v_text_cate = 'Chăm Sóc Cá Nhân';
	$v_link_cate = '/products/PERSONALCARE-0';
}
if($type == 'PRODUCTCOMPANY'){
	$v_text_cate = 'TPCN NichieiAsia';
	$v_link_cate = '/products/PRODUCTCOMPANY-0';
}
if($type == 'MEDICALDEVICES'){
	$v_text_cate = 'Thiết Bị Y Tế';
	$v_link_cate = '/products/MEDICALDEVICES-0';
}
if($type == 'ORDERFAST'){
	$v_text_cate = 'Tất cả sản phẩm';
	$v_link_cate = '/products/ORDERFAST-0';
}
?>
<style>
    .swiper-container {
        width: 100%;
    }
    .swiper-slide img {
        width: 100%;
    }
    .thumbnail-container {
        margin-top: 10px;
    }
    .thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        cursor: pointer;
        margin: 0 5px;
    }
</style>
<div class="main-content">
    <div class="container">
        <div class="menu-prod">
            <div class="d-flex align-items-center gap-2">
                <button <?php echo ($type == 'ORDERFAST') ? 'class="active"' : ''; ?>><a href="/products/ORDERFAST-0">Tất cả sản phẩm</a></button>
                <button <?php echo ($type == '') ? 'class="active"' : ''; ?>>
                    <a href="/order-fast">
                        Đặt hàng nhanh
                        <img src="<?= asset('/images/icon_new.gif') ?>" width="40" style="padding-left:3px" />
                    </a>
                </button>
                <!--<button <?php echo ($type == 'FUNCTIONIAL') ? 'class="active"' : ''; ?>><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <button <?php echo ($type == 'COSMECEUTICALS') ? 'class="active"' : ''; ?>><a href="/products/COSMECEUTICALS-0">Hóa Mỹ phẩm</a></button>
                <button <?php echo ($type == 'PERSONALCARE') ? 'class="active"' : ''; ?>><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>
                <button <?php echo ($type == 'PRODUCTCOMPANY') ? 'class="active"' : ''; ?>><a href="/products/PRODUCTCOMPANY-0">TPCN NichieiAsia</a></button>
                <button <?php echo ($type == 'MEDICALDEVICES') ? 'class="active"' : ''; ?>><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>-->
            </div>
        </div>
		<?php 
		if(!empty($type)){
		?>
            <nav aria-label="breadcrumb" class="breadcrumb-container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo $v_link_cate; ?>"><?php echo $v_text_cate; ?></a></li>
                    <!--<?php 
                    if($categoryByParentId){
                    ?>
                            <li class="breadcrumb-item"><a href="<?php echo '/products/'.$categoryByParentId->rewrite.'-'.$categoryByParentId->id; ?>"><?php echo $categoryByParentId->name ?></a></li>
                    <?php 
                    }
                    if($categoryById){
                            ?>
                                    <li class="breadcrumb-item active" aria-current="page"><?php echo $categoryById->name ?></li>
                            <?php
                    }?>-->
                </ol>
            </nav>
        <?php 
		}?>
        <div class="main-search mb-3 d-xl-none">
            <div class="input-group input-group-search-mobile">
                <input type="text" id="keywordm" name="keyword" value="<?php echo isset($keyword) ? $keyword : ''; ?>" class="form-control" placeholder="Nhập tên thuốc, hoạt chất cần tìm..." aria-label="basic-search" aria-describedby="basic-search">
                <span class="input-group-text" id="main-search">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.6" cy="11.6" r="7.6" stroke="#8A909F" stroke-width="1.5"></circle>
                        <path d="M17.2 17.2L20 20" stroke="#8A909F" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </span>
            </div>
            <div id="dropdown-list-suggest-mobile" class="position-relative"></div>
        </div>
        <div class="row menu-prod">
            <div class="col-md-5">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="swiper-container main-slider">
                            <div class="swiper-wrapper">
                                    <div class="square-image mx-auto">
                                        <img src="<?php echo $product['avatar']['url'] ?>" class="thumbnail-image" alt="Thumbnail Image">
                                    </div>
                            </div>  
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 prod-item">
                <div class="product-details">
					<h2><?php echo $product['name'] ?></h2>
					<p><?php echo $product['teaser'] ?></p>
					<?php 
                    $pricePolicies = $product['pricePolicies'];
                    $htmlPriceSl = "";
                    if(check_array($pricePolicies)){
                        foreach($pricePolicies as $price){
                            $htmlPriceSl .= '<div class="price-sl">Mua số lượng từ '.$price['quantity'].' giá '.formatCurrencyVND($price['price']).'</div>';
                        }
                    }
					if(intval($product['price'])>0){
					?>
                        <div class="price text-decoration-line-through color-gray">Giá: <?php echo formatCurrencyVND($product['price']); ?></div>
                        <?php 
                        if($product['db_discount_price']){
                        ?>
                            <div class="price">Giá khuyến mại: <?php echo formatCurrencyVND($product['db_discount_price']); ?></div>
					<?php
                        }
                        echo $htmlPriceSl;
					}
                    ?>
					<p><i style="--text-color: #018279;">(Liên hệ để được giá tốt nhất)</i></p>
					<div class="input-group number-input width-mobile-50 me-2" style="width: 124px;">
						<div class="input-group-prepend">
							<button class="btn btn-decrement" type="button" data-product-id="<?php echo $product['id']; ?>" disabled>
								<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="16" cy="16" r="10.6667" stroke="#C3CCEC" stroke-width="1.5" />
									<path d="M19.2 16H12.8" stroke="#C3CCEC" stroke-width="1.5" stroke-linecap="round" />
								</svg>
							</button>
						</div>
						<input type="number" id="productCount<?php echo $product['id']; ?>" class="form-control inputNumber" value="0" min="0" />
						<div class="input-group-append">
							<button class="btn btn-increment" data-product-id="<?php echo $product['id']; ?>" type="button">
								<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
									<circle cx="16" cy="16" r="10.6667" stroke="#1C274C" stroke-width="1.5" />
									<path
										d="M19.2 16L16 16M16 16L12.8 16M16 16L16 12.8M16 16L16 19.2"
										stroke="#1C274C"
										stroke-width="1.5"
										stroke-linecap="round"
									/>
								</svg>
							</button>
						</div>
					</div>
					<div class="d-flex">
						<a class="btn-custom d-flex gap-2 align-items-center mt-1 btn-increment-cart bg-warning" data-product-id="<?php echo $product['id']; ?>" href="javascript:void(0)" id="btn-increment-cart">
							<span class="text-white"> Thêm giỏ hàng</span>
						</a>
						<a class="btn-custom d-flex gap-2 align-items-center mt-1 btn-increment-order ms-2" data-product-id="<?php echo $product['id']; ?>" href="javascript:void(0)">
							<!--<svg width="24" height="24" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M16.8 33.6C18.1255 33.6 19.2 34.6745 19.2 36C19.2 37.3255 18.1255 38.4 16.8 38.4C15.4745 38.4 14.4 37.3255 14.4 36C14.4 34.6745 15.4745 33.6 16.8 33.6Z" stroke="white" stroke-width="2"></path>
								<path d="M31.2 33.6001C32.5255 33.6001 33.6 34.6746 33.6 36.0001C33.6 37.3256 32.5255 38.4001 31.2 38.4001C29.8746 38.4001 28.8 37.3256 28.8 36.0001C28.8 34.6746 29.8746 33.6001 31.2 33.6001Z" stroke="white" stroke-width="2"></path>
								<path d="M8.41793 9.74694L8.74961 8.80355L8.74961 8.80355L8.41793 9.74694ZM8.33168 8.65661C7.81066 8.47343 7.23979 8.74731 7.05661 9.26833C6.87343 9.78935 7.1473 10.3602 7.66832 10.5434L8.33168 8.65661ZM12.1373 11.7168L12.963 11.1526L12.963 11.1526L12.1373 11.7168ZM14.2203 28.1379L13.4947 28.8259L13.4947 28.8259L14.2203 28.1379ZM37.8524 20.6124L38.8319 20.8143L38.8331 20.8081L37.8524 20.6124ZM37.0528 24.492L38.0322 24.6938L38.0322 24.6938L37.0528 24.492ZM37.9752 15.5153L37.1826 16.1251L37.1826 16.1251L37.9752 15.5153ZM35.4138 28.8806L34.7821 28.1054L34.7821 28.1054L35.4138 28.8806ZM13.7329 20.416V16.0614H11.7329V20.416H13.7329ZM8.74961 8.80355L8.33168 8.65661L7.66832 10.5434L8.08626 10.6903L8.74961 8.80355ZM22.3 30.6H30.7846V28.6H22.3V30.6ZM13.7329 16.0614C13.7329 14.9256 13.7343 13.994 13.6527 13.24C13.5686 12.4636 13.3886 11.7754 12.963 11.1526L11.3117 12.281C11.4817 12.5297 11.5994 12.8562 11.6643 13.4553C11.7316 14.0768 11.7329 14.8818 11.7329 16.0614H13.7329ZM8.08626 10.6903C9.14995 11.0643 9.86511 11.3175 10.3947 11.5773C10.8982 11.8244 11.1447 12.0365 11.3117 12.281L12.963 11.1526C12.5344 10.5255 11.9624 10.1188 11.2758 9.78184C10.6152 9.45774 9.76847 9.16176 8.74961 8.80355L8.08626 10.6903ZM11.7329 20.416C11.7329 22.7449 11.7553 24.4022 11.9711 25.6624C12.1993 26.9947 12.6508 27.9359 13.4947 28.8259L14.946 27.4499C14.4036 26.8778 14.1114 26.3112 13.9424 25.3248C13.7611 24.2662 13.7329 22.7936 13.7329 20.416H11.7329ZM22.3 28.6C20.0391 28.6 18.4494 28.5976 17.2471 28.4271C16.0792 28.2615 15.4249 27.955 14.946 27.4499L13.4947 28.8259C14.402 29.7829 15.5565 30.2074 16.9663 30.4073C18.3416 30.6024 20.0986 30.6 22.3 30.6V28.6ZM12.7329 15.592H32.1421V13.592H12.7329V15.592ZM36.873 20.4105L36.0734 24.2901L38.0322 24.6938L38.8318 20.8143L36.873 20.4105ZM32.1421 15.592C33.5086 15.592 34.727 15.5933 35.6922 15.7012C36.1723 15.7549 36.5429 15.8305 36.8117 15.925C37.0885 16.0223 37.1703 16.1091 37.1826 16.1251L38.7678 14.9056C38.4249 14.4599 37.937 14.2005 37.4747 14.0381C37.0045 13.8729 36.4667 13.7754 35.9144 13.7136C34.8145 13.5907 33.4716 13.592 32.1421 13.592V15.592ZM38.8331 20.8081C39.105 19.4458 39.3313 18.3233 39.3866 17.4283C39.4431 16.5115 39.3362 15.6444 38.7678 14.9056L37.1826 16.1251C37.3246 16.3096 37.4339 16.5998 37.3904 17.3051C37.3455 18.0322 37.1552 18.9966 36.8718 20.4167L38.8331 20.8081ZM30.7846 30.6C32.0083 30.6 33.0167 30.6018 33.8262 30.5027C34.663 30.4003 35.4033 30.1792 36.0455 29.6558L34.7821 28.1054C34.5415 28.3014 34.216 28.4401 33.5833 28.5175C32.9233 28.5983 32.0584 28.6 30.7846 28.6V30.6ZM36.0734 24.2901C35.8163 25.5377 35.64 26.3844 35.4276 27.0145C35.2241 27.6186 35.0226 27.9094 34.7821 28.1054L36.0455 29.6558C36.6877 29.1325 37.0537 28.4521 37.3229 27.6532C37.5833 26.8804 37.7852 25.8924 38.0322 24.6938L36.0734 24.2901Z" fill="white"></path>
							</svg>-->
							<span class="text-white">Mua ngay</span>
						</a>
					</div>
                </div>
            </div>      
        </div>    
        <?php 
        if(!empty($product['specifications'])){
        ?>
            <!--<div class="divider">
                <div class="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <path d="M27.7927 12.2074C27.7927 12.2074 27.2158 15.2001 23.8743 18.5416C20.5328 21.8831 17.5406 22.4594 17.5406 22.4594M31.2101 25.8768C28.379 28.7078 23.789 28.7078 20.958 25.8768L14.1233 19.0421C11.2922 16.2111 11.2922 11.6211 14.1233 8.79002C16.9543 5.95899 21.5443 5.95899 24.3753 8.79002L31.2101 15.6247C34.0411 18.4558 34.0411 23.0458 31.2101 25.8768Z" stroke="#018279" stroke-width="2"></path>
                        <path d="M23.3334 12.6667L21.3334 10.6667" stroke="#018279" stroke-width="2" stroke-linecap="round"></path>
                        <path d="M12.9736 17.5129C9.36846 18.2897 6.66663 21.4961 6.66663 25.3334C6.66663 29.7517 10.2483 33.3334 14.6666 33.3334C18.5214 33.3334 21.7394 30.607 22.4976 26.9774" stroke="#018279" stroke-width="2"></path>
                    </svg>
                </div>
            </div>-->
        <?php 
        }?>
    </div>
</div>
<script>
    var thumbnailSlider = new Swiper('.thumbnail-slider', {
        spaceBetween: 10,
        autoHeight: true,
        slidesPerView: 4,
        freeMode: true,
        watchSlidesVisibility: true,
        watchSlidesProgress: true
    });

    var mainSlider = new Swiper('.main-slider', {
        spaceBetween: 10,
        autoHeight: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        thumbs: {
            swiper: thumbnailSlider
        }
    });
</script>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>