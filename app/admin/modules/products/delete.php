<?

use App\Models\Product;

require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("delete");
$returnUrl = base64_decode(getValue("returnurl", "str", "GET", base64_encode("listing.php")));
$recordId = getValue("record_id", "int", "GET", 0);
if ($recordId) {
    $item = Product::findById($recordId);
    if ($item) {
        $item->delete();
    }
}

redirect(url_back());
?>