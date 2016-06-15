CREATE TABLE groupType (
    groupTypeID INT PRIMARY KEY AUTO_INCREMENT,
    groupTypeName VARCHAR(255) NOT NULL,
    groupTypeDesc VARCHAR(500)
);

CREATE TABLE `group` (
    groupID INT PRIMARY KEY AUTO_INCREMENT,
    groupName VARCHAR(255),
    groupDesc VARCHAR(500),
    groupTypeID INT NOT NULL,
    groupWelcomeTemplate VARCHAR(10000) NOT NULL,
    FOREIGN KEY (groupTypeID) REFERENCES groupType(groupTypeID)
);

Create Table group_configuration (
    groupConfigurationID INT PRIMARY KEY AUTO_INCREMENT,
    groupID INT NOT NULL,
    configurationID INT NOT NULL,
    FOREIGN KEY (groupID) REFERENCES `group`(groupID),
    FOREIGN KEY (configurationID) REFERENCES configuration(configurationID)
);

CREATE TABLE lottery (
    groupID INT PRIMARY KEY AUTO_INCREMENT,
    lotteryJackpot INT NOT NULL,
    lotterySecond INT NOT NULL,
    lotteryThird INT NOT NULL,
    lotterySecondAmount INT NOT NULL,
    lotteryThirdAmount INT NOT NULL,
    lotteryStartDate DATETIME NOT NULL,
    lotteryEndDate DATETIME NOT NULL,
    lotteryTicketsPerAssessment INT NOT NULL,
    lotteryTicketsPerShare INT NOT NULL,
    lotteryTicketsPerComment INT NOT NULL,
    lotteryTicketsPerScreenshot INT NOT NULL,
    FOREIGN KEY (groupID) REFERENCES `group`(groupID)
);

INSERT INTO groupType (groupTypeName, groupTypeDesc) VALUES ('Lottery', 'A Lottery where players gain tickets for completing assessments and refering other participants.');
INSERT INTO `group` (groupName, groupDesc, groupTypeID, groupWelcomeTemplate) VALUES ('Test Lottery One', 'A test for the lottery system', 1, '<p>The UW TEDS Soccer Research Project is a research from the University of Washington Information School testing a methodology to measure the usability of iOS (Apple) mobile soccer apps. We would really appreciate your evaluation feedback of the Sounders FC iOS mobile application.</p><p>The Sounders FC application is one of 11 mobile soccer applications from around the world we are comparing in this study. In addition to the academic study, the analysis and data collected will be anonymized and presented to the Sounders to assist in improving their application.</p><p>We are analyzing 2 scenarios in this study, “Player Information”, and “Schedule, Results and League”. We’d love your opinion on both scenarios if possible, but if you only do one, that’s fine too.  Each survey takes about 5 minutes to complete. We appreciate you taking the time and your valuable opinion.   The first 25 users who complete both surveys will receive a $5 Amazon gift card. Thanks!</p><!-- <p>Please use the following link to evaluate the "Player Information" features. (Roster, player card, player stats, etc):<br></p><p>Please use the following link to evaluate "Schedule, Results and League" features (Schedule calendar, game stats, recap, the table, etc):<br></p> --><p>No personal data will be shared from these surveys.</p><p>Thanks!<br><br><i>The Teds Team</i></p>');

INSERT INTO `lottery` (groupID, lotteryJackpot, lotterySecond, lotteryThird, lotterySecondAmount, lotteryThirdAmount, lotteryStartDate, lotteryEndDate) VALUES (1, 1000, 50, 25, 5, 20, '2016-6-12 00:00:00', '2016-6-30 00:00:00', 10, 5, 1, 1);

INSERT INTO group_configuration (groupID, configurationID) VALUES (1, 353), (1, 352), (1,  351);

ALTER TABLE assessment ADD COLUMN groupID INT ; ALTER TABLE assessment ADD FOREIGN KEY (groupID) REFERENCES group(groupID);


--Alter add assessment proc

set @groupID = (SELECT g.groupID from configuration c JOIN group_configuration gc on gc.configurationID = c.configurationID JOIN `group` g ON g.groupID = gc.groupID Where c.configurationID = configurationID order by g.groupID DESC Limit 1);
        INSERT INTO assessment
        (userID, configurationID, groupID)
        VALUES
        (userID, configurationID, @groupID);

        SELECT LAST_INSERT_ID() INTO assessmentID;