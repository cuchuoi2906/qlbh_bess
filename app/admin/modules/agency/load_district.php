<?php
/**
 * Created by PhpStorm.
 * User: Truong
 * Date: 09/01/2019
 * Time: 3:26 CH
 */

require_once("../../bootstrap.php");
require_once 'inc_security.php';

//check quyền them sua xoa
checkAddEdit("edit");
$form = new form();

$city_id = getValue('city_id', 'int', 'POST', 0);
$district_id = getValue('district_id', 'int', 'POST', 0);

$district = \App\Models\Cities::where('cit_parent_id', '=', $city_id)->select_all();
$district = $district->lists('cit_id', 'cit_name');

echo $form->select("Chọn quận\huyên", "agc_district_id", "agc_district_id", $district, ($district_id > 0) ? $district_id : '', '', "");
