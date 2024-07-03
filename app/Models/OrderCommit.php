<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-04
 * Time: 16:29
 */

namespace App\Models;

use VatGia\Model\Model;

class OrderCommit extends Model
{
    public $table = 'order_commits';
    public $prefix = 'orc';


    CONST STATUS_NEW = 'new';
}