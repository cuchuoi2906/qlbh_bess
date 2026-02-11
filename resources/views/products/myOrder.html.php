<?php
include dirname(__FILE__) . '/../includes/header2.html.php';
$classActive = "";
$status_code = getValue('ord_status_code', 'str', 'GET', "");
?>
<div class="main-content">
    <div class="container">
        <div id="section-5">
            <ul class="nav nav-pills mb-3 menu-prod" id="pills-tab" role="tablist">
                <li class="nav-item-menu" role="presentation">
                    <button  class="btn-menu-link <?php echo ($status_code == App\Models\Order::NEW) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::NEW; ?>">Đơn hàng mới (<?php echo $totalNew; ?>)</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link <?php echo ($status_code == App\Models\Order::PENDING) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::PENDING; ?>">Chờ xử lý (<?php echo $totalPending; ?>)</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link <?php echo ($status_code == App\Models\Order::BEING_TRANSPORTED) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::BEING_TRANSPORTED; ?>">Đang vận chuyển (<?php echo $totalTransported; ?>)</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link <?php echo ($status_code == App\Models\Order::SUCCESS) ? "active" : ""; ?>"><a href="/my-order?ord_status_code=<?php echo App\Models\Order::SUCCESS; ?>">Thành Công (<?php echo $totalSucess; ?>)</a></button>
                </li>
            </ul>
        </div>
        <div class="row">
            <?php
            if(check_array($result['vars'])){
            ?>
                <div class="col-xl-12">
            <?php 
            }else{
                ?>
                <div class="col-xl-12">
                <?php
            }
            if(check_array($result['vars'])){
            ?>
                <div class="content-box mb-3 mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Thời gian</th>
                                <th scope="col">Chi tiết đơn</th>
                                <!--<th scope="col">Mã Đơn hàng</th>
                                <th scope="col">Trạng Thái</th>-->
                            </tr>
                        </thead>
                    <tbody>
                <?php
                    $orderList = $result['vars']['data'];
                    //pre($orderList[0]);
                    foreach($orderList as $items){
                        $order = $items;
                        $dateCreate = $order['created_at']->format('d-m-Y');
                        $dateCreate_hource = $order['created_at']->format('H:i:s');
                        $brDate = (isMobileDevice()) ? '<br />' : " ";
                        $fontSize = (isMobileDevice()) ? 'font-size: 15px;' : " ";
                ?>
                        <tr>
                            <td style="padding-left: 0px;<?php echo $fontSize; ?>">
                                <a class="text-primary" href="/my-order-detail/<?php echo $order['id'] ?>" alt="Đơn hàng"><?php echo $dateCreate_hource.$brDate.$dateCreate; ?></a>
                            </td>
                            <td>
                                <?php echo number_format($order['amount']); ?>đ<br />
                                Sản phẩm: <?php echo count($order['products']); ?> - Tổng SL: <?php echo $order['total_product']; ?>
                            </td>
                            <!--<td><a class="text-primary" href="/my-order-detail/<?php echo $order['id'] ?>" alt="Đơn hàng"><?php echo $order['code'] ?></a></td>
                            <td>
                                <?php echo $order['status']; ?>
                            </td>-->
                        </tr>
                <?php 
                    }
                    ?>
                        </tbody>
                     </table>
                    <div class="grid-bottom d-flex justify-content-center align-items-center">
                        <?php
                        if($pagination){
                            echo renderPagination($pagination);
                        }
                        ?>
                    </div>
               </div>
                    <?php
                }else{
                    ?>
                    <div class="imgCartNoProduct">
                        <img src="<?= asset('/images/cart-no-produc.svg') ?>" />
                    </div>
                    <div style="width: 100%;text-align: center;;">
                        <h6>Bạn chưa có Đơn hàng nào!</h6>
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