<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
?>
<div class="main-content">
    <div class="container">
        <div class="menu-prod">
            <div class="d-flex align-items-center gap-2">
                <button><a href="/products">FLASH SALE</a></button>
                <button><a href="/products/ORDERFAST-0">Đặt hàng nhanh</a></button>
                <button><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <button><a href="/products/COSMECEUTICALS-0">Hóa Mỹ phẩm</a></button>
                <button><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>
                <button><a href="/products/PRODUCTCOMPANY-0">TPCN NichieiAsia</a></button>
                <button><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8">
                 <input type="hidden" id="pageCart" name="pageCart" value="1">
                <?php 
                if(check_array($result['vars'])){
                    $productsList = $result['vars'];
                    $meta = $productsList['meta'];
                    unset($productsList['meta']);
                    foreach($productsList as $items){
                        $product = $items['product'];
                        $quantity = $items['quantity'];
                ?>
                    <div class="content-box mb-3">
                        <div class="cart-item d-flex align-items-start justify-content-between">
                            <div class="left d-flex align-items-center gap-3">
                                <div class="thumb">
                                    <img width="100" height="100" src="<?php echo $product['avatar']['url']; ?>" alt="prod-item-1" />
                                </div>
                                <div class="info d-flex flex-column">
                                    <h3 class="cart-item-title"><?php echo $product['name']; ?></h3>
                                    <div class="price"><?php echo formatCurrencyVND($product['price']); ?></div>
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
                                    <div class="total-price" id="total-price-product<?php echo intval($product['id']); ?>"><?php echo formatCurrencyVND($product['price']*$quantity); ?></div>
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
            <div class="col-xl-4 mb-4">
				<div class="box-info mb-4">
                    <h2 class="info-title">Thông Tin Người Nhận Hàng</h2>
                    <ul class="d-flex flex-column list-style-none m-0 p-0 gap-2">
                        <li class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <circle cx="11.9998" cy="7.2" r="3.2" stroke="#1C274C" />
                                <ellipse cx="11.9999" cy="16" rx="5.6" ry="3.2" stroke="#1C274C" />
                            </svg>
                            <span><?php echo $user->name; ?></span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M11.23 5.85292L11.7492 6.78327C12.2177 7.62285 12.0296 8.72424 11.2917 9.46223C11.2917 9.46223 11.2917 9.46223 11.2917 9.46223C11.2916 9.46234 10.3966 10.3575 12.0195 11.9804C13.6417 13.6026 14.5368 12.709 14.5377 12.7082C14.5377 12.7082 14.5377 12.7082 14.5377 12.7082C15.2757 11.9702 16.3771 11.7822 17.2166 12.2507L18.147 12.7699C19.4148 13.4774 19.5645 15.2554 18.4501 16.3697C17.7805 17.0393 16.9602 17.5604 16.0534 17.5947C14.5269 17.6526 11.9345 17.2663 9.33406 14.6658C6.7336 12.0654 6.34727 9.47297 6.40514 7.94646C6.43952 7.03967 6.96054 6.21938 7.63015 5.54977C8.74451 4.43541 10.5224 4.58514 11.23 5.85292Z"
                                    stroke="#1C274C"
                                    stroke-linecap="round"
                                />
                            </svg>
                            <span><?php echo $user->phone; ?></span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M5.6001 10.5146C5.6001 6.9167 8.46548 4 12.0001 4C15.5347 4 18.4001 6.9167 18.4001 10.5146C18.4001 14.0844 16.3574 18.2499 13.1704 19.7396C12.4275 20.0868 11.5727 20.0868 10.8298 19.7396C7.64276 18.2499 5.6001 14.0844 5.6001 10.5146Z"
                                    stroke="#1C274C"
                                />
                                <circle cx="12.0001" cy="10.4" r="2.4" stroke="#1C274C" />
                            </svg>
                            <span><?php echo $user->address_register; ?></span>
                        </li>
                    </ul>
					<div class="mt-2">
						<h2 class="info-title">Ghi Chú Giao Hàng</h2>
						<textarea
							class="form-control"
							id="exampleFormControlTextarea1"
							name="note"
							rows="4"
							placeholder="Thêm ghi chú về đơn hàng để được hỗ trợ. Vd: Hãy gọi điện trước khi giao hàng. Thay đổi địa chỉ nhận hàng ( nếu có)"
						></textarea>
					</div>
                </div>
                <!--<div class="content-box mb-4">
                    <h2 class="content-title">Mã giảm giá</h2>
                    <div id="view-voucher" class="view-voucher d-flex align-items-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M13.604 5.6H10.396C7.37151 5.6 5.85926 5.6 4.91966 6.53726C4.27273 7.18258 4.07123 8.09871 4.00846 9.59157C3.99602 9.88763 3.98979 10.0357 4.04508 10.1344C4.10036 10.2332 4.32106 10.3564 4.76247 10.6029C5.25268 10.8767 5.58406 11.3997 5.58406 12C5.58406 12.6003 5.25268 13.1233 4.76247 13.3971C4.32107 13.6436 4.10036 13.7668 4.04508 13.8656C3.98979 13.9643 3.99601 14.1124 4.00846 14.4084C4.07123 15.9013 4.27273 16.8174 4.91966 17.4627C5.85926 18.4 7.37151 18.4 10.396 18.4H13.604C16.6285 18.4 18.1407 18.4 19.0803 17.4627C19.7273 16.8174 19.9288 15.9013 19.9915 14.4084C20.004 14.1124 20.0102 13.9643 19.9549 13.8656C19.8996 13.7668 19.6789 13.6436 19.2375 13.3971C18.7473 13.1233 18.4159 12.6003 18.4159 12C18.4159 11.3997 18.7473 10.8767 19.2375 10.6029C19.6789 10.3564 19.8996 10.2332 19.9549 10.1344C20.0102 10.0357 20.004 9.88763 19.9915 9.59157C19.9288 8.09871 19.7273 7.18258 19.0803 6.53726C18.1407 5.6 16.6285 5.6 13.604 5.6Z"
                                stroke="#F8C333"
                                stroke-width="1.5"
                            />
                            <path d="M9.6001 14.4L14.4001 9.6" stroke="#F8C333" stroke-width="1.5" stroke-linecap="round" />
                            <path
                                d="M14.8002 14C14.8002 14.4418 14.442 14.8 14.0002 14.8C13.5584 14.8 13.2002 14.4418 13.2002 14C13.2002 13.5582 13.5584 13.2 14.0002 13.2C14.442 13.2 14.8002 13.5582 14.8002 14Z"
                                fill="#F8C333"
                            />
                            <path
                                d="M10.8002 10C10.8002 10.4418 10.442 10.8 10.0002 10.8C9.55837 10.8 9.2002 10.4418 9.2002 10C9.2002 9.55817 9.55837 9.2 10.0002 9.2C10.442 9.2 10.8002 9.55817 10.8002 10Z"
                                fill="#F8C333"
                            />
                        </svg>
                        <span>Nhấn để xem mã giảm giá & quà tặng</span>
                    </div>
                    <input type="text" class="form-control mb-3" id="voucher-code" placeholder="Nhập mã ưu đãi" />
                    <div class="btn-custom">Áp Dụng Ngay</div>
                </div>-->
                <div class="content-box">
                    <h2 class="content-title">Thông tin đơn hàng</h2>
                    <ul class="d-flex flex-column gap-2 m-0 p-0">
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Tổng số lượng</div>
                            <div class="value fw-bold" id="totalProduct"><?php echo isset($meta['total_product']) ? $meta['total_product'] : 0; ?></div>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Giảm giá trực tiếp</div>
                            <div class="value fw-bold"><?php echo isset($meta['total_discount']) ? formatCurrencyVND($meta['total_discount']) : 0; ?></div>
                        </li>
                        <!--<li class="d-flex justify-content-between align-items-center">
                            <div class="label">Giảm giá Voucher</div>
                            <div class="value fw-bold"><?php echo isset($meta['total_discount']) ? formatCurrencyVND($meta['total_discount']) : 0; ?></div>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Tiết kiệm được</div>
                            <div class="value fw-bold">0 đ</div>
                        </li>-->
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Tổng tiền</div>
                            <div class="value fw-bold total" id="totalMoney"><?php echo isset($meta['total_money']) ? formatCurrencyVND($meta['total_money']) : 0; ?></div>
                        </li>
                    </ul>
                    <div class="form-check policy-check mt-2 mb-3">
                        <input class="form-check-input" type="checkbox" value="" id="policy" checked />
                        <label class="form-check-label" for="policy"> Tôi đồng ý với <a href="/dieu-khoan-su-dung">Điều khoản sử dụng</a> </label>
                    </div>
                    <div class="btn-custom d-flex gap-2 align-items-center" id="btnOrder">
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
                        <span class="text-white">Đặt Hàng Ngay</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>