<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyễn <ntdinh1987@gmail.com>
 * Date: 4/6/20
 * Time: 17:48
 */

require_once 'inc_security.php';

$province_id = getValue('province_id', 'int', 'GET', 0);
$response = model('province/get_district_by_province_id')->load(['province_id' => $province_id]);
$html = '<option>Chọn quận / huyện</option>';
foreach ($response['vars'] as $district) {
    $html .= '<option value="' . $district['id'] . '">' . $district['name'] . '</option>';
}

echo $html;
die;