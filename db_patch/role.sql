CREATE TABLE role (
    roleID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    roleName VARCHAR(255) NOT NULL,
    roleDesc VARCHAR(500)
);

CREATE TABLE project_role (
    project_roleID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    projectID INT(11) NOT NULL,
    roleID INT(11) NOT NULL,
    FOREIGN KEY (projectID) REFERENCES project(projectID),
    FOREIGN KEY (roleID) REFERENCES role(roleID)
);
CREATE TABLE project_persona (
    project_personaID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    projectID INT(11) NOT NULL,
    personaID INT(11) NOT NULL,
    FOREIGN KEY (projectID) REFERENCES project(projectID),
    FOREIGN KEY (personaID) REFERENCES persona(personaID)
);
CREATE TABLE project_scenario (
    project_scenarioID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    projectID INT(11) NOT NULL,
    scenarioID INT(11) NOT NULL,
    FOREIGN KEY (projectID) REFERENCES project(projectID),
    FOREIGN KEY (scenarioID) REFERENCES scenario(scenarioID)
);

INSERT INTO role (roleName) VALUES ('general');