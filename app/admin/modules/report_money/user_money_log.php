<?

use App\Models\UserMoneyLog;

require_once 'inc_security.php';


$sqlWhere = "1";

// $use_id = getValue('use_id', 'int', 'GET', 0);
// if ($use_id) {
//     $sqlWhere .= ' AND use_id = ' . $use_id;
// }

// $use_name = getValue('use_name', 'str', 'GET', '', 3);
// if ($use_name) {
//     $sqlWhere .= " AND use_name LIKE '%" . $use_name . "%'";
// }

// $use_email = getValue('use_email', 'str', 'GET', '', 3);
// if ($use_email) {
//     $sqlWhere .= " AND use_email = '" . $use_email . "'";
// }

// $use_phone = getValue('use_phone', 'str', 'GET', '', 3);
// if ($use_phone) {
//     $sqlWhere .= " AND use_login = '" . $use_phone . "'";
// }

$range_date = getValue('uml_created_at', 'str', 'GET', '', 3);

if (!$range_date) {
    //    $start_month = new DateTime('first day of this month');
    //    $start_day = $start_month->format('d/m/Y');
    //    $range_date = date('d/m/Y') . ' - ' . date('d/m/Y');
}

if ($range_date) {
    $dates = explode(" - ", $range_date);
    $star_date = new \DateTime(str_replace('/', '-', $dates[0]));
    $end_date = new \DateTime(str_replace('/', '-', $dates[1]));
    $sqlWhere .= " AND uml_created_at BETWEEN '" . $star_date->format('Y-m-d 00:00:01') . "' AND '" . $end_date->format('Y-m-d 23:59:59') . '\'';
}

//
//$categories = \App\Models\Categories\Category::where($sqlWhere)
//    ->pagination(getValue('page'), $per_page)
//    ->order_by('cat_order', 'DESC')
//    ->select_all();
//$total = \App\Models\Categories\Category::where($sqlWhere)->count();

// $items = Users::withTrash()
//     ->where($sqlWhere)->with(['wallet', 'childs', 'parent'])
//     ->pagination(getValue('page'), $per_page)
//     ->order_by('use_id', 'DESC')
//     ->all();

$items = UserMoneyLog
    ::fields('*')
    ->sum('IF(uml_type IN(\'' . UserMoneyLog::TYPE_COMMISSION . '\', \'' . UserMoneyLog::TYPE_TRANSFER . '\'), IF(`uml_amount` > 0, uml_amount, 0), 0)', 'commission_money_add')
    ->sum('IF(uml_type IN(\'' . UserMoneyLog::TYPE_COMMISSION . '\', \'' . UserMoneyLog::TYPE_TRANSFER . '\'), IF(`uml_amount` < 0, uml_amount, 0), 0)', 'commission_money_sub')
    ->sum('IF(uml_type = \'' . UserMoneyLog::TYPE_MONEY_ADD . '\', IF(`uml_amount` > 0, uml_amount, 0), 0)', 'charge_money_add')
    ->sum('IF(uml_type = \'' . UserMoneyLog::TYPE_MONEY_ADD . '\', IF(`uml_amount` < 0, uml_amount, 0), 0)', 'charge_money_sub')
    ->where($sqlWhere)
    ->group_by('uml_user_id')
    ->order_by(sort_field('commission_money_add'), sort_type())
    ->all();



// $total = Users::withTrash()->where($sqlWhere)->count();

$dataGrid = new DataGrid($items, count($items), 'use_id');
$dataGrid->search('uml_created_at', 'Ngày', 'string');
$dataGrid->column('user.name', 'Thành viên', 'string', [], false);
$dataGrid->column('charge_money_add', 'Ví tiêu dùng (+)', 'money', [1], false);
$dataGrid->column('charge_money_sub', 'Ví tiêu dùng (-)', 'money', [1], false);
$dataGrid->column('commission_money_add', 'Tích lũy(+)', 'money', [1], false);
$dataGrid->column('commission_money_sub', 'Tích lũy (-)', 'money', [1], false);

// $dataGrid->column(false, 'Lịch sử', function ($row) use ($blade) {
// //    dump($row->childs());
// //    dump($row->parent);
//     return $blade->view()->make('user_detail_modal', compact('row'))->render();
// });

echo $blade->view()->make('listing', [
    'data_table' => $dataGrid->render()
] + get_defined_vars())->render();
die;
