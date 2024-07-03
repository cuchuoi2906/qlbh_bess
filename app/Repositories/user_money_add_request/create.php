<?php
/**
 * Created by PhpStorm.
 * User: tunganh
 * Date: 2019-03-19
 * Time: 14:50
 */

use App\Models\Users\UserMoneyAddRequest;
use AppView\Helpers\BaoKimAPI;
use AppView\Helpers\BaoKimPaymentPro;

$vars = [];

//throw new Exception('Tính năng này hiện đang hoàn thiện. Bạn vui lòng sử dụng vào lúc khác.', 400);

$user = \App\Models\Users\Users::findByID(input('user_id'));
if (!$user) {
    throw new \RuntimeException("User not found", 400);
}
$bank_id = input('bank_id');

$id = UserMoneyAddRequest::insert([
    'umar_user_id' => $user->id,
    'umar_amount' => (int)input('amount'),
    'umar_type' => input('type'),
    'umar_status' => UserMoneyAddRequest::STATUS_NEW,
    'umar_note' => 'Nạp tiền vào tài khoản'
]);

$client = new GuzzleHttp\Client(['timeout' => 20.0]);

$payload['mrc_order_id'] = 'basaco_payment_' . $id;
$payload['total_amount'] = (int)input('amount');
$payload['description'] = "Nạp tiền vào tài khoản " . $user->email;
$payload['url_success'] = url('payment-callback') . '?success=1';
$payload['url_detail'] = url('payment-callback') . '?cancel=1';
$payload['lang'] = "vi";
$payload['items'] = json_encode([]);
$payload['bpm_id'] = $bank_id;
$payload['accept_bank'] = 1;
$payload['accept_cc'] = 1;
$payload['accept_qrpay'] = 1;
$payload['webhooks'] = url('payment-callback-bpn');
$payload['customer_email'] = $user->email ? $user->email : 'default@dododo24h.com.vn';
$payload['customer_phone'] = $user->phone;
$payload['customer_name'] = $user->name;
$payload['customer_address'] = $user->address;
$options['form_params'] = $payload;

$options['query']['jwt'] = BaoKimAPI::getToken($payload);

$response = $client->request("POST", config('baokim.api_domain') . "/payment/api/v4/order/send", $options);
$result = json_decode($response->getBody()->getContents(), true);
if ($result['code'] > 0) {
    foreach ($result['message'] as $error) {
        if (is_array($error)) {
            foreach ($error as $item) {
                throw new RuntimeException($item, 400);
            }
        } else {
            throw new RuntimeException($error, 400);
        }
    }

} else {
    $vars['redirect_url'] = $result['data']['redirect_url'] ?? '';
    $vars['redirect_url'] = (false != strpos($vars['redirect_url'], 'https')) ? $vars['redirect_url'] : (config('baokim.website_domain') . $vars['redirect_url']);
}
return [
    'vars' => $vars
];
