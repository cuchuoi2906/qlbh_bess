<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/9/18
 * Time: 10:01
 */

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class DriverTransformer extends TransformerAbstract
{
//    public $availableIncludes = [
//        'product',
//    ];

    public function transform($item)
    {
        $item->created_at = ($item->created_at != '0000-00-00 00:00:00') ? (($item->updated_at != '0000-00-00 00:00:00') ? $item->updated_at : date('Y-m-d H:i:s')) : date('Y-m-d H:i:s');
        return [
            'id' => (int)$item->id,
            'title' => $item->title,
            'rewrite' => $item->rewrite ? $item->rewrite : removeTitle($item->title),
            'file_link' => $item->image ? (url() . '/upload/files/' . $item->image) : '',
            'file_size' => $item->file_size ? $item->file_size : '',
            'teaser' => $item->teaser ? nl2br($item->teaser) : '',
            'content' => ($item->content) ? html_entity_decode($item->content) : '',
//            'link' => url('post.detail', ['rewrite' => $item->rewrite, 'id' => (int)$item->id]),
            'created_at' => new \DateTime($item->created_at)
        ];
    }

//    public function includeProduct($item)
//    {
//        $product = $item->product ?? collect([]);
//
//        return $this->item($product, new ProductTransformer());
//    }
}