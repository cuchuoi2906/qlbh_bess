<?php
require_once 'inc_security.php';
checkAddEdit("delete");

$returnUrl = base64_decode(getValue("returnurl", "str", "GET", base64_encode("listing.php")));
$recordId = getValue("record_id", "int", "GET", 0);

if ($recordId) {
    $record = \App\Models\SalesExport::findById($recordId);
    if ($record) {
        admin_log($admin_id, ADMIN_LOG_ACTION_DELETE, $recordId, 'Đã xóa phiếu xuất kho ID: ' . $recordId);
        $record->delete();
    }
}

redirect($returnUrl);
?>
