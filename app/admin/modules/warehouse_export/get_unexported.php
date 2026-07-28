<?php
require_once 'inc_security.php';

$phone = trim(getValue('phone', 'str', 'GET', ''));
header('Content-Type: application/json; charset=utf-8');

if (!$phone) {
    echo json_encode(['items' => [], 'customer' => null]);
    die;
}

$phoneSafe = addslashes($phone);
$sql = "SELECT s.sae_id, s.sae_product_name, s.sae_quantity_ban, s.sae_unit_price,
               s.sae_total_ban, s.sae_lot_number, s.sae_exp_date,
               s.sae_customer_name, s.sae_customer_address,
               w.who_packaging_unit
        FROM sales_export s
        LEFT JOIN warehouse w ON s.sae_who_id = w.who_id
        WHERE s.sae_customer_phone = '{$phoneSafe}'
          AND (s.sae_export_slip_status = 0 OR s.sae_export_slip_status IS NULL)
        ORDER BY s.sae_id DESC";

$db = new db_query($sql);
$items = [];
$customer = null;
if ($db->result) {
    while ($r = mysqli_fetch_object($db->result)) {
        $items[] = [
            'sae_id'         => (int)$r->sae_id,
            'product_name'   => $r->sae_product_name,
            'quantity'       => (int)$r->sae_quantity_ban,
            'unit_price'     => (float)$r->sae_unit_price,
            'total'          => (float)$r->sae_total_ban,
            'lot'            => $r->sae_lot_number,
            'exp_date'       => $r->sae_exp_date ? date('d/m/Y', strtotime($r->sae_exp_date)) : '',
            'packaging_unit' => (int)$r->who_packaging_unit,
        ];
        if (!$customer) {
            $customer = [
                'name'    => $r->sae_customer_name,
                'address' => $r->sae_customer_address,
            ];
        }
    }
}
unset($db);

echo json_encode(['items' => $items, 'customer' => $customer]);
die;
