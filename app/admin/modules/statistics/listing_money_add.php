<?


require_once 'inc_security.php';

$ord_code = getValue('ord_code', 'str', 'GET', '', 3);
$ord_status_code = getValue('ord_status_code', 'str', 'GET', '', 3);



$formatDate = "'%Y-%m-%d'";
$dateSelect = 1;

$db_query = new db_query("SELECT  DATE_FORMAT(`umar_created_at`, {$formatDate}) as datetime, 
 			SUM(if((`umar_type` = 'MONEY_ADD'),1,0)) as tong_luot_money_add,
 			SUM(if((`umar_type` = 'ORDER'),1,0)) as tong_luot_order,
			SUM(if((`umar_type` = 'ORDER'),`umar_amount`,0)) as tong_tien_order,
			SUM(if((`umar_type` = 'MONEY_ADD'),`umar_amount`,0)) as tong_tien_add
 FROM `user_money_add_request`
 WHERE 
 	`umar_created_at` > {$dateSelect}
 	AND `umar_status` = 'SUCCESS'
 	GROUP BY datetime
 	ORDER BY datetime DESC");



$items = $db_query->result;

$total = 35;

$dataGrid = new DataGrid($items, $total, 'datetime');

$dataGrid->column('datetime', 'Thời gian', 'string');
$dataGrid->column(false, 'Tiền nạp/số lượng', function($row){
    return  number_format($row['tong_tien_add']) . "vnđ/" .number_format($row['tong_luot_money_add']) . " lượt";
});

$dataGrid->column(false, 'Tiền thanh toán order/số lượng', function($row){
    return  number_format($row['tong_tien_order']) . "vnđ/" .number_format($row['tong_luot_order']) . " lượt";
});


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
