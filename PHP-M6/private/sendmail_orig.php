<?php
require_once "../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer();
//Enable SMTP debugging.
$mail->SMTPDebug = 3;
//Set PHPMailer to use SMTP.
$mail->isSMTP();
//Set SMTP host name
$mail->Host = "in-v3.mailjet.com";
//Set this to true if SMTP host requires authentication
$mail->SMTPAuth = true;
//Provide username and password
$mail->Username = "895833ed5aaec5c37ca13f9047dabdb3";
$mail->Password = "9c5b4d6bfa7a45a93d0b0602dc556ee6";
//If SMTP requires TLS encryption then set it
$mail->SMTPSecure = "tls";
//Set TCP port to connect to
$mail->Port = 587;
// $mail->Port = 25;
$mail->From = "czephyr1@gmail.com";
$mail->FromName = "Chingwei";
$mail->addAddress("czephyr1@yahoo.com", "Chingwei");
$mail->isHTML(true);
$mail->Subject = "Subject Text";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";
if(!$mail->send())
{
    echo "Mailer Error: " . $mail->ErrorInfo;
}
else
{
    echo "Message has been sent successfully";
}
?>
