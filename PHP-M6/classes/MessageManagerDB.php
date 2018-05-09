<?php

include_once "util/db.php";
date_default_timezone_set('Asia/Singapore');

class MessageManagerDB
{
    public static function getLastMsgThread() 
    {
        $db = connectPDO();
        try {
            $sql  = "Select max(msgThreadID) from CP_TB_MESSAGE";
            $stmt = $db->prepare($sql);        
            $stmt->execute();
            $result = $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        var_dump($result);
        return $result;
    }


	/**
     * Insert new message into database 
     */
	public static function createMessage($subject, $msgContent, $filepath, $recipientID, $senderID, $msgThreadID)
	{
		$db = connectPDO();
 		try {
 			$sql  = "Insert into cp_tb_message (msgThreadID, subject, msgContent, filepath, recipientID, createdBy) " .
 					" values (:msgThreadID, :subject, :msgContent, :filepath, :recipientID, :createdBy)";
 			$stmt = $db->prepare($sql);
 			$stmt->bindParam(':msgThreadID', $msgThreadID);
 			$stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':msgContent', $msgContent);
            $stmt->bindParam(':filepath', $filepath);
            $stmt->bindParam(':recipientID', $recipientID);
            $stmt->bindParam(':createdBy', $senderID); 			
 			$stmt->execute();
 		} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br/>";
    		die();
		}
 		$db = NULL;
	}

	public static function viewAllMessages($userID)
	{
		$db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_MSGINBOX(?)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $userID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result;
    }

    public static function viewMessageDetails($msgThreadID)
    {
        $db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_VIEWMESSAGEDETAILS(?)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $msgThreadID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result;
    }

}