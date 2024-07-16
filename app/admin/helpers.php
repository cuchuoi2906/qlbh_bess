<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 11/26/18
 * Time: 10:37
 */

function get_categories_for_select($deep = 0, $pre_label = '|__', $type = 'PRODUCT')
{
    $categories = \App\Models\Categories\Category::where('cat_active', 1)
        ->where('cat_parent_id = 0')
        ->with(['childs'])
        ->order_by('cat_order', 'DESC');
        if($type == 'NEWS'){
            $categories->where('cat_type = "'.$type.'"');
        }else{
            $categories->where('cat_type != "NEWS"');
        }
    $categories = $categories->all();

    if (!$deep) {
        $items = transformer_collection($categories, new \App\Transformers\CategoryWithAllChildsTransformer());
    } else {
        $with = '';
        $i = 1;
        while ($i < $deep) {
            $with = (!$with) ? 'childs' : ($with . '.childs');
            $i++;
        }
        $items = transformer_collection($categories, new \App\Transformers\CategoryTransformer(), [$with]);
    }

    $items = collect_recursive($items);
    set_childs_level($items);

    $result = [];
    flat_items($result, $items);
    $result = collect($result);
    $result->map(function ($item) {
        $item->name = $item->pre . $item->name;

        return $item;
    });

    return $result->lists('id', 'name');

}

function set_childs_level(&$categories, $level = 0, $pre = '')
{
    $level++;
    if ($level > 1) {
        $pre = '|__' . $pre;
    }
    foreach ($categories as &$category) {
        $category['level'] = $level;
        $category['pre'] = $pre;
        if ($category->childs) {
            set_childs_level($category->childs, $level, $pre);
        }
    }
}

function flat_items(array &$result, $items, $key = 'id')
{
    foreach ($items as $item) {
        $result[$item[$key]] = $item;
        if (isset($item['childs'])) {
            flat_items($result, $item['childs']);
        }
    }
}

function formatSizeUnits($bytes)
{
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}

function sorting()
{
    return getValue('sort_field', 'str');
}

function sort_field($default = '')
{
    return getValue('sort_field', 'str', 'GET', $default);
}

function sort_type($default = 'DESC')
{
    return getValue('sort_type', 'str', 'GET', $default);
}

