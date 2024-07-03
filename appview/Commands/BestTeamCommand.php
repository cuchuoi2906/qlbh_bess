<?php
/**
 * Created by vatgia-framework.
 * Date: 7/29/2017
 * Time: 8:06 PM
 */

namespace AppView\Commands;


use App\Models\BestTeam;
use App\Models\Order;
use App\Workers\TotalTeamPointDayWorker;
use Symfony\Component\Console\Input\InputArgument;
use VatGia\Queue\Facade\Queue;

class BestTeamCommand extends \VatGia\Helpers\Console\Command
{

    protected $name = 'best-team';
    protected $description = '';

    public function fire()
    {

        $order_id = $this->input->getArgument('order_id');

        if (!$order_id) {
            $orders = Order::where('ord_status_code', '<>', Order::CANCEL)->all();
        } else {
            $orders = Order::where('ord_id', $order_id)->all();
        }

        if ($orders->count()) {

            BestTeam::where('bes_id', '>', 0)->delete();
            foreach ($orders as $order) {
                $this->output->success('Quét cho đơn hàng ' . $order->id);
                Queue::pushOn(TotalTeamPointDayWorker::$name, TotalTeamPointDayWorker::class, [
                    'user_id' => $order->user_id,
                    'order_id' => $order->id
                ]);
            }
        }


    }

    protected function getArguments()
    {
        return [
            ['order_id', InputArgument::OPTIONAL, 'Nhập ID đơn hàng. Để trống để quét tất cả đơn'],
        ];
    }

    protected function getOptions()
    {
        return [
            //['lang', null, InputOption::VALUE_OPTIONAL, 'Language you want to display'],
        ];
    }
}