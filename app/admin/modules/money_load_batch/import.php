<?php

use App\Models\Moneyloadbatch;
require_once 'inc_security.php';
require_once '../../resource/PHPExcel/Classes/PHPExcel.php';
if (isset($_POST['money_file_name']) && $_POST['money_file_name'] != '' && intval($_POST['actionImport']) == 1) 
{   
    $fs_filepath = ROOT . "/public/temp/";
    $fs_extension = "xlsx,xls";
    $fs_filesize = 100000;
    $upload = new upload('excelImport', $fs_filepath, $fs_extension, $fs_filesize);
    if ($upload->common_error == '') {
        $inputFileName = $fs_filepath . $upload->file_name;
        try {
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $objPHPExcel = $objReader->load($inputFileName);
            $allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
            $i=0;
            $j=0;
            $inserdata =array();
            $dataerr =array();
            foreach ($allDataInSheet as $value) {
                if(empty($value['B']) || !intval($value['A'])){
                    continue;
                }
                # kiếm tra sdt
                $row = array();
                if(!empty($value['B'])){
                    $db_data = new db_query("SELECT *
                    from users where use_id = ".intval($value['B'])."
                     LIMIT 1");
                    $row = $db_data->fetch();
                }
                if(intval($value['C']) ==0){
                    $inserdata[$i]['content'] = 'Số tiền không hợp lệ';
                }
                if(!check_array($row)){
                    $inserdata[$i]['content'] = 'Id mã giới thiệu không tồn tại';
                }
                $inserdata[$i]['stt'] = $value['A'];
                $inserdata[$i]['sdt'] = $value['B'];
                $inserdata[$i]['money'] = $value['C'];
                //$inserdata[$i]['money_type'] = $value['D'];
                $i++;
            }
            $use_name = getValue('money_file_name', 'str', 'POST', '');
            for($j=0;$j<count($inserdata);$j++){
                $data = [];
                $pri_created_at = gmdate("Y-m-d H:i:s", time());
                $v_money_status = !empty($inserdata[$j]['content']) ? 3 : 1;
                $myform = new generate_form();
                //myform->add('money_type', 'money_type', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['money_type'], 1);
                $myform->add('money_user_id', 'money_user_id', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['sdt'], 1);
                $myform->add('money_status', 'money_status', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $v_money_status);
                $myform->add('money_charge', 'money_charge', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['money']);
                $myform->add('money_created_at', 'money_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pri_created_at);
                $myform->add('money_name', 'money_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_name);
                $myform->add('money_admin_id_import', 'money_admin_id_import', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $admin_id);
                $myform->add('money_content', 'money_content', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['content']);
                $myform->addTable('money_load_batch');

                $myform->evaluate();
                $db_excute = new db_execute_return();
                $db_excute->db_execute($myform->generate_insert_SQL());
            }
        } catch (Exception $e) {}
    }
}
$sqlWhere = "1";
$money_name = getValue('money_name', 'str', 'GET', '', 3);
if ($money_name) {
    $sqlWhere .= " AND money_name LIKE '%" . $money_name . "%'";
}
$money_status = getValue('money_status', 'int', 'GET', -1);
if (in_array($money_status, array(0,1,2,3))) {
    $sqlWhere .= ' AND money_status = ' . $money_status;
}
$money_user_id = getValue('money_user_id', 'int', 'GET', -1);
if ($money_user_id > 0) {
    $sqlWhere .= ' AND money_user_id = ' . $money_user_id;
}
$model = new App\Models\Moneyloadbatch();
$model->inner_join('admin_user', 'adm_id = money_admin_id_import');
$model->where($sqlWhere);
$model->fields('*,(select adm_name FROM admin_user WHERE adm_id = money_admin_id LIMIT 1) adm_name_approved');
$model->pagination(getValue('page'), $per_page)
    ->order_by('money_id', 'DESC');

$items= $model->all();
$total = $model->count();

$dataGrid = new DataGrid($items, $total, 'money_id');
$dataGrid->model = $model;

$dataGrid->column('money_id', 'chk_item_all', ['CheckboxAll'], []);
$dataGrid->column('money_user_id', 'Mã giới thiệu','number', [],true);
$dataGrid->column('money_name', 'Tên', ['string', 'trim'], [], true);
$dataGrid->column('money_charge', 'Số Tiền nạp', function ($row) {
    return number_format(floatval($row['money_charge']));
}, []);
$dataGrid->column('adm_id', 'User import', function ($row) {
    return $row['adm_name'].'('.$row['adm_id'].')';
}, []);
$dataGrid->column('money_admin_id', 'User duyệt', function($row){
    return !empty($row['adm_name_approved']) ? $row['adm_name_approved'].'('.$row['money_admin_id'].')' : '';
}, []);
$dataGrid->column('money_created_at', 'Ngày import', function ($row) {
    return (new DateTime($row->money_created_at))->format('H:i:s d/m/Y');
}, true);
$dataGrid->column('money_updated_at', 'Ngày Duyệt', function ($row) {
    if($row->money_updated_at != ''){
        return (new DateTime($row->money_updated_at))->format('H:i:s d/m/Y');
    }else{
        return '';
    }
}, true);
/*$dataGrid->column('money_type', 'Hình thức cộng tiền', function ($row) {
    return ($row['money_type'] == 1) ? 'Cộng tiền ví' : 'trừ tiền ví';
}, []);*/
$dataGrid->column(['money_status', [-1 => 'Tất cả']+$status], 'Trạng thái', 'selectShow', [], true);
$dataGrid->column('money_content', 'Nội dung', ['string', 'trim'], []);
echo $blade->view()->make('import', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>