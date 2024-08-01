<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
?>
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
        <div class="row">
        <div class="col-md-6">
            <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="square-image">
                                <img src="<?php echo $product['avatar']['url']; ?>" class="product-image" alt="Product Image">
                            </div>
                        </div>  
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" 
                        data-bs-slide="prev" (click)="previousImage()">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" 
                        data-bs-slide="next" (click)="nextImage()">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
            <div class="row">
                <div class="thumbnail-container">
                    <?php 
                    $images = $product['images'];
                    foreach($images as $items){
                    ?>
                        <div class="thumbnail-item">
                            <img src="<?php echo $items['url'] ?>" class="thumbnail-image" alt="Thumbnail Image">
                        </div>
                    <?php 
                    }?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-details">
            <h2>{{ product?.name }}</h2>
            <p>{{ product?.description }}</p>
            <p>Giá: ${{ product?.price }}</p>
            <p>Tổng tiền: ${{ getTotalPrice() | number:'1.2-2' }}</p>
            <div class="product-actions">
                <button class="btn btn-primary" (click)="addToCart()">Thêm vào giỏ hàng</button>
                <button class="btn btn-success" (click)="buyNow()">Mua ngay</button>
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
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>