<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2/24/19
 * Time: 23:11
 */

namespace AppView\Repository;


class UserRepository
{

    public function getById($id)
    {
        $result = model('users/get_by_id')->load([
            'id' => $id
        ]);

        if ($result['vars']) {

            if (!isset($result['vars']['parent']['id']) || !$result['vars']['parent']['id']) {
                unset($result['vars']['parent']);
            }

            return collect_recursive($result['vars']);
        }

    }

}