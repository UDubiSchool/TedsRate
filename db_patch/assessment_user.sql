CREATE TABLE assessmentConfiguration (
    assessmentConfigurationID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    projectID INT(11) NOT NULL,
    artifactID INT(11) NOT NULL,
    scenarioID INT(11) NOT NULL,
    personaID INT(11) NOT NULL,
    FOREIGN KEY (projectID) REFERENCES project(projectID),
    FOREIGN KEY (artifactID) REFERENCES artifact(artifactID),
    FOREIGN KEY (scenarioID) REFERENCES scenario(scenarioID),
    FOREIGN KEY (personaID) REFERENCES persona(personaID),
    CONSTRAINT uc_assessmentConfig UNIQUE (projectID, artifactID, scenarioID, personaID)
);

CREATE TABLE configuration (
    configurationID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    attributeConfigurationID INT(11) NOT NULL,
    assessmentConfigurationID INT(11) NOT NULL,
    FOREIGN KEY (attributeConfigurationID) REFERENCES attributeConfiguration(attributeConfigurationID),
    FOREIGN KEY (assessmentConfigurationID) REFERENCES assessmentConfiguration(assessmentConfigurationID),
    CONSTRAINT uc_config UNIQUE (attributeConfigurationID, assessmentConfigurationID)
);

ALTER TABLE attributeConfiguration_attribute ADD CONSTRAINT uc_attributeConfig UNIQUE (attributeConfigurationID, attributeID);