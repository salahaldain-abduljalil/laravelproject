<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if(!function_exists('sendEmail')){
    function sendEmail($mailConfig){
         require 'PHPMailer/src/Exception.php';
         require 'PHPMailer/src/PHPMailer.php';
        require 'PHPMailer/src/SMTP.php';
        $mail = PHPMailer(true);
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = env('EMAIL_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('EMAIL_USERNAME');
        $mail->password = env('EMAIL_PASSWORD');
        $mail->SMTPSecure = env('email_encryption');
        $mail->Port      = env('EMAIL_PORT');
        $mail->setForm ($mailConfig['mail_from_email'],$mailConfig['mail_from_name']);
        $mail->addAddress($mailConfig['mail_recipient_email'],$mailConfig['mail_recipient_name']);
        $mail->isHTML(true);
        $mail->Subject = $mailConfig['mail_subject'];
        $mail->Body = $mailConfig['email_body'];
        if($mail->send){
            return true;
        }else{
            return false;
        }

    }
}

?>
