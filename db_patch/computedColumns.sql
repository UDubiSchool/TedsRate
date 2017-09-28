DELIMITER $$
CREATE FUNCTION projectAssessmentCount (projectID INT) RETURNS INT
BEGIN
    RETURN (SELECT count(*) FROM assessment a
    JOIN configuration c ON a.configurationID = c.configurationID
    JOIN assessmentConfiguration ac ON c.assessmentConfigurationID = ac.assessmentConfigurationID
    WHERE ac.projectID = projectID);
END$$
DELIMITER ;

DELIMITER $$
CREATE FUNCTION projectCompletedAssessmentCount (projectID INT) RETURNS INT
BEGIN
    RETURN (SELECT count(*) FROM assessment a
    JOIN configuration c ON a.configurationID = c.configurationID
    JOIN assessmentConfiguration ac ON c.assessmentConfigurationID = ac.assessmentConfigurationID
    WHERE ac.projectID = projectID
    AND a.completionDate IS NOT NULL);
END$$
DELIMITER ;

delimiter //
CREATE TRIGGER ratingUpdate_touchAssessment
BEFORE UPDATE ON rating
FOR EACH ROW
BEGIN
    CALL touchAssessment(NEW.assessmentID);
END;//
delimiter ;

delimiter //
CREATE TRIGGER ratingInsert_touchAssessment
BEFORE INSERT ON rating
FOR EACH ROW
BEGIN
    CALL touchAssessment(NEW.assessmentID);
END;//
delimiter ;

delimiter //
CREATE TRIGGER commentInsert_touchAssessment
BEFORE INSERT ON comment
FOR EACH ROW
BEGIN

    SET @assessmentID = (SELECT r.assessmentID FROM comment c
    JOIN rating r ON r.ratingID = c.ratingID
    WHERE c.commentID = NEW.commentID);
    CALL touchAssessment(@assessmentID);
END;//
delimiter ;

delimiter //
CREATE TRIGGER commentUpdate_touchAssessment
BEFORE UPDATE ON comment
FOR EACH ROW
BEGIN
    SET @assessmentID = (SELECT r.assessmentID FROM comment c
    JOIN rating r ON r.ratingID = c.ratingID
    WHERE c.commentID = NEW.commentID);
    CALL touchAssessment(@assessmentID);
END;//
delimiter ;

delimiter //
CREATE TRIGGER screenshotInsert_touchAssessment
BEFORE INSERT ON screenshot
FOR EACH ROW
BEGIN
    SET @assessmentID = (SELECT r.assessmentID FROM screenshot s
    JOIN rating r ON r.ratingID = s.ratingID
    WHERE s.screenshot = NEW.screenshotID);
    CALL touchAssessment(@assessmentID);
END;//
delimiter ;

delimiter //
CREATE TRIGGER screenshotUpdate_touchAssessment
BEFORE UPDATE ON screenshot
FOR EACH ROW
BEGIN
    SET @assessmentID = (SELECT r.assessmentID FROM screenshot s
    JOIN rating r ON r.ratingID = s.ratingID
    WHERE s.screenshot = NEW.screenshotID);
    CALL touchAssessment(@assessmentID);
END;//
delimiter ;

delimiter //
CREATE TRIGGER responseInsert_touchAssessment
BEFORE INSERT ON response
FOR EACH ROW
BEGIN
    CALL touchAssessment(NEW.assessmentID);
END;//
delimiter ;

delimiter //
CREATE TRIGGER responseUpdate_touchAssessment
BEFORE UPDATE ON response
FOR EACH ROW
BEGIN
    CALL touchAssessment(NEW.assessmentID);
END;//
delimiter ;