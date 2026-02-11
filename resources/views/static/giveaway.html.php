<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
?>
<div class="main-content">
    <div class="container">
        <div id="section-5" class="pb-0">
            <ul class="nav nav-pills mb-3 menu-prod" id="pills-tab" role="tablist">
                <li class="nav-item-menu" role="presentation">
                    <button  class="btn-menu-link"><a href="/products/ORDERFAST-0">Sản phẩm</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link">
                        <a href="/order-fast">
                            Đặt hàng nhanh
                            <img src="<?= asset('/images/icon_new.gif') ?>" width="40" style="padding-left:3px" />
                        </a>
                    </button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link"><a href="/products/ORDERFAST-0/244">Sản phẩm độc quyền</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link active"><a href="/doanh-so-tich-luy">Quà tặng tháng</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link"><a href="/doanh-so-tich-luy">Doanh số tích lũy</a></button>
                </li>
            </ul>
        </div>
		<div class="row">
			<div class="col-xl-7">
				<img class="w-100 mobile-login d-none" src="<?= asset('/images/qua-tang-thang/mobile_img_1.jpg') ?>" style="border-radius: 7px" alt="login" />
				<img class="w-100 mobile-login d-none mt-1" src="<?= asset('/images/qua-tang-thang/mobile_img_2.jpg') ?>" style="border-radius: 7px" alt="login" />
			</div>
		</div>
        <div class="login-page" style="border-radius: 7px;">
            <div class="row">
                <div class="col-xl-6 pe-1">
                    <img class="w-100" src="<?= asset('/images/qua-tang-thang/pc_img_1.jpg') ?>" style="border-radius: 7px" alt="login" />
                </div>
                <div class="col-xl-6 ps-1">
                    <img class="w-100" src="<?= asset('/images/qua-tang-thang/pc_img_2.jpg') ?>" style="border-radius: 7px" alt="login" />
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>