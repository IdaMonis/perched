<?php

// include_once "ThreadReply.php";

class ThreadReplyManager
{

	public static function createThread($threadID, $threadMsgContent, $filepath, $createdBy)
	{
		$threadReplyID = ThreadReplyManagerDB::createThreadMsg($threadID, $threadMsgContent, $createdBy);
		if($filepath) {
			if ($threadReplyID) {
				return ThreadReplyManagerDB::createThreadAttach($threadReplyID, $filepath, $createdBy);
			}
		}
	}

	public static function viewAllThreadReply($threadID)
	{
		return ThreadReplyManagerDB::viewAllThreadReply($threadID);
	}

	public static function viewThreadReplyToMe($userID)
	{
		return ThreadReplyManagerDB::viewThreadReplyToMe($userID);
	}

}