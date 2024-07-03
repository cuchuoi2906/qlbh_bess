<?
require_once 'inc_security.php';

$id = getValue('id', 'int', 'POST', 0);
$item = \App\Models\MoneyAddRequestNotify::findByID($id);
if ($item) {
    $old_status = $item->status;
    $item->status = getValue('status', 'int', 'POST', 0);
    $item->admin_id = $admin_id;
    $item->update();

    admin_log($admin_id, ADMIN_LOG_ACTION_ACTIVE, $id, 'Đã thay đổi trạng thái yêu cầu nạp tiền (' . $id . ') từ ' . $old_status . ' thành ' . $item->status);
}
die;
?>
