<?PHP
require_once 'inc_security.php';
use App\Models\Order;

$ord_code = getValue('ord_code', 'str', 'GET', '', 3);
$ord_status_code = getValue('ord_status_code', 'str', 'GET', '', 3);
$fs_table = "orders";
$id_field = "";
$name_field = "ord_ship_name";


$formatDate = "'%Y-%m-%d'";
$dateSelect = 1;
$statusType = Order::SUCCESS;
$payment_type_COD = Order::PAYMENT_TYPE_COD;
$payment_type_Online = Order::PAYMENT_TYPE_ONLINE;
$payment_type_Wallet = Order::PAYMENT_TYPE_WALLET;
$select = "SELECT  DATE_FORMAT(`ord_created_at`, {$formatDate}) as datetime, 
 			SUM(if((`ord_status_code` = '{$statusType}' AND `ord_payment_type`  = '{$payment_type_COD}'),1,0)) as tong_luot_cod,
 			SUM(if((`ord_status_code` = '{$statusType}' AND `ord_payment_type`  = '{$payment_type_COD}'),`ord_amount`,0)) as tong_tien_cod,
 			SUM(if((`ord_status_code` = '{$statusType}' AND `ord_payment_type`  = '{$payment_type_Online}'),1,0)) as tong_luot_online,
 			SUM(if((`ord_status_code` = '{$statusType}' AND `ord_payment_type`  = '{$payment_type_Online}'),`ord_amount`,0)) as tong_tien_online,
 			SUM(if((`ord_status_code` = '{$statusType}' AND `ord_payment_type`  = '{$payment_type_Wallet}'),1,0)) as tong_luot_vi,
 			SUM(if((`ord_status_code` = '{$statusType}' AND `ord_payment_type`  = '{$payment_type_Wallet}'),`ord_amount`,0)) as tong_tien_vi,
			SUM(if((`ord_status_code` = '{$statusType}'),`ord_amount`,0)) as tong_tien_order,
			SUM(if((`ord_status_code` = '{$statusType}'),1,0)) as tong_luot
 FROM `orders`
 WHERE 
 	`ord_created_at` > {$dateSelect}
 	AND `ord_status_code` = '{$statusType}'
 	GROUP BY datetime
 	ORDER BY datetime DESC";

$db_query = new db_query($select);



$items = $db_query->result;

$total = 35;

$dataGrid = new DataGrid($items, $total, 'datetime');

$dataGrid->column('datetime', 'Thời gian', 'string');
$dataGrid->column(false, 'COD', function($row){
    return  number_format($row['tong_tien_cod']) . "vnđ/" .number_format($row['tong_luot_cod']) . " lượt";
});
$dataGrid->column(false, 'ONLINE', function($row){
    return  number_format($row['tong_tien_online']) . "vnđ/" .number_format($row['tong_luot_online']) . " lượt";
});
$dataGrid->column(false, 'VÍ', function($row){
    return  number_format($row['tong_tien_vi']) . "vnđ/" .number_format($row['tong_luot_vi']) . " lượt";
});

$dataGrid->column(false, 'Tổng', function($row){
    return  number_format($row['tong_tien_order']) . "vnđ/" .number_format($row['tong_luot']) . " lượt";
});


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
