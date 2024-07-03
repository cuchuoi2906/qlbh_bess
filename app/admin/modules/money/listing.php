<?

use App\Models\Users\Users;

require_once 'inc_security.php';

$use_name = getValue('use_name', 'str', 'GET', '', 3);
$use_email = getValue('use_email', 'str', 'GET', '', 3);
$use_phone = getValue('use_phone', 'str', 'GET', '', 3);
$use_id = getValue('use_id', 'int', 'GET', '', 3);

$sqlWhere = "1";

if ($use_name) {
    $sqlWhere .= " AND use_name LIKE '%" . $use_name . "%'";
}

if ($use_email) {
    $sqlWhere .= " AND use_email = '" . $use_email . "'";
}

if ($use_id) {
    $sqlWhere .= " AND use_id = " . $use_id;
}

if ($use_phone) {
    $sqlWhere .= " AND use_phone = '" . $use_phone . "'";
}

$sort_field = getValue('sort_field', 'str', 'GET', '');
$sort_type = getValue('sort_type', 'str', 'GET', 'DESC');

$items = [];

//
$itemsModel = Users::with(['wallet'])
    ->where($sqlWhere)->pagination(getValue('page'), $page_size);

if ($sort_field) {
    switch ($sort_field) {
        case 'charge':
            $itemsModel->left_join('user_wallet', 'use_id = usw_user_id')
                ->order_by('usw_charge', $sort_type);
            break;
        case 'commission':
            $itemsModel->left_join('user_wallet', 'use_id = usw_user_id')
                ->order_by('usw_commission', $sort_type);
            break;
        default:
            $itemsModel->order_by('use_id', 'DESC');
            break;
    }
}

$items = $itemsModel->all();
//dd($items);
$total = Users::where($sqlWhere)->count();

$total_money_wallet = \App\Models\Users\UserWallet::inner_join('users', 'use_id = usw_user_id')
    ->where($sqlWhere)
    ->sum('usw_charge', 'total_charge')
    ->sum('usw_commission', 'total_commission')
    ->first();
$total_money_charge = $total_money_wallet->total_charge;
$total_money_commission = $total_money_wallet->total_commission;

$dataGrid = new DataGrid($items, $total, 'use_id');
$dataGrid->column('use_id', 'ID', 'number', [], true);
$dataGrid->column('use_name', 'Tên User', ['string', 'trim'], [], true);
$dataGrid->column('charge', 'Tiền nạp', function ($user) {
    $money = '0 đ';
    if ($user->wallet) {
        $money = number_format($user->wallet->charge, 0, ',', '.') . ' đ';
    }

    return '<div class="text-center">' . $money . '</div>';
}, true);
$dataGrid->total('charge', $total_money_charge, 'đ');
$dataGrid->column('commission', 'Điểm tích lũy', function ($user) {
    $money = '0 đ';
    if ($user->wallet) {
        $money = number_format($user->wallet->commission, 0, ',', '.') . ' đ';
    }

    return '<div class="text-center">' . $money . '</div>';
}, true);
$dataGrid->column(uniqid(), 'VAT', function ($user) {
    $money = '0 đ';
    if ($user->wallet) {
        $money = number_format((int)($user->wallet->commission / 9), 0, ',', '.') . ' đ';
    }

    return '<div class="text-center">' . $money . '</div>';
});
$dataGrid->total('commission', $total_money_commission, 'đ');
$dataGrid->column('use_phone', 'Điện thoại', ['string', 'trim'], [], true);

$dataGrid->column(uniqid(), 'Nạp tiền', function ($row) {
    $url = 'add_money.php?record_id=' . $row['use_id'];

    return '<div class = "text-center" ><a href="' . $url . '"><i class="fa fa-plus" aria-hidden="true"></i></a></div>';
});

$dataGrid->column(uniqid(), 'Trừ tiền', function ($row) {
    $url = 'reduction_money.php?record_id=' . $row['use_id'];
    if ($row->wallet) {
        return '<div class = "text-center" ><a href="' . $url . '"><i class="fa fa-minus" aria-hidden="true"></i></a></div>';
    }

    return '';
});
$dataGrid->column(false, 'Lịch sử', function ($row) use ($blade) {
    $wallet_log = \App\Models\Users\UserWalletLog::with(['adminInfo'])->where('uwl_use_id', $row->id)->where('uwl_type', '=', 0)->order_by('uwl_id', 'DESC')->all();

    return $blade->view()->make('wallet_log', compact('wallet_log', 'row'))->render();
});

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
