<?

use App\Models\Users\UserMoneyAdd;


require_once 'inc_security.php';

$sqlWhere = "1";
$str_search = getValue('uma_user_id', 'str', 'GET', '', 3);
if($str_search){
    $user =   \App\Models\Users\Users::where('use_id','=',$str_search,'OR')
        ->where('use_login','=' ,$str_search, 'OR')
        ->where('use_email','=' ,$str_search, 'OR')
        ->where('use_phone','=' ,$str_search, 'OR')
        ->all();
    if (count($user) > 0) {
        $strInArray = $user->map(function($row){
            return $row->id;
        })->all();
        $sqlWhere .= " AND uma_user_id IN (" . implode(",", $strInArray) . ")";
    }
}
$items = UserMoneyAdd::with(['user'])->where($sqlWhere)
    ->pagination(getValue('page', 'int', 'GET', 1), $per_page)
    ->order_by('uma_id', 'DESC')
    ->all();
$total = UserMoneyAdd::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'uma_id');

$dataGrid->column('uma_user_id', 'User_id', ['string', 'trim'], [], true);
$dataGrid->column(false, 'Số điện thoại',  function($row) {
    return $row->user->mobile;
});
$dataGrid->column(false, 'Email', function($row){
    return $row->user->email;
});
$dataGrid->column('uma_type', 'Hình thức nạp', ['string|center', function ($value) {
    $types = UserMoneyAdd::types();
    return $types[$value];
}]);

$dataGrid->column('uma_amount', 'Số tiền', ['string', function ($amount) {
    return number_format($amount);
}]);
$dataGrid->column('uma_note', 'Ghi chú', ['string', function ($note) {
    return $note;
}]);
$dataGrid->column('uma_created_at', 'Ngày tạo', ['string|center', function ($value) {
    return $value;
//    return date('d/m/Y H:i', $value);
}]);


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
