<?php

class IndustryManager 
{
    /**
     * Made a call to class IndustryManagerDB method getAll to get all
 	 * records of industry ID and industry name from the database.
 	 */
	public static function getAll() 
	{
		return IndustryManagerDB::getAll();
	}

    /**
     * Made a call to class IndustryManagerDB method getIndustryByID to get
 	 * industry name based on its industry ID.
 	 */
	public static function getIndustryByID($id) 
	{
		return IndustryManagerDB::getIndustryByID($id);
	}
}

?>