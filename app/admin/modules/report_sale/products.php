<?php

require_once 'inc_security.php';

$items_model = new \App\Models\OrderProduct();

$range_date = getValue('ord_successed_at', 'str', 'GET', '', 3);

if (!$range_date) {
    $start_month = new DateTime('first day of this month');
    $start_day = $start_month->format('d/m/Y');
    $range_date = date('01/01/2018') . ' - ' . date('d/m/Y');
}

if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $items_model->where("ord_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . "'");
}

//Lấy tổng số lượng bán hàng theo sản phẩm
$pro_name = getValue('pro_name_vn', 'str', 'GET', '', 3);
if(empty($pro_name)){
	$pro_name = 'Alpha Choay hộp 2 vỉ x 15 viên nén Sanofi';
}
//$sqlWhere = "pro_parent_id = 0";
$sqlWhere = "1";

if ($pro_name) {
    $sqlWhere .= " AND pro_name_vn LIKE '%" . fw24h_replace_bad_char($pro_name) . "%'";
}

$orp_pharmacy_hapu = getValue('orp_pharmacy_hapu', 'str', 'GET', '', 3);
if ($orp_pharmacy_hapu) {
    $sqlWhere .= " AND orp_pharmacy_hapu LIKE '%" . fw24h_replace_bad_char($orp_pharmacy_hapu) . "%'";
}

$orp_supplier_hapu = getValue('orp_supplier_hapu', 'str', 'GET', '', 3);
if ($orp_supplier_hapu) {
    $sqlWhere .= " AND orp_supplier_hapu LIKE '%" . $orp_supplier_hapu . "%'";
}


$items_model->fields('CONCAT(pro_id, "_", orp_pharmacy_hapu) AS fake_id,orp_product_id, pro_name_vn,orp_pharmacy_hapu,orp_supplier_hapu
        ,MIN(order_products.orp_price_hapu) as price_hapu
        ,MAX(ord_created_at) as ord_created_at
        ,SUM(orp_price_hapu * orp_quantity) AS total_money_product
        ,pro_id,SUM(orp_quantity) total_product_quantity
        ,DATE_FORMAT(ord_created_at, "%d-%m-%Y") AS date_group')
    ->inner_join('products', 'orp_product_id = pro_id')
    ->inner_join('orders', 'ord_id = orp_ord_id')
    ->where('orp_price_hapu', '>',0)
    ->where('ord_status_code', 'NOT IN',[App\Models\Order::CANCEL,App\Models\Order::NEW])
    //->where('orp_pharmacy_hapu', '!=','')
    ->where($sqlWhere)
    ->order_by('orp_pharmacy_hapu','DESC')
    ->group_by('order_products.orp_product_id')
    ->group_by('date_group')
    ->group_by('order_products.orp_pharmacy_hapu');

if(isset($_GET['export']) && $_GET['export'] == 'Export'){
    $per_page = 20000;
}
if (!getValue('export', 'str') == 'Export') {
    $querryString  = $items_model->toSelectQueryString();
    $querryStringCount = 'SELECT SUM(total_money_product) As total_money_product_all
                FROM ('.$querryString.') a';

    $db_data_querryStringCount = new db_query($querryStringCount);
    $row_querryStringCount = $db_data_querryStringCount->fetch();
    $total_money = $row_querryStringCount[0]['total_money_product_all'];
}
$total = $items_model->all()->count();
$items= $items_model->pagination((int)getValue('page'), $per_page)->all();

$querryString  = $items_model->toSelectQueryString();
//var_dump($querryString);die;
$dataGrid = new DataGrid($items, $total, 'fake_id',$per_page);
if(isset($_GET['export']) && $_GET['export'] == 'Export'){
    $dataGrid->dataExport = $items_model->all();
}
//Search
$dataGrid->search('ord_successed_at', 'Thời gian', 'string', true);

$dataGrid->column('pro_name_vn', 'Tên sản phẩm', ['string', 'trim'], true, true)->addExport();
$dataGrid->column('ord_created_at', 'Ngày đặt', function ($row) {
    return (new DateTime($row->ord_created_at))->format('d/m/Y');
}, true)->addExport();
$dataGrid->column('orp_supplier_hapu', 'Nhà sản xuất', ['string', 'trim'], true,true)->addExport();
$dataGrid->column('total_product_quantity', 'Số lượng', 'number', true)->addExport();
$dataGrid->column('orp_pharmacy_hapu', 'Quầy', ['string', 'trim'], true,true)->addExport();
$dataGrid->column('price_hapu', 'Giá', function($row){
    return number_format($row->price_hapu);
}, true)->addExport();
$dataGrid->column('total_money_product', 'Tổng tiền', function($row){
    return number_format($row->total_money_product);
}, true)->addExport();
$dataGrid->total('total_money_product', $total_money, '');

echo $blade->view()->make('products', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
