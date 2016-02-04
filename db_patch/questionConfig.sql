CREATE TABLE questionType (
    questionTypeID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionTypeName VARCHAR(255) NOT NULL,
    questionTypeDesc VARCHAR(500)
);

CREATE TABLE question (
    questionID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionValue VARCHAR(1000) NOT NULL,
    questionTypeID INT(11) NOT NULL,
    FOREIGN KEY (questionTypeID) REFERENCES questionType(questionTypeID)
);

CREATE TABLE questionConfiguration (
    questionConfigurationID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionConfigurationName VARCHAR(255) NOT NULL,
    questionConfigurationDesc VARCHAR(500)
);

CREATE TABLE question_questionConfiguration (
    question_questionConfigurationID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionConfigurationID INT(11) NOT NULL,
    questionID INT(11) NOT NULL,
    FOREIGN KEY (questionConfigurationID) REFERENCES questionConfiguration(questionConfigurationID),
    FOREIGN KEY (questionID) REFERENCES question(questionID)
);

CREATE TABLE response (
    responseID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionID INT(11) NOT NULL,
    responseValue INT(11) NOT NULL,
    FOREIGN KEY (questionID) REFERENCES question(questionID)
);

CREATE TABLE question_project (
    question_projectID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionID INT(11) NOT NULL,
    projectID INT(11) NOT NULL,
    FOREIGN KEY (questionID) REFERENCES question(questionID),
    FOREIGN KEY (projectID) REFERENCES project(projectID)
);

CREATE TABLE question_artifact (
    question_artifactID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionID INT(11) NOT NULL,
    artifactID INT(11) NOT NULL,
    FOREIGN KEY (questionID) REFERENCES question(questionID),
    FOREIGN KEY (artifactID) REFERENCES artifact(artifactID)
);

CREATE TABLE question_scenario (
    question_scenarioID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionID INT(11) NOT NULL,
    scenarioID INT(11) NOT NULL,
    FOREIGN KEY (questionID) REFERENCES question(questionID),
    FOREIGN KEY (scenarioID) REFERENCES scenario(scenarioID)
);

CREATE TABLE question_attribute (
    question_attributeID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    questionID INT(11) NOT NULL,
    attributeID INT(11) NOT NULL,
    FOREIGN KEY (questionID) REFERENCES question(questionID),
    FOREIGN KEY (attributeID) REFERENCES attribute(attributeID)
);



INSERT INTO questionType (questionTypeName) VALUES
('Demographic'),
('Project'),
('Artifact'),
('Scenario'),
('Attribute');

INSERT INTO questionConfiguration (questionConfigurationName, questionConfigurationDesc) VALUES ('No additional questions', 'used for backward compatibility with old & expert ratings');

ALTER TABLE configuration ADD COLUMN questionConfigurationID INT(11);
ALTER TABLE configuration ADD COLUMN uiConfigurationID INT(11);

UPDATE configuration SET questionConfigurationID = 1;
UPDATE configuration SET uiConfigurationID = 1;

ALTER TABLE configuration ADD CONSTRAINT FOREIGN KEY (questionConfigurationID) REFERENCES questionConfiguration(questionConfigurationID);
ALTER TABLE configuration ADD CONSTRAINT FOREIGN KEY (uiConfigurationID) REFERENCES uiConfiguration(uiConfigxurationID);

ALTER TABLE configuration ADD COLUMN configurationIDHashed INT(11);