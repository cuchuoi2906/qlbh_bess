<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/13/18
 * Time: 12:55
 */

namespace AppView\Repository;


class ContactRepository
{

    public function save($data, $id = null)
    {

        if ($id) {
            $data['id'] = (int)$id;
        }

        $result = model('contact/save')->load($data);

        return $result['vars'];

    }

}