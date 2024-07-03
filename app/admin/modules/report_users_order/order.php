<?php

use App\Models\Users\Users;

require_once 'inc_security.php';

$model = new App\Models\Users\Users();
$sqlWhere = "1";

$range_date = getValue('ord_created_at', 'str', 'GET', '', 3);
if (!$range_date) {
//    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}
$v_range_date_where  = '';
if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $date_type = getValue('date_type', 'int', 'GET', 1);
    $date_field = ' AND ord_created_at';

    //$sqlWhere .= $date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
    $v_range_date_where = $date_field . " BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'";
}
$user_id_list = getValue('use_id', 'str', 'GET', '', 3);
$view_option = getValue('view_option', 'int', 'GET', '', 3);
if ($user_id_list) {
    $sqlWhere .= " AND use_id IN (" . $user_id_list . ")";
}
$model->where($sqlWhere);
$v_sub_querry = '';
if($view_option == 2){
    $v_sub_querry = str_replace('<!--FILLTER_DATE-->', $v_range_date_where, $v_sub_querry_select);
}
$model->with(['wallet', ['childs', function (Users $model) {
    return $model->withTrash();
}], 'parent'])
    ->left_join('orders', 'ord_user_id = use_id '.$v_range_date_where);
    
if($view_option == 2){
    $model->order_by('totalreferralamountorder', 'DESC');
}else{
    $model->order_by('totalAmount', 'DESC');
}
$model ->setFields('users.*
        ,SUM(case when ord_status_code = \'NEW\' then ord_amount end) totalNew
        ,SUM(case when ord_status_code = \'PENDING\' then ord_amount end) totalPending
        ,SUM(case when ord_status_code = \'BEING_TRANSPORTED\' then ord_amount end) totalBeginTransport
        ,SUM(case when ord_status_code = \'RECEIVED\' then ord_amount end) totalReceiced
        ,SUM(case when ord_status_code = \'CANCEL\' then ord_amount end) totalCancel
        ,SUM(case when ord_status_code = \'SUCCESS\' then ord_amount end) totalSuccess
        ,SUM(case when ord_payment_type = \'COD\' AND ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_amount end) totalCod
        ,SUM(case when ord_payment_type = \'WALLET\' AND ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_amount end) totalWallet
        ,SUM(case when ord_payment_type = \'ONLINE\' AND ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_amount end) totalOnline
        ,SUM(case when ord_payment_type = \'BANKING\' AND ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_amount end) totalBank
        ,SUM(case when ord_payment_status = 0 AND ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_amount end) totalUnPayment
        ,SUM(case when ord_payment_status = 1 AND ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_amount end) totalPayment
        ,SUM(case when ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_amount end) totalAmount
        ,SUM(case when ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_shipping_fee end) totalShippingFee
        ,SUM(case when ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' then ord_auto_shipping_fee end) totalAutoShippingFee
        ,(SELECT SUM(orc_amount) FROM orders INNER JOIN order_commissions ON orc_order_id = ord_id WHERE ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' AND ord_user_id = use_id '.$v_range_date_where.' AND orc_type = 0 AND orc_is_direct = 0) totalOrcAmount
        ,(SELECT SUM(orc_vat)FROM orders INNER JOIN order_commissions ON orc_order_id = ord_id WHERE ord_status_code != \'CANCEL\' AND ord_status_code != \'REFUND\' AND ord_user_id = use_id '.$v_range_date_where.' AND orc_type = 0 AND orc_is_direct = 0) totalOrcVat
        '.$v_sub_querry);
$modelSelect = $model->group_by('use_id');
$querryString  = $modelSelect->toSelectQueryString();
//var_dump($querryString);
$querryStringCount = 'SELECT count(*) As count
            FROM ('.$querryString.') a';
                
$db_data_querryStringCount = new db_query($querryStringCount);
$row_querryStringCount = $db_data_querryStringCount->fetch();

$model->pagination(getValue('page'), $per_page);
$items= $model->all();
$total = intval($row_querryStringCount[0]["count"]);
if (!getValue('export', 'str') == 'Export') {
    $v_sub_querry_total = '';
    global $view_option;
    if($view_option == 2){
        $v_sub_querry_total = $v_sub_querry_select_total;
    }
    
    $querryStringTotal = 'SELECT SUM(a.totalNew) as totalNewAll
            ,SUM(a.totalPending) as totalPendingAll
            ,SUM(a.totalBeginTransport) as totalBeginTransportAll
            ,SUM(a.totalReceiced) as totalReceicedAll
            ,SUM(a.totalCancel) as totalCancelAll
            ,SUM(a.totalSuccess) as totalSuccessAll
            ,SUM(a.totalCod) as totalCodAll
            ,SUM(a.totalWallet) as totalWalletAll
            ,SUM(a.totalOnline) as totalOnlineAll
            ,SUM(a.totalBank) as totalBankAll
            ,SUM(a.totalUnPayment) as totalUnPaymentAll
            ,SUM(a.totalPayment) as totalPaymentAll
            ,SUM(a.totalAmount) as totalAmountAll
            ,SUM(a.totalShippingFee) as totalShippingFeeAll
            ,SUM(a.totalAutoShippingFee) as totalAutoShippingFeeAll
            ,SUM(a.totalOrcAmount) as totalOrcAmountAll
            ,SUM(a.totalOrcVat) as totalOrcVatAll
            '.$v_sub_querry_total.'
            FROM ('.$querryString.') a
            ';
    //var_dump($querryStringTotal);
    $db_data_querryStringTotal = new db_query($querryStringTotal);
    $row_querryStringTotal = $db_data_querryStringTotal->fetch();
    $totalNewAll = $row_querryStringTotal[0]['totalNewAll'] + $row_querryStringTotal[0]['totalNewreferralAll'];
    $totalPendingAll = $row_querryStringTotal[0]['totalPendingAll'] + $row_querryStringTotal[0]['totalPendingreferralAll'];
    $totalBeginTransportAll = $row_querryStringTotal[0]['totalBeginTransportAll'] + $row_querryStringTotal[0]['totalBeginTransportreferralAll'];
    $totalReceicedAll = $row_querryStringTotal[0]['totalReceicedAll'] + $row_querryStringTotal[0]['totalReceicedreferralAll'];
    $totalCancelAll = $row_querryStringTotal[0]['totalCancelAll'] + $row_querryStringTotal[0]['totalCancelreferralAll'];
    $totalSuccessAll = $row_querryStringTotal[0]['totalSuccessAll'] + $row_querryStringTotal[0]['totalSuccessreferralAll'];
    $totalCodAll = $row_querryStringTotal[0]['totalCodAll'] + $row_querryStringTotal[0]['totalCodreferralAll'];
    $totalWalletAll = $row_querryStringTotal[0]['totalWalletAll'] + $row_querryStringTotal[0]['totalWalletreferralAll'];
    $totalOnlineAll = $row_querryStringTotal[0]['totalOnlineAll'] + $row_querryStringTotal[0]['totalOnlinereferralAll'];
    $totalBankAll = $row_querryStringTotal[0]['totalBankAll'] + $row_querryStringTotal[0]['totalBankreferralAll'];
    $totalUnPaymentAll = $row_querryStringTotal[0]['totalUnPaymentAll'] + $row_querryStringTotal[0]['totalUnPaymentreferralAll'];
    $totalPaymentAll = $row_querryStringTotal[0]['totalPaymentAll'] + $row_querryStringTotal[0]['totalPaymentreferralAll'];
    $totalAmountAll = $row_querryStringTotal[0]['totalAmountAll'] + $row_querryStringTotal[0]['totalAmountreferralAll'];
    $totalShippingFeeAll = $row_querryStringTotal[0]['totalShippingFeeAll'] + $row_querryStringTotal[0]['totalShippingFeereferralAll'];
    $totalAutoShippingFeeAll = $row_querryStringTotal[0]['totalAutoShippingFeeAll'] + $row_querryStringTotal[0]['totalAutoShippingFeereferralAll'];
    $totalOrcAmountAll = $row_querryStringTotal[0]['totalOrcAmountAll'] + $row_querryStringTotal[0]['totalOrcAmountreferralAll'];
    $totalOrcVatAll = $row_querryStringTotal[0]['totalOrcVatAll'] + $row_querryStringTotal[0]['totalOrcVatreferralAll'];
    
    $phi_ship_minh_chiu = (int)$totalShippingFeeAll;
    $phi_ship_khach_chiu = (int)$totalAutoShippingFeeAll;
    $commission = $totalOrcAmountAll;
    $commission_vat =$totalOrcVatAll;
    $total_commission = $commission + $commission_vat;
    
    $revenueTotal = $totalAmountAll + $phi_ship_khach_chiu - $total_commission - $phi_ship_minh_chiu;
    
}
$dataGrid = new DataGrid($items, $total, 'use_id');
$dataGrid->model = $model;
$dataGrid->deleteLabel = 'hủy';
//Search
$dataGrid->search('ord_created_at', 'Thời gian', 'string', true);
$dataGrid->column('use_id', 'ID', 'number', [], true)->addExport();
$dataGrid->search(['view_option',$array_view_option], 'Hình thức xem', 'selectShow', true);
$dataGrid->column('use_name', 'Tên', ['string', 'trim'], [])->addExport();
$dataGrid->column('totalNew', 'Đơn hàng mới', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalNew'] =  $row['totalNew'] +  $row['totalNewreferral']; 
    }
    return number_format($row['totalNew']);
})->addExport();
$dataGrid->total('totalNew', $totalNewAll, '');

$dataGrid->column('totalPending', 'Chờ xử lý', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalPending'] =  $row['totalPending'] +  $row['totalPendingNewreferral'];
    }
    return number_format($row['totalPending']);
})->addExport();
$dataGrid->total('totalPending', $totalPendingAll, '');

$dataGrid->column('totalBeginTransport', 'Đang vận chuyển', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalBeginTransport'] =  $row['totalBeginTransport'] +  $row['totalBeginTransportNewreferral'];
    }
    return number_format($row['totalBeginTransport']);
})->addExport();
$dataGrid->total('totalBeginTransport', $totalBeginTransportAll, '');

$dataGrid->column('totalReceiced', 'Đã nhận hàng', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalReceiced'] =  $row['totalReceiced'] +  $row['totalReceicedNewreferral'];
    }
    return number_format($row['totalReceiced']);
})->addExport();
$dataGrid->total('totalReceiced', $totalReceicedAll, '');


/*$dataGrid->column('totalCancel', 'Đã hủy', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalCancel'] =  $row['totalCancel'] +  $row['totalCancelNewreferral'];
    }
    return number_format($row['totalCancel']);
})->addExport();
$dataGrid->total('totalCancel', $totalCancelAll, '');*/

$dataGrid->column('totalSuccess', 'Thành công', function ($row) {
    global $view_option;
    $totalSuccess =$row['totalSuccess'];
    if($view_option == 2){
        $totalSuccess =  $totalSuccess +  $row['totalSuccessNewreferral'];
    }
    return number_format($totalSuccess);
})->addExport();
$dataGrid->total('totalSuccess', $totalSuccessAll, '');

$dataGrid->column('totalCod', 'COD', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalCod'] =  $row['totalCod'] +  $row['totalCodNewreferral'];
    }
    return number_format($row['totalCod']);
})->addExport();
$dataGrid->total('totalCod', $totalCodAll, '');

$dataGrid->column('totalWallet', 'VÍ', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalWallet'] =  $row['totalWallet'] +  $row['totalWalletNewreferral'];
    }
    return number_format($row['totalWallet']);
})->addExport();
$dataGrid->total('totalWallet', $totalWalletAll, '');


$dataGrid->column('totalOnline', 'ONLINE', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalOnline'] =  $row['totalOnline'] +  $row['totalOnlineNewreferral'];
    }
    return number_format($row['totalOnline']);
})->addExport();
$dataGrid->total('totalOnline', $totalOnlineAll, '');


