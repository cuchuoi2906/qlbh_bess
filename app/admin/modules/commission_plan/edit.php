<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin";
$fs_action = getURL();
$fs_errorMsg = "";


// Lấy giá trị từ POST
$cpl_plan_name = getValue('cpl_plan_name', 'str', 'POST', '');
$cpl_commission_percent = getValue('cpl_commission_percent', 'int', 'POST', 0);

$cpl_commission_percent = ($cpl_commission_percent <= 0) ? 0 : $cpl_commission_percent;
$cpl_commission_percent = ($cpl_commission_percent >= 100) ? 100 : $cpl_commission_percent;

//Call Class generate_form();
$myform = new generate_form();
$myform->add('cpl_plan_name', 'cpl_plan_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, '', 1, 'Chưa nhập tên');
$myform->add('cpl_commission_percent', 'cpl_commission_percent', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, '', 1, 'Chưa nhập % hoa hồng');

$myform->addTable($fs_table);

$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();

        if ($fs_errorMsg == '') {
            $sqlUpdate = $myform->generate_update_SQL($id_field, $record_id);
            $db_excute = new db_execute($sqlUpdate);
            unset($db_excute);
            redirect($fs_redirect);
        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")

//lay du lieu cua record can sua doi
$db_data = new db_query("SELECT * FROM " . $fs_table . " WHERE " . $id_field . " = " . $record_id);
if ($row = $db_data->fetch(true)) {
    foreach ($row as $key => $value) {
        if ($key != 'lang_id' && $key != 'admin_id') $$key = $value;
    }
} else {
    exit();
}
unset($db_data);

echo $blade->view()->make('edit', get_defined_vars())->render();
return;
?>