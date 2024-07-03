<?php

use App\Models\Post;

require_once("../../bootstrap.php");
require_once("../../resource/security/security.php");

$model = Registration::class;

$per_page = 10;

$views = [

    //Chứa view module
    dirname(__FILE__) . '/views',

    //Chứa view master
    realpath(dirname(__FILE__) . '/../../resource/views')
];
$cache = ROOT . '/storage/framework/views/';
$blade = new \Philo\Blade\Blade($views, $cache);