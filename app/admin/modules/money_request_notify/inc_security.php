<?
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 45;

$module_name = "Yêu cầu nạp tiền";

$type = getValue('type', 'int', 'GET', 0);
if ($type == 1) {
    $status = \App\Models\MoneyAddRequestNotify::$orderStatus;
} else {
    $status = \App\Models\MoneyAddRequestNotify::$status;
}

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$model = new \App\Models\MoneyAddRequestNotify();
$fs_table = $model->table;
$id_field = $model->prefix . "_id";
$name_field = "";
$break_page = "{---break---}";

$per_page = 10;

//$fs_fieldupload = "cat_picture";
$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;

$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>