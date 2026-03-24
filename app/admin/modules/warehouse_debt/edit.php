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

$fs_title = "Cập nhật công nợ";
$fs_action = getURL();
$fs_errorMsg = "";

function parseDate($str, $default = '') {
    if (!$str) return $default;
    $d = DateTime::createFromFormat('d/m/Y', $str);
    return $d ? $d->format('Y-m-d') : $str;
}

$myform = new generate_form();
$myform->addRecordID('sae_id', $record_id);

$sae_payment_status = getValue('sae_payment_status', 'str', 'POST', '');
$myform->add('sae_payment_status', 'sae_payment_status', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_payment_status, 1, 'Chưa chọn trạng thái');

$sae_payment_date = parseDate(getValue('sae_payment_date', 'str', 'POST', ''));
$myform->add('sae_payment_date', 'sae_payment_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $sae_payment_date);

$myform->addTable('sales_export');
$myform->evaluate();

$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    if ($fs_errorMsg == '') {
        $sqlUpdate = $myform->generate_update_SQL('sae_id', $record_id);
        $db_excute = new db_execute($sqlUpdate);
        unset($db_excute);

        admin_log($admin_id, ADMIN_LOG_ACTION_EDIT, $record_id, 'Cập nhật công nợ: ' . $record->product_name . ' (' . $record_id . ')');
        \AppView\Helpers\Facades\FlashMessage::success('Cập nhật thành công', url_back());
    }
}

// Load record data
$record = SalesExport::findById($record_id);
if ($record) {
    foreach ($record->toArray(false) as $key => $value) {
        $$key = $value;
    }
}

$sae_payment_date_display = ($sae_payment_date ?? '') ? date('d/m/Y', strtotime($sae_payment_date)) : '';

echo $blade->view()->make('edit', get_defined_vars())->render();
?>
