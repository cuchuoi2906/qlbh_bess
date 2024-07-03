<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 9/9/19
 * Time: 15:13
 */

//Check user
$affected = false;
$user = \App\Models\Users\Users::findByID((int)input('id'));

if ($user) {

    //Referrer code
    if (!$user->referral_id && (input('ref_code') ?? false)) {

        $ref = \App\Models\Users\Users::where('use_login = \'' . input('ref_code') . '\' OR use_id = \'' . input('ref_code') . '\' OR use_referer_code = \'' . input('ref_code') . '\'')
            ->first();
        if ($ref) {
            $user->referral_id = $ref->id;
            $affected = $user->update();

            \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\CountChildUserWorker::$name, \App\Workers\CountChildUserWorker::class, [
                'id' => $user->id
            ]);
        }
    }
}

return [
    'vars' => $affected
];


