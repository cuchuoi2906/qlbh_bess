<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 4;

$module_name = "Quản lý sản phẩm";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$fs_table = "products";
$id_field = "pro_id";
$name_field = "pro_name";
$break_page = "{---break---}";
$fs_filepath = ROOT . "/public/upload/products/";

$per_page = 100;

//$fs_fieldupload = "cat_picture";
$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;

$fs_extension_video = "wmv,flv,mp4,3gp";
$fs_filesize_video = 20*1024*1024; # 30MB
$fs_filepath_video = ROOT . "/public/upload/products/";


$locales = collect(config('app.locales'))->lists('code', 'name');


$categories = get_categories_for_select(0, '', 'PRODUCT');

$commission_plans = \App\Models\CommissionPlan::all();
$commission_plans->map(function ($item) {
    $item->cpl_plan_name = $item->cpl_plan_name . ' (' . $item->cpl_commission_percent . '%) ';

    return $item;
});
$commission_plans = $commission_plans->lists('cpl_id', 'cpl_plan_name');
$commission_plans[0] = 'Không chiết khấu hoa hồng trực tiếp';
ksort($commission_plans);

$brands = App\Models\Categories\Category::where('cat_type', 'BRAND')
    ->all();

$brands = $brands->lists('cat_id', 'cat_name_vn');

$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>