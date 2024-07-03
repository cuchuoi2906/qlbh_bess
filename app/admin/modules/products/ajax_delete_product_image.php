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

    if ($image->is_avatar == 1) {
        $product_id = $image->product_id;

        $image_next = ProductImage::where('pri_product_id = ' . $product_id)
            ->order_by('pri_id', 'ASC')
            ->first();
        if ($image_next) {
            $image_next->is_avatar = 1;
            $image_next->update();
        }
    }
}

die;
?>