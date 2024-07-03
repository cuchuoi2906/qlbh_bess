<?php

use App\Models\Product;

require_once 'inc_security.php';

$pro_name = getValue('pro_name_vn', 'str', 'GET', '', 3);

//$sqlWhere = "pro_parent_id = 0";
$sqlWhere = "1";

if ($pro_name) {
    $sqlWhere .= " AND pro_name_vn LIKE '%" . $pro_name . "%'";
}

$pro_active = getValue('pro_active', 'int', 'GET', -1);
if ($pro_active >= 0) {
    $sqlWhere .= " AND pro_active = ".(int)$pro_active;
}
$pro_brand_id = getValue('pro_brand_id', 'int', 'GET', 0);
if ($pro_brand_id > 0) {
    $sqlWhere .= " AND pro_brand_id = ".(int)$pro_brand_id;
}

$pro_active_inventory = getValue('pro_active_inventory', 'int', 'GET', 0);
if ($pro_active_inventory >= 1) {
    $sqlWhere .= " AND pro_active_inventory = ".(int)$pro_active_inventory;
}

$ids = false;
$pro_category_id = getValue('pro_category_id', 'int', 'GET', 0);
if ($pro_category_id) {
    //Lấy tất cả danh mục con
    $category = \App\Models\Categories\Category::findByID($pro_category_id);
    if ($category) {
        $ids = [$category->id];
        function child_ids(array &$ids, $category)
        {
            foreach ($category->childs ?? [] as $child) {
                $ids[] = $child->id;
                if ($child->childs ?? []) {
                    child_ids($ids, $child);
                }
            }
        }

        child_ids($ids, $category);
    }
}
if ($ids ?? false) {
    $sqlWhere .= " AND pro_category_id  IN (" . implode(',', $ids) . ")";
}

$itemsModel = Product::where($sqlWhere)
    ->with(['avatar'])
    //->inner_join('order_product_commission','order_product_commission.opc_product_id = products.pro_id')
    ->pagination((int)getValue('page'), $per_page);

if (sorting()) {
    $itemsModel->order_by(sort_field(), sort_type());
} else {
    $itemsModel->order_by('pro_order', 'DESC')
        ->order_by('pro_id', 'DESC');
}
$itemsModel ->setFields('products.*,(SELECT group_concat(concat(`ppp_quantity`,":",`ppp_price`) separator ",") FROM product_price_policies WHERE product_price_policies.ppp_product_id = products.pro_id) opc_commission');
//var_dump($itemsModel->toSelectQueryString());
$items = $itemsModel->all();

$total = Product::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'pro_id', $per_page);

$dataGrid->model = $itemsModel;
$dataGrid->column('avatar', 'Ảnh đại diện', function ($row) {

    $linkEdit = 'edit.php?record_id=' . $row['pro_id'];


    $avatar = $row->avatar;
    if ($avatar) {
        return '
    <a class="edit_link" data-toggle="tooltip" title="Sửa bản ghi này" href="' . $linkEdit . '">
        <img style="width: 100px;" src="' . url() . '/upload/products/' . $avatar->file_name . '"/>
    </a>';
    }
})->addExport();
$dataGrid->column('pro_name_' . locale(), 'Tên', ['string', 'trim'], true, true)->addExport();
$dataGrid->column('pro_code', 'Mã sản phẩm', ['string', 'trim'], true, true)->addExport();
$dataGrid->column('pro_barcode', 'Barcode', ['string', 'trim'], [], true)->addExport();

//$dataGrid->column('pro_category_id', 'Danh mục', function ($row) {
//    $category = \App\Models\Categories\Category::findByID($row['pro_category_id']);
//    return $category?$category->name:'';
//});

$dataGrid->column(['pro_category_id', ['Chưa chọn danh mục'] + $categories], 'Danh mục', 'selectShow', [], true)->addExport();
$dataGrid->column(['pro_brand_id', ['Chưa chọn nhãn hiệu'] + $brands], 'Nhãn hiệu', 'selectShow', [], true);

$dataGrid->column('pro_price', 'Giá', 'money', true)->addExport();
$dataGrid->column('pro_discount_price', 'Giá khuyến mại', ['string', function ($item) {
   return number_format($item->discount_price);
}])->addExport();

//$dataGrid->column('pro_direct_commission', '% hoa hồng trực tiếp', function ($row) {
//    return $row->planCommission ? $row->planCommission->commission_percent : '';
//});

$dataGrid->column('pro_commission', 'Tổng hoa hồng', 'money', true)->addExport();
$dataGrid->column('pro_point', 'Point', 'number', true)->addExport();

//$dataGrid->column('pro_teaser_' . locale(), 'Mô tả ngắn', ['string', 'trim']);

//$dataGrid->column('pro_active', 'Active', 'active|center');
$dataGrid->column('pro_is_hot', 'Hot', 'activeDisabled|center');
$dataGrid->column('pro_is_trial', 'Dùng thử', 'active|center');
$dataGrid->column(['pro_active',[-1=>'Tất cả',0=>'Không hiển thị',1=>'Hiển thị']], 'Trạng thái', 'selectShow',true,true);
$dataGrid->column(['pro_active_inventory',[0=>'Tất cả',1=>'Còn hàng',2=>'Hết hàng']], 'Tình trạng tồn kho','selectShow',true,true);

$dataGrid->column(uniqid(), 'Chính sách giá', function ($row) use ($blade) {

    return $blade->view()->make('price_policy', compact('row'))->render();
});

//$dataGrid->column(uniqid(), 'Lên', function ($row) {
//    return '<a href=""><i class="fa fa-caret-square-o-up" aria-hidden="true"></i></a>';
//});
//
//$dataGrid->column(uniqid(), 'Xuống', function ($row) {
//    return '<a href=""><i class="fa fa-caret-square-o-down" aria-hidden="true"></i></a>';
//});

//$dataGrid->column('', 'Trạng thái', 'softDelete|center');
$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');
$dataGrid->column('opc_commission', 'Giá sỉ', ['string', 'trim'], [], false)->addExport();

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
