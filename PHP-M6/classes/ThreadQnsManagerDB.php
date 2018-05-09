<?php

include_once "util/db.php";
date_default_timezone_set('Asia/Singapore');

class ThreadQnsManagerDB
{

	/**
     * Insert new thread query into database with parameters 
     */
	public static function createThreadQns($threadTitle, $threadMsgContent, $createdBy) 
	{
		$db = connectPDO();
 		try {
 			$sql  = "Insert into cp_tb_thread (threadTitle, threadMsgContent, createdBy) " .
 					" values (:threadTitle, :threadMsgContent, :createdBy)";
 			$stmt = $db->prepare($sql);
 			$stmt->bindParam(':threadTitle', $threadTitle);
 			$stmt->bindParam(':threadMsgContent', $threadMsgContent);
 			$stmt->bindParam(':createdBy', $createdBy); 			
 			$stmt->execute();
 		} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br/>";
    		die();
		}
 		$db = NULL;
	}

	public static function viewAllThreadQns()
	{
		$db = connectPDO();  
        try {
            $sql  = "SELECT * FROM CP_VIEW_THREAD"; 
            $stmt = $db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        //var_dump($result);
        return $result;
    }

    public static function viewThread($threadID)
    {
        $db = connectPDO();  
        try {
            $sql  = "CALL CP_PROC_THREADQNS(?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $threadID, PDO::PARAM_STR);
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