$dataGrid->column('totalBank', 'CK', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalBank'] =  $row['totalBank'] +  $row['totalBankNewreferral'];
    }
    return number_format($row['totalBank']);
})->addExport();
$dataGrid->total('totalBank', $totalBankAll, '');

$dataGrid->column('totalPayment', 'Đã thanh toán', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalPayment'] =  $row['totalPayment'] +  $row['totalPaymentNewreferral'];
    }
    return number_format($row['totalPayment']);
})->addExport();
$dataGrid->total('totalPayment', $totalPaymentAll, '');

$dataGrid->column('totalUnPayment', 'Chưa thanh toán', function ($row) {
    global $view_option;
    if($view_option == 2){
        $row['totalUnPayment'] =  $row['totalUnPayment'] +  $row['totalUnPaymentNewreferral'];
    }
    return number_format($row['totalUnPayment']);
})->addExport();
$dataGrid->total('totalUnPayment', $totalUnPaymentAll, '');

$dataGrid->column('totalAmount', 'Giá trị đơn hàng', function ($row) {
    global $view_option;
    $totalAmount1 = $row['totalAmount'];
    if($view_option == 2){
        $totalAmount1 =  $totalAmount1 +  $row['totalAmountNewreferral'];
    }
    return number_format($totalAmount1);
})->addExport();
$dataGrid->total('totalAmount', $totalAmountAll, '');

