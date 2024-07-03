<?

use App\Models\Post;

require_once 'inc_security.php';

$pos_title = getValue('pos_title_' . locale(), 'str', 'GET', '', 3);
$pos_product_id = getValue('pos_product_id', 'int', 'GET', 0, 3);
$pos_category_id = getValue('pos_category_id', 'int', 'GET', 0, 3);
//$cat_type = getValue('cat_type', 'str', 'GET', '', 3);
$sqlWhere = '1 AND pos_type = \'DRIVER\' AND pos_category_id <> 29';

if ($pos_product_id) {
    $sqlWhere .= " AND pos_product_id = '" . $pos_product_id . "'";
}

if ($pos_category_id) {
    $sqlWhere .= " AND pos_category_id = '" . $pos_category_id . "'";
}

if ($pos_title) {
    $sqlWhere .= " AND pos_title_" . locale() . " LIKE '%" . $pos_title . "%'";
}


$items = Post::where($sqlWhere)
    ->pagination(getValue('page', 'int', 'GET', 1), $per_page)
    ->order_by('pos_id', 'DESC')
    ->all();

$total = Post::where($sqlWhere)->count();


$dataGrid = new DataGrid($items, $total, 'pos_id');

if ($categories_arr ?? false):
    $dataGrid->column(['pos_category_id', $categories_arr], 'Danh mục', 'select', [], true);
endif;
if ($products ?? false):
    $dataGrid->column(['pos_product_id', $products], 'Sản phẩm', 'select', [], true);
endif;


$dataGrid->column('pos_title_' . locale(), 'Tiêu đề', ['string', 'trim'], [], true);

$dataGrid->column(uniqid(), 'Link', function ($row) {
    return '<a href="' . url() . '/upload/files/' . $row['pos_image'] . '"><i class="fa fa-download" aria-hidden="true"></i></a></div>';
});

$dataGrid->column('pos_active', 'Active', 'active|center');
//$dataGrid->column('pos_totnedal_view', 'Lượt truy cập', 'number|center');
$dataGrid->column('pos_created_at', 'Ngày tạo', 'string|center');
$dataGrid->column('pos_updated_at', 'Ngày sửa', 'string|center');

$dataGrid->column(uniqid(), 'Sửa', function ($row) {
    $linkEdit = 'edit_driver.php?record_id=' . $row['pos_id'] . '&type=' . $row['pos_type'];
    return '<div class = "text-center" ><a href="' . $linkEdit . '"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></div>';
});

$dataGrid->column(uniqid(), 'Xóa', function ($row) {
    $linkEdit = 'delete.php?record_id=' . $row['pos_id'] . '&type=' . $row['pos_type'];
    return '<div class = "text-center" ><a href="' . $linkEdit . '"><i class="fa fa-times" aria-hidden="true"></i></a></div>';
});
//$dataGrid->column('', 'Sửa', 'edit|center');
//$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
