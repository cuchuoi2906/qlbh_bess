<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 8/22/19
 * Time: 23:15
 */
require_once 'inc_security.php';

define('IS_API_CALL', true);

$id = getValue('id', 'int', 'POST', 0);
$before_id = getValue('before_id', 'int', 'POST', 0);
$after_id = getValue('after_id', 'int', 'POST', 0);

$item = \App\Models\Categories\Category::findByID($id);
$order = $item->order;

if ($before_id) {
    $before = \App\Models\Categories\Category::findByID($before_id);
    if ($before) {
        $before_order = $before->order;
    }
}
if ($after_id) {
    $after = \App\Models\Categories\Category::findByID($after_id);
    if ($after) {
        $after_order = $after->order;
    }
}

if (($before_order ?? false) && ($after_order ?? false)) {
    $order = ($before_order + $after_order) / 2;
}

if (($before_order ?? false) && !($after_order ?? false)) {
    $order = $before_order - 1;
}

if (!($before_order ?? false) && ($after_order ?? false)) {
    $order = $after_order + 1;
}

$order = ($order == 0) ? -1 : $order;

$item->order = $order;
$item->update();

echo $order;