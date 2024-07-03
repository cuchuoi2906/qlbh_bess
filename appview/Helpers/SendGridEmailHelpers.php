<?php
/**
 * Created by PhpStorm.
 * User: Stephen Nguyen
 * Date: 4/12/19
 * Time: 09:46
 */

namespace AppView\Helpers;


use PHPMailer\PHPMailer\PHPMailer;

class SendGridEmailHelpers
{

    public static function send($title, $to_email, $to_name, $content, $from_email = null, $from_name = null)
    {

        $from_email = $from_email ?? setting('support_email');
        $from_name = $from_name ?? setting('support_email_name');

        $mail = new PHPMailer(true);// Passing `true` enables exceptions
        try {

            //Server settings
            $mail->SMTPDebug = config('app.debug') ? 2 : 0;// Enable verbose debug output
//            $mail->SMTPDebug = 2;// Enable verbose debug output
            $mail->isSMTP();// Set mailer to use SMTP
            $mail->Host = 'in-v3.mailjet.com';  // Specify main and backup SMTP servers
//            $mail->Host = '103.157.218.141';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;// Enable SMTP authentication
            $mail->Username = 'c7a4eaddc3ef1b5680441efd81155177';// SMTP username
//            $mail->Password = $account['password'];// SMTP password
            $mail->Password = '5f3a6b225ede2e518a153200028ec38d';// SMTP password

            $mail->SMTPSecure = 'tls';// Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;
            $mail->CharSet = "UTF-8";  // TCP port to connect to

            //Recipients
            $mail->setFrom($from_email, $from_name);

            $mail->addAddress($to_email, $to_name);     // Add a recipient

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $title;
            $mail->Body = $content;
            $mail->AltBody = $content;

            $mail->send();

        } catch (\PHPMailer\PHPMailer\Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
        }

        return;

        $response = false;

        $email = new \SendGrid\Mail\Mail();

        $from_email = $from_email ?? setting('support_email');
        $from_name = $from_name ?? setting('support_email_name');

        $email->setFrom($from_email, $from_name);
        $email->setSubject($title);
        $email->addTo($to_email, $to_name);
        $email->addContent(
            "text/html", $content
        );

        $sendgrid = new \SendGrid(config('app.send_grid_api_key'));

        try {
            $response = $sendgrid->send($email);
        } catch (\Exception $e) {
//            echo 'Caught exception: ' . $e->getMessage() . "\n";
        }

        return $response;
    }
}