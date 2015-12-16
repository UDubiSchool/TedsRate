<?php
    // require_once "../session_inc.php";
    // require_once "../header.inc.php";
    require_once "../dbconnect.php";

    //set up some SQL statements
    $sql["project"] = 'SELECT * from project';
    $sql["project_atft"] = 'SELECT * FROM projectArtifact pa
                            join project p on p.projectID = pa.projectID
                            join artifact a on a.artifactID = pa.artifactID';
    $sql["persona"] = 'select personaeID as perid, personaTitle as perTitle from personae';

    try {
        $arrayToSend = [];
        $dbq = db_connect();
        $pre_result = $dbq->prepare("SELECT pjt.projectTitle as project, a.artifactTitle as artifact,
                                     p.personaTitle as persona, s.scenarioTitle as scenario,
                                     CONCAT(upro.firstName, ' ', upro.lastName) as userprofile,
                                     urp.isComplete as complete,
                                     urp.completionDate as completionDate,
                                     upro.email as email,
                                     urp.userRatingProgressID as urpID,
                                     urp.ratingUrl as ratingUrl
                                     FROM userRatingProgress urp
                                     join userProfile upro on urp.userID = upro.userID
                                     join userPersonae uper on uper.userID = upro.userID
                                     join personae p on uper.personaeID = p.personaeID
                                     join personaScenario ps on p.personaeID = ps.personaID
                                     join scenario s on ps.scenarioID = s.scenarioID
                                     join projectArtifact pa on urp.projectArtifactID = pa.projectArtifactID
                                     join project pjt on pjt.projectID = pa.projectID
                                     join artifact a on a.artifactID = pa.artifactID
                                     where upro.userID = urp.userID
                                     and p.personaeID = urp.personaID
                                     and s.scenarioID = urp.scenarioID order by completionDate DESC");
        $pre_result->execute();
        while ($row = $pre_result->fetch(PDO::FETCH_ASSOC)) {
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