<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

checkAddEdit("add");

$fs_redirect = base64_decode(getValue("url", "str", "GET", base64_encode("listing.php")));

//Khai báo biến khi thêm mới
$fs_title = "Thêm mới thông báo";
$fs_action = getURL();
$fs_errorMsg = "";


// Lấy giá trị từ POST
$not_title = getValue('not_title', 'str', 'POST', '');
$not_content = getValue('not_content', 'str', 'POST', '');
$not_is_send_all = getValue('not_is_send_all', 'int', 'POST', 0);
$not_created_at = date('Y-m-d H:i:s');
$not_type = getValue('not_type', 'str', 'POST', '');
$user_ids = getValue('user_ids', 'arr', 'POST', []);

//Call Class generate_form();
$myform = new generate_form();
$myform->add('not_title', 'not_title', 0, 1, '', 1, 'Chưa nhập tiêu đề notification');
$myform->add('not_content', 'not_content', 0, 1, '', 1, 'Chưa nhập nội dung notification');
$myform->add('not_created_at', 'not_created_at', 0, 1, '');
$myform->add('not_is_send_all', 'not_is_send_all', 1, 1, 1);
$myform->add('not_type', 'not_type', 0, 1, '');
$myform->addTable($fs_table);

$myform->evaluate();

//Get action variable for add new data
$action = getValue("action", "str", "POST", "");
//Check $action for insert new data
if ($action == "execute") {
    if ($fs_errorMsg == "") {

        $fs_errorMsg .= $myform->checkdata();
        if ($fs_errorMsg == '') {
            $db_excute = new db_execute_return();
            $sqlUpdate = $myform->generate_insert_SQL();
            $not_id = $db_excute->db_execute($sqlUpdate);
            unset($db_excute);
            $not_id = (int)$not_id;

            if ($not_id > 0) {
                $sql_update = "Update notification SET not_admin_id = 1 WHERE not_id = ".$not_id;
                $db_update_link = new db_execute($sql_update);
                if ($not_is_send_all) {
                    $user_ids = App\Models\Users\Users::all();
                    $user_ids = $user_ids->lists('use_id');
                }

                if (!empty($user_ids)) {
                    foreach ($user_ids as $key => $value) {
                        $nts_user_id = (int)$value;
                        $sql = "INSERT INTO notification_status (`nts_notification_id`, `nts_user_id`) VALUES  ($not_id, $nts_user_id)";
                        $db_update_link = new db_execute($sql);
                    }
                }
                unset($db_excute);
            }

            \VatGia\Queue\Facade\Queue::pushOn(\App\Workers\SendNotificationWorker::$name, \App\Workers\SendNotificationWorker::class, [
                'id' => $not_id
            ]);

            redirect($fs_redirect);
        }
    }//End if($fs_errorMsg == "")

}//End if($action == "insert")

$users = \App\Models\Users\Users::where('use_active', 1)->all();

echo $blade->view()->make('add', get_defined_vars())->render();
return;
?>