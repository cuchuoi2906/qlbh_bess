<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-07
 * Time: 17:03
 */

namespace App\Models;

use App\Models\Users\UserBank;
use App\Models\Users\Users;
use VatGia\Model\Model;

class UserMoneyLog extends Model
{
    public $table = 'user_money_logs';
    public $prefix = 'uml';

    CONST TYPE_COMMISSION = 'commission';
    CONST TYPE_MONEY_ADD = 'money_add';
    CONST TYPE_TRANSFER = 'transfer';

    const SOURCE_ADMIN = 0;
    const SOURCE_BANK = 1;
    const SOURCE_COMMISSION = 2;
    const SOURCE_TRANSFER = 3;
    const SOURCE_DONATE = 4;

    const SOURCE_TYPE_DEFAULT = 0;
    const SOURCE_TYPE_ORDER = 1;
    const SOURCE_TYPE_USER = 2;
    const SOURCE_TYPE_PAYMENT = 3;


    public static function types()
    {
        return [
            self::TYPE_COMMISSION => 'Ví hoa hồng',
            self::TYPE_MONEY_ADD => 'Ví tiêu dùng',
            self::TYPE_TRANSFER => 'Đổi hoa hồng',
        ];
    }

    public static function source()
    {

        return [
            self::SOURCE_ADMIN => 'Admin nạp',
            self::SOURCE_BANK => 'Ngân hàng',
            self::SOURCE_COMMISSION => 'Chuyển từ ví hoa hồng',
            self::SOURCE_TRANSFER => 'Chuyển sang ví tiêu dùng',
            self::SOURCE_DONATE => 'Tặng tiền người dùng',

        ];
    }

    public function order()
    {
        $this->hasOne(
            __FUNCTION__,
            Order::class,
            'ord_id',
            'uml_source'
        );
    }

    public function paymentBank()
    {

        $this->hasOne(
            __FUNCTION__,
            UserBank::class,
            'usb_id',
            'uml_source'
        );
    }

    public function user()
    {

        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'uml_user_id'
        );
    }
}