<?php
header("Access-Control-Allow-Origin: *");
require_once dirname(__FILE__) . '/../bootstrap/bootstrap.php';

// TODO: Implement __invoke() method.
$handle = fopen(ROOT . '/ipstore/' . ($_SERVER['REQUEST_METHOD'] ?? 'GET') . str_replace('/', '_', $_SERVER['REDIRECT_URL']) . '-input.txt', 'a+');
if ($handle) {
    $data = '------------------------------------------' . PHP_EOL;
    $data .= current_url() . PHP_EOL;
    $data .= file_get_contents('php://input') . PHP_EOL;
    $data .= var_export($_SERVER, true) . PHP_EOL;
    fwrite($handle, $data);
    fclose($handle);
}

$app->run();