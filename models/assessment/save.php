<?php

    if(!isset($_POST['assessment'])) {
        exit;
    }

    $assessment = $_POST['assessment'];


    foreach($assessment['criteria'] as $criterionKey => $criterion) {
        foreach ($criterion['attributes'] as $attributeKey => $attribute) {
            $addRating = $dbq->prepare("CALL addRating(:ratingValue, :attributeID, :assessmentID, @ratingID)");
            $addRating->bindValue(':ratingValue', $v, PDO::PARAM_STR);
            $addRating->bindValue(':attributeID', $categoryID, PDO::PARAM_INT);
            $addRating->bindValue(':assessmentID', $ids['assessmentID'], PDO::PARAM_INT);
            $addRating->execute();

            $ratingID = $dbq->query('SELECT @ratingID')->fetchColumn();

        }
    }

    // $updateAssessment = $dbq->prepare("UPDATE assessment SET userID =:userID WHERE assessmentID =:assessmentID");
    // $updateAssessment->bindValue(':userID', $userID, PDO::PARAM_INT);
    // $updateAssessment->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
    // $updateAssessment->execute();
    // // $updateAssessment = $updateAssessment->fetch();

    // $numberOfRows = $dbq->query("SELECT ROW_COUNT();");
    // $numberOfRows = $numberOfRows->fetchColumn();

    // if ($numberOfRows == 1) {
    //     $data['updated'] = true;
    // } else {
    //     $data['updated'] = false;
    // }

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
    //close connection
    $dbq = NULL;

?>