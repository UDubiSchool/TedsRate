CREATE TABLE authority (
    authorityID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    authorityLevel INT NOT NULL,
    authorityName VARCHAR(255) NOT NULL,
    authorityDesc VARCHAR(500)
);

INSERT INTO authority (authorityLevel, authorityName, authorityDesc) VALUES
(1, 'Expert Rater', 'An expert rater as deemed by the TEDSRate research group.'),
(2, 'Administrator', 'An Administrator for the TEDSRate research group.'),
(3, 'Non-Expert Rater', 'An outside rater or consumer. This rater is not associated with the TEDSRate research group.');

ALTER TABLE user ADD COLUMN authorityID INT;
UPDATE user SET authorityID = authorityLevel;

CREATE TABLE user_authority (
    userAuthorityID INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
    userID INT NOT NULL,
    authorityID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES user(userID),
    FOREIGN KEY (authorityID) REFERENCES authority(authorityID)
);

INSERT INTO user_authority (userID, authorityID) VALUES
(50, 1),
(44, 1),
(52, 1),
(32, 1),
(48, 1),
(45, 1),
(49, 1),
(42, 1),
(47, 1),
(54, 1),
(54, 2),
(32, 2),
(42, 2);