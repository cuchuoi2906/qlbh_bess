<?
require_once 'inc_security.php';
use App\Models\OrderCommission;

$ord_code = getValue('ord_code', 'str', 'GET', '', 3);
$ord_status_code = getValue('ord_status_code', 'str', 'GET', '', 3);
$fs_table = "orders";
$id_field = "";
$name_field = "ord_ship_name";


$formatDate = "'%Y-%m-%d'";
$dateSelect = 1;
$statusType = OrderCommission::STATUS_SUCCESS;
//$payment_type_COD = Order::PAYMENT_TYPE_COD;
//$payment_type_Online = Order::PAYMENT_TYPE_ONLINE;
//$payment_type_Wallet = Order::PAYMENT_TYPE_WALLET;
$select = "SELECT  DATE_FORMAT(`orc_created_at`, {$formatDate}) as datetime, 
 			SUM(if((`orc_status_code` = '{$statusType}' AND `orc_is_owner`  = 1),1,0)) as tong_luot_tructiep,
 			SUM(if((`orc_status_code` = '{$statusType}' AND `orc_is_owner`  = 1),`orc_amount`,0)) as tong_tien_tructiep,
 			SUM(if((`orc_status_code` = '{$statusType}' AND `orc_is_owner`  = 0),1,0)) as tong_luot_giantiep,
 			SUM(if((`orc_status_code` = '{$statusType}' AND `orc_is_owner`  = 0),`orc_amount`,0)) as tong_tien_giantiep,
			SUM(if((`orc_status_code` = '{$statusType}'),`orc_amount`,0)) as tong_tien_order,
			SUM(if((`orc_status_code` = '{$statusType}'),1,0)) as tong_luot
 FROM `order_commissions`
 WHERE 
 	`orc_created_at` > {$dateSelect}
 	AND `orc_status_code` = '{$statusType}'
 	GROUP BY datetime
 	ORDER BY datetime DESC";

$db_query = new db_query($select);



$items = $db_query->result;

$total = 35;

$dataGrid = new DataGrid($items, $total, 'datetime');

$dataGrid->column('datetime', 'Thời gian', 'string');
$dataGrid->column(false, 'Hoa hồng trực tiếp mua hàng', function($row){
    return  number_format($row['tong_tien_tructiep']) . "vnđ/" .number_format($row['tong_luot_tructiep']) . " lượt";
});
$dataGrid->column(false, 'Hoa hồng gián tiếp', function($row){
    return  number_format($row['tong_tien_giantiep']) . "vnđ/" .number_format($row['tong_luot_giantiep']) . " lượt";
});
$dataGrid->column(false, 'Tổng', function($row){
    return  number_format($row['tong_tien_order']) . "vnđ/" .number_format($row['tong_luot']) . " lượt";
});


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
