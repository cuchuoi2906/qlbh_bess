<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

$module_id = 54;
$module_name = "Quản lý công nợ";

checkLogged();

if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);
}

$fs_table = "sales_export";
$id_field = "sae_id";
$name_field = "sae_product_name";

$per_page = 20;

// Trạng thái thanh toán
$payment_statuses = [
    'Chưa thanh toán'      => 'Chưa thanh toán',
    'Thanh toán một phần'  => 'Thanh toán một phần',
    'Đã thanh toán'        => 'Đã thanh toán',
];

$views = [
    dirname(__FILE__) . '/views',
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>
