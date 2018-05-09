-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 09, 2018 at 04:28 PM
-- Server version: 5.6.38-log
-- PHP Version: 7.2.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `m4`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_AddEduExperience`(IN p_uid INT, 
IN p_QTypeID VARCHAR(2), 
IN p_qualification VARCHAR(100),
IN p_institution VARCHAR(100),
IN p_yearAttained INT,
IN p_ctryID VARCHAR(2))
BEGIN
  INSERT INTO CP_TB_USREDU (UserID, QTypeID, Qualification, Institution, YearAttained, CountryID) 
  VALUES (p_uid, p_QTypeID, p_qualification, p_institution, p_yearAttained, p_ctryID);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_AddJobExperience`(IN p_uid INT, 
IN p_jobPosition VARCHAR(100), 
IN p_company VARCHAR(100),
IN p_industry INT,
IN p_fromDate DATE,
IN p_toDate DATE,
IN p_desc TEXT(65535))
BEGIN
  INSERT INTO CP_TB_USERJOB (UserID, JobPosition, Company, IndustryTypeID, FromDate, ToDate, Description) 
  VALUES (p_uid, p_jobPosition, p_company, p_industry, p_fromDate, p_toDate, p_desc);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_AddThreadAttach`(IN p_tid INT,
IN p_filepath VARCHAR(255)
)
BEGIN
  INSERT INTO CP_TB_THREADATTACH (ThreadMsgID, FilePath) VALUES (p_tid, p_filepath);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ApplyJob`(IN p_jobID INT, 
IN p_message TEXT,
IN p_attachment VARCHAR(255),
IN p_uid INT)
BEGIN
  INSERT INTO CP_TB_JOBAPPLY (jobID,  filepath, message, userID) 
  VALUES (p_jobID, p_attachment, p_message, p_uid);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ChangePassword`(IN p_uid INT, IN p_newPassword VARCHAR(20))
BEGIN
  UPDATE CP_TB_USER SET Password = p_newPassword, UpdatedBy = p_uid, UpdatedOn = sysdate() 
  WHERE UserID = p_uid;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_MsgInbox`(IN p_id INT)
