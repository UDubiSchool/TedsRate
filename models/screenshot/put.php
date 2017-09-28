<?php
    //add or update a screenshot for a given assessment

    if(!isset($_POST['path']) || !isset($_POST['ratingID'])) {
        exit;
    }

    $path = $_POST['path'];
    $ratingID = intval($_POST['ratingID']);



    $stmt = $dbq->prepare("CALL addScreenshot(:path, :ratingID, @screenshotID)");
    $stmt->bindValue(':path', $path, PDO::PARAM_STR);
    $stmt->bindValue(':ratingID', $ratingID, PDO::PARAM_INT);
    $stmt->execute();

    $screenshotID = $dbq->query('SELECT @screenshotID')->fetchColumn();
    $data['screenshotID'] = $screenshotID;

    header('Content-Type: application/json');
    echo json_encode($data, TRUE);
?>