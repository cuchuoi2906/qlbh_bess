<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/16/19
 * Time: 12:04
 */
$affected = 1;

$user = \App\Models\Users\Users::findByID(input('user_id'));

//if ($user->active == 1) {
//    throw new Exception('Tài khoản đã được xác thực rồi.', 400);
//}
if (!$user->active) {
    if ($user->register_confirm_code == input('code') || (config('app.env') == 'development' && input('code') == '1111')) {
        $user->use_active = 1;
        $affected = $user->update();

        \AppView\Helpers\Notification::to([$user->parent->id], 'Cộng tác viên đăng ký thành công', 'Thành viên ' . $user->name . ' đã đăng kí thành công tài khoản app dododo24h qua mã giới thiệu của bạn.');

        \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\CountChildUserWorker::$name, \App\Workers\CountChildUserWorker::class, [
            'id' => $user->id
        ]);

    } else {
        throw new RuntimeException('Mã xác thực của bạn chưa đúng.', 400);
    }
} else {
    $affected = 1;
}

return [
    'vars' => $affected
];