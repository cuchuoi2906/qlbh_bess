<?
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("edit");

$id = getValue('id', 'int', 'POST');
$field = getValue('field', 'str', 'POST');
$value = getValue('value', 'int', 'POST');
$item = $model::findByID($id);
if ($item) {
    $old_value = $item->$field;
    $item->$field = $value;
    $affected = $item->update();
    if ($affected) {
        admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $id, '(' . $module_name . ': ' . $id . ')Thay đổi giá trị trường ' . $field . ' từ ' . $old_value . ' thành ' . $value);
    }
}
?>