<?php
/**
 * Created by PhpStorm.
 * User: Truong
 * Date: 24/12/2018
 * Time: 11:10 SA
 */

namespace App\Transformers;


class CategoryWithAllChildsTransformer extends CategoryTransformer
{

    protected $defaultIncludes = [
        'childs'
    ];

}