<?php

require_once "ThreadQns.php";

class ThreadQnsManager
{

	public static function createThreadQns($threadTitle, $threadMsgContent, $createdBy) 
	{
		return ThreadQnsManagerDB::createThreadQns($threadTitle, $threadMsgContent, $createdBy);
	}

	public static function viewAllThreadQns()
	{
		return ThreadQnsManagerDB::viewAllThreadQns();
	}

	public static function viewThread($threadID)
	{
		return ThreadQnsManagerDB::viewThread($threadID);
	}

}