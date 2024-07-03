<?php

use App\Models\Moneyloadbatch;
require_once 'inc_security.php';
require_once '../../resource/PHPExcel/Classes/PHPExcel.php';
if (isset($_POST['form_submit']) && $_POST['money_file_name'] != '') 
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
                if(empty($value['B']) || empty($value['C']) || !intval($value['A'])){
                    continue;
                }
                # kiếm tra sdt
                $row = array();
                if(!empty($value['B'])){
                    $db_data = new db_query("SELECT *
                    from users where use_phone= ".$value['B']."
                     LIMIT 1");
                    $row = $db_data->fetch();
                }
                if(intval($value['C']) ==0 || !check_array($row)){
                    $dataerr[$j]['sdt'] = $value['B'];
                    $dataerr[$j]['money'] = $value['C'];
                    //$dataerr[$j]['money_type'] = $value['D'];
                    if($value['C'])
                    $j++;
                    continue;
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
                $myform = new generate_form();
                //myform->add('money_type', 'money_type', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['money_type'], 1);
                $myform->add('money_phone', 'money_phone', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['sdt'], 1);
                $myform->add('money_status', 'money_status', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, 0);
                $myform->add('money_charge', 'money_charge', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['money']);
                $myform->add('money_created_at', 'money_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pri_created_at);
                $myform->add('money_name', 'money_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_name);
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
if ($use_name) {
    $sqlWhere .= " AND money_name LIKE '%" . $money_name . "%'";
}
$money_status = getValue('money_status', 'int', 'GET', -1);
if ($money_status > 0) {
    $sqlWhere .= ' AND money_status = ' . $money_status;
}
$money_phone = getValue('money_phone', 'int', 'GET', -1);
if ($money_phone > 0) {
    $sqlWhere .= ' AND money_phone = ' . $money_phone;
}

$model = new App\Models\Moneyloadbatch();
$model->where($sqlWhere);
$model->pagination(getValue('page'), $per_page)
    ->order_by('money_id', 'DESC');

$items= $model->all();
$total = $model->count();

$dataGrid = new DataGrid($items, $total, 'money_id');
$dataGrid->model = $model;

$dataGrid->column('money_id', 'chk_item_all', ['CheckboxAll'], []);
$dataGrid->column('money_phone', 'Số điện thoại', ['string', 'trim'], [],true);
$dataGrid->column('money_name', 'Tên', ['string', 'trim'], [], true);
$dataGrid->column('money_charge', 'Số Tiền nạp', function ($row) {
    return number_format($row['money_charge']);
}, []);
/*$dataGrid->column('money_type', 'Hình thức cộng tiền', function ($row) {
    return ($row['money_type'] == 1) ? 'Cộng tiền ví' : 'trừ tiền ví';
}, []);*/
$dataGrid->column(['money_status', $status], 'Trạng thái', 'selectShow', [], true);
echo $blade->view()->make('import', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>