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

class UserTotalReferCommand extends Command
{

    protected $name = 'user:refer_all_level';
    protected $description = 'Xác định lại toàn bộ các cấp giới thiệu của user';

    public function fire()
    {

        $id = $this->input->getArgument('id');
        $users = [];
        if ($id) {
            $user = Users::withTrash()->findByID($id);
            $users[] = $user;
        } else {
            $users = Users::withTrash()->all();
        }

        if (!empty($users)) {

            foreach ($users as $user) {

                $all_level = '';
                $parent = $user->parent;
                while ($parent) {
                    $all_level .= ($all_level == '') ? ('.' . $parent->id . '.') : ($parent->id . '.');
                    $parent = $parent->parent;
                }
                $user->all_level = $all_level;
                $user->update();
                \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\CountChildUserWorker::$name, \App\Workers\CountChildUserWorker::class, [
                    'id' => $user->id
                ]);
                echo 'Update user ' . $user->id . PHP_EOL;
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