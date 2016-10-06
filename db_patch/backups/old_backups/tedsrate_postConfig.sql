-- phpMyAdmin SQL Dump
-- version 4.4.13.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 26, 2016 at 09:47 PM
-- Server version: 5.6.27-0ubuntu1
-- PHP Version: 5.6.11-1ubuntu3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tedsrate`
--
CREATE DATABASE IF NOT EXISTS `tedsrate` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `tedsrate`;

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addArtifact`(IN `artifactName` VARCHAR(45), IN `artifactURL` VARCHAR(255), IN `artifactTypeID` INT, IN `languageID` INT, OUT `newArtifactID` INT)
BEGIN
START TRANSACTION;
INSERT INTO artifact
(artifactName, artifactURL, artifactTypeID, languageID)
VALUES 
(artifactName, artifactURL, artifactTypeID, languageID);
COMMIT;
SELECT last_insert_id() INTO newArtifactID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `addClusterRating`(IN `ratingValue` INT, IN `clusterID` INT, IN `assessmentID` INT, OUT `newRatingID` INT)
    NO SQL
IF (SELECT 1 = 1 
    FROM rating  
    JOIN clusterRating ON clusterRating.ratingID = rating.ratingID 
    WHERE rating.assessmentID= assessmentID 
    AND clusterRating.clusterID = clusterID) 
    THEN
		BEGIN
        	UPDATE rating 
        	JOIN clusterRating ON clusterRating.ratingID = rating.ratingID 
        	SET ratingValue = ratingValue, 
        	rating.ratingID = LAST_INSERT_ID(rating.ratingID)
        	WHERE rating.assessmentID= assessmentID 
            AND clusterRating.clusterID = clusterID;
        	SELECT LAST_INSERT_ID() INTO newRatingID;
	END;
	ELSE
		BEGIN
    	INSERT INTO rating 
        (ratingValue, assessmentID)
		VALUES (ratingValue, assessmentID);
		SELECT LAST_INSERT_ID() INTO newRatingID;
        INSERT INTO clusterRating (ratingID, clusterID)
        VALUES (newRatingID, clusterID);
	END;
END IF$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addPersona`(IN `name` VARCHAR(45), IN `description` VARCHAR(255), IN `languageID` INT, OUT `personaID` INT)
BEGIN
Start Transaction;
INSERT INTO persona
(personaName, personaDesc, languageID)
Values (name, description, languageID);
COMMIT;
SELECT last_insert_id() INTO personaID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addPersonaScenario`(IN `personaID` INT, IN `scenarioID` INT, OUT `PSID` INT)
BEGIN
START TRANSACTION;
INSERT INTO personaScenario
(personaID, scenarioID)
VALUES
(personaID, scenarioID);
COMMIT;
SELECT last_input_id() into PSID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addProject`(IN `projectName` VARCHAR(45), IN `projectDescription` VARCHAR(150), IN `languageID` INT, OUT `newProjectID` INT)
BEGIN
START TRANSACTION;
INSERT Into project
(projectName, projectDescription, languageID)
VALUES
(projectName, projectDescription, languageID);
COMMIT;
SELECT Last_insert_id() into newProjectID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addProjectArtifact`(in projectID int, artifactIDint int, isAnchor bit, out newPAID int)
BEGIN
START TRANSACTION;
Insert Into projectArtifact
(projectID, artifactID, isAnchor)
VALUES
(projectID, artifactID, isAnchor);
COMMIT;
select last_insert_id() INTO newPAID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addRating`(IN `ratingValue` DECIMAL, IN `attributeID` INT, IN `assessmentID` INT, OUT `newRatingID` INT)
IF (SELECT 1 = 1 
    FROM rating  
    WHERE rating.assessmentID= assessmentID 
    AND rating.attributeID = attributeID) 
    THEN
		BEGIN
        	UPDATE rating 
        	SET ratingValue = ratingValue, 
        	rating.ratingID = LAST_INSERT_ID(rating.ratingID)
        	WHERE rating.assessmentID= assessmentID 
            AND rating.attributeID = attributeID;
        	SELECT LAST_INSERT_ID() INTO newRatingID;
	END;
	ELSE
		BEGIN
    	INSERT INTO rating 
        (ratingValue, assessmentID, attributeID)
		VALUES (ratingValue, assessmentID, attributeID);
		SELECT LAST_INSERT_ID() INTO newRatingID;
	END;
END IF$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addScenario`(IN `scenarioName` VARCHAR(45), IN `scenarioDescription` VARCHAR(255), IN `languageID` INT, OUT `newScenarioID` INT)
BEGIN
START TRANSACTION;
INSERT INTO scenario
(scenarioName, scenarioDescription, languageID)
VALUES
(scenarioName, scenarioDescription, languageID);
COMMIT;
select last_insert_ID() Into newScenarioID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addUser`(IN `email` VARCHAR(100), IN `firstName` VARCHAR(45), IN `lastName` VARCHAR(45), OUT `userID` INT)
BEGIN

START TRANSACTION;
INSERT INTO `user`
(email, firstName, lastName)
VALUES (email, firstName, lastName);

COMMIT;
select last_insert_id() INTO userID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addUserRatingProgress`(IN `userID` INT, IN `projectArtifactID` INT, OUT `assessmentID` INT)
BEGIN
START TRANSACTION;
INSERT INTO assessment
(userID, projectArtifactID, isComplete, completionDate)
VALUES
(userID, projectArtifactID, 1, now());
COMMIT;
SELECT last_insert_ID() into assessmentID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `addUser_new`(IN `email` VARCHAR(100), IN `firstName` VARCHAR(45), IN `lastName` VARCHAR(45), IN `passwordValue` VARCHAR(50), IN `languageID` INT, OUT `userID` INT, IN `AuthorityLevel` INT)
BEGIN

START TRANSACTION;
INSERT INTO `user`
(email, firstName, lastName, languageID, passwordValue, AuthorityLevel)
VALUES (email, firstName, lastName, languageID, passwordValue, AuthorityLevel);

COMMIT;
select last_insert_id() INTO userID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getAllArtifacts`(OUT `ID` INT, OUT `name` VARCHAR(45), OUT `description` VARCHAR(100), OUT `URL` VARCHAR(255), OUT `aType` VARCHAR(45))
BEGIN
SELECT a.artifactID as ID, artifact.artifactName as name, a.artifactURL as URL, atype.artifactTypeTitle as artType
from artifact a inner join artifactType atype on a.artifactTypeID = atype.artifactTypeID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getAllArtifacts_new`(IN `languageID` INT, OUT `ID` INT, OUT `name` VARCHAR(45), OUT `description` VARCHAR(100), OUT `URL` VARCHAR(255), OUT `aType` VARCHAR(45))
BEGIN
SELECT a.artifactID as ID, artifact.artifactName as name, a.artifactURL as URL, atype.artifactTypeTitle as artType
from artifact a inner join artifactType atype on a.artifactTypeID = atype.artifactTypeID
WHERE artifact.languageID = languageID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getAllPersonae`(IN `languageID` INT, OUT `name` VARCHAR(45), OUT `ID` INT)
BEGIN
Select p.personaName as name, p.personaID as ID 
FROM persona p
WHERE persona.languageID = languageID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getAllProjects`(OUT `ID` INT, OUT `projectName` VARCHAR(45), IN `Description` VARCHAR(150))
BEGIN
SELECT p.projectID as ID, p.projectName as projectName, p.projectDescription as Description
FROM project p;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getAllProjects_new`(IN `languageID` INT, OUT `ID` INT, OUT `projectName` VARCHAR(45), IN `Description` VARCHAR(150))
BEGIN
SELECT p.projectID as ID, p.projectName as projectName, p.projectDescription as Description
FROM project p
WHERE project.languageID = languageID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getAllScenarios`(IN `languageID` INT, OUT `name` VARCHAR(45), OUT `id` INT)
BEGIN
Select scenario.scenarioName as name, s.scenarioID as ID
From scenario s
Where languageID = languageID;

END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getArtifact`(IN `artifactID` INT, OUT `name` VARCHAR(45), OUT `url` VARCHAR(255), OUT `description` VARCHAR(150), OUT `artType` VARCHAR(45))
BEGIN
SELECT a.artifactName as name, a.artifactURL as URL, atype.artifactTypeDescription as description, atype.artifactTypeName as artType
from artifact a inner join artifactType atype on a.artifactTypeID = atype.artifactTypeID
WHERE a.artifactID = artifactID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getCategories`(IN `criterionID` INT, OUT `attributeID` INT, IN `attributeName` VARCHAR(45), OUT `attributeDesc` VARCHAR(255))
BEGIN

Select a.attributeName AS attributeName, a.attributeID AS attributeID , a.attributeDesc AS attributeDesc
From category c
JOIN attribute a ON a.attributeID = c.attributeID
WHERE a.criterionID = criterionID
Order by a.attributeID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getConfigurationAttributes`(IN `assessmentID` INT)
    NO SQL
BEGIN

SELECT a.attributeID, a.attributeName, a.attributeDesc, a.criterionID
FROM assessment ass
JOIN configuration con ON ass.configurationID = con.configurationID
JOIN configuration_attribute ca ON ca.configurationID = con.configurationID
JOIN attribute a ON ca.attributeID = a.attributeID
WHERE ass.assessmentID = assessmentID
ORDER BY a.attributeID;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `getConfigurationCriterionAttributes`(IN `assessmentID` INT, IN `criterionID` INT)
    NO SQL
BEGIN

SELECT a.attributeID, a.attributeName, a.attributeDesc, a.criterionID
FROM assessment ass
JOIN configuration con ON ass.configurationID = con.configurationID
JOIN configuration_attribute ca ON ca.configurationID = con.configurationID
JOIN attribute a ON ca.attributeID = a.attributeID
WHERE ass.assessmentID = assessmentID
AND a.criterionID = criterionID
ORDER BY a.attributeID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getCriteria`(IN `languageID` INT, OUT `criterionID` INT, OUT `criterionName` VARCHAR(255), OUT `criterionDesc` VARCHAR(255))
BEGIN
SELECT criterion.criterionID , criterion.criterionName, criterion.criterionDesc
From criterion
Where languageID = languageID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getProject`(IN `pID` INT, OUT `name` VARCHAR(45), OUT `description` VARCHAR(150))
BEGIN
SELECT p.projectName as name, p.projectDescription as description
FROM project p
WHERE p.projectID = pID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `updateCategory`(IN `categoryID` INT, IN `categoryName` VARCHAR(45), IN `categoryDesc` VARCHAR(255), IN `criterionID` INT, IN `languageID` INT)
BEGIN
UPDATE Category
SET
categoryName = categoryName,
categoryDesc = categoryDesc,
criterionID = criterionID,
languageID = languageID
WHERE categoryID = categoryID;

END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `updateUser`(IN `userID` INT, IN `email` VARCHAR(100), IN `firstName` VARCHAR(45), IN `lastName` VARCHAR(45))
BEGIN
Update user
SET email = email,
firstName = firstName,
lastName = lastName
WHERE userID = userID;
END$$

CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `updateUser_new`(IN `userID` INT, IN `email` VARCHAR(100), IN `firstName` VARCHAR(45), IN `lastName` VARCHAR(45), IN `languageID` INT, IN `passwordValue` VARCHAR(50))
BEGIN
Update `user`
SET email = email,
firstName = firstName,
lastName = lastName,
preferredLanguage = preferredLanguage,
passwordValue = passwordValue
WHERE userID = userID;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `artifact`
--

CREATE TABLE IF NOT EXISTS `artifact` (
  `artifactID` int(11) NOT NULL,
  `artifactName` varchar(255) NOT NULL,
  `artifactURL` varchar(255) NOT NULL,
  `artifactTypeID` int(11) NOT NULL DEFAULT '4',
  `languageID` int(11) NOT NULL DEFAULT '5',
  `artifactDescription` varchar(150) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artifact`
--

