<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/9/18
 * Time: 10:01
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class PostTransformer extends TransformerAbstract
{

    public $availableIncludes = [
        'category',
    ];

    public function transform($item)
    {
        $item->created_at = ($item->created_at != '0000-00-00 00:00:00') ? (($item->updated_at != '0000-00-00 00:00:00') ? $item->updated_at : date('Y-m-d H:i:s')) : date('Y-m-d H:i:s');

        return [
            'id' => (int)$item->id,
            'title' => $item->title,
            'rewrite' => $item->rewrite ? $item->rewrite : removeTitle($item->title),
            'image' => $item->image ? (url() . '/upload/posts/' . $item->image) : '',
            'teaser' => $item->teaser ? nl2br($item->teaser) : '',
            'content' => html_entity_decode($item->content),
            'is_hot' => (int)$item->is_hot,
            'show_home' => (int)$item->show_home,
            'type' => $item->type,
            'created_at' => new \DateTime($item->created_at),
            'link' => url('post.detail', ['slug' => $item->rewrite, 'id' => (int)$item->id])
        ];
    }

    public function includeCategory($item)
    {
        $category = $item->category ? $item->category : collect([]);

        return $this->item($category, new CategoryTransformer());
    }

}