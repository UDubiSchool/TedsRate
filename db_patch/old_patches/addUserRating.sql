BEGIN
start transaction;
INSERT INTO userRating
(userID, ratingID, userPersonaID, personaScenarioID, scenarioCategoryID, artifactID, userRatingProcessID)
VALUES (userID, ratingID, userPersonaID, personaScenarioID, scenarioCategoryID, artifactID, userRatingProcessID)
ON DUPLICATE KEY UPDATE
ratingID = VALUES (ratingID),
id=LAST_INSERT_ID(id);
commit;
select userRatingProcessID into newRatingID;
END