<?php
    require_once "../dbconnect.php";

    try {
        $arrayToSend = [];
        $dbq = db_connect();
        $pre_result = $dbq->prepare("SELECT pjt.projectName AS project,
                                     a.artifactName AS artifact,
                                     p.personaName AS persona,
                                      s.scenarioName AS scenario,
                                     CONCAT(u.firstName, ' ', u.lastName) AS name,
                                     ass.completionDate AS completionDate,
                                     u.email AS email,
                                     ass.assessmentID AS assessmentID,
                                     ass.ratingUrl AS ratingUrl,
                                     atcon.attributeConfigurationName AS configurationName
                                     FROM assessment ass
                                     JOIN user u ON ass.userID = u.userID
                                     JOIN configuration con ON con.configurationID = ass.configurationID
                                     JOIN attributeConfiguration atcon ON atcon.attributeConfigurationID = con.attributeConfigurationID
                                     JOIN assessmentConfiguration ascon ON ascon.assessmentConfigurationID = con.assessmentConfigurationID
                                     JOIN project pjt ON pjt.projectID = ascon.projectID
                                     JOIN artifact a ON a.artifactID = ascon.artifactID
                                     JOIN persona p ON p.personaID = ascon.personaID
                                     JOIN scenario s ON s.scenarioID = ascon.scenarioID
                                     WHERE u.userID = ass.userID
                                     AND p.personaID = ascon.personaID
                                     AND s.scenarioID = ascon.scenarioID
                                     ORDER BY completionDate DESC");
        $pre_result->execute();
        while ($row = $pre_result->fetch()) {
            array_push($arrayToSend, $row);
        }
        header('Content-Type: application/json');
        echo json_encode($arrayToSend, TRUE);
        //close connection
        $dbq = NULL;

    } catch (PDOException $e) {
         print ("getMessage(): " . $e->getMessage () . "\n");
    }


?>