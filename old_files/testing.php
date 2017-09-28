<?php

require_once "dbconnect.php";

try {
    $dbq = db_connect();
    $dbq->setAttribute (PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $addAssessment = $dbq->prepare("CALL addAssessment(:userID, :configurationID, @assessmentID)");
    $addAssessment->bindValue(':userID', 57, PDO::PARAM_INT);
    $addAssessment->bindValue(':configurationID', 120, PDO::PARAM_INT);
    $addAssessment->execute();
    $addAssessment->closeCursor();

    $assessmentID = $dbq->query('SELECT @assessmentID')->fetchColumn();

    $hash = hash('sha256', $assessmentID);

    $targetURL = "rater.php?&asid=" . $hash;
    $fullUrl = $root_url;
    $fullUrl .= $targetURL;

    $addHash = $dbq->prepare("UPDATE assessment SET assessmentIDHashed = :hash, ratingUrl = :url where assessmentID = :assessmentID");
    $addHash->bindValue(':hash', $hash, PDO::PARAM_STR);
    $addHash->bindValue(':url', $fullUrl, PDO::PARAM_STR);
    $addHash->bindValue(':assessmentID', $assessmentID, PDO::PARAM_INT);
    $addHash->execute();

    return $targetURL;

} catch (PDOException $e) {
    echo $e;
}

