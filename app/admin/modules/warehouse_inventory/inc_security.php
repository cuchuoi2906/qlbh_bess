<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

$module_id = 53;
$module_name = "Quản lý tồn kho";

checkLogged();

if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);
}

$fs_table = "warehouse";
$id_field = "who_id";
$name_field = "who_product_name";

$per_page = 20;

// Đơn vị tính (dùng chung với nhập kho)
$packaging_units = [
    0 => '',
    1 => 'Chai',
    2 => 'Hộp',
    3 => 'Gói',
];

$views = [
    dirname(__FILE__) . '/views',
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>
