<?php

use App\Models\Order;

require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

/**
 * Module id.
 * Thay thế bằng id lấy từ mục 'Cấu hình module'
 */
$status = getValue('status', 'str', 'GET', '');
$status = strtoupper($status);
$status_list = [];
switch ($status) {
    case 'NEW':
        $module_id = 30;
        $status_list = [
            \App\Models\Order::NEW => 'Đơn hàng mới',
            \App\Models\Order::PENDING => 'Chờ xử lý',
            \App\Models\Order::CANCEL => 'Đã hủy'
        ];
        break;
    case 'PENDING':
        $module_id = 31;
        $status_list = [
            \App\Models\Order::PENDING => 'Chờ xử lý',
            \App\Models\Order::BEING_TRANSPORTED => 'Đang vận chuyển',
            \App\Models\Order::CANCEL => 'Đã hủy'
        ];
        break;
    case 'BEING_TRANSPORTED':
        $module_id = 32;
        $status_list = [
            \App\Models\Order::BEING_TRANSPORTED => 'Đang vận chuyển',
            \App\Models\Order::RECEIVED => 'Đã nhận hàng',
            \App\Models\Order::SUCCESS => 'Thành công',
            \App\Models\Order::REFUND => 'Đã hoàn',
            \App\Models\Order::CANCEL => 'Đã hủy'
        ];
        break;
    case Order::RECEIVED:
            $module_id = 50;
            $status_list = [
                \App\Models\Order::RECEIVED => 'Đã nhận hàng',
                \App\Models\Order::SUCCESS => 'Thành công',
                \App\Models\Order::CANCEL => 'Đã hủy'
            ];
            break;
    case 'SUCCESS':
        $status_list = [
            \App\Models\Order::SUCCESS => 'Thành công',
        ];
        $module_id = 33;
        break;
    case 'CANCEL':
        $module_id = 34;
        $status_list = [
            \App\Models\Order::CANCEL => 'Đã hủy',
            \App\Models\Order::NEW => 'Đơn hàng mới',
        ];
        break;
    default:
        $module_id = 29;
        break;
}
//$module_id = 21;

$module_name = "Đơn hàng";

function status_list($status)
{
    $status_list = [];
    switch ($status) {
        case 'NEW':
            $status_list = [
                \App\Models\Order::NEW => 'Đơn hàng mới',
                \App\Models\Order::PENDING => 'Chờ xử lý',
                \App\Models\Order::CANCEL => 'Đã hủy'
            ];
            break;
        case 'PENDING':
            $status_list = [
                \App\Models\Order::PENDING => 'Chờ xử lý',
                \App\Models\Order::BEING_TRANSPORTED => 'Đang vận chuyển',
                \App\Models\Order::CANCEL => 'Đã hủy'
            ];
            break;
        case 'BEING_TRANSPORTED':
            $status_list = [
                \App\Models\Order::BEING_TRANSPORTED => 'Đang vận chuyển',
                \App\Models\Order::RECEIVED => 'Đã nhận hàng',
                \App\Models\Order::SUCCESS => 'Thành công',
                \App\Models\Order::REFUND => 'Đã hoàn',
                \App\Models\Order::CANCEL => 'Đã hủy'
            ];
            break;
        case Order::RECEIVED:
                $status_list = [
                    \App\Models\Order::RECEIVED => 'Đã nhận hàng',
                    \App\Models\Order::SUCCESS => 'Thành công',
                    \App\Models\Order::CANCEL => 'Đã hủy'
                ];
                break;
        case 'SUCCESS':
            $status_list = [
                \App\Models\Order::SUCCESS => 'Thành công',
            ];
            break;
        case 'CANCEL':
            $status_list = [
                \App\Models\Order::CANCEL => 'Đã hủy',
                \App\Models\Order::NEW => 'Đơn hàng mới',
            ];
            break;
        case 'REFUND':
            $status_list = [
                \App\Models\Order::REFUND => 'Đã hoàn',
                \App\Models\Order::NEW => 'Đơn hàng mới',
            ];
            break;
        default:
            break;
    }

    return $status_list;
}

$array_date_type = [
    1 => 'Thơi gian đặt đơn',
    2 => 'Thời gian xử lý đơn',
];

//Check user login...
checkLogged();

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}

//Declare prameter when insert data
$fs_table = "orders";
$id_field = "ord_id";
$name_field = "ord_ship_name";
$break_page = "{---break---}";

$per_page = 20;

$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);
