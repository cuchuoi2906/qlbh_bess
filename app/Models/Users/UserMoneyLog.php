<?php
/**
 * Created by PhpStorm.
 * User: MyLove
 * Date: 4/23/2019
 * Time: 3:57 PM
 */

namespace App\Models\Users;

use App\Models\AdminUser;
use App\Models\Banks;
use VatGia\Model\Model;

class UserMoneyLog extends Model
{
    public $table = 'user_money_logs';
    public $prefix = 'uml';
}