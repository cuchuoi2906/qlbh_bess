<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin đại lý";
$fs_action = getURL();
$fs_errorMsg = "";


// Lấy giá trị từ POST
$agc_name = getValue('agc_name', 'str', 'POST', '');
$agc_phone = getValue('agc_phone', 'str', 'POST', '');
$agc_email = getValue('agc_email', 'str', 'POST', '');
$agc_website = getValue('agc_website', 'str', 'POST', '');
$agc_address = getValue('agc_address', 'str', 'POST', '');
$agc_city_id = getValue('agc_city_id', 'int', 'POST', '');
$agc_district_id = getValue('agc_district_id', 'int', 'POST', '');
$agc_show = getValue('agc_show', 'int', 'POST', 0);
$maps = getValue('maps', 'arr', 'POST', '');
//$maps?dd($maps):true;
$agc_latitude = $maps['maps_maplat'] ?? 0;
$agc_longitude = $maps['maps_maplng'] ?? 0;

$agc_updated_at = date('Y-m-d H:i:s');

//Call Class generate_form();
$myform = new generate_form();
$myform->add('agc_name', 'agc_name', 0, 1, '', 1, 'Chưa nhập tên đại lý');
$myform->add('agc_phone', 'agc_phone', 0, 1, '', 1, 'Chưa nhập số điện thoại');
$myform->add('agc_email', 'agc_email', 0, 1, '');
$myform->add('agc_address', 'agc_address', 0, 1, '', 1, 'Chưa nhập địa chỉ');
$myform->add('agc_website', 'agc_website', 0, 1, '', 0);
$myform->add('agc_latitude', 'agc_latitude', 0, 1, '', 0);
$myform->add('agc_longitude', 'agc_longitude', 0, 1, '', 0);
$myform->add('agc_city_id', 'agc_city_id', 0, 1, '', 1);
$myform->add('agc_district_id', 'agc_district_id', 0, 1, '', 1);
$myform->add('agc_updated_at', 'agc_updated_at', 0, 1, '', 0);
$myform->add('agc_show', 'agc_show', 0, 1, '', 0);

$myform->addTable($fs_table);

$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();

        if ($fs_errorMsg == '') {
            $sqlUpdate = $myform->generate_update_SQL('agc_id', $record_id);
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