<?
require_once 'inc_security.php';

//checkAddEdit("add");

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

// Lấy giá trị từ POST
foreach ($locales as $code => $locale):
    ${'cat_name_' . $code} = getValue('cat_name_' . $code, 'str', 'POST', '');
    $myform->add('cat_name_' . $code, 'cat_name_' . $code, 0, 1, '', 1, 'Bạn chưa hoàn thiện tên danh mục');

    ${'cat_description_' . $code} = getValue('cat_description_' . $code, 'str', 'POST', '');
    $myform->add('cat_description_' . $code, 'cat_description_' . $code, 0, 1, '');
endforeach;

$cat_parent_id = getValue('cat_parent_id', 'int', 'POST', 0);
$cat_type = getValue('cat_type', 'str', 'POST', '');
$cat_rewrite = getValue('cat_rewrite', 'str', 'POST', '');
$cat_rewrite = $cat_rewrite ? $cat_rewrite : removeTitle($cat_name_vn);
$cat_rewrite = removeTitle($cat_name_vn);
//$cat_url = getValue('cat_url', 'str', 'POST', '');
//$cat_show = getValue('cat_show', 'int', 'POST', 1);
$cat_seo_title = getValue('cat_seo_title', 'str', 'POST', '');
$cat_seo_keyword = getValue('cat_seo_keyword', 'str', 'POST', '');
$cat_seo_description = getValue('cat_seo_description', 'str', 'POST', '');
//$cat_teaser = getValue('cat_teaser', 'str', 'POST', '');

$cat_order = getValue('cat_order', 'int', 'POST', 0);
$cat_active = getValue('cat_active', 'int', 'POST', 1);

//Call Class generate_form();

$myform->add('cat_type', 'type', 0, 1, $type ?? '');

$myform->add('cat_parent_id', 'cat_parent_id', 1, 1, 0);
//$myform->add('cat_type', 'cat_type', 0, 1, '', 1, 'Chưa chọn loại danh mục');
//$myform->add('cat_has_child', 'cat_has_child', 1, 1, 0);
$myform->add('cat_active', 'cat_active', 1, 1, 0);
$myform->add('cat_order', 'cat_order', 1, 1, 0);
$myform->add('cat_rewrite', 'cat_rewrite', 0, 1, '');
$myform->add('cat_seo_title', 'cat_seo_title', 0, 1, '');
$myform->add('cat_seo_keyword', 'cat_seo_keyword', 0, 1, '');
$myform->add('cat_seo_description', 'cat_seo_description', 0, 1, '');

$myform->addTable('category_news');

$myform->evaluate();


$action = getValue("action", "str", "POST", "");
if ($action == "execute") {
    $fs_errorMsg .= $myform->checkdata();
    $file_name = '';
//    $upload = new upload('cat_icon', $fs_filepath, $fs_extension, $fs_filesize);
    if (isset($_FILES['cat_icon']['tmp_name']) && $_FILES['cat_icon']['tmp_name'] != '') {
        $upload = new upload('cat_icon', $fs_filepath, $fs_extension, $fs_filesize);
        if ($upload->common_error == '') {
            $file_name = $upload->file_name;
            $myform->add('cat_icon', 'file_name', 0, 1, '');
        } else {
            $fs_errorMsg = $upload->common_error;
        }
    }
//    else {
//        $fs_errorMsg .= 'Bạn chưa hoàn thiện ảnh đại diện của danh mục';
//    }

    if ($fs_errorMsg == '') {
        $db_excute = new db_execute($myform->generate_insert_SQL());
        unset($db_excute);
        \AppView\Helpers\Facades\FlashMessage::success('Thêm mới thành công', url_back());
    }
}
$layout = 'add';
if($type == 'BRAND')
{
    $layout = 'brand_add';
}
echo $blade->view()->make($layout, get_defined_vars())->render();
?>