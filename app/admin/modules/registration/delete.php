<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';
$model = new App\Models\Registration();


$returnUrl = base64_decode(getValue("returnurl", "str", "GET", base64_encode("registration.php")));
$recordId = getValue("record_id", "int", "GET", 0);
if ($recordId) {
    $item = $model::findById($recordId);
    if ($item) {
        $item->delete();
        admin_log($admin_id, ADMIN_LOG_ACTION_DELETE, $recordId, 'Đã xóa email ' . $item->email);
    }
}

redirect(url_back());