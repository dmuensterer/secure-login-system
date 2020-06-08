<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    require 'mail/Exception.php';
    require 'mail/PHPMailer.php';

    $mail = new PHPMailer(true);
    try {

        $mail->CharSet ="UTF-8";

        $mail->setFrom('tettnang@kauft-online.de', 'Tettnang Kauft Online');
        $mail->addAddress('dominik@muensterer.net');     // Add a recipient
        $mail->addReplyTo('tettnang@kauft-online.de', 'Tettnang Kauft Online');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Passwort zurücksetzen';
        $mail->Body    = 'Hallo,<br> bitte klicken Sie zum zurücksetzen Ihres Passworts auf folgenden Link: <a href=\"#\">Passwort zurücksetzen</a><br><br>Falls Sie Ihr Passwort nicht zurücksetzen wollten, können Sie diese E-Mail ignorieren.';
        $mail->AltBody = 'Hallo, bitte rufen Sie zum zurücksetzen Ihres Passworts folgenden Link im Browser auf: #';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }


?>