BEGIN
  SELECT distinct m.msgThreadID, m.subject, u.fullname AS senderName, 
  t.lastThreadUpdate, m.recipientID
  FROM CP_TB_MESSAGE m
  INNER JOIN CP_TB_USER u
  ON m.createdBy = u.userID
  INNER JOIN CP_VIEW_LATESTMESSAGEUPDATE t
  ON m.msgThreadID = t.msgThreadID
  WHERE m.recipientID = p_id
  ORDER BY m.msgThreadID DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_NewMsgThread`(IN p_msgContent TEXT(1000),
IN p_senderID INT
)
BEGIN
  DECLARE v_msgid INT;
  SELECT MAX(MessageID) INTO v_msgid FROM CP_TB_MESSAGE;
  SET v_msgid = v_msgid + 1;
  INSERT INTO CP_TB_MESSAGE (MessageID, MsgContent, CreatedBy, CreatedOn) 
  VALUES (v_msgID, p_msgContent, p_senderID, sysdate());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_NewThread`(IN p_title VARCHAR(255),
IN p_content TEXT(65535),
IN p_id INT
)
BEGIN
  INSERT INTO CP_TB_THREAD (ThreadTitle, ThreadContent, CreatedOn, CreatedBy) 
  VALUES (p_title, p_content, sysdate(), p_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_PostJob`(IN p_jp VARCHAR(100), 
IN p_desc TEXT(65535), 
IN p_salary DECIMAL(8,2),
IN p_cdate DATE,
IN p_postedBy INT)
BEGIN
  INSERT INTO CP_TB_JOB (JobPosition, Description, Salary, ClosingDate, PostedBy, PostedOn) 
  VALUES (p_jp, p_desc, p_salary, p_cdate, p_postedBy, sysdate());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_RegisterUser`(IN p_fn VARCHAR(100), 
IN p_email VARCHAR(100), 
IN p_password VARCHAR(20), 
IN p_dob DATE, 
IN p_residence VARCHAR(2), 
IN p_job VARCHAR(100), 
IN p_company VARCHAR(100), 
IN p_industry INT)
BEGIN
  INSERT INTO CP_TB_USER (Fullname, Email, Password, DOB, CountryOfResidence, JobPosition, Company,  Industry, UserTypeID, CreatedOn) 
  VALUES (p_fn, p_email, p_password, p_dob, p_residence, p_job, p_company, p_industry, 0, sysdate());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ReplyMessage`(IN p_msgID INT,
IN p_msgContent TEXT(1000),
IN p_senderID INT,
IN p_createdOn DATETIME
)
BEGIN
  INSERT INTO CP_TB_MESSAGE (MessageID, MsgContent, CreatedBy, CreatedOn) 
  VALUES (p_msgID, p_msgContent, p_senderID, p_createdOn);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ReplyThread`(IN p_tid INT,
IN p_msgContent TEXT(65535),
IN p_createdBy INT
)
BEGIN
  INSERT INTO CP_TB_THREADMSG (ThreadID, ThreadMsgContent, CreatedOn, CreatedBy) 
  VALUES (p_tid, p_msgContent, p_createdBy, sysdate());
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_SearchUser`(IN p_search VARCHAR(100),
IN p_uid INT)
BEGIN
  DECLARE v_search VARCHAR(102);
  SET v_search = CONCAT('%', p_search, '%');
    (
    SELECT u.userID, u.FullName, c.countryName, u.jobPosition,'NAME' AS CATEGORY
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    WHERE u.FullName LIKE v_search AND u.userDisabled = 0 AND u.userID <> p_uid
    )
  UNION
    (
	SELECT u.userID, u.FullName, c.countryName, u.jobPosition,'COUNTRY' AS CATEGORY
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    WHERE c.countryName LIKE v_search AND u.userDisabled = 0 AND u.userID <> p_uid
    )
  UNION
    (
    SELECT u.userID, u.FullName, c.countryName, u.jobPosition, 'JOBPOSITION' AS CATEGORY
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    LEFT JOIN CP_TB_USERJOB j
    ON u.UserID = j.UserID
    WHERE (j.JobPosition LIKE v_search or u.JobPosition LIKE v_search) AND u.userDisabled = 0 AND u.userID <> p_uid
    )
  UNION
    (
    SELECT u.userID, u.FullName, c.countryName, u.jobPosition, 'INDUSTRY' AS CATEGORY
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    LEFT JOIN CP_TB_USERJOB j
    ON u.UserID = j.UserID
    LEFT JOIN CP_TB_INDUSTRY i
    ON u.industryID = i.industryID
    LEFT JOIN CP_TB_INDUSTRY k
    ON j.industryTypeID = k.industryID
    WHERE (i.IndustryTypeName LIKE v_search or k.IndustryTypeName LIKE v_search) AND u.userDisabled = 0 AND u.userID <> p_uid
    )
  UNION
    (
	SELECT u.userID, u.FullName, c.countryName, u.jobPosition, 'COMPANY' AS CATEGORY
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    LEFT JOIN CP_TB_USERJOB j
    ON u.UserID = j.UserID
    WHERE (u.Company LIKE v_search OR j.Company LIKE v_search) AND u.userDisabled = 0 AND u.userID <> p_uid
    )
  UNION
    (
	SELECT u.userID, u.FullName, c.countryName, u.jobPosition, 'QUALIFICATION' AS CATEGORY
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    LEFT JOIN CP_TB_USREDU e
    ON u.UserID = e.UserID
    WHERE e.Qualification LIKE v_search AND u.userDisabled = 0 AND u.userID <> p_uid
    )
  UNION
    (
    SELECT u.userID, u.FullName, c.countryName, u.jobPosition, 'INSTITUTION' AS CATEGORY
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    LEFT JOIN CP_TB_USREDU e
    ON u.UserID = e.UserID
    WHERE e.Institution LIKE v_search AND u.userDisabled = 0 AND u.userID <> p_uid
    )
    ORDER BY FIELD(CATEGORY, 'NAME', 'COUNTRY', 'JOBPOSITION', 'COMPANY', 'INDUSTRY', 'QUALIFICATION', 'INSTITUTION');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_THREADQNS`(in t_id int)
BEGIN
	SELECT t.threadID, t.threadTitle, t.threadMsgContent, t.createdOn, u.fullname AS createdBy
    FROM
    CP_TB_THREAD t
    INNER JOIN
    CP_TB_USER u
    ON t.createdBy = u.userID 
    WHERE threadID = t_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_THREADREPLY`(IN t_id INT)
BEGIN
	SELECT t.threadID, t.threadMsgContent, t.createdOn, ta.filepath, u.fullname AS createdBy
    FROM
    CP_TB_THREADMSG t
    INNER JOIN
    CP_TB_USER u
	ON t.createdBy = u.userID 
    LEFT JOIN
    cp_tb_threadattach ta
    ON t.threadMsgID = ta.threadMsgID
    WHERE threadID = t_id
    ORDER BY t.createdOn ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_THREADREPLYTOUSER`(IN u_id INT)
BEGIN
	SELECT tm.threadID, t.threadTitle, tm.createdOn, u.fullname AS createdBy
    FROM
    CP_TB_THREADMSG tm
    INNER JOIN
    CP_TB_USER u
	ON tm.createdBy = u.userID 
    LEFT JOIN
    CP_TB_THREAD t
    ON tm.threadID = t.threadID
    WHERE tm.threadID IN (SELECT threadID FROM CP_TB_THREAD WHERE createdBy = u_id) AND tm.createdBy <> u_id
    ORDER BY tm.createdOn DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ViewJobApplicantsByJobID`(IN j_id INT)
BEGIN
	SELECT u.fullname, j.jobID, j.filepath, j.applicationDate, j.message
	FROM CP_TB_JOBAPPLY j
    INNER JOIN CP_TB_USER u
    ON j.userID = u.userID
    WHERE j.jobID = j_id
	ORDER BY j.applicationID ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ViewJobApplicantsByPostedID`(IN p_uid INT)
BEGIN
	SELECT u.fullname, ja.jobID, j.jobPosition, ja.applicationDate
	FROM CP_TB_JOBAPPLY ja
    INNER JOIN CP_TB_USER u
    ON ja.userID = u.userID
    INNER JOIN CP_TB_JOB j
    ON ja.jobID = j.jobID
    WHERE ja.jobID IN 
    (SELECT jobID FROM CP_TB_JOB WHERE postedBy = p_uid)
	ORDER BY ja.applicationID DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ViewJobDetails`(IN j_id INT)
BEGIN
	SELECT u.fullname AS postedBy, j.postedBy AS postedByID, j.jobID, j.jobPosition, j.description, j.salary, j.closingDate, j.postedOn
	FROM CP_TB_JOB j
    INNER JOIN CP_TB_USER u
    ON j.postedBy = u.userID
    WHERE j.jobID = j_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_VIEWJOBPOSTINGSEXCEPT`(IN p_uid INT)
BEGIN
SELECT 
        `j`.`jobID` AS `jobID`,
        `j`.`jobPosition` AS `jobPosition`,
        `j`.`closingDate` AS `closingDate`,
        `u`.`fullname` AS `postedBy`,
        `j`.`postedOn` AS `postedOn`,
        (SELECT 
                COUNT(`ja`.`applicationID`)
            FROM
                `m4`.`cp_tb_jobapply` `ja`
            WHERE
                (`ja`.`jobID` = `j`.`jobID`)) AS `noOfApplications`
    FROM `m4`.`cp_tb_job` `j`
	INNER JOIN `m4`.`cp_tb_user` `u` 
    ON `j`.`postedBy` = `u`.`userID`
    WHERE
        (`j`.`closingDate` >= SYSDATE() AND `j`.`postedBy` <> p_uid)
    ORDER BY `j`.`postedOn` DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_VIEWMESSAGEDETAILS`(IN p_tid INT)
BEGIN
	SELECT m.messageID, m.msgThreadID, m.subject, m.msgContent, m.createdOn, 
    m.filepath, u.fullname AS recipientName, m.recipientID, r.fullname AS senderName, 
    m.createdBy AS senderID
    FROM CP_TB_MESSAGE m
    INNER JOIN CP_TB_USER u
    ON m.recipientID = u.userID
    INNER JOIN CP_TB_USER r
    ON m.createdBy = r.userID
    WHERE (m.msgThreadID = p_tid)
    ORDER BY m.messageID ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ViewOwnJobApplication`(IN p_id INT,
IN p_userID INT)
BEGIN
	SELECT u.fullname, j.jobID, j.filepath, j.applicationDate, j.message
	FROM CP_TB_JOBAPPLY j
    INNER JOIN CP_TB_USER u
    ON j.userID = u.userID
    WHERE j.jobID = p_id AND j.userID = p_userID
	ORDER BY j.applicationID ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ViewPastJobs`(IN p_id INT)
BEGIN
  SELECT jobPosition AS pastJobPosition, company AS pastCompany, fromDate, toDate
    FROM CP_TB_USERJOB
    WHERE userID = p_id
    ORDER BY toDate DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CP_PROC_ViewPublicProfile`(IN p_id INT)
BEGIN
  SELECT u.fullName, u.email, c.countryName, u.jobPosition, u.company, i.industryTypeName,
    e.qualification, e.institution
    FROM CP_TB_USER u
    LEFT JOIN CP_TB_USREDU e
    ON u.userID = e.userID
    LEFT JOIN CP_TB_INDUSTRY i
    ON u.industryID = i.industryID
    LEFT JOIN CP_TB_COUNTRY c
    ON u.countryOfResidence = c.countryID
    WHERE u.userID = p_id AND u.userDisabled = 0;
END$$

--
-- Functions
--
CREATE DEFINER=`root`@`localhost` FUNCTION `CP_FN_UserExist`(p_email VARCHAR(100)) RETURNS tinyint(1)
BEGIN
  RETURN Exists(SELECT UserID FROM CP_TB_USER WHERE email = p_email);
END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `CP_FN_UserLogin`(p_email VARCHAR(100), p_password VARCHAR(20)) RETURNS varchar(20) CHARSET utf8
    DETERMINISTIC
BEGIN
  DECLARE v_locked TINYINT(1);
  DECLARE v_disabled TINYINT(1);
  IF  CP_FN_UserExist(p_email) THEN
    SELECT UserDisabled INTO v_disabled FROM CP_TB_USER WHERE email = p_email;
    IF v_disabled THEN RETURN 'DISABLED'; 
    END IF;
    SELECT LockedFlag INTO v_locked FROM CP_TB_USER WHERE email = p_email;
    IF v_locked THEN RETURN 'LOCKED'; 
    END IF;
    IF p_password <> (SELECT password FROM CP_TB_USER WHERE email = p_email) THEN
      RETURN 'MISMATCHED';
    END IF;
    RETURN 'SUCCESS';
  ELSE
    RETURN 'INVALID';
  END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_country`
--

CREATE TABLE IF NOT EXISTS `cp_tb_country` (
  `countryID` varchar(2) NOT NULL COMMENT '2 character country ID',
  `countryName` varchar(45) DEFAULT NULL COMMENT 'Country Name',
  PRIMARY KEY (`countryID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_industry`
--

CREATE TABLE IF NOT EXISTS `cp_tb_industry` (
  `industryID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID with 2 chars',
  `industryTypeName` varchar(45) DEFAULT NULL COMMENT 'Types of industry e.g. Manufacturing, Banking etc',
  PRIMARY KEY (`industryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=58 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_job`
--

CREATE TABLE IF NOT EXISTS `cp_tb_job` (
  `jobID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto increment field.',
  `jobPosition` varchar(100) DEFAULT NULL COMMENT 'The job position posted.',
  `description` text COMMENT 'Job responsibilies and requirements.',
  `salary` varchar(50) DEFAULT NULL COMMENT 'Salary of job posted.',
  `closingDate` datetime DEFAULT NULL COMMENT 'Closing date for job application.',
  `postedBy` int(11) DEFAULT NULL COMMENT 'ID of user who posted the job.',
  `postedOn` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Posted date/time.',
  PRIMARY KEY (`jobID`),
  KEY `fk_Job_User1_idx` (`postedBy`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_jobapply`
--

CREATE TABLE IF NOT EXISTS `cp_tb_jobapply` (
  `applicationID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto increment field. Primary Key.',
  `userID` int(11) DEFAULT NULL COMMENT 'ID of user who apply for job.',
  `jobID` int(11) DEFAULT NULL COMMENT 'Job ID applied for.',
  `message` text,
  `filepath` varchar(255) DEFAULT NULL COMMENT 'Resume Attachment represented as file path.',
  `applicationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created date',
  PRIMARY KEY (`applicationID`),
  KEY `fk_JobApplication_JobID_idx` (`jobID`),
  KEY `fk_JobApplication_UserID` (`userID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_mailsent`
--

CREATE TABLE IF NOT EXISTS `cp_tb_mailsent` (
  `mailID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto Increment field. Number will indicate the total messages sent out.',
  `subject` varchar(255) DEFAULT NULL,
  `mailContent` text COMMENT 'Content of message',
  `filename` varchar(255) DEFAULT NULL,
  `recipientEmail` varchar(100) DEFAULT NULL,
  `senderID` int(11) DEFAULT NULL COMMENT 'Created by User ID',
  `createdOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created date/time',
  PRIMARY KEY (`mailID`),
  KEY `fk_Message_User1_idx` (`senderID`),
  KEY `fk_message_user_recipientID_idx` (`recipientEmail`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_message`
--

CREATE TABLE IF NOT EXISTS `cp_tb_message` (
  `messageID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto Increment field. Number will indicate the total messages sent out.',
  `msgThreadID` int(11) NOT NULL COMMENT 'Unique for each message group created.',
  `subject` varchar(255) DEFAULT NULL,
  `msgContent` text COMMENT 'Content of message',
  `createdBy` int(11) DEFAULT NULL COMMENT 'Created by User ID',
  `createdOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created date/time',
  `recipientID` int(11) DEFAULT NULL,
  `filepath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`messageID`),
  KEY `fk_Message_User1_idx` (`createdBy`),
  KEY `idx_MessageID` (`msgThreadID`),
  KEY `fk_message_user_recipientID_idx` (`recipientID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_qualificationtype`
--

CREATE TABLE IF NOT EXISTS `cp_tb_qualificationtype` (
  `QTypeID` varchar(2) NOT NULL COMMENT 'Education Qualification ID in 2 chars',
  `QTypeName` varchar(45) DEFAULT NULL COMMENT 'Education Qualification type (e.g. Degree, Certification)',
  PRIMARY KEY (`QTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_thread`
--

CREATE TABLE IF NOT EXISTS `cp_tb_thread` (
  `threadID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Unique for each new thread topic created.',
  `threadTitle` varchar(255) DEFAULT NULL COMMENT 'Title topic of thread.',
  `threadMsgContent` text COMMENT 'Content of thread.',
  `createdOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Create date/time',
  `createdBy` int(11) DEFAULT NULL COMMENT 'User ID of creator',
  PRIMARY KEY (`threadID`),
  KEY `fk_Thread_UserID_idx` (`createdBy`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_threadattach`
--

CREATE TABLE IF NOT EXISTS `cp_tb_threadattach` (
  `attachmentID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto increment field.',
  `threadMsgID` int(11) NOT NULL COMMENT 'The thread message ID the attachment is associated with.',
  `filePath` varchar(255) DEFAULT NULL COMMENT 'File path of the attachment.',
  `createdOn` datetime DEFAULT CURRENT_TIMESTAMP,
  `createdBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`attachmentID`),
  KEY `fk_ThreadAttachment_ThreadMsg1_idx` (`threadMsgID`),
  KEY `fk_ThreadAttach_User_createdBy_idx` (`createdBy`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_threadmsg`
--

CREATE TABLE IF NOT EXISTS `cp_tb_threadmsg` (
  `threadMsgID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto Increment field. Number will indicate the total replies for all thread topics.',
  `threadID` int(11) DEFAULT NULL COMMENT 'ID of thread topic the message is associated with.',
  `threadMsgContent` text COMMENT 'Message content of thread.',
  `createdOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Created date/time',
  `createdBy` int(11) DEFAULT NULL COMMENT 'User ID of creator',
  PRIMARY KEY (`threadMsgID`),
  KEY `fk_ThreadMsg_Thread1_idx` (`threadID`),
  KEY `fk_ThreadMsg_UserID_idx` (`createdBy`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_user`
--

CREATE TABLE IF NOT EXISTS `cp_tb_user` (
  `userID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto incremented user ID',
  `fullname` varchar(100) NOT NULL COMMENT 'Full name of user',
  `email` varchar(100) NOT NULL COMMENT 'Email of user',
  `password` varchar(255) NOT NULL COMMENT 'Password limit restricted to 20 chars.',
  `dob` date DEFAULT NULL COMMENT 'Date of birth',
  `countryOfResidence` varchar(2) DEFAULT NULL COMMENT 'Current country of residence.',
  `jobPosition` varchar(100) DEFAULT NULL COMMENT 'Current job position',
  `company` varchar(100) DEFAULT NULL COMMENT 'Current company',
  `industryID` int(11) DEFAULT NULL COMMENT 'Industry category of current work',
  `userTypeID` tinyint(4) DEFAULT '0' COMMENT '0 for User, 1 for Administrator',
  `createdOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Datetime for record creation',
  `updatedOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Datetime for last update',
  `updatedBy` int(11) DEFAULT NULL COMMENT 'Can be updated by Administrator for field LockedFlag.',
  `lockedFlag` tinyint(1) DEFAULT '0' COMMENT 'More than 5 password incorrect attempt will result in LockedFlag set to True.',
  `userDisabled` tinyint(1) DEFAULT '0' COMMENT 'Administrator can disable user if user found to be spamming/exhibit other unacceptable behaviour in the portal.',
  PRIMARY KEY (`userID`),
  UNIQUE KEY `Email_UNIQUE` (`email`),
  KEY `fk_CtryResidence_idx` (`countryOfResidence`),
  KEY `fk_user_CurrentIndustry_idx` (`industryID`),
  KEY `fk_user_UpdatedBy_idx` (`updatedBy`),
  KEY `fk_user_UserType_idx` (`userTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=53 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_userjob`
--

CREATE TABLE IF NOT EXISTS `cp_tb_userjob` (
  `jobExperienceID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto increment field.',
  `userID` int(11) DEFAULT NULL COMMENT 'ID of user who added job experience.',
  `jobPosition` varchar(100) DEFAULT NULL COMMENT 'Job position added.',
  `company` varchar(100) DEFAULT NULL COMMENT 'Company associated with job position.',
  `industryTypeID` int(11) DEFAULT NULL COMMENT 'Industry type ID selected from CP_TB_Industry table.',
  `fromDate` date DEFAULT NULL COMMENT 'The start date of job position.',
  `toDate` date DEFAULT NULL COMMENT 'The end date of job position.',
  `description` text COMMENT 'Description of the job.',
  PRIMARY KEY (`jobExperienceID`),
  KEY `fk_JobExperience_User1_idx` (`userID`),
  KEY `fk_Industry_idx` (`industryTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_usredu`
--

CREATE TABLE IF NOT EXISTS `cp_tb_usredu` (
  `userEduID` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Auto increment field.',
  `userID` int(11) DEFAULT NULL COMMENT 'ID of user who added education history.',
  `qTypeID` varchar(2) DEFAULT NULL COMMENT 'Qualification type selected from table CP_TB_QualificationType.',
  `qualification` varchar(100) DEFAULT NULL COMMENT 'User can type out the qualification received. E.g. Bachelor of Engineering (1st Class Honors)',
  `institution` varchar(100) DEFAULT NULL COMMENT 'The institution which confer the qualification.',
  `yearAttained` int(11) DEFAULT NULL COMMENT 'The year the qualification was attained.',
  `countryID` varchar(2) DEFAULT NULL COMMENT 'The country ID (selected from table CP_TB_Country) the qualification was attained at.',
  PRIMARY KEY (`userEduID`),
  KEY `fk_EducationalQualification_User_idx` (`userID`),
  KEY `fk_EducationalQualification_QType_idx` (`qTypeID`),
  KEY `fk_EducationalQualification_Country_idx` (`countryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `cp_tb_usrtype`
--

CREATE TABLE IF NOT EXISTS `cp_tb_usrtype` (
  `UserTypeID` tinyint(4) NOT NULL COMMENT '0 for User, 1 for administrator',
  `UserTypeName` varchar(5) DEFAULT NULL COMMENT 'Only 2 type names, USER or ADMIN',
  PRIMARY KEY (`UserTypeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Stand-in structure for view `cp_view_administrator`
--
CREATE TABLE IF NOT EXISTS `cp_view_administrator` (
`UserID` int(11)
,`FullName` varchar(100)
,`Email` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `cp_view_countmsgreplies`
--
CREATE TABLE IF NOT EXISTS `cp_view_countmsgreplies` (
`msgThreadID` int(11)
,`noOfReplies` bigint(22)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `cp_view_disableduser`
--
CREATE TABLE IF NOT EXISTS `cp_view_disableduser` (
`UserID` int(11)
,`FullName` varchar(100)
,`Email` varchar(100)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `cp_view_jobpostings`
--
CREATE TABLE IF NOT EXISTS `cp_view_jobpostings` (
`jobID` int(11)
,`jobPosition` varchar(100)
,`closingDate` datetime
,`postedBy` varchar(100)
,`postedOn` datetime
,`noOfApplications` bigint(21)
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `cp_view_latestmessageupdate`
--
CREATE TABLE IF NOT EXISTS `cp_view_latestmessageupdate` (
`msgThreadID` int(11)
,`lastThreadUpdate` timestamp
);
-- --------------------------------------------------------

--
-- Stand-in structure for view `cp_view_thread`
--
CREATE TABLE IF NOT EXISTS `cp_view_thread` (
`threadID` int(11)
,`threadTitle` varchar(255)
,`threadMsgContent` text
,`createdOn` timestamp
,`createdBy` varchar(100)
,`noOfReplies` bigint(21)
);
-- --------------------------------------------------------

--
-- Structure for view `cp_view_administrator`
--
DROP TABLE IF EXISTS `cp_view_administrator`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cp_view_administrator` AS select `cp_tb_user`.`userID` AS `UserID`,`cp_tb_user`.`fullname` AS `FullName`,`cp_tb_user`.`email` AS `Email` from `cp_tb_user` where (`cp_tb_user`.`userTypeID` = 1);

-- --------------------------------------------------------

--
-- Structure for view `cp_view_countmsgreplies`
--
DROP TABLE IF EXISTS `cp_view_countmsgreplies`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cp_view_countmsgreplies` AS select `cp_tb_message`.`msgThreadID` AS `msgThreadID`,(count(`cp_tb_message`.`messageID`) - 1) AS `noOfReplies` from `cp_tb_message` group by `cp_tb_message`.`msgThreadID`;

-- --------------------------------------------------------

--
-- Structure for view `cp_view_disableduser`
--
DROP TABLE IF EXISTS `cp_view_disableduser`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cp_view_disableduser` AS select `cp_tb_user`.`userID` AS `UserID`,`cp_tb_user`.`fullname` AS `FullName`,`cp_tb_user`.`email` AS `Email` from `cp_tb_user` where (`cp_tb_user`.`userDisabled` = 1);

-- --------------------------------------------------------

--
-- Structure for view `cp_view_jobpostings`
--
DROP TABLE IF EXISTS `cp_view_jobpostings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cp_view_jobpostings` AS select `j`.`jobID` AS `jobID`,`j`.`jobPosition` AS `jobPosition`,`j`.`closingDate` AS `closingDate`,`u`.`fullname` AS `postedBy`,`j`.`postedOn` AS `postedOn`,(select count(`ja`.`applicationID`) from `cp_tb_jobapply` `ja` where (`ja`.`jobID` = `j`.`jobID`)) AS `noOfApplications` from (`cp_tb_job` `j` join `cp_tb_user` `u` on((`j`.`postedBy` = `u`.`userID`))) where (`j`.`closingDate` >= sysdate()) order by `j`.`postedOn` desc;

-- --------------------------------------------------------

--
-- Structure for view `cp_view_latestmessageupdate`
--
DROP TABLE IF EXISTS `cp_view_latestmessageupdate`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cp_view_latestmessageupdate` AS select `cp_tb_message`.`msgThreadID` AS `msgThreadID`,max(`cp_tb_message`.`createdOn`) AS `lastThreadUpdate` from `cp_tb_message` group by `cp_tb_message`.`msgThreadID`;

-- --------------------------------------------------------

--
-- Structure for view `cp_view_thread`
--
DROP TABLE IF EXISTS `cp_view_thread`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cp_view_thread` AS select `t`.`threadID` AS `threadID`,`t`.`threadTitle` AS `threadTitle`,`t`.`threadMsgContent` AS `threadMsgContent`,`t`.`createdOn` AS `createdOn`,`u`.`fullname` AS `createdBy`,(select count(`cp_tb_threadmsg`.`threadMsgID`) from `cp_tb_threadmsg` where (`cp_tb_threadmsg`.`threadID` = `t`.`threadID`)) AS `noOfReplies` from (`cp_tb_thread` `t` join `cp_tb_user` `u` on((`t`.`createdBy` = `u`.`userID`))) order by `t`.`createdOn` desc;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cp_tb_job`
--
ALTER TABLE `cp_tb_job`
  ADD CONSTRAINT `fk_Job_User1` FOREIGN KEY (`postedBy`) REFERENCES `cp_tb_user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cp_tb_jobapply`
--
ALTER TABLE `cp_tb_jobapply`
  ADD CONSTRAINT `fk_JobApplication_JobID` FOREIGN KEY (`jobID`) REFERENCES `cp_tb_job` (`jobID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_JobApplication_UserID` FOREIGN KEY (`userID`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `cp_tb_mailsent`
--
ALTER TABLE `cp_tb_mailsent`
  ADD CONSTRAINT `fk_message_user_senderID` FOREIGN KEY (`senderID`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cp_tb_message`
--
ALTER TABLE `cp_tb_message`
  ADD CONSTRAINT `fk_Message_User1` FOREIGN KEY (`createdBy`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_message_user_recipientID` FOREIGN KEY (`recipientID`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `cp_tb_thread`
--
ALTER TABLE `cp_tb_thread`
  ADD CONSTRAINT `fk_Thread_UserID` FOREIGN KEY (`createdBy`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `cp_tb_threadattach`
--
ALTER TABLE `cp_tb_threadattach`
  ADD CONSTRAINT `fk_ThreadAttach_User_createdBy` FOREIGN KEY (`createdBy`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ThreadAttachment_ThreadMsg` FOREIGN KEY (`threadMsgID`) REFERENCES `cp_tb_threadmsg` (`threadMsgID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cp_tb_threadmsg`
--
ALTER TABLE `cp_tb_threadmsg`
  ADD CONSTRAINT `fk_ThreadMsg_Thread` FOREIGN KEY (`threadID`) REFERENCES `cp_tb_thread` (`threadID`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ThreadMsg_UserID` FOREIGN KEY (`createdBy`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `cp_tb_user`
--
ALTER TABLE `cp_tb_user`
  ADD CONSTRAINT `fk_user_CtryResidence` FOREIGN KEY (`countryOfResidence`) REFERENCES `cp_tb_country` (`countryID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_CurrentIndustry` FOREIGN KEY (`industryID`) REFERENCES `cp_tb_industry` (`industryID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_UpdatedBy` FOREIGN KEY (`updatedBy`) REFERENCES `cp_tb_user` (`userID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_UserType` FOREIGN KEY (`userTypeID`) REFERENCES `cp_tb_usrtype` (`UserTypeID`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `cp_tb_userjob`
--
ALTER TABLE `cp_tb_userjob`
  ADD CONSTRAINT `fk_JobExperience_Industry` FOREIGN KEY (`industryTypeID`) REFERENCES `cp_tb_industry` (`industryID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_JobExperience_User1` FOREIGN KEY (`userID`) REFERENCES `cp_tb_user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cp_tb_usredu`
--
ALTER TABLE `cp_tb_usredu`
  ADD CONSTRAINT `fk_EducationalQualification_Country` FOREIGN KEY (`countryID`) REFERENCES `cp_tb_country` (`countryID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_EducationalQualification_QType` FOREIGN KEY (`qTypeID`) REFERENCES `cp_tb_qualificationtype` (`QTypeID`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_EducationalQualification_User` FOREIGN KEY (`userID`) REFERENCES `cp_tb_user` (`userID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
