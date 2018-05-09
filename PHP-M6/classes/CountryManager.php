<?php

class CountryManager 
{
    /**
     * Made a call to class CountryManagerDB method getAll to get all
 	 * records of country ID and country name from the database.
 	 */
	public static function getAll() 
	{
		return CountryManagerDB::getAll();
	}

    /**
     * Made a call to class CountryManagerDB method getCountryByID to get 
 	 * country name based on its country ID
 	 */
	public static function getCountryByID($id) 
	{
		return CountryManagerDB::getCountryByID($id);
	}
}

?>