CREATE TABLE screenshot (
    screenshotID INT(11) PRIMARY KEY AUTO_INCREMENT,
    screenshotDesc TEXT,
    screenshotPath VARCHAR(255) NOT NULL,
    dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userCreated INT(11) NOT NULL
);

CREATE TABLE comment (
    commentID INT(11) PRIMARY KEY AUTO_INCREMENT,
    comment TEXT NOT NULL,
    dateCreated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    userCreated INT(11) NOT NULL
);

CREATE TABLE userRating_screenshot (
    userRating_screenshotID INT(11) PRIMARY KEY AUTO_INCREMENT,
    userRatingID INT(11) NOT NULL FOREIGN KEY REFERENCES userRating(userRatingID),
    screenshotID INT(11) NOT NULL FOREIGN KEY REFERENCES screenshot(screenshotID)
);

CREATE TABLE userRating_comment (
    userRating_commentID INT(11) PRIMARY KEY AUTO_INCREMENT,
    userRatingID INT(11) NOT NULL FOREIGN KEY REFERENCES userRating(userRatingID),
    commentID INT(11) NOT NULL FOREIGN KEY  REFERENCES comment(commentID)
);

