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

$titles = getValue('title', 'arr', 'POST', []);
$contents = getValue('content', 'arr', 'POST', []);

for ($i = 1; $i < 4; $i++) {

    $setting_title = Setting::find('swe_key = \'why_choose_title_' . $i . '\'');
    ${'title' . $i} = $setting_title->value;

    $setting_content = Setting::find('swe_key = \'why_choose_content_' . $i . '\'');
    ${'content' . $i} = $setting_content->value;

}

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    for ($i = 1; $i < 4; $i++) {
        ${'title' . $i} = $titles[$i];
        ${'content' . $i} = $contents[$i];

        $setting_title = Setting::find('swe_key = \'why_choose_title_' . $i . '\'');
        if ($setting_title) {
            $setting_title->update([
                'swe_value' => $titles[$i],
                'swe_type' => 'plain_text',
                'swe_update_time' => gmdate('Y-m-d H:i:s'),
                'swe_label' => 'Vì sao chọn Hevina - Tiêu đề ' . $i
            ]);
        } else {
            Setting::insert([
                'swe_key' => 'why_choose_title_' . $i,
                'swe_value' => $titles[$i],
                'swe_type' => 'plain_text',
                'swe_update_time' => gmdate('Y-m-d H:i:s'),
                'swe_label' => 'Vì sao chọn Hevina - Tiêu đề ' . $i
            ]);
        }
        $setting_content = Setting::find('swe_key = \'why_choose_content_' . $i . '\'');
        if ($setting_content) {
            $setting_content->update([
                'swe_value' => $contents[$i],
                'swe_type' => 'plain_text',
                'swe_update_time' => gmdate('Y-m-d H:i:s'),
                'swe_label' => 'Vì sao chọn Hevina - Nội dung ' . $i
            ]);
        } else {
            Setting::insert([
                'swe_key' => 'why_choose_content_' . $i,
                'swe_value' => $contents[$i],
                'swe_type' => 'plain_text',
                'swe_update_time' => gmdate('Y-m-d H:i:s'),
                'swe_label' => 'Vì sao chọn Hevina - Nội dung ' . $i
            ]);
        }
    }
}//End if($action == "insert")

echo $blade->view()->make('why_choose', get_defined_vars())->render();
return;
?>