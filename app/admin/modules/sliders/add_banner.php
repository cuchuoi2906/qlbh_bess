<?
require_once 'inc_security.php';
checkAddEdit("add");

//Khai báo biến khi thêm mới
$add = "add_banner.php";
$listing = "listing.php";
$after_save_data = getValue("after_save_data", "str", "POST", $add);
$fs_title = translate("Thêm banner");
$fs_action = getURL();
$fs_redirect = $after_save_data;
$fs_errorMsg = "";

// Lấy giá trị từ POST
$ban_title = getValue('ban_title', 'str', 'POST', '');
$ban_link = getValue('ban_link', 'str', 'POST', '');
$ban_type = getValue('ban_type', 'int', 'POST', 0);
$ban_html = getValue('ban_html', 'str', 'POST', '');
$ban_active = getValue('ban_active', 'int', 'POST', 1);
$ban_create_time = time();

$ban_object_type = getValue('ban_object_type', 'str', 'POST', '');
$ban_object_id = getValue('ban_object_id', 'int', 'POST', 0);

//Call Class generate_form();
$myform = new generate_form();
$myform->add('ban_title', 'ban_title', 0, 1, '', 1, 'Chưa nhập tiêu đề banner');
$myform->add('ban_link', 'ban_link', 0, 1, '', 0, 'Chưa nhập đường dẫn');
$myform->add('ban_html', 'ban_html', 0, 1, 0);
$myform->add('ban_type', 'ban_type', 1, 1, 0);
$myform->add('ban_active', 'ban_active', 1, 1, 0);
$myform->add('ban_create_time', 'ban_create_time', 1, 1, 0);

$myform->add('ban_object_type', 'ban_object_type', FORM_ADD_TYPE_STRING, FORM_ADD_VALUE_FROM_GLOBAL, $ban_object_type);
$myform->add('ban_object_id', 'ban_object_id', FORM_ADD_TYPE_INT, FORM_ADD_VALUE_FROM_GLOBAL, $ban_object_id);

$myform->addTable('banner');

$myform->evaluate();


$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();

    if ($fs_errorMsg == '') {

        $upload = new upload('ban_image', $fs_filepath, $fs_extension, $fs_filesize);
        $fs_errorMsg = $upload->common_error;
        if ($fs_errorMsg == '') {
            $file_name = $upload->file_name;
            if ($file_name != '') {
                $myform->add('ban_image', 'file_name', 0, 1, '', 1, '');
            }
            $db_excute = new db_execute($myform->generate_insert_SQL());
            unset($db_excute);
            echo 'Thêm thành công!';
            redirect($fs_redirect);
        }


    }
}

$types = [
    'CA'
];

echo $blade->view()->make('add_banner', get_defined_vars())->render();
?>