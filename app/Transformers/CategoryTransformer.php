<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 5/13/18
 * Time: 11:42
 */

namespace App\Transformers;


use App\Models\Product;
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
            'parent_id'=>$item->parent_id,
            'count_pro'=>$item->count_pro_id,
            'rewrite' => $item->rewrite,
            'description' => $item->description,
            'icon' => url() . '/upload/categories/' . $item->icon,
            'type' => $item->type,
            'created_at' => new \DateTime($item->created_at),
			'total_product' => $this->getTotalProducts($item),
        ];
    }

    public function includeChilds($category)
    {
        $category->childs = $category->childs ?? [];

        return $this->collection($category->childs, new static());
    }
	
	private function getTotalProducts($category)
    {
        // Get all subcategories IDs
		if(!$category->childs){
			return '';
		}
        $subcategoryIds = $category->childs->toArray();
		if(!check_array($subcategoryIds)){
			return '';
		}
		$arrIds = array_column($subcategoryIds, 'id');
        $arrIds[] = $category->id;
		//$ids = implode(',',$arrIds);
		return Product::where('pro_category_id',"IN",$arrIds)->count();
    }

}