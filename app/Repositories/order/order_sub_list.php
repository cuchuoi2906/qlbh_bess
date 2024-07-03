<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/20/19
 * Time: 23:26
 */

$vars = [];
$page = input('page') ?? getValue('page', 'int', 'GET', 1);
$page_size = input('page_size') ?? 10;
if(isset($_GET['user_id']) && intval($_GET['user_id']) > 0){
    $user = \App\Models\Users\Users::findByID(intval($_GET['user_id']));
}else{
    $user = \App\Models\Users\Users::findByID(input('user_id'));
}
if ($user) {

    $end_date = input('end_date');
    if (!$end_date) {
        $end_date = date('Y-m-d');
    }
    $sql = 'DATE(ord_created_at) <= \'' . $end_date . '\'';

    if (input('start_date')) {
        $sql .= ' AND DATE(ord_created_at) >= \'' . input('start_date') . '\'';
    }
    if(isset($_GET['user_id']) && intval($_GET['user_id']) > 0){
        $model = \App\Models\Order::with(['products', 'user'])
            ->where('ord_active', 1)
            ->where($sql)
            ->inner_join('order_commissions', 'ord_id = orc_order_id AND orc_user_id = ' . $user->id . ' AND orc_is_owner = 0');
    }else{
        $model = \App\Models\Order::with(['products', 'user'])
            ->where('ord_active', 1)
            ->where($sql)
            ->where('ord_status_code', '<>', \App\Models\Order::CANCEL)
            ->inner_join('order_commissions', 'ord_id = orc_order_id AND orc_user_id = ' . $user->id . ' AND orc_is_owner = 0');
    }
    $items = $model
        ->pagination($page, $page_size)
        ->order_by('ord_id', 'DESC')
        ->all();
    if(isset($_GET['user_id']) && intval($_GET['user_id']) > 0){
        pre($items);die;
    }
    $total = $model->count();

    if ($items->count()) {
        $pagination = new \VatGia\Helpers\Transformer\TransformerPaginatorAdapter($total, $page, $page_size);

        $vars = transformer_collection_paginator($items, new \App\Transformers\OrderTransformer(), $pagination, ['products', 'user']);
    }
    if(isset($_GET['user_id']) && intval($_GET['user_id']) > 0){
        pre($vars);die;
    }
    //pre($vars);die;

}

return [
    'vars' => $vars
];