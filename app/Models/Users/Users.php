<?php

/**
 * Created by ntdinh1987.
 * User: ntdinh1987
 * Date: 11/15/16
 * Time: 2:34 PM
 */

namespace App\Models\Users;

use App\Models\Model;
use App\Models\Order;
use App\Models\OrderCommission;
use App\Models\Setting;

class Users extends Model {
    public $table = 'users';
    public $prefix = 'use';

    public $soft_delete = true;

    public $hidden = [
        'use_password',
        'use_security',
    ];

    const GENDERS = [
        0 => 'Nữ',
        1 => 'Nam'
    ];


    const LEVEL_0 = 0;

    const MAX_LEVEL = 100;

    /**
     * return string
     */
    public function getNextLevel() {
        $flag = 0;
        if ($this->level == self::MAX_LEVEL) {
            return self::MAX_LEVEL;
        } else {
            return $this->level + 1;
        }
    }

    /**
     * Tạo user vg from email
     * @param  string $email
     * @param  int $id
     * @return bool
     */
    public static function createUserFromDataIdVg($info) {
        $use_id_vatgia = (int)$info['id'];
        $use_id = (int)$info['id'];
        $use_active = 1;
        $use_group = 1;
        $use_email = $info['email'];
        $use_password = md5($info['email'] . '|' . $info['first_name']);
        $use_name = $info['first_name'] . ' ' . $info['last_name'];
        $use_phone = $info['phone'];
        $use_yahoo = '';
        $use_address = $info['address'];
        $use_birthdays = $info['dob'];
        $use_birthdays = strtotime($use_birthdays);
        $use_gender = ($info['gender'] == 'MALE') ? 1 : 0;
        $use_date = time();

        $use_idvg_access_token = $info['access_token'];
        $use_avatar = $info['avatar'];

        $myfrom = new \generate_form();
        $myfrom->add('use_id', 'use_id_vatgia', 1, 1, $use_id_vatgia);
        $myfrom->add('use_id_vatgia', 'use_id_vatgia', 1, 1, $use_id_vatgia);
        $myfrom->add('use_password', 'use_password', 0, 1, $use_password, 0, 'use_password');
        $myfrom->add('use_email', 'use_email', 0, 1, $use_email, 0, 'use_email');
        $myfrom->add('use_name', 'use_name', 0, 1, $use_name, 0, 'use_name');
        $myfrom->add('use_phone', 'use_phone', 0, 1, $use_phone, 0, 'use_phone');
        $myfrom->add('use_address', 'use_address', 0, 1, $use_address, 0, 'use_address');
        $myfrom->add('use_birthdays', 'use_birthdays', 0, 1, $use_birthdays, 0, 'use_birthdays');
        $myfrom->add('use_gender', 'use_gender', 1, 1, $use_gender, 0, 'use_gender');
        $myfrom->add('use_active', 'use_active', 1, 1, $use_active, 0, 'use_active');
        $myfrom->add('use_group', 'use_group', 1, 1, $use_group, 0, 'use_group');
        $myfrom->add('use_date', 'use_date', 1, 1, $use_date, 0, 'use_date');
        $myfrom->add('use_idvg_access_token', 'use_idvg_access_token', 0, 1, $use_idvg_access_token, 0, 'use_idvg_access_token');
        $myfrom->add('use_avatar', 'use_avatar', 0, 1, $use_avatar, 0, 'use_avatar');

        $myfrom->addTable('users');

        $myfrom->evaluate();
        $err = '';
        $err = $myfrom->checkdata();

        if (!$err || $err == '') {
            $new_user = new \db_execute_return();

            //Lưu thông tin vào bảng users_info
            if ($userId = $new_user->db_execute($myfrom->generate_replace_SQL())) {
                unset($new_user);

                return $userId;
            }
        }

        return false;
    }

    public function childs() {
        $this->hasMany(
            __FUNCTION__,
            static::class,
            'use_referral_id',
            'use_id'
        );
    }

    public function parent() {
        $this->hasOne(
            __FUNCTION__,
            static::class,
            'use_id',
            'use_referral_id'
        );
    }

    public function getGender() {
        return static::GENDERS[$this->gender] ?? 'Chưa rõ';
    }

