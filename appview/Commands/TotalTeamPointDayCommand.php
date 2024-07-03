<?php
/**
 * Created by vatgia-framework.
 * Date: 7/29/2017
 * Time: 8:06 PM
 */

namespace AppView\Commands;


use App\Models\OrderCommission;
use App\Workers\TotalTeamPointDayWorker;
use Symfony\Component\Console\Input\InputArgument;
use VatGia\Queue\Facade\Queue;

class TotalTeamPointDayCommand extends \VatGia\Helpers\Console\Command
{

    protected $name = 'total-team-point-day';
    protected $description = 'Repush all queue to count total team point day';

    public function fire()
    {

        $commissions = OrderCommission::all();

        foreach ($commissions as $commission) {

            echo 'Push queue user ' . $commission->user->name . '(' . $commission->user->id . ') và đơn hàng ' . $commission->order->id . PHP_EOL;
            Queue::pushOn(TotalTeamPointDayWorker::$name, TotalTeamPointDayWorker::class, [
                'user_id' => $commission->user->id,
                'order_id' => $commission->order->id
            ]);
        }
    }

    protected function getArguments()
    {
        return [
            ['user_id', InputArgument::OPTIONAL, 'User id'],
        ];
    }

    protected function getOptions()
    {
        return [
//            ['lang', null, InputOption::VALUE_OPTIONAL, 'Language you want to display'],
        ];
    }
}