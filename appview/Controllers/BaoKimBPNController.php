<?php
/**
 * Created by facebook.
 * Date: 10/20/2017
 * Time: 1:56 AM
 */

namespace AppView\Controllers;

use AppView\Controllers\Api\ApiController;
use AppView\Helpers\BaoKimAPI;

class BaoKimBPNController extends ApiController
{

    public $data;

    public function postBpn()
    {
        //\AppView\Helpers\Notification::to(1235, 'Nhận BPN từ Bảo Kim', 'Nhận BPN từ Bảo Kim mã đơn ' . $this->data['order']['mrc_order_id']);
        // TODO: Implement __invoke() method.
        $this->data = $this->input;

        return $this->process();
    }

    private static function verifyBPNRequest($data)
    {
        $signData = json_encode(['order' => $data['order'], 'txn' => $data['txn']]);
        $yourSign = hash_hmac('sha256', $signData, BaoKimAPI::API_SECRET);
        if ($yourSign != $data['sign']) {
            throw new \RuntimeException('Verify BPN Post Data Invalid', 500);
        }

        return true;
    }

    public function process()
    {
        //Lấy user tương ứng với đơn hàng đã lưu
        if (self::verifyBPNRequest($this->data)) {

            //Update info request
            extract($this->data);
            $order_id = $this->data['order']['mrc_order_id'];
            $order_id = str_replace('basaco_payment_', '', $order_id);
            $data = model('user_money_add_request/update_success')->load([
                'request_id' => $order_id,
                'transaction_id' => $this->data['order']['id'],
                'data' => $this->input
            ]);

            $this->addResponse = [
                'err_code' => 0,
                'message' => 'Cộng tiền thành công'
            ];

            return $data['vars'];

        } else {
            //Thông tin BPN không chính xác
            throw new \RuntimeException('Verify BPN Post Data Invalid', 500);
        }
    }

    public function testBpn()
    {
        $order_id = $_POST['order_id'];
        $transaction_id = $_POST['transaction_id'];
        $transaction_status = $_POST['transaction_status'];


        $data = model('user_money_add_request/update_success')->load([
            'request_id' => $order_id,
            'transaction_id' => $transaction_id,
            'transaction_status' => $transaction_status,
        ]);

    }
}