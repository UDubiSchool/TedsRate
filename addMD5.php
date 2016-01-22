<?php
    require 'dbconnect.php';
    $dbq = db_connect();
    $getAssessments = $dbq->query("SELECT assessmentID from assessment");
    while ($row = $getAssessments->fetch(PDO::FETCH_ASSOC)) {
        $hash = hash('sha256', $row['assessmentID']);
        // $addHash = $dbq->query("UPDATE assessment SET assessmentIDHashed = $hash");
        $addHash = $dbq->prepare("UPDATE assessment SET assessmentIDHashed = :hash where assessmentID = :assessmentID");
        $addHash->bindValue(':hash', $hash, PDO::PARAM_STR);
        $addHash->bindValue(':assessmentID', $row['assessmentID'], PDO::PARAM_INT);
        $addHash->execute();
    }
?>