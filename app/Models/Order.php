<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/6/18
 * Time: 01:47
 */

namespace App\Models;


use App\Models\Users\UserAddress;
use App\Models\Users\Users;
use VatGia\Model\Model;

class Order extends Model
{

    public $table = 'orders';
    public $prefix = 'ord';

    const NEW = 'NEW';
    const PENDING = 'PENDING';
    const BEING_TRANSPORTED = 'BEING_TRANSPORTED';
    const PAID = 'PAID'; //đã thanh toán
    const RECEIVED = 'RECEIVED';
    const SUCCESS = 'SUCCESS';
    const CANCEL = 'CANCEL';
    const REFUND = 'REFUND';

    const PAYMENT_STATUS_NEW = 0;
    const PAYMENT_STATUS_SUCCESS = 1;

    const PAYMENT_TYPE_COD = 'COD';
    const PAYMENT_TYPE_WALLET = 'WALLET';
    const PAYMENT_TYPE_ONLINE = 'ONLINE';
    const PAYMENT_TYPE_BANKING = 'BANKING';


    public static $status = [
        self::NEW => 'Đơn hàng mới',
        self::PENDING => 'Chờ xử lý',
        self::BEING_TRANSPORTED => 'Đang vận chuyển',
        self::RECEIVED => 'Đã nhận hàng',
        self::SUCCESS => 'Thành công',
        self::CANCEL => 'Hủy',
        self::REFUND => 'Hoàn',
    ];

    public static function paymentTypes()
    {
        return [
            self::PAYMENT_TYPE_COD => "COD",
            self::PAYMENT_TYPE_WALLET => "VÍ",
            self::PAYMENT_TYPE_ONLINE => "ONLINE",
            self::PAYMENT_TYPE_BANKING => "CK",
        ];
    }

    public static function paymentStatus()
    {
        return [
            self::PAYMENT_STATUS_SUCCESS => "Đã thanh toán",
            self::PAYMENT_STATUS_NEW => "Chưa thanh toán",
        ];

    }

    public function logs()
    {
        return $this->hasMany(
            __FUNCTION__,
            OrderLog::class,
            'orl_ord_id',
            'ord_id'
        );
    }

    public function products()
    {
        return $this->hasMany(
            __FUNCTION__,
            OrderProduct::class,
            'orp_ord_id',
            'ord_id'
        );
    }
	public function productsDetail()
    {

        return $this->belongsToMany(
            __FUNCTION__,
            Product::class,
			OrderProduct::class,
            'pro_id',
			'ord_id',
            'orp_product_id',
            'orp_ord_id'
        );
    }

    public function commissions()
    {
        return $this->hasMany(
            __FUNCTION__,
            OrderCommission::order_by('orc_amount', 'DESC'),
            'orc_order_id',
            'ord_id'
        );
    }

    public function commits()
    {
        return $this->hasMany(
            __FUNCTION__,
            OrderCommission::class,
            'orc_order_id',
            'ord_id'
        );
    }

    public function user()
    {
        $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'ord_user_id'
        );
    }

    public function address()
    {

        return $this->hasOne(
            __FUNCTION__,
            UserAddress::class,
            'usa_id',
            'ord_address_id'
        );
    }

    public function ward()
    {

        return $this->hasOne(
            __FUNCTION__,
            Ward::class,
            'war_id',
            'ord_ward_id'
        );
    }

    public function district()
    {

        return $this->hasOne(
            __FUNCTION__,
            District::class,
            'dis_id',
            'ord_district_id'
        );
    }

    public function province()
    {

        return $this->hasOne(
            __FUNCTION__,
            Province::class,
            'prov_id',
            'ord_province_id'
        );
    }

    public function getCommission()
    {
        $user_id = app('auth')->u_id;
        if (!$user_id) {
            return 0;
        }
        $item = OrderCommission::where('orc_user_id', $user_id)
            ->where('orc_order_id', $this->id)
            ->where('orc_type', '=', 0)
            ->where('orc_is_direct', '=', 0)
            ->first();

        $total_commisson = $item->amount ?? 0;

        if ($this->ord_commission_type == 2) {
            $item = OrderCommission::where('orc_user_id', $user_id)
                ->where('orc_order_id', $this->id)
                ->where('orc_type', '=', 0)
                ->where('orc_is_direct', '=', 1)
                ->first();
            $total_commisson += $item->amount ?? 0;
        }

        return $total_commisson;

    }

}