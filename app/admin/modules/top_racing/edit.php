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


$trc_title = getValue('trc_title', 'str', 'POST', '');
$trc_type = getValue('trc_type', 'str', 'POST', 'OWNER');
$trc_active = getValue('trc_active', 'int', 'POST', 1);
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
$myform->add('trc_start', 'trc_start', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $trc_start ?? date('Y-m-d'), 1, 'Thời gian bắt đầu không được để trống');
$myform->add('trc_end', 'trc_end', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $trc_end ?? 0);

$myform->add('trc_all_product', 'trc_all_product', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $trc_all_product ?? 1);


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

            \App\Models\TopRacingCampaignProduct::where('trcp_campaign_id', $record_id)->delete();
            foreach ($pro_ids as $pro_id) {
                \App\Models\TopRacingCampaignProduct::insert([
                    'trcp_product_id' => (int)$pro_id,
                    'trcp_campaign_id' => $record_id
                ]);
            }

            admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $record_id, 'Đã sửa chiến dịch đua top ' . $trc_title);

            unset($db_excute);
//            \AppView\Helpers\Facades\FlashMessage::success('Cập nhật thành công', url_back());
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

$pro_ids = $item->products ? $item->products->lists('pro_id') : [];

//dd($row);
unset($db_data);

echo $blade->view()->make('add', get_defined_vars())->render();
return;
?>