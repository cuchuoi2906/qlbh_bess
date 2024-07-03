<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/13/18
 * Time: 11:42
 */

namespace App\Transformers;


use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{

    protected $availableIncludes = [
        'posts',
        'childs'
    ];

    public function transform($item)
    {
        return [
            'id' => (int)$item->id,
            'name' => $item->name,
            'rewrite' => $item->rewrite,
            'description' => $item->description,
            'icon' => url() . '/upload/categories/' . $item->icon,
            'type' => $item->type,
            'created_at' => new \DateTime($item->created_at)
        ];
    }

    public function includeChilds($category)
    {
        $category->childs = $category->childs ?? [];

        return $this->collection($category->childs, new static());
    }

}