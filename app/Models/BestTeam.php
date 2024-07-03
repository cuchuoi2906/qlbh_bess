<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 3/22/20
 * Time: 10:55
 */

namespace App\Models;


use App\Models\Users\Users;

class BestTeam extends Model
{

    public $table = 'best_team';
    public $prefix = 'bes';

    const TYPE_TEAM_COMMISSION = 0;
    const TYPE_TEAM_PRODUCT_QUANTITY = 1;
    const TYPE_OWN_PRODUCT_QUANTITY = 2;

    public function user()
    {

        return $this->hasOne(
            __FUNCTION__,
            Users::class,
            'use_id',
            'bes_user_id'
        );
    }
}