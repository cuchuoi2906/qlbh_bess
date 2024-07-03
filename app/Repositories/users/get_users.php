<?php
/**
 * Created by PhpStorm.
 * User: Huanvv
 * Date: 3/2/2019
 * Time: 8:58 AM
 */

use App\Models\Users\Users;
use App\Transformers\UserTransformer;
use VatGia\Helpers\Transformer\TransformerPaginatorAdapter;

$vars = [];
$id = input('id') ?? 0;
$page = input('page') ?? getValue('page', 'int', 'GET', 1);
$page_size = input('page_size') ?? 10;

$user = App\Models\Users\Users::findByID($id);
if (!$user) {
    throw new RuntimeException('Người dùng không tồn tại', 404);
}
$start_date = input('start_date');
$end_date = input('end_date');

$conditions = "use_referral_id = $id AND use_active = 1";
if(!empty($start_date) && !empty($end_date)){
    $dateNows = $end_date.' 23:59:59';
    $dayfristM = $start_date.' 00:00:01';
}else{
    $dateNows = date('Y-m-d 23:59:59');
    $dayNows = intval(date('d'))-1;
    $dayfristM = date( "Y-m-d 00:00:01", strtotime($dateNows." -$dayNows day" ) ); // PHP:  2009-03-04
}

$v_range_date_where = " AND ord_created_at BETWEEN '" . $dayfristM . "' AND '" . $dateNows . "'";
//var_dump($v_range_date_where);die;
$modelCount = Users::where($conditions);
$model = Users::where($conditions)
    ->fields('users.*, COUNT(ord_id) AS total_order, SUM(IF(ord_status_code = \'' . \App\Models\Order::SUCCESS . '\', 1, 0)) AS total_order_success,SUM(case when ord_status_code != \'' . \App\Models\Order::NEW . '\' AND ord_status_code != \'' . \App\Models\Order::CANCEL . '\' '.$v_range_date_where.' then ord_amount end) AS total_ord_amount')
    ->left_join('orders', 'ord_user_id = use_id')
    ->group_by('use_id')
    ->pagination($page, $page_size);

switch (input('sort')) {
    case 'level':
        $model->order_by('use_level', 'DESC');
        break;
    case 'direct':
        $model->order_by('use_total_direct_refer', 'DESC');
        break;
    case 'commission':
        $model->order_by('total_ord_amount', 'DESC');
        break;
    default:
        $model->order_by('use_created_at', 'DESC');
        break;
}

if (input('keyword')) {
    $model->where('use_phone LIKE \'%' . input('keyword') . '%\' OR use_name LIKE \'%' . input('keyword') . '%\'');
    $modelCount->where('use_phone LIKE \'%' . input('keyword') . '%\' OR use_name LIKE \'%' . input('keyword') . '%\'');

}
$collaborators = $model->all();

$total = $modelCount->count();

$paginator = new TransformerPaginatorAdapter($total, $page, $page_size);

if ($collaborators) {

    $vars = transformer_collection_paginator($collaborators, new UserTransformer(), $paginator, [], [
        'direct' => [
            'total' => $user->use_total_direct_refer,
            'total_display' => number_format($user->use_total_direct_refer)
        ],
        'users_all' => [
            'total' => $user->use_total_refer,
            'total_display' => number_format($user->use_total_refer)
        ]
    ]);
}

return [
    'vars' => $vars
];