INSERT INTO `artifact` (`artifactID`, `artifactName`, `artifactURL`, `artifactTypeID`, `languageID`, `artifactDescription`) VALUES
(59, 'duel one', 'http://www.duelone.com', 4, 5, NULL),
(60, 'duel two', 'http://www.dueltwo.com', 4, 5, NULL),
(62, 'baidu', 'http://www.baidu.com', 4, 5, NULL),
(63, 'taobao', 'http://www.taobao.com', 4, 5, NULL),
(67, 'seattle gov', 'http%3A%2F%2Fwww.seattle.gov%2F', 4, 5, NULL),
(71, 'nba', 'http%3A%2F%2Fwww.nba.com', 4, 5, NULL),
(72, 'mlb', 'http%3A%2F%2Fwww.mlb.com', 4, 5, NULL),
(73, 'eCityGovAlliance', 'http%3A%2F%2Fwww.ecitygov.net%2Fdefault.aspx', 4, 5, NULL),
(74, 'Seattle City Government', 'http%3A%2F%2Fwww.seattle.gov%2F', 4, 5, NULL),
(75, 'Local Government', 'http%3A%2F%2Fwww.seattle.gov%2F', 4, 5, NULL),
(76, 'Football 365', 'http%3A%2F%2Fwww.football365.com%2F', 4, 5, NULL),
(77, 'FC Barcelona Web site', '//www.fcbarcelona.com', 4, 5, NULL),
(78, 'Carolina Railhawks Mobile Application', 'http%3A%2F%2Fwww.carolinarailhawks.com%2Fhome', 4, 5, NULL),
(79, 'Chicago Fire Mobile Application', 'http%3A%2F%2Fwww.chicago-fire.com%2F', 4, 5, NULL),
(80, 'Manchester United FC Official Website', 'http://www.manutd.com/', 4, 5, NULL),
(81, 'Seattle Seahawks Mobile Application', 'http%3A%2F%2Fprod.www.seahawks.clubs.nfl.com%2Fmobile%2Fapps.html', 4, 5, NULL),
(82, 'FC Bayern Munich Mobile App', 'http%3A%2F%2Fwww.fcbayern.de%2Fen%2Ffans%2Ffcb-mobile%2F', 4, 5, NULL),
(83, 'Sounders FC Mobile App', 'http%3A%2F%2Fwww.soundersfc.com%2Fmobileapps', 4, 5, NULL),
(84, 'Seattle Seahawks Mobile App', 'http%3A%2F%2Fprod.www.seahawks.clubs.nfl.com%2Fmobile%2Fapps.html', 4, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `artifactType`
--

CREATE TABLE IF NOT EXISTS `artifactType` (
  `artifactTypeID` int(11) NOT NULL,
  `artifactTypeName` varchar(100) NOT NULL,
  `artifactTypeDescription` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artifactType`
--

INSERT INTO `artifactType` (`artifactTypeID`, `artifactTypeName`, `artifactTypeDescription`) VALUES
(4, 'Website', 'Website'),
(5, 'Book', 'Book'),
(6, 'Audio File', 'Audio File');

-- --------------------------------------------------------

--
-- Table structure for table `artifactTypes`
--

CREATE TABLE IF NOT EXISTS `artifactTypes` (
  `artifactTypeID` int(11) NOT NULL,
  `artifactTypeTitle` varchar(100) NOT NULL,
  `artifactTypeDescription` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `artifactTypes`
--

INSERT INTO `artifactTypes` (`artifactTypeID`, `artifactTypeTitle`, `artifactTypeDescription`) VALUES
(4, 'Website', 'Website'),
(5, 'Book', 'Book'),
(6, 'Audio File', 'Audio File');

-- --------------------------------------------------------

--
-- Table structure for table `assessment`
--

CREATE TABLE IF NOT EXISTS `assessment` (
  `assessmentID` int(11) NOT NULL,
  `assessmentIDHashed` varchar(64) NOT NULL,
  `configurationID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `personaID` int(11) NOT NULL,
  `scenarioID` int(11) NOT NULL,
  `projectArtifactID` int(11) NOT NULL,
  `isComplete` varchar(11) DEFAULT NULL,
  `completionDate` datetime DEFAULT NULL,
  `ratingUrl` varchar(2083) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `assessment`
--

INSERT INTO `assessment` (`assessmentID`, `assessmentIDHashed`, `configurationID`, `userID`, `personaID`, `scenarioID`, `projectArtifactID`, `isComplete`, `completionDate`, `ratingUrl`) VALUES
(42, '73475cb40a568e8da8a045ced110137e159f890ac4da883b6b17dc651b3a8049', 1, 42, 39, 36, 65, 'true', '2015-04-27 11:21:07', NULL),
(43, '44cb730c420480a0477b505ae68af508fb90f96cf0ec54c6ad16949dd427f13a', 1, 32, 39, 36, 65, NULL, NULL, NULL),
(44, '71ee45a3c0db9a9865f7313dd3372cf60dca6479d46261f3542eb9346e4a04d6', 1, 44, 39, 36, 65, NULL, NULL, NULL),
(45, '811786ad1ae74adfdd20dd0372abaaebc6246e343aebd01da0bfc4c02bf0106c', 1, 45, 39, 36, 65, NULL, NULL, NULL),
(46, '25fc0e7096fc653718202dc30b0c580b8ab87eac11a700cba03a7c021bc35b0c', 1, 47, 39, 36, 65, 'true', '2015-04-26 11:50:36', NULL),
(47, '31489056e0916d59fe3add79e63f095af3ffb81604691f21cad442a85c7be617', 1, 48, 39, 36, 65, NULL, NULL, NULL),
(48, '98010bd9270f9b100b6214a21754fd33bdc8d41b2bc9f9dd16ff54d3c34ffd71', 1, 49, 39, 36, 65, 'true', '2015-04-26 11:58:26', NULL),
(49, '0e17daca5f3e175f448bacace3bc0da47d0655a74c8dd0dc497a3afbdad95f1f', 1, 50, 39, 36, 65, NULL, NULL, NULL),
(50, '1a6562590ef19d1045d06c4055742d38288e9e6dcd71ccde5cee80f1d5a774eb', 1, 32, 39, 37, 65, 'true', '2015-04-23 16:04:13', NULL),
(52, '41cfc0d1f2d127b04555b7246d84019b4d27710a3f3aff6e7764375b1e06e05d', 1, 44, 39, 37, 65, NULL, NULL, NULL),
(53, '2858dcd1057d3eae7f7d5f782167e24b61153c01551450a628cee722509f6529', 1, 45, 39, 37, 65, NULL, NULL, NULL),
(54, '2fca346db656187102ce806ac732e06a62df0dbb2829e511a770556d398e1a6e', 1, 47, 39, 37, 65, NULL, NULL, NULL),
(55, '02d20bbd7e394ad5999a4cebabac9619732c343a4cac99470c03e23ba2bdc2bc', 1, 48, 39, 37, 65, NULL, NULL, NULL),
(56, '7688b6ef52555962d008fff894223582c484517cea7da49ee67800adc7fc8866', 1, 49, 39, 37, 65, NULL, NULL, NULL),
(57, 'c837649cce43f2729138e72cc315207057ac82599a59be72765a477f22d14a54', 1, 50, 39, 37, 65, NULL, NULL, NULL),
(58, '6208ef0f7750c111548cf90b6ea1d0d0a66f6bff40dbef07cb45ec436263c7d6', 1, 52, 39, 36, 65, NULL, NULL, NULL),
(60, '39fa9ec190eee7b6f4dff1100d6343e10918d044c75eac8f9e9a2596173f80c9', 1, 42, 39, 37, 65, 'true', '2015-04-27 13:26:01', NULL),
(61, 'd029fa3a95e174a19934857f535eb9427d967218a36ea014b70ad704bc6c8d1c', 1, 42, 39, 38, 62, 'true', '2015-04-28 22:07:27', NULL),
(62, '81b8a03f97e8787c53fe1a86bda042b6f0de9b0ec9c09357e107c99ba4d6948a', 1, 42, 39, 39, 62, NULL, NULL, NULL),
(63, 'da4ea2a5506f2693eae190d9360a1f31793c98a1adade51d93533a6f520ace1c', 1, 32, 39, 36, 66, NULL, NULL, NULL),
(64, 'a68b412c4282555f15546cf6e1fc42893b7e07f271557ceb021821098dd66c1b', 1, 32, 39, 37, 66, NULL, NULL, NULL),
(65, '108c995b953c8a35561103e2014cf828eb654a99e310f87fab94c2f4b7d2a04f', 1, 42, 39, 36, 66, 'true', '2015-05-03 21:22:37', NULL),
(66, '3ada92f28b4ceda38562ebf047c6ff05400d4c572352a1142eedfef67d21e662', 1, 42, 39, 37, 66, 'true', '2015-05-03 20:58:51', NULL),
(67, '49d180ecf56132819571bf39d9b7b342522a2ac6d23c1418d3338251bfe469c8', 1, 44, 39, 36, 66, NULL, NULL, NULL),
(68, 'a21855da08cb102d1d217c53dc5824a3a795c1c1a44e971bf01ab9da3a2acbbf', 1, 44, 39, 37, 66, NULL, NULL, NULL),
(69, 'c75cb66ae28d8ebc6eded002c28a8ba0d06d3a78c6b5cbf9b2ade051f0775ac4', 1, 45, 39, 36, 66, 'true', '2015-07-20 23:26:27', NULL),
(70, 'ff5a1ae012afa5d4c889c50ad427aaf545d31a4fac04ffc1c4d03d403ba4250a', 1, 45, 39, 37, 66, NULL, NULL, NULL),
(71, '7f2253d7e228b22a08bda1f09c516f6fead81df6536eb02fa991a34bb38d9be8', 1, 47, 39, 36, 66, 'true', '2015-05-04 11:09:57', NULL),
(72, '8722616204217eddb39e7df969e0698aed8e599ba62ed2de1ce49b03ade0fede', 1, 47, 39, 37, 66, 'true', '2015-05-03 23:07:32', NULL),
(73, '96061e92f58e4bdcdee73df36183fe3ac64747c81c26f6c83aada8d2aabb1864', 1, 48, 39, 36, 66, 'true', '2015-05-03 21:59:09', NULL),
(74, 'eb624dbe56eb6620ae62080c10a273cab73ae8eca98ab17b731446a31c79393a', 1, 48, 39, 37, 66, 'true', '2015-05-03 22:06:50', NULL),
(75, 'f369cb89fc627e668987007d121ed1eacdc01db9e28f8bb26f358b7d8c4f08ac', 1, 49, 39, 36, 66, NULL, NULL, NULL),
(76, 'f74efabef12ea619e30b79bddef89cffa9dda494761681ca862cff2871a85980', 1, 49, 39, 37, 66, 'true', '2015-05-03 16:05:49', NULL),
(77, 'a88a7902cb4ef697ba0b6759c50e8c10297ff58f942243de19b984841bfe1f73', 1, 50, 39, 36, 66, NULL, NULL, NULL),
(78, '349c41201b62db851192665c504b350ff98c6b45fb62a8a2161f78b6534d8de9', 1, 50, 39, 37, 66, NULL, NULL, NULL),
(79, '98a3ab7c340e8a033e7b37b6ef9428751581760af67bbab2b9e05d4964a8874a', 1, 52, 39, 36, 66, NULL, NULL, NULL),
(80, '48449a14a4ff7d79bb7a1b6f3d488eba397c36ef25634c111b49baf362511afc', 1, 52, 39, 37, 66, NULL, NULL, NULL),
(81, '5316ca1c5ddca8e6ceccfce58f3b8540e540ee22f6180fb89492904051b3d531', 1, 32, 39, 38, 66, NULL, NULL, NULL),
(82, 'a46e37632fa6ca51a13fe39a567b3c23b28c2f47d8af6be9bd63e030e214ba38', 1, 32, 39, 39, 66, NULL, NULL, NULL),
(83, 'bbb965ab0c80d6538cf2184babad2a564a010376712012bd07b0af92dcd3097d', 1, 42, 39, 38, 66, 'true', '2015-05-03 21:42:06', NULL),
(84, '44c8031cb036a7350d8b9b8603af662a4b9cdbd2f96e8d5de5af435c9c35da69', 1, 42, 39, 39, 66, 'true', '2015-05-03 21:47:25', NULL),
(85, 'b4944c6ff08dc6f43da2e9c824669b7d927dd1fa976fadc7b456881f51bf5ccc', 1, 44, 39, 38, 66, NULL, NULL, NULL),
(86, '434c9b5ae514646bbd91b50032ca579efec8f22bf0b4aac12e65997c418e0dd6', 1, 44, 39, 39, 66, NULL, NULL, NULL),
(87, 'bdd2d3af3a5a1213497d4f1f7bfcda898274fe9cb5401bbc0190885664708fc2', 1, 45, 39, 38, 66, NULL, NULL, NULL),
(88, '8b940be7fb78aaa6b6567dd7a3987996947460df1c668e698eb92ca77e425349', 1, 45, 39, 39, 66, NULL, NULL, NULL),
(89, 'cd70bea023f752a0564abb6ed08d42c1440f2e33e29914e55e0be1595e24f45a', 1, 47, 39, 38, 66, 'true', '2015-05-03 22:39:42', NULL),
(90, '69f59c273b6e669ac32a6dd5e1b2cb63333d8b004f9696447aee2d422ce63763', 1, 47, 39, 39, 66, 'true', '2015-05-03 22:01:27', NULL),
(91, '1da51b8d8ff98f6a48f80ae79fe3ca6c26e1abb7b7d125259255d6d2b875ea08', 1, 48, 39, 38, 66, 'true', '2015-05-03 22:10:50', NULL),
(92, '8241649609f88ccd2a0a5b233a07a538ec313ff6adf695aa44a969dbca39f67d', 1, 48, 39, 39, 66, 'true', '2015-05-03 22:14:14', NULL),
(93, '6e4001871c0cf27c7634ef1dc478408f642410fd3a444e2a88e301f5c4a35a4d', 1, 49, 39, 38, 66, 'true', '2015-05-03 19:30:12', NULL),
(94, 'e3d6c4d4599e00882384ca981ee287ed961fa5f3828e2adb5e9ea890ab0d0525', 1, 49, 39, 39, 66, 'true', '2015-05-03 17:01:04', NULL),
(95, 'ad48ff99415b2f007dc35b7eb553fd1eb35ebfa2f2f308acd9488eeb86f71fa8', 1, 50, 39, 38, 66, NULL, NULL, NULL),
(96, '7b1a278f5abe8e9da907fc9c29dfd432d60dc76e17b0fabab659d2a508bc65c4', 1, 50, 39, 39, 66, NULL, NULL, NULL),
(97, 'd6d824abba4afde81129c71dea75b8100e96338da5f416d2f69088f1960cb091', 1, 52, 39, 38, 66, NULL, NULL, NULL),
(98, '29db0c6782dbd5000559ef4d9e953e300e2b479eed26d887ef3f92b921c06a67', 1, 52, 39, 39, 66, NULL, NULL, NULL),
(99, '8c1f1046219ddd216a023f792356ddf127fce372a72ec9b4cdac989ee5b0b455', 1, 32, 39, 40, 66, NULL, NULL, NULL),
(100, 'ad57366865126e55649ecb23ae1d48887544976efea46a48eb5d85a6eeb4d306', 1, 32, 39, 41, 66, NULL, NULL, NULL),
(101, '16dc368a89b428b2485484313ba67a3912ca03f2b2b42429174a4f8b3dc84e44', 1, 42, 39, 40, 66, 'true', '2015-05-03 21:57:10', NULL),
(102, '37834f2f25762f23e1f74a531cbe445db73d6765ebe60878a7dfbecd7d4af6e1', 1, 42, 39, 41, 66, 'true', '2015-05-03 22:01:20', NULL),
(103, '454f63ac30c8322997ef025edff6abd23e0dbe7b8a3d5126a894e4a168c1b59b', 1, 44, 39, 40, 66, NULL, NULL, NULL),
(104, '5ef6fdf32513aa7cd11f72beccf132b9224d33f271471fff402742887a171edf', 1, 44, 39, 41, 66, NULL, NULL, NULL),
(105, '1253e9373e781b7500266caa55150e08e210bc8cd8cc70d89985e3600155e860', 1, 45, 39, 40, 66, NULL, NULL, NULL),
(106, '482d9673cfee5de391f97fde4d1c84f9f8d6f2cf0784fcffb958b4032de7236c', 1, 45, 39, 41, 66, NULL, NULL, NULL),
(107, '3346f2bbf6c34bd2dbe28bd1bb657d0e9c37392a1d5ec9929e6a5df4763ddc2d', 1, 47, 39, 40, 66, 'true', '2015-05-03 21:33:25', NULL),
(108, '9537f32ec7599e1ae953af6c9f929fe747ff9dadf79a9beff1f304c550173011', 1, 47, 39, 41, 66, NULL, NULL, NULL),
(109, '0fd42b3f73c448b34940b339f87d07adf116b05c0227aad72e8f0ee90533e699', 1, 48, 39, 40, 66, 'true', '2015-05-03 22:17:37', NULL),
(110, '9bdb2af6799204a299c603994b8e400e4b1fd625efdb74066cc869fee42c9df3', 1, 48, 39, 41, 66, 'true', '2015-05-03 22:22:10', NULL),
(111, 'f6e0a1e2ac41945a9aa7ff8a8aaa0cebc12a3bcc981a929ad5cf810a090e11ae', 1, 49, 39, 40, 66, 'true', '2015-05-03 19:51:12', NULL),
(112, 'b1556dea32e9d0cdbfed038fd7787275775ea40939c146a64e205bcb349ad02f', 1, 49, 39, 41, 66, 'true', '2015-05-03 20:38:32', NULL),
(113, '6c658ee83fb7e812482494f3e416a876f63f418a0b8a1f5e76d47ee4177035cb', 1, 50, 39, 40, 66, NULL, NULL, NULL),
(114, '9f1f9dce319c4700ef28ec8c53bd3cc8e6abe64c68385479ab89215806a5bdd6', 1, 50, 39, 41, 66, NULL, NULL, NULL),
(115, '28dae7c8bde2f3ca608f86d0e16a214dee74c74bee011cdfdd46bc04b655bc14', 1, 52, 39, 40, 66, NULL, NULL, NULL),
(116, 'e5b861a6d8a966dfca7e7341cd3eb6be9901688d547a72ebed0b1f5e14f3d08d', 1, 52, 39, 41, 66, NULL, NULL, NULL),
(117, '2ac878b0e2180616993b4b6aa71e61166fdc86c28d47e359d0ee537eb11d46d3', 1, 32, 39, 36, 67, NULL, NULL, NULL),
(118, '85daaf6f7055cd5736287faed9603d712920092c4f8fd0097ec3b650bf27530e', 1, 42, 39, 36, 67, 'true', '2015-05-10 19:17:29', NULL),
(119, '3038bfb575bee6a0e61945eff8784835bb2c720634e42734678c083994b7f018', 1, 44, 39, 36, 67, NULL, NULL, NULL),
(120, '2abaca4911e68fa9bfbf3482ee797fd5b9045b841fdff7253557c5fe15de6477', 1, 45, 39, 36, 67, NULL, NULL, NULL),
(121, '89aa1e580023722db67646e8149eb246c748e180e34a1cf679ab0b41a416d904', 1, 47, 39, 36, 67, 'true', '2015-05-10 22:56:56', NULL),
(122, '1be00341082e25c4e251ca6713e767f7131a2823b0052caf9c9b006ec512f6cb', 1, 48, 39, 36, 67, 'true', '2015-05-11 12:02:57', NULL),
(123, 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', 1, 49, 39, 36, 67, 'true', '2015-05-11 09:02:42', NULL),
(124, '6affdae3b3c1aa6aa7689e9b6a7b3225a636aa1ac0025f490cca1285ceaf1487', 1, 50, 39, 36, 67, NULL, NULL, NULL),
(125, '0f8ef3377b30fc47f96b48247f463a726a802f62f3faa03d56403751d2f66c67', 1, 52, 39, 36, 67, NULL, NULL, NULL),
(126, '65a699905c02619370bcf9207f5a477c3d67130ca71ec6f750e07fe8d510b084', 1, 32, 39, 37, 67, NULL, NULL, NULL),
(127, '922c7954216ccfe7a61def609305ce1dc7c67e225f873f256d30d7a8ee4f404c', 1, 42, 39, 37, 67, 'true', '2015-05-10 19:36:10', NULL),
(128, '2747b7c718564ba5f066f0523b03e17f6a496b06851333d2d59ab6d863225848', 1, 44, 39, 37, 67, NULL, NULL, NULL),
(129, '6566230e3a3ce3774c1bbc7c18b590ae0f457bbcd511e90e3e7dca2a02e7addc', 1, 45, 39, 37, 67, NULL, NULL, NULL),
(130, '38d66d9692ac590000a91b03a88da1c88d51fab2b78f63171f553ecc551a0c6f', 1, 47, 39, 37, 67, 'true', '2015-05-11 00:09:17', NULL),
(131, 'eeca91fd439b6d5e827e8fda7fee35046f2def93508637483f6be8a2df7a4392', 1, 48, 39, 37, 67, 'true', '2015-05-11 12:12:21', NULL),
(132, 'dbb1ded63bc70732626c5dfe6c7f50ced3d560e970f30b15335ac290358748f6', 1, 49, 39, 37, 67, 'true', '2015-05-11 09:16:35', NULL),
(133, 'd2f483672c0239f6d7dd3c9ecee6deacbcd59185855625902a8b1c1a3bd67440', 1, 50, 39, 37, 67, NULL, NULL, NULL),
(134, '5d389f5e2e34c6b0bad96581c22cee0be36dcf627cd73af4d4cccacd9ef40cc3', 1, 52, 39, 37, 67, NULL, NULL, NULL),
(135, '13671077b66a29874a2578b5240319092ef2a1043228e433e9b006b5e53e7513', 1, 32, 39, 37, 66, NULL, NULL, NULL),
(136, '36ebe205bcdfc499a25e6923f4450fa8d48196ceb4fa0ce077d9d8ec4a36926d', 1, 42, 39, 37, 66, NULL, NULL, NULL),
(137, 'd80eae6e96d148b3b2abbbc6760077b66c4ea071f847dab573d507a32c4d99a5', 1, 45, 39, 37, 66, NULL, NULL, NULL),
(138, 'd6a4031733610bb080d0bfa794fcc9dbdcff74834aeaab7c6b927e21e9754037', 1, 47, 39, 37, 66, NULL, NULL, NULL),
(139, '8d27ba37c5d810106b55f3fd6cdb35842007e88754184bfc0e6035f9bcede633', 1, 48, 39, 37, 66, NULL, NULL, NULL),
(140, 'dbae772db29058a88f9bd830e957c695347c41b6162a7eb9a9ea13def34be56b', 1, 49, 39, 37, 66, NULL, NULL, NULL),
(141, '2c7d5490e6050836f8f2f0d496b1c8d6a38d4ffac2b898e6e77751bdcd20ebf5', 1, 50, 39, 37, 66, NULL, NULL, NULL),
(142, 'd4ee9f58e5860574ca98e3b4839391e7a356328d4bd6afecefc2381df5f5b41b', 1, 52, 39, 37, 66, NULL, NULL, NULL),
(143, 'd6f0c71ef0c88e45e4b3a2118fcb83b0def392d759c901e9d755d0e879028727', 1, 32, 39, 36, 68, NULL, NULL, NULL),
(144, '5ec1a0c99d428601ce42b407ae9c675e0836a8ba591c8ca6e2a2cf5563d97ff0', 1, 32, 39, 37, 68, NULL, NULL, NULL),
(145, 'be47addbcb8f60566a3d7fd5a36f8195798e2848b368195d9a5d20e007c59a0c', 1, 42, 39, 36, 68, 'true', '2015-05-17 21:40:08', NULL),
(146, '0a5b046d07f6f971b7776de682f57c5b9cdc8fa060db7ef59de82e721c8098f4', 1, 42, 39, 37, 68, 'true', '2015-05-17 21:45:39', NULL),
(147, '1d28c120568c10e19b9d8abe8b66d0983fa3d2e11ee7751aca50f83c6f4a43aa', 1, 45, 39, 36, 68, NULL, NULL, NULL),
(148, 'ec2e990b934dde55cb87300629cedfc21b15cd28bbcf77d8bbdc55359d7689da', 1, 45, 39, 37, 68, NULL, NULL, NULL),
(149, '05ada863a4cf9660fd8c68e2295f1d35b2264815f5b605003d6625bd9e0492cf', 1, 47, 39, 36, 68, 'true', '2015-05-18 11:05:21', NULL),
(150, '9ae2bdd7beedc2e766c6b76585530e16925115707dc7a06ab5ee4aa2776b2c7b', 1, 47, 39, 37, 68, 'true', '2015-05-18 11:10:00', NULL),
(151, '8e612bd1f5d132a339575b8dafb7842c64614e56bcf3d5ab65a0bc4b34329407', 1, 48, 39, 36, 68, 'true', '2015-05-18 08:47:40', NULL),
(152, '043066daf2109523a7490d4bfad4766da5719950a2b5f96d192fc0537e84f32a', 1, 48, 39, 37, 68, 'true', '2015-05-18 08:53:03', NULL),
(153, '620c9c332101a5bae955c66ae72268fbcd3972766179522c8deede6a249addb7', 1, 49, 39, 36, 68, 'true', '2015-05-18 10:53:44', NULL),
(154, '1d0ebea552eb43d0b1e1561f6de8ae92e3de7f1abec52399244d1caed7dbdfa6', 1, 49, 39, 37, 68, 'true', '2015-05-18 10:43:17', NULL),
(155, '210e3b160c355818509425b9d9e9fd3ea2e287f2c43a13e5be8817140db0b9e6', 1, 50, 39, 36, 68, NULL, NULL, NULL),
(156, '0fecf9247f3ddc84db8a804fa3065c013baf6b7c2458c2ba2bf56c2e1d42ddd4', 1, 50, 39, 37, 68, 'true', '2015-05-18 12:13:13', NULL),
(157, 'c75de23d89df36ba921287616ee8edb4c986e328a78e033e57c1e5e2b59c838e', 1, 52, 39, 36, 68, NULL, NULL, NULL),
(158, '7ed8f0f3b707956d9fb1e889e11153e0aa0a854983081d262fbe5eede32da7ca', 1, 52, 39, 37, 68, NULL, NULL, NULL),
(159, 'ff2ccb6ba423d356bd549ed4bfb76e96976a0dcde05a09996a1cdb9f83422ec4', 1, 42, 39, 36, 69, 'true', '2015-09-21 07:52:01', NULL),
(160, 'a512db2741cd20693e4b16f19891e72b9ff12cead72761fc5e92d2aaf34740c1', 1, 42, 39, 37, 69, 'true', '2015-09-22 08:18:03', NULL),
(161, 'bb668ca95563216088b98a62557fa1e26802563f3919ac78ae30533bb9ed422c', 1, 42, 39, 37, 69, NULL, NULL, NULL),
(162, '79d6eaa2676189eb927f2e16a70091474078e2117c3fc607d35cdc6b591ef355', 1, 42, 39, 48, 69, 'true', '2015-09-23 09:23:00', NULL),
(163, '3d3286f7cd19074f04e514b0c6c237e757513fb32820698b790e1dec801d947a', 1, 42, 39, 40, 69, 'true', '2015-09-23 10:34:00', NULL),
(164, '3f9807cb9ae9fb6c30942af6139909d27753a5e03fe5a5c6e93b014f5b17366f', 1, 42, 39, 41, 69, 'true', '2015-09-25 19:50:29', NULL),
(165, 'bc52dd634277c4a34a2d6210994a9a5e2ab6d33bb4a3a8963410e00ca6c15a02', 1, 42, 39, 50, 69, 'true', '2015-09-30 09:39:39', NULL),
(166, 'e0f05da93a0f5a86a3be5fc0e301606513c9f7e59dac2357348aa0f2f47db984', 1, 42, 39, 49, 69, 'true', '2015-09-28 10:30:08', NULL),
(167, '73d3f1ba062585bce51f77d70a26be88c44b55d70f81b8bd7e2ded030ca4454a', 1, 42, 39, 51, 69, 'true', '2015-09-30 08:37:31', NULL),
(168, '80c3cd40fa35f9088b8741bd8be6153de05f661cfeeb4625ffbf5f4a6c3c02c4', 1, 47, 39, 36, 69, 'true', '2015-09-22 22:01:01', NULL),
(169, 'f57e5cb1f4532c008183057ecc94283801fcb5afe2d1c190e3dfd38c4da08042', 1, 47, 39, 37, 69, 'true', '2015-09-22 22:49:02', NULL),
(170, '734d0759cdb4e0d0a35e4fd73749aee287e4fdcc8648b71a8d6ed591b7d4cb3f', 1, 47, 39, 48, 69, 'true', '2015-09-22 21:16:29', NULL),
(171, '284de502c9847342318c17d474733ef468fbdbe252cddf6e4b4be0676706d9d0', 1, 47, 39, 40, 69, 'true', '2015-09-28 15:51:20', NULL),
(172, '68519a9eca55c68c72658a2a1716aac3788c289859d46d6f5c3f14760fa37c9e', 1, 47, 39, 41, 69, 'true', '2015-10-18 13:10:30', NULL),
(173, '4a8596a7790b5ca9e067da401c018b3206befbcf95c38121854d1a0158e7678a', 1, 47, 39, 50, 69, 'true', '2015-10-01 22:17:56', NULL),
(174, '41e521adf8ae7a0f419ee06e1d9fb794162369237b46f64bf5b2b9969b0bcd2e', 1, 47, 39, 49, 69, 'true', '2015-09-21 22:14:30', NULL),
(175, 'dac53c17c250fd4d4d81eaf6d88435676dac1f3f3896441e277af839bf50ed8a', 1, 47, 39, 51, 69, 'true', '2015-09-29 10:04:14', NULL),
(176, 'cba28b89eb859497f544956d64cf2ecf29b76fe2ef7175b33ea59e64293a4461', 1, 49, 39, 36, 69, 'true', '2015-09-23 14:50:41', NULL),
(177, '8cd2510271575d8430c05368315a87b9c4784c7389a47496080c1e615a2a00b6', 1, 49, 39, 37, 69, 'true', '2015-09-23 12:19:46', NULL),
(178, '01d54579da446ae1e75cda808cd188438834fa6249b151269db0f9123c9ddc61', 1, 49, 39, 48, 69, 'true', '2015-09-23 16:32:43', NULL),
(179, '3068430da9e4b7a674184035643d9e19af3dc7483e31cc03b35f75268401df77', 1, 49, 39, 40, 69, 'true', '2015-09-26 21:41:43', NULL),
(180, '7b69759630f869f2723875f873935fed29d2d12b10ef763c1c33b8e0004cb405', 1, 49, 39, 41, 69, 'true', '2015-09-26 23:11:35', NULL),
(181, '580811fa95269f3ecd4f22d176e079d36093573680b6ef66fa341e687a15b5da', 1, 49, 39, 50, 69, 'true', '2015-10-01 17:05:16', NULL),
(182, 'bfa7634640c53da7cb5e9c39031128c4e583399f936896f27f999f1d58d7b37e', 1, 49, 39, 49, 69, 'true', '2015-09-29 11:15:05', NULL),
(183, 'b8aed072d29403ece56ae9641638ddd50d420f950bde0eefc092ee8879554141', 1, 49, 39, 51, 69, 'true', '2015-10-01 14:06:22', NULL),
(184, '52f11620e397f867b7d9f19e48caeb64658356a6b5d17138c00dd9feaf5d7ad6', 1, 50, 39, 36, 69, 'true', '2015-09-23 23:07:18', NULL),
(185, '61a229bae1e90331edd986b6bbbe617f7035de88a5bf7c018c3add6c762a6e8d', 1, 50, 39, 37, 69, 'true', '2015-09-23 17:33:21', NULL),
(186, '2811745d7b8d8874f6e653d176cefdd19e05e920ce389b9b7e83e5b2dfa546c7', 1, 50, 39, 48, 69, 'true', '2015-09-23 18:05:45', NULL),
(187, '38b2d03f3256502b1e9db02b2d12aa27a46033ffe6d8c0ef0f2cf6b1530be9d8', 1, 50, 39, 40, 69, NULL, NULL, NULL),
(188, 'd6061bbee6cf13bd73765faaea7cdd0af1323e4b125342ac346047f7c4bda1fc', 1, 50, 39, 41, 69, 'true', '2015-09-23 17:46:39', NULL),
(189, '7045d16ae7f043ec25774a0a85d6f479e5bb019e9c5a1584bc76736d116b8f33', 1, 50, 39, 50, 69, 'true', '2015-09-22 20:47:23', NULL),
(190, '2397346b45823e070f6fc72ac94c0a999d234c472479f0e26b30cdf5942db854', 1, 50, 39, 49, 69, 'true', '2015-09-23 17:58:20', NULL),
(191, '70260742c2952154c84e2ea9f68b1a7397f49b6d343da1ed284093c0bd72c742', 1, 50, 39, 51, 69, 'true', '2015-09-23 23:13:13', NULL),
(192, 'eb3be230bbd2844b1f5d8f2e4fab9ffba8ab22cfeeb69c4c1361993ba4f377b9', 1, 32, 39, 36, 69, NULL, NULL, NULL),
(193, '684fe39f03758de6a882ae61fa62312b67e5b1e665928cbf3dc3d8f4f53e3562', 1, 32, 39, 37, 69, NULL, NULL, NULL),
(194, '7559ca4a957c8c82ba04781cd66a68d6022229fca0e8e88d8e487c96ee4446d0', 1, 32, 39, 48, 69, NULL, NULL, NULL),
(195, '1dfacb2ea5a03e0a915999e03b5a56196f1b1664d2f768d1b7eff60ac059789d', 1, 32, 39, 40, 69, NULL, NULL, NULL),
(196, 'b4bbe448fde336bb6a7d7d765f36d3327c772b845e7b54c8282aa08c9775ddd7', 1, 32, 39, 41, 69, NULL, NULL, NULL),
(197, '8bcbb4c131df56f7c79066016241cc4bdf4e58db55c4f674e88b22365bd2e2ad', 1, 32, 39, 50, 69, NULL, NULL, NULL),
(198, 'a4e00d7e6aa82111575438c5e5d3e63269d4c475c718b2389f6d02932c47f8a6', 1, 32, 39, 49, 69, NULL, NULL, NULL),
(199, '5a39cadd1b007093db50744797c7a04a34f73b35ed444704206705b02597d6fd', 1, 32, 39, 51, 69, NULL, NULL, NULL),
(200, '27badc983df1780b60c2b3fa9d3a19a00e46aac798451f0febdca52920faaddf', 1, 54, 39, 46, 69, 'true', '2016-01-18 18:50:05', 'tedsrate.rep/rater.php?asid=27badc983df1780b60c2b3fa9d3a19a00e46aac798451f0febdca52920faaddf'),
(201, '43974ed74066b207c30ffd0fed5146762e6c60745ac977004bc14507c7c42b50', 1, 54, 39, 36, 69, NULL, NULL, 'tedsrate.rep/rater.php?asid=43974ed74066b207c30ffd0fed5146762e6c60745ac977004bc14507c7c42b50'),
(202, 'c17edaae86e4016a583e098582f6dbf3eccade8ef83747df9ba617ded9d31309', 1, 54, 39, 51, 69, NULL, NULL, 'tedsrate.rep/rater.php?&asid=c17edaae86e4016a583e098582f6dbf3eccade8ef83747df9ba617ded9d31309'),
(203, '4621c1d55fa4e86ce0dae4288302641baac86dd53f76227c892df9d300682d41', 1, 54, 39, 37, 69, NULL, NULL, 'tedsrate.rep/rater.php?&asid=4621c1d55fa4e86ce0dae4288302641baac86dd53f76227c892df9d300682d41'),
(204, 'fc56dbc6d4652b315b86b71c8d688c1ccdea9c5f1fd07763d2659fde2e2fc49a', 1, 54, 39, 41, 69, NULL, NULL, 'tedsrate.rep/rater.php?&asid=fc56dbc6d4652b315b86b71c8d688c1ccdea9c5f1fd07763d2659fde2e2fc49a'),
(205, 'f8809aff4d69bece79dabe35be0c708b890d7eafb841f121330667b77d2e2590', 1, 54, 39, 42, 69, NULL, NULL, 'tedsrate.rep/rater.php?&asid=f8809aff4d69bece79dabe35be0c708b890d7eafb841f121330667b77d2e2590'),
(206, '5cf4e26bd3d87da5e03f80a43a64f1220a1f4ba9e1d6348caea83c06353c3f39', 1, 54, 39, 39, 69, NULL, NULL, 'tedsrate.rep/rater.php?&asid=5cf4e26bd3d87da5e03f80a43a64f1220a1f4ba9e1d6348caea83c06353c3f39'),
(207, '968076be2e38cf897d4d6cea3faca9c037e1a4e3b4b7744fb2533e07751bd30a', 1, 54, 39, 40, 69, NULL, NULL, 'tedsrate.rep/rater.php?&asid=968076be2e38cf897d4d6cea3faca9c037e1a4e3b4b7744fb2533e07751bd30a'),
(208, '8df66f64b57424391d363fd6b811fed3c430c77597da265025728bd637bad804', 2, 54, 39, 41, 69, NULL, NULL, 'tedsrate.rep/rater.php?&asid=8df66f64b57424391d363fd6b811fed3c430c77597da265025728bd637bad804');

-- --------------------------------------------------------

--
-- Table structure for table `attribute`
--

CREATE TABLE IF NOT EXISTS `attribute` (
  `attributeID` int(11) NOT NULL,
  `attributeName` varchar(255) DEFAULT NULL,
  `criterionID` int(11) DEFAULT NULL,
  `languageID` int(11) NOT NULL,
  `attributeDesc` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attribute`
--

INSERT INTO `attribute` (`attributeID`, `attributeName`, `criterionID`, `languageID`, `attributeDesc`) VALUES
(7, 'Browsing/browsability/searchability', 1, 5, 'Human actor-expected capability of an IS/IT artifact to scan an information neighborhood with the probability that the human actor will serendipitously find information of value. (Taylor, 1986, p. 70)'),
(8, 'Formatting/Presentation', 1, 5, 'The physical presentation and arrangement of data/information in ways that allow more efficient scanning and hence extraction of items of interest from the store. (Taylor, 1986, p. 70). Presenting and arranging information to facilitate scanning and selecting (Choo, 2002) on part of a human actor.'),
(9, 'Mediation', 1, 5, 'The means used to assist human actors in getting answers from the IS/IT artifact. (Taylor, 1986, p. 70). The act or process of intervening between the IS/IT artifact and the human actor to promote reconciliation or understanding, that is, the intermediation between the functions and characteristics of the IS/IT artifact and the human actor.'),
(10, 'Orientation', 1, 5, 'The means used to help human actors understand and to gain experience with the IS/IT artifact and its complexities. (Taylor, 1986, p. 70). The ease with which a human actor can orient himself/herself regarding the intended or emerging utilization of the IS/IT artifact at hand; that is, the IS/IT artifact gives clear clues to where the human actor can find information or functions such as intuitive navigation, breadcrumbs, and other visual and audio clues.'),
(11, 'Order/Consistency', 1, 5, 'The value-added by initially dividing or organizing a body of subject matter by some form of gross ordering, such as alphabetization, or large groupings. (Taylor, 1986, p. 70). Orderly and systematic arrangement of IS/IT artifact components and elements such that a human actor can easily identify information and functionality provided by the IS/IT artifact. Also, the fashion through which the IS/IT artifact transforms and presents itself based on human action.'),
(12, 'Accessibility', 1, 5, 'The processes of making access to information stores easier in a physical sense. (Taylor, 1986, p. 70). Ease of physical access to an IS/IT artifact and to the information and functionality that the IS/IT artifact can provide to human actors (including to impaired human actors). Also, how many, or how few, steps does the human actor need (for example, in terms of clicks) to reach the desired information or functionality.'),
(13, 'Simplicity', 1, 5, 'Lack of complexity, complication, or difficulty when a human actor interacts with and operates an IS/IT artifact. '),
(14, 'Item Identification', 6, 5, 'The value achieved by the identification of any information chunk or discrete piece of data by systematic physical description and location information (Taylor, 1986, p. 69). A humanactor- oriented comprehensive description of the physical and functional characteristics as well as the location of a unit of information or of an IS/IT artifact.'),
(15, 'Subject description/classification/controlled vocabulary', 6, 5, 'The provision of a subject description through access points such as index terms, descriptors, and names (Taylor, 1986, p. 70). Human-actor-oriented interpretive layer, which helps describe, arrange into groups, and classify information or physical and functional characteristics of an IS/IT artifact; that also includes that a human actor can easily identify a source/piece of information or an IS/IT artifact characteristic by its classification.'),
(16, 'Subject Summary', 6, 5, 'The result of processes which reduce or compress large amounts of information into compact items, such as executive summaries, abstracts, terse conclusions, chemical structure diagrams, mathematical formulae, graphs, or charts (Taylor, 1986, p. 70). A brief summary or abstract of information or of the physical and functional characteristics of an IS/IT artifact.'),
(17, 'Linkage / Referral', 6, 5, 'The value-added by providing pointers and links to items, sources, and systems external to the IS/IT artifact in use, thus expanding the human actors information options (Taylor, 1986, p. 70).'),
(18, 'Precision/(relevant retrieved) over (retrieved)', 6, 5, 'The capability of an IS/IT artifact to aid human actors in finding exactly what they want, by providing signals on such attributes as language, data aggregation, sophistication level, or by ranking output (Taylor, 1986, p. 70). The capacity of a source/piece of information or the physical or functional characteristics of an IS/IT artifact to provide a human actor exactly with what she or he expects and needs. '),
(19, 'Selectivity', 6, 5, 'The value-added when choices are made at the input point of the IS/IT artifact, choices based on the appropriateness and merit of information chunks to the client population served (Taylor, 1986, p. 70). The number and the nature of choices a human actor encounters and chooses from when she or he uses a source/piece of information or an IS/IT artifact.'),
(20, 'Order', 6, 5, 'Parsimony, noise-reduced structure, and absence of distraction that a human actor encounters when she or he uses a source/piece of information or an IS/IT artifact.'),
(21, 'Novelty', 6, 5, 'The extent of originality and newness relative to a human actors needs that she or he encounters when using a source/piece of information or an IS/IT artifact, that is, the balance of maintaining currency and overloading the human actor.'),
(22, 'Accuracy', 3, 5, 'The value-added by system processes that assures error-free transfer of data and information as it flows through the IS/IT artifact and is eventually displayed to a human actor (Taylor, 1986, p. 70). Exactness of a piece of information and its conformity to the original source as well as the exact and unaltered transfer and presentation of a piece of information to a human actor through an IS/IT artifact.'),
(23, 'Comprehensiveness', 3, 5, 'The value-added by the completeness of coverage of a particular subject or of a particular form of information (Taylor, 1986, p. 70). Complete and broad coverage of a particular subject, in which a human actor is interested, provided by a source/piece of information or an IS/IT artifact.'),
(24, 'Currency', 3, 5, 'The value-added (a) by the recency of the data acquired by the system; and (b) by the capability of the IS/IT artifact to reflect current modes of thinkingin its structure, organization, and access vocabularies (Taylor, 1986, p. 70). The recency of a source/piece of information sought and the capability of an IS/IT artifact to reflect and represent current modes of understanding of the sought subject matter to a human actor.'),
(25, 'Reliability', 3, 5, 'The value-added by the trust an IS/IT artifact inspires in human actors by its consistency of quality performance over time (Taylor, 1986, p. 70). The suitability and dependability of a source/piece of information or of an IS/IT artifact that a human actor consistently experiences.'),
(26, 'Validity', 3, 5, 'The value-added when the IS/IT artifact provides signals about the degree to which data or information presented to human actors can be judged as sound (Taylor, 1986, p. 70). The quality of a source/piece of information or of an IS/IT artifact to be assessed as sound, justifiable, well-grounded, and logically correct by a human actor.'),
(27, 'Authority', 3, 5, 'The extent of credibility and reputation human actors attribute to the human, technical, or institutional sources/pieces of information or to an IS/IT artifact.'),
(28, 'Contextuality/closeness to problem', 2, 5, 'The value-added by the activities of the system, usually through human intervention, to meet the specific needs of a person in a particular environment with a particular problem; this implies knowledge of that persons style, bias, idiosyncrasies, and sophistication, as well as the politics and constraints of the context (Taylor, 1986, p. 70). The extent to which a source/piece of information or an IS/IT artifact matches a human actors specific informational or transactional needs, also with respect to that human actors specific location when stationary or on the move.'),
(29, 'Flexibility', 2, 5, 'The capability of an IS/IT artifact to provide a variety of ways and approaches of working dynamically with the data/information in a file (Taylor, 1986, p. 70). The dynamic adjustment of information or an IS/IT artifact to a human actors changing informational or transactional needs.'),
(30, 'Simplicity', 2, 5, 'The value achieved by presenting the most clear and lucid (explanation, data, hypothesis, or method) among several within quality and validity limits (Taylor, 1986, p. 70). The lack of complication or difficulty with which information or an IS/IT artifact adjust to a human actors changing informational and transactional needs.'),
(31, 'Transaction', 2, 5, 'The capacity of an IS/IT artifact to immediately and on a per-demand basis cope with a human-actors transactional need without referral or deferral (a transaction might include the purchasing of goods and services, electronic or nonelectronic).'),
(32, 'Trust', 2, 5, 'An individual human actors willingness to consider a source/piece of information or an IS/IT artifact trustworthy and to act accordingly based on accumulated experience or other clues such as certificates, ratings, or reviews.'),
(33, 'Feedback', 2, 5, 'The capacity of a source/piece of information or an IS/IT artifact to entertain, receive feedback from and display feedback to human actors who utilize and assess that source/piece of information or that IS/IT artifact.'),
(34, 'Community/social networking', 2, 5, 'The capacity of a source/piece of information or an IS/IT artifact to help human actors form a community or social network, electronically or nonelectronically, around a set of shared interests.'),
(35, 'Individualization', 2, 5, 'The capacity of a source/piece of information or an IS/IT artifact to adjust to an individual human-actors specific needs. Two forms of individualization are distinguished: (a) Static or basic individualization, for example, based on a human actors preset preferences and selections including push-updates of information; (b) advanced or dynamic individualization where specific human-actors needs are addressed as those that emerge through utilization and relative to the changing contexts over time (including the inference of changes in human-actors preferences, ambiences, or search patterns as well as suggesting the selection of information or the potential utilization of an IS/IT artifact).'),
(36, 'Localization', 2, 5, 'The extent to which a source/piece of information or an IS/IT artifact is sensitive to or reflective of differences in physical measures and metrics, time zones, languages, cultural, and other differences in real time relative to a human-actors specific needs.'),
(37, 'Privacy', 2, 5, 'A human actors right to be left alone (Warren & Brandeis, 1890) and to be or remain apart from company, observation, tracking, and recording of activities and selections when utilizing a source/ piece of information or an IS/IT artifact.'),
(38, 'Cost savings', 4, 5, 'The value achieved by conscious IS/IT artifact design and operating decisions that save dollars for the human actor (Taylor, 1986, p. 70). The extent to which a human actor can cut real cost when utilizing a source/piece of information or an IS/IT artifact.'),
(39, 'Time savings', 4, 5, 'The perceived value of an IS/IT artifact based on the speed of its response time (Taylor, 1986, p. 70). The extent to which a human actor can save time when utilizing a source/piece of information or an IS/IT artifact.'),
(40, 'Security', 4, 5, 'The extent to which a source/piece of information or an IS/IT artifact provides safeguards against fraud and intrusion such that a human actor can feel secure, protected, and free of anxiety when utilizing a source/piece of information or an IS/IT artifact.'),
(41, 'Safety', 4, 5, 'The extent to which a source/piece of information or an IS/IT artifact provides safeguards against the risk of hurt, injury, loss, or danger such that a human actor can feel safe when utilizing a source/piece of information or an IS/IT artifact.'),
(42, 'Aesthetics', 5, 5, 'The extent to which a human actor appreciates the appearance and perceived beauty of presentation when using a source/piece of information or an IS/IT artifact.'),
(43, 'Entertainment', 5, 5, 'The extent to which a human actor appreciates the perceived amusement and diversion of presentation and interaction when using a source/piece of information or an IS/IT artifact.'),
(44, 'Engagement', 5, 5, 'The extent to which a human actor appreciates the attractiveness of presentation and the appeal of interaction when using a source/piece of information or an IS/IT artifact.'),
(45, 'Stimulation', 5, 5, 'The extent to which a human actor feels stimulated to act or grow to greater activity when using a source/piece of information or an IS/IT artifact.'),
(46, 'Satisfaction/rewarding/incenting', 5, 5, 'The extent to which a human actor feels personally satisfied, incented, and rewarded when using a source/piece of information or an IS/IT artifact.'),
(59, 'Navigation and Findability', 1, 5, NULL),
(60, 'Structure', 1, 5, NULL),
(61, 'Identity', 6, 5, NULL),
(62, 'Parsimony', 6, 5, NULL),
(63, 'Completeness', 3, 5, NULL),
(64, 'Trustworthiness', 3, 5, NULL),
(65, 'Interaction', 2, 5, NULL),
(66, 'Customization', 2, 5, NULL),
(67, 'Savings', 4, 5, NULL),
(68, 'Confidence', 4, 5, NULL),
(69, 'Attractiveness', 5, 5, NULL),
(70, 'Enjoyment', 5, 5, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `attributeID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`attributeID`) VALUES
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42),
(43),
(44),
(45),
(46);

-- --------------------------------------------------------

--
-- Table structure for table `category_backup`
--

CREATE TABLE IF NOT EXISTS `category_backup` (
  `categoryID` int(11) NOT NULL,
  `categoryName` varchar(255) DEFAULT NULL,
  `criterionID` int(11) DEFAULT NULL,
  `languageID` int(11) NOT NULL,
  `categoryDesc` varchar(5000) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `category_backup`
--

INSERT INTO `category_backup` (`categoryID`, `categoryName`, `criterionID`, `languageID`, `categoryDesc`) VALUES
(7, 'Browsing/browsability/searchability', 1, 5, 'Human actor-expected capability of an IS/IT artifact to scan an information neighborhood with the probability that the human actor will serendipitously find information of value. (Taylor, 1986, p. 70)'),
(8, 'Formatting/Presentation', 1, 5, 'The physical presentation and arrangement of data/information in ways that allow more efficient scanning and hence extraction of items of interest from the store. (Taylor, 1986, p. 70). Presenting and arranging information to facilitate scanning and selecting (Choo, 2002) on part of a human actor.'),
(9, 'Mediation', 1, 5, 'The means used to assist human actors in getting answers from the IS/IT artifact. (Taylor, 1986, p. 70). The act or process of intervening between the IS/IT artifact and the human actor to promote reconciliation or understanding, that is, the intermediation between the functions and characteristics of the IS/IT artifact and the human actor.'),
(10, 'Orientation', 1, 5, 'The means used to help human actors understand and to gain experience with the IS/IT artifact and its complexities. (Taylor, 1986, p. 70). The ease with which a human actor can orient himself/herself regarding the intended or emerging utilization of the IS/IT artifact at hand; that is, the IS/IT artifact gives clear clues to where the human actor can find information or functions such as intuitive navigation, breadcrumbs, and other visual and audio clues.'),
(11, 'Order/Consistency', 1, 5, 'The value-added by initially dividing or organizing a body of subject matter by some form of gross ordering, such as alphabetization, or large groupings. (Taylor, 1986, p. 70). Orderly and systematic arrangement of IS/IT artifact components and elements such that a human actor can easily identify information and functionality provided by the IS/IT artifact. Also, the fashion through which the IS/IT artifact transforms and presents itself based on human action.'),
(12, 'Accessibility', 1, 5, 'The processes of making access to information stores easier in a physical sense. (Taylor, 1986, p. 70). Ease of physical access to an IS/IT artifact and to the information and functionality that the IS/IT artifact can provide to human actors (including to impaired human actors). Also, how many, or how few, steps does the human actor need (for example, in terms of clicks) to reach the desired information or functionality.'),
(13, 'Simplicity', 1, 5, 'Lack of complexity, complication, or difficulty when a human actor interacts with and operates an IS/IT artifact. '),
(14, 'Item Identification', 6, 5, 'The value achieved by the identification of any information chunk or discrete piece of data by systematic physical description and location information (Taylor, 1986, p. 69). A humanactor- oriented comprehensive description of the physical and functional characteristics as well as the location of a unit of information or of an IS/IT artifact.'),
(15, 'Subject description/classification/controlled vocabulary', 6, 5, 'The provision of a subject description through access points such as index terms, descriptors, and names (Taylor, 1986, p. 70). Human-actor-oriented interpretive layer, which helps describe, arrange into groups, and classify information or physical and functional characteristics of an IS/IT artifact; that also includes that a human actor can easily identify a source/piece of information or an IS/IT artifact characteristic by its classification.'),
(16, 'Subject Summary', 6, 5, 'The result of processes which reduce or compress large amounts of information into compact items, such as executive summaries, abstracts, terse conclusions, chemical structure diagrams, mathematical formulae, graphs, or charts (Taylor, 1986, p. 70). A brief summary or abstract of information or of the physical and functional characteristics of an IS/IT artifact.'),
(17, 'Linkage / Referral', 6, 5, 'The value-added by providing pointers and links to items, sources, and systems external to the IS/IT artifact in use, thus expanding the human actors information options (Taylor, 1986, p. 70).'),
(18, 'Precision/(relevant retrieved) over (retrieved)', 6, 5, 'The capability of an IS/IT artifact to aid human actors in finding exactly what they want, by providing signals on such attributes as language, data aggregation, sophistication level, or by ranking output (Taylor, 1986, p. 70). The capacity of a source/piece of information or the physical or functional characteristics of an IS/IT artifact to provide a human actor exactly with what she or he expects and needs. '),
(19, 'Selectivity', 6, 5, 'The value-added when choices are made at the input point of the IS/IT artifact, choices based on the appropriateness and merit of information chunks to the client population served (Taylor, 1986, p. 70). The number and the nature of choices a human actor encounters and chooses from when she or he uses a source/piece of information or an IS/IT artifact.'),
(20, 'Order', 6, 5, 'Parsimony, noise-reduced structure, and absence of distraction that a human actor encounters when she or he uses a source/piece of information or an IS/IT artifact.'),
(21, 'Novelty', 6, 5, 'The extent of originality and newness relative to a human actors needs that she or he encounters when using a source/piece of information or an IS/IT artifact, that is, the balance of maintaining currency and overloading the human actor.'),
(22, 'Accuracy', 3, 5, 'The value-added by system processes that assures error-free transfer of data and information as it flows through the IS/IT artifact and is eventually displayed to a human actor (Taylor, 1986, p. 70). Exactness of a piece of information and its conformity to the original source as well as the exact and unaltered transfer and presentation of a piece of information to a human actor through an IS/IT artifact.'),
(23, 'Comprehensiveness', 3, 5, 'The value-added by the completeness of coverage of a particular subject or of a particular form of information (Taylor, 1986, p. 70). Complete and broad coverage of a particular subject, in which a human actor is interested, provided by a source/piece of information or an IS/IT artifact.'),
(24, 'Currency', 3, 5, 'The value-added (a) by the recency of the data acquired by the system; and (b) by the capability of the IS/IT artifact to reflect current modes of thinkingin its structure, organization, and access vocabularies (Taylor, 1986, p. 70). The recency of a source/piece of information sought and the capability of an IS/IT artifact to reflect and represent current modes of understanding of the sought subject matter to a human actor.'),
(25, 'Reliability', 3, 5, 'The value-added by the trust an IS/IT artifact inspires in human actors by its consistency of quality performance over time (Taylor, 1986, p. 70). The suitability and dependability of a source/piece of information or of an IS/IT artifact that a human actor consistently experiences.'),
(26, 'Validity', 3, 5, 'The value-added when the IS/IT artifact provides signals about the degree to which data or information presented to human actors can be judged as sound (Taylor, 1986, p. 70). The quality of a source/piece of information or of an IS/IT artifact to be assessed as sound, justifiable, well-grounded, and logically correct by a human actor.'),
(27, 'Authority', 3, 5, 'The extent of credibility and reputation human actors attribute to the human, technical, or institutional sources/pieces of information or to an IS/IT artifact.'),
(28, 'Contextuality/closeness to problem', 2, 5, 'The value-added by the activities of the system, usually through human intervention, to meet the specific needs of a person in a particular environment with a particular problem; this implies knowledge of that persons style, bias, idiosyncrasies, and sophistication, as well as the politics and constraints of the context (Taylor, 1986, p. 70). The extent to which a source/piece of information or an IS/IT artifact matches a human actors specific informational or transactional needs, also with respect to that human actors specific location when stationary or on the move.'),
(29, 'Flexibility', 2, 5, 'The capability of an IS/IT artifact to provide a variety of ways and approaches of working dynamically with the data/information in a file (Taylor, 1986, p. 70). The dynamic adjustment of information or an IS/IT artifact to a human actors changing informational or transactional needs.'),
(30, 'Simplicity', 2, 5, 'The value achieved by presenting the most clear and lucid (explanation, data, hypothesis, or method) among several within quality and validity limits (Taylor, 1986, p. 70). The lack of complication or difficulty with which information or an IS/IT artifact adjust to a human actors changing informational and transactional needs.'),
(31, 'Transaction', 2, 5, 'The capacity of an IS/IT artifact to immediately and on a per-demand basis cope with a human-actors transactional need without referral or deferral (a transaction might include the purchasing of goods and services, electronic or nonelectronic).'),
(32, 'Trust', 2, 5, 'An individual human actors willingness to consider a source/piece of information or an IS/IT artifact trustworthy and to act accordingly based on accumulated experience or other clues such as certificates, ratings, or reviews.'),
(33, 'Feedback', 2, 5, 'The capacity of a source/piece of information or an IS/IT artifact to entertain, receive feedback from and display feedback to human actors who utilize and assess that source/piece of information or that IS/IT artifact.'),
(34, 'Community/social networking', 2, 5, 'The capacity of a source/piece of information or an IS/IT artifact to help human actors form a community or social network, electronically or nonelectronically, around a set of shared interests.'),
(35, 'Individualization', 2, 5, 'The capacity of a source/piece of information or an IS/IT artifact to adjust to an individual human-actors specific needs. Two forms of individualization are distinguished: (a) Static or basic individualization, for example, based on a human actors preset preferences and selections including push-updates of information; (b) advanced or dynamic individualization where specific human-actors needs are addressed as those that emerge through utilization and relative to the changing contexts over time (including the inference of changes in human-actors preferences, ambiences, or search patterns as well as suggesting the selection of information or the potential utilization of an IS/IT artifact).'),
(36, 'Localization', 2, 5, 'The extent to which a source/piece of information or an IS/IT artifact is sensitive to or reflective of differences in physical measures and metrics, time zones, languages, cultural, and other differences in real time relative to a human-actors specific needs.'),
(37, 'Privacy', 2, 5, 'A human actors right to be left alone (Warren & Brandeis, 1890) and to be or remain apart from company, observation, tracking, and recording of activities and selections when utilizing a source/ piece of information or an IS/IT artifact.'),
(38, 'Cost savings', 4, 5, 'The value achieved by conscious IS/IT artifact design and operating decisions that save dollars for the human actor (Taylor, 1986, p. 70). The extent to which a human actor can cut real cost when utilizing a source/piece of information or an IS/IT artifact.'),
(39, 'Time savings', 4, 5, 'The perceived value of an IS/IT artifact based on the speed of its response time (Taylor, 1986, p. 70). The extent to which a human actor can save time when utilizing a source/piece of information or an IS/IT artifact.'),
(40, 'Security', 4, 5, 'The extent to which a source/piece of information or an IS/IT artifact provides safeguards against fraud and intrusion such that a human actor can feel secure, protected, and free of anxiety when utilizing a source/piece of information or an IS/IT artifact.'),
(41, 'Safety', 4, 5, 'The extent to which a source/piece of information or an IS/IT artifact provides safeguards against the risk of hurt, injury, loss, or danger such that a human actor can feel safe when utilizing a source/piece of information or an IS/IT artifact.'),
(42, 'Aesthetics', 5, 5, 'The extent to which a human actor appreciates the appearance and perceived beauty of presentation when using a source/piece of information or an IS/IT artifact.'),
(43, 'Entertainment', 5, 5, 'The extent to which a human actor appreciates the perceived amusement and diversion of presentation and interaction when using a source/piece of information or an IS/IT artifact.'),
(44, 'Engagement', 5, 5, 'The extent to which a human actor appreciates the attractiveness of presentation and the appeal of interaction when using a source/piece of information or an IS/IT artifact.'),
(45, 'Stimulation', 5, 5, 'The extent to which a human actor feels stimulated to act or grow to greater activity when using a source/piece of information or an IS/IT artifact.'),
(46, 'Satisfaction/rewarding/incenting', 5, 5, 'The extent to which a human actor feels personally satisfied, incented, and rewarded when using a source/piece of information or an IS/IT artifact.');

-- --------------------------------------------------------

--
-- Table structure for table `cluster`
--

CREATE TABLE IF NOT EXISTS `cluster` (
  `attributeID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cluster`
--

INSERT INTO `cluster` (`attributeID`) VALUES
(59),
(60),
(61),
(62),
(63),
(64),
(65),
(66),
(67),
(68),
(69),
(70);

-- --------------------------------------------------------

--
-- Table structure for table `cluster_category`
--

CREATE TABLE IF NOT EXISTS `cluster_category` (
  `clusterCategoryID` int(11) NOT NULL,
  `clusterID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=161 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cluster_category`
--

INSERT INTO `cluster_category` (`clusterCategoryID`, `clusterID`, `categoryID`) VALUES
(121, 59, 7),
(122, 59, 9),
(123, 59, 10),
(124, 59, 13),
(125, 60, 8),
(126, 60, 11),
(127, 60, 12),
(128, 61, 14),
(129, 61, 15),
(130, 61, 16),
(131, 61, 18),
(132, 61, 19),
(133, 62, 17),
(134, 62, 20),
(135, 62, 21),
(136, 63, 22),
(137, 63, 23),
(138, 63, 24),
(139, 64, 25),
(140, 64, 26),
(141, 64, 27),
(142, 65, 28),
(143, 65, 31),
(144, 65, 33),
(145, 65, 34),
(146, 66, 29),
(147, 66, 30),
(148, 66, 32),
(149, 66, 35),
(150, 66, 36),
(151, 66, 37),
(152, 67, 38),
(153, 67, 39),
(154, 68, 40),
(155, 68, 41),
(156, 69, 42),
(157, 69, 46),
(158, 70, 43),
(159, 70, 44),
(160, 70, 45);

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE IF NOT EXISTS `comment` (
  `commentID` int(11) NOT NULL,
  `comment` varchar(5000) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userCreated` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`commentID`, `comment`, `dateCreated`, `userCreated`) VALUES
(89, 'this comment updated 5th', '2016-01-27 05:00:38', 54),
(90, 'Test cluster comment', '2016-01-27 05:41:13', 54);

-- --------------------------------------------------------

--
-- Table structure for table `configuration`
--

CREATE TABLE IF NOT EXISTS `configuration` (
  `configurationID` int(11) NOT NULL,
  `configurationName` varchar(255) NOT NULL,
  `configurationDesc` varchar(500) DEFAULT NULL,
  `configurationTypeID` int(11) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `configuration`
--

INSERT INTO `configuration` (`configurationID`, `configurationName`, `configurationDesc`, `configurationTypeID`, `dateCreated`) VALUES
(1, 'All Original Categories', 'A configuration of all 40 original categories.', 1, '2016-01-25 03:17:09'),
(2, 'All Original Clusters', 'A configuration of all 12 original clusters.', 2, '2016-01-25 03:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `configurationType`
--

CREATE TABLE IF NOT EXISTS `configurationType` (
  `configurationTypeID` int(11) NOT NULL,
  `configurationTypeName` varchar(255) NOT NULL,
  `configurationTypeDesc` varchar(500) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `configurationType`
--

INSERT INTO `configurationType` (`configurationTypeID`, `configurationTypeName`, `configurationTypeDesc`) VALUES
(1, 'Category', 'A configuration of categories.'),
(2, 'Cluster', 'A configuration of clusters.');

-- --------------------------------------------------------

--
-- Table structure for table `configuration_attribute`
--

CREATE TABLE IF NOT EXISTS `configuration_attribute` (
  `configurationAttributeID` int(11) NOT NULL,
  `configurationID` int(11) NOT NULL,
  `attributeID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `configuration_attribute`
--

INSERT INTO `configuration_attribute` (`configurationAttributeID`, `configurationID`, `attributeID`) VALUES
(1, 1, 7),
(2, 1, 8),
(3, 1, 9),
(4, 1, 10),
(5, 1, 11),
(6, 1, 12),
(7, 1, 13),
(8, 1, 14),
(9, 1, 15),
(10, 1, 16),
(11, 1, 17),
(12, 1, 18),
(13, 1, 19),
(14, 1, 20),
(15, 1, 21),
(16, 1, 22),
(17, 1, 23),
(18, 1, 24),
(19, 1, 25),
(20, 1, 26),
(21, 1, 27),
(22, 1, 28),
(23, 1, 29),
(24, 1, 30),
(25, 1, 31),
(26, 1, 32),
(27, 1, 33),
(28, 1, 34),
(29, 1, 35),
(30, 1, 36),
(31, 1, 37),
(32, 1, 38),
(33, 1, 39),
(34, 1, 40),
(35, 1, 41),
(36, 1, 42),
(37, 1, 43),
(38, 1, 44),
(39, 1, 45),
(40, 1, 46),
(41, 2, 59),
(42, 2, 60),
(43, 2, 61),
(44, 2, 62),
(45, 2, 63),
(46, 2, 64),
(47, 2, 65),
(48, 2, 66),
(49, 2, 67),
(50, 2, 68),
(51, 2, 69),
(52, 2, 70);

-- --------------------------------------------------------

--
-- Table structure for table `criterion`
--

CREATE TABLE IF NOT EXISTS `criterion` (
  `criterionID` int(11) NOT NULL,
  `criterionName` varchar(255) NOT NULL,
  `criterionDesc` varchar(255) DEFAULT NULL,
  `languageID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `criterion`
--

INSERT INTO `criterion` (`criterionID`, `criterionName`, `criterionDesc`, `languageID`) VALUES
(1, 'Ease of Use', NULL, 5),
(2, 'Adaptability', NULL, 5),
(3, 'Quality', NULL, 5),
(4, 'Performance', NULL, 5),
(5, 'Affection', NULL, 5),
(6, 'Noise Reduction', NULL, 5);

-- --------------------------------------------------------

--
-- Table structure for table `language`
--

CREATE TABLE IF NOT EXISTS `language` (
  `languageID` int(11) NOT NULL,
  `languageName` varchar(45) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `language`
--

INSERT INTO `language` (`languageID`, `languageName`) VALUES
(5, 'English'),
(6, 'Francais'),
(7, 'Deutch');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `languageID` int(11) NOT NULL,
  `languageTitle` varchar(45) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`languageID`, `languageTitle`) VALUES
(5, 'English'),
(6, 'Francais'),
(7, 'Deutch');

-- --------------------------------------------------------

--
-- Table structure for table `persona`
--

CREATE TABLE IF NOT EXISTS `persona` (
  `personaID` int(11) NOT NULL,
  `personaName` varchar(45) NOT NULL,
  `personaDesc` varchar(512) DEFAULT NULL,
  `languageID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `persona`
--

INSERT INTO `persona` (`personaID`, `personaName`, `personaDesc`, `languageID`) VALUES
(20, 'player', 'player of a certain team', 5),
(21, 'general user', 'this is a general user just want to surf the site', 5),
(34, 'team coach', 'The coach of a certain team', 5),
(35, 'website admin', 'administrator of the website', 5),
(39, 'Casey', 'A male or female, ages 16 to 60. A supporter, fan or follower. Someone with average computer literacy and unrestricted access to the internet. Interested in at least one professional sports team. Accesses a professional sports team website occasionally to to frequently. Purchased tickets or merchandise never or occasionally to regularly.', 5);

-- --------------------------------------------------------

--
-- Table structure for table `personae`
--

CREATE TABLE IF NOT EXISTS `personae` (
  `personaeID` int(11) NOT NULL,
  `personaTitle` varchar(45) CHARACTER SET latin1 NOT NULL,
  `personaDescription` varchar(512) CHARACTER SET latin1 DEFAULT NULL,
  `personaLanguage` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personae`
--

INSERT INTO `personae` (`personaeID`, `personaTitle`, `personaDescription`, `personaLanguage`) VALUES
(20, 'player', 'player of a certain team', 5),
(21, 'general user', 'this is a general user just want to surf the site', 5),
(34, 'team coach', 'The coach of a certain team', 5),
(35, 'website admin', 'administrator of the website', 5),
(39, 'Casey', 'A male or female, ages 16 to 60. A supporter, fan or follower. Someone with average computer literacy and unrestricted access to the internet. Interested in at least one professional sports team. Accesses a professional sports team website occasionally to to frequently. Purchased tickets or merchandise never or occasionally to regularly.', 5);

-- --------------------------------------------------------

--
-- Table structure for table `personaScenario`
--

CREATE TABLE IF NOT EXISTS `personaScenario` (
  `personaScenarioID` int(11) NOT NULL,
  `personaID` int(11) NOT NULL,
  `scenarioID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `personaScenario`
--

INSERT INTO `personaScenario` (`personaScenarioID`, `personaID`, `scenarioID`) VALUES
(113, 39, 36),
(112, 39, 37),
(111, 39, 38),
(110, 39, 39),
(109, 39, 40),
(108, 39, 41),
(114, 39, 42),
(115, 39, 43),
(116, 39, 45),
(107, 39, 46),
(117, 39, 48),
(118, 39, 49),
(119, 39, 50),
(120, 39, 51);

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE IF NOT EXISTS `project` (
  `projectID` int(11) NOT NULL,
  `projectName` varchar(45) NOT NULL,
  `projectDescription` varchar(150) DEFAULT NULL,
  `languageID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`projectID`, `projectName`, `projectDescription`, `languageID`) VALUES
(29, 'delong_small_project', 'this is a small project', 5),
(32, 'smart city', 'smart city project', 5),
(36, 'IMT598 TEDS Anchor Test', 'This is the Anchor Test that will be used for IMT598', 5),
(37, 'IMT598 TEDS Mobile Anchor Test', 'Anchor test for IMT598 Mobile App Testing', 5),
(38, 'Sports Mobile App Comparison', 'Mobile applications for sports comparison Fall 2015', 5);

-- --------------------------------------------------------

--
-- Table structure for table `projectArtifact`
--

CREATE TABLE IF NOT EXISTS `projectArtifact` (
  `projectArtifactID` int(11) NOT NULL,
  `projectID` int(11) NOT NULL,
  `artifactID` int(11) NOT NULL,
  `isAnchor` bit(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `projectArtifact`
--

INSERT INTO `projectArtifact` (`projectArtifactID`, `projectID`, `artifactID`, `isAnchor`) VALUES
(52, 32, 67, NULL),
(62, 36, 77, NULL),
(63, 36, 78, NULL),
(64, 36, 79, NULL),
(65, 36, 80, NULL),
(66, 37, 81, NULL),
(67, 37, 82, NULL),
(68, 37, 83, NULL),
(69, 38, 84, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rating`
--

CREATE TABLE IF NOT EXISTS `rating` (
  `ratingID` int(11) NOT NULL,
  `assessmentID` int(11) NOT NULL,
  `ratingValue` decimal(11,1) NOT NULL,
  `attributeID` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4429 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rating`
--

INSERT INTO `rating` (`ratingID`, `assessmentID`, `ratingValue`, `attributeID`) VALUES
(42, 42, 4.0, 7),
(47, 47, 5.0, 7),
(50, 50, 1.0, 7),
(54, 54, 5.0, 7),
(56, 56, 4.0, 7),
(57, 57, 5.0, 7),
(60, 60, 4.0, 7),
(69, 69, 1.0, 7),
(76, 76, 4.0, 7),
(136, 66, 4.0, 7),
(166, 166, 3.0, 7),
(187, 187, 5.0, 7),
(921, 42, 3.0, 8),
(922, 42, 3.0, 9),
(923, 42, 3.0, 10),
(924, 42, 4.0, 11),
(925, 42, 3.0, 12),
(926, 42, 3.0, 13),
(927, 42, 3.0, 28),
(928, 42, 3.0, 29),
(929, 42, 3.0, 30),
(930, 42, 1.0, 31),
(931, 42, 2.0, 32),
(932, 42, 2.0, 33),
(933, 42, 3.0, 34),
(934, 42, 2.0, 35),
(935, 42, 5.0, 36),
(936, 42, 3.0, 37),
(937, 42, 4.0, 22),
(938, 42, 4.0, 23),
(939, 42, 4.0, 24),
(940, 42, 4.0, 25),
(941, 42, 4.0, 26),
(942, 42, 5.0, 27),
(943, 42, 4.0, 38),
(944, 42, 4.0, 39),
(945, 42, 4.0, 40),
(946, 42, 4.0, 41),
(947, 42, 4.0, 42),
(948, 42, 4.0, 43),
(949, 42, 4.0, 44),
(950, 42, 4.0, 45),
(951, 42, 4.0, 46),
(953, 50, 1.0, 8),
(954, 50, 1.0, 9),
(955, 50, 2.0, 10),
(956, 50, 1.0, 11),
(957, 50, 1.0, 12),
(958, 50, 1.0, 13),
(972, 46, 4.0, 7),
(973, 46, 3.0, 8),
(974, 46, 3.0, 9),
(975, 46, 3.0, 10),
(976, 46, 4.0, 11),
(977, 46, 4.0, 12),
(978, 46, 4.0, 13),
(979, 46, 4.0, 28),
(980, 46, 4.0, 29),
(981, 46, 4.0, 30),
(982, 46, 4.0, 31),
(983, 46, 4.0, 32),
(984, 46, 2.0, 33),
(985, 46, 2.0, 34),
(986, 46, 2.0, 35),
(987, 46, 4.0, 36),
(988, 46, 3.0, 37),
(989, 46, 4.0, 22),
(990, 46, 4.0, 23),
(991, 46, 4.0, 24),
(992, 46, 4.0, 25),
(993, 46, 4.0, 26),
(994, 46, 5.0, 27),
(995, 46, 4.0, 38),
(996, 46, 4.0, 39),
(997, 46, 3.0, 40),
(998, 46, 3.0, 41),
(999, 46, 3.0, 42),
(1000, 46, 2.0, 43),
(1001, 46, 3.0, 44),
(1002, 46, 3.0, 45),
(1003, 46, 2.0, 46),
(1004, 46, 4.0, 14),
(1005, 46, 4.0, 15),
(1006, 46, 4.0, 16),
(1007, 46, 4.0, 17),
(1008, 46, 4.0, 18),
(1009, 46, 4.0, 19),
(1010, 46, 4.0, 20),
(1011, 46, 3.0, 21),
(1012, 48, 4.0, 7),
(1013, 48, 4.0, 8),
(1014, 48, 3.0, 9),
(1015, 48, 4.0, 10),
(1016, 48, 3.0, 11),
(1017, 48, 4.0, 12),
(1018, 48, 3.0, 13),
(1019, 48, 4.0, 28),
(1020, 48, 5.0, 29),
(1021, 48, 4.0, 30),
(1022, 48, 4.0, 31),
(1023, 48, 4.0, 32),
(1024, 48, 2.0, 33),
(1025, 48, 2.0, 34),
(1026, 48, 4.0, 35),
(1027, 48, 5.0, 36),
(1028, 48, 4.0, 37),
(1029, 48, 4.0, 22),
(1030, 48, 4.0, 23),
(1031, 48, 3.0, 24),
(1032, 48, 4.0, 25),
(1033, 48, 5.0, 26),
(1034, 48, 5.0, 27),
(1035, 48, 4.0, 38),
(1036, 48, 3.0, 39),
(1037, 48, 4.0, 40),
(1038, 48, 4.0, 41),
(1039, 48, 4.0, 42),
(1040, 48, 3.0, 43),
(1041, 48, 4.0, 44),
(1042, 48, 3.0, 45),
(1043, 48, 3.0, 46),
(1044, 48, 4.0, 14),
(1045, 48, 4.0, 15),
(1046, 48, 4.0, 16),
(1047, 48, 4.0, 17),
(1048, 48, 3.0, 18),
(1049, 48, 5.0, 19),
(1050, 48, 4.0, 20),
(1051, 48, 4.0, 21),
(1053, 56, 4.0, 8),
(1054, 56, 3.0, 9),
(1055, 56, 3.0, 10),
(1056, 56, 4.0, 11),
(1057, 56, 4.0, 12),
(1058, 56, 5.0, 13),
(1059, 56, 4.0, 28),
(1060, 56, 5.0, 29),
(1061, 56, 4.0, 30),
(1062, 56, 4.0, 31),
(1063, 56, 4.0, 32),
(1064, 56, 5.0, 33),
(1065, 56, 3.0, 34),
(1066, 56, 4.0, 35),
(1067, 56, 5.0, 36),
(1068, 56, 3.0, 37),
(1069, 56, 5.0, 22),
(1070, 56, 4.0, 23),
(1071, 56, 5.0, 24),
(1072, 56, 5.0, 25),
(1073, 56, 5.0, 26),
(1074, 56, 5.0, 27),
(1075, 56, 4.0, 38),
(1076, 56, 4.0, 39),
(1077, 56, 4.0, 40),
(1078, 56, 4.0, 41),
(1079, 56, 4.0, 42),
(1080, 56, 5.0, 43),
(1081, 56, 4.0, 44),
(1082, 56, 4.0, 45),
(1083, 56, 5.0, 46),
(1084, 56, 5.0, 14),
(1085, 56, 4.0, 15),
(1086, 56, 5.0, 16),
(1087, 56, 4.0, 17),
(1088, 56, 4.0, 18),
(1089, 56, 5.0, 19),
(1090, 56, 5.0, 20),
(1091, 56, 4.0, 21),
(1096, 47, 3.0, 8),
(1097, 47, 4.0, 9),
(1098, 47, 4.0, 10),
(1099, 47, 4.0, 11),
(1100, 47, 4.0, 12),
(1101, 47, 4.0, 13),
(1102, 47, 4.0, 28),
(1103, 47, 4.0, 29),
(1104, 47, 4.0, 30),
(1105, 47, 3.0, 31),
(1106, 47, 5.0, 32),
(1107, 47, 4.0, 33),
(1108, 47, 4.0, 34),
(1109, 47, 5.0, 35),
(1110, 47, 4.0, 36),
(1111, 47, 3.0, 37),
(1112, 47, 4.0, 22),
(1113, 47, 5.0, 23),
(1114, 47, 3.0, 24),
(1115, 47, 5.0, 25),
(1116, 47, 5.0, 26),
(1117, 47, 5.0, 27),
(1118, 47, 4.0, 38),
(1119, 47, 5.0, 39),
(1120, 47, 3.0, 40),
(1121, 47, 4.0, 41),
(1122, 47, 2.0, 42),
(1123, 47, 3.0, 43),
(1124, 47, 3.0, 44),
(1125, 47, 3.0, 45),
(1126, 47, 2.0, 46),
(1127, 47, 3.0, 14),
(1128, 47, 3.0, 15),
(1129, 47, 4.0, 16),
(1130, 47, 5.0, 17),
(1131, 47, 4.0, 18),
(1132, 47, 4.0, 19),
(1133, 47, 5.0, 20),
(1134, 47, 3.0, 21),
(1136, 54, 3.0, 8),
(1137, 54, 3.0, 9),
(1138, 54, 4.0, 10),
(1139, 54, 4.0, 11),
(1140, 54, 3.0, 12),
(1141, 54, 4.0, 13),
(1142, 54, 4.0, 28),
(1143, 54, 4.0, 29),
(1144, 54, 4.0, 30),
(1145, 54, 4.0, 31),
(1146, 54, 4.0, 32),
(1147, 54, 2.0, 33),
(1148, 54, 1.0, 34),
(1149, 54, 2.0, 35),
(1150, 54, 4.0, 36),
(1151, 54, 3.0, 37),
(1152, 54, 4.0, 22),
(1153, 54, 3.0, 23),
(1154, 54, 3.0, 24),
(1155, 54, 3.0, 25),
(1156, 54, 4.0, 26),
(1157, 54, 5.0, 27),
(1158, 54, 3.0, 38),
(1159, 54, 3.0, 39),
(1160, 54, 2.0, 40),
(1161, 54, 3.0, 41),
(1162, 54, 3.0, 42),
(1163, 54, 1.0, 43),
(1164, 54, 2.0, 44),
(1165, 54, 2.0, 45),
(1166, 54, 2.0, 46),
(1167, 54, 4.0, 14),
(1168, 54, 4.0, 15),
(1169, 54, 4.0, 16),
(1170, 54, 3.0, 17),
(1171, 54, 4.0, 18),
(1172, 54, 3.0, 19),
(1173, 54, 3.0, 20),
(1174, 54, 2.0, 21),
(1175, 42, 4.0, 14),
(1176, 42, 4.0, 15),
(1177, 42, 4.0, 16),
(1178, 42, 3.0, 17),
(1179, 42, 4.0, 18),
(1180, 42, 3.0, 19),
(1181, 42, 4.0, 20),
(1182, 42, 3.0, 21),
(1184, 57, 4.0, 8),
(1185, 57, 4.0, 9),
(1186, 57, 5.0, 10),
(1187, 57, 2.0, 11),
(1188, 57, 5.0, 12),
(1189, 57, 3.0, 13),
(1190, 57, 5.0, 28),
(1191, 57, 5.0, 29),
(1192, 57, 5.0, 30),
(1193, 57, 3.0, 31),
(1194, 57, 3.0, 32),
(1195, 57, 2.0, 33),
(1196, 57, 2.0, 34),
(1197, 57, 2.0, 35),
(1198, 57, 4.0, 36),
(1199, 57, 2.0, 37),
(1200, 57, 5.0, 22),
(1201, 57, 3.0, 23),
(1202, 57, 3.0, 24),
(1203, 57, 3.0, 25),
(1204, 57, 4.0, 26),
(1205, 57, 3.0, 27),
(1206, 57, 5.0, 38),
(1207, 57, 3.0, 39),
(1208, 57, 5.0, 40),
(1209, 57, 5.0, 41),
(1210, 57, 3.0, 42),
(1211, 57, 2.0, 43),
(1212, 57, 2.0, 44),
(1213, 57, 2.0, 45),
(1214, 57, 3.0, 46),
(1215, 57, 4.0, 14),
(1216, 57, 1.0, 15),
(1217, 57, 1.0, 16),
(1218, 57, 2.0, 17),
(1219, 57, 3.0, 18),
(1220, 57, 4.0, 19),
(1221, 57, 3.0, 20),
(1222, 57, 2.0, 21),
(1224, 60, 3.0, 8),
(1225, 60, 3.0, 9),
(1226, 60, 4.0, 10),
(1227, 60, 4.0, 11),
(1228, 60, 4.0, 12),
(1229, 60, 3.0, 13),
(1230, 60, 3.0, 28),
(1231, 60, 3.0, 29),
(1232, 60, 3.0, 30),
(1233, 60, 2.0, 31),
(1234, 60, 3.0, 32),
(1235, 60, 2.0, 33),
(1236, 60, 2.0, 34),
(1237, 60, 3.0, 35),
(1238, 60, 5.0, 36),
(1239, 60, 3.0, 37),
(1240, 60, 4.0, 22),
(1241, 60, 4.0, 23),
(1242, 60, 4.0, 24),
(1243, 60, 4.0, 25),
(1244, 60, 4.0, 26),
(1245, 60, 4.0, 27),
(1246, 60, 4.0, 38),
(1247, 60, 4.0, 39),
(1248, 60, 3.0, 40),
(1249, 60, 3.0, 41),
(1250, 60, 3.0, 42),
(1251, 60, 3.0, 43),
(1252, 60, 3.0, 44),
(1253, 60, 3.0, 45),
(1254, 60, 3.0, 46),
(1255, 60, 4.0, 14),
(1256, 60, 4.0, 15),
(1257, 60, 4.0, 16),
(1258, 60, 3.0, 17),
(1259, 60, 4.0, 18),
(1260, 60, 4.0, 19),
(1261, 60, 4.0, 20),
(1262, 60, 3.0, 21),
(1263, 61, 2.0, 7),
(1264, 61, 3.0, 8),
(1265, 61, 4.0, 9),
(1266, 75, 4.0, 7),
(1267, 75, 4.0, 8),
(1268, 75, 3.0, 9),
(1269, 75, 3.0, 10),
(1270, 75, 2.0, 11),
(1271, 75, 4.0, 12),
(1272, 75, 3.0, 13),
(1273, 75, 4.0, 28),
(1274, 75, 2.0, 29),
(1275, 75, 3.0, 30),
(1276, 75, 3.0, 31),
(1277, 75, 4.0, 32),
(1278, 75, 2.0, 33),
(1279, 75, 3.0, 34),
(1280, 75, 2.0, 35),
(1281, 75, 2.0, 36),
(1282, 75, 3.0, 37),
(1283, 75, 4.0, 22),
(1284, 75, 3.0, 23),
(1285, 75, 4.0, 24),
(1286, 75, 4.0, 25),
(1287, 75, 5.0, 26),
(1288, 75, 4.0, 27),
(1289, 75, 3.0, 38),
(1290, 75, 2.0, 39),
(1291, 75, 4.0, 40),
(1292, 75, 4.0, 41),
(1293, 75, 4.0, 42),
(1294, 75, 4.0, 43),
(1295, 75, 3.0, 44),
(1296, 75, 3.0, 45),
(1297, 75, 3.0, 46),
(1298, 75, 5.0, 14),
(1299, 75, 4.0, 15),
(1300, 75, 4.0, 16),
(1301, 75, 3.0, 17),
(1302, 75, 2.0, 18),
(1303, 75, 2.0, 19),
(1304, 75, 3.0, 20),
(1305, 75, 3.0, 21),
(1307, 76, 3.0, 8),
(1308, 76, 3.0, 9),
(1309, 76, 3.0, 10),
(1310, 76, 3.0, 11),
(1311, 76, 3.0, 12),
(1312, 76, 2.0, 13),
(1313, 76, 4.0, 28),
(1314, 76, 3.0, 29),
(1315, 76, 3.0, 30),
(1316, 76, 2.0, 31),
(1317, 76, 4.0, 32),
(1318, 76, 3.0, 33),
(1319, 76, 4.0, 34),
(1320, 76, 3.0, 35),
(1321, 76, 3.0, 36),
(1322, 76, 3.0, 37),
(1323, 76, 4.0, 22),
(1324, 76, 3.0, 23),
(1325, 76, 3.0, 24),
(1326, 76, 3.0, 25),
(1327, 76, 4.0, 26),
(1328, 76, 4.0, 27),
(1329, 76, 3.0, 38),
(1330, 76, 3.0, 39),
(1331, 76, 4.0, 40),
(1332, 76, 4.0, 41),
(1333, 76, 4.0, 42),
(1334, 76, 4.0, 43),
(1335, 76, 3.0, 44),
(1336, 76, 3.0, 45),
(1337, 76, 3.0, 46),
(1338, 76, 4.0, 14),
(1339, 76, 3.0, 15),
(1340, 76, 2.0, 16),
(1341, 76, 2.0, 17),
(1342, 76, 3.0, 18),
(1343, 76, 3.0, 19),
(1344, 76, 4.0, 20),
(1345, 76, 3.0, 21),
(1346, 94, 3.0, 7),
(1347, 94, 3.0, 8),
(1348, 94, 3.0, 9),
(1349, 94, 2.0, 10),
(1350, 94, 3.0, 11),
(1351, 94, 3.0, 12),
(1352, 94, 3.0, 13),
(1353, 94, 3.0, 28),
(1354, 94, 4.0, 29),
(1355, 94, 4.0, 30),
(1356, 94, 3.0, 31),
(1357, 94, 4.0, 32),
(1358, 94, 2.0, 33),
(1359, 94, 2.0, 34),
(1360, 94, 3.0, 35),
(1361, 94, 3.0, 36),
(1362, 94, 4.0, 37),
(1363, 94, 4.0, 22),
(1364, 94, 4.0, 23),
(1365, 94, 3.0, 24),
(1366, 94, 3.0, 25),
(1367, 94, 4.0, 26),
(1368, 94, 4.0, 27),
(1369, 94, 2.0, 38),
(1370, 94, 2.0, 39),
(1371, 94, 4.0, 40),
(1372, 94, 4.0, 41),
(1373, 94, 3.0, 42),
(1374, 94, 4.0, 43),
(1375, 94, 3.0, 44),
(1376, 94, 2.0, 45),
(1377, 94, 2.0, 46),
(1378, 94, 4.0, 14),
(1379, 94, 3.0, 15),
(1380, 94, 4.0, 16),
(1381, 94, 2.0, 17),
(1382, 94, 3.0, 18),
(1383, 94, 3.0, 19),
(1384, 94, 4.0, 20),
(1385, 94, 4.0, 21),
(1386, 93, 4.0, 7),
(1387, 93, 3.0, 8),
(1388, 93, 3.0, 9),
(1389, 93, 3.0, 10),
(1390, 93, 3.0, 11),
(1391, 93, 4.0, 12),
(1392, 93, 4.0, 13),
(1393, 93, 3.0, 28),
(1394, 93, 2.0, 29),
(1395, 93, 4.0, 30),
(1396, 93, 3.0, 31),
(1397, 93, 4.0, 32),
(1398, 93, 3.0, 33),
(1399, 93, 3.0, 34),
(1400, 93, 3.0, 35),
(1401, 93, 2.0, 36),
(1402, 93, 4.0, 37),
(1403, 93, 4.0, 22),
(1404, 93, 5.0, 23),
(1405, 93, 4.0, 24),
(1406, 93, 4.0, 25),
(1407, 93, 4.0, 26),
(1408, 93, 4.0, 27),
(1409, 93, 3.0, 38),
(1410, 93, 3.0, 39),
(1411, 93, 4.0, 40),
(1412, 93, 4.0, 41),
(1413, 93, 4.0, 42),
(1414, 93, 3.0, 43),
(1415, 93, 3.0, 44),
(1416, 93, 3.0, 45),
(1417, 93, 3.0, 46),
(1418, 93, 4.0, 14),
(1419, 93, 3.0, 15),
(1420, 93, 2.0, 16),
(1421, 93, 3.0, 17),
(1422, 93, 3.0, 18),
(1423, 93, 3.0, 19),
(1424, 93, 4.0, 20),
(1425, 93, 3.0, 21),
(1426, 111, 4.0, 7),
(1427, 111, 4.0, 8),
(1428, 111, 3.0, 9),
(1429, 111, 3.0, 10),
(1430, 111, 3.0, 11),
(1431, 111, 4.0, 12),
(1432, 111, 3.0, 13),
(1433, 111, 4.0, 28),
(1434, 111, 4.0, 29),
(1435, 111, 3.0, 30),
(1436, 111, 4.0, 31),
(1437, 111, 3.0, 32),
(1438, 111, 3.0, 33),
(1439, 111, 2.0, 34),
(1440, 111, 4.0, 35),
(1441, 111, 3.0, 36),
(1442, 111, 2.0, 37),
(1443, 111, 4.0, 22),
(1444, 111, 4.0, 23),
(1445, 111, 3.0, 24),
(1446, 111, 4.0, 25),
(1447, 111, 4.0, 26),
(1448, 111, 4.0, 27),
(1449, 111, 3.0, 38),
(1450, 111, 3.0, 39),
(1451, 111, 2.0, 40),
(1452, 111, 2.0, 41),
(1453, 111, 4.0, 42),
(1454, 111, 4.0, 43),
(1455, 111, 3.0, 44),
(1456, 111, 3.0, 45),
(1457, 111, 3.0, 46),
(1458, 111, 4.0, 14),
(1459, 111, 4.0, 15),
(1460, 111, 2.0, 16),
(1461, 111, 3.0, 17),
(1462, 111, 4.0, 18),
(1463, 111, 4.0, 19),
(1464, 111, 3.0, 20),
(1465, 111, 3.0, 21),
(1466, 112, 3.0, 7),
(1467, 112, 3.0, 8),
(1468, 112, 3.0, 9),
(1469, 112, 3.0, 10),
(1470, 112, 3.0, 11),
(1471, 112, 4.0, 12),
(1472, 112, 2.0, 13),
(1473, 112, 4.0, 28),
(1474, 112, 3.0, 29),
(1475, 112, 3.0, 30),
(1476, 112, 4.0, 31),
(1477, 112, 3.0, 32),
(1478, 112, 3.0, 33),
(1479, 112, 2.0, 34),
(1480, 112, 4.0, 35),
(1481, 112, 4.0, 36),
(1482, 112, 3.0, 37),
(1483, 112, 3.0, 22),
(1484, 112, 3.0, 23),
(1485, 112, 3.0, 24),
(1486, 112, 3.0, 25),
(1487, 112, 2.0, 26),
(1488, 112, 2.0, 27),
(1489, 112, 3.0, 38),
(1490, 112, 3.0, 39),
(1491, 112, 2.0, 40),
(1492, 112, 2.0, 41),
(1493, 112, 3.0, 42),
(1494, 112, 3.0, 43),
(1495, 112, 3.0, 44),
(1496, 112, 3.0, 45),
(1497, 112, 3.0, 46),
(1498, 112, 3.0, 14),
(1499, 112, 3.0, 15),
(1500, 112, 2.0, 16),
(1501, 112, 4.0, 17),
(1502, 112, 3.0, 18),
(1503, 112, 3.0, 19),
(1504, 112, 3.0, 20),
(1505, 112, 3.0, 21),
(1507, 66, 4.0, 8),
(1508, 66, 3.0, 9),
(1509, 66, 4.0, 10),
(1510, 66, 3.0, 11),
(1511, 66, 3.0, 12),
(1512, 66, 4.0, 13),
(1513, 66, 3.0, 28),
(1514, 66, 4.0, 29),
(1515, 66, 4.0, 30),
(1516, 66, 2.0, 31),
(1517, 66, 3.0, 32),
(1518, 66, 2.0, 33),
(1519, 66, 1.0, 34),
(1520, 66, 1.0, 35),
(1521, 66, 1.0, 36),
(1522, 66, 3.0, 37),
(1523, 66, 4.0, 22),
(1524, 66, 3.0, 23),
(1525, 66, 3.0, 24),
(1526, 66, 3.0, 25),
(1527, 66, 4.0, 26),
(1528, 66, 4.0, 27),
(1529, 66, 3.0, 38),
(1530, 66, 3.0, 39),
(1531, 66, 3.0, 40),
(1532, 66, 3.0, 41),
(1533, 66, 4.0, 42),
(1534, 66, 3.0, 43),
(1535, 66, 3.0, 44),
(1536, 66, 3.0, 45),
(1537, 66, 4.0, 46),
(1538, 66, 3.0, 14),
(1539, 66, 4.0, 15),
(1540, 66, 2.0, 16),
(1541, 66, 2.0, 17),
(1542, 66, 3.0, 18),
(1543, 66, 3.0, 19),
(1544, 66, 4.0, 20),
(1545, 66, 3.0, 21),
(1546, 65, 3.0, 7),
(1547, 65, 3.0, 8),
(1548, 65, 3.0, 9),
(1549, 65, 3.0, 10),
(1550, 65, 3.0, 11),
(1551, 65, 3.0, 12),
(1552, 65, 4.0, 13),
(1553, 65, 3.0, 28),
(1554, 65, 3.0, 29),
(1555, 65, 3.0, 30),
(1556, 65, 2.0, 31),
(1557, 65, 3.0, 32),
(1558, 65, 2.0, 33),
(1559, 65, 4.0, 34),
(1560, 65, 2.0, 35),
(1561, 65, 2.0, 36),
(1562, 65, 3.0, 37),
(1563, 65, 3.0, 22),
(1564, 65, 3.0, 23),
(1565, 65, 3.0, 24),
(1566, 65, 2.0, 25),
(1567, 65, 3.0, 26),
(1568, 65, 3.0, 27),
(1569, 65, 3.0, 38),
(1570, 65, 2.0, 39),
(1571, 65, 3.0, 40),
(1572, 65, 3.0, 41),
(1573, 65, 3.0, 42),
(1574, 65, 3.0, 43),
(1575, 65, 3.0, 44),
(1576, 65, 3.0, 45),
(1577, 65, 2.0, 46),
(1578, 65, 3.0, 14),
(1579, 65, 3.0, 15),
(1580, 65, 4.0, 16),
(1581, 65, 2.0, 17),
(1582, 65, 2.0, 18),
(1583, 65, 2.0, 19),
(1584, 65, 3.0, 20),
(1585, 65, 3.0, 21),
(1586, 107, 2.0, 7),
(1587, 107, 3.0, 8),
(1588, 107, 3.0, 9),
(1589, 107, 4.0, 10),
(1590, 107, 3.0, 11),
(1591, 107, 3.0, 12),
(1592, 107, 3.0, 13),
(1593, 107, 3.0, 28),
(1594, 107, 3.0, 29),
(1595, 107, 3.0, 30),
(1596, 107, 3.0, 31),
(1597, 107, 4.0, 32),
(1598, 107, 4.0, 33),
(1599, 107, 3.0, 34),
(1600, 107, 3.0, 35),
(1601, 107, 1.0, 36),
(1602, 107, 3.0, 37),
(1603, 107, 4.0, 22),
(1604, 107, 4.0, 23),
(1605, 107, 3.0, 24),
(1606, 107, 4.0, 25),
(1607, 107, 3.0, 26),
(1608, 107, 4.0, 27),
(1609, 107, 3.0, 38),
(1610, 107, 4.0, 39),
(1611, 107, 4.0, 40),
(1612, 107, 4.0, 41),
(1613, 107, 4.0, 42),
(1614, 107, 3.0, 43),
(1615, 107, 4.0, 44),
(1616, 107, 4.0, 45),
(1617, 107, 4.0, 46),
(1618, 107, 4.0, 14),
(1619, 107, 4.0, 15),
(1620, 107, 4.0, 16),
(1621, 107, 4.0, 17),
(1622, 107, 4.0, 18),
(1623, 107, 3.0, 19),
(1624, 107, 3.0, 20),
(1625, 107, 3.0, 21),
(1626, 83, 3.0, 7),
(1627, 83, 3.0, 8),
(1628, 83, 4.0, 9),
(1629, 83, 3.0, 10),
(1630, 83, 3.0, 11),
(1631, 83, 3.0, 12),
(1632, 83, 3.0, 13),
(1633, 83, 4.0, 28),
(1634, 83, 2.0, 29),
(1635, 83, 3.0, 30),
(1636, 83, 2.0, 31),
(1637, 83, 3.0, 32),
(1638, 83, 2.0, 33),
(1639, 83, 3.0, 34),
(1640, 83, 2.0, 35),
(1641, 83, 2.0, 36),
(1642, 83, 3.0, 37),
(1643, 83, 3.0, 22),
(1644, 83, 4.0, 23),
(1645, 83, 3.0, 24),
(1646, 83, 3.0, 25),
(1647, 83, 4.0, 26),
(1648, 83, 3.0, 27),
(1649, 83, 3.0, 38),
(1650, 83, 3.0, 39),
(1651, 83, 3.0, 40),
(1652, 83, 3.0, 41),
(1653, 83, 3.0, 42),
(1654, 83, 3.0, 43),
(1655, 83, 4.0, 44),
(1656, 83, 3.0, 45),
(1657, 83, 3.0, 46),
(1658, 83, 3.0, 14),
(1659, 83, 4.0, 15),
(1660, 83, 3.0, 16),
(1661, 83, 2.0, 17),
(1662, 83, 2.0, 18),
(1663, 83, 3.0, 19),
(1664, 83, 3.0, 20),
(1665, 83, 3.0, 21),
(1666, 84, 1.0, 7),
(1667, 84, 1.0, 8),
(1668, 84, 1.0, 9),
(1669, 84, 2.0, 10),
(1670, 84, 2.0, 11),
(1671, 84, 1.0, 12),
(1672, 84, 1.0, 13),
(1673, 84, 1.0, 28),
(1674, 84, 1.0, 29),
(1675, 84, 3.0, 30),
(1676, 84, 1.0, 31),
(1677, 84, 3.0, 32),
(1678, 84, 1.0, 33),
(1679, 84, 1.0, 34),
(1680, 84, 1.0, 35),
(1681, 84, 1.0, 36),
(1682, 84, 3.0, 37),
(1683, 84, 2.0, 22),
(1684, 84, 1.0, 23),
(1685, 84, 1.0, 24),
(1686, 84, 1.0, 25),
(1687, 84, 1.0, 26),
(1688, 84, 1.0, 27),
(1689, 84, 1.0, 38),
(1690, 84, 1.0, 39),
(1691, 84, 3.0, 40),
(1692, 84, 3.0, 41),
(1693, 84, 2.0, 42),
(1694, 84, 1.0, 43),
(1695, 84, 1.0, 44),
(1696, 84, 1.0, 45),
(1697, 84, 1.0, 46),
(1698, 84, 1.0, 14),
(1699, 84, 1.0, 15),
(1700, 84, 1.0, 16),
(1701, 84, 1.0, 17),
(1702, 84, 1.0, 18),
(1703, 84, 1.0, 19),
(1704, 84, 1.0, 20),
(1705, 84, 1.0, 21),
(1706, 101, 3.0, 7),
(1707, 101, 2.0, 8),
(1708, 101, 3.0, 9),
(1709, 101, 3.0, 10),
(1710, 101, 3.0, 11),
(1711, 101, 3.0, 12),
(1712, 101, 3.0, 13),
(1713, 101, 3.0, 28),
(1714, 101, 3.0, 29),
(1715, 101, 4.0, 30),
(1716, 101, 4.0, 31),
(1717, 101, 3.0, 32),
(1718, 101, 2.0, 33),
(1719, 101, 2.0, 34),
(1720, 101, 3.0, 35),
(1721, 101, 1.0, 36),
(1722, 101, 3.0, 37),
(1723, 101, 3.0, 22),
(1724, 101, 4.0, 23),
(1725, 101, 3.0, 24),
(1726, 101, 4.0, 25),
(1727, 101, 3.0, 26),
(1728, 101, 3.0, 27),
(1729, 101, 3.0, 38),
(1730, 101, 3.0, 39),
(1731, 101, 3.0, 40),
(1732, 101, 3.0, 41),
(1733, 101, 3.0, 42),
(1734, 101, 3.0, 43),
(1735, 101, 3.0, 44),
(1736, 101, 3.0, 45),
(1737, 101, 3.0, 46),
(1738, 101, 4.0, 14),
(1739, 101, 3.0, 15),
(1740, 101, 3.0, 16),
(1741, 101, 2.0, 17),
(1742, 101, 3.0, 18),
(1743, 101, 4.0, 19),
(1744, 101, 3.0, 20),
(1745, 101, 3.0, 21),
(1746, 73, 5.0, 7),
(1747, 73, 4.0, 8),
(1748, 73, 4.0, 9),
(1749, 73, 4.0, 10),
(1750, 73, 5.0, 11),
(1751, 73, 5.0, 12),
(1752, 73, 3.0, 13),
(1753, 73, 4.0, 28),
(1754, 73, 3.0, 29),
(1755, 73, 3.0, 30),
(1756, 73, 4.0, 31),
(1757, 73, 5.0, 32),
(1758, 73, 5.0, 33),
(1759, 73, 4.0, 34),
(1760, 73, 4.0, 35),
(1761, 73, 3.0, 36),
(1762, 73, 4.0, 37),
(1763, 73, 4.0, 22),
(1764, 73, 5.0, 23),
(1765, 73, 4.0, 24),
(1766, 73, 5.0, 25),
(1767, 73, 5.0, 26),
(1768, 73, 5.0, 27),
(1769, 73, 4.0, 38),
(1770, 73, 4.0, 39),
(1771, 73, 4.0, 40),
(1772, 73, 5.0, 41),
(1773, 73, 5.0, 42),
(1774, 73, 5.0, 43),
(1775, 73, 4.0, 44),
(1776, 73, 3.0, 45),
(1777, 73, 4.0, 46),
(1778, 73, 4.0, 14),
(1779, 73, 5.0, 15),
(1780, 73, 4.0, 16),
(1781, 73, 5.0, 17),
(1782, 73, 4.0, 18),
(1783, 73, 4.0, 19),
(1784, 73, 5.0, 20),
(1785, 73, 4.0, 21),
(1786, 102, 2.0, 7),
(1787, 102, 2.0, 8),
(1788, 102, 2.0, 9),
(1789, 102, 2.0, 10),
(1790, 102, 2.0, 11),
(1791, 102, 2.0, 12),
(1792, 102, 2.0, 13),
(1793, 102, 2.0, 28),
(1794, 102, 3.0, 29),
(1795, 102, 2.0, 30),
(1796, 102, 1.0, 31),
(1797, 102, 3.0, 32),
(1798, 102, 2.0, 33),
(1799, 102, 2.0, 34),
(1800, 102, 3.0, 35),
(1801, 102, 1.0, 36),
(1802, 102, 3.0, 37),
(1803, 102, 3.0, 22),
(1804, 102, 2.0, 23),
(1805, 102, 3.0, 24),
(1806, 102, 3.0, 25),
(1807, 102, 3.0, 26),
(1808, 102, 3.0, 27),
(1809, 102, 3.0, 38),
(1810, 102, 3.0, 39),
(1811, 102, 3.0, 40),
(1812, 102, 3.0, 41),
(1813, 102, 3.0, 42),
(1814, 102, 3.0, 43),
(1815, 102, 3.0, 44),
(1816, 102, 3.0, 45),
(1817, 102, 3.0, 46),
(1818, 102, 3.0, 14),
(1819, 102, 3.0, 15),
(1820, 102, 3.0, 16),
(1821, 102, 2.0, 17),
(1822, 102, 2.0, 18),
(1823, 102, 2.0, 19),
(1824, 102, 3.0, 20),
(1825, 102, 3.0, 21),
(1826, 90, 3.0, 7),
(1827, 90, 3.0, 8),
(1828, 90, 2.0, 9),
(1829, 90, 3.0, 10),
(1830, 90, 4.0, 11),
(1831, 90, 4.0, 12),
(1832, 90, 4.0, 13),
(1833, 90, 3.0, 28),
(1834, 90, 2.0, 29),
(1835, 90, 4.0, 30),
(1836, 90, 3.0, 31),
(1837, 90, 4.0, 32),
(1838, 90, 1.0, 33),
(1839, 90, 1.0, 34),
(1840, 90, 1.0, 35),
(1841, 90, 1.0, 36),
(1842, 90, 3.0, 37),
(1843, 90, 5.0, 22),
(1844, 90, 4.0, 23),
(1845, 90, 3.0, 24),
(1846, 90, 4.0, 25),
(1847, 90, 4.0, 26),
(1848, 90, 4.0, 27),
(1849, 90, 3.0, 38),
(1850, 90, 4.0, 39),
(1851, 90, 3.0, 40),
(1852, 90, 3.0, 41),
(1853, 90, 2.0, 42),
(1854, 90, 1.0, 43),
(1855, 90, 2.0, 44),
(1856, 90, 1.0, 45),
(1857, 90, 2.0, 46),
(1858, 90, 3.0, 14),
(1859, 90, 3.0, 15),
(1860, 90, 2.0, 16),
(1861, 90, 3.0, 17),
(1862, 90, 3.0, 18),
(1863, 90, 3.0, 19),
(1864, 90, 4.0, 20),
(1865, 90, 2.0, 21),
(1866, 74, 4.0, 7),
(1867, 74, 5.0, 8),
(1868, 74, 5.0, 9),
(1869, 74, 5.0, 10),
(1870, 74, 5.0, 11),
(1871, 74, 4.0, 12),
(1872, 74, 3.0, 13),
(1873, 74, 4.0, 28),
(1874, 74, 4.0, 29),
(1875, 74, 3.0, 30),
(1876, 74, 4.0, 31),
(1877, 74, 5.0, 32),
(1878, 74, 4.0, 33),
(1879, 74, 3.0, 34),
(1880, 74, 4.0, 35),
(1881, 74, 4.0, 36),
(1882, 74, 4.0, 37),
(1883, 74, 5.0, 22),
(1884, 74, 5.0, 23),
(1885, 74, 4.0, 24),
(1886, 74, 5.0, 25),
(1887, 74, 5.0, 26),
(1888, 74, 5.0, 27),
(1889, 74, 4.0, 38),
(1890, 74, 4.0, 39),
(1891, 74, 4.0, 40),
(1892, 74, 5.0, 41),
(1893, 74, 5.0, 42),
(1894, 74, 4.0, 43),
(1895, 74, 5.0, 44),
(1896, 74, 4.0, 45),
(1897, 74, 4.0, 46),
(1898, 74, 5.0, 14),
(1899, 74, 5.0, 15),
(1900, 74, 4.0, 16),
(1901, 74, 4.0, 17),
(1902, 74, 5.0, 18),
(1903, 74, 4.0, 19),
(1904, 74, 4.0, 20),
(1905, 74, 4.0, 21),
(1906, 91, 5.0, 7),
(1907, 91, 5.0, 8),
(1908, 91, 5.0, 9),
(1909, 91, 4.0, 10),
(1910, 91, 5.0, 11),
(1911, 91, 5.0, 12),
(1912, 91, 5.0, 13),
(1913, 91, 4.0, 28),
(1914, 91, 4.0, 29),
(1915, 91, 5.0, 30),
(1916, 91, 4.0, 31),
(1917, 91, 5.0, 32),
(1918, 91, 4.0, 33),
(1919, 91, 3.0, 34),
(1920, 91, 4.0, 35),
(1921, 91, 3.0, 36),
(1922, 91, 4.0, 37),
(1923, 91, 5.0, 22),
(1924, 91, 3.0, 23),
(1925, 91, 4.0, 24),
(1926, 91, 5.0, 25),
(1927, 91, 5.0, 26),
(1928, 91, 5.0, 27),
(1929, 91, 4.0, 38),
(1930, 91, 4.0, 39),
(1931, 91, 4.0, 40),
(1932, 91, 4.0, 41),
(1933, 91, 5.0, 42),
(1934, 91, 4.0, 43),
(1935, 91, 4.0, 44),
(1936, 91, 4.0, 45),
(1937, 91, 3.0, 46),
(1938, 91, 4.0, 14),
(1939, 91, 5.0, 15),
(1940, 91, 5.0, 16),
(1941, 91, 3.0, 17),
(1942, 91, 5.0, 18),
(1943, 91, 4.0, 19),
(1944, 91, 4.0, 20),
(1945, 91, 5.0, 21),
(1946, 92, 5.0, 7),
(1947, 92, 3.0, 8),
(1948, 92, 3.0, 9),
(1949, 92, 4.0, 10),
(1950, 92, 4.0, 11),
(1951, 92, 4.0, 12),
(1952, 92, 4.0, 13),
(1953, 92, 4.0, 28),
(1954, 92, 2.0, 29),
(1955, 92, 4.0, 30),
(1956, 92, 4.0, 31),
(1957, 92, 2.0, 32),
(1958, 92, 2.0, 33),
(1959, 92, 1.0, 34),
(1960, 92, 2.0, 35),
(1961, 92, 2.0, 36),
(1962, 92, 4.0, 37),
(1963, 92, 4.0, 22),
(1964, 92, 2.0, 23),
(1965, 92, 4.0, 24),
(1966, 92, 4.0, 25),
(1967, 92, 3.0, 26),
(1968, 92, 3.0, 27),
(1969, 92, 2.0, 38),
(1970, 92, 2.0, 39),
(1971, 92, 4.0, 40),
(1972, 92, 4.0, 41),
(1973, 92, 3.0, 42),
(1974, 92, 2.0, 43),
(1975, 92, 2.0, 44),
(1976, 92, 2.0, 45),
(1977, 92, 1.0, 46),
(1978, 92, 3.0, 14),
(1979, 92, 4.0, 15),
(1980, 92, 4.0, 16),
(1981, 92, 2.0, 17),
(1982, 92, 2.0, 18),
(1983, 92, 2.0, 19),
(1984, 92, 4.0, 20),
(1985, 92, 2.0, 21),
(1986, 109, 5.0, 7),
(1987, 109, 5.0, 8),
(1988, 109, 4.0, 9),
(1989, 109, 4.0, 10),
(1990, 109, 5.0, 11),
(1991, 109, 4.0, 12),
(1992, 109, 3.0, 13),
(1993, 109, 4.0, 28),
(1994, 109, 5.0, 29),
(1995, 109, 3.0, 30),
(1996, 109, 4.0, 31),
(1997, 109, 5.0, 32),
(1998, 109, 5.0, 33),
(1999, 109, 5.0, 34),
(2000, 109, 5.0, 35),
(2001, 109, 4.0, 36),
(2002, 109, 4.0, 37),
(2003, 109, 5.0, 22),
(2004, 109, 4.0, 23),
(2005, 109, 4.0, 24),
(2006, 109, 5.0, 25),
(2007, 109, 5.0, 26),
(2008, 109, 5.0, 27),
(2009, 109, 4.0, 38),
(2010, 109, 4.0, 39),
(2011, 109, 4.0, 40),
(2012, 109, 4.0, 41),
(2013, 109, 4.0, 42),
(2014, 109, 5.0, 43),
(2015, 109, 5.0, 44),
(2016, 109, 5.0, 45),
(2017, 109, 5.0, 46),
(2018, 109, 5.0, 14),
(2019, 109, 5.0, 15),
(2020, 109, 5.0, 16),
(2021, 109, 4.0, 17),
(2022, 109, 4.0, 18),
(2023, 109, 4.0, 19),
(2024, 109, 5.0, 20),
(2025, 109, 4.0, 21),
(2026, 110, 4.0, 7),
(2027, 110, 4.0, 8),
(2028, 110, 3.0, 9),
(2029, 110, 4.0, 10),
(2030, 110, 4.0, 11),
(2031, 110, 4.0, 12),
(2032, 110, 4.0, 13),
(2033, 110, 2.0, 28),
(2034, 110, 1.0, 29),
(2035, 110, 2.0, 30),
(2036, 110, 1.0, 31),
(2037, 110, 1.0, 32),
(2038, 110, 1.0, 33),
(2039, 110, 1.0, 34),
(2040, 110, 1.0, 35),
(2041, 110, 2.0, 36),
(2042, 110, 4.0, 37),
(2043, 110, 3.0, 22),
(2044, 110, 1.0, 23),
(2045, 110, 2.0, 24),
(2046, 110, 3.0, 25),
(2047, 110, 3.0, 26),
(2048, 110, 5.0, 27),
(2049, 110, 1.0, 38),
(2050, 110, 1.0, 39),
(2051, 110, 4.0, 40),
(2052, 110, 4.0, 41),
(2053, 110, 1.0, 42),
(2054, 110, 1.0, 43),
(2055, 110, 1.0, 44),
(2056, 110, 1.0, 45),
(2057, 110, 1.0, 46),
(2058, 110, 4.0, 14),
(2059, 110, 4.0, 15),
(2060, 110, 4.0, 16),
(2061, 110, 2.0, 17),
(2062, 110, 3.0, 18),
(2063, 110, 1.0, 19),
(2064, 110, 3.0, 20),
(2065, 110, 1.0, 21),
(2066, 89, 3.0, 7),
(2067, 89, 4.0, 8),
(2068, 89, 3.0, 9),
(2069, 89, 3.0, 10),
(2070, 89, 4.0, 11),
(2071, 89, 4.0, 12),
(2072, 89, 4.0, 13),
(2073, 89, 4.0, 28),
(2074, 89, 2.0, 29),
(2075, 89, 3.0, 30),
(2076, 89, 2.0, 31),
(2077, 89, 4.0, 32),
(2078, 89, 1.0, 33),
(2079, 89, 1.0, 34),
(2080, 89, 1.0, 35),
(2081, 89, 1.0, 36),
(2082, 89, 3.0, 37),
(2083, 89, 5.0, 22),
(2084, 89, 5.0, 23),
(2085, 89, 5.0, 24),
(2086, 89, 5.0, 25),
(2087, 89, 5.0, 26),
(2088, 89, 5.0, 27),
(2089, 89, 3.0, 38),
(2090, 89, 4.0, 39),
(2091, 89, 3.0, 40),
(2092, 89, 3.0, 41),
(2093, 89, 2.0, 42),
(2094, 89, 1.0, 43),
(2095, 89, 2.0, 44),
(2096, 89, 2.0, 45),
(2097, 89, 3.0, 46),
(2098, 89, 4.0, 14),
(2099, 89, 3.0, 15),
(2100, 89, 3.0, 16),
(2101, 89, 4.0, 17),
(2102, 89, 3.0, 18),
(2103, 89, 3.0, 19),
(2104, 89, 3.0, 20),
(2105, 89, 2.0, 21),
(2106, 72, 4.0, 7),
(2107, 72, 4.0, 8),
(2108, 72, 4.0, 9),
(2109, 72, 4.0, 10),
(2110, 72, 4.0, 11),
(2111, 72, 4.0, 12),
(2112, 72, 4.0, 13),
(2113, 72, 3.0, 28),
(2114, 72, 3.0, 29),
(2115, 72, 4.0, 30),
(2116, 72, 3.0, 31),
(2117, 72, 4.0, 32),
(2118, 72, 1.0, 33),
(2119, 72, 1.0, 34),
(2120, 72, 1.0, 35),
(2121, 72, 1.0, 36),
(2122, 72, 3.0, 37),
(2123, 72, 5.0, 22),
(2124, 72, 5.0, 23),
(2125, 72, 4.0, 24),
(2126, 72, 5.0, 25),
(2127, 72, 5.0, 26),
(2128, 72, 5.0, 27),
(2129, 72, 3.0, 38),
(2130, 72, 4.0, 39),
(2131, 72, 3.0, 40),
(2132, 72, 3.0, 41),
(2133, 72, 4.0, 42),
(2134, 72, 4.0, 43),
(2135, 72, 4.0, 44),
(2136, 72, 3.0, 45),
(2137, 72, 4.0, 46),
(2138, 72, 3.0, 14),
(2139, 72, 3.0, 15),
(2140, 72, 3.0, 16),
(2141, 72, 4.0, 17),
(2142, 72, 3.0, 18),
(2143, 72, 3.0, 19),
(2144, 72, 4.0, 20),
(2145, 72, 4.0, 21),
(2146, 71, 4.0, 7),
(2147, 71, 4.0, 8),
(2148, 71, 4.0, 9),
(2149, 71, 4.0, 10),
(2150, 71, 4.0, 11),
(2151, 71, 4.0, 12),
(2152, 71, 4.0, 13),
(2153, 71, 4.0, 28),
(2154, 71, 2.0, 29),
(2155, 71, 4.0, 30),
(2156, 71, 3.0, 31),
(2157, 71, 4.0, 32),
(2158, 71, 1.0, 33),
(2159, 71, 3.0, 34),
(2160, 71, 2.0, 35),
(2161, 71, 1.0, 36),
(2162, 71, 3.0, 37),
(2163, 71, 3.0, 22),
(2164, 71, 3.0, 23),
(2165, 71, 4.0, 24),
(2166, 71, 4.0, 25),
(2167, 71, 4.0, 26),
(2168, 71, 5.0, 27),
(2169, 71, 3.0, 38),
(2170, 71, 4.0, 39),
(2171, 71, 3.0, 40),
(2172, 71, 3.0, 41),
(2173, 71, 3.0, 42),
(2174, 71, 4.0, 43),
(2175, 71, 4.0, 44),
(2176, 71, 4.0, 45),
(2177, 71, 3.0, 46),
(2178, 71, 2.0, 14),
(2179, 71, 4.0, 15),
(2180, 71, 3.0, 16),
(2181, 71, 4.0, 17),
(2182, 71, 3.0, 18),
(2183, 71, 4.0, 19),
(2184, 71, 3.0, 20),
(2185, 71, 3.0, 21),
(2186, 118, 3.0, 7),
(2187, 118, 4.0, 8),
(2188, 118, 3.0, 9),
(2189, 118, 3.0, 10),
(2190, 118, 4.0, 11),
(2191, 118, 4.0, 12),
(2192, 118, 4.0, 13),
(2193, 118, 3.0, 28),
(2194, 118, 3.0, 29),
(2195, 118, 4.0, 30),
(2196, 118, 3.0, 31),
(2197, 118, 3.0, 32),
(2198, 118, 4.0, 33),
(2199, 118, 2.0, 34),
(2200, 118, 4.0, 35),
(2201, 118, 4.0, 36),
(2202, 118, 4.0, 37),
(2203, 118, 4.0, 22),
(2204, 118, 4.0, 23),
(2205, 118, 4.0, 24),
(2206, 118, 3.0, 25),
(2207, 118, 4.0, 26),
(2208, 118, 3.0, 27),
(2209, 118, 4.0, 38),
(2210, 118, 4.0, 39),
(2211, 118, 3.0, 40),
(2212, 118, 3.0, 41),
(2213, 118, 4.0, 42),
(2214, 118, 4.0, 43),
(2215, 118, 4.0, 44),
(2216, 118, 4.0, 45),
(2217, 118, 4.0, 46),
(2218, 118, 4.0, 14),
(2219, 118, 4.0, 15),
(2220, 118, 4.0, 16),
(2221, 118, 3.0, 17),
(2222, 118, 3.0, 18),
(2223, 118, 3.0, 19),
(2224, 118, 3.0, 20),
(2225, 118, 3.0, 21),
(2226, 127, 4.0, 7),
(2227, 127, 4.0, 8),
(2228, 127, 3.0, 9),
(2229, 127, 4.0, 10),
(2230, 127, 4.0, 11),
(2231, 127, 4.0, 12),
(2232, 127, 4.0, 13),
(2233, 127, 4.0, 28),
(2234, 127, 3.0, 29),
(2235, 127, 4.0, 30),
(2236, 127, 4.0, 31),
(2237, 127, 3.0, 32),
(2238, 127, 4.0, 33),
(2239, 127, 2.0, 34),
(2240, 127, 2.0, 35),
(2241, 127, 4.0, 36),
(2242, 127, 4.0, 37),
(2243, 127, 4.0, 22),
(2244, 127, 3.0, 23),
(2245, 127, 3.0, 24),
(2246, 127, 3.0, 25),
(2247, 127, 4.0, 26),
(2248, 127, 4.0, 27),
(2249, 127, 3.0, 38),
(2250, 127, 3.0, 39),
(2251, 127, 3.0, 40),
(2252, 127, 3.0, 41),
(2253, 127, 4.0, 42),
(2254, 127, 4.0, 43),
(2255, 127, 4.0, 44),
(2256, 127, 3.0, 45),
(2257, 127, 3.0, 46),
(2258, 127, 4.0, 14),
(2259, 127, 3.0, 15),
(2260, 127, 3.0, 16),
(2261, 127, 4.0, 17),
(2262, 127, 3.0, 18),
(2263, 127, 3.0, 19),
(2264, 127, 4.0, 20),
(2265, 127, 4.0, 21),
(2266, 121, 2.0, 7),
(2267, 121, 3.0, 8),
(2268, 121, 3.0, 9),
(2269, 121, 4.0, 10),
(2270, 121, 4.0, 11),
(2271, 121, 4.0, 12),
(2272, 121, 4.0, 13),
(2273, 121, 4.0, 28),
(2274, 121, 2.0, 29),
(2275, 121, 4.0, 30),
(2276, 121, 4.0, 31),
(2277, 121, 5.0, 32),
(2278, 121, 4.0, 33),
(2279, 121, 1.0, 34),
(2280, 121, 4.0, 35),
(2281, 121, 1.0, 36),
(2282, 121, 4.0, 37),
(2283, 121, 4.0, 22),
(2284, 121, 4.0, 23),
(2285, 121, 5.0, 24),
(2286, 121, 5.0, 25),
(2287, 121, 5.0, 26),
(2288, 121, 5.0, 27),
(2289, 121, 4.0, 38),
(2290, 121, 4.0, 39),
(2291, 121, 3.0, 40),
(2292, 121, 3.0, 41),
(2293, 121, 5.0, 42),
(2294, 121, 4.0, 43),
(2295, 121, 4.0, 44),
(2296, 121, 5.0, 45),
(2297, 121, 4.0, 46),
(2298, 121, 4.0, 14),
(2299, 121, 3.0, 15),
(2300, 121, 4.0, 16),
(2301, 121, 1.0, 17),
(2302, 121, 3.0, 18),
(2303, 121, 4.0, 19),
(2304, 121, 4.0, 20),
(2305, 121, 4.0, 21),
(2306, 130, 3.0, 7),
(2307, 130, 3.0, 8),
(2308, 130, 3.0, 9),
(2309, 130, 4.0, 10),
(2310, 130, 4.0, 11),
(2311, 130, 4.0, 12),
(2312, 130, 4.0, 13),
(2313, 130, 3.0, 28),
(2314, 130, 2.0, 29),
(2315, 130, 4.0, 30),
(2316, 130, 4.0, 31),
(2317, 130, 4.0, 32),
(2318, 130, 4.0, 33),
(2319, 130, 1.0, 34),
(2320, 130, 3.0, 35),
(2321, 130, 2.0, 36),
(2322, 130, 4.0, 37),
(2323, 130, 5.0, 22),
(2324, 130, 4.0, 23),
(2325, 130, 4.0, 24),
(2326, 130, 5.0, 25),
(2327, 130, 5.0, 26),
(2328, 130, 5.0, 27),
(2329, 130, 4.0, 38),
(2330, 130, 5.0, 39),
(2331, 130, 3.0, 40),
(2332, 130, 3.0, 41),
(2333, 130, 4.0, 42),
(2334, 130, 4.0, 43),
(2335, 130, 4.0, 44),
(2336, 130, 4.0, 45),
(2337, 130, 4.0, 46),
(2338, 130, 4.0, 14),
(2339, 130, 4.0, 15),
(2340, 130, 4.0, 16),
(2341, 130, 4.0, 17),
(2342, 130, 4.0, 18),
(2343, 130, 4.0, 19),
(2344, 130, 4.0, 20),
(2345, 130, 5.0, 21),
(2346, 123, 3.0, 7),
(2347, 123, 4.0, 8),
(2348, 123, 3.0, 9),
(2349, 123, 3.0, 10),
(2350, 123, 4.0, 11),
(2351, 123, 3.0, 12),
(2352, 123, 4.0, 13),
(2353, 123, 3.0, 28),
(2354, 123, 2.0, 29),
(2355, 123, 3.0, 30),
(2356, 123, 2.0, 31),
(2357, 123, 3.0, 32),
(2358, 123, 2.0, 33),
(2359, 123, 2.0, 34),
(2360, 123, 2.0, 35),
(2361, 123, 3.0, 36),
(2362, 123, 3.0, 37),
(2363, 123, 3.0, 22),
(2364, 123, 4.0, 23),
(2365, 123, 3.0, 24),
(2366, 123, 3.0, 25),
(2367, 123, 4.0, 26),
(2368, 123, 4.0, 27),
(2369, 123, 3.0, 38),
(2370, 123, 3.0, 39),
(2371, 123, 4.0, 40),
(2372, 123, 4.0, 41),
(2373, 123, 4.0, 42),
(2374, 123, 4.0, 43),
(2375, 123, 4.0, 44),
(2376, 123, 3.0, 45),
(2377, 123, 3.0, 46),
(2378, 123, 4.0, 14),
(2379, 123, 3.0, 15),
(2380, 123, 2.0, 16),
(2381, 123, 3.0, 17),
(2382, 123, 3.0, 18),
(2383, 123, 3.0, 19),
(2384, 123, 3.0, 20),
(2385, 123, 3.0, 21),
(2386, 132, 3.0, 7),
(2387, 132, 3.0, 8),
(2388, 132, 3.0, 9),
(2389, 132, 3.0, 10),
(2390, 132, 4.0, 11),
(2391, 132, 2.0, 12),
(2392, 132, 3.0, 13),
(2393, 132, 3.0, 28),
(2394, 132, 3.0, 29),
(2395, 132, 4.0, 30),
(2396, 132, 2.0, 31),
(2397, 132, 4.0, 32),
(2398, 132, 3.0, 33),
(2399, 132, 2.0, 34),
(2400, 132, 2.0, 35),
(2401, 132, 2.0, 36),
(2402, 132, 4.0, 37),
(2403, 132, 4.0, 22),
(2404, 132, 3.0, 23),
(2405, 132, 3.0, 24),
(2406, 132, 4.0, 25),
(2407, 132, 4.0, 26),
(2408, 132, 4.0, 27),
(2409, 132, 3.0, 38),
(2410, 132, 3.0, 39),
(2411, 132, 4.0, 40),
(2412, 132, 4.0, 41),
(2413, 132, 4.0, 42),
(2414, 132, 3.0, 43),
(2415, 132, 4.0, 44),
(2416, 132, 3.0, 45),
(2417, 132, 3.0, 46),
(2418, 132, 3.0, 14),
(2419, 132, 3.0, 15),
(2420, 132, 3.0, 16),
(2421, 132, 3.0, 17),
(2422, 132, 2.0, 18),
(2423, 132, 3.0, 19),
(2424, 132, 4.0, 20),
(2425, 132, 3.0, 21),
(2426, 122, 3.0, 7),
(2427, 122, 2.0, 8),
(2428, 122, 3.0, 9),
(2429, 122, 2.0, 10),
(2430, 122, 3.0, 11),
(2431, 122, 3.0, 12),
(2432, 122, 2.0, 13),
(2433, 122, 2.0, 28),
(2434, 122, 2.0, 29),
(2435, 122, 3.0, 30),
(2436, 122, 1.0, 31),
(2437, 122, 2.0, 32),
(2438, 122, 2.0, 33),
(2439, 122, 1.0, 34),
(2440, 122, 2.0, 35),
(2441, 122, 4.0, 36),
(2442, 122, 3.0, 37),
(2443, 122, 3.0, 22),
(2444, 122, 4.0, 23),
(2445, 122, 4.0, 24),
(2446, 122, 3.0, 25),
(2447, 122, 3.0, 26),
(2448, 122, 3.0, 27),
(2449, 122, 2.0, 38),
(2450, 122, 2.0, 39),
(2451, 122, 3.0, 40),
(2452, 122, 3.0, 41),
(2453, 122, 2.0, 42),
(2454, 122, 4.0, 43),
(2455, 122, 3.0, 44),
(2456, 122, 4.0, 45),
(2457, 122, 3.0, 46),
(2458, 122, 1.0, 14),
(2459, 122, 3.0, 15),
(2460, 122, 1.0, 16),
(2461, 122, 1.0, 17),
(2462, 122, 2.0, 18),
(2463, 122, 2.0, 19),
(2464, 122, 3.0, 20),
(2465, 122, 2.0, 21),
(2466, 131, 1.0, 7),
(2467, 131, 4.0, 8),
(2468, 131, 4.0, 9),
(2469, 131, 3.0, 10),
(2470, 131, 4.0, 11),
(2471, 131, 1.0, 12),
(2472, 131, 3.0, 13),
(2473, 131, 4.0, 28),
(2474, 131, 4.0, 29),
(2475, 131, 4.0, 30),
(2476, 131, 2.0, 31),
(2477, 131, 3.0, 32),
(2478, 131, 4.0, 33),
(2479, 131, 2.0, 34),
(2480, 131, 4.0, 35),
(2481, 131, 2.0, 36),
(2482, 131, 3.0, 37),
(2483, 131, 3.0, 22),
(2484, 131, 4.0, 23),
(2485, 131, 2.0, 24),
(2486, 131, 3.0, 25),
(2487, 131, 3.0, 26),
(2488, 131, 3.0, 27),
(2489, 131, 2.0, 38),
(2490, 131, 2.0, 39),
(2491, 131, 3.0, 40),
(2492, 131, 3.0, 41),
(2493, 131, 3.0, 42),
(2494, 131, 4.0, 43),
(2495, 131, 4.0, 44),
(2496, 131, 4.0, 45),
(2497, 131, 4.0, 46),
(2498, 131, 4.0, 14),
(2499, 131, 4.0, 15),
(2500, 131, 3.0, 16),
(2501, 131, 3.0, 17),
(2502, 131, 3.0, 18),
(2503, 131, 3.0, 19),
(2504, 131, 4.0, 20),
(2505, 131, 4.0, 21),
(2506, 145, 3.0, 7),
(2507, 145, 3.0, 8),
(2508, 145, 3.0, 9),
(2509, 145, 3.0, 10),
(2510, 145, 2.0, 11),
(2511, 145, 2.0, 12),
(2512, 145, 3.0, 13),
(2513, 145, 3.0, 28),
(2514, 145, 2.0, 29),
(2515, 145, 3.0, 30),
(2516, 145, 1.0, 31),
(2517, 145, 3.0, 32),
(2518, 145, 3.0, 33),
(2519, 145, 2.0, 34),
(2520, 145, 3.0, 35),
(2521, 145, 1.0, 36),
(2522, 145, 3.0, 37),
(2523, 145, 3.0, 22),
(2524, 145, 2.0, 23),
(2525, 145, 3.0, 24),
(2526, 145, 3.0, 25),
(2527, 145, 3.0, 26),
(2528, 145, 3.0, 27),
(2529, 145, 3.0, 38),
(2530, 145, 3.0, 39),
(2531, 145, 3.0, 40),
(2532, 145, 3.0, 41),
(2533, 145, 3.0, 42),
(2534, 145, 3.0, 43),
(2535, 145, 3.0, 44),
(2536, 145, 3.0, 45),
(2537, 145, 3.0, 46),
(2538, 145, 3.0, 14),
(2539, 145, 3.0, 15),
(2540, 145, 3.0, 16),
(2541, 145, 2.0, 17),
(2542, 145, 2.0, 18),
(2543, 145, 2.0, 19),
(2544, 145, 3.0, 20),
(2545, 145, 2.0, 21),
(2546, 146, 3.0, 7),
(2547, 146, 2.0, 8),
(2548, 146, 3.0, 9),
(2549, 146, 3.0, 10),
(2550, 146, 3.0, 11),
(2551, 146, 3.0, 12),
(2552, 146, 3.0, 13),
(2553, 146, 3.0, 28),
(2554, 146, 2.0, 29),
(2555, 146, 3.0, 30),
(2556, 146, 1.0, 31),
(2557, 146, 3.0, 32),
(2558, 146, 2.0, 33),
(2559, 146, 2.0, 34),
(2560, 146, 2.0, 35),
(2561, 146, 1.0, 36),
(2562, 146, 3.0, 37),
(2563, 146, 3.0, 22),
(2564, 146, 2.0, 23),
(2565, 146, 3.0, 24),
(2566, 146, 3.0, 25),
(2567, 146, 3.0, 26),
(2568, 146, 3.0, 27),
(2569, 146, 2.0, 38),
(2570, 146, 2.0, 39),
(2571, 146, 3.0, 40),
(2572, 146, 3.0, 41),
(2573, 146, 2.0, 42),
(2574, 146, 2.0, 43),
(2575, 146, 2.0, 44),
(2576, 146, 2.0, 45),
(2577, 146, 2.0, 46),
(2578, 146, 3.0, 14),
(2579, 146, 3.0, 15),
(2580, 146, 3.0, 16),
(2581, 146, 2.0, 17),
(2582, 146, 2.0, 18),
(2583, 146, 2.0, 19),
(2584, 146, 3.0, 20),
(2585, 146, 1.0, 21),
(2586, 151, 3.0, 7),
(2587, 151, 3.0, 8),
(2588, 151, 3.0, 9),
(2589, 151, 3.0, 10),
(2590, 151, 3.0, 11),
(2591, 151, 3.0, 12),
(2592, 151, 3.0, 13),
(2593, 151, 3.0, 28),
(2594, 151, 2.0, 29),
(2595, 151, 3.0, 30),
(2596, 151, 2.0, 31),
(2597, 151, 4.0, 32),
(2598, 151, 4.0, 33),
(2599, 151, 4.0, 34),
(2600, 151, 4.0, 35),
(2601, 151, 2.0, 36),
(2602, 151, 2.0, 37),
(2603, 151, 4.0, 22),
(2604, 151, 3.0, 23),
(2605, 151, 2.0, 24),
(2606, 151, 4.0, 25),
(2607, 151, 4.0, 26),
(2608, 151, 4.0, 27),
(2609, 151, 2.0, 38),
(2610, 151, 3.0, 39),
(2611, 151, 3.0, 40),
(2612, 151, 3.0, 41),
(2613, 151, 3.0, 42),
(2614, 151, 3.0, 43),
(2615, 151, 4.0, 44),
(2616, 151, 4.0, 45),
(2617, 151, 3.0, 46),
(2618, 151, 4.0, 14),
(2619, 151, 4.0, 15),
(2620, 151, 4.0, 16),
(2621, 151, 4.0, 17),
(2622, 151, 3.0, 18),
(2623, 151, 3.0, 19),
(2624, 151, 3.0, 20),
(2625, 151, 3.0, 21),
(2626, 152, 3.0, 7),
(2627, 152, 3.0, 8),
(2628, 152, 3.0, 9),
(2629, 152, 4.0, 10),
(2630, 152, 3.0, 11),
(2631, 152, 3.0, 12),
(2632, 152, 4.0, 13),
(2633, 152, 3.0, 28),
(2634, 152, 4.0, 29),
(2635, 152, 3.0, 30),
(2636, 152, 2.0, 31),
(2637, 152, 2.0, 32),
(2638, 152, 2.0, 33),
(2639, 152, 3.0, 34),
(2640, 152, 3.0, 35),
(2641, 152, 2.0, 36),
(2642, 152, 2.0, 37),
(2643, 152, 3.0, 22),
(2644, 152, 3.0, 23),
(2645, 152, 2.0, 24),
(2646, 152, 3.0, 25),
(2647, 152, 3.0, 26),
(2648, 152, 3.0, 27),
(2649, 152, 2.0, 38),
(2650, 152, 2.0, 39),
(2651, 152, 3.0, 40),
(2652, 152, 3.0, 41),
(2653, 152, 2.0, 42),
(2654, 152, 3.0, 43),
(2655, 152, 2.0, 44),
(2656, 152, 2.0, 45),
(2657, 152, 2.0, 46),
(2658, 152, 3.0, 14),
(2659, 152, 2.0, 15),
(2660, 152, 2.0, 16),
(2661, 152, 2.0, 17),
(2662, 152, 2.0, 18),
(2663, 152, 2.0, 19),
(2664, 152, 4.0, 20),
(2665, 152, 2.0, 21),
(2666, 154, 3.0, 7),
(2667, 154, 4.0, 8),
(2668, 154, 4.0, 9),
(2669, 154, 4.0, 10),
(2670, 154, 3.0, 11),
(2671, 154, 4.0, 12),
(2672, 154, 4.0, 13),
(2673, 154, 3.0, 28),
(2674, 154, 2.0, 29),
(2675, 154, 3.0, 30),
(2676, 154, 3.0, 31),
(2677, 154, 4.0, 32),
(2678, 154, 2.0, 33),
(2679, 154, 2.0, 34),
(2680, 154, 3.0, 35),
(2681, 154, 3.0, 36),
(2682, 154, 4.0, 37),
(2683, 154, 4.0, 22),
(2684, 154, 2.0, 23),
(2685, 154, 3.0, 24),
(2686, 154, 2.0, 25),
(2687, 154, 4.0, 26),
(2688, 154, 4.0, 27),
(2689, 154, 3.0, 38),
(2690, 154, 3.0, 39),
(2691, 154, 4.0, 40),
(2692, 154, 4.0, 41),
(2693, 154, 4.0, 42),
(2694, 154, 2.0, 43),
(2695, 154, 3.0, 44),
(2696, 154, 4.0, 45),
(2697, 154, 2.0, 46),
(2698, 154, 4.0, 14),
(2699, 154, 3.0, 15),
(2700, 154, 2.0, 16),
(2701, 154, 2.0, 17),
(2702, 154, 3.0, 18),
(2703, 154, 3.0, 19),
(2704, 154, 4.0, 20),
(2705, 154, 3.0, 21),
(2706, 153, 3.0, 7),
(2707, 153, 3.0, 8),
(2708, 153, 3.0, 9),
(2709, 153, 3.0, 10),
(2710, 153, 3.0, 11),
(2711, 153, 3.0, 12),
(2712, 153, 3.0, 13),
(2713, 153, 4.0, 28),
(2714, 153, 3.0, 29),
(2715, 153, 4.0, 30),
(2716, 153, 2.0, 31),
(2717, 153, 3.0, 32),
(2718, 153, 2.0, 33),
(2719, 153, 3.0, 34),
(2720, 153, 3.0, 35),
(2721, 153, 2.0, 36),
(2722, 153, 4.0, 37),
(2723, 153, 4.0, 22),
(2724, 153, 3.0, 23),
(2725, 153, 4.0, 24),
(2726, 153, 4.0, 25),
(2727, 153, 4.0, 26),
(2728, 153, 4.0, 27),
(2729, 153, 3.0, 38),
(2730, 153, 3.0, 39),
(2731, 153, 4.0, 40),
(2732, 153, 4.0, 41),
(2733, 153, 4.0, 42),
(2734, 153, 3.0, 43),
(2735, 153, 3.0, 44),
(2736, 153, 4.0, 45),
(2737, 153, 4.0, 46),
(2738, 153, 4.0, 14),
(2739, 153, 3.0, 15),
(2740, 153, 2.0, 16),
(2741, 153, 3.0, 17),
(2742, 153, 4.0, 18),
(2743, 153, 4.0, 19),
(2744, 153, 3.0, 20),
(2745, 153, 4.0, 21),
(2746, 149, 3.0, 7),
(2747, 149, 3.0, 8),
(2748, 149, 2.0, 9),
(2749, 149, 3.0, 10),
(2750, 149, 3.0, 11),
(2751, 149, 4.0, 12),
(2752, 149, 4.0, 13),
(2753, 149, 3.0, 28),
(2754, 149, 2.0, 29),
(2755, 149, 3.0, 30),
(2756, 149, 2.0, 31),
(2757, 149, 3.0, 32),
(2758, 149, 2.0, 33),
(2759, 149, 3.0, 34),
(2760, 149, 1.0, 35),
(2761, 149, 1.0, 36),
(2762, 149, 2.0, 37),
(2763, 149, 4.0, 22),
(2764, 149, 2.0, 23),
(2765, 149, 4.0, 24),
(2766, 149, 4.0, 25),
(2767, 149, 4.0, 26),
(2768, 149, 4.0, 27),
(2769, 149, 2.0, 38),
(2770, 149, 3.0, 39),
(2771, 149, 3.0, 40),
(2772, 149, 3.0, 41),
(2773, 149, 4.0, 42),
(2774, 149, 4.0, 43),
(2775, 149, 3.0, 44),
(2776, 149, 3.0, 45),
(2777, 149, 3.0, 46),
(2778, 149, 4.0, 14),
(2779, 149, 4.0, 15),
(2780, 149, 3.0, 16),
(2781, 149, 4.0, 17),
(2782, 149, 4.0, 18),
(2783, 149, 3.0, 19),
(2784, 149, 3.0, 20),
(2785, 149, 2.0, 21),
(2786, 150, 3.0, 7),
(2787, 150, 3.0, 8),
(2788, 150, 2.0, 9),
(2789, 150, 3.0, 10),
(2790, 150, 4.0, 11),
(2791, 150, 4.0, 12),
(2792, 150, 4.0, 13),
(2793, 150, 2.0, 28),
(2794, 150, 3.0, 29),
(2795, 150, 3.0, 30),
(2796, 150, 2.0, 31),
(2797, 150, 3.0, 32),
(2798, 150, 2.0, 33),
(2799, 150, 1.0, 34),
(2800, 150, 1.0, 35),
(2801, 150, 1.0, 36),
(2802, 150, 2.0, 37),
(2803, 150, 3.0, 22),
(2804, 150, 2.0, 23),
(2805, 150, 2.0, 24),
(2806, 150, 3.0, 25),
(2807, 150, 3.0, 26),
(2808, 150, 4.0, 27),
(2809, 150, 2.0, 38),
(2810, 150, 2.0, 39),
(2811, 150, 3.0, 40),
(2812, 150, 3.0, 41),
(2813, 150, 3.0, 42),
(2814, 150, 2.0, 43),
(2815, 150, 1.0, 44),
(2816, 150, 2.0, 45),
(2817, 150, 2.0, 46),
(2818, 150, 3.0, 14),
(2819, 150, 3.0, 15),
(2820, 150, 3.0, 16),
(2821, 150, 2.0, 17),
(2822, 150, 3.0, 18),
(2823, 150, 3.0, 19),
(2824, 150, 3.0, 20),
(2825, 150, 2.0, 21),
(2826, 156, 3.0, 7),
(2827, 156, 3.0, 8),
(2828, 156, 2.0, 9),
(2829, 156, 2.0, 10),
(2830, 156, 5.0, 11),
(2831, 156, 3.0, 12),
(2832, 156, 4.0, 13),
(2833, 156, 3.0, 28),
(2834, 156, 1.0, 29),
(2835, 156, 4.0, 30),
(2836, 156, 5.0, 31),
(2837, 156, 3.0, 32),
(2838, 156, 4.0, 33),
(2839, 156, 1.0, 34),
(2840, 156, 1.0, 35),
(2841, 156, 1.0, 36),
(2842, 156, 1.0, 37),
(2843, 156, 4.0, 22),
(2844, 156, 2.0, 23),
(2845, 156, 1.0, 24),
(2846, 156, 3.0, 25),
(2847, 156, 2.0, 26),
(2848, 156, 3.0, 27),
(2849, 156, 3.0, 38),
(2850, 156, 4.0, 39),
(2851, 156, 3.0, 40),
(2852, 156, 3.0, 41),
(2853, 156, 4.0, 42),
(2854, 156, 2.0, 43),
(2855, 156, 2.0, 44),
(2856, 156, 1.0, 45),
(2857, 156, 2.0, 46),
(2858, 156, 4.0, 14),
(2859, 156, 4.0, 15),
(2860, 156, 2.0, 16),
(2861, 156, 1.0, 17),
(2862, 156, 2.0, 18),
(2863, 156, 2.0, 19),
(2864, 156, 4.0, 20),
(2865, 156, 2.0, 21),
(2867, 69, 1.0, 8),
(2868, 69, 1.0, 9),
(2869, 69, 1.0, 10),
(2870, 69, 1.0, 11),
(2871, 69, 1.0, 12),
(2872, 69, 1.0, 13),
(2873, 69, 1.0, 28),
(2874, 69, 1.0, 29),
(2875, 69, 1.0, 30),
(2876, 69, 1.0, 31),
(2877, 69, 1.0, 32),
(2878, 69, 1.0, 33),
(2879, 69, 1.0, 34),
(2880, 69, 1.0, 35),
(2881, 69, 1.0, 36),
(2882, 69, 1.0, 37),
(2883, 69, 1.0, 22),
(2884, 69, 1.0, 23),
(2885, 69, 1.0, 24),
(2886, 69, 1.0, 25),
(2887, 69, 1.0, 26),
(2888, 69, 1.0, 27),
(2889, 69, 1.0, 38),
(2890, 69, 1.0, 39),
(2891, 69, 1.0, 40),
(2892, 69, 1.0, 41),
(2893, 69, 1.0, 42),
(2894, 69, 1.0, 43),
(2895, 69, 1.0, 44),
(2896, 69, 1.0, 45),
(2897, 69, 1.0, 46),
(2898, 69, 1.0, 14),
(2899, 69, 1.0, 15),
(2900, 69, 1.0, 16),
(2901, 69, 1.0, 17),
(2902, 69, 1.0, 18),
(2903, 69, 1.0, 19),
(2904, 69, 1.0, 20),
(2905, 69, 1.0, 21),
(2906, 159, 4.0, 7),
(2907, 159, 4.0, 8),
(2908, 159, 4.0, 9),
(2909, 159, 4.0, 10),
(2910, 159, 5.0, 11),
(2911, 159, 4.0, 12),
(2912, 159, 4.0, 13),
(2913, 159, 4.0, 28),
(2914, 159, 4.0, 29),
(2915, 159, 4.0, 30),
(2916, 159, 3.0, 31),
(2917, 159, 3.0, 32),
(2918, 159, 3.0, 33),
(2919, 159, 3.0, 34),
(2920, 159, 2.0, 35),
(2921, 159, 2.0, 36),
(2922, 159, 3.0, 37),
(2923, 159, 4.0, 22),
(2924, 159, 4.0, 23),
(2925, 159, 5.0, 24),
(2926, 159, 4.0, 25),
(2927, 159, 4.0, 26),
(2928, 159, 4.0, 27),
(2929, 159, 3.0, 38),
(2930, 159, 4.0, 39),
(2931, 159, 3.0, 40),
(2932, 159, 3.0, 41),
(2933, 159, 4.0, 42),
(2934, 159, 5.0, 43),
(2935, 159, 4.0, 44),
(2936, 159, 4.0, 45),
(2937, 159, 4.0, 46),
(2938, 159, 4.0, 14),
(2939, 159, 4.0, 15),
(2940, 159, 3.0, 16),
(2941, 159, 3.0, 17),
(2942, 159, 4.0, 18),
(2943, 159, 4.0, 19),
(2944, 159, 4.0, 20),
(2945, 159, 4.0, 21),
(2946, 174, 3.0, 7),
(2947, 174, 2.0, 8),
(2948, 174, 3.0, 9),
(2949, 174, 3.0, 10),
(2950, 174, 3.0, 11),
(2951, 174, 4.0, 12),
(2952, 174, 2.0, 13),
(2953, 174, 4.0, 28),
(2954, 174, 4.0, 29),
(2955, 174, 3.0, 30),
(2956, 174, 3.0, 31),
(2957, 174, 4.0, 32),
(2958, 174, 1.0, 33),
(2959, 174, 1.0, 34),
(2960, 174, 1.0, 35),
(2961, 174, 1.0, 36),
(2962, 174, 3.0, 37),
(2963, 174, 4.0, 22),
(2964, 174, 4.0, 23),
(2965, 174, 3.0, 24),
(2966, 174, 4.0, 25),
(2967, 174, 4.0, 26),
(2968, 174, 4.0, 27),
(2969, 174, 3.0, 38),
(2970, 174, 4.0, 39),
(2971, 174, 3.0, 40),
(2972, 174, 3.0, 41),
(2973, 174, 3.0, 42),
(2974, 174, 2.0, 43),
(2975, 174, 3.0, 44),
(2976, 174, 2.0, 45),
(2977, 174, 2.0, 46),
(2978, 174, 4.0, 14),
(2979, 174, 4.0, 15),
(2980, 174, 4.0, 16),
(2981, 174, 2.0, 17),
(2982, 174, 3.0, 18),
(2983, 174, 3.0, 19),
(2984, 174, 3.0, 20),
(2985, 174, 2.0, 21),
(2986, 160, 4.0, 7),
(2987, 160, 4.0, 8),
(2988, 160, 4.0, 9),
(2989, 160, 4.0, 10),
(2990, 160, 5.0, 11),
(2991, 160, 4.0, 12),
(2992, 160, 4.0, 13),
(2993, 160, 3.0, 28),
(2994, 160, 3.0, 29),
(2995, 160, 4.0, 30),
(2996, 160, 2.0, 31),
(2997, 160, 3.0, 32),
(2998, 160, 3.0, 33),
(2999, 160, 3.0, 34),
(3000, 160, 2.0, 35),
(3001, 160, 2.0, 36),
(3002, 160, 3.0, 37),
(3003, 160, 4.0, 22),
(3004, 160, 4.0, 23),
(3005, 160, 4.0, 24),
(3006, 160, 4.0, 25),
(3007, 160, 4.0, 26),
(3008, 160, 4.0, 27),
(3009, 160, 3.0, 38),
(3010, 160, 4.0, 39),
(3011, 160, 3.0, 40),
(3012, 160, 3.0, 41),
(3013, 160, 4.0, 42),
(3014, 160, 4.0, 43),
(3015, 160, 4.0, 44),
(3016, 160, 3.0, 45),
(3017, 160, 4.0, 46),
(3018, 160, 4.0, 14),
(3019, 160, 4.0, 15),
(3020, 160, 4.0, 16),
(3021, 160, 3.0, 17),
(3022, 160, 3.0, 18),
(3023, 160, 3.0, 19),
(3024, 160, 4.0, 20),
(3025, 160, 4.0, 21),
(3026, 189, 2.0, 7),
(3027, 189, 2.0, 8),
(3028, 189, 4.0, 9),
(3029, 189, 3.0, 10),
(3030, 189, 4.0, 11),
(3031, 189, 3.0, 12),
(3032, 189, 5.0, 13),
(3033, 189, 4.0, 28),
(3034, 189, 5.0, 29),
(3035, 189, 3.0, 30),
(3036, 189, 5.0, 31),
(3037, 189, 3.0, 32),
(3038, 189, 5.0, 33),
(3039, 189, 5.0, 34),
(3040, 189, 3.0, 35),
(3041, 189, 3.0, 36),
(3042, 189, 5.0, 37),
(3043, 189, 5.0, 22),
(3044, 189, 3.0, 23),
(3045, 189, 4.0, 24),
(3046, 189, 5.0, 25),
(3047, 189, 3.0, 26),
(3048, 189, 5.0, 27),
(3049, 189, 3.0, 38),
(3050, 189, 4.0, 39),
(3051, 189, 3.0, 40),
(3052, 189, 3.0, 41),
(3053, 189, 5.0, 42),
(3054, 189, 5.0, 43),
(3055, 189, 5.0, 44),
(3056, 189, 5.0, 45),
(3057, 189, 5.0, 46),
(3058, 189, 5.0, 14),
(3059, 189, 4.0, 15),
(3060, 189, 3.0, 16),
(3061, 189, 5.0, 17),
(3062, 189, 2.0, 18),
(3063, 189, 5.0, 19),
(3064, 189, 5.0, 20),
(3065, 189, 5.0, 21),
(3066, 170, 2.0, 7),
(3067, 170, 3.0, 8),
(3068, 170, 2.0, 9),
(3069, 170, 4.0, 10),
(3070, 170, 3.0, 11),
(3071, 170, 4.0, 12),
(3072, 170, 4.0, 13),
(3073, 170, 3.0, 28),
(3074, 170, 4.0, 29),
(3075, 170, 4.0, 30),
(3076, 170, 2.0, 31),
(3077, 170, 3.0, 32),
(3078, 170, 2.0, 33),
(3079, 170, 1.0, 34),
(3080, 170, 1.0, 35),
(3081, 170, 1.0, 36),
(3082, 170, 2.0, 37),
(3083, 170, 5.0, 22),
(3084, 170, 5.0, 23),
(3085, 170, 5.0, 24),
(3086, 170, 5.0, 25),
(3087, 170, 5.0, 26),
(3088, 170, 5.0, 27),
(3089, 170, 2.0, 38),
(3090, 170, 3.0, 39),
(3091, 170, 3.0, 40),
(3092, 170, 3.0, 41),
(3093, 170, 4.0, 42),
(3094, 170, 2.0, 43),
(3095, 170, 3.0, 44),
(3096, 170, 2.0, 45),
(3097, 170, 3.0, 46),
(3098, 170, 4.0, 14),
(3099, 170, 3.0, 15),
(3100, 170, 3.0, 16),
(3101, 170, 4.0, 17),
(3102, 170, 4.0, 18),
(3103, 170, 3.0, 19),
(3104, 170, 3.0, 20),
(3105, 170, 3.0, 21),
(3106, 168, 3.0, 7),
(3107, 168, 3.0, 8),
(3108, 168, 2.0, 9),
(3109, 168, 3.0, 10),
(3110, 168, 3.0, 11),
(3111, 168, 4.0, 12),
(3112, 168, 4.0, 13),
(3113, 168, 3.0, 28),
(3114, 168, 3.0, 29),
(3115, 168, 3.0, 30),
(3116, 168, 2.0, 31),
(3117, 168, 4.0, 32),
(3118, 168, 2.0, 33),
(3119, 168, 2.0, 34),
(3120, 168, 2.0, 35),
(3121, 168, 1.0, 36),
(3122, 168, 2.0, 37),
(3123, 168, 4.0, 22),
(3124, 168, 3.0, 23),
(3125, 168, 5.0, 24),
(3126, 168, 5.0, 25),
(3127, 168, 5.0, 26),
(3128, 168, 5.0, 27),
(3129, 168, 2.0, 38),
(3130, 168, 3.0, 39),
(3131, 168, 3.0, 40),
(3132, 168, 3.0, 41),
(3133, 168, 3.0, 42),
(3134, 168, 4.0, 43),
(3135, 168, 3.0, 44),
(3136, 168, 4.0, 45),
(3137, 168, 4.0, 46),
(3138, 168, 3.0, 14),
(3139, 168, 3.0, 15),
(3140, 168, 3.0, 16),
(3141, 168, 4.0, 17),
(3142, 168, 3.0, 18),
(3143, 168, 3.0, 19),
(3144, 168, 3.0, 20),
(3145, 168, 3.0, 21),
(3146, 169, 3.0, 7),
(3147, 169, 3.0, 8),
(3148, 169, 3.0, 9),
(3149, 169, 3.0, 10),
(3150, 169, 4.0, 11),
(3151, 169, 3.0, 12),
(3152, 169, 3.0, 13),
(3153, 169, 3.0, 28),
(3154, 169, 3.0, 29),
(3155, 169, 3.0, 30),
(3156, 169, 2.0, 31),
(3157, 169, 4.0, 32),
(3158, 169, 2.0, 33),
(3159, 169, 2.0, 34),
(3160, 169, 2.0, 35),
(3161, 169, 1.0, 36),
(3162, 169, 2.0, 37),
(3163, 169, 4.0, 22),
(3164, 169, 4.0, 23),
(3165, 169, 4.0, 24),
(3166, 169, 4.0, 25),
(3167, 169, 5.0, 26),
(3168, 169, 5.0, 27),
(3169, 169, 2.0, 38),
(3170, 169, 4.0, 39),
(3171, 169, 3.0, 40),
(3172, 169, 3.0, 41),
(3173, 169, 4.0, 42),
(3174, 169, 2.0, 43),
(3175, 169, 3.0, 44),
(3176, 169, 3.0, 45),
(3177, 169, 4.0, 46),
(3178, 169, 3.0, 14),
(3179, 169, 3.0, 15),
(3180, 169, 3.0, 16),
(3181, 169, 4.0, 17),
(3182, 169, 3.0, 18),
(3183, 169, 4.0, 19),
(3184, 169, 4.0, 20),
(3185, 169, 4.0, 21),
(3186, 162, 4.0, 7),
(3187, 162, 4.0, 8),
(3188, 162, 4.0, 9),
(3189, 162, 4.0, 10),
(3190, 162, 5.0, 11),
(3191, 162, 4.0, 12),
(3192, 162, 4.0, 13),
(3193, 162, 4.0, 28),
(3194, 162, 3.0, 29),
(3195, 162, 4.0, 30),
(3196, 162, 3.0, 31),
(3197, 162, 3.0, 32),
(3198, 162, 3.0, 33),
(3199, 162, 3.0, 34),
(3200, 162, 2.0, 35),
(3201, 162, 2.0, 36),
(3202, 162, 3.0, 37),
(3203, 162, 4.0, 22),
(3204, 162, 4.0, 23),
(3205, 162, 5.0, 24),
(3206, 162, 4.0, 25),
(3207, 162, 4.0, 26),
(3208, 162, 4.0, 27),
(3209, 162, 3.0, 38),
(3210, 162, 4.0, 39),
(3211, 162, 3.0, 40),
(3212, 162, 3.0, 41),
(3213, 162, 4.0, 42),
(3214, 162, 4.0, 43),
(3215, 162, 4.0, 44),
(3216, 162, 4.0, 45),
(3217, 162, 4.0, 46),
(3218, 162, 5.0, 14),
(3219, 162, 4.0, 15),
(3220, 162, 3.0, 16),
(3221, 162, 4.0, 17),
(3222, 162, 2.0, 18),
(3223, 162, 2.0, 19),
(3224, 162, 4.0, 20),
(3225, 162, 4.0, 21),
(3226, 163, 4.0, 7),
(3227, 163, 4.0, 8),
(3228, 163, 3.0, 9),
(3229, 163, 3.0, 10),
(3230, 163, 4.0, 11),
(3231, 163, 3.0, 12),
(3232, 163, 4.0, 13),
(3233, 163, 3.0, 28),
(3234, 163, 4.0, 29),
(3235, 163, 4.0, 30),
(3236, 163, 4.0, 31),
(3237, 163, 4.0, 32),
(3238, 163, 3.0, 33),
(3239, 163, 3.0, 34),
(3240, 163, 4.0, 35),
(3241, 163, 3.0, 36),
(3242, 163, 4.0, 37),
(3243, 163, 4.0, 22),
(3244, 163, 4.0, 23),
(3245, 163, 3.0, 24),
(3246, 163, 3.0, 25),
(3247, 163, 3.0, 26),
(3248, 163, 4.0, 27),
(3249, 163, 3.0, 38),
(3250, 163, 4.0, 39),
(3251, 163, 4.0, 40),
(3252, 163, 4.0, 41),
(3253, 163, 3.0, 42),
(3254, 163, 3.0, 43),
(3255, 163, 3.0, 44),
(3256, 163, 3.0, 45),
(3257, 163, 4.0, 46),
(3258, 163, 4.0, 14),
(3259, 163, 4.0, 15),
(3260, 163, 4.0, 16),
(3261, 163, 3.0, 17),
(3262, 163, 4.0, 18),
(3263, 163, 4.0, 19),
(3264, 163, 4.0, 20),
(3265, 163, 3.0, 21),
(3266, 177, 4.0, 7),
(3267, 177, 4.0, 8),
(3268, 177, 4.0, 9),
(3269, 177, 4.0, 10),
(3270, 177, 4.0, 11),
(3271, 177, 3.0, 12),
(3272, 177, 4.0, 13),
(3273, 177, 4.0, 28),
(3274, 177, 3.0, 29),
(3275, 177, 3.0, 30),
(3276, 177, 2.0, 31),
(3277, 177, 4.0, 32),
(3278, 177, 4.0, 33),
(3279, 177, 4.0, 34),
(3280, 177, 3.0, 35),
(3281, 177, 3.0, 36),
(3282, 177, 4.0, 37),
(3283, 177, 5.0, 22),
(3284, 177, 4.0, 23),
(3285, 177, 5.0, 24),
(3286, 177, 4.0, 25),
(3287, 177, 5.0, 26),
(3288, 177, 5.0, 27),
(3289, 177, 3.0, 38),
(3290, 177, 3.0, 39),
(3291, 177, 4.0, 40),
(3292, 177, 4.0, 41),
(3293, 177, 4.0, 42),
(3294, 177, 3.0, 43),
(3295, 177, 3.0, 44),
(3296, 177, 3.0, 45),
(3297, 177, 4.0, 46),
(3298, 177, 4.0, 14),
(3299, 177, 4.0, 15),
(3300, 177, 2.0, 16),
(3301, 177, 3.0, 17),
(3302, 177, 5.0, 18),
(3303, 177, 4.0, 19),
(3304, 177, 4.0, 20),
(3305, 177, 3.0, 21),
(3306, 176, 3.0, 7),
(3307, 176, 3.0, 8),
(3308, 176, 2.0, 9),
(3309, 176, 4.0, 10),
(3310, 176, 5.0, 11),
(3311, 176, 3.0, 12),
(3312, 176, 4.0, 13),
(3313, 176, 3.0, 28),
(3314, 176, 3.0, 29),
(3315, 176, 3.0, 30),
(3316, 176, 2.0, 31),
(3317, 176, 4.0, 32),
(3318, 176, 3.0, 33),
(3319, 176, 5.0, 34),
(3320, 176, 3.0, 35),
(3321, 176, 3.0, 36),
(3322, 176, 3.0, 37),
(3323, 176, 4.0, 22),
(3324, 176, 5.0, 23),
(3325, 176, 4.0, 24),
(3326, 176, 3.0, 25),
(3327, 176, 3.0, 26),
(3328, 176, 3.0, 27),
(3329, 176, 2.0, 38),
(3330, 176, 2.0, 39),
(3331, 176, 3.0, 40),
(3332, 176, 3.0, 41),
(3333, 176, 3.0, 42),
(3334, 176, 3.0, 43),
(3335, 176, 4.0, 44),
(3336, 176, 4.0, 45),
(3337, 176, 3.0, 46),
(3338, 176, 4.0, 14),
(3339, 176, 4.0, 15),
(3340, 176, 3.0, 16),
(3341, 176, 4.0, 17),
(3342, 176, 3.0, 18),
(3343, 176, 4.0, 19),
(3344, 176, 3.0, 20),
(3345, 176, 3.0, 21),
(3346, 178, 4.0, 7),
(3347, 178, 4.0, 8),
(3348, 178, 3.0, 9),
(3349, 178, 4.0, 10),
(3350, 178, 5.0, 11),
(3351, 178, 3.0, 12),
(3352, 178, 4.0, 13),
(3353, 178, 5.0, 28),
(3354, 178, 3.0, 29),
(3355, 178, 4.0, 30),
(3356, 178, 2.0, 31),
(3357, 178, 5.0, 32),
(3358, 178, 3.0, 33),
(3359, 178, 3.0, 34),
(3360, 178, 3.0, 35),
(3361, 178, 3.0, 36),
(3362, 178, 4.0, 37),
(3363, 178, 4.0, 22),
(3364, 178, 5.0, 23),
(3365, 178, 4.0, 24),
(3366, 178, 4.0, 25),
(3367, 178, 4.0, 26),
(3368, 178, 4.0, 27),
(3369, 178, 4.0, 38),
(3370, 178, 5.0, 39),
(3371, 178, 4.0, 40),
(3372, 178, 4.0, 41),
(3373, 178, 4.0, 42),
(3374, 178, 5.0, 43),
(3375, 178, 3.0, 44),
(3376, 178, 3.0, 45),
(3377, 178, 3.0, 46),
(3378, 178, 4.0, 14),
(3379, 178, 4.0, 15),
(3380, 178, 4.0, 16),
(3381, 178, 2.0, 17),
(3382, 178, 4.0, 18),
(3383, 178, 3.0, 19),
(3384, 178, 5.0, 20),
(3385, 178, 4.0, 21),
(3387, 187, 5.0, 8),
(3388, 187, 5.0, 9),
(3389, 187, 5.0, 10),
(3390, 187, 5.0, 11),
(3391, 187, 5.0, 12),
(3392, 187, 5.0, 13),
(3393, 187, 5.0, 28),
(3394, 187, 5.0, 29),
(3395, 187, 5.0, 30),
(3396, 187, 5.0, 31),
(3397, 187, 4.0, 32),
(3398, 187, 3.0, 33),
(3399, 187, 4.0, 34),
(3400, 187, 3.0, 35),
(3401, 187, 3.0, 36),
(3402, 187, 4.0, 37),
(3403, 187, 3.0, 22),
(3404, 187, 4.0, 23),
(3405, 187, 5.0, 24),
(3406, 187, 3.0, 25),
(3407, 187, 4.0, 26),
(3408, 187, 3.0, 27),
(3409, 187, 5.0, 38),
(3410, 187, 5.0, 39),
(3411, 187, 2.0, 40),
(3412, 187, 3.0, 41),
(3413, 187, 5.0, 42),
(3414, 187, 3.0, 43),
(3415, 187, 5.0, 44),
(3416, 187, 5.0, 45),
(3417, 187, 5.0, 46),
(3418, 187, 5.0, 14),
(3419, 187, 5.0, 15),
(3420, 187, 5.0, 16),
(3421, 187, 5.0, 17),
(3422, 187, 4.0, 18),
(3423, 187, 5.0, 19),
(3424, 187, 3.0, 20),
(3425, 187, 3.0, 21),
(3426, 185, 4.0, 7),
(3427, 185, 5.0, 8),
(3428, 185, 5.0, 9),
(3429, 185, 2.0, 10),
(3430, 185, 3.0, 11),
(3431, 185, 3.0, 12),
(3432, 185, 2.0, 13),
(3433, 185, 5.0, 28),
(3434, 185, 4.0, 29),
(3435, 185, 5.0, 30),
(3436, 185, 3.0, 31),
(3437, 185, 5.0, 32),
(3438, 185, 2.0, 33),
(3439, 185, 5.0, 34),
(3440, 185, 3.0, 35),
(3441, 185, 3.0, 36),
(3442, 185, 3.0, 37),
(3443, 185, 5.0, 22),
(3444, 185, 5.0, 23),
(3445, 185, 5.0, 24),
(3446, 185, 5.0, 25),
(3447, 185, 5.0, 26),
(3448, 185, 5.0, 27),
(3449, 185, 4.0, 38),
(3450, 185, 3.0, 39),
(3451, 185, 3.0, 40),
(3452, 185, 3.0, 41),
(3453, 185, 5.0, 42),
(3454, 185, 5.0, 43),
(3455, 185, 5.0, 44),
(3456, 185, 3.0, 45),
(3457, 185, 3.0, 46),
(3458, 185, 5.0, 14),
(3459, 185, 3.0, 15),
(3460, 185, 4.0, 16),
(3461, 185, 5.0, 17),
(3462, 185, 5.0, 18),
(3463, 185, 5.0, 19),
(3464, 185, 3.0, 20),
(3465, 185, 3.0, 21),
(3466, 188, 3.0, 7),
(3467, 188, 4.0, 8),
(3468, 188, 3.0, 9),
(3469, 188, 3.0, 10),
(3470, 188, 5.0, 11),
(3471, 188, 4.0, 12),
(3472, 188, 2.0, 13),
(3473, 188, 3.0, 28),
(3474, 188, 4.0, 29),
(3475, 188, 5.0, 30),
(3476, 188, 3.0, 31),
(3477, 188, 5.0, 32),
(3478, 188, 3.0, 33),
(3479, 188, 1.0, 34),
(3480, 188, 5.0, 35),
(3481, 188, 5.0, 36),
(3482, 188, 3.0, 37),
(3483, 188, 5.0, 22),
(3484, 188, 5.0, 23),
(3485, 188, 5.0, 24),
(3486, 188, 5.0, 25),
(3487, 188, 5.0, 26),
(3488, 188, 5.0, 27),
(3489, 188, 3.0, 38),
(3490, 188, 2.0, 39),
(3491, 188, 2.0, 40),
(3492, 188, 2.0, 41),
(3493, 188, 3.0, 42),
(3494, 188, 3.0, 43);
INSERT INTO `rating` (`ratingID`, `assessmentID`, `ratingValue`, `attributeID`) VALUES
(3495, 188, 4.0, 44),
(3496, 188, 3.0, 45),
(3497, 188, 3.0, 46),
(3498, 188, 5.0, 14),
(3499, 188, 3.0, 15),
(3500, 188, 5.0, 16),
(3501, 188, 4.0, 17),
(3502, 188, 2.0, 18),
(3503, 188, 3.0, 19),
(3504, 188, 5.0, 20),
(3505, 188, 3.0, 21),
(3506, 190, 4.0, 7),
(3507, 190, 3.0, 8),
(3508, 190, 3.0, 9),
(3509, 190, 3.0, 10),
(3510, 190, 2.0, 11),
(3511, 190, 5.0, 12),
(3512, 190, 5.0, 13),
(3513, 190, 4.0, 28),
(3514, 190, 3.0, 29),
(3515, 190, 4.0, 30),
(3516, 190, 3.0, 31),
(3517, 190, 5.0, 32),
(3518, 190, 3.0, 33),
(3519, 190, 2.0, 34),
(3520, 190, 2.0, 35),
(3521, 190, 1.0, 36),
(3522, 190, 3.0, 37),
(3523, 190, 5.0, 22),
(3524, 190, 3.0, 23),
(3525, 190, 4.0, 24),
(3526, 190, 4.0, 25),
(3527, 190, 5.0, 26),
(3528, 190, 5.0, 27),
(3529, 190, 4.0, 38),
(3530, 190, 4.0, 39),
(3531, 190, 4.0, 40),
(3532, 190, 5.0, 41),
(3533, 190, 3.0, 42),
(3534, 190, 3.0, 43),
(3535, 190, 3.0, 44),
(3536, 190, 3.0, 45),
(3537, 190, 4.0, 46),
(3538, 190, 5.0, 14),
(3539, 190, 3.0, 15),
(3540, 190, 4.0, 16),
(3541, 190, 4.0, 17),
(3542, 190, 5.0, 18),
(3543, 190, 5.0, 19),
(3544, 190, 5.0, 20),
(3545, 190, 3.0, 21),
(3546, 186, 4.0, 7),
(3547, 186, 5.0, 8),
(3548, 186, 3.0, 9),
(3549, 186, 4.0, 10),
(3550, 186, 4.0, 11),
(3551, 186, 5.0, 12),
(3552, 186, 5.0, 13),
(3553, 186, 5.0, 28),
(3554, 186, 3.0, 29),
(3555, 186, 5.0, 30),
(3556, 186, 5.0, 31),
(3557, 186, 5.0, 32),
(3558, 186, 3.0, 33),
(3559, 186, 1.0, 34),
(3560, 186, 1.0, 35),
(3561, 186, 1.0, 36),
(3562, 186, 3.0, 37),
(3563, 186, 5.0, 22),
(3564, 186, 4.0, 23),
(3565, 186, 5.0, 24),
(3566, 186, 5.0, 25),
(3567, 186, 4.0, 26),
(3568, 186, 3.0, 27),
(3569, 186, 5.0, 38),
(3570, 186, 5.0, 39),
(3571, 186, 3.0, 40),
(3572, 186, 3.0, 41),
(3573, 186, 5.0, 42),
(3574, 186, 4.0, 43),
(3575, 186, 3.0, 44),
(3576, 186, 3.0, 45),
(3577, 186, 5.0, 46),
(3578, 186, 5.0, 14),
(3579, 186, 5.0, 15),
(3580, 186, 4.0, 16),
(3581, 186, 3.0, 17),
(3582, 186, 5.0, 18),
(3583, 186, 5.0, 19),
(3584, 186, 5.0, 20),
(3585, 186, 5.0, 21),
(3586, 184, 2.0, 7),
(3587, 184, 4.0, 8),
(3588, 184, 5.0, 9),
(3589, 184, 4.0, 10),
(3590, 184, 5.0, 11),
(3591, 184, 5.0, 12),
(3592, 184, 4.0, 13),
(3593, 184, 3.0, 28),
(3594, 184, 5.0, 29),
(3595, 184, 4.0, 30),
(3596, 184, 4.0, 31),
(3597, 184, 4.0, 32),
(3598, 184, 3.0, 33),
(3599, 184, 5.0, 34),
(3600, 184, 3.0, 35),
(3601, 184, 4.0, 36),
(3602, 184, 3.0, 37),
(3603, 184, 5.0, 22),
(3604, 184, 3.0, 23),
(3605, 184, 5.0, 24),
(3606, 184, 5.0, 25),
(3607, 184, 5.0, 26),
(3608, 184, 5.0, 27),
(3609, 184, 4.0, 38),
(3610, 184, 5.0, 39),
(3611, 184, 3.0, 40),
(3612, 184, 3.0, 41),
(3613, 184, 5.0, 42),
(3614, 184, 5.0, 43),
(3615, 184, 5.0, 44),
(3616, 184, 5.0, 45),
(3617, 184, 4.0, 46),
(3618, 184, 5.0, 14),
(3619, 184, 4.0, 15),
(3620, 184, 3.0, 16),
(3621, 184, 5.0, 17),
(3622, 184, 5.0, 18),
(3623, 184, 5.0, 19),
(3624, 184, 4.0, 20),
(3625, 184, 5.0, 21),
(3626, 191, 3.0, 7),
(3627, 191, 3.0, 8),
(3628, 191, 5.0, 9),
(3629, 191, 5.0, 10),
(3630, 191, 5.0, 11),
(3631, 191, 4.0, 12),
(3632, 191, 5.0, 13),
(3633, 191, 5.0, 28),
(3634, 191, 2.0, 29),
(3635, 191, 3.0, 30),
(3636, 191, 4.0, 31),
(3637, 191, 5.0, 32),
(3638, 191, 4.0, 33),
(3639, 191, 3.0, 34),
(3640, 191, 3.0, 35),
(3641, 191, 5.0, 36),
(3642, 191, 3.0, 37),
(3643, 191, 3.0, 22),
(3644, 191, 3.0, 23),
(3645, 191, 3.0, 24),
(3646, 191, 3.0, 25),
(3647, 191, 3.0, 26),
(3648, 191, 3.0, 27),
(3649, 191, 4.0, 38),
(3650, 191, 5.0, 39),
(3651, 191, 5.0, 40),
(3652, 191, 5.0, 41),
(3653, 191, 3.0, 42),
(3654, 191, 3.0, 43),
(3655, 191, 3.0, 44),
(3656, 191, 3.0, 45),
(3657, 191, 3.0, 46),
(3658, 191, 4.0, 14),
(3659, 191, 3.0, 15),
(3660, 191, 3.0, 16),
(3661, 191, 3.0, 17),
(3662, 191, 3.0, 18),
(3663, 191, 3.0, 19),
(3664, 191, 5.0, 20),
(3665, 191, 5.0, 21),
(3666, 164, 3.0, 7),
(3667, 164, 4.0, 8),
(3668, 164, 3.0, 9),
(3669, 164, 3.0, 10),
(3670, 164, 2.0, 11),
(3671, 164, 2.0, 12),
(3672, 164, 3.0, 13),
(3673, 164, 3.0, 28),
(3674, 164, 2.0, 29),
(3675, 164, 2.0, 30),
(3676, 164, 3.0, 31),
(3677, 164, 4.0, 32),
(3678, 164, 4.0, 33),
(3679, 164, 1.0, 34),
(3680, 164, 3.0, 35),
(3681, 164, 1.0, 36),
(3682, 164, 4.0, 37),
(3683, 164, 3.0, 22),
(3684, 164, 3.0, 23),
(3685, 164, 4.0, 24),
(3686, 164, 3.0, 25),
(3687, 164, 3.0, 26),
(3688, 164, 4.0, 27),
(3689, 164, 3.0, 38),
(3690, 164, 3.0, 39),
(3691, 164, 4.0, 40),
(3692, 164, 4.0, 41),
(3693, 164, 2.0, 42),
(3694, 164, 2.0, 43),
(3695, 164, 2.0, 44),
(3696, 164, 2.0, 45),
(3697, 164, 2.0, 46),
(3698, 164, 3.0, 14),
(3699, 164, 3.0, 15),
(3700, 164, 3.0, 16),
(3701, 164, 3.0, 17),
(3702, 164, 3.0, 18),
(3703, 164, 4.0, 19),
(3704, 164, 3.0, 20),
(3705, 164, 3.0, 21),
(3706, 179, 4.0, 7),
(3707, 179, 5.0, 8),
(3708, 179, 4.0, 9),
(3709, 179, 4.0, 10),
(3710, 179, 4.0, 11),
(3711, 179, 4.0, 12),
(3712, 179, 4.0, 13),
(3713, 179, 4.0, 28),
(3714, 179, 3.0, 29),
(3715, 179, 4.0, 30),
(3716, 179, 5.0, 31),
(3717, 179, 5.0, 32),
(3718, 179, 3.0, 33),
(3719, 179, 3.0, 34),
(3720, 179, 4.0, 35),
(3721, 179, 2.0, 36),
(3722, 179, 4.0, 37),
(3723, 179, 4.0, 22),
(3724, 179, 4.0, 23),
(3725, 179, 4.0, 24),
(3726, 179, 4.0, 25),
(3727, 179, 5.0, 26),
(3728, 179, 5.0, 27),
(3729, 179, 5.0, 38),
(3730, 179, 4.0, 39),
(3731, 179, 4.0, 40),
(3732, 179, 4.0, 41),
(3733, 179, 4.0, 42),
(3734, 179, 4.0, 43),
(3735, 179, 5.0, 44),
(3736, 179, 4.0, 45),
(3737, 179, 5.0, 46),
(3738, 179, 4.0, 14),
(3739, 179, 5.0, 15),
(3740, 179, 5.0, 16),
(3741, 179, 5.0, 17),
(3742, 179, 4.0, 18),
(3743, 179, 4.0, 19),
(3744, 179, 4.0, 20),
(3745, 179, 4.0, 21),
(3746, 180, 4.0, 7),
(3747, 180, 4.0, 8),
(3748, 180, 2.0, 9),
(3749, 180, 4.0, 10),
(3750, 180, 4.0, 11),
(3751, 180, 2.0, 12),
(3752, 180, 3.0, 13),
(3753, 180, 3.0, 28),
(3754, 180, 3.0, 29),
(3755, 180, 2.0, 30),
(3756, 180, 4.0, 31),
(3757, 180, 3.0, 32),
(3758, 180, 3.0, 33),
(3759, 180, 2.0, 34),
(3760, 180, 4.0, 35),
(3761, 180, 2.0, 36),
(3762, 180, 3.0, 37),
(3763, 180, 3.0, 22),
(3764, 180, 4.0, 23),
(3765, 180, 4.0, 24),
(3766, 180, 2.0, 25),
(3767, 180, 4.0, 26),
(3768, 180, 4.0, 27),
(3769, 180, 2.0, 38),
(3770, 180, 2.0, 39),
(3771, 180, 3.0, 40),
(3772, 180, 3.0, 41),
(3773, 180, 3.0, 42),
(3774, 180, 3.0, 43),
(3775, 180, 3.0, 44),
(3776, 180, 3.0, 45),
(3777, 180, 3.0, 46),
(3778, 180, 3.0, 14),
(3779, 180, 3.0, 15),
(3780, 180, 4.0, 16),
(3781, 180, 3.0, 17),
(3782, 180, 3.0, 18),
(3783, 180, 3.0, 19),
(3784, 180, 3.0, 20),
(3785, 180, 3.0, 21),
(3787, 166, 2.0, 8),
(3788, 166, 3.0, 9),
(3789, 166, 3.0, 10),
(3790, 166, 3.0, 11),
(3791, 166, 3.0, 12),
(3792, 166, 3.0, 13),
(3793, 166, 3.0, 28),
(3794, 166, 3.0, 29),
(3795, 166, 3.0, 30),
(3796, 166, 4.0, 31),
(3797, 166, 3.0, 32),
(3798, 166, 4.0, 33),
(3799, 166, 2.0, 34),
(3800, 166, 3.0, 35),
(3801, 166, 1.0, 36),
(3802, 166, 3.0, 37),
(3803, 166, 4.0, 22),
(3804, 166, 4.0, 23),
(3805, 166, 5.0, 24),
(3806, 166, 4.0, 25),
(3807, 166, 4.0, 26),
(3808, 166, 5.0, 27),
(3809, 166, 3.0, 38),
(3810, 166, 4.0, 39),
(3811, 166, 3.0, 40),
(3812, 166, 3.0, 41),
(3813, 166, 3.0, 42),
(3814, 166, 4.0, 43),
(3815, 166, 4.0, 44),
(3816, 166, 4.0, 45),
(3817, 166, 4.0, 46),
(3818, 166, 3.0, 14),
(3819, 166, 3.0, 15),
(3820, 166, 3.0, 16),
(3821, 166, 3.0, 17),
(3822, 166, 3.0, 18),
(3823, 166, 3.0, 19),
(3824, 166, 3.0, 20),
(3825, 166, 4.0, 21),
(3826, 171, 5.0, 7),
(3827, 171, 5.0, 8),
(3828, 171, 4.0, 9),
(3829, 171, 4.0, 10),
(3830, 171, 4.0, 11),
(3831, 171, 4.0, 12),
(3832, 171, 4.0, 13),
(3833, 171, 4.0, 28),
(3834, 171, 4.0, 29),
(3835, 171, 4.0, 30),
(3836, 171, 5.0, 31),
(3837, 171, 5.0, 32),
(3838, 171, 4.0, 33),
(3839, 171, 4.0, 34),
(3840, 171, 5.0, 35),
(3841, 171, 2.0, 36),
(3842, 171, 4.0, 37),
(3843, 171, 5.0, 22),
(3844, 171, 5.0, 23),
(3845, 171, 4.0, 24),
(3846, 171, 5.0, 25),
(3847, 171, 5.0, 26),
(3848, 171, 5.0, 27),
(3849, 171, 3.0, 38),
(3850, 171, 4.0, 39),
(3851, 171, 5.0, 40),
(3852, 171, 5.0, 41),
(3853, 171, 5.0, 42),
(3854, 171, 4.0, 43),
(3855, 171, 4.0, 44),
(3856, 171, 4.0, 45),
(3857, 171, 4.0, 46),
(3858, 171, 5.0, 14),
(3859, 171, 4.0, 15),
(3860, 171, 4.0, 16),
(3861, 171, 4.0, 17),
(3862, 171, 5.0, 18),
(3863, 171, 4.0, 19),
(3864, 171, 3.0, 20),
(3865, 171, 3.0, 21),
(3866, 175, 3.0, 7),
(3867, 175, 4.0, 8),
(3868, 175, 4.0, 9),
(3869, 175, 3.0, 10),
(3870, 175, 4.0, 11),
(3871, 175, 3.0, 12),
(3872, 175, 4.0, 13),
(3873, 175, 3.0, 28),
(3874, 175, 2.0, 29),
(3875, 175, 4.0, 30),
(3876, 175, 4.0, 31),
(3877, 175, 4.0, 32),
(3878, 175, 2.0, 33),
(3879, 175, 1.0, 34),
(3880, 175, 2.0, 35),
(3881, 175, 1.0, 36),
(3882, 175, 2.0, 37),
(3883, 175, 3.0, 22),
(3884, 175, 2.0, 23),
(3885, 175, 3.0, 24),
(3886, 175, 3.0, 25),
(3887, 175, 4.0, 26),
(3888, 175, 4.0, 27),
(3889, 175, 3.0, 38),
(3890, 175, 4.0, 39),
(3891, 175, 2.0, 40),
(3892, 175, 3.0, 41),
(3893, 175, 4.0, 42),
(3894, 175, 2.0, 43),
(3895, 175, 4.0, 44),
(3896, 175, 3.0, 45),
(3897, 175, 4.0, 46),
(3898, 175, 4.0, 14),
(3899, 175, 3.0, 15),
(3900, 175, 2.0, 16),
(3901, 175, 3.0, 17),
(3902, 175, 3.0, 18),
(3903, 175, 3.0, 19),
(3904, 175, 4.0, 20),
(3905, 175, 3.0, 21),
(3906, 182, 3.0, 7),
(3907, 182, 3.0, 8),
(3908, 182, 2.0, 9),
(3909, 182, 3.0, 10),
(3910, 182, 3.0, 11),
(3911, 182, 3.0, 12),
(3912, 182, 3.0, 13),
(3913, 182, 3.0, 28),
(3914, 182, 3.0, 29),
(3915, 182, 3.0, 30),
(3916, 182, 3.0, 31),
(3917, 182, 4.0, 32),
(3918, 182, 4.0, 33),
(3919, 182, 2.0, 34),
(3920, 182, 4.0, 35),
(3921, 182, 2.0, 36),
(3922, 182, 3.0, 37),
(3923, 182, 4.0, 22),
(3924, 182, 4.0, 23),
(3925, 182, 3.0, 24),
(3926, 182, 4.0, 25),
(3927, 182, 4.0, 26),
(3928, 182, 4.0, 27),
(3929, 182, 3.0, 38),
(3930, 182, 4.0, 39),
(3931, 182, 3.0, 40),
(3932, 182, 4.0, 41),
(3933, 182, 3.0, 42),
(3934, 182, 3.0, 43),
(3935, 182, 3.0, 44),
(3936, 182, 3.0, 45),
(3937, 182, 3.0, 46),
(3938, 182, 3.0, 14),
(3939, 182, 3.0, 15),
(3940, 182, 3.0, 16),
(3941, 182, 3.0, 17),
(3942, 182, 2.0, 18),
(3943, 182, 3.0, 19),
(3944, 182, 3.0, 20),
(3945, 182, 3.0, 21),
(3946, 167, 3.0, 7),
(3947, 167, 3.0, 8),
(3948, 167, 3.0, 9),
(3949, 167, 3.0, 10),
(3950, 167, 3.0, 11),
(3951, 167, 3.0, 12),
(3952, 167, 4.0, 13),
(3953, 167, 4.0, 28),
(3954, 167, 3.0, 29),
(3955, 167, 4.0, 30),
(3956, 167, 4.0, 31),
(3957, 167, 4.0, 32),
(3958, 167, 4.0, 33),
(3959, 167, 2.0, 34),
(3960, 167, 3.0, 35),
(3961, 167, 1.0, 36),
(3962, 167, 3.0, 37),
(3963, 167, 3.0, 22),
(3964, 167, 3.0, 23),
(3965, 167, 3.0, 24),
(3966, 167, 3.0, 25),
(3967, 167, 4.0, 26),
(3968, 167, 4.0, 27),
(3969, 167, 3.0, 38),
(3970, 167, 4.0, 39),
(3971, 167, 4.0, 40),
(3972, 167, 4.0, 41),
(3973, 167, 3.0, 42),
(3974, 167, 3.0, 43),
(3975, 167, 4.0, 44),
(3976, 167, 3.0, 45),
(3977, 167, 3.0, 46),
(3978, 167, 4.0, 14),
(3979, 167, 3.0, 15),
(3980, 167, 3.0, 16),
(3981, 167, 2.0, 17),
(3982, 167, 3.0, 18),
(3983, 167, 3.0, 19),
(3984, 167, 3.0, 20),
(3985, 167, 5.0, 21),
(3986, 165, 2.0, 7),
(3987, 165, 2.0, 8),
(3988, 165, 2.0, 9),
(3989, 165, 3.0, 10),
(3990, 165, 3.0, 11),
(3991, 165, 2.0, 12),
(3992, 165, 3.0, 13),
(3993, 165, 2.0, 28),
(3994, 165, 2.0, 29),
(3995, 165, 3.0, 30),
(3996, 165, 2.0, 31),
(3997, 165, 3.0, 32),
(3998, 165, 3.0, 33),
(3999, 165, 2.0, 34),
(4000, 165, 2.0, 35),
(4001, 165, 1.0, 36),
(4002, 165, 3.0, 37),
(4003, 165, 3.0, 22),
(4004, 165, 3.0, 23),
(4005, 165, 3.0, 24),
(4006, 165, 3.0, 25),
(4007, 165, 3.0, 26),
(4008, 165, 3.0, 27),
(4009, 165, 3.0, 38),
(4010, 165, 3.0, 39),
(4011, 165, 3.0, 40),
(4012, 165, 3.0, 41),
(4013, 165, 2.0, 42),
(4014, 165, 2.0, 43),
(4015, 165, 2.0, 44),
(4016, 165, 2.0, 45),
(4017, 165, 2.0, 46),
(4018, 165, 3.0, 14),
(4019, 165, 3.0, 15),
(4020, 165, 3.0, 16),
(4021, 165, 3.0, 17),
(4022, 165, 3.0, 18),
(4023, 165, 2.0, 19),
(4024, 165, 3.0, 20),
(4025, 165, 2.0, 21),
(4026, 183, 4.0, 7),
(4027, 183, 4.0, 8),
(4028, 183, 4.0, 9),
(4029, 183, 4.0, 10),
(4030, 183, 4.0, 11),
(4031, 183, 4.0, 12),
(4032, 183, 4.0, 13),
(4033, 183, 4.0, 28),
(4034, 183, 3.0, 29),
(4035, 183, 4.0, 30),
(4036, 183, 5.0, 31),
(4037, 183, 5.0, 32),
(4038, 183, 4.0, 33),
(4039, 183, 2.0, 34),
(4040, 183, 3.0, 35),
(4041, 183, 2.0, 36),
(4042, 183, 3.0, 37),
(4043, 183, 4.0, 22),
(4044, 183, 4.0, 23),
(4045, 183, 4.0, 24),
(4046, 183, 4.0, 25),
(4047, 183, 4.0, 26),
(4048, 183, 4.0, 27),
(4049, 183, 4.0, 38),
(4050, 183, 4.0, 39),
(4051, 183, 4.0, 40),
(4052, 183, 4.0, 41),
(4053, 183, 4.0, 42),
(4054, 183, 4.0, 43),
(4055, 183, 4.0, 44),
(4056, 183, 4.0, 45),
(4057, 183, 5.0, 46),
(4058, 183, 4.0, 14),
(4059, 183, 5.0, 15),
(4060, 183, 4.0, 16),
(4061, 183, 3.0, 17),
(4062, 183, 4.0, 18),
(4063, 183, 4.0, 19),
(4064, 183, 4.0, 20),
(4065, 183, 5.0, 21),
(4066, 181, 3.0, 7),
(4067, 181, 3.0, 8),
(4068, 181, 2.0, 9),
(4069, 181, 2.0, 10),
(4070, 181, 3.0, 11),
(4071, 181, 3.0, 12),
(4072, 181, 3.0, 13),
(4073, 181, 3.0, 28),
(4074, 181, 3.0, 29),
(4075, 181, 3.0, 30),
(4076, 181, 2.0, 31),
(4077, 181, 3.0, 32),
(4078, 181, 3.0, 33),
(4079, 181, 2.0, 34),
(4080, 181, 1.0, 35),
(4081, 181, 1.0, 36),
(4082, 181, 2.0, 37),
(4083, 181, 3.0, 22),
(4084, 181, 3.0, 23),
(4085, 181, 3.0, 24),
(4086, 181, 3.0, 25),
(4087, 181, 3.0, 26),
(4088, 181, 3.0, 27),
(4089, 181, 3.0, 42),
(4090, 181, 2.0, 43),
(4091, 181, 2.0, 44),
(4092, 181, 2.0, 45),
(4093, 181, 3.0, 46),
(4094, 181, 2.0, 14),
(4095, 181, 3.0, 15),
(4096, 181, 3.0, 16),
(4097, 181, 2.0, 17),
(4098, 181, 2.0, 18),
(4099, 181, 2.0, 19),
(4100, 181, 2.0, 20),
(4101, 181, 3.0, 21),
(4102, 173, 2.0, 7),
(4103, 173, 3.0, 8),
(4104, 173, 1.0, 9),
(4105, 173, 3.0, 10),
(4106, 173, 2.0, 11),
(4107, 173, 1.0, 12),
(4108, 173, 3.0, 13),
(4109, 173, 2.0, 28),
(4110, 173, 1.0, 29),
(4111, 173, 3.0, 30),
(4112, 173, 1.0, 31),
(4113, 173, 3.0, 32),
(4114, 173, 2.0, 33),
(4115, 173, 3.0, 34),
(4116, 173, 1.0, 35),
(4117, 173, 1.0, 36),
(4118, 173, 2.0, 37),
(4119, 173, 4.0, 22),
(4120, 173, 2.0, 23),
(4121, 173, 4.0, 24),
(4122, 173, 4.0, 25),
(4123, 173, 4.0, 26),
(4124, 173, 4.0, 27),
(4125, 173, 1.0, 38),
(4126, 173, 1.0, 39),
(4127, 173, 3.0, 40),
(4128, 173, 3.0, 41),
(4129, 173, 3.0, 42),
(4130, 173, 3.0, 43),
(4131, 173, 2.0, 44),
(4132, 173, 3.0, 45),
(4133, 173, 2.0, 46),
(4134, 173, 4.0, 14),
(4135, 173, 3.0, 15),
(4136, 173, 3.0, 16),
(4137, 173, 4.0, 17),
(4138, 173, 2.0, 18),
(4139, 173, 3.0, 19),
(4140, 173, 3.0, 20),
(4141, 173, 1.0, 21),
(4142, 172, 1.0, 7),
(4143, 172, 1.0, 8),
(4144, 172, 1.0, 9),
(4145, 172, 1.0, 10),
(4146, 172, 1.0, 11),
(4147, 172, 1.0, 12),
(4148, 172, 1.0, 13),
(4149, 172, 1.0, 28),
(4150, 172, 1.0, 29),
(4151, 172, 1.0, 30),
(4152, 172, 1.0, 31),
(4153, 172, 1.0, 32),
(4154, 172, 1.0, 33),
(4155, 172, 1.0, 34),
(4156, 172, 1.0, 35),
(4157, 172, 1.0, 36),
(4158, 172, 1.0, 37),
(4159, 172, 1.0, 22),
(4160, 172, 1.0, 23),
(4161, 172, 1.0, 24),
(4162, 172, 1.0, 25),
(4163, 172, 1.0, 26),
(4164, 172, 1.0, 27),
(4165, 172, 1.0, 38),
(4166, 172, 1.0, 39),
(4167, 172, 1.0, 40),
(4168, 172, 1.0, 41),
(4169, 172, 1.0, 42),
(4170, 172, 1.0, 43),
(4171, 172, 1.0, 44),
(4172, 172, 1.0, 45),
(4173, 172, 1.0, 46),
(4174, 172, 1.0, 14),
(4175, 172, 1.0, 15),
(4176, 172, 1.0, 16),
(4177, 172, 1.0, 17),
(4178, 172, 1.0, 18),
(4179, 172, 1.0, 19),
(4180, 172, 1.0, 20),
(4181, 172, 1.0, 21),
(4183, 200, 1.0, 8),
(4184, 200, 1.0, 9),
(4185, 200, 1.0, 10),
(4186, 200, 4.0, 11),
(4187, 200, 4.0, 12),
(4188, 200, 4.0, 13),
(4189, 200, 2.0, 28),
(4190, 200, 4.0, 29),
(4191, 200, 4.0, 30),
(4192, 200, 4.0, 31),
(4204, 200, 1.0, 32),
(4205, 200, 1.0, 33),
(4245, 200, 2.0, 34),
(4409, 200, 2.0, 35),
(4410, 200, 1.0, 36),
(4414, 206, 4.0, 7),
(4415, 206, 4.0, 8),
(4416, 206, 1.0, 9),
(4417, 206, 1.0, 10),
(4418, 206, 2.0, 11),
(4419, 204, 2.0, 7),
(4420, 200, 5.0, 7),
(4421, 200, 5.0, 37),
(4423, 208, 5.0, 59),
(4424, 208, 5.0, 60),
(4425, 208, 5.0, 65),
(4426, 208, 5.0, 66),
(4427, 208, 5.0, 63),
(4428, 208, 5.0, 64);

-- --------------------------------------------------------

--
-- Table structure for table `rating_comment`
--

CREATE TABLE IF NOT EXISTS `rating_comment` (
  `rating_commentID` int(11) NOT NULL,
  `ratingID` int(11) NOT NULL,
  `commentID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rating_comment`
--

INSERT INTO `rating_comment` (`rating_commentID`, `ratingID`, `commentID`) VALUES
(89, 4420, 89),
(90, 4423, 90);

-- --------------------------------------------------------

--
-- Table structure for table `rating_screenshot`
--

CREATE TABLE IF NOT EXISTS `rating_screenshot` (
  `rating_screenshotID` int(11) NOT NULL,
  `ratingID` int(11) NOT NULL,
  `screenshotID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `scenario`
--

CREATE TABLE IF NOT EXISTS `scenario` (
  `scenarioID` int(11) NOT NULL,
  `scenarioName` varchar(45) NOT NULL,
  `scenarioDescription` varchar(255) DEFAULT NULL,
  `languageID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scenario`
--

INSERT INTO `scenario` (`scenarioID`, `scenarioName`, `scenarioDescription`, `languageID`) VALUES
(36, 'Team News', 'Finding, selecting, reading Team News', 5),
(37, 'Player Information', 'Finding, selecting and reading player information.', 5),
(38, 'Schedule and Results', 'Finding, selecting, reading, analyzing team schedules and results', 5),
(39, 'Leagues and Other teams', 'Finding, selecting, consuming information about leagues in which this team participates and about other teams this team plays.', 5),
(40, 'Merchandise and Store', 'Finding, selecting and purchasing merchandise relevant to this team.', 5),
(41, 'Ticketing', 'Finding, selecting and purchasing tickets for games for this team.', 5),
(42, 'Mobile Access', 'Accessing the ICT through mobile methods rather than a standard web site browser on a PC', 5),
(43, 'Facebook', 'Finding, selecting, consuming team information through a team facebook page', 5),
(45, 'Twitter', 'Finding, selecting, consuming team information through official team twitter interactions', 5),
(46, 'YouTube', 'Finding, selecting, consuming team information through a team integration with YouTube', 5),
(47, 'Stadium Guide', 'Finding, selecting information about the stadium or arena for game day', 5),
(48, 'Schedule, Results and Other League Info', 'Finding, selecting, reading, analyzing team schedules and results as well as league and other team information ', 5),
(49, 'Game Day and Stadium Guide', 'Finding, selecting information about the game day and the stadium or arena', 5),
(50, 'Social Media Integration', 'Integration of Facebook, Twitter, YouTube within the application', 5),
(51, 'In-Seat Concessions', 'Finding, seleecting, consuming information about In-Seat Concessions', 5);

-- --------------------------------------------------------

--
-- Table structure for table `scenarioAttribute`
--

CREATE TABLE IF NOT EXISTS `scenarioAttribute` (
  `scenarioAttributeID` int(11) NOT NULL,
  `scenarioID` int(11) NOT NULL,
  `attributeID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1541 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scenarioAttribute`
--

INSERT INTO `scenarioAttribute` (`scenarioAttributeID`, `scenarioID`, `attributeID`) VALUES
(901, 36, 7),
(902, 36, 8),
(903, 36, 9),
(904, 36, 10),
(905, 36, 11),
(906, 36, 12),
(907, 36, 13),
(908, 36, 14),
(909, 36, 15),
(910, 36, 16),
(911, 36, 17),
(912, 36, 18),
(913, 36, 19),
(914, 36, 20),
(915, 36, 21),
(916, 36, 22),
(917, 36, 23),
(918, 36, 24),
(919, 36, 25),
(920, 36, 26),
(921, 36, 27),
(922, 36, 28),
(923, 36, 29),
(924, 36, 30),
(925, 36, 31),
(926, 36, 32),
(927, 36, 33),
(928, 36, 34),
(929, 36, 35),
(930, 36, 36),
(931, 36, 37),
(932, 36, 38),
(933, 36, 39),
(934, 36, 40),
(935, 36, 41),
(936, 36, 42),
(937, 36, 43),
(938, 36, 44),
(939, 36, 45),
(940, 36, 46),
(941, 37, 7),
(942, 37, 8),
(943, 37, 9),
(944, 37, 10),
(945, 37, 11),
(946, 37, 12),
(947, 37, 13),
(948, 37, 14),
(949, 37, 15),
(950, 37, 16),
(951, 37, 17),
(952, 37, 18),
(953, 37, 19),
(954, 37, 20),
(955, 37, 21),
(956, 37, 22),
(957, 37, 23),
(958, 37, 24),
(959, 37, 25),
(960, 37, 26),
(961, 37, 27),
(962, 37, 28),
(963, 37, 29),
(964, 37, 30),
(965, 37, 31),
(966, 37, 32),
(967, 37, 33),
(968, 37, 34),
(969, 37, 35),
(970, 37, 36),
(971, 37, 37),
(972, 37, 38),
(973, 37, 39),
(974, 37, 40),
(975, 37, 41),
(976, 37, 42),
(977, 37, 43),
(978, 37, 44),
(979, 37, 45),
(980, 37, 46),
(981, 38, 7),
(982, 38, 8),
(983, 38, 9),
(984, 38, 10),
(985, 38, 11),
(986, 38, 12),
(987, 38, 13),
(988, 38, 14),
(989, 38, 15),
(990, 38, 16),
(991, 38, 17),
(992, 38, 18),
(993, 38, 19),
(994, 38, 20),
(995, 38, 21),
(996, 38, 22),
(997, 38, 23),
(998, 38, 24),
(999, 38, 25),
(1000, 38, 26),
(1001, 38, 27),
(1002, 38, 28),
(1003, 38, 29),
(1004, 38, 30),
(1005, 38, 31),
(1006, 38, 32),
(1007, 38, 33),
(1008, 38, 34),
(1009, 38, 35),
(1010, 38, 36),
(1011, 38, 37),
(1012, 38, 38),
(1013, 38, 39),
(1014, 38, 40),
(1015, 38, 41),
(1016, 38, 42),
(1017, 38, 43),
(1018, 38, 44),
(1019, 38, 45),
(1020, 38, 46),
(1021, 39, 7),
(1022, 39, 8),
(1023, 39, 9),
(1024, 39, 10),
(1025, 39, 11),
(1026, 39, 12),
(1027, 39, 13),
(1028, 39, 14),
(1029, 39, 15),
(1030, 39, 16),
(1031, 39, 17),
(1032, 39, 18),
(1033, 39, 19),
(1034, 39, 20),
(1035, 39, 21),
(1036, 39, 22),
(1037, 39, 23),
(1038, 39, 24),
(1039, 39, 25),
(1040, 39, 26),
(1041, 39, 27),
(1042, 39, 28),
(1043, 39, 29),
(1044, 39, 30),
(1045, 39, 31),
(1046, 39, 32),
(1047, 39, 33),
(1048, 39, 34),
(1049, 39, 35),
(1050, 39, 36),
(1051, 39, 37),
(1052, 39, 38),
(1053, 39, 39),
(1054, 39, 40),
(1055, 39, 41),
(1056, 39, 42),
(1057, 39, 43),
(1058, 39, 44),
(1059, 39, 45),
(1060, 39, 46),
(1061, 40, 7),
(1062, 40, 8),
(1063, 40, 9),
(1064, 40, 10),
(1065, 40, 11),
(1066, 40, 12),
(1067, 40, 13),
(1068, 40, 14),
(1069, 40, 15),
(1070, 40, 16),
(1071, 40, 17),
(1072, 40, 18),
(1073, 40, 19),
(1074, 40, 20),
(1075, 40, 21),
(1076, 40, 22),
(1077, 40, 23),
(1078, 40, 24),
(1079, 40, 25),
(1080, 40, 26),
(1081, 40, 27),
(1082, 40, 28),
(1083, 40, 29),
(1084, 40, 30),
(1085, 40, 31),
(1086, 40, 32),
(1087, 40, 33),
(1088, 40, 34),
(1089, 40, 35),
(1090, 40, 36),
(1091, 40, 37),
(1092, 40, 38),
(1093, 40, 39),
(1094, 40, 40),
(1095, 40, 41),
(1096, 40, 42),
(1097, 40, 43),
(1098, 40, 44),
(1099, 40, 45),
(1100, 40, 46),
(1101, 41, 7),
(1102, 41, 8),
(1103, 41, 9),
(1104, 41, 10),
(1105, 41, 11),
(1106, 41, 12),
(1107, 41, 13),
(1108, 41, 14),
(1109, 41, 15),
(1110, 41, 16),
(1111, 41, 17),
(1112, 41, 18),
(1113, 41, 19),
(1114, 41, 20),
(1115, 41, 21),
(1116, 41, 22),
(1117, 41, 23),
(1118, 41, 24),
(1119, 41, 25),
(1120, 41, 26),
(1121, 41, 27),
(1122, 41, 28),
(1123, 41, 29),
(1124, 41, 30),
(1125, 41, 31),
(1126, 41, 32),
(1127, 41, 33),
(1128, 41, 34),
(1129, 41, 35),
(1130, 41, 36),
(1131, 41, 37),
(1132, 41, 38),
(1133, 41, 39),
(1134, 41, 40),
(1135, 41, 41),
(1136, 41, 42),
(1137, 41, 43),
(1138, 41, 44),
(1139, 41, 45),
(1140, 41, 46),
(1141, 42, 7),
(1142, 42, 8),
(1143, 42, 9),
(1144, 42, 10),
(1145, 42, 11),
(1146, 42, 12),
(1147, 42, 13),
(1148, 42, 14),
(1149, 42, 15),
(1150, 42, 16),
(1151, 42, 17),
(1152, 42, 18),
(1153, 42, 19),
(1154, 42, 20),
(1155, 42, 21),
(1156, 42, 22),
(1157, 42, 23),
(1158, 42, 24),
(1159, 42, 25),
(1160, 42, 26),
(1161, 42, 27),
(1162, 42, 28),
(1163, 42, 29),
(1164, 42, 30),
(1165, 42, 31),
(1166, 42, 32),
(1167, 42, 33),
(1168, 42, 34),
(1169, 42, 35),
(1170, 42, 36),
(1171, 42, 37),
(1172, 42, 38),
(1173, 42, 39),
(1174, 42, 40),
(1175, 42, 41),
(1176, 42, 42),
(1177, 42, 43),
(1178, 42, 44),
(1179, 42, 45),
(1180, 42, 46),
(1181, 43, 7),
(1182, 43, 8),
(1183, 43, 9),
(1184, 43, 10),
(1185, 43, 11),
(1186, 43, 12),
(1187, 43, 13),
(1188, 43, 14),
(1189, 43, 15),
(1190, 43, 16),
(1191, 43, 17),
(1192, 43, 18),
(1193, 43, 19),
(1194, 43, 20),
(1195, 43, 21),
(1196, 43, 22),
(1197, 43, 23),
(1198, 43, 24),
(1199, 43, 25),
(1200, 43, 26),
(1201, 43, 27),
(1202, 43, 28),
(1203, 43, 29),
(1204, 43, 30),
(1205, 43, 31),
(1206, 43, 32),
(1207, 43, 33),
(1208, 43, 34),
(1209, 43, 35),
(1210, 43, 36),
(1211, 43, 37),
(1212, 43, 38),
(1213, 43, 39),
(1214, 43, 40),
(1215, 43, 41),
(1216, 43, 42),
(1217, 43, 43),
(1218, 43, 44),
(1219, 43, 45),
(1220, 43, 46),
(1261, 45, 7),
(1262, 45, 8),
(1263, 45, 9),
(1264, 45, 10),
(1265, 45, 11),
(1266, 45, 12),
(1267, 45, 13),
(1268, 45, 14),
(1269, 45, 15),
(1270, 45, 16),
(1271, 45, 17),
(1272, 45, 18),
(1273, 45, 19),
(1274, 45, 20),
(1275, 45, 21),
(1276, 45, 22),
(1277, 45, 23),
(1278, 45, 24),
(1279, 45, 25),
(1280, 45, 26),
(1281, 45, 27),
(1282, 45, 28),
(1283, 45, 29),
(1284, 45, 30),
(1285, 45, 31),
(1286, 45, 32),
(1287, 45, 33),
(1288, 45, 34),
(1289, 45, 35),
(1290, 45, 36),
(1291, 45, 37),
(1292, 45, 38),
(1293, 45, 39),
(1294, 45, 40),
(1295, 45, 41),
(1296, 45, 42),
(1297, 45, 43),
(1298, 45, 44),
(1299, 45, 45),
(1300, 45, 46),
(1301, 46, 7),
(1302, 46, 8),
(1303, 46, 9),
(1304, 46, 10),
(1305, 46, 11),
(1306, 46, 12),
(1307, 46, 13),
(1308, 46, 14),
(1309, 46, 15),
(1310, 46, 16),
(1311, 46, 17),
(1312, 46, 18),
(1313, 46, 19),
(1314, 46, 20),
(1315, 46, 21),
(1316, 46, 22),
(1317, 46, 23),
(1318, 46, 24),
(1319, 46, 25),
(1320, 46, 26),
(1321, 46, 27),
(1322, 46, 28),
(1323, 46, 29),
(1324, 46, 30),
(1325, 46, 31),
(1326, 46, 32),
(1327, 46, 33),
(1328, 46, 34),
(1329, 46, 35),
(1330, 46, 36),
(1331, 46, 37),
(1332, 46, 38),
(1333, 46, 39),
(1334, 46, 40),
(1335, 46, 41),
(1336, 46, 42),
(1337, 46, 43),
(1338, 46, 44),
(1339, 46, 45),
(1340, 46, 46),
(1341, 47, 7),
(1342, 47, 8),
(1343, 47, 9),
(1344, 47, 10),
(1345, 47, 11),
(1346, 47, 12),
(1347, 47, 13),
(1348, 47, 14),
(1349, 47, 15),
(1350, 47, 16),
(1351, 47, 17),
(1352, 47, 18),
(1353, 47, 19),
(1354, 47, 20),
(1355, 47, 21),
(1356, 47, 22),
(1357, 47, 23),
(1358, 47, 24),
(1359, 47, 25),
(1360, 47, 26),
(1361, 47, 27),
(1362, 47, 28),
(1363, 47, 29),
(1364, 47, 30),
(1365, 47, 31),
(1366, 47, 32),
(1367, 47, 33),
(1368, 47, 34),
(1369, 47, 35),
(1370, 47, 36),
(1371, 47, 37),
(1372, 47, 38),
(1373, 47, 39),
(1374, 47, 40),
(1375, 47, 41),
(1376, 47, 42),
(1377, 47, 43),
(1378, 47, 44),
(1379, 47, 45),
(1380, 47, 46),
(1381, 48, 7),
(1382, 48, 8),
(1383, 48, 9),
(1384, 48, 10),
(1385, 48, 11),
(1386, 48, 12),
(1387, 48, 13),
(1388, 48, 14),
(1389, 48, 15),
(1390, 48, 16),
(1391, 48, 17),
(1392, 48, 18),
(1393, 48, 19),
(1394, 48, 20),
(1395, 48, 21),
(1396, 48, 22),
(1397, 48, 23),
(1398, 48, 24),
(1399, 48, 25),
(1400, 48, 26),
(1401, 48, 27),
(1402, 48, 28),
(1403, 48, 29),
(1404, 48, 30),
(1405, 48, 31),
(1406, 48, 32),
(1407, 48, 33),
(1408, 48, 34),
(1409, 48, 35),
(1410, 48, 36),
(1411, 48, 37),
(1412, 48, 38),
(1413, 48, 39),
(1414, 48, 40),
(1415, 48, 41),
(1416, 48, 42),
(1417, 48, 43),
(1418, 48, 44),
(1419, 48, 45),
(1420, 48, 46),
(1421, 49, 7),
(1422, 49, 8),
(1423, 49, 9),
(1424, 49, 10),
(1425, 49, 11),
(1426, 49, 12),
(1427, 49, 13),
(1428, 49, 14),
(1429, 49, 15),
(1430, 49, 16),
(1431, 49, 17),
(1432, 49, 18),
(1433, 49, 19),
(1434, 49, 20),
(1435, 49, 21),
(1436, 49, 22),
(1437, 49, 23),
(1438, 49, 24),
(1439, 49, 25),
(1440, 49, 26),
(1441, 49, 27),
(1442, 49, 28),
(1443, 49, 29),
(1444, 49, 30),
(1445, 49, 31),
(1446, 49, 32),
(1447, 49, 33),
(1448, 49, 34),
(1449, 49, 35),
(1450, 49, 36),
(1451, 49, 37),
(1452, 49, 38),
(1453, 49, 39),
(1454, 49, 40),
(1455, 49, 41),
(1456, 49, 42),
(1457, 49, 43),
(1458, 49, 44),
(1459, 49, 45),
(1460, 49, 46),
(1461, 50, 7),
(1462, 50, 8),
(1463, 50, 9),
(1464, 50, 10),
(1465, 50, 11),
(1466, 50, 12),
(1467, 50, 13),
(1468, 50, 14),
(1469, 50, 15),
(1470, 50, 16),
(1471, 50, 17),
(1472, 50, 18),
(1473, 50, 19),
(1474, 50, 20),
(1475, 50, 21),
(1476, 50, 22),
(1477, 50, 23),
(1478, 50, 24),
(1479, 50, 25),
(1480, 50, 26),
(1481, 50, 27),
(1482, 50, 28),
(1483, 50, 29),
(1484, 50, 30),
(1485, 50, 31),
(1486, 50, 32),
(1487, 50, 33),
(1488, 50, 34),
(1489, 50, 35),
(1490, 50, 36),
(1491, 50, 37),
(1492, 50, 38),
(1493, 50, 39),
(1494, 50, 40),
(1495, 50, 41),
(1496, 50, 42),
(1497, 50, 43),
(1498, 50, 44),
(1499, 50, 45),
(1500, 50, 46),
(1501, 51, 7),
(1502, 51, 8),
(1503, 51, 9),
(1504, 51, 10),
(1505, 51, 11),
(1506, 51, 12),
(1507, 51, 13),
(1508, 51, 14),
(1509, 51, 15),
(1510, 51, 16),
(1511, 51, 17),
(1512, 51, 18),
(1513, 51, 19),
(1514, 51, 20),
(1515, 51, 21),
(1516, 51, 22),
(1517, 51, 23),
(1518, 51, 24),
(1519, 51, 25),
(1520, 51, 26),
(1521, 51, 27),
(1522, 51, 28),
(1523, 51, 29),
(1524, 51, 30),
(1525, 51, 31),
(1526, 51, 32),
(1527, 51, 33),
(1528, 51, 34),
(1529, 51, 35),
(1530, 51, 36),
(1531, 51, 37),
(1532, 51, 38),
(1533, 51, 39),
(1534, 51, 40),
(1535, 51, 41),
(1536, 51, 42),
(1537, 51, 43),
(1538, 51, 44),
(1539, 51, 45),
(1540, 51, 46);

-- --------------------------------------------------------

--
-- Table structure for table `screenshot`
--

CREATE TABLE IF NOT EXISTS `screenshot` (
  `screenshotID` int(11) NOT NULL,
  `screenshotDesc` text,
  `screenshotPath` varchar(255) NOT NULL,
  `dateCreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `userCreated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `userID` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstName` varchar(45) NOT NULL,
  `lastName` varchar(45) NOT NULL,
  `languageID` int(11) DEFAULT '5',
  `passwordValue` varchar(100) DEFAULT NULL,
  `AuthorityLevel` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `email`, `firstName`, `lastName`, `languageID`, `passwordValue`, `AuthorityLevel`) VALUES
(12, 'lomad@uw.edu', 'Loma', 'Desai', 5, 'placeholder', 1),
(27, 'gaodl@uw.edu', 'Delong', 'Gao', 5, '123456', 1),
(29, 'gdlshallowshade@gmail.com', 'Dylan', 'Gao', 5, 'placeholder', 1),
(30, '510405893@qq.com', 'Delong', 'Gao', 5, 'knowyourenemy', 1),
(31, 'donghe90@uw.edu', 'Donghe', 'Xu', 5, 'placeholder', 1),
(32, 'jochenscholl@gmail.com', 'Hans J', 'Scholl', 5, 'jochen', 2),
(34, '510405893@qq.com', 'Delong', 'Gao', 5, 'placeholder', 1),
(35, 'dhtim135@gmail.com', 'Tim', 'Xu', 5, 'placeholder', 1),
(39, 'jochenscholl@gmail.com', 'Hans J', 'Scholl', 5, 'placeholder', 1),
(41, 'lomadesai@gmail.com', 'Loma', 'Desai', 5, 'placeholder', 1),
(42, 'timca@uw.edu', 'Tim', 'Carlson', 5, 'placeholder', 2),
(44, 'jeff.grove@outlook.com', 'Jeff', 'Grove', 5, 'placeholder', 1),
(45, 'jsaxena@uw.edu', 'Jyotsna', 'Saxena', 5, 'placeholder', 2),
(46, 'jsaxena@uw.edu', 'Jyotsna', 'Saxena', 5, 'placeholder', 1),
(47, 'woodsgs@uw.edu', 'Grant', 'Woods', 5, 'placeholder', 1),
(48, 'jorgeb@uw.edu', 'Jorge', 'Borunda', 5, 'placeholder', 1),
(49, 'sylincir@uw.edu', 'Dustin', 'Chiang', 5, 'placeholder', 1),
(50, 'galdi7@gmail.com', 'Mario', 'Sanchez', 5, 'placeholder', 1),
(51, ' borunda.jorge@gmail.com', 'Jorge', 'Borunda', 5, 'placeholder', 1),
(52, 'jeff@sports-it.com', 'Geoff', 'Grove', 5, 'placeholder', 1),
(53, 'wtmenten@gmail.com', 'William', 'Menten-Weil', 5, 'test', 2),
(54, 'wtmenten@gmail.com', 'William', 'Menten-Weil', 5, 'placeholder', 1),
(55, 'test@gmail.com', 'Test', 'User', 5, 'placeholder', 1),
(56, 'test2@gmail.com', 'Test', 'User2', 5, 'placeholder', 1);

-- --------------------------------------------------------

--
-- Table structure for table `userPersona`
--

CREATE TABLE IF NOT EXISTS `userPersona` (
  `userPersonaID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `personaID` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=77 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `userPersona`
--

INSERT INTO `userPersona` (`userPersonaID`, `userID`, `personaID`) VALUES
(15, 27, 20),
(17, 27, 21),
(21, 29, 20),
(22, 29, 34),
(23, 29, 21),
(24, 31, 20),
(25, 31, 34),
(26, 31, 35),
(27, 32, 39),
(28, 34, 20),
(29, 34, 34),
(32, 35, 20),
(40, 42, 39),
(41, 42, 34),
(42, 42, 21),
(43, 42, 35),
(54, 44, 39),
(55, 44, 34),
(56, 44, 21),
(57, 44, 35),
(61, 45, 39),
(62, 45, 35),
(63, 46, 21),
(64, 47, 39),
(65, 48, 39),
(66, 49, 39),
(67, 50, 39),
(68, 51, 21),
(69, 52, 20),
(70, 52, 34),
(71, 52, 21),
(72, 52, 35),
(73, 52, 39),
(74, 54, 39),
(75, 55, 39),
(76, 56, 39);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artifact`
--
ALTER TABLE `artifact`
  ADD PRIMARY KEY (`artifactID`),
  ADD KEY `FK_artifactTypeID` (`artifactTypeID`),
  ADD KEY `FK_ArtifactLanguageID` (`languageID`) USING BTREE;

--
-- Indexes for table `artifactType`
--
ALTER TABLE `artifactType`
  ADD PRIMARY KEY (`artifactTypeID`);

--
-- Indexes for table `artifactTypes`
--
ALTER TABLE `artifactTypes`
  ADD PRIMARY KEY (`artifactTypeID`);

--
-- Indexes for table `assessment`
--
ALTER TABLE `assessment`
  ADD PRIMARY KEY (`assessmentID`),
  ADD UNIQUE KEY `assessmentIDHashed` (`assessmentIDHashed`),
  ADD KEY `FK_userProgressID` (`userID`),
  ADD KEY `FK_projectArtifactID` (`projectArtifactID`),
  ADD KEY `assessment_ibfk_2` (`personaID`),
  ADD KEY `assessment_ibfk_3` (`scenarioID`),
  ADD KEY `assessment_ibfk_5` (`configurationID`);

--
-- Indexes for table `attribute`
--
ALTER TABLE `attribute`
  ADD PRIMARY KEY (`attributeID`),
  ADD KEY `FK_categoryLanguage` (`languageID`),
  ADD KEY `criterionID` (`criterionID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`attributeID`);

--
-- Indexes for table `category_backup`
--
ALTER TABLE `category_backup`
  ADD PRIMARY KEY (`categoryID`),
  ADD KEY `FK_categoryLanguage` (`languageID`),
  ADD KEY `criterionID` (`criterionID`);

--
-- Indexes for table `cluster`
--
ALTER TABLE `cluster`
  ADD PRIMARY KEY (`attributeID`);

--
-- Indexes for table `cluster_category`
--
ALTER TABLE `cluster_category`
  ADD PRIMARY KEY (`clusterCategoryID`),
  ADD KEY `categoryID` (`categoryID`),
  ADD KEY `clusterID` (`clusterID`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`commentID`),
  ADD KEY `userCreated` (`userCreated`);

--
-- Indexes for table `configuration`
--
ALTER TABLE `configuration`
  ADD PRIMARY KEY (`configurationID`),
  ADD KEY `configurationTypeID` (`configurationTypeID`);

--
-- Indexes for table `configurationType`
--
ALTER TABLE `configurationType`
  ADD PRIMARY KEY (`configurationTypeID`);

--
-- Indexes for table `configuration_attribute`
--
ALTER TABLE `configuration_attribute`
  ADD PRIMARY KEY (`configurationAttributeID`),
  ADD KEY `configurationID` (`configurationID`),
  ADD KEY `attributeID` (`attributeID`) USING BTREE;

--
-- Indexes for table `criterion`
--
ALTER TABLE `criterion`
  ADD PRIMARY KEY (`criterionID`),
  ADD KEY `criterionLanguageID` (`languageID`);

--
-- Indexes for table `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`languageID`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`languageID`);

--
-- Indexes for table `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`personaID`),
  ADD KEY `FK_personaLanguageID` (`languageID`);

--
-- Indexes for table `personae`
--
ALTER TABLE `personae`
  ADD PRIMARY KEY (`personaeID`),
  ADD KEY `FK_personaLanguageID` (`personaLanguage`);

--
-- Indexes for table `personaScenario`
--
ALTER TABLE `personaScenario`
  ADD PRIMARY KEY (`personaScenarioID`),
  ADD UNIQUE KEY `personaID` (`personaID`,`scenarioID`),
  ADD KEY `FK_Persona` (`personaID`),
  ADD KEY `FK_Scenario` (`scenarioID`);

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`projectID`),
  ADD KEY `FK_projectLanguageID` (`languageID`);

--
-- Indexes for table `projectArtifact`
--
ALTER TABLE `projectArtifact`
  ADD PRIMARY KEY (`projectArtifactID`),
  ADD KEY `FK_ProjectID` (`projectID`),
  ADD KEY `FK_ArtifactID` (`artifactID`);

--
-- Indexes for table `rating`
--
ALTER TABLE `rating`
  ADD PRIMARY KEY (`ratingID`),
  ADD KEY `assessmentID` (`assessmentID`) USING BTREE,
  ADD KEY `userRating_ibfk_2` (`attributeID`);

--
-- Indexes for table `rating_comment`
--
ALTER TABLE `rating_comment`
  ADD PRIMARY KEY (`rating_commentID`),
  ADD KEY `ratingID` (`ratingID`) USING BTREE,
  ADD KEY `rating_comment_ibfk_2` (`commentID`);

--
-- Indexes for table `rating_screenshot`
--
ALTER TABLE `rating_screenshot`
  ADD PRIMARY KEY (`rating_screenshotID`),
  ADD KEY `ratingID` (`ratingID`) USING BTREE,
  ADD KEY `rating_screenshot_ibfk_2` (`screenshotID`);

--
-- Indexes for table `scenario`
--
ALTER TABLE `scenario`
  ADD PRIMARY KEY (`scenarioID`),
  ADD KEY `FK_personaLanguageID` (`languageID`);

--
-- Indexes for table `scenarioAttribute`
--
ALTER TABLE `scenarioAttribute`
  ADD PRIMARY KEY (`scenarioAttributeID`),
  ADD KEY `FK_Scenario` (`scenarioID`),
  ADD KEY `FK_Category` (`attributeID`);

--
-- Indexes for table `screenshot`
--
ALTER TABLE `screenshot`
  ADD PRIMARY KEY (`screenshotID`),
  ADD KEY `userCreated` (`userCreated`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `preferredLanguage` (`languageID`);

--
-- Indexes for table `userPersona`
--
ALTER TABLE `userPersona`
  ADD PRIMARY KEY (`userPersonaID`),
  ADD KEY `FK_userID` (`userID`),
  ADD KEY `FK_personaeID` (`personaID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artifact`
--
ALTER TABLE `artifact`
  MODIFY `artifactID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=85;
--
-- AUTO_INCREMENT for table `artifactType`
--
ALTER TABLE `artifactType`
  MODIFY `artifactTypeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `artifactTypes`
--
ALTER TABLE `artifactTypes`
  MODIFY `artifactTypeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `assessment`
--
ALTER TABLE `assessment`
  MODIFY `assessmentID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=209;
--
-- AUTO_INCREMENT for table `attribute`
--
ALTER TABLE `attribute`
  MODIFY `attributeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `attributeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `category_backup`
--
ALTER TABLE `category_backup`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `cluster`
--
ALTER TABLE `cluster`
  MODIFY `attributeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=71;
--
-- AUTO_INCREMENT for table `cluster_category`
--
ALTER TABLE `cluster_category`
  MODIFY `clusterCategoryID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=161;
--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `configuration`
--
ALTER TABLE `configuration`
  MODIFY `configurationID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `configurationType`
--
ALTER TABLE `configurationType`
  MODIFY `configurationTypeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `configuration_attribute`
--
ALTER TABLE `configuration_attribute`
  MODIFY `configurationAttributeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `criterion`
--
ALTER TABLE `criterion`
  MODIFY `criterionID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `language`
--
ALTER TABLE `language`
  MODIFY `languageID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `languageID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `persona`
--
ALTER TABLE `persona`
  MODIFY `personaID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `personae`
--
ALTER TABLE `personae`
  MODIFY `personaeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `personaScenario`
--
ALTER TABLE `personaScenario`
  MODIFY `personaScenarioID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=121;
--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `projectID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `projectArtifact`
--
ALTER TABLE `projectArtifact`
  MODIFY `projectArtifactID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=70;
--
-- AUTO_INCREMENT for table `rating`
--
ALTER TABLE `rating`
  MODIFY `ratingID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4429;
--
-- AUTO_INCREMENT for table `rating_comment`
--
ALTER TABLE `rating_comment`
  MODIFY `rating_commentID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=91;
--
-- AUTO_INCREMENT for table `rating_screenshot`
--
ALTER TABLE `rating_screenshot`
  MODIFY `rating_screenshotID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `scenario`
--
ALTER TABLE `scenario`
  MODIFY `scenarioID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=52;
--
-- AUTO_INCREMENT for table `scenarioAttribute`
--
ALTER TABLE `scenarioAttribute`
  MODIFY `scenarioAttributeID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1541;
--
-- AUTO_INCREMENT for table `screenshot`
--
ALTER TABLE `screenshot`
  MODIFY `screenshotID` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=57;
--
-- AUTO_INCREMENT for table `userPersona`
--
ALTER TABLE `userPersona`
  MODIFY `userPersonaID` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=77;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `artifact`
--
ALTER TABLE `artifact`
  ADD CONSTRAINT `artifact_ibfk_1` FOREIGN KEY (`artifactTypeID`) REFERENCES `artifactType` (`artifactTypeID`),
  ADD CONSTRAINT `artifact_ibfk_2` FOREIGN KEY (`artifactTypeID`) REFERENCES `artifactType` (`artifactTypeID`),
  ADD CONSTRAINT `artifact_ibfk_3` FOREIGN KEY (`artifactTypeID`) REFERENCES `artifactType` (`artifactTypeID`),
  ADD CONSTRAINT `artifact_ibfk_4` FOREIGN KEY (`artifactTypeID`) REFERENCES `artifactType` (`artifactTypeID`),
  ADD CONSTRAINT `artifact_ibfk_5` FOREIGN KEY (`artifactTypeID`) REFERENCES `artifactType` (`artifactTypeID`),
  ADD CONSTRAINT `artifact_ibfk_6` FOREIGN KEY (`languageID`) REFERENCES `language` (`languageID`);

--
-- Constraints for table `assessment`
--
ALTER TABLE `assessment`
  ADD CONSTRAINT `assessment_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `assessment_ibfk_2` FOREIGN KEY (`personaID`) REFERENCES `persona` (`personaID`),
  ADD CONSTRAINT `assessment_ibfk_3` FOREIGN KEY (`scenarioID`) REFERENCES `scenario` (`scenarioID`),
  ADD CONSTRAINT `assessment_ibfk_4` FOREIGN KEY (`projectArtifactID`) REFERENCES `projectArtifact` (`projectArtifactID`),
  ADD CONSTRAINT `assessment_ibfk_5` FOREIGN KEY (`configurationID`) REFERENCES `configuration` (`configurationID`);

--
-- Constraints for table `attribute`
--
ALTER TABLE `attribute`
  ADD CONSTRAINT `fk_criterionID` FOREIGN KEY (`criterionID`) REFERENCES `criterion` (`criterionID`),
  ADD CONSTRAINT `fk_languageID` FOREIGN KEY (`languageID`) REFERENCES `language` (`languageID`);

--
-- Constraints for table `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `category_fk_attributeID` FOREIGN KEY (`attributeID`) REFERENCES `attribute` (`attributeID`);

--
-- Constraints for table `cluster`
--
ALTER TABLE `cluster`
  ADD CONSTRAINT `fk_attributeID` FOREIGN KEY (`attributeID`) REFERENCES `attribute` (`attributeID`);

--
-- Constraints for table `cluster_category`
--
ALTER TABLE `cluster_category`
  ADD CONSTRAINT `cluster_category_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `attribute` (`attributeID`),
  ADD CONSTRAINT `cluster_category_ibfk_2` FOREIGN KEY (`clusterID`) REFERENCES `attribute` (`attributeID`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`userCreated`) REFERENCES `user` (`userID`);

--
-- Constraints for table `configuration`
--
ALTER TABLE `configuration`
  ADD CONSTRAINT `configuration_ibfk_1` FOREIGN KEY (`configurationTypeID`) REFERENCES `configurationType` (`configurationTypeID`);

--
-- Constraints for table `configuration_attribute`
--
ALTER TABLE `configuration_attribute`
  ADD CONSTRAINT `configuration_attribute_ibfk_1` FOREIGN KEY (`configurationID`) REFERENCES `configuration` (`configurationID`),
  ADD CONSTRAINT `configuration_attribute_ibfk_2` FOREIGN KEY (`attributeID`) REFERENCES `attribute` (`attributeID`);

--
-- Constraints for table `criterion`
--
ALTER TABLE `criterion`
  ADD CONSTRAINT `criterion_ibfk_1` FOREIGN KEY (`languageID`) REFERENCES `language` (`languageID`);

--
-- Constraints for table `persona`
--
ALTER TABLE `persona`
  ADD CONSTRAINT `persona_ibfk_1` FOREIGN KEY (`languageID`) REFERENCES `language` (`languageID`);

--
-- Constraints for table `personaScenario`
--
ALTER TABLE `personaScenario`
  ADD CONSTRAINT `personaScenario_ibfk_1` FOREIGN KEY (`personaID`) REFERENCES `persona` (`personaID`),
  ADD CONSTRAINT `personaScenario_ibfk_2` FOREIGN KEY (`scenarioID`) REFERENCES `scenario` (`scenarioID`);

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `project_ibfk_1` FOREIGN KEY (`languageID`) REFERENCES `language` (`languageID`);

--
-- Constraints for table `projectArtifact`
--
ALTER TABLE `projectArtifact`
  ADD CONSTRAINT `projectArtifact_ibfk_1` FOREIGN KEY (`artifactID`) REFERENCES `artifact` (`artifactID`),
  ADD CONSTRAINT `projectArtifact_ibfk_2` FOREIGN KEY (`projectID`) REFERENCES `project` (`projectID`);

--
-- Constraints for table `rating`
--
ALTER TABLE `rating`
  ADD CONSTRAINT `rating_ibfk_1` FOREIGN KEY (`assessmentID`) REFERENCES `assessment` (`assessmentID`),
  ADD CONSTRAINT `rating_ibfk_2` FOREIGN KEY (`attributeID`) REFERENCES `attribute` (`attributeID`);

--
-- Constraints for table `rating_comment`
--
ALTER TABLE `rating_comment`
  ADD CONSTRAINT `rating_comment_ibfk_1` FOREIGN KEY (`ratingID`) REFERENCES `rating` (`ratingID`),
  ADD CONSTRAINT `rating_comment_ibfk_2` FOREIGN KEY (`commentID`) REFERENCES `comment` (`commentID`);

--
-- Constraints for table `rating_screenshot`
--
ALTER TABLE `rating_screenshot`
  ADD CONSTRAINT `rating_screenshot_ibfk_1` FOREIGN KEY (`ratingID`) REFERENCES `rating` (`ratingID`),
  ADD CONSTRAINT `rating_screenshot_ibfk_2` FOREIGN KEY (`screenshotID`) REFERENCES `screenshot` (`screenshotID`);

--
-- Constraints for table `scenario`
--
ALTER TABLE `scenario`
  ADD CONSTRAINT `scenario_ibfk_1` FOREIGN KEY (`languageID`) REFERENCES `language` (`languageID`);

--
-- Constraints for table `scenarioAttribute`
--
ALTER TABLE `scenarioAttribute`
  ADD CONSTRAINT `scenarioAttribute_ibfk_1` FOREIGN KEY (`scenarioID`) REFERENCES `scenario` (`scenarioID`),
  ADD CONSTRAINT `scenarioAttribute_ibfk_2` FOREIGN KEY (`attributeID`) REFERENCES `attribute` (`attributeID`);

--
-- Constraints for table `screenshot`
--
ALTER TABLE `screenshot`
  ADD CONSTRAINT `screenshot_ibfk_1` FOREIGN KEY (`userCreated`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `screenshot_ibfk_2` FOREIGN KEY (`userCreated`) REFERENCES `user` (`userID`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`languageID`) REFERENCES `language` (`languageID`);

--
-- Constraints for table `userPersona`
--
ALTER TABLE `userPersona`
  ADD CONSTRAINT `userPersona_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `userPersona_ibfk_2` FOREIGN KEY (`personaID`) REFERENCES `persona` (`personaID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
