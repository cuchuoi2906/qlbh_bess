<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("delete");

$returnUrl = base64_decode(getValue("returnurl", "str", "GET", base64_encode("listing.php")));
$recordId = getValue("record_id", "int", "GET", 0);
if ($recordId) {
    $item = $model::findById($recordId);
    if ($item) {
        $item->delete();
        admin_log($admin_id, ADMIN_LOG_ACTION_DELETE, $recordId, 'Đã xóa sự kiện ' . $item->name);
    }
}

redirect(url_back());
?>