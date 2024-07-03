<?php
/**
 * Created by PhpStorm.
 * User: Huanvv
 * Date: 3/12/2019
 * Time: 1:25 PM
 */

use \App\Models\NotificationStatus;
use VatGia\Helpers\Transformer\TransformerPaginatorAdapter;

$vars = [];
$id = input('user_id') ?? 0;
$page = input('page') ?? 1;
$page_size = input('page_size') ?? 10;

$user = \App\Models\Users\Users::findByID($id);
if (!$user) {
    throw new Exception('User không tồn tại.', 400);
}


$model = NotificationStatus::with(['notification'])
    ->inner_join('notification', 'nts_notification_id = not_id')
    ->where('nts_user_id', $user->id)
    ->order_by('nts_notification_id', 'DESC');

$total = $model->count();

$items = $model->pagination($page, $page_size)->all();

$unread = $model->where('nts_status', '=', 0)->count();

$paginator = new TransformerPaginatorAdapter($total, $page, $page_size);

if ($items) {
    $vars = transformer_collection_paginator($items, new \App\Transformers\NotificationStatusTransformer(), $paginator, [], [
        'unread' => $unread
    ]);
}

return [
    'vars' => $vars
];