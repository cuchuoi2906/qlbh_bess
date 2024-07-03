<?

use App\Models\Users\UserMoneyAddRequest;


require_once 'inc_security.php';

$sqlWhere = "1";
$str_search = getValue('umar_user_id', 'str', 'GET', '', 3);
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
        $sqlWhere .= " AND umar_user_id IN (0,"  . implode(",", $strInArray) . ")";
    }
}
$umarType = getValue('umar_type', 'str', 'GET', '', 3);
if($umarType){
    $sqlWhere .= " AND umar_type = '{$umarType}'";
}

$umarStatus = getValue('umar_status', 'str', 'GET', '', 3);
if($umarStatus){
    $sqlWhere .= " AND umar_status = '{$umarStatus}'";
}
$items = UserMoneyAddRequest::with(['user'])->where($sqlWhere)
    ->pagination(getValue('page', 'int', 'GET', 1), $per_page)
    ->order_by('umar_id', 'DESC')
    ->all();
$total = UserMoneyAddRequest::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'umar_id');
$dataGrid->column('umar_id', 'Request ID', ['string', 'trim'], [], true);
$dataGrid->column('umar_user_id', 'User ID', ['string', 'trim'], [], true);

$dataGrid->column(false, 'Số điện thoại',  function($row) {
    return $row->user->mobile;
});
$dataGrid->column(false, 'Email', function($row){
    return $row->user->email;
});
$dataGrid->column('umar_type', 'Hình thức nạp', ['string|center', function ($value) {
    $types = UserMoneyAddRequest::types();
    return $types[$value];
}],[]);

$dataGrid->column('umar_status', 'Trạng thái', ['string|center', function ($value) {
    $types = UserMoneyAddRequest::status();
    return $types[$value];
}],[]);

$dataGrid->column('umar_amount', 'Số tiền', ['string', function ($amount) {
    return number_format($amount);
}]);
$dataGrid->column('umar_note', 'Ghi chú', ['string', function ($note) {
    return $note;
}]);
$dataGrid->column('umar_created_at', 'Ngày tạo', ['string|center', function ($value) {
    return $value;
//    return date('d/m/Y H:i', $value);
}]);


echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
