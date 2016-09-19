<?php

/**
 * Created by PhpStorm.
 * User: vladimirvahrusev
 * Date: 19.09.16
 * Time: 12:13
 */
class Mail
{
    public function sentMail()
    {
        require 'vendor/phpmailer/phpmailer/PHPMailerAutoload.php';

        $mail = new PHPMailer;

        //$mail->SMTPDebug = 3;                               // Enable verbose debug output

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com;';  // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'lofttoloft@gmail.com';                 // SMTP username
        $mail->Password = '1234LOFT';                           // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 587;                                    // TCP port to connect to

        $mail->setFrom('lofttoloft@gmail.com', 'Mailer');
        $mail->addAddress($_POST['email']);               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
//        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Спасиибо за регистрацию';
        $mail->Body    = 'Регистрация прошла успешно <b>Ура!</b>';
        $mail->AltBody = 'Регистарция прошла успешно';

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
            echo 'Message has been sent';
        }

    }

}