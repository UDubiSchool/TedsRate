
INSERT INTO question (questionName, questionDesc, questionData, questionTypeID) VALUES ("Boolean Question 2", "Another Sample of yes no questioning", '{"questionType": "Boolean","Boolean": {"true": "Yes","false": "No","0": "No","1": "Yes"}}', 1);

INSERT INTO question (questionName, questionDesc, questionData, questionTypeID) VALUES ("Likert Question 2", "Another Sample of likert questioning", '{"questionType": "Radio","Radio": {"preface": "Strongly Disagree","postface": "Strongly Agree"}}', 1);

INSERT INTO question (questionName, questionDesc, questionData, questionTypeID) VALUES ("Text Question 1", "A Sample of text questioning", '{"questionType": "Text","Text": {"placeholder": "Answer Here"}}', 1);

SET @qid = LAST_INSERT_ID();

INSERT INTO question_project (questionID, projectID) VALUES (@qid, 38);


INSERT INTO question (questionName, questionDesc, questionData, questionTypeID) VALUES ("Text Question 2", "Another Sample of text questioning", '{"questionType": "Text","Text": {"placeholder": "Answer Here"}}', 1);

SET @qid = LAST_INSERT_ID();

INSERT INTO question_project (questionID, projectID) VALUES (@qid, 38);

INSERT INTO question (questionName, questionDesc, questionData, questionTypeID) VALUES ("Select Question 1", "A Sample of select questioning", '{"questionType": "Select","Select": {"options": ["Option 1", "Option 2", "Option 3", "Option 4"]}}', 2);


SET @qid = LAST_INSERT_ID();

INSERT INTO question_project (questionID, projectID) VALUES (@qid, 38);

INSERT INTO question (questionName, questionDesc, questionData, questionTypeID) VALUES ("Check Question 1", "A Sample of check questioning", '{"questionType": "Check","Check": {"options": ["Option 1", "Option 2", "Option 3", "Option 4"]}}', 2);


SET @qid = LAST_INSERT_ID();

INSERT INTO question_project (questionID, projectID) VALUES (@qid, 38);



INSERT INTO questionConfiguration (questionConfigurationName, questionConfigurationDesc) VALUES ('All question subsets', 'All subsets of questions, Demographic, artifact, project, scenario, attribute');


INSERT INTO screenshot (screenshotPath, ratingID) VALUES ("upload/screenshots/cat1.jpg", 4422) , ("upload/screenshots/cat2.jpg", 4422), ("upload/screenshots/cat3.jpg", 4422);