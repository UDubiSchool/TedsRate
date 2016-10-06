IF (SELECT 1 = 1
    FROM assessment
    WHERE assessment.userID = userID
    AND assessment.configurationID = configurationID)
    THEN
    BEGIN
        SELECT assessment.assessmentID
        FROM assessment
        WHERE assessment.userID = userID
        AND assessment.configurationID = configurationID
        INTO assessmentID;
    END;
    ELSE
    BEGIN
        INSERT INTO assessment
        (userID, configurationID)
        VALUES
        (userID, configurationID);

        SELECT LAST_INSERT_ID() INTO assessmentID;
    END;
END IF