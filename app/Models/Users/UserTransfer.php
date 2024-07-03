<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-16
 * Time: 00:52
 */

namespace App\Models\Users;



use App\Models\Model;

class UserTransfer extends Model
{
    public $table = 'user_transfer';
    public $prefix = 'ust';
}