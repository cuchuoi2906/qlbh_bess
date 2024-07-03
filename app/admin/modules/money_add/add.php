<?
require_once 'inc_security.php';
checkAddEdit("add");

$myform = new generate_form();

//Khai báo biến khi thêm mới
$add = "add.php?cat_parent_id=" . getValue('cat_parent_id', 'int', 'GET') . '&type=' . getValue('type', 'str');
$listing = "listing.php";
$after_save_data = getValue("after_save_data", "str", "POST", $add);
$fs_title = translate("Thêm mới danh mục");
$fs_action = getURL();
$fs_redirect = $after_save_data;
$fs_errorMsg = "";
$cat_has_child = 0;


$uma_user_id = getValue('uma_user_id', 'int', 'POST', '');
$uma_amount = getValue('uma_amount', 'int', 'POST', '');
$uma_note = getValue('uma_note', 'str', 'POST', '');
$uma_type = \App\Models\Users\UserMoneyAdd::TYPE_METHOD_ADMIN;


$myform->add('uma_user_id', 'uma_user_id', 1, 1,  '');
$myform->add('uma_type', 'uma_type', 2, 1,  '');
$myform->add('uma_amount', 'uma_amount', 1, 1, 0);
$myform->add('uma_note', 'uma_note', 0, 1, '');
$myform->addTable($fs_table);

$myform->evaluate();


$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $user = \App\Models\Users\Users::findByID($uma_user_id);
    if(!$user){
        $fs_errorMsg .= "user id không tồn tại";
    }
    $fs_errorMsg .= $myform->checkdata();
    if ($fs_errorMsg == '') {
        $uma_note .= " Admin id: " . $admin_id . "\n" . "IP Admin: " . $_SERVER['REMOTE_ADDR'] ?? 'No support' ;
        $db_excute = new db_execute_return();
        $id = $db_excute->db_execute($myform->generate_insert_SQL());
        if($id){
            $moneyAddManager = new \App\Manager\MoneyAdd\MoneyAddManager();
            $moneyAddManager->add_money($uma_user_id, $uma_amount, 'Nạp tiền trực tiếp');
        }

        unset($db_excute);
        redirect($fs_redirect);
    }
}
echo $blade->view()->make('add', get_defined_vars())->render();


?>