<?php
require_once 'inc_security.php';

$ids_str = getValue('ids', 'str', 'POST', '') ?: getValue('ids', 'str', 'GET', '');
$ids = array_filter(array_map('intval', explode(',', $ids_str)));

if (empty($ids)) {
    die('Không có sản phẩm nào được chọn');
}

$idsList = implode(',', $ids);
$sql = "SELECT s.*, w.who_packaging_unit
        FROM sales_export s
        LEFT JOIN warehouse w ON s.sae_who_id = w.who_id
        WHERE s.sae_id IN ({$idsList})
        ORDER BY s.sae_id ASC";

$db = new db_query($sql);
$items = [];
$customer = null;
if ($db->result) {
    while ($r = mysqli_fetch_object($db->result)) {
        $items[] = $r;
        if (!$customer) {
            $customer = (object)[
                'name'    => $r->sae_customer_name,
                'phone'   => $r->sae_customer_phone,
                'address' => $r->sae_customer_address,
                'note'    => $r->sae_note,
            ];
        }
    }
}
unset($db);

if (empty($items)) {
    die('Không tìm thấy sản phẩm');
}

// Đánh dấu đã xuất phiếu
$db_upd = new db_execute("UPDATE sales_export SET sae_export_slip_status = 1 WHERE sae_id IN ({$idsList})");
unset($db_upd);

$packaging_units_map = [0 => '', 1 => 'Chai', 2 => 'Hộp', 3 => 'Gói'];
$total = 0;
foreach ($items as $it) {
    $total += (float)$it->sae_total_ban;
}

$slip_no = 'PXK-' . date('Ymd') . '-' . $items[0]->sae_id;
?><!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Phiếu giao hàng kiêm phiếu xuất kho</title>
<style>
    @page { size: A4; margin: 0; }
    body { font-family: 'Times New Roman', serif; font-size: 13px; color: #000; margin: 0; padding: 20px; background: #e0e0e0; }
    #slip { background: #fff; max-width: 800px; margin: 0 auto; padding: 25px 30px; }
    @media print {
        body { background: #fff; padding: 0; margin: 0; }
        #slip { max-width: none; padding: 12mm; margin: 0; }
        .toolbar { display: none !important; }
    }
    .header h2 { margin: 0; font-size: 14px; }
    .header p { margin: 2px 0; font-size: 12px; }
    h1 { text-align: center; font-size: 18px; margin: 15px 0 5px; }
    .meta { text-align: center; margin-bottom: 15px; font-size: 12px; }
    .info p { margin: 6px 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    table th, table td { border: 1px solid #000; padding: 6px; font-size: 12px; }
    table th { background: #f0f0f0; text-align: center; }
    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .signatures { display: table; width: 100%; margin-top: 40px; text-align: center; }
    .signatures > div { display: table-cell; width: 20%; }
    .signatures strong { display: block; }
    .signatures em { display: block; font-size: 11px; font-style: italic; }
    .toolbar { max-width: 800px; margin: 0 auto 15px; text-align: right; }
    .toolbar button { padding: 8px 16px; margin-left: 6px; cursor: pointer; background: #337ab7; color: #fff; border: none; border-radius: 3px; font-size: 13px; }
    .toolbar button.gray { background: #888; }
</style>
</head>
<body>
<div class="toolbar">
    <button onclick="window.print()">🖨 In / Lưu PDF</button>
    <button class="gray" onclick="window.close()">Đóng</button>
</div>

<div id="slip">
    <div class="header">
        <h2>CÔNG TY CỔ PHẦN DỊCH VỤ THƯƠNG MẠI VÀ CÔNG NGHỆ DAK</h2>
        <p>Địa chỉ: FS - Goldseason - 47 Nguyễn Tuân - Thanh Xuân - TP Hà Nội</p>
        <p>Website: https://dakpharma.vn</p>
    </div>

    <h1>PHIẾU GIAO HÀNG KIÊM PHIẾU XUẤT KHO</h1>
    <div class="meta">
        <em>Ngày xuất kho: <?= date('d/m/Y') ?></em><br>
        Số: <?= htmlspecialchars($slip_no) ?>
    </div>

    <div class="info">
        <p>- Họ và tên người nhận hàng: <?= htmlspecialchars($customer->name ?? '') ?></p>
        <p>- Số điện thoại: <?= htmlspecialchars($customer->phone ?? '') ?></p>
        <p>- Địa chỉ nhận hàng: <?= htmlspecialchars($customer->address ?? '') ?></p>
        <p>- Ghi chú: <?= htmlspecialchars($customer->note ?? '') ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="4%">STT</th>
                <th>Sản phẩm</th>
                <th width="9%">Số lô</th>
                <th width="9%">Hạn dùng</th>
                <th width="6%">ĐVT</th>
                <th width="7%">Số lượng</th>
                <th width="11%">Đơn giá</th>
                <th width="11%">CP vận chuyển</th>
                <th width="13%">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($items as $it): ?>
            <tr>
                <td class="text-center"><?= $i++ ?></td>
                <td><?= htmlspecialchars($it->sae_product_name) ?></td>
                <td class="text-center"><?= htmlspecialchars($it->sae_lot_number) ?></td>
                <td class="text-center"><?= $it->sae_exp_date ? date('d/m/Y', strtotime($it->sae_exp_date)) : '' ?></td>
                <td class="text-center"><?= $packaging_units_map[(int)$it->who_packaging_unit] ?? '' ?></td>
                <td class="text-center"><?= number_format((int)$it->sae_quantity_ban) ?></td>
                <td class="text-right"><?= number_format((float)$it->sae_unit_price) ?></td>
                <td class="text-right"><?= number_format((float)$it->sae_other_cost) ?></td>
                <td class="text-right"><?= number_format((float)$it->sae_total_ban) ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="8" class="text-right"><strong>Cộng</strong></td>
                <td class="text-right"><strong><?= number_format($total) ?></strong></td>
            </tr>
        </tbody>
    </table>

    <p style="margin-top:15px;">- Tổng số tiền (viết bằng chữ): <strong><?= numberToVietnamese($total) ?></strong></p>
    <p>- Số chứng từ gốc kèm theo (nếu có): .........................</p>

    <div class="signatures">
        <div><strong>Người lập phiếu</strong><em>(Ký, họ tên)</em></div>
        <div><strong>Kế toán</strong><em>(Ký, họ tên)</em></div>
        <div><strong>Kho Phát</strong><em>(Ký, họ tên)</em></div>
        <div><strong>Nhân viên giao hàng</strong><em>(Ký, họ tên)</em></div>
        <div><strong>Khách hàng</strong><em>(Ký, họ tên)</em></div>
    </div>
</div>

<script>
// Tự động mở dialog in (user chọn "Save as PDF" để lưu)
document.title = '<?= $slip_no ?>';
window.addEventListener('load', function() {
    setTimeout(function() { window.print(); }, 500);
});
</script>

</body>
</html>
<?php die; ?>
