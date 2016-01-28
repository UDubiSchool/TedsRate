DELIMITER $$
CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getCriteria`(in languageID INT, out criteriaID INT, out criteriaName varchar(255), out criteriaDesc varchar(255))
BEGIN
SELECT criteria.criteriaID , criteria.criteriaName, criteria.criteriaDesc
From criteria
Where criteriaLanguageID = languageID;
END$$
DELIMITER ;