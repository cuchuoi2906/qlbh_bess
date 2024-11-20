<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
?>
<div class="main-content">
    <div class="container">
        <div class="menu-prod">
            <div class="d-flex align-items-center gap-2">
                <button><a href="/products/ORDERFAST-0">Tất cả sản phẩm</a></button>
                <button class="active">
                    <a href="/order-fast">
                        Đặt hàng nhanh
                        <img src="<?= asset('/images/icon_new.gif') ?>" width="40" style="padding-left:3px" />
                    </a>
                </button>
                <!--<button><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <button><a href="/products/COSMECEUTICALS-0">Hóa Mỹ phẩm</a></button>
                <button><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>
                <button><a href="/products/PRODUCTCOMPANY-0">TPCN NichieiAsia</a></button>
                <button><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>-->
            </div>
        </div>
        <div class="row">
            <div class="col-xl-7" id="left-cart-fast">
                <div class="main-search mb-3">
                    <div class="input-group input-group-search-mobile">
                        <input type="text" id="keywordSearchFast" name="keywordSearchFast" value="<?php echo isset($keyword) ? $keyword : ''; ?>" class="form-control" placeholder="Nhập tên thuốc, hoạt chất cần tìm..." aria-label="basic-search" aria-describedby="basic-search">
                        <div style="align-content: center;" id="clearSearchIconFast">
                            <img src="<?= asset('/images/delete-img.png') ?>" height="20px" />
                        </div>
                        <span class="input-group-text" id="main-search">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11.6" cy="11.6" r="7.6" stroke="#8A909F" stroke-width="1.5"></circle>
                                <path d="M17.2 17.2L20 20" stroke="#8A909F" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </span>
                    </div>
                </div>
                <h6 id="titleFastOrder">Có thể bạn muốn tìm</h6>
                <div id="dropdown-list-suggest-fast"></div>
            </div>
            <div class="col-xl-5 mb-4">
                <div class="content-box container_cart_fast" id="container_cart_fast">
                    <h2 class="content-title">Giỏ hàng 
                        <button class="float-end" onclick="deleteProductCartFastAll()">
                            <img src="https://vuaduoc.com/assets//images/icons/Delete.svg" alt="heart">
                            <span class="price-fast">Xóa tất cả</span>
                        </button>
                    </h2>
                    <div class="content-box-order-fast-scroll" id="content-box-order-fast-scroll">
                        <?php 
                        if(check_array($result['vars'])){
                            $productsList = $result['vars'];
                            $meta = $productsList['meta'];
                            unset($productsList['meta']);
                            foreach($productsList as $items){
                                $htmlPriceSl = "";
                                if(isset($items['product']['pricePolicies']) && check_array($items['product']['pricePolicies'])){
                                    $pricePolicies = $items['product']['pricePolicies'];
                                    foreach($pricePolicies as $price){
                                        $htmlPriceSl .= '<div class="price-sl mb-2">Mua số lượng từ '.$price['quantity'].' giá '.formatCurrencyVND($price['price']).'</div>';
                                    }
                                }
                                $product = $items['product'];
                                $quantity = $items['quantity'];
                        ?>
                            <div class="content-box-order-fast mb-2">
                                <div class="cart-item d-flex align-items-start justify-content-between mb-2">
                                    <div class="left d-flex align-items-center gap-3">
                                        <div class="thumb">
                                            <img width="100" height="100" src="<?php echo $product['avatar']['url']; ?>" alt="prod-item-1" />
                                        </div>
                                        <div class="info d-flex flex-column">
                                            <h3 class="cart-item-title"><?php echo $product['name']; ?></h3>
                                            <?php 
                                            $price = $product['db_discount_price'] ? $product['db_discount_price'] : $product['price'];
                                            $price = $product['price_policy'] ? $product['price_policy'] : $price;
                                            ?>
                                            <div class="price" id="priceDetail<?php echo $product['id']; ?>"><?php echo formatCurrencyVND($price); ?></div>
                                            <div class="input-group number-input">
                                                <div class="input-group-prepend">
                                                    <button class="btn btn-decrement" type="button" data-product-id="<?php echo $product['id']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                            <path
                                                                d="M5 10H15"
                                                                stroke="#1C274C"
                                                                stroke-width="1.5"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <input type="number" id="productCount<?php echo intval($product['id']); ?>" class="form-control inputNumber" value="<?php echo $quantity; ?>" min="0" />
                                                <div class="input-group-append">
                                                    <button class="btn btn-increment" type="button" data-product-id="<?php echo $product['id']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                            <path
                                                                d="M5 10H15"
                                                                stroke="#1C274C"
                                                                stroke-width="1.5"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                            <path
                                                                d="M10 15V5"
                                                                stroke="#1C274C"
                                                                stroke-width="1.5"
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="right h-100">
                                        <div class="cta d-flex justify-content-start flex-column text-end h-100 align-items-end h-100">
                                            <div class="d-sm-flex d-none align-items-center btn-product-delete" data-product-id="<?php echo intval($product['id']); ?>"> 
                                                <button><img src="<?= asset('/images/icons/Delete.svg') ?>" alt="heart">Xóa</button>
                                            </div>
                                            <div class="close d-sm-none d-block btn-product-delete" data-product-id="<?php echo intval($product['id']); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" fill="none">
                                                    <path
                                                        fill-rule="evenodd"
                                                        clip-rule="evenodd"
                                                        d="M16.5299 6.21195C16.7252 6.01678 16.7252 5.70033 16.5299 5.50515C16.3345 5.30998 16.0178 5.30998 15.8224 5.50515L11.0199 10.3035L6.21735 5.50515C6.02201 5.30998 5.70529 5.30998 5.50994 5.50515C5.31459 5.70033 5.31459 6.01678 5.50994 6.21195L10.3125 11.0103L5.50994 15.8087C5.31459 16.0038 5.31459 16.3203 5.50994 16.5155C5.70529 16.7106 6.02201 16.7106 6.21735 16.5155L11.0199 11.7171L15.8224 16.5155C16.0178 16.7106 16.3345 16.7106 16.5299 16.5155C16.7252 16.3203 16.7252 16.0038 16.5299 15.8087L11.7273 11.0103L16.5299 6.21195Z"
                                                        fill="#1C274C"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            }
                        }?>
                    </div>
                    <ul class="d-flex flex-column gap-2 m-0 p-0">
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Tổng số lượng</div>
                            <div class="value fw-bold" id="totalProduct"><?php echo isset($meta['total_product']) ? $meta['total_product'] : 0; ?></div>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Tổng tiền</div>
                            <div class="value fw-bold total" id="totalMoney"><?php echo isset($meta['total_money']) ? formatCurrencyVND($meta['total_money']) : 0; ?></div>
                        </li>
                    </ul>
                    <div class="btn-custom d-flex gap-2 align-items-center">
                        <a href="/cart" title="Giỏ hàng">
                            <svg width="24" height="24" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M16.8 33.6C18.1255 33.6 19.2 34.6745 19.2 36C19.2 37.3255 18.1255 38.4 16.8 38.4C15.4745 38.4 14.4 37.3255 14.4 36C14.4 34.6745 15.4745 33.6 16.8 33.6Z"
                                    stroke="white"
                                    stroke-width="2"
                                ></path>
                                <path
                                    d="M31.2 33.6001C32.5255 33.6001 33.6 34.6746 33.6 36.0001C33.6 37.3256 32.5255 38.4001 31.2 38.4001C29.8746 38.4001 28.8 37.3256 28.8 36.0001C28.8 34.6746 29.8746 33.6001 31.2 33.6001Z"
                                    stroke="white"
                                    stroke-width="2"
                                ></path>
                                <path
                                    d="M8.41793 9.74694L8.74961 8.80355L8.74961 8.80355L8.41793 9.74694ZM8.33168 8.65661C7.81066 8.47343 7.23979 8.74731 7.05661 9.26833C6.87343 9.78935 7.1473 10.3602 7.66832 10.5434L8.33168 8.65661ZM12.1373 11.7168L12.963 11.1526L12.963 11.1526L12.1373 11.7168ZM14.2203 28.1379L13.4947 28.8259L13.4947 28.8259L14.2203 28.1379ZM37.8524 20.6124L38.8319 20.8143L38.8331 20.8081L37.8524 20.6124ZM37.0528 24.492L38.0322 24.6938L38.0322 24.6938L37.0528 24.492ZM37.9752 15.5153L37.1826 16.1251L37.1826 16.1251L37.9752 15.5153ZM35.4138 28.8806L34.7821 28.1054L34.7821 28.1054L35.4138 28.8806ZM13.7329 20.416V16.0614H11.7329V20.416H13.7329ZM8.74961 8.80355L8.33168 8.65661L7.66832 10.5434L8.08626 10.6903L8.74961 8.80355ZM22.3 30.6H30.7846V28.6H22.3V30.6ZM13.7329 16.0614C13.7329 14.9256 13.7343 13.994 13.6527 13.24C13.5686 12.4636 13.3886 11.7754 12.963 11.1526L11.3117 12.281C11.4817 12.5297 11.5994 12.8562 11.6643 13.4553C11.7316 14.0768 11.7329 14.8818 11.7329 16.0614H13.7329ZM8.08626 10.6903C9.14995 11.0643 9.86511 11.3175 10.3947 11.5773C10.8982 11.8244 11.1447 12.0365 11.3117 12.281L12.963 11.1526C12.5344 10.5255 11.9624 10.1188 11.2758 9.78184C10.6152 9.45774 9.76847 9.16176 8.74961 8.80355L8.08626 10.6903ZM11.7329 20.416C11.7329 22.7449 11.7553 24.4022 11.9711 25.6624C12.1993 26.9947 12.6508 27.9359 13.4947 28.8259L14.946 27.4499C14.4036 26.8778 14.1114 26.3112 13.9424 25.3248C13.7611 24.2662 13.7329 22.7936 13.7329 20.416H11.7329ZM22.3 28.6C20.0391 28.6 18.4494 28.5976 17.2471 28.4271C16.0792 28.2615 15.4249 27.955 14.946 27.4499L13.4947 28.8259C14.402 29.7829 15.5565 30.2074 16.9663 30.4073C18.3416 30.6024 20.0986 30.6 22.3 30.6V28.6ZM12.7329 15.592H32.1421V13.592H12.7329V15.592ZM36.873 20.4105L36.0734 24.2901L38.0322 24.6938L38.8318 20.8143L36.873 20.4105ZM32.1421 15.592C33.5086 15.592 34.727 15.5933 35.6922 15.7012C36.1723 15.7549 36.5429 15.8305 36.8117 15.925C37.0885 16.0223 37.1703 16.1091 37.1826 16.1251L38.7678 14.9056C38.4249 14.4599 37.937 14.2005 37.4747 14.0381C37.0045 13.8729 36.4667 13.7754 35.9144 13.7136C34.8145 13.5907 33.4716 13.592 32.1421 13.592V15.592ZM38.8331 20.8081C39.105 19.4458 39.3313 18.3233 39.3866 17.4283C39.4431 16.5115 39.3362 15.6444 38.7678 14.9056L37.1826 16.1251C37.3246 16.3096 37.4339 16.5998 37.3904 17.3051C37.3455 18.0322 37.1552 18.9966 36.8718 20.4167L38.8331 20.8081ZM30.7846 30.6C32.0083 30.6 33.0167 30.6018 33.8262 30.5027C34.663 30.4003 35.4033 30.1792 36.0455 29.6558L34.7821 28.1054C34.5415 28.3014 34.216 28.4401 33.5833 28.5175C32.9233 28.5983 32.0584 28.6 30.7846 28.6V30.6ZM36.0734 24.2901C35.8163 25.5377 35.64 26.3844 35.4276 27.0145C35.2241 27.6186 35.0226 27.9094 34.7821 28.1054L36.0455 29.6558C36.6877 29.1325 37.0537 28.4521 37.3229 27.6532C37.5833 26.8804 37.7852 25.8924 38.0322 24.6938L36.0734 24.2901Z"
                                    fill="white"
                                ></path>
                            </svg>
                            <span class="text-white">Xem giỏ hàng</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>