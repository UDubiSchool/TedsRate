CREATE TABLE criteria (
    criteriaID INT(11) PRIMARY KEY AUTO_INCREMENT,
    criteriaName VARCHAR(255) NOT NULL,
    criteriaDesc VARCHAR(255),
    criteriaLanguageID INT(11) DEFAULT 5
);
ALTER TABLE category ADD COLUMN criteriaID INT after parentCategoryID;

INSERT INTO criteria (criteriaName) VALUES ('Ease of Use'), ('Adaptability'), ('Quality'), ('Performance'), ('Affection'), ('Noise Reduction');
UPDATE category SET criteriaID = parentCategoryID;
ALTER TABLE category drop COLUMN parentCategoryID;