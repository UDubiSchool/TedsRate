<?php

    if(!isset($_POST['assessmentID'])) {
        exit;
    }

    $assessmentID = intval($_POST['assessmentID']);


    $updateAssessment = $dbq->prepare("DELETE FROM assessment WHERE assessmentID =:assessmentID");
    $updateAssessment->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
    $updateAssessment->execute();

    $numberOfRows = $dbq->query("SELECT ROW_COUNT();");
    $numberOfRows = $numberOfRows->fetchColumn();

    if ($numberOfRows == 1) {
        $data['deleted'] = true;
    } else {
        $data['deleted'] = false;
    }

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
    //close connection
    $dbq = NULL;

?>