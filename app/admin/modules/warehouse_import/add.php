<?php
require_once 'inc_security.php';
checkAddEdit("add");

$fs_title = "Thêm phiếu nhập kho";
$fs_action = getURL();
$fs_redirect = 'listing.php';
$fs_errorMsg = "";

// Helper chuyển DD/MM/YYYY → Y-m-d (daterangepicker single mode gửi lên)
function parseDate($str, $default = '') {
    if (!$str) return $default;
    $d = DateTime::createFromFormat('d/m/Y', $str);
    return $d ? $d->format('Y-m-d') : $str;
}

$myform = new generate_form();

// Sản phẩm — lấy từ select
$who_pro_id = getValue('who_pro_id', 'int', 'POST', 0);
$myform->add('who_pro_id', 'who_pro_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $who_pro_id, 1, 'Chưa chọn sản phẩm');

// Tên sản phẩm — tự động điền từ select
$who_product_name = getValue('who_product_name', 'str', 'POST', '');
$myform->add('who_product_name', 'who_product_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_product_name);

// Ngày nhập — convert từ DD/MM/YYYY sang Y-m-d
$who_import_date = parseDate(getValue('who_import_date', 'str', 'POST', ''), date('Y-m-d'));
$myform->add('who_import_date', 'who_import_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_import_date);

// Đơn vị tính — số
$who_packaging_unit = getValue('who_packaging_unit', 'int', 'POST', 0);
$myform->add('who_packaging_unit', 'who_packaging_unit', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $who_packaging_unit);

$who_quantity = getValue('who_quantity', 'int', 'POST', 0);
$myform->add('who_quantity', 'who_quantity', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $who_quantity);

$who_quantity_packing = getValue('who_quantity_packing', 'int', 'POST', 0);
$myform->add('who_quantity_packing', 'who_quantity_packing', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $who_quantity_packing);

$who_carton_quantity = getValue('who_carton_quantity', 'int', 'POST', 0);
$myform->add('who_carton_quantity', 'who_carton_quantity', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $who_carton_quantity);

$who_unit_price = getValue('who_unit_price', 'str', 'POST', 0);
$myform->add('who_unit_price', 'who_unit_price', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $who_unit_price);

$who_other_cost = getValue('who_other_cost', 'str', 'POST', 0);
$myform->add('who_other_cost', 'who_other_cost', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $who_other_cost);

$who_total_price = getValue('who_total_price', 'str', 'POST', 0);
$myform->add('who_total_price', 'who_total_price', FORM_ADD_TYPE_DOUBLE, FORM_ADD_VALUE_FROM_GLOBAL, $who_total_price);

$who_supplier_name = getValue('who_supplier_name', 'str', 'POST', '');
$myform->add('who_supplier_name', 'who_supplier_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_supplier_name);

$who_supplier_code = getValue('who_supplier_code', 'str', 'POST', '');
$myform->add('who_supplier_code', 'who_supplier_code', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_supplier_code);

$who_receiver_name = getValue('who_receiver_name', 'str', 'POST', '');
$myform->add('who_receiver_name', 'who_receiver_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_receiver_name);

$who_warehouse_name = getValue('who_warehouse_name', 'str', 'POST', '');
$myform->add('who_warehouse_name', 'who_warehouse_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_warehouse_name);

$who_lot_number = getValue('who_lot_number', 'str', 'POST', '');
$myform->add('who_lot_number', 'who_lot_number', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_lot_number);

$who_mfg_date = parseDate(getValue('who_mfg_date', 'str', 'POST', ''));
$myform->add('who_mfg_date', 'who_mfg_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_mfg_date);

$who_exp_date = parseDate(getValue('who_exp_date', 'str', 'POST', ''));
$myform->add('who_exp_date', 'who_exp_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_exp_date);

$who_note = getValue('who_note', 'str', 'POST', '');
$myform->add('who_note', 'who_note', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_note);

$who_status = 'completed';
$myform->add('who_status', 'who_status', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_status);

$who_created_by = $admin_username ?? '';
$myform->add('who_created_by', 'who_created_by', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_created_by);

$who_created_date = gmdate("Y-m-d H:i:s", time());
$myform->add('who_created_date', 'who_created_date', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $who_created_date);

$myform->addTable('warehouse');
$myform->evaluate();

$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    if ($fs_errorMsg == '') {
        $db_excute = new db_execute_return();
        $who_id = $db_excute->db_execute($myform->generate_insert_SQL());
        unset($db_excute);

        if ($who_id) {
            admin_log($admin_id, ADMIN_LOG_ACTION_ADD, $who_id, 'Đã thêm phiếu nhập kho: ' . $who_product_name . ' (' . $who_id . ')');
            redirect($fs_redirect);
        }
    }
}

// Giá trị hiển thị lại trên form (DD/MM/YYYY)
$who_import_date_display = $who_import_date ? date('d/m/Y', strtotime($who_import_date)) : date('d/m/Y');
$who_mfg_date_display    = $who_mfg_date    ? date('d/m/Y', strtotime($who_mfg_date))    : '';
$who_exp_date_display    = $who_exp_date    ? date('d/m/Y', strtotime($who_exp_date))    : '';

echo $blade->view()->make('add', get_defined_vars())->render();
?>
