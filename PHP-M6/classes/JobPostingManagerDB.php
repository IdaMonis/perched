<?php

include_once "util/db.php";
date_default_timezone_set('Asia/Singapore');

class JobPostingManagerDB
{

	/**
     * Insert new thread query into database with parameters 
     */
	public static function createJobPosting($jobPosition, $description, $salary, $closingDate, $postedBy) 
	{
		$db = connectPDO();
 		try {
 			$sql  = "Insert into cp_tb_job (jobPosition, description, salary, closingDate, postedBy) " .
 					" values (:jobPosition, :description, :salary, :closingDate, :postedBy)";
 			$stmt = $db->prepare($sql);
 			$stmt->bindParam(':jobPosition', $jobPosition);
 			$stmt->bindParam(':description', $description);
            $stmt->bindParam(':salary', $salary);
            $stmt->bindParam(':closingDate', $closingDate);
            $stmt->bindParam(':postedBy', $postedBy); 			
 			$stmt->execute();
 		} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br/>";
    		die();
		}
 		$db = NULL;
	}

	public static function viewAllJobPostingExceptMine($userID)
	{
		$db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_VIEWJOBPOSTINGSEXCEPT(?)"; 
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

    public static function viewJobPostingDetails($jobID)
    {
        $db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_VIEWJOBDETAILS(?)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $jobID, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result;
    }

}