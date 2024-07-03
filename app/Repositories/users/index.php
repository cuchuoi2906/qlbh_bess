<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/22/19
 * Time: 13:23
 */

$vars = [];

$page = input('page') ?? getValue('page', 'int', 'GET', 0);
$page_size = input('page_size') ?? input('limit') ?? getValue('limit', 'int', 'GET', 10);;

$sort = input('sort') ?? 'id';
$sort = 'use_' . $sort;
$sort_type = input('sort_type') ?? 'DESC';

if ($sort == 'use_total_direct_refer' && setting('swe_event_best_team_active')) {
    //Lấy team mạnh nhất từ bảng best_team
    //Lấy cấu hình về đua team
    $start_date = setting('swe_event_best_team_start');
    $start_date = date('Y-m-d', strtotime($start_date));
    $end_date = setting('swe_event_best_team_end');
    $end_date = date('Y-m-d', strtotime($end_date));

    $items = \App\Models\Users\Users::where('use_active', 1)
        ->fields('*, SUM(bes_point) AS use_commission')
        ->inner_join('best_team', 'bes_user_id = use_id AND bes_type = ' . \App\Models\BestTeam::TYPE_TEAM_COMMISSION)
        ->where('bes_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'')
        ->group_by('use_id')
        ->order_by('use_commission', $sort_type)
        ->pagination($page, $page_size)
        ->all();

} else {
    $items = \App\Models\Users\Users::where('use_active', 1)
        ->order_by($sort, $sort_type)
        ->pagination($page, $page_size)
        ->all();
}

if ($items->count()) {
    $vars = transformer_collection($items, new \App\Transformers\UserTransformer());
}

return [
    'vars' => $vars
];