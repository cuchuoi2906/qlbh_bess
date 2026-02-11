<?php

use App\Models\Order;

$vars = [];

$adm_id = input('adm_id');
$adminUser = App\Models\AdminUser::findByID($adm_id);
if (!$adminUser) {
    api_404();
}

//$conditions = 'ord_status_code IN ("'.Order::NEW.'","'.Order::PENDING.'","'.Order::BEING_TRANSPORTED.'")';
if($adm_id == 109){
    $conditions = 'ord_status_code IN ("'.Order::NEW.'")';
    $orderModel = Order::where('ord_status_process = 2 OR ord_status_process = 1');
}else{
    $conditions = 'ord_status_code IN ("'.Order::NEW.'","'.Order::PENDING.'")';
    $orderModel = Order::where('ord_admin_userprice_id', $adminUser->id);
}
$orderModel->order_by('ord_status_process', 'ASC')->order_by('ord_created_at', 'DESC')
    ->where($conditions);
$items = $orderModel->all();

$paginator = new \VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, input('page') ?? 1, input('page_size') ?? 10);
if ($items) {
    $vars = transformer_collection($items, new \App\Transformers\OrderTransformer(),['products','useradminhapu','user']);
}
if(isset($_GET['test'])){
	//var_dump($vars);die;
}
if(isset($_GET['test'])){
	//pre($vars);die;
}
//var_dump($vars);die;
return [
    'vars' => $vars
];