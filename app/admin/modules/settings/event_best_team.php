<?

use App\Models\Setting;

require_once 'inc_security.php';

checkAddEdit("edit");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));
$record_id = getValue("record_id");

//Khai báo biến khi thêm mới
$fs_title = "Tại sao chọn Hevina";
$fs_action = getURL();
$fs_errorMsg = "";

$swe_event_best_team_active = getValue('swe_event_best_team_active', 'int', 'POST', setting('swe_event_best_team_active', 0));
$swe_event_best_team_start = getValue('swe_event_best_team_start', 'str', 'POST', setting('swe_event_best_team_start', date('d/m/Y')));
$swe_event_best_team_end = getValue('swe_event_best_team_end', 'str', 'POST', setting('swe_event_best_team_end', date('d/m/Y')));

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    Setting::insertUpdate([
        'swe_key' => 'swe_event_best_team_active',
        'swe_value_vn' => $swe_event_best_team_active,
        'swe_type' => 'plain_text',
        'swe_label' => 'Chạy event đua tem mạnh nhất',
        'swe_display' => 0
    ], [
        'swe_value_vn' => $swe_event_best_team_active
    ]);
    Setting::insertUpdate([
        'swe_key' => 'swe_event_best_team_start',
        'swe_value_vn' => $swe_event_best_team_start,
        'swe_type' => 'plain_text',
        'swe_label' => 'Thời gian bắt đầu chạy event đua tem mạnh nhất',
        'swe_display' => 0
    ], [
        'swe_value_vn' => $swe_event_best_team_start
    ]);
    Setting::insertUpdate([
        'swe_key' => 'swe_event_best_team_end',
        'swe_value_vn' => $swe_event_best_team_end,
        'swe_type' => 'plain_text',
        'swe_label' => 'Thời gian kết thúc Chạy event đua tem mạnh nhất',
        'swe_display' => 0
    ], [
        'swe_value_vn' => $swe_event_best_team_end
    ]);
}//End if($action == "insert")

echo $blade->view()->make('event_best_team', get_defined_vars())->render();
return;
?>