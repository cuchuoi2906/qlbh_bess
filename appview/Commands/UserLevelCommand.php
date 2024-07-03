<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 10/22/19
 * Time: 09:31
 */

namespace AppView\Commands;


use App\Manager\OrderCommit\OrderCommissionManager;
use App\Models\Users\Users;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use VatGia\Helpers\Console\Command;

class UserLevelCommand extends Command
{

    protected $name = 'user_level';
    protected $description = 'Tính toán lại toàn bộ level của user';

    public function fire()
    {

        $id = $this->input->getArgument('id');
        $users = [];
        if ($id) {
            $user = Users::findByID($id);
            $users[] = $user;
        } else {
            $users = Users::all();
        }

        if (!empty($users)) {
            foreach ($users as $user) {
                $order_commission = new OrderCommissionManager();
                $order_commission->checkAndUpdateLevel($user->id);
            }
        }


    }

    protected function getArguments()
    {
        return [
            ['id', InputArgument::OPTIONAL, 'ID user'],
        ];
    }

    protected function getOptions()
    {
        return [
//            ['lang', null, InputOption::VALUE_OPTIONAL, 'Language you want to display'],
        ];
    }

}