<?php
include dirname(__FILE__) . '/../includes/headerhapu.html.php';
?>
<style>
    .table tr td{
        font-size: 11px !important;
    }
    .table tr th{
        font-size: 11px !important;
    }
</style>
<div class="main-content">
    <div class="row">
        <div class="text-center"><b>Mua hàng: <?php echo $result['vars'][0]['useradminhapu']['name']; ?></b></div>
        <?php
        if(check_array($result['vars'])){
        ?>
            <div class="col-xl-12">
        <?php 
        }else{
            ?>
            <div class="col-xl-12">
            <?php
        }?>    
            <?php 
            if(check_array($result['vars'])){
                ?>
                <div class="content-box mb-3 mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên NT-QT-PK</th>
                                <th scope="col">Nhà Xe</th>
                                <th scope="col">Giờ Chạy</th>
                                <th scope="col">Trạng thái nhặt hàng</th>
                                <th scope="col">Tổng tiền nhặt</th>
                            </tr>
                        </thead>
                    <tbody>
                <?php
                $orderList = $result['vars'];
                $i=1;
                $totalMoneyHapu = 0;
                $v_data_money_by_day = [];
                foreach($orderList as $items){
                    $order = $items;
                    $dateCreate = $order['created_at']->format('d-m-Y');
                    $status = 'Chưa xử lý';
                    $classStatus="bg-secondary";
                    if($order['status_process'] == 1){
                        $status = 'Xin duyệt giá';
                        $classStatus="bg-warning";
                    }
                    if($order['status_process'] == 2){
                        $status = 'Xác nhận và nhặt';
                        $classStatus="bg-success";
                    }
                    $orderId = $order['id'];
                    $useradminhapu = $order['useradminhapu'];
                    $products = $items['products'];
                    $products = $items['products'];
                    $totalMoneyNhap = 0;
                    foreach($products as $product){ 
                        $totalMoneyNhap += $product['price_hapu']*$product['quantity'];
                    }
                    $totalMoneyHapu +=$totalMoneyNhap;
                    $v_data_money_by_day[$dateCreate][$order['status_process']]['count']  = $v_data_money_by_day[$dateCreate][$order['status_process']]['count']+1;
                    $v_data_money_by_day[$dateCreate][$order['status_process']]['total_money']  = $v_data_money_by_day[$dateCreate][$order['status_process']]['total_money']+$totalMoneyNhap;
            ?>
                        
                            <tr>
                                <th scope="row"><?php echo $i; ?></th>
                                <td><?php echo $items['user']['name'];  ?><br /><span style="color: red;"><?php echo $order['created_at']->format('d-m-Y'); ?></span></td>
                                <td><?php echo $order['shipping_car'];  ?></td>
                                <td><?php echo $order['shipping_car_start'];  ?></td>
                                <td>
                                    <span class="<?php echo $classStatus; ?>" style="padding: 2px 10px 2px 10px;border-radius: 10px;">
                                        <a class="" href="/order-detail-<?php echo $orderId; ?>">
                                            <?php echo $status; ?>
                                        </a>
                                    </span>
                                </td>
                                <td>
                                    <?php echo number_format($totalMoneyNhap); ?>
                                </td>
                            </tr>
                        
            <?php
                $i++;
                }
                ?>
                    </tbody>
                    </table>
                </div>
                <div>
                    <div class="content-box mb-3 mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Ngày tháng</th>
                                <th scope="col" class="text-primary">Chưa xử lý</th>
                                <th scope="col" class="text-primary">Tổng tiền chưa xử lý</th>
                                <th scope="col" class="text-secondary">Xin duyệt giá</th>
                                <th scope="col" class="text-secondary">Tổng tiền Xin duyệt giá</th>
                                <th scope="col" class="text-danger">Xác nhận và nhặt</th>
                                <th scope="col" class="text-danger">Tổng tiền Xác nhận và nhặt</th>
                                <th scope="col">Tổng tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            foreach($v_data_money_by_day as $key=>$items){
                                ?>
                                    <tr>
                                        <td><?php echo $key; ?></td>
                                        <td class="text-primary"><?php echo intval($items[0]['count']); ?></td>
                                        <td class="text-primary"><?php echo format_number(intval($items[0]['total_money'])); ?></td>
                                        <td class="text-secondary"><?php echo intval($items[1]['count']); ?></td>
                                        <td class="text-secondary"><?php echo format_number(intval($items[1]['total_money'])); ?></td>
                                        <td class="text-danger"><?php echo intval($items[2]['count']); ?></td>
                                        <td class="text-danger"><?php echo format_number(intval($items[2]['total_money'])); ?></td>
                                        <td><?php echo format_number(intval($items[0]['total_money'])+intval($items[1]['total_money'])+intval($items[2]['total_money'])); ?></td>
                                    </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                <?php
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
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footerhapu.html.php';
?>