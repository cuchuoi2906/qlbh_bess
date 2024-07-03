<?

use App\Models\Categories\Category;

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
$model = \App\Models\Event::class;
$fs_table = "events";
$id_field = "evt_id";
$name_field = "evt_name";
$break_page = "{---break---}";

$per_page = 10;
$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>