<?php

include_once "util/db.php";
date_default_timezone_set('Asia/Singapore');

class ThreadReplyManagerDB
{

	/**
     * Insert new thread query into database with parameters 
     */
	public static function createThreadMsg($threadID, $threadMsgContent, $createdBy) 
	{
        $last_id = NULL;
		$db = connectPDO();
 		try {
 			$sql  = "Insert into cp_tb_threadmsg (threadID, threadMsgContent, createdBy) " .
 					" values (:threadID, :threadMsgContent, :createdBy)";
 			$stmt = $db->prepare($sql);
 			$stmt->bindParam(':threadID', $threadID);
 			$stmt->bindParam(':threadMsgContent', $threadMsgContent);
 			$stmt->bindParam(':createdBy', $createdBy); 			
 			$stmt->execute();
            $last_id = $db->lastInsertId();
 		} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br/>";
    		die();
		}
 		$db = NULL;
        return $last_id;
	}

    public static function createThreadAttach($threadMsgID, $filepath, $createdBy)
    {
        $db = connectPDO();
        try {
            $sql  = "Insert into cp_tb_threadattach (threadMsgID, filepath, createdBy) " .
                    " values (:threadMsgID, :filepath, :createdBy)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':threadMsgID', $threadMsgID);
            $stmt->bindParam(':filepath', $filepath);
            $stmt->bindParam(':createdBy', $createdBy);             
            $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
    }

	public static function viewAllThreadReply($threadID)
	{
		$db = connectPDO();  
        try {
            $sql="CALL CP_PROC_THREADREPLY(?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $threadID, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result;
    }

    public static function viewThreadReplyToMe($userID)
    {
        $db = connectPDO();  
        try {
            $sql="CALL CP_PROC_THREADREPLYTOUSER(?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $userID, PDO::PARAM_STR);
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