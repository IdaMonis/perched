<?php

require_once "User.php";

class UserManager
{
    /**
     * Compare input password with the password retrieved from the database
     *
     * The input password is hashed before comparison is made with the hashed password stored in the DB
     *
     * @return boolean true if input password is the same as what is stored in the DB
     */
	public static function comparePassword($input_password, $db_password)
	{
 		$password = self::hashPassword($input_password);
 		
 		return $password == $db_password;
 	}

    /**
     * Hash a given password
     *
     * @return string of hashed password using MD5 encryption
     */
 	public static function hashPassword($password)
 	{
 		return hash("md5", $password);
 	}

    /**
     * Sign in with an email and a password
     *
     * The input email and password will be used to check for different criterias which are
     * then returned as status.
     *
     * @return mixed[] of user ID and the status. $status = 0 indicates succesful login (with complete profile)
     *                                            $status = 1 indicates user is blocked
     *                                            $status = 2 indicates user is valid but password is wrongly entered
     *                                            $status = 3 indicates user has not registered
     *                                            $status = 4 indicates user is an administrator
     *                                            $status = 5 indicates succesful login (with incomplete profile)     
     */
 	public static function signin($email, $password)
 	{
 		$user = UserManagerDB::getUserByEmail($email);

 		if ($user) {
 			if ($user->userDisabled) {
 				$status = 1; 
 			} else {

 				if (UserManager::comparePassword($password, $user->password)) {
 					if ($user->userTypeID == 1) { //user is an administrator
 						$status = 4;  
 					} else {
                        if (empty($user->dob) 
                            || empty($user->countryOfResidence)      
                            || empty($user->jobPosition) 
                            || empty($user->company) 
                            || empty($user->industryID)
                        ) {
                            $status = 5;
                        } else {
                            $status = 0;
                        }
 					}
 				} else {
 					$status = 2; 
 				}
 			}

 		} else { 
 			$status = 3;
 		}

        if ($status == 0 || $status == 4) {
            //Update Session variables
            self::updateSession((array) $user);
        }

 		return $status;
 	}

    /**
     * Register a new user into the database.
     *
     * The password will be hashed and a call made UserManagerDB class method createUser
     * to insert data into the DB.
     *
     * @return mixed[] array containing all columns of the newly inserted record of user.
     */
 	public static function register($fullname, $email, $password)
 	{
 		$hashed_password = UserManager::hashPassword($password);
 		UserManagerDB::createUser($fullname, $email, $hashed_password); 		
 		return UserManagerDB::getUserByEmail($email);
 	}

    /**
     * Made a call to class UserManagerDB method saveProfile to save user's profile into the database.
     * Update session variables with new user data.
     */
	public static function saveProfile($id, $profile)
 	{
        UserManagerDB::saveProfile($id, $profile);
        self::updateSession($profile);
 	}

    /**
     * Made a call to class UserManagerDB method getUserByEmail to get all 
     * record of user with user's email.
     */
 	public static function getUserByEmail($email)
 	{
 		return UserManagerDB::getUserByEmail($email);
 	}

   /**
     * Update Session variables
     *
     * @param mixed[] array of user data
     */
    public static function updateSession($user)
    {
        foreach($user as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Made a call to class UserManagerDB method searchUsers to search for users
     * with input keyword (exclude ownself from search).
     */
 	public static function searchUsers($keywords, $except_id)
 	{
 		return UserManagerDB::searchUsers($keywords, $except_id);
 	}

    /**
     * Made a call to class UserManagerDB method viewPublicProfile to get user's 
     * public details based on user ID given.
     */
 	public static function viewPublicProfile($id)
 	{
 		return UserManagerDB::viewPublicProfile($id);
 	}

    /**
     * Made a call to class UserManagerDB method viewPastJobs to get all 
     * past job experiences of user based on user ID.
     */
 	public static function viewPastJobs($id)
 	{
 		return UserManagerDB::viewPastJobs($id);
 	}

}

?>