<?php

include_once "util/db.php";
date_default_timezone_set('Asia/Singapore');

class JobApplyManagerDB
{

	/**
     * Insert new job application into database with parameters 
     */
	public static function createJobApplication($jobID, $message, $attachment, $appliedBy)
	{
		$db = connectPDO();
 		try {
 			$sql  = "CALL CP_PROC_APPLYJOB(?,?,?,?)";
 			$stmt = $db->prepare($sql);
 			$stmt->bindParam(1, $jobID, PDO::PARAM_INT);
 			$stmt->bindParam(2, $message, PDO::PARAM_STR);
 			$stmt->bindParam(3, $attachment, PDO::PARAM_STR);
            $stmt->bindParam(4, $appliedBy, PDO::PARAM_INT);
 			$stmt->execute();
 		} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br/>";
    		die();
		}
 		$db = NULL;
	}

    public static function viewJobApplicantsByJobID($jobID)
    {
        $db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_VIEWJOBAPPLICANTSBYJOBID(?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $jobID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result;
    }

    public static function viewJobApplicantsByPostedID($postedID)
    {
        $db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_VIEWJOBAPPLICANTSBYPOSTEDID(?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $postedID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result;
    }

    public static function viewOwnJobApplication($jobID, $appliedBy)
    {
        $db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_VIEWOWNJOBAPPLICATION(?,?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $jobID, PDO::PARAM_INT);
            $stmt->bindParam(2, $appliedBy, PDO::PARAM_INT);
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