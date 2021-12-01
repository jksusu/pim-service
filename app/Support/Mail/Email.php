<?php

declare(strict_types=1);

namespace App\Support\Mail;

use App\Exception\MailException;
use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public static function send(string $email, $conf = 'default')
    {
        try {
            if (!isset(config('smtp')[$conf])) {
                throw new \Exception('smtp config empty' . $conf);
            }
            $config = config('smtp')[$conf];
            $mail = new PHPMailer();

            //邮箱服务配置
            $mail->SMTPDebug = $config['smtp_debug'];
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = false;
            $mail->Username = $config['username'];
            $mail->Password = $config['password'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = $config['port'];

            $mail->setFrom($config['from_address'], $config['from_nickname']);
            $mail->addAddress($email);
            $mail->setLanguage('zh_cn');

            //Content
            $mail->isHTML(true);
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if (!$mail->send()) {
                throw new MailException('邮件发送失败:' . $email);
            }
        } catch (\Exception $e) {
            output($e->getMessage());
        }
    }
}