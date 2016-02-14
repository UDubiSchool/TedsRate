INSERT INTO question (questionName, questionDesc, questionData, questionTypeID, questionRequired) VALUES
('How often would you say you use this team mobile app?', NULL, '{"questionType": "Radio","Radio": {"preface": "Not frequently","postface": "Very frequently"}}', 2, 1),
('How often would you say you watch games?', NULL, '{"questionType": "Radio","Radio": {"preface": "Not frequently","postface": "Very frequently"}}', 2, 0),
('How often would you say you buy tickets to games?', NULL, '{"questionType": "Radio","Radio": {"preface": "Not frequently","postface": "Very frequently"}}', 2, 0),
('Are you a season ticket holder?', NULL, '{"questionType": "Boolean","Boolean": {"true": "Yes","false": "No","0": "No","1": "Yes"}}', 2, 0),
('How often would you say you buy team merchandise?', NULL, '{"questionType": "Radio","Radio": {"preface": "Not frequently","postface": "Very frequently"}}', 2, 1),
('Education', 'What was the highest level of school you have completed?', '{"questionType": "Select","Select": {"options": ["Elementary school", "High school", "Some college", "Bachelor''s degree", "Master''s degree", "Doctorate"]}}', 1, 0),
('Age', 'Select your age range from the list below. This information will be used to cluster our survey results.', '{"questionType": "Select","Select": {"options": ["Under 12", "13-22", "23-32", "33-42", "43-52", "53-62", "63-72", "73-82", "Over 83"]}}', 1, 1),
('Gender', 'Select your gender from the list below. This information will be used to cluster our survey results.', '{"questionType": "Select","Select": {"options": ["Female", "Male", "LGBT", "No response"]}}', 1, 0),
('Provenance', 'Tell us where you are from.', '{"questionType": "Select","Select": {"options": ["North America", "Central America and Caribbean", "South America", "North Atlantic", "Europe", "Africa", "Middle East", "Asia", "Pacific Islands"]}}', 1, 1),
('Household Income', 'What is your household income?', '{"questionType": "Select","Select": {"options": ["Less Than $25,000", "$25,000 to $34,999", "$35,000 to $49,999", "$50,000 to $74,999", "$75,000 to $99,999", "$100,000 to $149,999", "$150,000 or more"]}}', 1, 0);




'{"questionType": "Radio","Radio": {"preface": "Not frequently","postface": "Very frequently"}}'
-- templates for question data
-- '{"questionType": "Select","Select": {"options": ["Option 1", "Option 2", "Option 3", "Option 4"]}}'
-- '{"questionType": "Text","Text": {"placeholder": "Answer Here"}}'
-- '{"questionType": "Radio","Radio": {"preface": "Strongly Disagree","postface": "Strongly Agree"}}'
-- '{"questionType": "Boolean","Boolean": {"true": "Yes","false": "No","0": "No","1": "Yes"}}'
-- '{"questionType": "Check","Check": {"options": ["Option 1", "Option 2", "Option 3", "Option 4"]}}'

'{"questionType": "Select","Select": {"options": ["Elementary school", "High school", "Some college", "Bachelor''s degree", "Master''s degree", "Doctorate"]}}'

'{"questionType": "Select","Select": {"options": ["Under 12", "13-22", "23-32", "33-42", "43-52", "53-62", "63-72", "73-82", "Over 83"]}}'

'{"questionType": "Select","Select": {"options": ["North America", "Central America and Caribbean", "South America", "North Atlantic", "Europe", "Africa", "Middle East", "Asia", "Pacific Islands"]}}'



'{"questionType": "Select","Select": {"options": ["Female", "Male", "LGBT", "No response"]}}'

'{"questionType": "Select","Select": {"options": ["Less Than $25,000", "$25,000 to $34,999", "$35,000 to $49,999", "$50,000 to $74,999", "$75,000 to $99,999", "$100,000 to $149,999", "$150,000 or more"]}}'
