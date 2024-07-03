<?
require_once 'inc_security.php';
checkAddEdit("add");

$myform = new generate_form();

//Khai báo biến khi thêm mới
$add = "add.php";
$listing = "listing.php";
$after_save_data = getValue("after_save_data", "str", "POST", $add);
$fs_title = translate("Thêm mới sự kiện");
$fs_action = getURL();
$fs_redirect = $after_save_data;
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
    if ($evt_end_time < time()) {
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
$myform->add('evt_end_time', 'evt_end_time', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $evt_start_time ?? 0);
$myform->add('evt_direct_commission_ratio', 'evt_direct_commission_ratio', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $evt_direct_commission_ratio ?? 1);
$myform->add('evt_parent_commission_ratio', 'evt_parent_commission_ratio', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $evt_direct_commission_ratio ?? 1);

$myform->add('evt_admin_id', 'admin_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $admin_id ?? 0);


$myform->addTable($fs_table);
$myform->evaluate();
$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();
    if ($fs_errorMsg == '') {
        $db_excute = new db_execute_return();
        $id = $db_excute->db_execute($myform->generate_insert_SQL());
        unset($db_excute);
        if ($id) {

            admin_log($admin_id, ADMIN_LOG_ACTION_ADD, $id, 'Đã thêm sự kiện ' . $evt_name);

            redirect('edit.php?record_id=' . $id);
        } else {
            $fs_errorMsg .= 'Tạo mới không thành công. Hãy thử lại';
        }
    }
}
echo $blade->view()->make('add', get_defined_vars())->render();
?>