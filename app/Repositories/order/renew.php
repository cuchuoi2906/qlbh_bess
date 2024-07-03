<?php

use App\Models\Order;
use App\Models\Users\UserCart;
use App\Models\Users\UserCartAdmin;
use App\Models\Users\Users;
use App\Transformers\ProductTransformer;

$vars = true;

$user = Users::findByID(input('user_id'));
if (empty($user)) {
    throw new RuntimeException("Người dùng không tồn tại", 400);
}

$order_id = input('id');
$order = Order::where('ord_user_id', $user->id)
    ->findByID(input('id'));

if (!$order) {
    throw new RuntimeException('Đơn hàng không tồn tại', 404);
}
//Xóa đơn
$result_delete = repository('order/delete')->load([
    'user_id' => $user->id,
    'id' => $order_id,
    'note' => input('note'),
    'admin_id' => input('admin_id')
]);

$products = $order->products;
if ($products) {
    //Xóa giỏ hàng
    if (input('admin_id')) {
        UserCartAdmin::where('usc_user_id', $user->id)->delete();
    } else {
        UserCart::where('usc_user_id', $user->id)->delete();
    }
    foreach ($products as $product) {
        $result = model('user_cart/add')->post([
            'user_id' => (int)$user->id,
            'product_id' => (int)$product->product_id,
            'quantity' => (int)$product->quantity,
            'is_add_more' => false,
            'admin_id' => input('admin_id')
        ]);
    }
}

return model('user_cart/index')->get([
    'user_id' => $user->id
]);
