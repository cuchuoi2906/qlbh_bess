<?

use App\Models\Setting;

require_once 'inc_security.php';

checkAddEdit("edit");

$total = Setting::where('swe_key', 'like', 'bank_account_number_%')->count();
$total = $total > 0 ? $total : 1;

//Khai báo biến khi thêm mới
$fs_title = "Tài khoản";
$fs_action = getURL();
$fs_errorMsg = "";

for ($i = 1; $i <= $total; $i++) {

    $account_number = Setting::where('swe_key', '=', 'bank_account_number_' . $i)->first();
    ${'account_number_' . $i} = $account_number->value ?? '';
    $bank_name = Setting::where('swe_key', '=', 'bank_account_bank_name_' . $i)->first();
    ${'bank_name_' . $i} = $bank_name->value ?? '';
    $branch = Setting::where('swe_key', '=', 'bank_account_branch_' . $i)->first();
    ${'branch_' . $i} = $branch->value ?? '';
    $name = Setting::where('swe_key', '=', 'bank_account_name_' . $i)->first();
    ${'name_' . $i} = $name->value ?? '';

}

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {

    $account_numbers = getValue('account_numbers', 'arr', 'POST', []);
    $bank_names = getValue('bank_names', 'arr', 'POST', []);
    $branches = getValue('branches', 'arr', 'POST', []);
    $names = getValue('names', 'arr', 'POST', []);

    for ($i = 1; $i <= $total + 1; $i++) {
        ${'account_number_' . $i} = $account_numbers[$i] ?? '';
        ${'bank_name_' . $i} = $bank_names[$i] ?? '';
        ${'branch_' . $i} = $branches[$i] ?? '';
        ${'name_' . $i} = $names[$i] ?? '';
        if (${'account_number_' . $i}) {
            Setting::insertUpdate([
                'swe_key' => 'bank_account_number_' . $i,
                'swe_value_vn' => ${'account_number_' . $i},
                'swe_type' => 'plain_text',
                'swe_label' => 'Số tài khoản ngân hàng thứ ' . $i,
                'swe_display' => 0
            ], [
                'swe_value_vn' => ${'account_number_' . $i},
            ]);
            Setting::insertUpdate([
                'swe_key' => 'bank_account_bank_name_' . $i,
                'swe_value_vn' => ${'bank_name_' . $i},
                'swe_type' => 'plain_text',
                'swe_label' => 'Tên ngân hàng thứ ' . $i,
                'swe_display' => 0
            ], [
                'swe_value_vn' => ${'bank_name_' . $i},
            ]);
            Setting::insertUpdate([
                'swe_key' => 'bank_account_branch_' . $i,
                'swe_value_vn' => ${'branch_' . $i},
                'swe_type' => 'plain_text',
                'swe_label' => 'Chi nhánh ngân hàng thứ ' . $i,
                'swe_display' => 0
            ], [
                'swe_value_vn' => ${'branch_' . $i},
            ]);
            Setting::insertUpdate([
                'swe_key' => 'bank_account_name_' . $i,
                'swe_value_vn' => ${'name_' . $i},
                'swe_type' => 'plain_text',
                'swe_label' => 'Chủ tài khoản ngân hàng thứ ' . $i,
                'swe_display' => 0
            ], [
                'swe_value_vn' => ${'name_' . $i},
            ]);
        }
    }

    \AppView\Helpers\Facades\FlashMessage::success('Thành công', url_back());

}//End if($action == "insert")

echo $blade->view()->make('bank_accounts', get_defined_vars())->render();
return;
?>