<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("edit");

$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Sửa thông tin khảo sát";
$fs_action = getURL();
$fs_errorMsg = "";

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    $myform = new generate_form();

    $survey_job = getValue('survey_job', 'int', 'POST', 0);
    if(intval($survey_job) == 0){
        $fs_errorMsg .= 'Bạn phải lựa chọn nghề nghiệp <br/>';
    }
    $survey_regis_reason = getValue('survey_regis_reason', 'int', 'POST', 0);
    if(intval($survey_regis_reason) == 0){
        $fs_errorMsg .= 'Bạn phải chọn Lý do đăng ký <br/>';
    }
    if($survey_regis_reason ==2){
        $survey_busined = getValue('survey_busined', 'int', 'POST', 0);
        if(intval($survey_busined) == 0){
            $fs_errorMsg .= 'Bạn phải chọn đã từng kinh doanh <br/>';
        }
        $survey_busines_date = getValue('survey_busines_date', 'int', 'POST', 0);
        if(intval($survey_busines_date) == 0){
            $fs_errorMsg .= 'Bạn phải chọn thời gian kinh doanh <br/>';
        }
        $survey_busines_desired = getValue('survey_busines_desired', 'int', 'POST', 0);
        if(intval($survey_busines_desired) == 0){
            $fs_errorMsg .= 'Bạn phải chọn hình thức kinh doanh mong muốn<br/>';
        }
    }
    $survey_note = getValue('survey_note', 'str', 'POST', 0);
    $survey_userId = getValue('survey_userId', 'int', 'POST', 0);
    $survey_updated_at = date('Y-m-d H:i:s');
    $survey_userId_survey = getValue("user_id", "str", "SESSION", "");
    
    $myform->add('survey_job', 'survey_job', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_job);
    $myform->add('survey_regis_reason', 'survey_regis_reason', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_regis_reason);
    $myform->add('survey_busined', 'survey_busined', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_busined);
    $myform->add('survey_busines_date', 'survey_busines_date', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_busines_date);
    $myform->add('survey_busines_desired', 'survey_busines_desired', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_busines_desired);
    $myform->add('survey_note', 'survey_note', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $survey_note);
    $myform->add('survey_userId', 'survey_userId', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $survey_userId);
    $myform->add('survey_updated_at', 'survey_updated_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $survey_updated_at);
    $myform->add('survey_userId_admin', 'survey_userId_admin', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $survey_userId_survey);
    $myform->addTable('survey_users');

    $myform->evaluate();
    
    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();
        if ($fs_errorMsg == '') {
            $sqlUpdate = $myform->generate_update_SQL('survey_id', $record_id);
            // _debug($sqlUpdate);die;
            $db_excute = new db_execute($sqlUpdate);
            unset($db_excute);
            echo '<script>alert("Sửa thành công!")</script>';
            $fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("survey_users.php?user_id=".$survey_userId)));
            redirectHeader($fs_redirect);
        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")

//lay du lieu cua record can sua doi
$db_data = new db_query("SELECT * FROM survey_users WHERE survey_id = " . $record_id);
if ($row = $db_data->fetch(true)) {
    foreach ($row as $key => $value) {
        if ($key != 'lang_id' && $key != 'admin_id') $$key = $value;
    }
} else {
    exit();
}
unset($db_data);

echo $blade->view()->make('survey_users_edit', get_defined_vars())->render();
return;
?>