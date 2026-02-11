<?php
include dirname(__FILE__) . '/../includes/headerhapu.html.php';
?>
<style>
    /* CSS cho modal */
    #imageModal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.8);
        justify-content: center;
        align-items: center;
    }

    #imageModal img {
        max-width: 90%;
        max-height: 90%;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.5);
    }

    #imageModal .close {
        position: absolute;
        top: 20px;
        right: 30px;
        font-size: 35px;
        color: white;
        cursor: pointer;
        font-weight: bold;
    }
    .table-responsive {
        overflow-x: auto; /* Bảng có thể cuộn ngang */
        -webkit-overflow-scrolling: touch; /* Hỗ trợ cuộn mượt trên iOS */
    }

    .fixed-col {
        min-width: 100px;
        max-width: 400px; /* Đảm bảo các cột cuối không bị thu nhỏ */
        word-wrap: break-word; /* Ngắt dòng khi cần */
        white-space: normal; /* Cho phép xuống dòng */
        overflow-wrap: break-word;
    }
    .fixed-col-price {
        min-width: 130px;
        max-width: 400px;
    }
    .fixed-col-scoll {
        min-width: 100px;
        max-width: 400px;
    }
    
    
    .table-scroll {
        position:relative;
        margin:auto;
        overflow:hidden;
    }
    .table-wrap {
        width:100%;
        overflow:auto;
    }
    .table-scroll table {
        width:100%;
        margin:auto;
        border-collapse:separate;
        border-spacing:0;
    }
    .table-scroll th, .table-scroll td {
        padding:5px 10px;
        background:#fff;
        vertical-align:top;
    }
    .table-scroll thead, .table-scroll tfoot {
        background:#f9f9f9;
    }
    .clone {
        position:absolute;
        top:0;
        left:0;
        pointer-events:none;
    }
    .clone th, .clone td {
        visibility:hidden
    }
    .clone td, .clone th {
        border-color:transparent
    }
    .clone tbody th {
        visibility:visible;
        color:red;
    }
    .clone .fixed-side {
        visibility:visible;
    }
    .content-box{
        padding-left: 5px;
        padding-right:5px;
    }
    .clone thead, .clone tfoot{background:transparent;}
</style>
<style>
    .table tr td{
        font-size: 13px !important;
    }
    .table tr th{
        font-size: 13px !important;
    }
