<?php
/**
 * PHP Version 5
 * Utilitario de Envio de Correo
 *
 * @category Utilities_Mail
 * @package  Utilites\Mail
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  Comercial http://
 *
 * @version 1.0.0
 *
 * @link http://url.com
 */

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/Mail/Exception.php';
require 'libs/Mail/PHPMailer.php';
require 'libs/Mail/SMTP.php';
//Load Composer's autoloader
//require 'vendor/autoload.php';

require_once 'libs/parameters.php';

/**
 * Envia un correo electrónico
 *
 * @param string $to      Dirección de Correo al que se envia
 * @param string $subjet  Asunto del Correo
 * @param string $message Mensaje del Correo
 *
 * @return boolean
 */
function sendemail($to, $subjet, $message)
{
    global $emailHost;
    global $smtpUser;
    global $smtpSecret;
    global $smtpPort;
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    try {
        //Server settings
        $mail->CharSet = 'UTF-8';
        $mail->SMTPKeepAlive = true;
        $mail->Mailer = "smtp";
        $mail->SMTPDebug = 2;                                 // Enable verbose debug output
        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $emailHost;   // Specify main and backup SMTP servers
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $smtpUser;             // SMTP username
        $mail->Password = $smtpSecret;
                                                              // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $smtpPort;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('noreply@mvc.biz', 'Servicio');
        // $mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
        $mail->addAddress($to);               // Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        //Content
        $mail->isHTML(true);                                   // Set email format to HTML
        $mail->Subject = $subjet;
        $mail->Body    = $message;
        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        $mail->send();
        //echo 'Message has been sent';
        return true;
    } catch (Exception $e) {
        error_log('Message could not be sent. Mailer Error: '. $mail->ErrorInfo);
        return false;
    }
}
?>
