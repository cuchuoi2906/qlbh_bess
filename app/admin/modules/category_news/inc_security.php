<?

use App\Models\CategoryNews;

require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$type = getValue('type', 'str', 'GET', '');
$type = strtoupper($type);

$module_name = "Danh mục";

//Check user login...
checkLogged();

//dd($type);
$fs_redirect = 'listing.php?type=' . $type ?? '';
//Declare prameter when insert data
$fs_table = "category_news";
$id_field = "cat_id";
$name_field = "cat_name";
$break_page = "{---break---}";
$fs_filepath = ROOT . "/public/upload/category_news/";

$per_page = 20;

$fs_fieldupload = "cat_icon";
$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;
//
//$types = [
//    'PRODUCT' => 'PRODUCT',
//    'ABOUT' => 'ABOUT',
//    'BLOG' => 'BLOG',
//    'COMMUNITY' => 'COMMUNITY'
//];

$locales = collect(config('app.locales'))->lists('code', 'name');
//dd($locales);
$sqlString = '1';
if ($type) {
    $sqlString .= ' AND cat_type = \'' . $type . '\'';
}


$parents = CategoryNews::where($sqlString)->where('cat_parent_id = 0')->all();
$parent_options = [];
foreach ($parents as &$parent) {
    $parent->{'name_' . locale()} = '|__' . $parent->{'name_' . locale()};
    $parent_options[$parent->id] = $parent->{'name_' . locale()};
    foreach ($parent->childs as &$child) {
        $child->{'name_' . locale()} = '|__|__' . $child->{'name_' . locale()};
        $parent_options[$child->id] = $child->{'name_' . locale()};
    }
}

$parent_options[0] = 'Danh mục gốc';
ksort($parent_options);
$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>