    public function banks() {

        $this->hasMany(
            __FUNCTION__,
            UserBank::class,
            'usb_user_id',
            'use_id'
        );
    }

    public function devices() {
        $this->hasMany(
            __FUNCTION__,
            UserDevice::class,
            'usd_user_id',
            'use_id'
        );
    }

    public function cart() {
        $this->hasMany(
            __FUNCTION__,
            UserCart::class,
            'usc_user_id',
            'use_id'
        );
    }

    public function cartAdmin() {
        $this->hasMany(
            __FUNCTION__,
            UserCartAdmin::class,
            'usc_user_id',
            'use_id'
        );
    }

    public function wallet() {
        $this->hasOne(
            __FUNCTION__,
            UserWallet::class,
            'usw_user_id',
            'use_id'
        );
    }

    public function transfers() {
        $this->hasMany(
            __FUNCTION__,
            UserTransfer::class,
            'ust_user_id',
            'use_id'
        );
    }

    public function orders() {

        return $this->hasMany(
            __FUNCTION__,
            Order::class,
            'ord_user_id',
            'use_id'
        );
    }

    /**
     * @param null $date
     * @return int
     */
    public function getTotalCommissionForUpLevel($date = null, $end_date = null, $order_id = 0) {

        $order_condition_date = '';

        if ($date) {
            if (!$end_date) {
                $order_condition_date .= ' AND DATE(ord_successed_at) = \'' . $date . '\'';
            } else {
                $order_condition_date .= ' AND DATE(ord_successed_at) BETWEEN = \'' . $date . '\' AND \'' . $end_date . '\'';
            }
        }

        $success_condition = ' AND orc_status_code = \'' . OrderCommission::STATUS_SUCCESS . '\' ' . ' AND ord_status_code = \'' . Order::SUCCESS . '\'';
        if ($order_id) {
            $order_condition_date .= ' AND ord_id = ' . (int)$order_id;
            $success_condition = '';
        }

        $order_condition_date .= $success_condition;

        $total_dirrect_commission = OrderCommission::sum('orc_point', 'amount')
            ->inner_join('orders', 'ord_id = orc_order_id ' . $order_condition_date)
            ->where('orc_user_id', $this->id)
            // ->where('orc_status_code', OrderCommission::STATUS_SUCCESS)
            ->where('orc_is_direct', 1)
            ->first();

        if ($total_dirrect_commission) {
            $amount = (int)$total_dirrect_commission->amount;
        }

        $total_commission = OrderCommission::sum('orc_point', 'amount')
            ->inner_join('orders', 'ord_id = orc_order_id ' . $order_condition_date)
            // ->where('orc_status_code', OrderCommission::STATUS_SUCCESS)
            ->where('orc_user_id', $this->id)
            ->where('orc_is_direct', '=', 0)
            ->first();


        if ($total_commission) {
            $amount += (int)$total_commission->amount;
        }

        return $amount;
    }

    public function getOwnerCommission() {
        $total_dirrect_commission = OrderCommission::sum('orc_amount', 'amount')
            ->inner_join('orders', 'ord_id = orc_order_id AND ord_status_code = \'' . Order::SUCCESS . '\'')
            ->where('orc_status_code', OrderCommission::STATUS_SUCCESS)
            ->where('orc_user_id', $this->id)
            ->where('orc_is_owner', 1)
            ->first();

        return $total_dirrect_commission->amount ?? 0;
    }

    public function changePhone() {

        return $this->hasOne(
            __FUNCTION__,
            UserChangePhone::class,
            'ucp_user_id',
            'use_id'
        );
    }

    public function addresses() {

        return $this->hasMany(
            __FUNCTION__,
            UserAddress::order_by('usa_is_main', 'DESC'),
            'usa_user_id',
            'use_id'
        );
    }

    public function defaultAddress() {

        return $this->hasOne(
            __FUNCTION__,
            UserAddress::where('usa_default', 1),
            'usa_user_id',
            'use_id'
        );
    }
    public function getSettingLever() {
        $value = Setting::where('swe_key', 'lever_duoc_phep_gioi_thieu') ->first();

        return $value->swe_value_vn ?? 0;
    }
    public function provice() {

        return $this->hasMany(
            __FUNCTION__,
            Province::class,
            'prov_id',
            'use_province_id'
        );
    }
}
