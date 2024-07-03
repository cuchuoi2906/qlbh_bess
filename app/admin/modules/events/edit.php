<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");
$myform = new generate_form();

//$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin sự kiện";
$fs_action = getURL();
$fs_errorMsg = "";


$evt_name = getValue('evt_name', 'str', 'POST', '');
$evt_active = getValue('evt_active', 'str', 'POST', 1);
$evt_note = getValue('evt_note', 'str', 'POST', '');
$evt_start_time = getValue('evt_start_time', 'str', 'POST', '');
if ($evt_start_time) {
    $evt_start_time = strtotime($evt_start_time);
    if ($evt_start_time < strtotime('Today')) {
        $fs_errorMsg .= 'Ngày bắt đầu không hợp lệ';
    }
}
$evt_end_time = getValue('evt_end_time', 'str', 'POST', '');
if ($evt_end_time) {
    $evt_end_time = strtotime($evt_end_time) + 86399;
    if ($evt_end_time < strtotime('Today')) {
        $fs_errorMsg .= 'Ngày kết thúc không hợp lệ';
    }
    if ($evt_end_time < $evt_start_time) {
        $fs_errorMsg .= 'Ngày kết thúc phải lớn hơn ngày bắt đầu';
    }
}

//Check start time end time
$check_start_time = $model::where('evt_start_time', '<', $evt_start_time)
    ->where('evt_end_time', '>', $evt_start_time)
    ->where('evt_active', '>', 0)
    ->count();
$check_end_time = $model::where('evt_start_time', '<', $evt_end_time)
    ->where('evt_end_time', '>', $evt_end_time)
    ->where('evt_active', '>', 0)
    ->count();
if ($check_start_time || $check_end_time) {
    $fs_errorMsg .= 'Thời gian bạn chọn đã có sự kiện khác';
}

$evt_direct_commission_ratio = getValue('evt_direct_commission_ratio', 'flo', 'POST', 1);
if ($evt_direct_commission_ratio < 1) {
    $fs_errorMsg .= 'Tỷ lệ hoa hồng trực tiếp phải lớn hơn hoặc bằng 1';
}

$evt_parent_commission_ratio = getValue('evt_parent_commission_ratio', 'flo', 'POST', 1);
if ($evt_parent_commission_ratio < 1) {
    $fs_errorMsg .= 'Tỷ lệ hoa hồng cấp trên phải lớn hơn hoặc bằng 1';
}

$myform->add('evt_name', 'evt_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $evt_name ?? '', 1, 'Tên sự kiện không được để trống');
$myform->add('evt_active', 'evt_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 1);
$myform->add('evt_note', 'evt_note', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $evt_note ?? '');
$myform->add('evt_start_time', 'evt_start_time', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $evt_start_time ?? time(), 1, 'Thời gian bắt đầu không được để trống');
$myform->add('evt_end_time', 'evt_end_time', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $evt_end_time ?? 0);
$myform->add('evt_direct_commission_ratio', 'evt_direct_commission_ratio', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $evt_direct_commission_ratio ?? 1);
$myform->add('evt_parent_commission_ratio', 'evt_parent_commission_ratio', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $evt_direct_commission_ratio ?? 1);

$myform->addTable($fs_table);
$myform->evaluate();
$action = getValue("action", "str", "POST", "");

//Check $action for insert new data
if ($action == "execute") {
    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();
        if ($fs_errorMsg == '') {
            $sqlUpdate = $myform->generate_update_SQL($id_field, $record_id);
            $db_excute = new db_execute($sqlUpdate);

            admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $record_id, 'Đã sửa sự kiện ' . $evt_name);

            unset($db_excute);
            \AppView\Helpers\Facades\FlashMessage::success('Cập nhật thành công', url_back());
//            redirect('edit.php?record_id=' . $record_id);
        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")

//lay du lieu cua record can sua doi
$item = $model::findByID($record_id);
if ($item) {
    foreach ($item->toArray(false) as $key => $value) {
        if ($key != 'lang_id' && $key != 'admin_id') $$key = $value;
    }
} else {
    exit();
}
//dd($row);
unset($db_data);

echo $blade->view()->make('edit', get_defined_vars())->render();
return;
?>