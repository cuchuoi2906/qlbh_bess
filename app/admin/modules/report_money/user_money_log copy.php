<?

use App\Models\Users\Users;

require_once 'inc_security.php';


$sqlWhere = "1";

$use_id = getValue('use_id', 'int', 'GET', 0);
if ($use_id) {
    $sqlWhere .= ' AND use_id = ' . $use_id;
}

$use_name = getValue('use_name', 'str', 'GET', '', 3);
if ($use_name) {
    $sqlWhere .= " AND use_name LIKE '%" . $use_name . "%'";
}

$use_email = getValue('use_email', 'str', 'GET', '', 3);
if ($use_email) {
    $sqlWhere .= " AND use_email = '" . $use_email . "'";
}

$use_phone = getValue('use_phone', 'str', 'GET', '', 3);
if ($use_phone) {
    $sqlWhere .= " AND use_login = '" . $use_phone . "'";
}

//
//$categories = \App\Models\Categories\Category::where($sqlWhere)
//    ->pagination(getValue('page'), $per_page)
//    ->order_by('cat_order', 'DESC')
//    ->select_all();
//$total = \App\Models\Categories\Category::where($sqlWhere)->count();

$items = Users::withTrash()->where($sqlWhere)->with(['wallet', 'childs', 'parent'])
    ->pagination(getValue('page'), $per_page)
    ->order_by('use_id', 'DESC')
    ->all();

$total = Users::withTrash()->where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'use_id');
$dataGrid->column('use_id', 'ID', 'number', [], true);
$dataGrid->column('use_name', 'Tên', ['string', 'trim'], [], true);
//$dataGrid->column(['use_gender', [0 => 'Female', 1 => 'Male']], 'Giới tính', 'select');
$dataGrid->column('use_email', 'Email', 'string', [], true);
$dataGrid->column('use_phone', 'Số điện thoại', 'string', [], true);

//$dataGrid->column('cat_icon', 'Icon', ['image', function ($cat_icon) {
//    $cat_icon = $cat_icon ? ('http://localhost:5000/upload/' . $cat_icon) : '';
//
//    return $cat_icon;
//}]);
//$dataGrid->column('cat_name', 'Tên', ['string', 'trim'], [], true);
//$dataGrid->column(['cat_type', $type_arr], 'Loại', 'select', [], true);

//$dataGrid->column('cat_picture', 'Ảnh đại diện', function ($row) {
//    if ($row['cat_picture']) {
//        return '<img src="http://localhost:5000/upload/' . $row['cat_picture'] . '" />';
//    }
//    return '';
//});

$dataGrid->column(false, 'Ví nạp', function ($row) {
    return $row->wallet ? number_format($row->wallet->charge) : 0;
});
$dataGrid->column(false, 'Ví Hoa hồng', function ($row) {
    return $row->wallet ? number_format($row->wallet->commission) : 0;
});
//$dataGrid->column(['use_level', [-1 => 'Tất cả'] + array_keys(array_fill(0, 101, 1))], 'Cấp độ hiện tại', 'selectShow', [], true);
//$dataGrid->column('use_level', 'Cấp độ hiện tại', 'number', [], true);
//$dataGrid->column('use_active', 'Active', 'active|center');

$dataGrid->column(false, 'Lịch sử', function ($row) use ($blade) {
//    dump($row->childs());
//    dump($row->parent);
    return $blade->view()->make('user_detail_modal', compact('row'))->render();
});


//$dataGrid->column('', 'Trạng thái', 'softDelete|center');
//$dataGrid->column('', 'Sửa', 'edit|center');
//$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
