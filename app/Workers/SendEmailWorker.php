<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen (ntdinh1987@gmail.com)
 * Date: 9/9/19
 * Time: 13:39
 */

namespace App\Workers;


use AppView\Helpers\SendGridEmailHelpers;

class SendEmailWorker
{

    public static $name = 'send_email';

    public function fire($data)
    {

        $this->call_api_send_email($data['email'], $data['name'], $data['title'], $data['content']);
    }

    public function call_api_send_email($to_email, $to_name, $subject, $content, $from_email = null, $from_name = null)
    {

        echo 'Send Email:' . PHP_EOL;
        echo 'To: ' . $to_email . ' ' . $to_name . PHP_EOL;
        echo $subject . PHP_EOL;
        echo $content . PHP_EOL;

        $response = SendGridEmailHelpers::send($subject, $to_email, $to_name, $content, $from_email, $from_name);

        if (!$response) {
            return false;
        } else {
            echo 'Thành công' . PHP_EOL;
            var_dump($response->statusCode());
            var_dump($response->body());
            var_dump($response->headers());

            return true;
        }

    }
}