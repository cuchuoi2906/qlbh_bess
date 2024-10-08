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
        <div class="checkout">
            <div class="checkout-content">
                <h3 class="checkout-title text-center">Quét QR Code để thanh toán đơn hàng</h3>
                <div class="order-info mb-4">
                    <div class="d-flex gap-3 justify-content-center">
                        <div class="">Mã đơn hàng: <strong><?php echo $orderData['code']; ?></strong></div>
                        <div class="">Tổng tiền thanh toán: <strong><?php echo $orderData['amount_formatted']; ?></strong></div>
                        <div class="">Hình thức thanh toán: <strong>Chuyển khoản ngân hàng</strong></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
						<img src="<?= asset('/images/qr-code.png') ?>" alt="qr-code" class="w-100">
						<div class="btn-custom d-flex gap-2 align-items-center mt-4">
							<a href="<?= asset('/images/qr-code.png') ?>" download="qr-code-vuaduoc" class="d-block text-white">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M4.7998 14.4C4.7998 16.6628 4.7998 17.7941 5.50275 18.4971C6.20569 19.2 7.33706 19.2 9.5998 19.2H14.3998C16.6625 19.2 17.7939 19.2 18.4969 18.4971C19.1998 17.7941 19.1998 16.6628 19.1998 14.4" stroke="#018279" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
									<path d="M11.9998 4.80001V15.2M11.9998 15.2L15.1998 11.7M11.9998 15.2L8.7998 11.7" stroke="#018279" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
								</svg>
								Tải mã ảnh QR xuống
							</a>
						</div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex flex-column mb-4 gap-2">
                            <div class="item">
                                <label for="">Ngân hàng:</label>
                                <div class="value fw-bold">Ngân hàng Thương mại cổ phần Kỹ Thương Việt Nam (Techcombank)</div>
                            </div>
                            <div class="item">
                                <label for="">Người nhận:</label>
                                <div class="value fw-bold">Dương Việt Hùng</div>
                            </div>
                            <div class="item">
                                <label for="">Số tài khoản:</label>
                                <div class="value fw-bold">19020234693028</div>
                            </div>
                            <div class="item">
                                <label for="">Nội dung chuyển khoản:</label>
                                <div class="value fw-bold"><?php echo $orderData['code'].' '.$orderData['user']['name'].' '.$orderData['user']['phone']; ?><br /> <?php echo $orderData['note']; ?></div>
                            </div>
                        </div>
                        <div class="box-info">
                            <h2 class="info-title">Thông Tin Người Nhận Hàng</h2>
                            <ul class="d-flex flex-column list-style-none m-0 p-0 gap-2">
                                <li class="d-flex align-items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <circle cx="11.9998" cy="7.2" r="3.2" stroke="#1C274C"></circle>
                                        <ellipse cx="11.9999" cy="16" rx="5.6" ry="3.2" stroke="#1C274C"></ellipse>
                                    </svg>
                                    <span><?php echo $orderData['user']['name']; ?></span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M11.23 5.85292L11.7492 6.78327C12.2177 7.62285 12.0296 8.72424 11.2917 9.46223C11.2917 9.46223 11.2917 9.46223 11.2917 9.46223C11.2916 9.46234 10.3966 10.3575 12.0195 11.9804C13.6417 13.6026 14.5368 12.709 14.5377 12.7082C14.5377 12.7082 14.5377 12.7082 14.5377 12.7082C15.2757 11.9702 16.3771 11.7822 17.2166 12.2507L18.147 12.7699C19.4148 13.4774 19.5645 15.2554 18.4501 16.3697C17.7805 17.0393 16.9602 17.5604 16.0534 17.5947C14.5269 17.6526 11.9345 17.2663 9.33406 14.6658C6.7336 12.0654 6.34727 9.47297 6.40514 7.94646C6.43952 7.03967 6.96054 6.21938 7.63015 5.54977C8.74451 4.43541 10.5224 4.58514 11.23 5.85292Z" stroke="#1C274C" stroke-linecap="round"></path>
                                    </svg>
                                    <span><?php echo $orderData['user']['phone']; ?></span>
                                </li>
                                <li class="d-flex align-items-start gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M5.6001 10.5146C5.6001 6.9167 8.46548 4 12.0001 4C15.5347 4 18.4001 6.9167 18.4001 10.5146C18.4001 14.0844 16.3574 18.2499 13.1704 19.7396C12.4275 20.0868 11.5727 20.0868 10.8298 19.7396C7.64276 18.2499 5.6001 14.0844 5.6001 10.5146Z" stroke="#1C274C"></path>
                                        <circle cx="12.0001" cy="10.4" r="2.4" stroke="#1C274C"></circle>
                                    </svg>
                                    <span><?php echo $orderData['user']['address_register']; ?></span>
                                </li>
                            </ul>
                        </div> <br>
                        <div class="btn-custom d-flex gap-2 align-items-center" id="btnOrderSuscess">
						
							<a href="/products" class="text-white">Mua tiếp đơn hàng mới</a>
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