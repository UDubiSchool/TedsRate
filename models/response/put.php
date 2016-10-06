<?php
    //add or update a response for a given assessment

    if(!isset($_POST['question']) || !isset($_POST['assessmentID'])) {
        exit;
    }

    $question = $_POST['question'];

    $questionID = intval($question['questionID']);
    $assessmentID = intval($_POST['assessmentID']);
    $response = $question['response'];
    if(is_array($response)) {
        $response = json_encode($response, true);
    }



    $stmt = $dbq->prepare("CALL addResponse(:responseAnswer, :questionID, :assessmentID, @responseID)");
    $stmt->bindValue(':responseAnswer', $response, PDO::PARAM_STR);
    $stmt->bindValue(':questionID', $questionID, PDO::PARAM_INT);
    $stmt->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
    $stmt->execute();

    $responseID = $dbq->query('SELECT @responseID')->fetchColumn();
    $data['responseID'] = $responseID;

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
?>