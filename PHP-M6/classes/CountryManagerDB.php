<?php

include_once("util/db.php");

class CountryManagerDB 
{
   /**
    * Get all records of country ID and country name from the database
    *
    * @return array with list of all country IDs and their corresponding country names
    */
	public static function getAll() 
    {
		$db      = connectPDO();
        $country = null;
        $sql     = "select * from cp_tb_country";
        $stmt    = $db->prepare($sql);
 		$stmt->execute();
        if ($stmt->rowCount() > 0) {
            $country = $stmt->fetchall(PDO::FETCH_ASSOC);
        }
        $db = null;
        
        return $country;
	}

   /**
    * Get country name based on its country ID
    *
    * @return array of size 1 containing country name
    */
    public static function getCountryByID($id) 
    {
        $db   = connectPDO();
        $countryName = null;
        $sql  = "select CountryName from cp_tb_country where CountryID = :countryID";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':countryID', $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $countryName = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $db = null;
        
        return $countryName;
    }
}

?>