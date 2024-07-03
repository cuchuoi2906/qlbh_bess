<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/15/19
 * Time: 02:07
 */
$data['use_active'] = -99; 
\App\Models\Users\Users::update($data, 'use_id = ' . (int)input('id'));

return [
    'vars' => true
];