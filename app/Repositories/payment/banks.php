<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/19/19
 * Time: 23:23
 */

$vars = [];

$client = new GuzzleHttp\Client(['timeout' => 20.0]);
$options['query']['jwt'] = \AppView\Helpers\BaoKimAPI::getToken();

$response = $client->request("GET", "https://api.baokim.vn/payment/api/v4/bpm/list", $options);
$response = json_decode($response->getBody()->getContents(), true);

foreach ($response['data'] as $key => &$bank) {
    if ($bank['id'] == 0 || $bank['id'] == 128) {
        unset($bank);
        unset($response['data'][$key]);
    }
}

//dd($response['data']);
return [
    'vars' => [
        'banks' => array_values($response['data']),
    ]
];