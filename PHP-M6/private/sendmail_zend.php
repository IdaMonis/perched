<?php
require_once "../vendor/autoload.php";
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

$message = new Message();
$message->addTo('czephyr1@yahoo.com');
$message->addFrom('czephyr1@gmail.com');
$message->setSubject('Greetings and Salutations!');
$message->setBody("Sorry, I'm going to be late today!");

// Setup SMTP transport using LOGIN authentication
$transport = new SmtpTransport();
$options   = new SmtpOptions([
    'name'              => 'mailjet',
    'host'              => 'in-v3.mailjet.com',
    'connection_class'  => 'login',
    'connection_config' => [
        'username' => '895833ed5aaec5c37ca13f9047dabdb3',
        'password' => '9c5b4d6bfa7a45a93d0b0602dc556ee6',
    ],
]);
$transport->setOptions($options);
$transport->send($message);

?>
