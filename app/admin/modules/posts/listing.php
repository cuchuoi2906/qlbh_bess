<?

use App\Models\Post;

require_once 'inc_security.php';

$pos_title = getValue('pos_title_' . locale(), 'str', 'GET', '', 3);
//$cat_type = getValue('cat_type', 'str', 'GET', '', 3);
$sqlWhere = '1';
if ($pos_type) {
    $sqlWhere .= " AND pos_type = '" . $pos_type . "'";
}

if ($pos_title) {
    $sqlWhere .= " AND pos_title_" . locale() . " LIKE '%" . $pos_title . "%'";
}

$category_id = getValue('category_id', 'int', 'GET', 0);
if ($category_id) {
    $sqlWhere .= ' AND pos_category_id = ' . $category_id;
}


$items = Post::where($sqlWhere)
    ->pagination(getValue('page', 'int', 'GET', 1), $per_page)
    ->order_by('pos_id', 'DESC')
    ->all();

$total = Post::withTrash()->where($sqlWhere)->count();


$dataGrid = new DataGrid($items, $total, 'pos_id');

if ($categories_arr ?? false):
    $dataGrid->column(['pos_category_id', $categories_arr], 'Danh mục', 'select');
endif;

$dataGrid->column('pos_image', 'Ảnh', function ($row) {
    return '<img class="data-grid-image" style="max-height: 100px;" src="' . url() . '/upload/posts/' . $row->image . '" />"';
});
$dataGrid->column('pos_title_' . locale(), 'Tiêu đề', ['string', 'trim'], [], true);
$dataGrid->column('pos_teaser_' . locale(), 'Mô tả ngắn', ['string', function ($row) {
    return cut_string(strip_tags($row->pos_teaser), 100);
}]);
$dataGrid->column('pos_active', 'Active', 'activeDisabled|center');
$dataGrid->column('pos_created_at', 'Ngày tạo', 'string|center');
$dataGrid->column('pos_updated_at', 'Ngày sửa', 'string|center');

//$dataGrid->column('', 'Trạng thái', 'softDelete|center');
$dataGrid->column(uniqid(), 'Sửa', function ($row) {
    $linkEdit = 'edit.php?record_id=' . $row['pos_id'] . '&type=' . $row['pos_type'];

    return '<div class = "text-center" ><a href="' . $linkEdit . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>';
});

$dataGrid->column(uniqid(), 'Xóa', function ($row) {
    $linkEdit = 'delete.php?record_id=' . $row['pos_id'] . '&type=' . $row['pos_type'];

    return '
<div class = "text-center" >
    <a href="' . $linkEdit . '" onclick="return confirm(\'Bạn có chắc muốn xóa bản ghi này?\');">
        <i class="fa fa-times" aria-hidden="true"></i>
    </a>
</div>';
});
//$dataGrid->column('', 'Sửa', 'edit|center');
//$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
