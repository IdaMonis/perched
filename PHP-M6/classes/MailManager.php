<?php

// require_once "User.php";

class MailManager
{
    /**
     * Get all users' email addresses from the database
     */
	public static function getAllUserMailAddress()
	{
 		return MailManagerDB::getAllUserMailAddress();
 		
 	}

 	/**
     * Save a copy of sent mail to database
     */
	public static function mailSent($senderID, $recipient_email, $subject, $body, $filename)
	{
 		MailManagerDB::mailSent($senderID, $recipient_email, $subject, $body, $filename);
 		
 	}

 }
?>