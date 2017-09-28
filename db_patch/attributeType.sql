CREATE TABLE attributeType (
    attributeTypeID INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
    attributeTypeName VARCHAR(255) NOT NULL,
    attributeTypeDesc VARCHAR(500)
);

ALTER TABLE attribute ADD COLUMN attributeTypeID INT(11);

INSERT INTO attributeType (attributeTypeName) VALUES ('Category');
INSERT INTO attributeType (attributeTypeName) VALUES ('Cluster');
UPDATE attribute SET attributeTypeID = 1