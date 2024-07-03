<?

require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$module_id = 25;
$module_name = "Lịch sử quản trị";

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$model = \VatGia\Admin\Models\AdminLog::class;
$fs_table = "admin_logs";
$id_field = "adl_id";
$name_field = "adl_record_title";

$per_page = 10;

$actions = [
    '' => 'Tất cả',
    ADMIN_LOG_ACTION_ADD => 'Thêm mới',
    ADMIN_LOG_ACTION_EDIT => 'Sửa',
    ADMIN_LOG_ACTION_DELETE => 'Xóa',
    ADMIN_LOG_ACTION_ACTIVE => 'Thay đổi trạng thái'
];

$views = [
    dirname(__FILE__) . '/views',
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
?>