<?php
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

//Check user login...
checkLogged();
$module_id = 19;

//Check access module...
if (checkAccessModule($module_id) != 1) {
    redirect($fs_denypath);

}
$type = getValue('type', 'int', 'GET', 0);
if ($type == 1) {
    $status = \App\Models\Moneyloadbatch::$orderStatus;
} else {
    $status = \App\Models\Moneyloadbatch::$status;
}

$per_page = 10;

//$fs_fieldupload = "cat_picture";
$fs_extension = "gif,jpg,jpe,jpeg,png";
$fs_filesize = 1000;

$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);