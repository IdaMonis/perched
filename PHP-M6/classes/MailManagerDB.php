<?php

include_once "util/db.php";
date_default_timezone_set('Asia/Singapore');

class MailManagerDB 
{

    /**
     * Insert new user into database with details fullname, email and hashed password
     */
	public static function getAllUserMailAddress()
    {
        $mail_array = null;
		$db = connectPDO();
 		try {
 			$sql  = "SELECT fullname, email from cp_tb_user WHERE userTypeID = 0 AND userDisabled = 0";
 			$stmt = $db->prepare($sql);
            $stmt->execute();
            // $stmt->setFetchMode(PDO::FETCH_INTO, $mail_obj);
            $mail_array = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = null;

        return $mail_array;
	}

    /**
     * Save a copy of sent mail to database
     */
    public static function mailSent($senderID, $recipient_email, $subject, $body, $filename)
    {
        $db = connectPDO();
        try {
            $sql  = "Insert into cp_tb_mailsent (subject, mailContent, filename, recipientEmail, senderID)" .
                    " values (:subject, :mailContent, :filename, :recipientEmail, :senderID)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':mailContent', $body);
            $stmt->bindParam(':filename', $filename);
            $stmt->bindParam(':recipientEmail', $recipient_email);
            $stmt->bindParam(':senderID', $senderID);          
            $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        
    }
}
?>