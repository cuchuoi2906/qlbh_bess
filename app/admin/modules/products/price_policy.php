<?

use App\Models\Product;

require_once("../../bootstrap.php");
require_once 'inc_security.php';

disable_debug_bar();

checkAddEdit("edit");
$fs_errorMsg = '';
$product_id = getValue('product_id', 'int', 'GET', 0);

$action = getValue('action', 'str', 'POST', '');
if ($action == 'add') {
    $price_add = getValue('price_add', 'int', 'POST', 0);
    $quantity_add = getValue('quantity_add', 'int', 'POST', 0);

    $product = Product::findByID($product_id);
    if ($product) {
        if ($price_add <= 0
            //|| $price_add > $product->commission
        ) {
            $fs_errorMsg = 'Chiết khấu phải lớn hơn 0 và nhỏ hơn ' . $product->commission . '% tổng chiết khẩu hoa hồng';
        } else {
            $id = \App\Models\ProductPricePolicy::insert([
                'ppp_product_id' => $product_id,
                'ppp_quantity' => $quantity_add,
                'ppp_price' => $price_add
            ]);
            if ($id) {
                redirect(url_back());
            }
        }
    } else {
        $fs_errorMsg = 'Sản phẩm không tồn tại';
    }

}

if ($action == 'edit') {
    $id = getValue('id', 'int', 'POST', 0);
    $quantity = getValue('quantity', 'int', 'POST');
    $price = getValue('price', 'int', 'POST');

    $product = Product::findByID($product_id);
    if ($product) {

        if ($price <= 0
            //|| $price > $product->commission
        ) {
            $fs_errorMsg = 'Chiết khấu phải lớn hơn 0 và nhỏ hơn ' . $product->commission . '% tổng chiết khẩu hoa hồng';
        } else {
            \App\Models\ProductPricePolicy::where('ppp_id', $id)
                ->update([
                    'ppp_quantity' => (int)$quantity,
                    'ppp_price' => (int)$price
                ]);
            redirect(url_back());
        }
    } else {
        $fs_errorMsg = 'Sản phẩm không tồn tại';
    }


}

//lay du lieu cua record can sua doi
$product = Product::findByID($product_id);
if (!$product) {
    exit();
}

echo $blade->view()->make('iframe_price_policy', get_defined_vars())->render();
return;
?>