</style>
<div class="main-content">
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
        }?>    
            <?php 
            if(check_array($result['vars'])){
                $orderList = $result['vars'];
                $products = $orderList['products'];
                $i=0;
                $total_gia_nhap = 0;
                $total_checking = 0;
                $total_checked = 0;
                $arrProduct = [];
                foreach($products as $key => $items){
                    $arrProduct[$key]['quantity'] = $items['quantity'];
                    $arrProduct[$key]['sale_price'] = $items['sale_price'];
                    $arrProduct[$key]['price_hapu'] = $items['price_hapu'];
                    $arrProduct[$key]['id'] = $items['id'];
                    $arrProduct[$key]['name'] = $items['name'];
                    $arrProduct[$key]['supplier_hapu'] = $items['supplier_hapu'];
                    $arrProduct[$key]['pharmacy_hapu'] = $items['pharmacy_hapu'];
                    $arrProduct[$key]['avatar'] = $items['avatar'];
                    $arrProduct[$key]['note_hapu'] = $items['note_hapu'];
                    $arrProduct[$key]['check_hapu_note'] = $items['check_hapu_note'];
                    $arrProduct[$key]['check_hapu_status'] = $items['check_hapu_status'];
                    if($items['check_hapu_status'] == 1){
                        $total_checked++;
                    }else{
                        $total_checking++;
                    }
                }
                usort($arrProduct, function ($a, $b) {
                    return strcmp($a['name'], $b['name']); // So sánh theo tên (tăng dần)
                });
                ?>
                <br />
                <div class="text-center"><b><?php echo $orderList['user']['name']; ?> - <?php echo $orderList['useradminhapu']['name']; ?></b></div>
                <div id="section-5" class="pb-0">
                    <ul class="nav nav-pills mb-3 menu-prod" id="pills-tab" role="tablist" style=" display: flex;justify-content: center;">
                        <li class="nav-item-menu" role="presentation">
                            <button class="btn-menu-link" style="background-color: #ED7D31;"><a href="/order-detail-<?php echo $orderList['id']; ?>">SOÁT HÀNG - THỰC HIỆN (<?php echo $total_checking; ?>)</a></button>
                        </li>
                        <li class="nav-item-menu" role="presentation">
                            <button class="btn-menu-link" style="background-color: #00B050;"><a href="/order-product-detail-checked-<?php echo $orderList['id']; ?>">SOÁT HÀNG - HOÀN THÀNH (<?php echo $total_checked; ?>)</a></button>
                        </li>
                    </ul>
                </div>
                <div class="content-box mb-3 mt-3">
                    <div id="table-scroll" class="table-scroll">
                        <div class="table-wrap">
                        <input type="hidden" name="order_id" id="order_id" value="<?php echo $idOrder; ?>" />
                        <table class="table table-hover main-table">
                            <thead>
                                <tr>
                                    <th scope="col" class="fixed-side">#</th>
                                    <th scope="col" class="fixed-side">Ảnh</th>
                                    <th scope="col" class="fixed-side fixed-col">Tên sản phẩm</th>
                                    <th scope="col" class="fixed-side">SL</th>
                                    <th scope="col" class="fixed-side">Soát hàng lại</th>
                                </tr>
                            </thead>
                    <?php
                                    
                    foreach($arrProduct as $items){
                        $i++;
                        $product = $items;
                        $quantity = $items['quantity'];
                        $sale_price = $product['sale_price'];
                        $pricechenh = $items['price_hapu'] - $sale_price;
                        $txtStatus = "OK";
                        $textWarning = '';
                        if($pricechenh >= 2000){
                            $txtStatus = "NOT OK";
                            $textWarning = 'Quá cao';
                        }
                        if($pricechenh >=0 &&  $pricechenh < 2000){
                            $textWarning = 'Chưa tốt';
                        }
                        if(!$quantity){$items['price_hapu'] = 0;}
                        $total_gia_nhap +=  $quantity* $items['price_hapu'];
                        if($items['check_hapu_status'] == 0){
                            continue;
                        }
                        
                ?>
                            <tbody>
                                <tr id="tr_row_price_hapu<?php echo $items['id']; ?>" class="<?php echo $classTr; ?>">
                                    <td class="fixed-side"><?php echo $i; ?></td>
                                    <td class="fixed-side">
                                        <input type="hidden" value="<?php echo $sale_price; ?>" id="hdn_price_vuaduoc<?php echo $items['id']; ?>" />
                                        <img class="clickable" width="100" height="100" src="<?php echo $product['avatar']['url']; ?>" alt="prod-item-1" />
                                    </td>
                                    <td class="fixed-side fixed-col"><?php echo $items['name']; ?></td>
                                    <td class="fixed-side" style="padding-top:12px;"><b><?php echo $quantity; ?></b></td>
                                    <td class="fixed-col-price">
                                        <input type="checkbox" onchange="updateCheckHapuProductOrderStatus(<?php echo $items['id']; ?>)" value="1" name="check_hapu_status<?php echo $items['id']; ?>" id="check_hapu_status<?php echo $items['id']; ?>" />
                                    </td>
                                </tr>
                            </tbody>
                <?php
                    }
                    ?>
                    </table>
                    </div>
                 </div>
                <div class="d-flex mt-3">
                    <div class="btn-custom d-flex gap-2 align-items-center" style="width: 200px;" id="btnOrderCheckedUpdate">
                        <span class="text-white">Đóng Hàng</span>
                    </div>
                </div>
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
<div id="imageModal">
    <span class="close">&times;</span>
    <img id="fullImage" src="">
</div>
<script>
document.getElementById('btnOrderCheckedUpdate').addEventListener('click', function () {
    const status = 3;
    const orderId = <?php echo $orderList['id']; ?>;
    
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
function updateCheckHapuProductOrderStatus(orderProductId){
    console.log(orderProductId);
    $.ajax({
        url: '/order/update-check-product-order',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({
            order_product_id: orderProductId,
            status:0
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
}
</script>

<?php
include dirname(__FILE__) . '/../includes/footerhapu.html.php';
?>