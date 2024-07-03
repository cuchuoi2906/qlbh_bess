<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 3/2/2019
 * Time: 8:53 AM
 */

namespace AppView\Controllers\Api;


class CollaboratorsController extends ApiController
{
    public function getCollaborators()
    {
        $page = getValue('page', 'int', 'GET', 1);

        $data = model('users/get_collaborators_by_id')->load([
            'id' => app('auth')->u_id ?? 0,
            'page' => $page,
            'page_size' => 10
        ]);

        return $data['vars'];
    }

    public function getUsers()
    {
        $page = getValue('page', 'int', 'GET', 1);
        $data = model('users/get_users')->load([
            'id' => app('auth')->u_id ?? 0,
            'page' => $page,
            'page_size' => 10,
            'sort' => getValue('sort', 'str', 'GET', 'level'),
            'keyword' => getValue('keyword', 'str', 'GET', '')
        ]);
        if(check_array($data['vars']['data'])){
            foreach($data['vars']['data'] as $key=>$items){
                $users_point_f1 = repository('statistic/point_group_member')->get([
                    'user_id' => $items['id']
                ] + $this->input);
                $data['vars']['data'][$key] = array_merge($data['vars']['data'][$key],$users_point_f1['vars']);
            }
        }
        $users_point = repository('statistic/point_group_member')->get([
            'user_id' => app('auth')->u_id
        ] + $this->input);
        $data['vars']['meta']['point'] = $users_point['vars'];

//        $meta = repository('statistic/all_member')->get([
//                'user_id' => app('auth')->u_id
//            ] + $this->input);
//
//        $data['vars']['meta'] += ['users_all' => $meta['vars']];
        return $data['vars'];
    }
}