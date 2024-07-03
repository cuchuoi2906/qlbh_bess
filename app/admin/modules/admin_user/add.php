<?

require_once("../../bootstrap.php");
require_once 'inc_security.php';
//check quyền them sua xoa
checkAddEdit("add");

$ff_action = $_SERVER['REQUEST_URI'];
$ff_redirect_succ = "listing.php";
$ff_redirect_fail = "";
$fs_action = getURL();
$fs_errorMsg = "";


$action = getValue("action", "str", "POST", "");
$arelate_select = (array)getValue("arelate_select", "arr", "POST");
//$menuid         = new menu();
//$menuid->getArray("categories_multi","cat_id","cat_parent_id"," lang_id = " . $lang_id);
//$adm_access_category 	= '';
//foreach($arelate_select as $key=>$value){
//	$adm_access_category .= '[' . str_replace(",","][",$menuid->getAllChildId($value)) . ']';
//}

$modules = \App\Models\Module::order_by('mod_order', 'DESC')->all();

$fs_errorMsg = "";
$allow_insert = 0;
$myform = new generate_form();
$myform->add("adm_loginname", "adm_loginname", 0, 0, "", 1, 'Bạn chưa hoàn thiện thông tin login name', 1, 'Tên đăng nhập đã tồn tại. Vui lòng kiểm tra lại.');
$myform->add("adm_password", "adm_password", 4, 0, "", 1, 'Bạn chưa hoàn thiện thông tin password', 0, "");
$myform->add("adm_email", "adm_email", 2, 0, "", 1, 'Bạn chưa hoàn thiện thông tin email', 0, "");
$myform->add("adm_name", "adm_name", 0, 0, "", 1, "Bạn chưa hoàn thiện thông tin Full name", 0, "");
$myform->add("adm_phone", "adm_phone", 0, 0, "", 1, "Bạn chưa hoàn thiện thông tin Số điện thoại", 0, "");
$myform->add("adm_all_category", "adm_all_category", 1, 0, 0, 0, "", 0, "");
$myform->add("adm_all_website", "adm_all_website", 1, 0, 0, 0, "", 0, "");
//$myform->add("adm_access_category","adm_access_category",0,1,"",0,"",0,"");
$myform->add("adm_edit_all", "adm_edit_all", 1, 0, 0, 0, "", 0, "");
$myform->add("admin_id", "admin_id", 1, 1, 0, 0, "", 0, "");
$myform->addTable("admin_user");
//get vaule from POST
$adm_loginname = getValue("adm_loginname", "str", "POST", "", 1);
//password hash md5 --> only replace \' = '
$adm_password = getValue("adm_password", "str", "POST", "", 1);
$adm_re_password = getValue('adm_re_password', 'str', 'POST', '');
//get Access Module list
$module_list = "";
$module_list = getValue("mod_id", "arr", "POST", "");
$user_lang_id_list = getValue("user_lang_id", "arr", "POST", "");
$arelate_select = getValue("arelate_select", "arr", "POST", "");


if ($action == 'execute') {
    $fs_errorMsg .= $myform->checkdata();

    foreach ($modules as $module) {
        $add = getValue("adu_add" . $module->id, "int", "POST");
        $edit = getValue("adu_edit" . $module->id, "int", "POST");
        $delete = getValue("adu_delete" . $module->id, "int", "POST");
        if ($add || $edit || $delete) {
            $allow_insert = 1;
        }
    }

    if ($allow_insert == 0) {
        $fs_errorMsg .= 'Bạn vui lòng chọn quyền cho user cần tạo';
    }

    //insert new user to database
    if ('' == $fs_errorMsg && $allow_insert == 1) {

        if (!$adm_re_password) {
            $fs_errorMsg .= "Bạn chưa xác nhận lại thông tin password";
        }

        //Call Class generate_form();
        $querystr = $myform->generate_insert_SQL();
//        $fs_errorMsg .= $myform->checkdata();
        $last_id = 0;
        if ($fs_errorMsg == "") {
            $db_ex = new db_execute_return();
            $last_id = $db_ex->db_execute($querystr);
            unset($db_ex);
            if ($last_id != 0) {

                admin_log($admin_id, 'ADD', $last_id, 'Thêm mới quản trị viên ' . getValue('adm_name', 'str', 'POST'));

                //insert user right\
//                if (isset($module_list[0])) {
                foreach ($modules as $module) {
                    $add = getValue("adu_add" . $module->id, "int", "POST");
                    $edit = getValue("adu_edit" . $module->id, "int", "POST");
                    $delete = getValue("adu_delete" . $module->id, "int", "POST");
                    if ($add || $edit || $delete) {
                        $query_str = "INSERT INTO admin_user_right VALUES(" . $last_id . "," . $module->id . ", " . $add . ", " . $edit . ", " . $delete . ")";
                        $db_ex = new db_execute($query_str);
                        unset($db_ex);
                    }
                }
//                }
                redirect($ff_redirect_succ);
                exit();
            }
        }
    }
}
$myform->evaluate();
echo $blade->view()->make('add', get_defined_vars())->render();