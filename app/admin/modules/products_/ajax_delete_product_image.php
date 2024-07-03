<?

use App\Models\ProductImage;

require_once("../../bootstrap.php");
require_once 'inc_security.php';
//check quyền them sua xoa
checkAddEdit("delete");

$record_id = getValue("image_id", "int", "POST", "0");

$image = ProductImage::findByID($record_id);
if ($image) {
    $image->delete();
}
die;
?>