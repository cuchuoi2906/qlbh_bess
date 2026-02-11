<?php
include dirname(__FILE__) . '/../includes/header2.html.php';

$labels = array_keys($monthlyTotals); // ['2024-12']
$data = array_values($monthlyTotals); // [1442500]

// Chuyển thành JSON để truyền sang JavaScript
$labelsJson = json_encode($labels);
$dataJson = json_encode($data);

?>
<div class="main-content">
    <div class="container mb-5">
        <div id="section-5" class="pb-0">
            <ul class="nav nav-pills mb-3 menu-prod" id="pills-tab" role="tablist">
                <li class="nav-item-menu" role="presentation">
                    <button class="btn-menu-link"><a href="/products/ORDERFAST-0">Sản phẩm</a></button>
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
                    <button class="btn-menu-link"><a href="/qua-tang-thang">Quà tặng tháng</a></button>
                </li>
                <li class="nav-item-menu" role="presentation">
                    <button class="active btn-menu-link"><a href="/doanh-so-tich-luy">Doanh số tích lũy</a></button>
                </li>
            </ul>
            
            <!--<div class="d-flex align-items-center gap-2v">
                <button><a href="/products/ORDERFAST-0">Tất cả sản phẩm</a></button>
                <button>
                    <a href="/order-fast">
                        Đặt hàng nhanh
                        <img src="<?= asset('/images/icon_new.gif') ?>" width="40" style="padding-left:3px" />
                    </a>
                </button>
                <button class="active"><a href="/doanh-so-tich-luy">Doanh số tích lũy</a></button>
                <!--<button><a href="/products/FUNCTIONIAL-0">Thực Phẩm Chức Năng</a></button>
                <button><a href="/products/COSMECEUTICALS-0">Hóa Mỹ phẩm</a></button>
                <button><a href="/products/PERSONALCARE-0">Chăm Sóc Cá Nhân</a></button>
                <button><a href="/products/PRODUCTCOMPANY-0">TPCN NichieiAsia</a></button>
                <button><a href="/products/MEDICALDEVICES-0">Thiết Bị Y Tế</a></button>
            </div>-->
        </div>
        <div class="card border-0 mt-4">
            <div class="text-center">
                <h6 class="mb-0">Tổng số tiền trong 12 tháng: <?php echo $total_money; ?> đ</h6>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-xl-8">
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <canvas id="myChart" width="400" height="200"></canvas>
                <script>
                    const labels = <?php echo $labelsJson; ?>;
                    const data = <?php echo $dataJson; ?>;

                    const ctx = document.getElementById('myChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar', // Hoặc 'line', 'pie', ...
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Tổng số tiền trong tháng',
                                data: data,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                </script>
            </div>
            <div class="col-xl-12">
                <div class="card border-0 mt-4">
                    <div class="text-center">
                        <h6 class="mb-0 text-danger">🎁 TẶNG CHUYẾN DU LỊCH 🎁</h6><br />
                    </div>
                </div>
            </div>
            <div class="col-xl-6 img-hover-cover">
                <img class="w-100 mt-2" src="<?= asset('/images/tich-luy/img_1.jpg') ?>" alt="login" style="border-radius: 10px;" />
                <img class="w-100 mt-4 mb-3" src="<?= asset('/images/tich-luy/img_2.jpg') ?>" style="border-radius: 10px;" alt="login" />
            </div>
            <div class="col-xl-6 img-hover-cover">
                <img class="w-100 mt-2" src="<?= asset('/images/tich-luy/img_3.jpg') ?>" style="border-radius: 10px;" alt="login" />
                <img class="w-100 mt-4" src="<?= asset('/images/tich-luy/img_4.jpg') ?>" style="border-radius: 10px;" alt="login" />
            </div>
        </div>
    </div>
</div>
<?php
include dirname(__FILE__) . '/../includes/footer.html.php';
?>