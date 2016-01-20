DELIMITER $$
CREATE DEFINER=`root`@`%.washington.edu` PROCEDURE `getCategories`(IN criteriaID INT, out categoryID INT, CategoryTitle varchar(45), out categoryDescription varchar(255))
BEGIN

Select c.categoryTitle, c.categoryID, c.categoryDescription
From category c
WHERE c.criteriaID = criteriaID
Order by c.categoryID;
END;
