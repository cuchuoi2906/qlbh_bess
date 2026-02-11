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
                $arr_product_id= [];
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
                    $arr_product_id[] = $arrProduct[$key]['id'];
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
                                    <th scope="col" class="fixed-col-price">Check sản phẩm</th>
                                    <th scope="col" class="fixed-col-scoll">Ghi chú</th>
                                </tr>
                            </thead>
                    <?php
                                    
                    foreach($arrProduct as $items){
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
                        $i++;
                        if($items['check_hapu_status'] == 1){
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
                                    <td>
                                         <textarea class="form-control" id="check_hapu_note<?php echo $items['id']; ?>" name="check_hapu_note<?php echo $items['id']; ?>" rows="3"><?php echo $items['check_hapu_note']; ?></textarea>
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
                    <div class="btn-custom d-flex gap-2 align-items-center" style="width: 200px;" id="btnOrderProductCheckNoteUpdate">
                        <span class="text-white">Cập nhật</span>
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
    var productList = <?php echo json_encode($arr_product_id); ?>;
    const orderId = <?php echo $orderList['id']; ?>;
    document.getElementById('btnOrderProductCheckNoteUpdate').addEventListener('click', function () {
        var noteCount = 0;
        for(let i =0 ;i<productList.length;i++){
            let value = "";
            if(document.getElementById("check_hapu_note"+productList[i])){
                value = document.getElementById("check_hapu_note"+productList[i]).value;
            }
            if(value != ""){
                $.ajax({
                    url: '/order/update-check-product-order',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        order_product_id: parseInt(productList[i]),
                        status:0,
                        note:value
                    }),
                    success: function(response) {
                        if(response.code == 200){
                            noteCount = noteCount+1;
                        }
                    },
                    error: function(error) {
                    }
                });
            }
        }
        $.ajax({
            url: '/order/update-stock-status',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                status: 2,
                order_id: orderId
            }),
            success: function(response) {
            },
            error: function(error) {
            }
        });
        alert("cập nhật ghi chú thành công!");
        location.reload();
    });
    function updateCheckHapuProductOrderStatus(orderProductId){
        console.log(orderProductId);
        $.ajax({
            url: '/order/update-check-product-order',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                order_product_id: orderProductId,
                status:1
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
    document.addEventListener('DOMContentLoaded', () => {
        
    });
    function formatCurrencyVND(number) {
            // Định dạng số theo tiền tệ Việt Nam
            let formatted = new Intl.NumberFormat('vi-VN', {
                style: 'decimal',
                currency: 'VND'
            }).format(number);

            // Thay thế dấu . bằng dấu ,
            return formatted.replace(/\./g, ',');
        }
        function loadInputValueformatCurrency(inputElement,price,orderProId){
            let value = inputElement.value;
            value = value.replace(/\,/g, '');
            value = parseInt(value);
            if(isNaN(value)){
                alert("Giá bán phải là số nguyên dương >= 0");
                inputElement.value = "";
                return;
            }
            inputElement.value = formatCurrencyVND(value);
            checkStatusPrice(value,orderProId);
        }
        function checkStatusPrice(price,orderProId){
            let priceVuaDuoc = document.getElementById("hdn_price_vuaduoc"+orderProId).value;
            let txtStatus = "OK";
            $("#tr_row_price_hapu"+orderProId)[0].className = '';
            pricechenh = price - priceVuaDuoc;
            let textWarning = '';
            if(pricechenh >= 2000){
                txtStatus = "NOT OK";
                $("#tr_row_price_hapu"+orderProId).addClass("");
                textWarning = 'Quá cao';
            }
            if(pricechenh >=0 &&  pricechenh < 2000){
                $("#tr_row_price_hapu"+orderProId).addClass("");
                textWarning = 'Chưa tốt';
            }
            //document.getElementById("status_hapu"+orderProId).innerHTML = txtStatus;
            document.getElementById("warning_price_hapu"+orderProId).innerHTML = textWarning;
        }
        
</script>
<script>
    $(document).ready(function() {
        $(".clickable").click(function() {
            let imgSrc = $(this).attr("src");  // Lấy đường dẫn ảnh
            $("#fullImage").attr("src", imgSrc);
            $("#imageModal").css("display", "flex").hide().fadeIn();  // Hiển thị modal
        });

        $(".close, #imageModal").click(function() {
            $("#imageModal").fadeOut(); // Ẩn modal khi click
        });

        $(document).keydown(function(e) {
            if (e.key === "Escape") {
                $("#imageModal").fadeOut(); // Ẩn modal khi nhấn ESC
            }
        });
    });
    jQuery(document).ready(function() {
        if(window.innerWidth < 1024){
            var clonedTable = jQuery(".main-table").clone(true).addClass('clone');

            // Lặp qua từng <td> không có class .fixed-side và xóa name, id
            clonedTable.find("td").not(".fixed-side").each(function () {
                $(this).removeAttr("name").removeAttr("id");
            });

            // Append vào DOM sau khi đã xóa thuộc tính
            clonedTable.appendTo('#table-scroll');
        }

    });
</script>

<?php
include dirname(__FILE__) . '/../includes/footerhapu.html.php';
?>