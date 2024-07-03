<?

use App\Models\Product;

require_once 'inc_security.php';

$pro_name = getValue('pro_name', 'str', 'GET', '', 3);

$sqlWhere = "1";

if ($pro_name) {
    $sqlWhere .= " AND pro_name LIKE '%" . $pro_name . "%'";
}

$items = Product::where($sqlWhere)
    ->pagination((int)getValue('page'), 10)
    ->order_by('pro_id', 'DESC')
    ->all();

$total = Product::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'pro_id');
$dataGrid->column('avatar', 'Ảnh đại diện', function ($row) {

    $avatar = $row->avatar;
    if ($avatar) {
        return '<img style="width: 100px;" src="' . url() . '/upload/products/' . $avatar->file_name . '"/>';
    }
});
$dataGrid->column('pro_name_' . locale(), 'Tên', ['string', 'trim'], [], true);
$dataGrid->column(uniqid(), 'Link', function ($row) {
    return '<a href="' . url('product.detail', ['rewrite' => removeTitle($row->name), 'id' => (int)$row->id]) . '" target="_blank">link</a>';
});
$dataGrid->column('pro_price', 'Giá', ['string', function ($price) {
    return number_format($price);
}]);
$dataGrid->column('pro_discount_price', 'Giá khuyến mại', ['string', function ($price) {
    return number_format($price);
}]);
$dataGrid->column('pro_teaser_' . locale(), 'Mô tả ngắn', ['string', 'trim']);

$dataGrid->column('pro_active', 'Active', 'active|center');
$dataGrid->column('pro_is_hot', 'Hot', 'active|center');
$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
