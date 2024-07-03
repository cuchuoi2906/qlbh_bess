<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 4/23/19
 * Time: 02:17
 */

$total = 0;

$user = \App\Models\Users\Users::findByID(input('user_id'));
if ($user) {

    $model = App\Models\Users\Users::where('use_all_level', 'LIKE', '%.' . $user->id . '.%');
    if (input('start_date')) {
        $model->where('use_created_at', '>=', input('start_date') . ' 00:00:00');
    }
    if (input('end_date')) {
        $model->where('use_created_at', '<=', input('end_date') . ' 23:59:59');
    }

    $total = $model->count();

}

return [
    'vars' => [
        'total' => (int)$total,
        'total_display' => number_format($total)
    ]
];