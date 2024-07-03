<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 8/12/19
 * Time: 20:00
 */

require_once 'inc_security.php';

disable_debug_bar();

$user_id = getValue('user_id', 'int', 'POST', 0);
$level = getValue('level', 'int', 'POST', 0);

if ($level > 0 && $level <= \App\Models\Users\Users::MAX_LEVEL) {
    $user = \App\Models\Users\Users::findByID($user_id);

    if ($user) {
        if ($user->level < $level) {

            $user->level = $level;
            $affected = $user->update();

            if ($affected) {
                $response = [
                    'error' => 0,
                    'message' => 'Thay đổi cấp độ người dùng thành công'
                ];
            } else {
                $response = [
                    'error' => 1,
                    'message' => 'Có lỗi. Bạn hãy thử lại'
                ];
            }

        } else {
            $response = [
                'error' => 1,
                'message' => 'Cần chọn cấp độ lớn hơn cấp độ hiện tại của người dùng'
            ];
        }
    } else {
        $response = [
            'error' => 1,
            'message' => 'Người dùng không tồn tại'
        ];
    }
} else {
    $response = [
        'error' => 1,
        'message' => 'Cấp độ không hợp lệ'
    ];
}

echo json_encode($response);
die;
