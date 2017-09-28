<?php
    //add or update a rating for a given assessment

    if(!isset($_POST['attribute']) || !isset($_POST['assessmentID'])) {
        exit;
    }

    $attribute = $_POST['attribute'];

    $attributeID = intval($attribute['attributeID']);
    $assessmentID = intval($_POST['assessmentID']);



    $stmt = $dbq->prepare("CALL addRating(:ratingValue, :attributeID, :assessmentID, @ratingID)");
    $stmt->bindValue(':ratingValue', $attribute['ratingValue'], PDO::PARAM_STR);
    $stmt->bindValue(':attributeID', $attributeID, PDO::PARAM_INT);
    $stmt->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
    $stmt->execute();

    $ratingID = $dbq->query('SELECT @ratingID')->fetchColumn();
    $data['ratingID'] = $ratingID;

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
?>