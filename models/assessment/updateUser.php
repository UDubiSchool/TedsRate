<?php

    if(!isset($_POST['userID']) || !isset($_POST['assessmentID'])) {
        exit;
    }

    $userID = intval($_POST['userID']);
    $assessmentID = intval($_POST['assessmentID']);


    $updateAssessment = $dbq->prepare("UPDATE assessment SET userID =:userID WHERE assessmentID =:assessmentID");
    $updateAssessment->bindValue(':userID', $userID, PDO::PARAM_INT);
    $updateAssessment->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
    $updateAssessment->execute();
    // $updateAssessment = $updateAssessment->fetch();

    $numberOfRows = $dbq->query("SELECT ROW_COUNT();");
    $numberOfRows = $numberOfRows->fetchColumn();

    if ($numberOfRows == 1) {
        $data['updated'] = true;
    } else {
        $data['updated'] = false;
    }

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
    //close connection
    $dbq = NULL;

?>