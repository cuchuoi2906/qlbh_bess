<?php

use App\Models\PoiciesByProducImport;
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
                    from products where pro_id = ".intval($value['B'])."
                     LIMIT 1");
                    $row = $db_data->fetch();
                }
                if(intval($value['C']) ==0){
                    $inserdata[$i]['content'] = 'Số lượng không hợp lệ';
                }
                if(intval($value['D']) ==0){
                    $inserdata[$i]['content'] = 'Chiết khấu không hợp lệ';
                }
                if(!check_array($row)){
                    $inserdata[$i]['content'] = 'Id mã sản phẩm không tồn tại';
                }
                $inserdata[$i]['stt'] = $value['A'];
                $inserdata[$i]['pro_id'] = $value['B'];
                $inserdata[$i]['quantity'] = $value['C'];
                $inserdata[$i]['charge'] = $value['D'];
                $i++;
            }
            $use_name = getValue('money_file_name', 'str', 'POST', '');
            for($j=0;$j<count($inserdata);$j++){
                $data = [];
                $pri_created_at = gmdate("Y-m-d H:i:s", time());
                $v_money_status = !empty($inserdata[$j]['content']) ? 3 : 1;
                $myform = new generate_form();
                //myform->add('money_type', 'money_type', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['money_type'], 1);
                $myform->add('policies_pro_id', 'policies_pro_id', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['pro_id'], 1);
                $myform->add('policies_quantity', 'policies_quantity', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['quantity'], 1);
                $myform->add('policies_status', 'policies_status', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $v_money_status);
                $myform->add('policies_charge', 'policies_charge', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['charge']);
                $myform->add('policies_created_at', 'policies_created_at', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $pri_created_at);
                $myform->add('policies_name', 'policies_name', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $use_name);
                $myform->add('policies_admin_id_import', 'policies_admin_id_import', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $admin_id);
                $myform->add('policies_content', 'policies_content', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $inserdata[$j]['content']);
                $myform->addTable('policies_by_produc_import');

                $myform->evaluate();
                $db_excute = new db_execute_return();
                $db_excute->db_execute($myform->generate_insert_SQL());
            }
        } catch (Exception $e) {}
    }
}
$sqlWhere = "1";
$policies_name = getValue('policies_name', 'str', 'GET', '', 3);
if ($policies_name) {
    $sqlWhere .= " AND policies_name LIKE '%" . $policies_name . "%'";
}
$policies_status = getValue('policies_status', 'int', 'GET', -1);
if (in_array($policies_status, array(0,1,2,3))) {
    $sqlWhere .= ' AND policies_status = ' . $policies_status;
}

$model = new App\Models\PoiciesByProducImport();
$model->inner_join('admin_user', 'adm_id = policies_admin_id_import');
$model->where($sqlWhere);
$model->fields('*,(select adm_name FROM admin_user WHERE adm_id = policies_admin_id LIMIT 1) adm_name_approved');
$model->pagination(getValue('page'), $per_page)
    ->order_by('policies_id', 'DESC');

$items= $model->all();
$total = $model->count();

$dataGrid = new DataGrid($items, $total, 'money_id');
$dataGrid->model = $model;

$dataGrid->column('policies_id', 'chk_item_all', ['CheckboxAll'], []);
$dataGrid->column('policies_pro_id', 'ID sản phẩm','string', [],true);
$dataGrid->column('policies_quantity', 'Số lượng','string', [],true);
$dataGrid->column('policies_charge', 'Chiết khấu', function ($row) {
    return number_format(floatval($row['policies_charge']));
}, []);
$dataGrid->column('adm_id', 'User import', function ($row) {
    return $row['adm_name'].'('.$row['adm_id'].')';
}, []);
$dataGrid->column('policies_created_at', 'Ngày import', function ($row) {
    return (new DateTime($row->policies_created_at))->format('H:i:s d/m/Y');
}, true);
$dataGrid->column('policies_updated_at', 'Ngày Duyệt', function ($row) {
    if($row->policies_updated_at != ''){
        return (new DateTime($row->policies_updated_at))->format('H:i:s d/m/Y');
    }else{
        return '';
    }
}, true);
/*$dataGrid->column('money_type', 'Hình thức cộng tiền', function ($row) {
    return ($row['money_type'] == 1) ? 'Cộng tiền ví' : 'trừ tiền ví';
}, []);*/
echo $blade->view()->make('import', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>