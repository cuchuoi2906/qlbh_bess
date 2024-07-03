<?php

require_once 'inc_security.php';

/**
 * @var $model \VatGia\Model\ModelBase
 */

//$db = new db_execute('UPDATE users SET use_deleted_at = null where use_id = 2699');

$db = new db_query('SELECT * FROM users WHERE use_all_level = "" AND use_referral_id > 0');
$result = $db->fetch();
if(check_array($result)){
    $errorMsg = date('Y-m-d H:i:s ')." ".print_r($result,true)." \n";
    @error_log($errorMsg, 3, '/var/www/dev_dododo/public_html/var/log_register.log');
    foreach($result as $items){
        $id =intval($items['use_id']);
        echo 'User: '.$id.'<br>';
        $use_referral_id =intval($items['use_referral_id']);
        if($id > 0){
            $user = \App\Models\Users\Users::findByID($id);
            $userReferral = \App\Models\Users\Users::findByID($use_referral_id);
            $referral_user = \App\Models\Users\Users::where('use_login = \'' . $userReferral->use_login . '\' OR (use_id = \'' . $userReferral->use_login . '\' AND LENGTH(use_id) = LENGTH(\''. $userReferral->use_login .'\')) OR use_referer_code = \'' . $userReferral->use_login . '\'')
            ->find();
            if (isset($referral_user)) {
                $string = '.' . $referral_user->id . '.';
                while ($parent = $referral_user->parent) {
                    $string .= $parent->id . '.';
                    $referral_user = $referral_user->parent;
                }
                $user->all_level = $string;
                $user->update();
            }
        }
    }
}
echo 'Done';
die;