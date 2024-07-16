<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/13/18
 * Time: 11:46
 */

namespace AppView\Repository;


class CategoryRepository
{

    public function findByRewrite($rewrite)
    {
        $result = model('categories/get_by_rewrite')->load([
            'rewrite' => $rewrite
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : false;
    }

    public function headerMenu()
    {
        $result = model('categories/header_menu')->load();

        $categories = $result['vars'] ? collect_recursive($result['vars']) : collect([]);

        return $categories->keyBy('id');
    }

    public function getCategoryByType($type)
    {
        $result = model('categories/get_by_type')->load([
            'type' => $type
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : null;
    }

    public function getCategoryByID($id)
    {
        $result = model('categories/get_by_id')->load([
            'id' => (int)$id
        ]);

        return $result['vars'] ? collect_recursive($result['vars']) : null;
    }
    
    public function getCategoryByIdAndType($type,$parent_id){
        $type = $type ?? 'NEWS';

        $result = model('categories/get_list_by_parent')->load([
            'type' => $type,
            'parent_id'=>$parent_id
        ]);
        return $result['vars'] ? collect_recursive($result['vars']) : null;
    }

}