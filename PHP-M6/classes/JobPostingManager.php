<?php

require_once "JobPosting.php";

class JobPostingManager
{

	public static function createJobPosting($jobPosition, $description, $salary, $closingDate, $postedBy) 
	{
		return JobPostingManagerDB::createJobPosting($jobPosition, $description, $salary, $closingDate, $postedBy);
	}

	public static function viewAllJobPostingExceptMine($userID)
	{
		return JobPostingManagerDB::viewAllJobPostingExceptMine($userID);
	}

	public static function viewJobPostingDetails($jobID)
	{
		return JobPostingManagerDB::viewJobPostingDetails($jobID);
	}

}