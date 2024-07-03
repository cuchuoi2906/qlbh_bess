<?
require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("delete");

$returnUrl = base64_decode(getValue("returnurl", "str", "GET", base64_encode("listing.php")));
$recordId = getValue("record_id", "int", "GET", 0);
if ($recordId) {
    $user = \App\Models\Users\Users::findByID($recordId);
    if ($user) {
        $user->delete();
    }
}

redirect(url_back());
?>