CREATE TABLE uiConfiguration (
    uiConfigurationID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    uiConfigurationName VARCHAR(255) NOT NULL,
    uiConfigurationDesc VARCHAR(500),
    ratingStyle VARCHAR(100) NOT NULL,
    artifactInclusion VARCHAR(100) NOT NULL

);

INSERT INTO uiConfiguration (uiConfigurationName, ratingStyle, artifactInclusion) VALUES ('TEDSRate v1', 'Text', 'Frame'), ('Likert and no iFrame', 'Likert', 'None');