$v_uni_doanh_thu_thuc = uniqid();
$dataGrid->column($v_uni_doanh_thu_thuc, 'Doanh thu thực', function ($row) {
    global $view_option;
    $phi_ship_minh_chiu = (int)$row->totalShippingFee;
    $phi_ship_khach_chiu = (int)$row->totalAutoShippingFee;
    
    $commission = $row->totalOrcAmount;
    $commission_vat =$row->totalOrcVat;
    $totalAmount =$row->totalAmount;
    
    if($view_option == 2){
        $phi_ship_minh_chiu =  $phi_ship_minh_chiu +  $row->totalShippingFeeNewreferral;
        $phi_ship_khach_chiu =  $phi_ship_khach_chiu +  $row->totalAutoShippingFeeNewreferral;
        $commission =  $commission +  $row->totalOrcAmountNewreferral;
        $commission_vat = $commission_vat +  $row->totalOrcVatNewreferral;
        $totalAmount =  $totalAmount +  $row->totalAmountNewreferral;
    }
    /*var_dump('totalAmount: '.$totalAmount);
    var_dump('ship_khach_chiu: '.$phi_ship_khach_chiu);
    var_dump('totalOrcAmount: '. $commission);
    var_dump('totalOrcVat: '. $commission_vat);
    var_dump('phi_ship_minh_chiu: '. $phi_ship_minh_chiu);*/
    $total_commission = $commission + $commission_vat;
    $revenue = $totalAmount + $phi_ship_khach_chiu - $total_commission - $phi_ship_minh_chiu;
    return number_format($revenue);
})->addExport();
$dataGrid->total($v_uni_doanh_thu_thuc, $revenueTotal, '');

        
echo $blade->view()->make('order', [
    'data_table' => $dataGrid->render()
] + get_defined_vars())->render();
die;