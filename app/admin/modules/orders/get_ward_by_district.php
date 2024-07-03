<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/6/20
 * Time: 17:48
 */

require_once 'inc_security.php';

$province_id = getValue('district_id', 'int', 'GET', 0);
$response = model('province/get_ward_by_district_id')->load(['district_id' => $province_id]);
$html = '<option>Chọn phường / xã</option>';
foreach ($response['vars'] as $ward) {
    $html .= '<option value="' . $ward['id'] . '">' . $ward['name'] . '</option>';
}

echo $html;
die;