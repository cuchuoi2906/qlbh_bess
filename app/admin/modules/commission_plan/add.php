<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));

//Khai báo biến khi thêm mới
$fs_title = "Thêm mới đại lý";
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
            $sqlUpdate = $myform->generate_insert_SQL();
            $db_excute = new db_execute($sqlUpdate);
            unset($db_excute);
            redirect($fs_redirect);
        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")

echo $blade->view()->make('add', get_defined_vars())->render();
return;
?>