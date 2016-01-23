<?php
    require_once "../dbconnect.php";

    try {
        $arrayToSend = [];
        $dbq = db_connect();
        $pre_result = $dbq->prepare("SELECT pjt.projectName as project,
                                     a.artifactName as artifact,
                                     p.personaName as persona,
                                      s.scenarioName as scenario,
                                     CONCAT(u.firstName, ' ', u.lastName) as name,
                                     ass.isComplete as complete,
                                     ass.completionDate as completionDate,
                                     u.email as email,
                                     ass.assessmentID as assessmentID,
                                     ass.ratingUrl as ratingUrl
                                     FROM assessment ass
                                     join user u on ass.userID = u.userID
                                     join userPersona uper on uper.userID = u.userID
                                     join persona p on uper.personaID = p.personaID
                                     join personaScenario ps on p.personaID = ps.personaID
                                     join scenario s on ps.scenarioID = s.scenarioID
                                     join projectArtifact pa on ass.projectArtifactID = pa.projectArtifactID
                                     join project pjt on pjt.projectID = pa.projectID
                                     join artifact a on a.artifactID = pa.artifactID
                                     where u.userID = ass.userID
                                     and p.personaID = ass.personaID
                                     and s.scenarioID = ass.scenarioID order by completionDate DESC");
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