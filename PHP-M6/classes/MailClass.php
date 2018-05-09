<?php
class MailClass
{
    function sendMail($subject,$sendto,$body,$targetpath = null)
    {
        try{

			// $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 'tls', 587);
			// $transport->setUsername('czephyr1@gmail.com');
			// $transport->setPassword('LovetheLord');

			$transport = (new Swift_SmtpTransport('in-v3.mailjet.com', 25))
  			->setUsername('895833ed5aaec5c37ca13f9047dabdb3')
  			->setPassword('9c5b4d6bfa7a45a93d0b0602dc556ee6')
			;

			// Create the Mailer using your created Transport
			$mailer = new Swift_Mailer($transport);

			// Create a message
			$message = (new Swift_Message($subject))
  				->setFrom(['czephyr1@gmail.com' => 'CW'])
  				->setTo($sendto)
  				->setBody($body)
  				;

			if(!empty($targetpath)){
				$message->attach(Swift_Attachment::fromPath($targetpath));
			}
			// $mailer = Swift_Mailer::newInstance($transport);
			$result = $mailer->send($message);
    		if ($result) {
        		echo "Number of emails sent: $result";
    		} else {
        		echo "Couldn't send email";
        	}
        }
        catch (Exception $e) {
    		echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
    }
}