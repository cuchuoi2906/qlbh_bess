<?

use App\Models\Contact;

require_once 'inc_security.php';

$cat_name = getValue('cat_name', 'str', 'GET', '', 3);
$cat_type = getValue('cat_type', 'str', 'GET', '', 3);

$sqlWhere = "1";

$items = Contact::where($sqlWhere)
    ->pagination(getValue('page'), $per_page)
    ->order_by('con_created_at', 'DESC')
    ->all();

$total = Contact::where($sqlWhere)->count();

$dataGrid = new DataGrid($items, $total, 'con_id');

$dataGrid->column('con_created_at', 'Ngày', function ($row) {
    return (new DateTime($row->created_at))->format('H:i:s d/m/Y');
});

$dataGrid->column('con_full_name', 'Tên', ['string', 'trim'], [], true);
$dataGrid->column('con_email', 'Thư điện tử', ['string', 'trim'], [], true);
$dataGrid->column('con_phone', 'Số điện thoại', ['string', 'trim'], [], true);


$dataGrid->column(false, 'Xem nội dung', function ($row) {

    return '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#contact_detail_' . $row->id . '">
  Xem chi tiết
</button>

<!-- Modal -->
<div class="modal fade" id="contact_detail_' . $row->id . '" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ' . $row->note . '
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng lại</button>
      </div>
    </div>
  </div>
</div>

';

});

$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render()
    ] + get_defined_vars())->render();
die;
?>
