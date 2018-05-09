<?php

require_once("util/db.php");

class IndustryManagerDB 
{
    /**
     * Get all records of industry ID and industry name from the database
     *
     * @return array with list of all industry IDs and their corresponding industry names
     */
	public static function getAll() 
    {
		$db       = connectPDO();
        $industry = null;
        try {
            $sql  = "select * from cp_tb_industry";
            $stmt = $db->prepare($sql);
 		    $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $industry = $stmt->fetchall(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        $db = null;
        
        return $industry;
	}

    /**
     * Get industry name based on its industry ID
     *
     * @return array of size 1 containing industry name
     */
    public static function getIndustryByID($id) 
    {
        $db = connectPDO();
        $industryName = null;
        try {
            $sql = "select industryTypeName from cp_tb_industry where industryID = :industryID";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':industryID', $id);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $industryName = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        $db = null;
        
        return $industryName;
    }
}

?>