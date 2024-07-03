<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 3/12/19
 * Time: 10:46
 */

namespace AppView\Middlewares;


class BeforeLogRequestMiddleware
{

    public function __invoke()
    {
//        // TODO: Implement __invoke() method.
//        $handle = fopen(ROOT . '/ipstore/' . ($_SERVER['REQUEST_METHOD'] ?? 'GET') . str_replace('/', '_', $_SERVER['REDIRECT_URL']) . '-input.txt', 'a+');
//        if ($handle) {
//            $data = '------------------------------------------' . PHP_EOL;
//            $data .= current_url() . PHP_EOL;
//            $data .= file_get_contents('php://input') . PHP_EOL;
//            $data .= var_export($_SERVER, true) . PHP_EOL;
//            fwrite($handle, $data);
//            fclose($handle);
//        }
    }
}