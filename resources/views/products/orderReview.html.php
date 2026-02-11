<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
$classActive = "";
$status_code = $result['vars']['status_code'];
?>
<div class="main-content">
    <div class="container">
        <div id="section-5">
            <ul class="nav nav-pills mb-3 menu-prod" id="pills-tab" role="tablist">
                <li class="nav-item-menu" role="presentation">
                    <button  class="btn-menu-link <?php echo ($status_code == App\Models\Order::NEW) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::NEW; ?>">Đơn hàng mới</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link <?php echo ($status_code == App\Models\Order::PENDING) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::PENDING; ?>">Chờ xử lý</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link <?php echo ($status_code == App\Models\Order::BEING_TRANSPORTED) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::BEING_TRANSPORTED; ?>">Đang vận chuyển</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link <?php echo ($status_code == App\Models\Order::SUCCESS) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::SUCCESS; ?>">Thành Công</a></button>
                </li>
            </ul>
        </div>
        <div class="row">
            <?php
            if(check_array($result['vars'])){
            ?>
                <div class="col-xl-8">
            <?php 
            }else{
                ?>
                <div class="col-xl-12">
                <?php
            }?>    
                <?php
                if(check_array($result['vars'])){
                    $productsList = $result['vars']['products'];
                    $meta = $result['vars'];
                    unset($productsList['meta']);
                    $total_money = 0;
                    $total_money_origin = 0;
                    $arrProduct = [];
                    $i=0;
                    foreach($productsList as $items){
                        $arrProduct[$i]['stt'] = $i+1;
                        $arrProduct[$i]['name'] = $items['name'];
                        $arrProduct[$i]['quantity'] = $items['quantity'];
                        $arrProduct[$i]['sale_price'] = $items['sale_price'];
                        $arrProduct[$i]['price'] = $items['price'];
                        $arrProduct[$i]['avatar']['url'] = $items['avatar']['url'];
                        $i++;
                    }
                    usort($arrProduct, function ($a, $b) {
                        return strcmp($a['name'], $b['name']); // So sánh theo tên (tăng dần)
                    });
                    foreach($arrProduct as $items){
                        $product = $items;
                        $quantity = $items['quantity'];
                        $price_origin = $product['price'];
                        $price = $product['sale_price'];
                        $total_money += $quantity * $price;
                        $total_money_origin += $quantity * $price_origin;
                ?>
                    <div class="content-box mb-3">
                        <div class="cart-item d-flex align-items-start justify-content-between">
                            <div class="left d-flex align-items-center gap-3">
                                <div class="thumb">
                                    <img width="100" height="100" src="<?php echo $product['avatar']['url']; ?>" alt="prod-item-1" />
                                </div>
                                <div class="info d-flex flex-column">
                                    <h3 class="cart-item-title"><?php echo $product['name']; ?></h3>
                                    <div class="price"">Giá: <?php echo formatCurrencyVND($price); ?></div>
                                    <div>Số lượng: <?php echo $quantity; ?></div>
                                </div>
                            </div>
                            <div class="right h-100">
                                <div class="cta d-flex justify-content-start flex-column text-end h-100 align-items-end h-100">
                                    <div class="total-price"><?php echo formatCurrencyVND($price*$quantity); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php 
                    }
                }else{
                    ?>
                    <div class="imgCartNoProduct">
                        <img src="<?= asset('/images/cart-no-produc.svg') ?>" />
                    </div>
                    <div style="width: 100%;text-align: center;;">
                        <h6>Bạn chưa có Đơn hàng mới đặt!</h6>
                    </div>
                    <?php
                }?>
            </div>
            <div class="col-xl-4 mb-4">
                <?php
                if(check_array($result['vars'])){
                ?>
                <div class="content-box">
                    <h2 class="content-title">Thông tin đơn hàng</h2>
                    <ul class="d-flex flex-column gap-2 m-0 p-0">
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Tổng số lượng</div>
                            <div class="value fw-bold"><?php echo isset($meta['total_product']) ? $meta['total_product'] : 0; ?></div>
                        </li>
                        <li class="d-flex justify-content-between align-items-center">
                            <div class="label">Tổng tiền</div>
                            <div class="value fw-bold total"><?php echo isset($meta['total_money']) ? formatCurrencyVND($total_money) : 0; ?></div>
                        </li>
                    </ul>
                    <div class="btn-custom d-flex gap-2 align-items-center mt-2" onclick="orderByIdOld(<?php echo $result['vars']['id'] ?>)">
                        <span class="text-white">Đặt hàng lại</span>
                    </div>
                </div>
                <?php
                }?>
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>