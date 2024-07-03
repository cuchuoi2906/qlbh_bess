<?

use App\Models\AdminUser;

require_once 'inc_security.php';


$listingModel = AdminUser::where('
    adm_loginname NOT IN (\'admin\')
    AND adm_delete = 0
');

if (sorting()) {
    $listingModel->order_by(sort_field(), sort_type());
} else {
    $listingModel->order_by('adm_active', 'DESC')
        ->order_by('adm_loginname', 'ASC');
}

$listing = $listingModel
    ->all();

$dataGrid = new DataGrid($listing, $listing->count(), 'adm_id');
$dataGrid->column('adm_loginname', 'Username', 'string', true);
$dataGrid->column('adm_name', 'Fullname', 'string', true);
$dataGrid->column('adm_email', 'Email', 'string', true);
//$dataGrid->column('adm_isadmin', 'Root', 'active');

$dataGrid->column('', 'Sửa', 'edit|center');
$dataGrid->column('', 'Xóa', 'delete|center');

echo $blade->view()->make('listing', [
        'data_table' => $dataGrid->render(),
    ] + get_defined_vars())->render();
die;