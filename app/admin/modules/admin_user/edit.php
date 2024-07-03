<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';
//check quyền them sua xoa
checkAddEdit("edit");


$ff_action = getURL();
$ff_redirect_succ = "listing.php";
$ff_redirect_fail = "";
$iAdm = getValue("record_id");
$ff_table = "admin_user";

$fs_redirect = base64_decode(getValue("returnurl", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("iAdm", "int", "GET");
$field_id = "adm_id";
$fs_errorMsg = "";
$fs_action = getValue("action", "str", "POST", "");

$arelate_select = (array)getValue("arelate_select", "arr", "POST", "");

$modules = \App\Models\Module::order_by('mod_order', 'DESC')->all();

//Call Class generate_form();
$myform = new generate_form();
$myform->add("adm_email", "adm_email", 2, 0, "", 1, "Bạn chưa hoàn thiện thông tin email", 0, "");
$myform->add("adm_name", "adm_name", 0, 0, "", 1, "Bạn chưa hoàn thiện thông tin Full name", 0, "");
$myform->add("adm_phone", "adm_phone", 0, 0, "", 1, "Bạn chưa hoàn thiện thông tin Phone", 0, "");
$myform->add("adm_all_category", "adm_all_category", 1, 0, 0, 0, "", 0, "");
$myform->add("adm_all_website", "adm_all_website", 1, 0, 0, 0, "", 0, "");
$myform->add("adm_access_category", "adm_access_category", 0, 1, "", 0, "", 0, "");
$myform->add("adm_edit_all", "adm_edit_all", 1, 0, 0, 0, "", 0, "");
$myform->add("admin_id", "admin_id", 1, 1, 0, 0, "", 0, "");

$myform->addTable($fs_table);

//Edit user profile
if ($fs_action == 'update') {
    $fs_errorMsg .= $myform->checkdata();
    if ($fs_errorMsg == "") {
        //echo $myform->generate_update_SQL("adm_id",$iAdm); exit();
        $db_ex = new db_execute($myform->generate_update_SQL("adm_id", $iAdm));
        unset($db_ex);

        admin_log($admin_id, 'EDIT', $record_id, 'Sửa quản trị viên ' . getValue('adm_name', 'str', 'POST'));

        $module_list = (array)getValue("mod_id", "arr", "POST", []);
        $user_lang_id_list = (array)getValue("user_lang_id", "arr", "POST", "");

        $arelate_select = (array)getValue("arelate_select", "arr", "POST", "");

        $allow_insert = 0;
        foreach ($modules as $module) {
            $add = getValue("adu_add" . $module->id, "int", "POST");
            $edit = getValue("adu_edit" . $module->id, "int", "POST");
            $delete = getValue("adu_delete" . $module->id, "int", "POST");
            if ($add || $edit || $delete) {
                $allow_insert = 1;
            }
        }
        if ($allow_insert == 0) {
            $fs_errorMsg .= 'Bạn vui lòng chọn quyền cho user';
        }

        if ($allow_insert) {
            $db_delete = new db_execute("DELETE FROM admin_user_right WHERE adu_admin_id =" . $iAdm);
            unset($db_delete);

            foreach ($modules as $module) {
                $add = getValue("adu_add" . $module->id, "int", "POST");
                $edit = getValue("adu_edit" . $module->id, "int", "POST");
                $delete = getValue("adu_delete" . $module->id, "int", "POST");
                if ($add || $edit || $delete) {
                    $query_str = "INSERT INTO admin_user_right VALUES(" . $iAdm . "," . $module->id . ", " . $add . ", " . $edit . ", " . $delete . ")";
                    $db_ex = new db_execute($query_str);
                    unset($db_ex);
                }
            }
        }

        //Update password
        $adm_password = getValue('adm_password', 'str', 'POST', '');
        $adm_password_verify = getValue('adm_password_verify', 'str', 'POST', '');
        if ($adm_password != '') {
            if ($adm_password === $adm_password_verify) {
                $myform = new generate_form();
                $myform->add("adm_password", "adm_password", 4, 0, "", 1, translate_text("Please enter new password"), 0, "");
                $myform->addTable($fs_table);
                $fs_errorMsg .= $myform->checkdata();
                if ($fs_errorMsg == "") {
                    $db_ex = new db_execute($myform->generate_update_SQL("adm_id", $iAdm));
                    unset($db_ex);
                    \AppView\Helpers\Facades\FlashMessage::success('Mật khẩu đã được cập nhật', url_back());
                }
            } else {
                $fs_errorMsg = 'Bạn cần xác nhận mật khẩu mới';
            }
        }
    }
}

//Select access module
$acess_module = "";
$arrayAddEdit = array();
$db_access = new db_query("SELECT * 
									FROM admin_user, admin_user_right, modules
									WHERE adm_id = adu_admin_id AND mod_id = adu_admin_module_id AND adm_id =" . $iAdm);
while ($row_access = mysqli_fetch_array($db_access->result)) {
    $acess_module .= "[" . $row_access['mod_id'] . "]";
    $arrayAddEdit[$row_access['mod_id']] = array($row_access["adu_add"], $row_access["adu_edit"], $row_access["adu_delete"]);
}
unset($db_access);

//Check user exist or not
$db_admin_sel = new db_query($s = "SELECT *
										  FROM admin_user
										  WHERE adm_id = " . $iAdm);
$row = mysqli_fetch_array($db_admin_sel->result);
extract($row);

//echo $s;die;
$db_getallmodule = new db_query("SELECT * 
												FROM modules
												ORDER BY mod_order DESC");

echo $blade->view()->make('edit', get_defined_vars())->render();
?>