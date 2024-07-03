<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("delete");

$recordId = getValue("record_id", "int", "GET", 0);
if ($recordId) {
    $item = \App\Models\AdminUser::findByID($recordId);
    if ($item) {
        $item->delete();

        admin_log($admin_id, ADMIN_LOG_ACTION_DELETE, $recordId, 'Đã xóa tài khoản quản trị ' . $item->name);

    }
}

redirect(url_back());
?>