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
                    <button class="btn-menu-link active">
                        <a href="/order-fast">
                            Đặt hàng nhanh
                            <img src="<?= asset('/images/icon_new.gif') ?>" width="40" style="padding-left:3px" />
                        </a>
                    </button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link"><a href="/qua-tang-thang">Quà tặng tháng</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link"><a href="/doanh-so-tich-luy">Doanh số tích lũy</a></button>
                </li>
            </ul>
        </div>
        <div class="checkout">
            <div class="checkout-content">
                <div class="imgCartNoProduct">
                    <img src="<?= asset('/images/bg_thanks.jpg') ?>" />
                </div>
                <div style="width: 100%;text-align: center;;">
                    <h6>Cảm ơn quý khách đã đặt hàng!</h6>
                    <p>Bộ phận chăm sóc khách hàng sẽ kiểm tra đơn và thông báo lại!</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>