<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 12/8/19
 * Time: 23:36
 */

namespace AppView\Commands;


use App\Models\Order;
use Symfony\Component\Console\Input\InputArgument;
use VatGia\Helpers\Console\Command;

class OrderProductCommissionCommand extends Command
{

    protected $name = 'commission:order-product';
    protected $description = 'Tính hoa hồng cho sản phẩm của tất cả các đơn hàng';

    public function fire()
    {

        $id = $this->input->getArgument('id');
        $users = [];
        if ($id) {
            $ids = [$id];
        } else {
            $orders = Order::all();
            $ids = $orders->lists('ord_id');
        }

        if (!empty($ids)) {
            foreach ($ids as $id) {
                \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\OrderProductCommissionDetailWorker::$name, \App\Workers\OrderProductCommissionDetailWorker::class, [
                    'order_id' => $id
                ]);
            }
        }


    }

    protected function getArguments()
    {
        return [
            ['id', InputArgument::OPTIONAL, 'ID order'],
        ];
    }

    protected function getOptions()
    {
        return [
//            ['lang', null, InputOption::VALUE_OPTIONAL, 'Language you want to display'],
        ];
    }

}