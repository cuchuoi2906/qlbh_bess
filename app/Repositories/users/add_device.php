<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/3/19
 * Time: 09:20
 *
 * @todo: Quy trình add 1 device user
 * - Insert vào bảng user_Device
 * - Bắn lên firebase. Add device vào các topic có chứa user.
 * - Các topic cần có tiền tố theo môi trường để khi test tránh ảnh hưởng đến user thật
 * - Có thể có các topic sau:
 *  + {ENV}_TOPIC_USER_ALL
 *  + {ENV}_TOPIC_USER_{user_id}
 *  + {ENV}_TOPIC_USER_GENDER_{user_gender}
 *  + {ENV}_TOPIC_USER_LEVEL_{user_level}
 */

$vars = false;

$user_id = input('id');
$registration_id = input('registration_id');
$info = input('info') ?? [];

$id = \App\Models\Users\UserDevice::replace([
    'usd_user_id' => (int)$user_id,
    'usd_registration_id' => $registration_id,
    'usd_device_info' => json_encode($info)
]);
if ($id) {
    fcm_add_device_to_topic($registration_id, 'TOPIC_USER_ALL');

    fcm_add_device_to_topic($registration_id, 'TOPIC_USER_' . $user_id);
    $vars = true;
}

return [
    'vars' => $vars
];

