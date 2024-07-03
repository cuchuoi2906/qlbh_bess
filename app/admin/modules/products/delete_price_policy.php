<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 8/15/19
 * Time: 23:16
 */

require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("delete");

$recordId = getValue("id", "int", "GET", 0);
if ($recordId) {
    $item = \App\Models\ProductPricePolicy::findById($recordId);
    if ($item) {
        $item->delete();

        admin_log($admin_id, ADMIN_LOG_ACTION_DELETE, $recordId, 'Đã xóa chính sách giá số lượng ' . $item->quantity . ' (giá ' . number_format($item->price) . ') của sản phẩm ' . $item->product->name . '(' . $item->product->id . ')');
    }
}

redirect(url_back());