<?
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 11;

$module_name = "Thông báo";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$fs_table = "notification";
$id_field = "not_id";
$name_field = "not_content";
$break_page = "{---break---}";
$fs_filepath = ROOT . "/public/upload/";

$per_page = 10;

$arr_type = array(
    'SYSTEM' => 'Hệ thống',
    'COMMISSION' => 'Hoa hồng',
);

$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>