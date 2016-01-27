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
                                     ass.isComplete AS complete,
                                     ass.completionDate AS completionDate,
                                     u.email AS email,
                                     ass.assessmentID AS assessmentID,
                                     ass.ratingUrl AS ratingUrl,
                                     con.configurationName AS configurationName
                                     FROM assessment ass
                                     JOIN user u ON ass.userID = u.userID
                                     JOIN userPersona uper ON uper.userID = u.userID
                                     JOIN persona p ON uper.personaID = p.personaID
                                     JOIN personaScenario ps ON p.personaID = ps.personaID
                                     JOIN scenario s ON ps.scenarioID = s.scenarioID
                                     JOIN projectArtifact pa ON ass.projectArtifactID = pa.projectArtifactID
                                     JOIN project pjt ON pjt.projectID = pa.projectID
                                     JOIN artifact a ON a.artifactID = pa.artifactID
                                     JOIN configuration con ON con.configurationID = ass.configurationID
                                     WHERE u.userID = ass.userID
                                     AND p.personaID = ass.personaID
                                     AND s.scenarioID = ass.scenarioID ORDER BY completionDate DESC");
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