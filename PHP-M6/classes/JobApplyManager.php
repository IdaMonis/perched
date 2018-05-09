<?php

require_once "JobApply.php";

class JobApplyManager
{

	public static function createJobApplication($jobID, $message, $attachment, $appliedBy)
	{
		return JobApplyManagerDB::createJobApplication($jobID, $message, $attachment, $appliedBy);
	}

	public static function viewJobApplicantsByJobID($jobID)
	{
		return JobApplyManagerDB::viewJobApplicantsByJobID($jobID);
	}

	public static function viewJobApplicantsByPostedID($postedID)
	{
		return JobApplyManagerDB::viewJobApplicantsByPostedID($postedID);
	}

	public static function viewOwnJobApplication($jobID, $appliedBy)
	{
		return JobApplyManagerDB::viewOwnJobApplication($jobID, $appliedBy);
	}

	public static function viewJobApplicants($jobID, $userID)
	{
		return JobApplyManagerDB::viewJobApplicants($jobID);
	}

}