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


$trc_title = getValue('trc_title', 'str', 'POST', '');
$trc_active = getValue('trc_active', 'int', 'POST', 1);
$trc_type = getValue('trc_type', 'str', 'POST', 'OWNER');
$trc_description = getValue('trc_description', 'str', 'POST', '');
$trc_start = getValue('trc_start', 'str', 'POST', date('Y-m-d'));
if ($trc_start) {
    $trc_start = strtotime($trc_start);
    // if ($trc_start < strtotime('Today')) {
    //     $fs_errorMsg .= 'Ngày bắt đầu không hợp lệ';
    // }
}

$trc_end = getValue('trc_end', 'str', 'POST', '');
if ($trc_end) {
    $trc_end = strtotime($trc_end) + 86399;
    if ($trc_end < time()) {
        $fs_errorMsg .= 'Ngày kết thúc không hợp lệ';
    }
    if ($trc_end < $trc_start) {
        $fs_errorMsg .= 'Ngày kết thúc phải lớn hơn ngày bắt đầu';
    }
}


$pro_ids = getValue('pro_ids', 'arr', 'POST', []);
$trc_all_product = 0;
if (empty($pro_ids)) {
    $trc_all_product = 1;
}

$myform->add('trc_title', 'trc_title', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $trc_title ?? '', 1, 'Tên chiến dịch không được để trống');
$myform->add('trc_type', 'trc_type', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $trc_type ?? 'OWNER', 1, 'Bạn phải chọn kiểu đua top');
$myform->add('trc_active', 'trc_active', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $trc_active ?? 1);
$myform->add('trc_description', 'trc_description', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $trc_description ?? '');
$myform->add('trc_start', 'trc_start', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $trc_start ?? time(), 1, 'Thời gian bắt đầu không được để trống');
$myform->add('trc_end', 'trc_end', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $trc_end ?? 0);

$myform->add('trc_all_product', 'trc_all_product', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $trc_all_product ?? 1);
$myform->add('trc_admin_id', 'admin_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $admin_id ?? 0);


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

            foreach ($pro_ids as $pro_id) {
                \App\Models\TopRacingCampaignProduct::insert([
                    'trcp_product_id' => (int)$pro_id,
                    'trcp_campaign_id' => $id
                ]);
            }

            admin_log($admin_id, ADMIN_LOG_ACTION_ADD, $id, 'Đã thêm chiến dịch đua top ' . $trc_title);

            redirect('edit.php?record_id=' . $id);
        } else {
            $fs_errorMsg .= 'Tạo mới không thành công. Hãy thử lại';
        }
    }
}
echo $blade->view()->make('add', get_defined_vars())->render();
?>