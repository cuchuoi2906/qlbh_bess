<?
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 11;

$module_name = "Đại lý";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$fs_table = "agency";
$id_field = "agc_id";
$name_field = "agc_name";
$break_page = "{---break---}";
$fs_filepath = ROOT . "/public/upload/";

$per_page = 10;

$fs_fieldupload = "cat_picture";
$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;

$cities = \App\Models\Cities::where('cit_parent_id','=',0)->select_all();
$cities = $cities->lists('cit_id', 'cit_name');


$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>