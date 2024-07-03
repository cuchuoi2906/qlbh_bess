<?
require_once 'inc_security.php';

$cat_name = getValue('cat_name_' . locale(), 'str', 'GET', '', 3);

$sqlWhere = "1 AND cat_parent_id = 0";

if ($cat_name) {
    $sqlWhere .= " AND cat_name_" . locale() . " LIKE '%" . $cat_name . "%'";
}

if ($type) {
    $sqlWhere .= " AND cat_type = '" . $type . "'";
}

$items = [];

//
$categories = \App\Models\Categories\Category::where($sqlWhere)
//    ->with(['childs'])
    ->pagination(getValue('page'), $per_page)
    ->order_by('cat_order', 'DESC')
    ->all();
$total = \App\Models\Categories\Category::where($sqlWhere)->count();
$items = $categories;

//foreach ($categories as $category) {
//    $items[$category->id] = $category;
//    if ($category->childs) {
//        foreach ($category->childs as $child) {
////            $child->{'name_' . locale()} = '|__' . $child->{'name_' . locale()};
//            $child->{'name_' . locale()} = '|__' . $child->{'name_' . locale()};
//            $items[$child->id] = $child;
//            foreach ($child->childs as $child1) {
//                $child1->{'name_' . locale()} = '|___|__' . $child1->{'name_' . locale()};
//                $items[$child1->id] = $child1;
//            }
//        }
//    }
//}

//$categories = collect([]);
//$total = 0;

$dataGrid = new DataGrid($items, $total, 'cat_id');
$dataGrid->perPage = $per_page;
$dataGrid->column('cat_id', 'ID', 'number');

$dataGrid->column('cat_icon', 'Icon', function ($item) {
    $cat_icon = $item->icon ? (url() . '/upload/categories/' . $item->icon) : '';

    return '<img style="width: 100px; height: auto;" src="' . $cat_icon . '"/>';
});

$dataGrid->column('cat_name_' . locale(), trans('label.name', 'Tên'), ['string', 'trim'], [], true);
$dataGrid->column('cat_active', 'Trạng thái', 'activeDisabled|center');

//$dataGrid->column('', 'Trạng thái', 'softDelete|center');
$dataGrid->column(uniqid(), 'Sửa', function ($row) {
    $linkEdit = 'edit.php?record_id=' . $row['cat_id'] . '&type=' . $row['cat_type'];

    return '<div class = "text-center" ><a href="' . $linkEdit . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>';
});
$dataGrid->column(uniqid(), 'Xóa', function ($row) {
    $linkEdit = 'delete.php?record_id=' . $row['cat_id'] . '&type=' . $row['cat_type'];

    return '<div class = "text-center" >
    <a href="' . $linkEdit . '" onclick="return confirm(\'Bạn có chắc muốn xóa bản ghi này?\');">
        <i class="fa fa-times" aria-hidden="true"></i>
    </a>
</div>';
});

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
