<?php
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit();

$record_id = getValue('user_id', 'int', 'GET', 1);
$fs_title = "";
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
    $agc_created_at = date('Y-m-d H:i:s');
    $survey_userId_survey = getValue("user_id", "str", "SESSION", "");
    
    $myform->add('survey_job', 'survey_job', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_job);
    $myform->add('survey_regis_reason', 'survey_regis_reason', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_regis_reason);
    $myform->add('survey_busined', 'survey_busined', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_busined);
    $myform->add('survey_busines_date', 'survey_busines_date', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_busines_date);
    $myform->add('survey_busines_desired', 'survey_busines_desired', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $survey_busines_desired);
    $myform->add('survey_note', 'survey_note', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $survey_note);
    $myform->add('survey_userId', 'survey_userId', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $survey_userId);
    $myform->add('survey_created_at', 'survey_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $agc_created_at);
    $myform->add('survey_userId_admin', 'survey_userId_admin', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $survey_userId_survey);
    $myform->addTable('survey_users');

    $myform->evaluate();
    if ($fs_errorMsg == '') {
        $sql = $myform->generate_insert_SQL();
        $db_excute = new db_execute_return();
        $id = $db_excute->db_execute($sql);
        if ($id) {
            \AppView\Helpers\Facades\FlashMessage::success('Tạo khảo sát thành công','');
        }
        unset($db_excute);
    }
}
$db_data = new db_query("SELECT users.*,
        (SELECT a.use_name FROM users a WHERE a.use_id = users.use_referral_id) username_referral,
        (SELECT b.ord_created_at FROM orders b WHERE b.ord_user_id = users.use_id order by ord_created_at DESC LIMIT 1) ord_created_at
        FROM users WHERE use_id = " . $record_id);
$row = $db_data->fetch(true);
if (!check_array($row)){
    exit();
}
unset($db_data);

$db_data_survey = new db_query("SELECT *,(SELECT concat(adm_loginname,'. ',adm_name) FROM admin_user WHERE adm_id = survey_userId_admin LIMIT 1) survey_users_admin_name
        FROM survey_users WHERE survey_userId = " . $record_id);
$row_survey_users = $db_data_survey->fetch();
unset($db_data_survey);

echo $blade->view()->make('survey_add', get_defined_vars())->render();
return;