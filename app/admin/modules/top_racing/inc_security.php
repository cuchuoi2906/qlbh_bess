<?

require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 40;

$module_name = "Sự kiện";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}
//dd($type);
$fs_redirect = 'listing.php';
//Declare prameter when insert data
$model = \App\Models\TopRacingCampaign::class;
$modelNew = new \App\Models\TopRacingCampaign();
$fs_table = $modelNew->table;
$id_field = $modelNew->prefix . '_id';
$name_field = $modelNew->prefix . '_title';
$break_page = "{---break---}";
$editor_path = '../../resource/ckeditor/';

$per_page = 10;

$products = \App\Models\Product::where('pro_active', 1)->all();
$products = $products->lists('pro_id', 'pro_name_vn');


$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>