<?php
/**
 * Created by vatgia-framework.
 * Date: 7/29/2017
 * Time: 8:06 PM
 */

namespace AppView\Commands;


use App\Models\Users\Users;
use App\Workers\UserTotalPointStatisticWorker;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use VatGia\Queue\Facade\Queue;

class CountTotalPointTeamCommand extends \VatGia\Helpers\Console\Command
{

    protected $name = 'user:count-total-point-team';
    protected $description = 'Say hello with someone';

    public function fire()
    {

        $id = $this->input->getArgument('user_id');
        if ($id) {
            $users = Users::where('use_id', $id)->all();
        } else {
            $users = Users::all();
        }
        foreach ($users as $user) {
            echo 'Push queue user ' . $user->name . '(' . $user->id . ')' . PHP_EOL;
            Queue::pushOn(UserTotalPointStatisticWorker::$name, UserTotalPointStatisticWorker::class, [
                'user_id' => $user->id
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