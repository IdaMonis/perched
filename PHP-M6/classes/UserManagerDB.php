<?php

include_once "util/db.php";
date_default_timezone_set('Asia/Singapore');

class UserManagerDB 
{

    /**
     * Insert new user into database with details fullname, email and hashed password
     */
	public static function createUser($fullname, $email, $h_password)
    {
		$db = connectPDO();
 		try {
 			$sql  = "Insert into cp_tb_user (fullname, email, password) values (:fullname, :email, :password)";
 			$stmt = $db->prepare($sql);
 			$stmt->bindParam(':fullname', $fullname);
 			$stmt->bindParam(':email', $email);
 			$stmt->bindParam(':password', $h_password); 			
 			$stmt->execute();
 		} catch (PDOException $e) {
    		print "Error!: " . $e->getMessage() . "<br/>";
    		die();
		}
 		$db = NULL;
	}

    /**
     * Get all record of user with user's email
     *
     * @return object of User.
     */
	public static function getUserByEmail($email)
    {
        $db   = connectPDO();
        $user_obj = new User;
        try {
            $sql="select * from cp_tb_user where email = :email";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':email', $email);
    	    $stmt->execute();
    	    //$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
            $stmt->setFetchMode(PDO::FETCH_INTO, $user_obj);
    	    $user = $stmt->fetch();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = null;

        return $user_obj;
    }

    /**
     * Save user's profile (fullname, dob, country of residence, job position, company and industry)
     * into the database.
     */
    public static function saveProfile($id, $profile)
    {
        $db = connectPDO();
        try {
            $sql = "UPDATE cp_tb_user SET " .
                   "fullName = :fullName, " .
                   "dob = :dob, " .
                   "countryOfResidence = :countryOfResidence, " .
                   "jobPosition = :jobPosition, " .
                   "company = :company, " .
                   "industryID = :industryID " .
                   "WHERE userID = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':fullName', $profile['fullname']);
            $stmt->bindParam(':dob', $profile['dob']);
            $stmt->bindParam(':countryOfResidence', $profile['countryOfResidence']);
            $stmt->bindParam(':jobPosition', $profile['jobPosition']);
            $stmt->bindParam(':company', $profile['company']);
            $stmt->bindParam(':industryID', $profile['industryID']);
            $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
    }

    /**
     * Search for users with input keyword (exclude ownself from search)
     *
     * @return mixed[] array of users who fulfil the search criterias.     
     */
    public static function searchUsers($keywords, $except_id)
    {
        $db     = connectPDO();  
        $result = null;
        try {
            $sql="call CP_PROC_SearchUser(?,?)";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $keywords, PDO::PARAM_STR);
            $stmt->bindParam(2, $except_id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchall();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result;
    }

    /**
     * Get user's public details based on user ID given
     *
     * @return mixed[] array of all public data of particular user     
     */
    public static function viewPublicProfile($id)
    {
        $db      = connectPDO();  
        $result2 = null;
        $i       = 0;
        try {
            $sql="call CP_PROC_ViewPublicProfile(?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchall();
            if ($stmt->rowCount() > 1) {
                foreach($result as $result) {
                    $result2['fullName'] = $result['fullName'];
                    $result2['email'] = $result['email'];
                    $result2['countryName'] = $result['countryName'];
                    $result2['jobPosition'] = $result['jobPosition'];
                    $result2['company'] = $result['company'];
                    $result2['industryTypeName'] = $result['industryTypeName'];
                    $result2['qualification'][$i]['qualification'] = $result['qualification'];
                    $result2['qualification'][$i]['institution'] = $result['institution'];
                    $i++;
                }
            } else {
                foreach($result as $result) {
                    $result2['fullName'] = $result['fullName'];
                    $result2['email'] = $result['email'];
                    $result2['countryName'] = $result['countryName'];
                    $result2['jobPosition'] = $result['jobPosition'];
                    $result2['company'] = $result['company'];
                    $result2['industryTypeName'] = $result['industryTypeName'];         
                    $result2['qualification'] = $result['qualification'];
                    $result2['qualification'] = $result['institution'];
                }
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result2;
    }

    /**
     * Get all past job experiences of user based on user ID
     *
     * @return mixed[] array of past job positions, past companies and dates of employment   
     */
    public static function viewPastJobs($id) 
    {
        $db      = connectPDO();  
        $result2 = null;
        $i       = 0;
        try {
            $sql="call CP_PROC_ViewPastJobs(?)"; 
            $stmt = $db->prepare($sql);
            $stmt->bindParam(1, $id, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetchall();
            foreach($result as $result) {
                $result2[$i]['pastJobPosition'] = $result['pastJobPosition'];
                $result2[$i]['pastCompany'] = $result['pastCompany'];
                $i++;
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        $db = NULL;
        return $result2;
    }
}
?>