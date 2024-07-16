<?

use App\Models\Categories\Category;
use App\Models\Post;

require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */

$type = getValue('type', 'str', 'GET', '');
$type = strtoupper($type);
$pos_type = $type;
switch ($type) {
    case 'POLICY':
        $module_id = 6;
        break;
    case 'ORIENTATION':
        $module_id = 7;
        break;
    case 'NEWS':
        $module_id = 8;
        break;
    case 'VIDEO':
        $module_id = 9;
        break;
}

$module_name = "Bài viết";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$fs_table = "posts";
$id_field = "pos_id";
$name_field = "pos_title";
$break_page = "{---break---}";
$fs_filepath = ROOT . "/public/upload/posts/";
$fs_driver_filepath = ROOT . "/public/upload/files/";

$model = Post::class;

$per_page = 10;

$fs_fieldupload = "pos_image";
$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 2000;

$fs_driver_fieldupload = "pos_file_size";
$fs_driver_extension = "rar,zip,7z,docx,doc,pdf";
$fs_driver_filesize = 256000;

$locales = collect(config('app.locales'))->lists('code', 'name');

$products = \App\Models\Product::where('pro_active', '=', '1')->select_all();
$products = $products->lists('pro_id', 'pro_name_' . locale());

$pos_type = getValue('type', 'str', 'GET', '');
$pos_type = strtoupper($pos_type);

if ($pos_type == 'DRIVER') {
    $fs_redirect = 'listing_driver.php?type=DRIVER';
} else {
    $fs_redirect = 'listing.php?type=' . $pos_type ?? '';
}

$categories_arr = get_categories_for_select(0, '|___', $pos_type);


$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>