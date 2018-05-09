<?php

require_once "Message.php";

class MessageManager
{
	public static function createMessage($subject, $msgContent, $filepath, $recipientID, $senderID, $msgThreadID)
	{	
    	// Check if message to be created is new
		if(empty($msgThreadID)) {
			// Get last msgThreadID
			$lastMsgThreadID = MessageManagerDB::getLastMsgThread();
			$nextMsgThreadID = $lastMsgThreadID + 1;
			return MessageManagerDB::createMessage($subject, $msgContent, $filepath, $recipientID, $senderID, $nextMsgThreadID);
		} else {
			return MessageManagerDB::createMessage($subject, $msgContent, $filepath, $recipientID, $senderID, $msgThreadID);
		}
	}

	public static function viewAllMessages($userID)
	{
		return MessageManagerDB::viewAllMessages($userID);
	}

	public static function viewMessageDetails($msgThreadID)
	{
		return MessageManagerDB::viewMessageDetails($msgThreadID);
	}

}