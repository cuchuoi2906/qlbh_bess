<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
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
                <button><a href="/products">FLASH SALE</a></button>
                <button><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <button><a href="/products/COSMECEUTICALS-0">Dược Mỹ Phẩm</a></button>
                <button><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>
                <button><a href="/products/PRODUCTCOMPANY-0">Sản Phẩm Vua Dược</a></button>
                <button><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>
            </div>
        </div>    
        <div class="row menu-prod">
            <div class="col-md-6">
                <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="swiper-container main-slider">
                            <div class="swiper-wrapper">
                                <?php 
                                $images = $product['images'];
                                foreach($images as $items){
                                ?>
                                    <div class="square-image swiper-slide">
                                        <img src="<?php echo $items['url'] ?>" class="thumbnail-image" alt="Thumbnail Image">
                                    </div>
                                <?php 
                                }?>
                            </div>  
                        </div>
                    </div>
                    <button class="carousel-control-prev swiper-button-prev" type="button" data-bs-target="#carouselExample" 
                            data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next swiper-button-next" type="button" data-bs-target="#carouselExample" 
                            data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <div class="row">
                    <div class="thumbnail-container">
                        <div class="swiper-container thumbnail-slider thumbnail-container">
                            <div class="swiper-wrapper">
                                <?php 
                                $images = $product['images'];
                                foreach($images as $items){
                                ?>
                                    <div class="thumbnail-item swiper-slide">
                                        <img src="<?php echo $items['url'] ?>" class="thumbnail-image" alt="Thumbnail Image">
                                    </div>
                                <?php 
                                }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 prod-item">
                <div class="product-details">
                <h2><?php echo $product['name'] ?></h2>
                <p><?php echo $product['teaser'] ?></p>
                <p>Giá: <?php echo formatCurrencyVND($product['price']); ?></p>

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
                <div class="product-quantity">
                    <div class="border-wrapper">
                    <button (click)="decreaseQuantity()">-</button>
                    <input type="text" [value]="quantity">
                    <button (click)="increaseQuantity()">+</button>
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
        <div class="row">
            <div class="col-xl-8">
                <?php echo $product['specifications']; ?>   
            </div>                
        </div>
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