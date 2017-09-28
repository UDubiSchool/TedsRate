<?php

    if(!isset($_POST['userID']) || !isset($_POST['configurationID'])) {
        exit;
    }

    $userID = $_POST['userID'];
    $configurationID = $_POST['configurationID'];


    $getAssessment = $dbq->prepare("SELECT * FROM assessment
                            LEFT JOIN user ON user.userID = assessment.userID
                            WHERE assessment.userID = :userID
                            AND assessment.configurationID = :configurationID
                            AND user.authorityLevel != 2");
    $getAssessment->bindValue(':userID', $userID, PDO::PARAM_STR);
    $getAssessment->bindValue(':configurationID', $configurationID, PDO::PARAM_STR);
    $getAssessment->execute();
    $getAssessment = $getAssessment->fetch();

    $numberOfRows = $dbq->query("SELECT FOUND_ROWS();");
    $numberOfRows = $numberOfRows->fetchColumn();

    if ($numberOfRows == 1) {
        $data['assessment'] = [
            'assessmentID' => $getAssessment['assessmentID'],
            'assessmentIDHashed' => $getAssessment['assessmentIDHashed'],
            'userID' => $getAssessment['userID'],
            'configurationID' => $getAssessment['configurationID']
        ];
    } else {
        $data['assessment'] = false;
    }

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
    //close connection
    $dbq = NULL;

?>