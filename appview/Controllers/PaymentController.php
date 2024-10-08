<?php
/**
 * Created by facebook.
 * Date: 10/19/2017
 * Time: 5:30 PM
 */

namespace AppView\Controllers;


use App\Models\Order;
use AppView\helpers\BaoKimPaymentPro;
use AppView\helpers\BaoKimPayment;

class PaymentController
{

    public function index()
    {
        $baokim = new BaoKimPaymentPro(config('baokim'));
        $banks = $baokim->get_seller_info();
        $bank_locals = [];
        $credits = [];
        foreach ($banks as $bank) {
            if ($bank['payment_method_type'] == PAYMENT_METHOD_TYPE_LOCAL_CARD) {
                $bank_locals[] = $bank;
            }

            if ($bank['payment_method_type'] == PAYMENT_METHOD_TYPE_CREDIT_CARD) {
                $credits[] = $bank;
            }
        }

//        dd($banks);

        return view('payment')->render(get_defined_vars());
    }

    public function payment()
    {
        echo 'Đang xử lý ... <br/>';

        $bank_payment_method_id = getValue('bank_payment_method_id', 'int', 'POST', 0);
        $baokim = new BaoKimPaymentPro(config('baokim'));
        $banks = $baokim->get_seller_info();
        $payment_method_type = 0;
        foreach ($banks as $bank) {
            if ($bank['id'] == $bank_payment_method_id) {
                $payment_method_type = $bank['payment_method_type'];
            }
            continue;
        }

        $service_id = getValue('service_id', 'int', 'POST', 0);

        $services = config('services.items');
        $service = isset($services[$service_id]) ? $services[$service_id] : false;
        if (!$service) {
            echo 'Phải chọn gói dịch vụ trước.';

            return redirect_after(url('payment'), 2);
        }

        $data = $_POST;
        $data = array_map('trim', $data);
        $data['total_amount'] = $service['money'] * 1000;
        //User test
        if (app('user')->u_id == 88) {
            $data['total_amount'] = 5000;
        }
        $data['payer_name'] = app('user')->use_name;
        $data['payer_email'] = app('user')->use_email;
        $data['payer_phone_no'] = app('user')->use_phone ?? '';
        $data['address'] = app('user')->use_address ?? '';

//dd($data);
        $response = [];

        switch ($payment_method_type) {
            case PAYMENT_METHOD_TYPE_CREDIT_CARD:

                $order_id = Order::insert([
                    'ord_user_id' => app('user')->u_id,
                    'ord_money' => $service['money'] * 1000,
                    'ord_status' => 0, //Chưa thanh toán
                    'ord_service_id' => $service_id,
                    'ord_created_at' => time(),
                ]);

                //Lưu thông tin nạp tiền trước khi bắn sang Bảo Kim
                if ($order_id) {
                    $data['order_id'] = $order_id;
                    $baokim = new BaoKimPayment(config('baokim'));
                    $data = array_map('trim', $data);
                    $baokim_url = $baokim->createRequestUrl($data);
                    $response = array('code' => 1, 'message' => 'Bạn sẽ chuyển sang trang thanh toán');
                    $response['redirect_url'] = $baokim_url;
                } else {
                    $response = array('code' => 0, 'message' => 'Có lỗi xảy ra. Bạn hãy thử lại.');
                }
                break;
            case PAYMENT_METHOD_TYPE_LOCAL_CARD:

                $order_id = Order::insert([
                    'ord_user_id' => app('user')->u_id,
                    'ord_money' => $service['money'] * 1000,
                    'ord_status' => 0, //Chưa thanh toán
                    'ord_service_id' => $service_id,
                    'ord_created_at' => time(),
                ]);

                if ($order_id) {
                    $data['order_id'] = (int)$order_id;
                    $baokim = new BaoKimPaymentPro(config('baokim'));
                    $result = $baokim->pay_by_card($data);
                    if (!empty($result['error'])) {
                        //Cập nhật trạng thái đơn hàng vì sao fail
//                        $MyadChargeMoney->updateBaoKimOrderStatus(array('order_id' => $order_id), $result['error']);
                        $response = array('code' => 0, 'message' => $result['error']);
                    } else {
                        $response = array('code' => 1, 'message' => 'Bạn sẽ chuyển sang trang thanh toán');
                        $response['redirect_url'] = $result['redirect_url'] ? $result['redirect_url'] : $result['guide_url'];
                    }
                } else {
                    $response = array('code' => 0, 'message' => 'Có lỗi xảy ra. Bạn hãy thử lại.');
                }
                break;
        }

        if (isset($response['redirect_url'])) {
            echo $response['message'];
            redirect_after($response['redirect_url'], 1);
        } else {
            echo $response['message'];
            redirect_after(url('payment'), 1);
        }
    }
    public function getBanksByOrderId($id = 0)
    {
        if(intval($id) <= 0){
            return;
        }
        $result = model('order/get_order_by_id')->load([
            'id' => (int)$id,
        ]);
		
        return view('products/checkout')->render([
            'id' => $id,
            'orderData'=>$result['vars']
        ]);
    }
	public function getBanksByOrderId2($id = 0)
    {
        if(intval($id) <= 0){
            return;
        }
        $result = model('order/get_order_by_id')->load([
            'id' => (int)$id,
        ]);
        return view('products/checkout2')->render([
            'id' => $id,
            'orderData'=>$result['vars']
        ]);
    }
}