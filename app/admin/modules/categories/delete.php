<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("delete");

$returnUrl = base64_decode(getValue("returnurl", "str", "GET", base64_encode("listing.php")));
$recordId = getValue("record_id", "int", "GET", 0);
if ($recordId) {
    $category = \App\Models\Categories\Category::findById($recordId);
    if ($category) {
//        $category->active = -1;
//        $category->update();
        $category->delete();
    }
}

redirect(url_back());
?>