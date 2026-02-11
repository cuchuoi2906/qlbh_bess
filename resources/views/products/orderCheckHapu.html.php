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
        <div class="text-center"><b>QC Kiểm tra và Soát hàng</b></div>
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
                                <th scope="col">PIC mua hàng</th>
                                <th scope="col">Trạng thái soát hàng</th>
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
                    $stock_check_status = $order['stock_check_status'];
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
                        <td>
                            <a class="" href="/order-detail-<?php echo $orderId; ?>" style="color: black;">
                                <?php echo $items['user']['name'];  ?><br /><span style="color: red;"><?php echo $order['created_at']->format('d-m-Y'); ?></span>
                            </a>
                        </td>
                        <td><?php echo $order['shipping_car'];  ?></td>
                        <td><?php echo $order['shipping_car_start'];  ?></td>
                        <td>
                            <b><?php echo $useradminhapu['name']; ?></b>
                        </td>
                        <td>
                            <?php 
                            $bgcolor = "";
                            if($stock_check_status == 1){
                                $bgcolor = "background-color: #ED7D31;";
                            }
                            if($stock_check_status == 2){
                                $bgcolor = "background-color: red;";
                            }
                            if($stock_check_status == 3){
                                $bgcolor = "background-color: #00B050;";
                            }
                            ?>
                            <select style="<?php echo $bgcolor ?>" <?php echo ($stock_check_status == 3) ? "disabled" : ""; ?> name="stock_check_status" id="stock_check_status" data-id="<?php echo $orderId; ?>">
                                <option value="0" <?php echo ($stock_check_status == 0) ? "selected" :""; ?>>Chưa soát hàng</option>
                                <option value="1" <?php echo ($stock_check_status == 1) ? "selected" :""; ?>>Đang soát hàng</option>
                                <option value="2" <?php echo ($stock_check_status == 2) ? "selected" :""; ?>>Hàng sai/thiếu</option>
                                <?php 
                                if($stock_check_status == 3){
                                    echo '<option value="3" selected>Đóng hàng</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>
                        
            <?php
                $i++;
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
    <script>
document.getElementById('stock_check_status').addEventListener('change', function () {
    const status = this.value;
    const orderId = this.dataset.id;
    
    if (!status) {
        alert("Bạn phải chọn trạng thái");
       return;
    }
    $.ajax({
        url: '/order/update-stock-status',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            status: status,
            order_id: orderId
        }),
        success: function(response) {
            console.log(response);
            if(response.code == 200){
                alert("Đã đổi thành công trạng thái soát hàng!.");
                location.reload();
            }else{
                alert(response.error);
                location.reload();
            }
        },
        error: function(error) {
            console.error('Error:', error);
            location.reload();
        }
    });
});
</script>
<?php
include dirname(__FILE__) . '/../includes/footerhapu.html.php';
?>