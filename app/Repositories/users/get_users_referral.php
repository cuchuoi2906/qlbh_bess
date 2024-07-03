<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-03
 * Time: 17:24
 */
$vars = [];
$referral_users = get_all_users_referral(input('referral_id'));
//dd($referral_users);
return ['vars' => $referral_users];


/**
 * @return array users
 */
function get_all_users_referral($referral_id){
    $arrUser = [];
//    dd($referral_id);
    $user = \App\Models\Users\Users::findByID($referral_id);
    if($user){
        $arrUser[] = $user;
        if($user->referral_id != 0){
           $arrUser = array_merge($arrUser, get_all_users_referral($user->use_referral_id));
        }
    }else{
        throw new Exception("referral_id không tồn tại");
    }
    return $arrUser;
}