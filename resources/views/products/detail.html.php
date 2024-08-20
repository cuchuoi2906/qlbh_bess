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
	$v_text_cate = 'Đặt hàng nhanh';
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
                <button <?php echo ($type == '') ? 'class="active"' : ''; ?>><a href="/products">FLASH SALE</a></button>
                <!--<button <?php echo ($type == 'ORDERFAST') ? 'class="active"' : ''; ?>><a href="/products/ORDERFAST-0">Đặt hàng nhanh</a></button>-->
                <button <?php echo ($type == 'FUNCTIONIAL') ? 'class="active"' : ''; ?>><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <!--<button <?php echo ($type == 'COSMECEUTICALS') ? 'class="active"' : ''; ?>><a href="/products/COSMECEUTICALS-0">Hóa Mỹ phẩm</a></button>
                <button <?php echo ($type == 'PERSONALCARE') ? 'class="active"' : ''; ?>><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>-->
                <button <?php echo ($type == 'PRODUCTCOMPANY') ? 'class="active"' : ''; ?>><a href="/products/PRODUCTCOMPANY-0">TPCN NichieiAsia</a></button>
                <!--<button <?php echo ($type == 'MEDICALDEVICES') ? 'class="active"' : ''; ?>><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>-->
            </div>
        </div>
<<<<<<< HEAD
		<?php 
		if(!empty($type)){
		?>
			<nav aria-label="breadcrumb" class="breadcrumb-container">
				<ol class="breadcrumb">
					<li class="breadcrumb-item active"><a href="/">Trang chủ</a></li>
					<li class="breadcrumb-item"><a href="<?php echo $v_link_cate; ?>"><?php echo $v_text_cate; ?></a></li>
					<?php 
					if($categoryByParentId){
					?>
						<li class="breadcrumb-item"><a href="<?php echo '/products/'.$categoryByParentId->rewrite.'-'.$categoryByParentId->id; ?>"><?php echo $categoryByParentId->name ?></a></li>
					<?php 
					}
					if($categoryById){
						?>
							<li class="breadcrumb-item active" aria-current="page"><?php echo $categoryById->name ?></li>
						<?php
					}?>
				</ol>
			</nav>
        <?php 
		}?>
=======
>>>>>>> 94db92f6dd1d3359b657dbda950f44ba25abc89c
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
                <div class="price">Giá: <?php echo formatCurrencyVND($product['price']); ?></div>

                <div class="input-group number-input w-50">
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