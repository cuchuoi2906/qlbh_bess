<?php

use App\Models\Users\Users;

require_once 'inc_security.php';

$model = new App\Models\Users\Users();
$sqlWhere = "1";

$use_id = getValue('use_id', 'int', 'GET', 0);
if ($use_id) {
    $sqlWhere .= ' AND use_id = ' . $use_id;
}

$use_referral_id = getValue('use_referral_id', 'int', 'GET', 0);
if ($use_referral_id) {
    $sqlWhere .= ' AND use_referral_id = ' . $use_referral_id;
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

$use_level = getValue('use_level', 'int', 'GET', -1);
if ($use_level > 0) {
    $sqlWhere .= ' AND use_level = ' . $use_level;
}
$use_sale = getValue('use_sale', 'int', 'GET', 0);
if ($use_sale > 0) {
    $sqlWhere .= ' AND use_sale = ' . $use_sale;
}

$use_source = getValue('use_source', 'int', 'GET', 0);
if ($use_source > 0) {
    $sqlWhere .= ' AND use_source = ' . $use_source;
}

$use_deleted_at = getValue('use_deleted_at', 'int', 'GET', -1);
if ($use_deleted_at == 0) {
} elseif ($use_deleted_at == 1) {
    $model = $model->onlyTrash();
} else {
    $model = $model->withTrash();
}

$use_active = getValue('use_active', 'int', 'GET', -1);
if ($use_active >= 0) {
    $model->where('use_active', '=', $use_active);
}
$use_premium = getValue('use_premium', 'int', 'GET', -1);
if ($use_premium >= 0) {
    $model->where('use_premium', '=', $use_premium);
}
$model->where($sqlWhere);

$model->with(['wallet', ['childs', function (Users $model) {
    return $model->withTrash();
}], 'parent'])
    ->pagination(getValue('page'), $per_page)
    ->order_by('use_id', 'DESC');
$model ->setFields('users.*,(SELECT a.use_name FROM users a WHERE a.use_id = users.use_referral_id) username_referral');
$items= $model->all();
$total = $model->count();

$dataGrid = new DataGrid($items, $total, 'use_id');

$dataGrid->model = $model;

$dataGrid->deleteLabel = 'hủy';

$dataGrid->column('use_id', 'ID', 'number', [], true)->addExport();
$dataGrid->column('use_referral_id', 'ID người giới thiệu', 'number', [], true)->addExport();
$dataGrid->column('username_referral', 'Tên người giới thiệu', 'string', [], false)->addExport();
$dataGrid->column('use_created_at', 'Ngày đăng ký', 'datetime', [], false)->addExport();
$dataGrid->column('use_name', 'Tên', ['string', 'trim'], [], true)->addExport();
//$dataGrid->column(['use_gender', [0 => 'Female', 1 => 'Male']], 'Giới tính', 'select');
$dataGrid->column('use_email', 'Email', 'string', [], true)->addExport();
$dataGrid->column('use_phone', 'Số điện thoại', 'string', [], true)->addExport();
$dataGrid->column(false, 'Ví nạp', function ($row) {
    return $row->wallet ? number_format($row->wallet->charge) : 0;
})->addExport();
$dataGrid->column(false, 'Ví Hoa hồng', function ($row) {
    return $row->wallet ? number_format($row->wallet->commission) : 0;
})->addExport();
$dataGrid->column('use_level', 'Cấp độ hiện tại', 'number', [], true)->addExport();
$dataGrid->column('use_active', 'Active', 'activeDisabled|center', [], true)->addExport();
$dataGrid->column('use_premium', 'Premium', 'active|center', [], true)->addExport();
$dataGrid->column('use_premium_commission', 'Premium Commission', 'number|center', [], false)->addExport();

$dataGrid->column('use_family', 'Đặc biệt (Mua hàng ưu đãi)', 'active|center', [], true)->addExport();
$dataGrid->column('use_is_seller', 'Mua hàng chiết khẩu max', 'active|center', [], true)->addExport();


//Tổng số thành viên
$dataGrid->column('total_direct_refer', 'F1', 'number', [], [])->addExport();
$dataGrid->column('total_refer', 'Tổng số F', 'number', [], [])->addExport();


$dataGrid->column(false, 'Chi tiết point', function ($row) use ($blade) {
    return '<a href="point.php?user_id=' . $row->id . '">Chi tiết Point</a>';
});

$dataGrid->column(false, 'Chi tiết', function ($row) use ($blade) {
    return $blade->view()->make('user_detail_modal', compact('row'))->render();
});
$dataGrid->column(false, 'Đơn hàng', function ($row) use ($blade) {
    return $blade->view()->make('order_modal', compact('row'))->render();
});
$dataGrid->column(false, 'Giao dịch', function ($row) use ($blade) {
    return $blade->view()->make('money_modal', compact('row'))->render();
});
$dataGrid->column(false, 'Khảo sát', function ($row) use ($blade) {
    return $blade->view()->make('survey_modal', compact('row'))->render();
});
$dataGrid->column('use_sale', 'Nhân viên sale', 'activeDisabled|center', [], true)->addExport();
$dataGrid->column(uniqid(), 'Link giới thiệu', function ($row) {
    return url('invite', ['base64' => base64_encode($row->id)]);
})->addExport();

$dataGrid->column(uniqid(), 'Địa Chỉ', function ($row) {
    $v_address = $row->use_address_register;
    if($row->use_ward_id > 0 && $row->use_district_id > 0 && $row->use_province_id > 0){
        $ward = \App\Models\Ward::findByID($row->use_ward_id);
        $district = \App\Models\District::findByID($row->use_district_id);
        $province = \App\Models\Province::findByID($row->use_province_id);
        $v_address .= '-'. $ward->name.'-'.$district->name.'-'.$province->name;
    }
    return $v_address;
})->addExport();

$dataGrid->column(['use_source', ['Tất cả'] + $sourceUser], 'Nguồn khách hàng', 'selectShow', [], true)->addExport();

$dataGrid->column(uniqid(), 'Nghề Nghiệp', function ($row) use ($use_job) {
    if(isset($use_job[$row->use_job_code])){
        return $use_job[$row->use_job_code];
    }
    return '';
})->addExport();

$dataGrid->search('use_deleted_at', 'Đã hủy?', 'active');
$dataGrid->column('', 'Trạng thái hủy', 'softDelete|center');
$dataGrid->column('', 'Sửa', function($row){
    if($row->use_active == -99){
        return '';
    }
    $linkEdit = 'edit.php?record_id=' . $row->use_id . '&page=' . getValue('page', 'int', 'GET', 1) . '&type=' . getValue('type', 'str');
    return '<a href="' . $linkEdit . '">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            </a>';
});
//$dataGrid->column('', 'Sửa', "edit|center");
$dataGrid->column('', 'Hủy', function($row){
    if($row->use_active == -99){
        return '';
    }
    $linkDelete = 'delete.php?record_id=' . $row->use_id;
    return '<a onclick="return confirm(\'Bạn có chắc muốn xóa bản ghi này?\');" href="' . $linkDelete . '">
            <i class="fa fa-times" aria-hidden="true"></i>
        </a>';
});
//$dataGrid->column('', 'Hủy', 'delete|center');
$dataGrid->column('', 'Phục hồi', function($row){
    if($row->use_active == -99){
        return '';
    }
    $linkDelete = 'restore.php?record_id=' . $row->use_id;

            return '<a title="Khôi phục bản ghi" onclick="return confirm(\'Bạn có chắc muốn khôi phục bản ghi này?\');" href="' . $linkDelete . '">
        <i class="fa fa-times" aria-hidden="true"></i>
    </a>';
});
//$dataGrid->column('', 'Phục hồi', 'restore|center');

if (
    //false &&
    $is_admin ?? false
) {
    $dataGrid->column(uniqid(), 'Token', function ($row) {
        return \Firebase\JWT\JWT::encode([
            'user_id' => $row->id
        ], config('app.jwt_key'), 'HS256');
    });
}

echo $blade->view()->make('listing', [
    'data_table' => $dataGrid->render()
] + get_defined_vars())->render();
die;
