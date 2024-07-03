<?

use App\Models\Users\Users;

require_once 'inc_security.php';


$sqlWhere = "1";
$is_paid = getValue('upr_is_paid', 'int', 'GET', -1);
if ($is_paid >= 0) {
    $sqlWhere .= ' AND upr_is_paid = ' . $is_paid;
}

$user_name = getValue('user_name', 'str', 'GET', '');
if ($user_name) {
    $users = Users::where('use_name LIKE \'%' . $user_name . '%\'')->all();
    $user_ids = $users->lists('use_id');
    $user_ids = $user_ids ? $user_ids : [0];
    $sqlWhere .= ' AND upr_user_id IN (' . implode(',', $user_ids) . ')';
}

$items = \App\Models\Users\UserPaymentRequest::where($sqlWhere)
    ->pagination(getValue('page', 'int', 'GET', 1), $per_page)
    ->order_by('upr_created_at', 'DESC')
    ->all();

$total = \App\Models\Users\UserPaymentRequest::where($sqlWhere)->count();


$dataGrid = new DataGrid($items, $total, 'upr_id');

$dataGrid->column('user_name', 'Người dùng', function ($row) {
    return $row->user->name;
}, [], true);
$dataGrid->column('upr_money', 'Số tiền muốn rút', 'money');

$dataGrid->column('upr_bank_id', 'Ngân hàng', function ($row) {

    return '<table>
    <tr>
    <td width="120">Tên ngân hàng: </td>
    <td> ' . $row->bank->bank_name . '</td>
</tr>
<tr>
<td>Chủ tk: </td>
<td> ' . $row->bank->account_name . '</td>
</tr>
<tr>
<td>Số tk: </td>
<td> ' . $row->bank->account_number . '</td>
</tr>
<tr>
<td>
Chi nhánh: 
</td>
<td>
' . $row->bank->branch . '
</td>
</tr>
</table>';

});

$dataGrid->column('upr_is_paid', 'Đã thực hiện?', 'activeDisabled|center', [], true);

$dataGrid->column('upr_id', 'Xác nhận đã chuyển khoản', function ($row) {
    if (!$row->is_paid) {
        return '<button data-toggle="modal" data-target="#payment_request_modal_' . $row->id . '">Xác nhận</button>


    <div class="modal fade" id="payment_request_modal_' . $row->id . '">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Xác nhận yêu cầu rút hoa hồng của ' . $row->user->name . '(' . $row->user->id . ':' . $row->user->email . ')</h4>
          </div>
          <div class="modal-body">
            <form>
            
                <table>
                    <tr>
                        <td>Số tiền hoa hồng:</td>
                        <td>' . number_format($row->user->wallet->commission) . 'đ</td>
                    </tr>
                    <tr>
                        <td>Yêu cầu rút:</td>
                        <td>' . number_format($row->money) . 'đ</td>
                    </tr>
                    <tr>
                        <td width="120">Tên ngân hàng: </td>
                        <td> ' . $row->bank->bank_name . '</td>
                    </tr>
                    <tr>
                        <td>Chủ tk: </td>
                        <td> ' . $row->bank->account_name . '</td>
                    </tr>
                    <tr>
                        <td>Số tk: </td>
                        <td> ' . $row->bank->account_number . '</td>
                    </tr>
                    <tr>
                        <td>
                        Chi nhánh: 
                        </td>
                        <td>
                        ' . $row->bank->branch . '
                        </td>
                    </tr>
                </table>
            
                <div class="form-group">
                  <label>Ghi chú</label>
                  <textarea class="form-control" name="payment_request_note_'.$row->id.'" id="payment_request_note_'.$row->id.'" rows="3" placeholder="Nhập nội dung ghi chú ..."></textarea>
                </div>
            
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn pull-left" data-dismiss="modal">Close</button>
            <button type="button" class="btn" onclick="payment_is_paid(' . $row->id . ', $(\'#payment_request_note_'.$row->id.'\').val())">Xác nhận</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


    ';
    } else {
        return '<table>
    <tr>
    <td width="70">
    Admin: 
    </td>
    <td> ' . $row->admin->name . '</td>
</tr><tr>
    <td width="70">
    Vào lúc: 
    </td>
    <td> ' . date('H:i:s d/m/Y', $row->paid_time) . '</td>
</tr><tr>
    <td width="70">
    Ghi chú: 
    </td>
    <td> ' .$row->note . '</td>
</tr>
</table>';
    }
});

$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
