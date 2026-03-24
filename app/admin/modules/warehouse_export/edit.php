<?php
use App\Models\SalesExport;

require_once 'inc_security.php';
checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id", "int", "GET", 0);

$record = SalesExport::findById($record_id);
if (!$record) {
    redirect('listing.php');
}

$fs_title = "Sửa phiếu xuất kho";
$fs_action = getURL();
$fs_errorMsg = "";

function parseDate($str, $default = '') {
    if (!$str) return $default;
    $d = DateTime::createFromFormat('d/m/Y', $str);
    return $d ? $d->format('Y-m-d') : $str;
}

$myform = new generate_form();
$myform->addRecordID('sae_id', $record_id);

$sae_pro_id = getValue('sae_pro_id', 'int', 'POST', 0);
$myform->add('sae_pro_id', 'sae_pro_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $sae_pro_id, 1, 'Chưa chọn sản phẩm');

$sae_product_name = getValue('sae_product_name', 'str', 'POST', '');
$myform->add('sae_product_name', 'sae_product_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_product_name);

$sae_export_date = parseDate(getValue('sae_export_date', 'str', 'POST', ''), date('Y-m-d'));
$myform->add('sae_export_date', 'sae_export_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_export_date);

$sae_product_type = getValue('sae_product_type', 'str', 'POST', '');
$myform->add('sae_product_type', 'sae_product_type', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_product_type);

$sae_quantity_ban = getValue('sae_quantity_ban', 'int', 'POST', 0);
$myform->add('sae_quantity_ban', 'sae_quantity_ban', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $sae_quantity_ban);

$sae_quantity_kha_dung = getValue('sae_quantity_kha_dung', 'int', 'POST', 0);
$myform->add('sae_quantity_kha_dung', 'sae_quantity_kha_dung', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $sae_quantity_kha_dung);

$sae_quantity_con_lai = getValue('sae_quantity_con_lai', 'int', 'POST', 0);
$myform->add('sae_quantity_con_lai', 'sae_quantity_con_lai', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $sae_quantity_con_lai);

$sae_unit_price = getValue('sae_unit_price', 'str', 'POST', 0);
$myform->add('sae_unit_price', 'sae_unit_price', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $sae_unit_price);

$sae_other_cost = getValue('sae_other_cost', 'str', 'POST', 0);
$myform->add('sae_other_cost', 'sae_other_cost', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $sae_other_cost);

$sae_total_ban = getValue('sae_total_ban', 'str', 'POST', 0);
$myform->add('sae_total_ban', 'sae_total_ban', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $sae_total_ban);

$sae_customer_name = getValue('sae_customer_name', 'str', 'POST', '');
$myform->add('sae_customer_name', 'sae_customer_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_customer_name);

$sae_customer_phone = getValue('sae_customer_phone', 'str', 'POST', '');
$myform->add('sae_customer_phone', 'sae_customer_phone', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_customer_phone);

$sae_customer_address = getValue('sae_customer_address', 'str', 'POST', '');
$myform->add('sae_customer_address', 'sae_customer_address', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_customer_address);

$sae_lot_number = getValue('sae_lot_number', 'str', 'POST', '');
$myform->add('sae_lot_number', 'sae_lot_number', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_lot_number);

$sae_mfg_date = parseDate(getValue('sae_mfg_date', 'str', 'POST', ''));
$myform->add('sae_mfg_date', 'sae_mfg_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_mfg_date);

$sae_exp_date = parseDate(getValue('sae_exp_date', 'str', 'POST', ''));
$myform->add('sae_exp_date', 'sae_exp_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_exp_date);

$sae_note = getValue('sae_note', 'str', 'POST', '');
$myform->add('sae_note', 'sae_note', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_note);

$myform->addTable('sales_export');
$myform->evaluate();

$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    if ($fs_errorMsg == '') {
        $sqlUpdate = $myform->generate_update_SQL('sae_id', $record_id);
        $db_excute = new db_execute($sqlUpdate);
        unset($db_excute);

        admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $record_id, 'Đã sửa phiếu xuất kho: ' . $sae_product_name . ' (' . $record_id . ')');
        \AppView\Helpers\Facades\FlashMessage::success('Cập nhật thành công', url_back());
    }
}

// Nạp dữ liệu record vào biến
$record = SalesExport::findById($record_id);
if ($record) {
    foreach ($record->toArray(false) as $key => $value) {
        $$key = $value;
    }
    $sae_pro_id = (int)($record->sae_pro_id ?? 0);
}

// Giá trị hiển thị cho datepicker (DD/MM/YYYY)
$sae_export_date_display = !empty($sae_export_date) ? date('d/m/Y', strtotime($sae_export_date)) : date('d/m/Y');
$sae_mfg_date_display    = !empty($sae_mfg_date)    ? date('d/m/Y', strtotime($sae_mfg_date))    : '';
$sae_exp_date_display    = !empty($sae_exp_date)    ? date('d/m/Y', strtotime($sae_exp_date))    : '';

echo $blade->view()->make('edit', get_defined_vars())->render();
?>
