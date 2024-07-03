<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 09/05/2021
 * Time: 09:11
 */

$user = \App\Models\Users\Users::findByID(input('user_id'));

$total = 0;
$money = 0;
$start_date = input('start_date');
$end_date = input('end_date');

$v_total_referre = 0;
$v_total_medium_referre = 0;
if ($user) {
    $model = \App\Models\Order::where('ord_user_id', $user->id);
    $model->where('ord_status_code','!=', \App\Models\Order::NEW);
    $model->where('ord_status_code','!=', \App\Models\Order::CANCEL);
    if ($start_date) {
        $model->where('ord_created_at', '>=', $start_date . ' 00:00:00');
    }
    if ($end_date) {
        $model->where('ord_created_at', '<=', $end_date . ' 23:59:59');
    }

    $total = $model->count();
    $money = $model->sum('ord_amount', 'total')->select();
    $money = $money->total;
    $where_date = '';
    if ($start_date) {
        $where_date .= "AND ord_created_at >='". $start_date ." 00:00:00'";
    }
    if ($end_date) {
        $where_date .= "AND ord_created_at <= '".$end_date ." 23:59:59'";
    }
    /*if(isset($_GET['debug'])){
        echo "SELECT ord_amount FROM users INNER JOIN orders ON ord_user_id = use_id WHERE 1=1 ".$where_date." AND ord_status_code != 'NEW' AND ord_status_code != 'CANCEL' AND use_referral_id = " . $user->id." ORDER BY ord_amount DESC";
    }*/
    $db_data = new db_query("SELECT ord_amount FROM users INNER JOIN orders ON ord_user_id = use_id WHERE 1=1 ".$where_date." AND ord_status_code != 'NEW' AND ord_status_code != 'CANCEL' AND use_referral_id = " . $user->id." ORDER BY ord_amount DESC");
    if ($row = $db_data->fetch()) {
        if(check_array($row)){
            $v_count = count($row) > 5 ? 5 : count($row);
            $v_amount_total = 0;
            $i=0;
            foreach($row as $items){
                $v_total_referre  += intval($items['ord_amount']);
                if($i<5){
                    $v_amount_total  += intval($items['ord_amount']);
                }
                $i++;
            }
            $v_total_medium_referre = floor($v_amount_total/$v_count);
        }
    }
}

return [
    'vars' => [
        'total' => (int)$total,
        'total_display' => number_format($total),
        'money' => (int)$money,
        'money_display' => number_format($money),
        'campain_type' => 1,
        'total_medium_referre' => $v_total_medium_referre,
        'Reward_result' => ($money >=$v_total_medium_referre) ? 1 : 0, // 1: thỏa mãn đk nhận thưởng 0: không thỏa mãn
        'Reward_point' => ($money >=$v_total_medium_referre) ? ($money + $v_total_referre/2) : 0,
        'total_referre_all' => $v_total_referre,
    ]
];