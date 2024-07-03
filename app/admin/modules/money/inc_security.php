<?
require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

$module_id = 24;
$module_name = "Money";
//Check user login...
checkLogged();
//Check access module...
if (checkAccessModule($module_id) != 1)
    redirect($fs_denypath);

$page_size = 10;

$wallet_type = [
    0 => 'Tài khoản tiền nạp',
    1 => 'Tài khoản hoa hồng',
];

$views = [
    dirname(__FILE__) . '/views',
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/ipstore';
$blade = new \Philo\Blade\Blade($views, $cache);
